<?php
use think\Db;
use wstmart\wechat\model\Users;
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
 */
/**
 * 建立文件夹
 * @param string $aimUrl
 * @return viod
 */
function WSTCreateDir($aimUrl) {
	$aimUrl = str_replace('', '/', $aimUrl);
	$aimDir = '';
	$arr = explode('/', $aimUrl);
	$result = true;
	foreach ($arr as $str) {
		$aimDir .= $str . '/';
		if (!file_exists($aimDir)) {
			$result = mkdir($aimDir,0777);
		}
	}
	return $result;
}
/**
 * 获取购物车数量
 */
function WSTCartNum(){
	$userId = session('WST_USER.userId');
	$cartNum = Db::name('carts')->where(['userId'=>$userId])->field('cartId')->select();
	$count = count($cartNum);
	return $count;
}

/**
 * 下载网络文件到本地服务器
 */
function WSTDownFile($url,$folde='./Upload/image/'){
	set_time_limit (24 * 60 * 60);
	WSTCreateDir(WSTRootPath().$folde);
	$postfix = '';
	$newfname = $folde . time().rand(10,100).".".($postfix!=''?$postfix:"jpg");
	$file = fopen ($url, "rb");
	if ($file) {
		$newf = fopen ($newfname, "wb");
		if ($newf){
			while(!feof($file)) {
				fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
			}
		}
	}
	if ($file) {
		fclose($file);
	}
	if ($newf) {
		fclose($newf);
	}
	return $newfname;
}

/**
 * 微信配置
 */
function WSTWechat(){
	$wechat = new \wechat\WSTWechat(WSTConf('CONF.wxAppId'),WSTConf('CONF.wxAppKey'));
	return $wechat;
}
function WSTBindWeixin($type=1){
	$USER = session('WST_USER');
	if($USER['userId']=='' || $USER['wxOpenId']==''){
		$we = WSTWechat();
		$wdata = session('WST_WX_WDATA');
		if(empty($wdata)){
			$wdata = $we->getUserInfo(input('param.code'));
			session('WST_WX_WDATA',$wdata);
		}
		$userinfo = session('WST_WX_USERINFO');
		if(empty($userinfo)){
			$userinfo = $we->UserInfo($wdata);
			session('WST_WX_USERINFO',$userinfo);
		}
		$users = new Users();
		if($wdata['openid']!=''){
		session('WST_WX_OPENID',$wdata['openid']);
		$rs = Db::name('users')->where(['wxOpenId'=>$wdata['openid'],'dataFlag'=>1])->field('wxOpenId')->select();
		if(count($rs)==0 && session('WST_WX_OPENID')!=''){
			if($type==1){
				header("location:".url('wechat/users/login'));
				exit;
			}
		}else{
			$users->accordLogin();
			$url = session('WST_WX_WlADDRESS');
			if($url){
				header("location:".$url);
				exit;
			}
		}
	}
	}

}
function WSTIsWeixin(){
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
		return true;
	}
	$isLimit = false;
	$limitArr[] = array("controller"=>"Payments","action"=>"notify");
	for($i=0;$i<count($limitArr);$i++){
		$obj = $limitArr[$i];
		if(request()->controller()==$obj["controller"] && request()->action()==$obj["action"]){
			$isLimit = true;
			break;
		}
	}
	if($isLimit){
		return true;
	}
	$url=urlencode($_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
	$url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.WSTConf('CONF.wxAppId').'&redirect_uri=http%3a%2f%2f'.$url.'&response_type=code&scope=snsapi_userinfo&state='.WSTConf('CONF.wxAppCode').'#wechat_redirect';
	header("location:".$url);
	exit;
	return false;
}