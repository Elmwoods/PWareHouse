<?php
namespace wstmart\wechat\controller;
use wstmart\wechat\model\Index as M;
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
 * 默认控制器
 */
class Index extends Base{
	/**
     * 首页
     */
    public function index(){
    	$m = new M();
    	hook('wechatControllerIndexIndex',['getParams'=>input()]);
    	$news = $m->getSysMsg('msg');
    	$this->assign('news',$news);
    	return $this->fetch('index');
    }
    /**
     * 楼层
     */
    public function pageQuery(){
    	$m = new M();
    	$rs = $m->pageQuery();
    	if(isset($rs['goods'])){
    		foreach ($rs['goods'] as $key =>$v){
    			$rs['goods'][$key]['goodsImg'] = WSTImg($v['goodsImg'],2);
    		}
    	}
        return $rs;
    }
    /**
     * 跳去登陆之前的地址
     */
    public function sessionAddress(){
    	session('WST_WX_WlADDRESS',input('url'));
    	return $rs;
    }
}
