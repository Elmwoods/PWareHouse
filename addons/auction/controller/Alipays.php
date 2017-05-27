<?php
namespace addons\auction\controller;
use think\addons\Controller;
use wstmart\common\model\Payments as PM;
use addons\auction\model\Auctions as M;
use wstmart\common\model\Orders as OM;
use wstmart\common\model\LogMoneys as LM;
use addons\auction\model\Auctions as AM;
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
class Alipays extends Controller{

	public function __construct(){
		parent::__construct();
		$m = new M();
		$data = $m->getConf('Auction');
		$this->assign("seoAuctionKeywords",$data['seoAuctionKeywords']);
		$this->assign("seoAuctionDesc",$data['seoAuctionDesc']);
	}
	/**
	 * 初始化
	 */
	private $aliPayConfig;
	public function _initialize() {
		$this->aliPayConfig = array();
		$m = new PM();
		$this->aliPayConfig = $m->getPayment("alipays");
	}
	
	/**
	 * 生成支付代码
	 */
	function getAlipaysUrl(){
		$payObj = input("payObj/s");
		$payFrom = (int)input("payFrom/s");
		$m = new OM();
		$am = new AM();
		$obj = array();
		$data = array();
		$orderAmount = 0;
		$out_trade_no = WSTOrderNo();
		$extra_common_param = "";
		$subject = "";
		$body = "";
		$auctionId = input("auctionId/d",0);
		$userId = (int)session('WST_USER.userId');
		if($payObj=="bao"){//保证金
			$auction = $am->getUserAuction($auctionId);
			$orderAmount = $auction["cautionMoney"];
			if($auction["userId"]>0){
				$data["status"] = -1;
				$data["msg"] = "您已缴保证金";
			}else{
				$data["status"] = $orderAmount>0?1:-1;
				$data["msg"] = ($data["status"]==-1)?"无需支付保证金":"";
				$extra_common_param = $payObj."@".$userId."@".$auctionId;
				$subject = '拍卖保证金 ¥'.$orderAmount.'元';
				$body = '支付保证金';
			}
		}else{
			
			$auction = $am->getAuctionPay($auctionId);
			if($auction["endPayTime"]<date("Y-m-d H:i:s")){
				$data["status"] = -1;
				$data["msg"] = "您已过拍卖支付货款期限";
			}else{
				$orderAmount = $auction["payPrice"];
				$subject = '拍卖货款 ¥'.$orderAmount.'元';
				$body = '支付拍卖货款';
				if($auction["isPay"]==1){
					$data["status"] = -1;
					$data["msg"] = "您已缴拍卖货款";
				}else{
					$data["status"] = $orderAmount>0?1:-1;
					$data["msg"] = ($data["status"]==-1)?"无需支付拍卖货款":"";
					$extra_common_param = $payObj."@".$userId."@".$auctionId;
				}
			}
		}
		
		if($data["status"]==1){
			$return_url = addon_url("auction://alipays/response","",true,true);
			$notify_url = addon_url("auction://alipays/aliNotify","",true,true);
			$parameter = array(
					'extra_common_param'=> $extra_common_param,
					'service'           => 'create_direct_pay_by_user',
					'partner'           => $this->aliPayConfig['parterID'],
					'_input_charset'    => "utf-8",
					'notify_url'        => $notify_url,
					'return_url'        => $return_url,
					/* 业务参数 */
					'subject'           => $subject,
					'body'  	        => $body,
					'out_trade_no'      => $out_trade_no,
					'total_fee'         => $orderAmount,
					'quantity'          => 1,
					'payment_type'      => 1,
					/* 物流参数 */
					'logistics_type'    => 'EXPRESS',
					'logistics_fee'     => 0,
					'logistics_payment' => 'BUYER_PAY_AFTER_RECEIVE',
					/* 买卖双方信息 */
					'seller_email'      => $this->aliPayConfig['payAccount']
			);
			ksort($parameter);
			reset($parameter);
			$param = '';
			$sign  = '';
			foreach ($parameter AS $key => $val){
				$param .= "$key=" .urlencode($val). "&";
				$sign  .= "$key=$val&";
			}
			$param = substr($param, 0, -1);
			$sign  = substr($sign, 0, -1). $this->aliPayConfig['parterKey'];
			$url = 'https://mapi.alipay.com/gateway.do?'.$param. '&sign='.md5($sign).'&sign_type=MD5';
			$data["url"] = $url;
		}

		return $data;
	}
	
