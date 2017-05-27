<?php
namespace wstmart\wechat\controller;
use wstmart\common\model\GoodsCats;
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
 * 商品控制器
 */
class Goods extends Base{
    protected $beforeActionList = [
          'checkAuth' => ['only'=>'history']
    ];
	/**
	 * 商品主页
	 */
	public function detail(){
		
		$m = model('goods');
		$goods = $m->getBySale(input('goodsId/d'));
		
		hook('wechatControllerGoodsIndex',['getParams'=>input()]);
		
        // 找不到商品记录
        if(empty($goods))return $this->fetch('error_lost');
		if(!empty($goods)){
        $goods['goodsDesc']=htmlspecialchars_decode($goods['goodsDesc']);
        $rule = '/<img src="\/(upload.*?)"/';
        preg_match_all($rule, $goods['goodsDesc'], $images);

        foreach($images[0] as $k=>$v){
            $goods['goodsDesc'] = str_replace('/'.$images[1][$k], request()->root().'/'.WSTConf("CONF.goodsLogo") . "\"  data-echo=\"".request()->root()."/".WSTImg($images[1][$k],3), $goods['goodsDesc']);
        }
            $history = cookie("wx_history_goods");
            $history = is_array($history)?$history:[];
            array_unshift($history, (string)$goods['goodsId']);
            $history = array_values(array_unique($history));
            if(!empty($history)){
                cookie("wx_history_goods",$history,25920000);
            }
        }

        $we = WSTWechat();
        $datawx = $we->getJsSignature('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
        $this->assign("datawx", $datawx);
		$this->assign("info", $goods);
		return $this->fetch('goods_detail');
	}
	/**
     * 商品列表
     */
    public function lists(){
    	$this->assign("keyword", input('keyword'));
    	$this->assign("catId", input('catId/d'));
    	$this->assign("brandId", input('brandId/d'));
    	return $this->fetch('goods_list');
    }
    /**
     * 获取列表
     */
    public function pageQuery(){
    	$m = model('goods');
    	$gc = new GoodsCats();
    	$catId = (int)input('catId');
    	if($catId>0){
    		$goodsCatIds = $gc->getParentIs($catId);
    	}else{
    		$goodsCatIds = [];
    	}
    	$rs = $m->pageQuery($goodsCatIds);
    	foreach ($rs['Rows'] as $key =>$v){
    		$rs['Rows'][$key]['goodsImg'] = WSTImg($v['goodsImg'],2);
    	}
    	return $rs;
    }

    /**
    * 浏览历史页面
    */
    public function history(){
       return $this->fetch('users/history/list');
    }
    /**
    * 获取浏览历史
    */
    public function historyQuery(){
        $rs = model('goods')->historyQuery();
        if(!empty($rs)){
	        foreach($rs['Rows'] as $k=>$v){
	            $rs['Rows'][$k]['goodsImg'] = WSTImg($v['goodsImg'],3);
	        }
        }
        return $rs;
    }
}
