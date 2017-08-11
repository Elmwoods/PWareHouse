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
 * 门店申请类
 */
class ShopApplys extends Base{
	
	 /**
     * 查询手机是否存在
     */
    public function checkShopPhone($shopPhone = ''){
    	$shopPhone = ($shopPhone!='')?$shopPhone:input("post.userPhone2");
    	if($shopPhone=='')return WSTReturn("请输入手机号码");
    	$rs = $this->where("phoneNo",$shopPhone)
    				->where(["dataFlag"=>1])
    				->count();
    	if($rs==0){
    		return WSTReturn("",1);
    	}
    	return WSTReturn("该手机号码已注册过");
    }
	/**
	 * 添加门店申请记录
	 */
	public function addApply(){
        $linkman = input('linkman');
		$phoneNo = input("post.userPhone2");
		$applyDesc = input("post.remark");
		if($linkman=='')return WSTReturn("请输入联系人");
		$crs = $this->checkShopPhone();
		if($crs['status']!=1){
			return WSTReturn("该手机已存在");
		}
		$mobileCode = input("post.mobileCode");
		$code = input("post.smsVerfy");
		$verifyCode = input("post.verifyCodea");

		if(WSTConf("CONF.smsOpen")==0){
			if(!WSTVerifyCheck($verifyCode)){
				return WSTReturn('验证码错误!');
			}
		}else{
			$verify = session('VerifyCode_shopPhone');
			$startTime = (int)session('VerifyCode_shopPhone_Time');
			if((time()-$startTime)>120){
				return WSTReturn("验证码已超过有效期!");
			}
			if($mobileCode=="" || $verify != $mobileCode){
				return WSTReturn("验证码错误!");
			}
		}
		$data = array();
		//添加代码 start
		if ((int)session('WST_USER.userId')) {
			$data['userId'] = (int)session('WST_USER.userId');
		}else{
			$model=Db::name('users')->where('userPhone',$phoneNo)->find();
			$data['userId'] = $model['userId'];
		}
		//添加代码 end
		$data['phoneNo'] = $phoneNo;
		$data['applyDesc'] = $applyDesc;
		$data['applyStatus'] = 0;
		$data['linkman'] = $linkman;
		$data['dataFlag'] = 1;
		$data['createTime'] = date('Y-m-d H:i:s');
		$rs = $this->data($data)->save();
		if(false !== $rs){
			return WSTReturn("申请成功", 1);
		}else{
			return WSTReturn($this->getError());
		}
	}
}
