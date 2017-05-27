<?php
namespace wstmart\home\controller;
use wstmart\common\model\Payments as M;
use wstmart\common\model\Orders as OM;
use wstmart\common\model\LogMoneys as LM;
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
class Alipays extends Base{

	/**
	 * 初始化
	 */
	private $aliPayConfig;
	public function _initialize() {
		$this->aliPayConfig = array();
		$m = new M();
		$this->aliPayConfig = $m->getPayment("alipays");
	}
	
	/**
	 * 生成支付代码
	 */
	function getAlipaysUrl(){
		$payObj = input("payObj/s");
		$m = new OM();
		$obj = array();
		$data = array();
		$orderAmount = 0;
		$out_trade_no = "";
		$extra_common_param = "";
		$subject = "";
		$body = "";
		if($payObj=="recharge"){//充值
			$orderAmount = input("needPay/d");
			$targetType = (int)input("targetType/d");
			$targetId = (int)session('WST_USER.userId');
			if($targetType==1){//商家
				$targetId = (int)session('WST_USER.shopId');
			}
			$data["status"] = $orderAmount>0?1:-1;
			$out_trade_no = WSTOrderNo();
			$extra_common_param = $payObj."@".$targetId."@".$targetType;
			$subject = '钱包充值 ¥'.$orderAmount.'元';
			$body = '钱包充值';
		}else{
			$obj["orderNo"] = input("orderNo/s");
			$obj["isBatch"] = (int)input("isBatch/d");
			$data = $m->checkOrderPay($obj);
			if($data["status"]==1){
				$userId = (int)session('WST_USER.userId');
				$obj["userId"] = $userId;
				$order = $m->getPayOrders($obj);
				$orderAmount = $order["needPay"];
				$payRand = $order["payRand"];
				$out_trade_no = $obj["orderNo"]."a".$payRand;
				$extra_common_param = $payObj."@".$userId."@".$obj["isBatch"];
				$subject = '支付购买商品费用'.$orderAmount.'元';
				$body = '支付订单费用';
			}
		}
		
		if($data["status"]==1){
			$return_url = url("home/alipays/response","",true,true);
			$notify_url = url("home/alipays/aliNotify","",true,true);
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
			if($extras[0]=="recharge"){//充值
				$this->redirect(url("home/logmoneys/shopmoneys"));
			}else{
				$this->redirect(url("home/orders/waitReceive"));
			}
		}else{
			$this->error('支付失败');
		}
	}
	
	/**
	 * 支付结果异步回调
	 */
	function aliNotify(){
		$m = new OM();
		$request = $_POST;
		$payRes = self::notify($request);
		if($payRes['status']){
			
			$extras = explode("@",$_POST['extra_common_param']);
			$rs = array();
			if($extras[0]=="recharge"){//充值
				$targetId = (int)$extras [1];
				$targetType = (int)$extras [2];
				$obj = array ();
				$obj["trade_no"] = $_POST['trade_no'];
				$obj["out_trade_no"] = $_POST["out_trade_no"];;
				$obj["targetId"] = $targetId;
				$obj["targetType"] = $targetType;
				$obj["total_fee"] = $_POST['total_fee'];
				$obj["payFrom"] = 'alipays';
				// 支付成功业务逻辑
				$m = new LM();
				$rs = $m->complateRecharge ( $obj );
			}else{
				//商户订单号
				$obj = array();
				$tradeNo = explode("a",$_POST['out_trade_no']);
				$obj["trade_no"] = $_POST['trade_no'];
				$obj["out_trade_no"] = $tradeNo[0];
				$obj["total_fee"] = $_POST['total_fee'];
					
				$obj["userId"] = $extras[1];
				$obj["isBatch"] = $extras[2];
				$obj["payFrom"] = 'alipays';
				//支付成功业务逻辑
				$rs = $m->complatePay($obj);
			}
			
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
