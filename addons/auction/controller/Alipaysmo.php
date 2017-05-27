<?php
namespace addons\auction\controller;
use think\Loader;
use think\addons\Controller;
use wstmart\common\model\Payments as PM;
use addons\auction\model\Auctions as M;
use wstmart\common\model\Orders as OM;
use wstmart\common\model\LogMoneys as LM;
use addons\auction\model\Auctions as AM;
use wstmart\common\model\LogPayParams as LPM;
use wstmart\common\model\wstmart\common\model;
/**
 * ============================================================================
 * WSTMart多用户商城
 * 版权所有 2016-2066 广州商淘信息科技有限公司，并保留所有权利。
 * 官网地址:http://www.wstmart.net
 * 交流社区:http://bbs.shangtaosoft.com
 * 联系QQ:153289970
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！未经本公司授权您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 阿里支付控制器
 */
class Alipaysmo extends Controller{

	/**
	 * 初始化
	 */
	private $alipayConfig;
	public function _initialize() {
		header ("Content-type: text/html; charset=utf-8");
		Loader::import ( 'alipay.Corefunction' );
    	Loader::import ( 'alipay.Md5function' );
    	Loader::import ( 'alipay.AlipayNotify' );
    	Loader::import ( 'alipay.AlipaySubmit' );
    	$m = new PM();
    	$payment = $m->getPayment("alipays");
    	$this->alipayConfig = array(
    			'partner' =>$payment['parterID'],   //这里是你在成功申请支付宝接口后获取到的PID；
    			'key'=>$payment['parterKey'],//这里是你在成功申请支付宝接口后获取到的Key
    			'seller_email'=>$payment['payAccount'],
    			'sign_type'=>strtoupper('MD5'),
    			'input_charset'=> strtolower('utf-8'),
    			'cacert'=>'',
    			'transport'=> 'http'
    	);
	}
	
	public function getAlipaysUrl(){
		$am = new AM();
		$payObj = input("payObj/s");
		$data = array();
		$auctionId = input("auctionId/d",0);
		if($payObj=="bao"){
			$auction = $am->getUserAuction($auctionId);
			$orderAmount = $auction["cautionMoney"];
			if($auction["userId"]>0){
				$data["status"] = -1;
				$data["msg"] = "您已缴保证金";
			}else{
				$data["status"] = $orderAmount>0?1:-1;
				$data["msg"] = ($data["status"]==-1)?"无需支付保证金":"";
			}
		}else{
			$auction = $am->getAuctionPay($auctionId);
			if($auction["endPayTime"]<date("Y-m-d H:i:s")){
				$data["status"] = -1;
				$data["msg"] = "您已过拍卖支付货款期限";
			}else{
				$orderAmount = $auction["payPrice"];
				$userId = (int)session('WST_USER.userId');
				if($auction["isPay"]==1){
					$data["status"] = -1;
					$data["msg"] = "您已缴拍卖货款";
				}else{
					$data["status"] = $orderAmount>0?1:-1;
					$data["msg"] = ($data["status"]==-1)?"无需支付拍卖货款":"";
				}	
			}
		}
		return $data;
	}
	
