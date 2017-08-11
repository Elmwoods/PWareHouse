<?php
namespace wstmart\purse\behavior;
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
 * 初始化基础数据
 */
class InitConfig 
{
    public function run(&$params){
        WSTConf('protectedUrl',model('HomeMenus')->getMenusUrl());
        // 检测手机端访问
        $request = request();
        $currModel = $request->module();
        
        hook('initConfigHook',['getParams'=>input()]);
//        dump($request);die;
        if($request->isMobile() &&  $currModel=='home'){
        	$url = url('mobile/index/index');
        	header("location:$url");
        	die;
        }
    }
}