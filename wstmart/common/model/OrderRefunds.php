<?php
namespace wstmart\common\model;
use think\Db;
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
 * 退款业务处理类
 */
class OrderRefunds extends Base{
	/**
	 * 用户申请退款
	 */
	public function refund($uId=0){
		$orderId = (int)input('post.id');
		$reason = (int)input('post.reason');
		$content = input('post.content');
		$money = (float)input('post.money');
		$userId = ($uId==0)?(int)session('WST_USER.userId'):$uId;
		if($money<0)return WSTReturn("退款金额不能为负数");
		$order = Db::name('orders')->alias('o')->join('__ORDER_REFUNDS__ orf','orf.orderId=o.orderId','left')->join('__SHOPS__ s','o.shopId=s.shopId','left')
		           ->where(['o.userId'=>$userId,'o.orderId'=>$orderId,'o.orderStatus'=>['in',[-3,-1]]])
		           ->field('o.orderId,s.userId,o.orderStatus,o.orderNo,o.realTotalMoney,o.isPay,o.payType,o.useScore,orf.id refundId')->find();
		$reasonData = WSTDatas(4,$reason);
		if(empty($reasonData))return WSTReturn("无效的退款原因");
		if($reason==10000 && $content=='')return WSTReturn("请输入退款原因");
		if(empty($order))return WSTReturn('操作失败，请检查订单状态是否已改变');
		$allowRequest = false;
		if($order['isPay']==1 || ($order['payType']==0 && $order['useScore']>0)){
			$allowRequest = true;
		}
		if(!$allowRequest)return WSTReturn("您的退款申请已提交，请留意退款信息");
		if($money>$order['realTotalMoney'])return WSTReturn("申请退款金额不能大于实支付金额");
		//查看退款申请是否已存在
		$orfId = $this->where('orderId',$orderId)->value('id');
		Db::startTrans();
		try{
			$result = false;
			//如果退款单存在就进行编辑
			if($orfId>0){
				$object = $this->get($orfId);
				$object->refundReson = $reason;
				if($reason==10000)$object->refundOtherReson = $content;
				$object->backMoney = $money;
				$object->refundStatus = 0;
				$result = $object->save();
			}else{
				$data = [];
				$data['orderId'] = $orderId;	
	            $data['refundTo'] = 0;
	            $data['refundReson'] = $reason;
	            if($reason==10000)$data['refundOtherReson'] = $content;
	            $data['backMoney'] = $money;
	            $data['createTime'] = date('Y-m-d H:i:s');
	            $data['refundStatus'] = ($order['orderStatus']==-1)?1:0;
	            $result = $this->save($data);
			}			
            if(false !== $result){
            	//拒收、取消申请退款的话要给商家发送信息
            	if($order['orderStatus']!=-1){
            		$tpl = WSTMsgTemplates('ORDER_REFUND_CONFER');
	                if($tpl['tplContent']!=''){
	                    $find = ['${ORDER_NO}'];
	                    $replace = [$order['orderNo']];
	                    WSTSendMsg($order['userId'],str_replace($find,$replace,$tpl['tplContent']),['from'=>1,'dataId'=>$orderId]);
	                } 
	                //微信消息
					if((int)WSTConf('CONF.wxenabled')==1){
						$params = [];
						$params['ORDER_NO'] = $order['orderNo'];
					    $params['REASON'] = $reasonData['dataName'].(($reason==10000)?" - ".$content:"");             
						$params['MONEY'] = $money.(($order['useScore']>0)?("【退回积分：".$order['useScore']."】"):"");
				        WSTWxMessage(['CODE'=>'WX_ORDER_REFUND_CONFER','userId'=>$order['userId'],'params'=>$params]);
					} 
			    }
            	Db::commit();
                return WSTReturn('您的退款申请已提交，请留意退款信息',1);
            }
		}catch (\Exception $e) {
		    Db::rollback();
	    }
	    return WSTReturn('操作失败',-1);
	}

	/**
	 * 获取订单价格以及申请退款价格
	 */
	public function getRefundMoneyByOrder($orderId = 0){
		return Db::name('orders')->alias('o')->join('__ORDER_REFUNDS__ orf','orf.orderId=o.orderId')->where('orf.id',$orderId)->field('o.orderId,orderNo,goodsMoney,deliverMoney,useScore,scoreMoney,totalMoney,realTotalMoney,orf.backMoney')->find();
	}

	/**
	 * 商家处理是否同意退款
	 */
	public function shoprefund(){
        $id = (int)input('id');
        $refundStatus = (int)input('refundStatus');
        $content = input('content');
        if($id==0)return WSTReturn('无效的操作');
        if(!in_array($refundStatus,[1,-1]))return WSTReturn('无效的操作');
        if($refundStatus==-1 && $content=='')return WSTReturn('请输入拒绝原因');
        Db::startTrans();
        try{
        	$object = $this->get($id);
            $object->refundStatus = $refundStatus;
            if($object->refundStatus==-1)$object->shopRejectReason = $content;
            $result = $object->save();
            if(false !== $result){
            	//如果是拒收话要给用户发信息
            	if($refundStatus==-1){
            		$order = Db::name('orders')->where('orderId',$object->orderId)->field('userId,orderNo,orderId,useScore')->find();
            		$tpl = WSTMsgTemplates('ORDER_REFUND_FAIL');
	                if($tpl['tplContent']!=''){
	                    $find = ['${ORDER_NO}','${REASON}'];
	                    $replace = [$order['orderNo'],$content];
	                    WSTSendMsg($order['userId'],str_replace($find,$replace,$tpl['tplContent']),['from'=>1,'dataId'=>$order['orderId']]);
	                } 
	                //微信消息
					if((int)WSTConf('CONF.wxenabled')==1){
						$reasonData = WSTDatas(4,$object->refundReson);
						$params = [];
						$params['ORDER_NO'] = $order['orderNo'];
					    $params['REASON'] = $reasonData['dataName'].(($object->refundReson==10000)?" - ".$object->refundOtherReson:"");
					    $params['SHOP_REASON'] = $object->shopRejectReason;             
						$params['MONEY'] = $object->backMoney.(($order['useScore']>0)?("【退回积分：".$order['useScore']."】"):"");
				        WSTWxMessage(['CODE'=>'WX_ORDER_REFUND_FAIL','userId'=>$order['userId'],'params'=>$params]);
					}  
            	}
            	Db::commit();
            	return WSTReturn('操作成功',1);
            }
        }catch (\Exception $e) {
		    Db::rollback();
	    }
	    return WSTReturn('操作失败',-1);
        

	}
}
