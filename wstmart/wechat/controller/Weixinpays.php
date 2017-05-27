<?php
namespace wstmart\wechat\controller;
use think\Loader;
use wstmart\common\model\Payments as M;
use wstmart\common\model\Orders as OM;
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
 * 微信支付控制器
 */
class Weixinpays extends Base{
	
	/**
	 * 初始化
	 */
	private $wxpayConfig;
	private $wxpay;
	public function _initialize() {
		header ("Content-type: text/html; charset=utf-8");
		Loader::import('wxpay.WxPayConf');
		Loader::import('wxpay.WxJsApiPay');
		
		$this->wxpayConfig = array();
		$m = new M();
		$this->wxpay = $m->getPayment("weixinpays");
		$this->wxpayConfig['appid'] = $this->wxpay['appId']; // 微信公众号身份的唯一标识
		$this->wxpayConfig['appsecret'] = $this->wxpay['appsecret']; // JSAPI接口中获取openid
		$this->wxpayConfig['mchid'] = $this->wxpay['mchId']; // 受理商ID
		$this->wxpayConfig['key'] = $this->wxpay['apiKey']; // 商户支付密钥Key
		$this->wxpayConfig['notifyurl'] = url("wechat/weixinpays/notify","",true,true);
		$this->wxpayConfig['curl_timeout'] = 30;
		$this->wxpayConfig['returnurl'] = url("wechat/orders/index","",true,true);
		// 初始化WxPayConf
		new \WxPayConf($this->wxpayConfig);
	}
	

	public function toPay(){
	    $data = [];
        $data['orderNo'] = input('orderNo');
        $data['isBatch'] = (int)input('isBatch');
        $data['userId'] = (int)session('WST_USER.userId');
		$m = new OM();
		$rs = $m->getOrderPayInfo($data);
		if(empty($rs)){
			$this->assign('type','');
			return $this->fetch("users/orders/orders_list");
		}else{
			$pkey = base64_decode(input("pkey"));
			$extras =  explode ( "@",$pkey);
			$openid = session('WST_USER.wxOpenId');
			$m = new OM();
			$userId = (int)session('WST_USER.userId');
			$obj["userId"] = $userId;
			$obj["orderNo"] = input("orderNo");
			$obj["isBatch"] = (int)input("isBatch");
	
			$rs = $m->getByUnique();
			$this->assign('rs',$rs);
			
			$order = $m->getPayOrders($obj);
			$needPay = $order["needPay"];
			$payRand = $order["payRand"];
			//使用jsapi接口
			$jsApi = new \JsApi();
			//使用统一支付接口
			$unifiedOrder = new \UnifiedOrder();
			$unifiedOrder->setParameter("openid",$openid);//商品描述
		
			//自定义订单号，此处仅作举例
			$unifiedOrder->setParameter("out_trade_no",$obj["orderNo"]."_".$payRand);//商户订单号
			$unifiedOrder->setParameter("notify_url",$this->wxpayConfig ['notifyurl']);//通知地址
			$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
		
			$unifiedOrder->setParameter("body","支付订单");//商品描述
			$needPay = WSTBCMoney($needPay,0,2);
			$unifiedOrder->setParameter("total_fee", $needPay * 100);//总金额
			$userId = (int)session('WST_USER.userId');
			$attach = $userId."@".$obj["orderNo"]."@".$obj["isBatch"];
			$this->assign('needPay',$needPay);
			$this->assign('returnUrl',$this->wxpayConfig ['returnurl'] );
			 
			$unifiedOrder->setParameter("attach",$attach);//附加数据
		
			$prepay_id = $unifiedOrder->getPrepayId();
			//=========步骤3：使用jsapi调起支付============
			$jsApi->setPrepayId($prepay_id);
		
			$jsApiParameters = $jsApi->getParameters();
			$this->assign('jsApiParameters',$jsApiParameters);
		}
		return $this->fetch('users/orders/orders_pay');
	}
	
	
	public function toAddonPay() {
		
		$this->assign('payObj',session("addonPay.payObj"));
		$this->assign('object',session("addonPay.object"));
		$this->assign('needPay',session("addonPay.needPay"));
		$this->assign('returnUrl',session("addonPay.returnUrl"));
		$this->assign('jsApiParameters',session("addonPay.jsApiParameters"));
		return $this->fetch(session("addonPay.showUrl"));
		
	}
	
	
	
	public function notify() {
		// 使用通用通知接口
		$notify = new \Notify();
		// 存储微信的回调
		$xml = $GLOBALS ['HTTP_RAW_POST_DATA'];
		$notify->saveData ( $xml );
		if ($notify->checkSign () == FALSE) {
			$notify->setReturnParameter ( "return_code", "FAIL" ); // 返回状态码
			$notify->setReturnParameter ( "return_msg", "签名失败" ); // 返回信息
		} else {
			$notify->setReturnParameter ( "return_code", "SUCCESS" ); // 设置返回码
		}
		$returnXml = $notify->returnXml ();
		if ($notify->checkSign () == TRUE) {
			if ($notify->data ["return_code"] == "FAIL") {
				// 此处应该更新一下订单状态，商户自行增删操作
			} elseif ($notify->data ["result_code"] == "FAIL") {
				// 此处应该更新一下订单状态，商户自行增删操作
			} else {
				$order = $notify->getData ();
				$rs = $this->process($order);
				if($rs["status"]==1){
					echo "SUCCESS";
				}else{
					echo "FAIL";
				}
			}
		}
	}
	
	//订单处理
	private function process($order) {
	
		$obj = array();
		$obj["trade_no"] = $order['transaction_id'];
		 
		$obj["total_fee"] = (float)$order["total_fee"]/100;
		$extras =  explode ( "@", $order ["attach"] );
		$obj["userId"] = $extras[0];
		$obj["out_trade_no"] = $extras[1];
		$obj["isBatch"] = $extras[2];
		$obj["payFrom"] = 2;
		
		// 支付成功业务逻辑
		$m = new OM();
		$rs = $m->complatePay ( $obj );
		return $rs;
		
	}

}