	/**
	 * 支付结果同步回调
	 */
	function response(){
		$m = new OM();
		$request = $_GET;
		unset($request['_URL_']);
		$payRes = self::notify($request);
		if($payRes['status']){
			$extras = explode("@",$_GET['extra_common_param']);
			$auctionId = (int)$extras [2];
			if($extras[0]=="bao"){//保证金
				return $this->fetch("/home/index/pay_success");
			}else{
				$this->redirect(addon_url("auction://users/checkPayStatus",array("id"=>$auctionId),true,true));
			}
		}else{
			session('0001','支付失败');
        	$this->redirect('home/error/message',['code'=>'0001']);
		}
	}
	
	/**
	 * 支付结果异步回调
	 */
	function aliNotify(){
		
		$request = $_POST;
		$payRes = self::notify($request);
		if($payRes['status']){
			
			$extras = explode("@",$_POST['extra_common_param']);
			$rs = array();
			
			$userId = (int)$extras [1];
			$auctionId = (int)$extras [2];
			$obj = array ();
			$obj["trade_no"] = $_POST['trade_no'];
			$obj["out_trade_no"] = $_POST["out_trade_no"];;
			$obj["userId"] = $userId;
			$obj["auctionId"] = $auctionId;
			$obj["total_fee"] = $_POST['total_fee'];
			$obj["payFrom"] = 'alipays';
			$obj["payObj"] = $extras[0];
			// 支付成功业务逻辑
			$m = new AM();
			$rs = $m->complateCautionMoney ( $obj );
			
			if($rs["status"]==1){
				echo 'success';
			}else{
				echo 'fail';
			}
		}else{
			echo 'fail';
		}
	}
	
	/**
	 * 支付回调接口
	 */
	function notify($request){
		$returnRes = array('info'=>'','status'=>false);
		$request = $this->argSort($request);
		// 检查数字签名是否正确 
		$isSign = $this->getSignVeryfy($request);
		if (!$isSign){//签名验证失败
			$returnRes['info'] = '签名验证失败';
			return $returnRes;
		}
		if ($request['trade_status'] == 'TRADE_SUCCESS' || $request['trade_status'] == 'TRADE_FINISHED'){
			$returnRes['status'] = true;
		}
		return $returnRes;
	}
	
	/**
	 * 获取返回时的签名验证结果
	 */
	function getSignVeryfy($para_temp) {
		$parterKey = $this->aliPayConfig["parterKey"];
		//除去待签名参数数组中的空值和签名参数
		$para_filter = $this->paraFilter($para_temp);
		//对待签名参数数组排序
		$para_sort = $this->argSort($para_filter);
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = $this->createLinkstring($para_sort);
	
		$isSgin = false;
		$isSgin = $this->md5Verify($prestr, $para_temp['sign'], $parterKey);
		return $isSgin;
	}
	
	/**
	 * 验证签名
	 */
	function md5Verify($prestr, $sign, $key) {
		$prestr = $prestr . $key;
		$mysgin = md5($prestr);
		if($mysgin == $sign) {
			return true;
		}else {
			return false;
		}
	}
	
	/**
	 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
	 */
	function createLinkstring($para) {
		$arg  = "";
		while (list ($key, $val) = each ($para)) {
			$arg.=$key."=".$val."&";
		}
		//去掉最后一个&字符
		$arg = substr($arg,0,count($arg)-2);
		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
	
		return $arg;
	}
	
	/**
	 * 除去数组中的空值和签名参数
	 */
	function paraFilter($para) {
		$para_filter = array();
		while (list ($key, $val) = each ($para)) {
			if($key == "sign" || $key == "sign_type" || $val == "")continue;
			else    $para_filter[$key] = $para[$key];
		}
		return $para_filter;
	}
	
	/**
	 * 对数组排序
	 */
	function argSort($para) {
		ksort($para);
		reset($para);
		return $para;
	}

}