    /**
     * 支付宝支付跳转方法
     */
    public function toAliPay(){
    	$payObj = input("payObj/s");
    	$am = new AM();
    	$obj = array();
    	$data = array();
    	$orderAmount = 0;
    	$auctionId = input("auctionId/d",0);
    	$userId = (int)session('WST_USER.userId');
    	$call_back_url = "";
    	
    	if($payObj=="bao"){//充值
    		$auction = $am->getUserAuction($auctionId);
    		$orderAmount = $auction["cautionMoney"];
    		if($auction["userId"]>0){
    			$data["status"] = -1;
                session('0001','您已缴保证金');
                $this->redirect('home/error/message',['code'=>'0001']);
    		}else{
    			$data["status"] = $orderAmount>0?1:-1;
    			$data["msg"] = ($data["status"]==-1)?"无需支付保证金":"";
    		}
    		$call_back_url = addon_url("auction://goods/modetail",array("id"=>$auctionId),true,true);
    		
    	}else{
    		$auction = $am->getAuctionPay($auctionId);
    		if($auction["endPayTime"]<date("Y-m-d H:i:s")){
    			$data["status"] = -1;
    			$data["msg"] = "您已过拍卖支付货款期限";
    		}else{
	    		$orderAmount = $auction["payPrice"];
	    		if($auction["isPay"]==1){
	    			$data["status"] = -1;
	    			$data["msg"] = "您已缴拍卖货款";
	    		}else{
	    			$data["status"] = $orderAmount>0?1:-1;
	    			$data["msg"] = ($data["status"]==-1)?"无需支付拍卖货款":"";
	    		}
	    		$call_back_url = addon_url("auction://users/mocheckPayStatus",array("id"=>$auctionId),true,true);
    		}
    	}
    	
    	$jsonParams = array();
    	$jsonParams["payObj"] = $payObj;
    	$jsonParams["userId"] = $userId;
    	$jsonParams["auctionId"] = $auctionId;
    	$notify_url = addon_url("auction://alipaysmo/aliNotify","",true,true);
    	
    	if($data["status"]==1){
	    
	    	$format = "xml";
	    	$v = "2.0";
	    	$req_id = date('Ymdhis');
	    	
	    	$subject = ($payObj=="bao")?'支付保证金':'支付拍卖货款';
	    	$merchant_url = "";
	    	$seller_email = $this->alipayConfig['seller_email'];
	    	
	    	$total_fee = $orderAmount;
	    	$out_trade_no = WSTOrderNo();
	    	$req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . $merchant_url . '</merchant_url></direct_trade_create_req>';
	    
	    	//构造要请求的参数数组，无需改动
	    	$para_token = array(
	    		"service" => "alipay.wap.trade.create.direct",
	    		"partner" => trim($this->alipayConfig['partner']),
	    		"sec_id" => trim($this->alipayConfig['sign_type']),
	    		"format"	=> $format,
	    		"v"	=> $v,
	    		"req_id"	=> $req_id,
	    		"req_data"	=> $req_data,
	    		"_input_charset"	=> trim(strtolower($this->alipayConfig['input_charset']))
	    	);
	    	//建立请求
	    	$alipaySubmit = new \AlipaySubmit($this->alipayConfig);
	    	$html_text = $alipaySubmit->buildRequestHttp($para_token);
	    	//URLDECODE返回的信息
	    	$html_text = urldecode($html_text);
	    	//解析远程模拟提交后返回的信息
	    	$para_html_text = $alipaySubmit->parseResponse($html_text);
	    	//获取request_token
	    	$request_token = $para_html_text['request_token'];
	    	//**************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute**************************
	    	//业务详细
	    	$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
	    	//必填
	
	    	//构造要请求的参数数组，无需改动
	    	$parameter = array(
	    		"service" => "alipay.wap.auth.authAndExecute",
	    		"partner" => trim($this->alipayConfig['partner']),
	    		"sec_id" => trim($this->alipayConfig['sign_type']),
	    		"format"	=> $format,
	    		"v"	=> $v,
	    		"req_id"	=> $req_id,
	    		"req_data"	=> $req_data,
	    		"_input_charset"	=> trim(strtolower($this->alipayConfig['input_charset']))
	    	);
	    	$m = new LPM();
	    	$obj = array();
	    	$obj["userId"] = $userId;
	    	$obj["transId"] = $out_trade_no;
	    	$obj["paramsVa"] = json_encode($jsonParams);
	    	$m->addPayLog($obj);
	    	//建立请求
	    	$alipaySubmit = new \AlipaySubmit($this->alipayConfig);
	    	$html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '');
	    	echo $html_text;
    	}else{
    		echo "<span style='font-size:40px;'>".$data["msg"]."</span>";
    		return;
    	}
    }
    
    /**
     * 服务器异步通知页面方法
     *
     */
    function aliNotify() {
    	$om = new OM();
    	// 计算得出通知验证结果
    	$alipayNotify = new \AlipayNotify ( $this->alipayConfig );
    	$verify_result = $alipayNotify->verifyNotify ();
    	
    	if ($verify_result) {
    		$notify_data = $_POST['notify_data'];
    		// 获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
    		// 解析notify_data
    		// 注意：该功能PHP5环境及以上支持，需开通curl、SSL等PHP配置环境。建议本地调试时使用PHP开发软件
    		$doc = new \DOMDocument ();
    		$doc->loadXML ( $notify_data );
    		if (! empty ( $doc->getElementsByTagName ( "notify" )->item ( 0 )->nodeValue )) {
    			// 交易号
    			$trade_no = $doc->getElementsByTagName ( "trade_no" )->item ( 0 )->nodeValue;
    			// 商户订单号
    			$out_trade_no = $doc->getElementsByTagName ( "out_trade_no" )->item ( 0 )->nodeValue;
    
    			$total_fee = $doc->getElementsByTagName( "total_fee" )->item(0)->nodeValue;
    			// 支付宝交易号
    			$trade_no = $doc->getElementsByTagName ( "trade_no" )->item ( 0 )->nodeValue;
    			// 交易状态
    			$trade_status = $doc->getElementsByTagName ( "trade_status" )->item ( 0 )->nodeValue;
    			if ($trade_status == 'TRADE_FINISHED' OR $trade_status  == 'TRADE_SUCCESS') {
    				
    				$m = new LPM();
    				$obj = array();
    				$obj["transId"] = $out_trade_no;
    				$params = $m->getPayLog($obj);
    				
    				$obj = array ();
    				$obj["trade_no"] = $trade_no;
      				$obj["out_trade_no"] = $out_trade_no;
    				$obj["total_fee"] = $total_fee;
    				
    				$obj["userId"] = (int)$params["userId"];
    				$obj["auctionId"] = (int)$params["auctionId"];
    				$obj["payObj"] = $params["payObj"];
    				$obj["payFrom"] = 'alipays';
    				// 支付成功业务逻辑
    				$m = new AM();
    				$rs = $m->complateCautionMoney ( $obj );
    				
    				if($rs["status"]==1){
    					echo 'success';
    				}else{
    					echo 'fail';
    				}
    			}
    			echo "success"; // 请不要修改或删除
    		}
    	} else {
    		// 验证失败
    		echo "fail";
    	}
    }
	
}
