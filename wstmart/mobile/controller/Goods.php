<?php
namespace wstmart\mobile\controller;
use wstmart\common\model\GoodsCats;
use wstmart\home\model\GoodsAttr;//添加代码
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
        hook('mobileControllerGoodsIndex',['getParams'=>input()]);
        // 找不到商品记录
        if(empty($goods))return $this->fetch('error_lost');
        $goods['goodsDesc']=htmlspecialchars_decode($goods['goodsDesc']);
        $rule = '/<img src="\/(upload.*?)"/';
        preg_match_all($rule, $goods['goodsDesc'], $images);

        foreach($images[0] as $k=>$v){
            $goods['goodsDesc'] = str_replace('/'.$images[1][$k], request()->root().'/'.WSTConf("CONF.goodsLogo") . "\"  data-echo=\"".request()->root()."/".WSTImg($images[1][$k],3), $goods['goodsDesc']);
        }
        if(!empty($goods)){

            //添加代码start
            if ($goods['isSpec'] == 2 || $goods['isSpec'] == 3 && $goods['skuProps'] != '') {       //添加  || $goods['isSpec'] == 3
                //组合淘宝导入csv销售属性
                $saleAttr = combineSaleAttr($goods['skuProps']);
                if (!empty($saleAttr)) {
                    $saleAttrArr = array();     //属性组合数组
                    $model = new GoodsAttr();
                    foreach ($saleAttr as $item) {
                        $valueArr = explode(';', $item);
                        if (!!strstr($valueArr[0], '1627207')) {
                            for ($i=0; $i<count($valueArr); $i++) {
                                $result = $model -> where('code', $valueArr[$i]) -> find();
                                if ($result) {
                                    $valueArr[$i] = $result['value'];
                                }else {
                                    $valueArr[$i] = urlencode('暂无');
                                }
                            }
                        }else {
                            $j = 0;
                            for ($i=count($valueArr); $i>0; $i--) {
                                $result = $model -> where('code', $valueArr[($i - 1)]) -> find();
                                if ($result) {
                                    $valueArr[$j] = $result['value'];
                                }else {
                                    $valueArr[$j] = urlencode('暂无');
                                }
                                $j++;
                            }
                        }
                        $saleAttrArr[] = $valueArr;
                    }

                    //添加代码start
                    //根据商品规格生成  当isSpec = 3  时spec_items 和 goods_spec  表的基本规格数据
                    if($goods['isSpec'] == 2 || $goods['isSpec'] == 3){
                        $isSpec3 = Db::name('goods')->where(['goodsId' =>$goods['goodsId']])->update(['isSpec' =>1]);//isSpec=2 -> isSpec=3
                        //把原先 isSpec == 2 的基本规格数据删除
                        $det = Db::name('spec_items')->where(['goodsId' =>$goods['goodsId']])->delete();
                        $det = Db::name('goods_specs')->where(['goodsId' =>$goods['goodsId']])->delete();
                        //生成catId
                        $rs = Db::name('spec_cats')->where('catName','尺寸')->find();
                        if(!$rs){
                            $data = [
                                'goodsCatId' =>'253',
                                'goodsCatPath' =>'47_62_253_',
                                'catName' =>'尺寸',
                                'createTime' =>date('Y-m-d H:i:s',time())
                            ];
                            $size = Db::name('spec_cats')->insert($data);
                        }

                        //生成 itemName
                        $arr = ['颜色'=>[],'尺寸'=>[]];
                        foreach ($saleAttrArr as $key1 => $value1) {
                            foreach ($value1 as $key2 => $value2) {
                                if(isset($value1[0]) && !in_array($value1[0],$arr['颜色'])){
                                    $arr['颜色'][] = $value1[0];
                                }
                                if(isset($value1[1]) && !in_array($value1[1],$arr['尺寸'])){
                                    $arr['尺寸'][] = $value1[1];
                                }
                            }
                        }

                        //生成spec_items数据
                        foreach ($arr as $catName => $value) {
                            foreach ($value as $key2 => $itemName) {

                                if($itemName == urlencode('暂无')){
                                    $itemName = urldecode($itemName);
                                }

                                //生成catId
                                $spec_cats = Db::name('spec_cats')->field('catId')->where('catName',$catName)->find();
                                $data = [
                                    'shopId' =>$goods['shopId'],
                                    'catId' =>$spec_cats['catId'],
                                    'goodsId' =>$goods['goodsId'],
                                    // 'itemImg' =>$goods['goodsImg'],
                                    'createTime' =>date('Y-m-d H:i:s',time()),
                                    'itemName' =>$itemName
                                ];
                                $spec_items = Db::name('spec_items')->insert($data);
                            }
                        }

                        //2.生成goods_spec数据
                        //组织商品规格生成新的数组
                        $specIds = [];
                        $arrNew = [];
                        $arr1 = [];//颜色
                        $arr2 = [];//尺寸
                        $flag = '';
                        foreach ($arr as $key => $value) {
                            foreach ($value as $k => $v) {
                                if($key=='颜色'){
                                    $arr1[] = $v;
                                }
                                if($key=='尺寸'){
                                    $arr2[] = $v;
                                }
                            }
                        }

                        //判断商品规格
                        if(!empty($arr1) && !empty($arr2)){//颜色和尺寸存在
                            $temp = $arr1;
                        }
                        if(!empty($arr1) && empty($arr2)){//颜色存在  尺寸不存在
                            $temp = $arr1;
                        }
                        if(empty($arr1) && !empty($arr2)){//颜色不存在  尺寸存在
                            $temp = $arr2;
                        }

                        //生成新的数组
                        foreach ($temp as $key => $value) {
                            if($value == urlencode('暂无')){
                                $value = urldecode($value);
                            }
                            if(!empty($arr2) && $temp != $arr2){
                                for($i=0;$i<count($arr2);$i++){
                                    if($arr2[$i] == urlencode('暂无')){
                                        $arr2[$i] = urldecode($arr2[$i]);
                                    }
                                    $arrNew[] = [$value=>$arr2[$i]];
                                }
                            }else{
                                $arrNew[] = [$value];
                            }
                        }

                        //生成specIds
                        foreach ($arrNew as $key => $value) {
                            foreach ($value as $k => $v) {
                                if($k !== 0){
                                    $colorID = Db::query("SELECT * FROM   `jingo_spec_items` where goodsId=? and itemName=? limit 1",[$goods['goodsId'],$k]);
                                }

                                $sizeID = Db::query("SELECT * FROM   `jingo_spec_items` where goodsId=? and itemName=? limit 1",[$goods['goodsId'],$v]);

                                foreach ($sizeID as $key => $value) {
                                    if(!empty($colorID)){
                                        for ($i=0; $i<1 ; $i++) {
                                            if(!in_array($colorID[$i]['itemId'].':'.$value['itemId'],$specIds)){
                                                $specIds[] = $colorID[$i]['itemId'].':'.$value['itemId'];
                                            }
                                        }
                                    }else{
                                        $specIds[] = $value['itemId'];
                                    }
                                }
                            }
                        }

                        //生成goods_specs表数据
                        //生成goods_specs表数据
                        foreach ($specIds as $key => $value) {
                            if($key ==0){
                                 $data = [
                                    'shopId' =>$goods['shopId'],
                                    'goodsId' =>$goods['goodsId'],
                                    'productNo' =>$goods['productNo'],
                                    'specIds' =>$value,
                                    'isDefault' =>1,
                                    'marketPrice' =>$goods['marketPrice'],
                                    'specPrice' =>$goods['shopPrice'],
                                    'specStock' =>$goods['goodsStock'],
                                    'warnStock' =>$goods['warnStock']
                                ];
                            
                            }else{
                                $data = [
                                    'shopId' =>$goods['shopId'],
                                    'goodsId' =>$goods['goodsId'],
                                    'productNo' =>$goods['productNo'],
                                    'specIds' =>$value,
                                    'marketPrice' =>$goods['marketPrice'],
                                    'specPrice' =>$goods['shopPrice'],
                                    'specStock' =>$goods['goodsStock'],
                                    'warnStock' =>$goods['warnStock']
                                ];
                            }
                            $goods_spec = Db::name('goods_specs')->insert($data);
                        }
                    }
            //         //添加代码end
                    $this -> assign('goodsSpec', urldecode(json_encode($arr)));
                }
            }
            //添加代码end

            $history = cookie("wx_history_goods");
            $history = is_array($history)?$history:[];
            array_unshift($history, (string)$goods['goodsId']);
            $history = array_values(array_unique($history));
            if(!empty($history)){
                cookie("wx_history_goods",$history,25920000);
            }
        }
		$this->assign("info", $goods);
        // header("content-type:text/html; charset=utf-8");
        //     echo "<pre>";
        //     print_r($goods);die;
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
        $isSearch = 0;
        if($catId>0){
            $goodsCatIds[] = $gc->getParentIs($catId);
        }else{
             $isSearch = 1;
            $data['keyword'] = input('keyword');
            $model = model('GoodsCats')->where('catName',$data['keyword'])->field('catId')->select(); 
            if($model){
                foreach ($model as $key => $value) {
                    $goodsCatIds[] = $gc->getParentIs($value['catId']);
                }
            }else{
                $goodsCatIds = [];
            } 
        }
        
        $rs = $m->pageQuery($goodsCatIds,$isSearch);
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
