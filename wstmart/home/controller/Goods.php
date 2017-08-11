<?php
namespace wstmart\home\controller;
use wstmart\home\model\Goods as M;
use think\Db;
use wstmart\common\model\Goods as CM;
use wstmart\home\model\GoodsAttr;

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
    /**
      * 批量删除商品
      */
     public function batchDel(){
        $m = new M();
        return $m->batchDel();
     }
    /**
     * 修改商品库存/价格
     */
    public function editGoodsBase(){
        $m = new M();
        return $m->editGoodsBase();
    }

    /**
    * 修改商品状态
    */
    public function changSaleStatus(){
        $m = new M();
        return $m->changSaleStatus();
    }
    /**
    * 批量修改商品状态 新品/精品/热销/推荐
    */
    public function changeGoodsStatus(){
         $m = new M();
        return $m->changeGoodsStatus();
    }
    /**
    *   批量上(下)架
    */
    public function changeSale(){
        $m = new M();
        return $m->changeSale();
    }
   /**
    *  上架商品列表
    */
    public function sale(){
        return $this->fetch('shops/goods/list_sale');
    }
    /**
     * 获取上架商品列表
     */
    public function saleByPage(){
        $m = new M();
        $rs = $m->saleByPage();
        $rs['status'] = 1;
        return $rs;
    }
    /**
     * 仓库中商品
     */
    public function store(){
        return $this->fetch('shops/goods/list_store');
    }
    /**
     * 审核中的商品
     */
    public function audit(){
        return $this->fetch('shops/goods/list_audit');
    }
    /**
     * 获取审核中的商品
     */
    public function auditByPage(){
        $m = new M();
        $rs = $m->auditByPage();
        $rs['status'] = 1;
        return $rs;
    }
    /**
     * 获取仓库中的商品
     */
    public function storeByPage(){
        $m = new M();
        $rs = $m->storeByPage();
        $rs['status'] = 1;
        return $rs;
    }
    /**
     * 违规商品
     */
    public function illegal(){
        return $this->fetch('shops/goods/list_illegal');
    }
    /**
     * 获取违规的商品
     */
    public function illegalByPage(){
        $m = new M();
        $rs = $m->illegalByPage();
        $rs['status'] = 1;
        return $rs;
    }
    
    /**
     * 跳去新增页面
     */
    public function add(){
        $m = new M();
        $object = $m->getEModel('goods');
        $object['goodsSn'] = WSTGoodsNo();
        $object['productNo'] = WSTGoodsNo();
        $object['goodsImg'] = WSTConf('CONF.goodsLogo');
        $data = ['object'=>$object,'src'=>'add'];
        return $this->fetch('shops/goods/edit',$data);
    } 
    
    /**
     * 新增商品
     */
    public function toAdd(){
        $m = new M();
        return $m->add();
    }
    
    /**
     * 跳去编辑页面
     */
    public function edit(){
        $m = new M();
        $object = $m->getById(input('get.id'));
        if($object['goodsImg']=='')$object['goodsImg'] = WSTConf('CONF.goodsLogo');
        $data = ['object'=>$object,'src'=>input('src')];
        // header("content-type:text/html; charset=utf-8");
        //     echo "<pre>";
        //     print_r($object);die;
        return $this->fetch('shops/goods/edit',$data);
    }
    
    /**
     * 编辑商品
     */
    public function toEdit(){
        $m = new M();
        return $m->edit();
    }
    /**
     * 删除商品
     */
    public function del(){
        $m = new M();
        return $m->del();
    }
    /**
     * 获取商品规格属性
     */
    public function getSpecAttrs(){
        $m = new M();
        return $m->getSpecAttrs();
    }
    /**
     * 进行商品搜索
     */
    public function search(){
        //获取商品记录
        $m = new M();
        $data = [];
        $data['isStock'] = Input('isStock/d');
        $data['isNew'] = Input('isNew/d');
        $data['isFreeShipping'] = input('isFreeShipping/d');
        $data['orderBy'] = Input('orderBy/d');
        $data['order'] = Input('order/d',1);
        $data['keyword'] = input('get.keyword');
        $data['sprice'] = Input('sprice/d');
        $data['eprice'] = Input('eprice/d');
        // var_dump($data['keyword']);die;

        //添加代码  获取goodsCatIds
        $model = model('GoodsCats')->where('catName',$data['keyword'])->field('catId')->select(); 
         if($model){
                foreach ($model as $key => $value) {
                    $goodsCatIds[] =  model('GoodsCats')->getParentIs($value['catId']);
                }
            }else{
                $goodsCatIds = [];
            } 
        // $goodsCatIds = model('GoodsCats')->getParentIs($catId['catId']);


        $data['areaId'] = (int)Input('areaId');
        $aModel = model('home/areas');

        // 获取地区
        $data['area1'] = $data['area2'] = $data['area3'] = $aModel->listQuery(); // 省级

        // 如果有筛选地区 获取上级地区信息
        if($data['areaId']!==0){
            $areaIds = $aModel->getParentIs($data['areaId']);
            /*
              2 => int 440000
              1 => int 440100
              0 => int 440106
            */
            $selectArea = [];
            $areaName = '';
            foreach($areaIds as $k=>$v){
                $a = $aModel->getById($v);
                $areaName .=$a['areaName'];
                $selectArea[] = $a;
            }
            // 地区完整名称
            $selectArea['areaName'] = $areaName;
            // 当前选择的地区
            $data['areaInfo'] = $selectArea;

            $data['area2'] = $aModel->listQuery($areaIds[2]); // 广东的下级
 
            $data['area3'] = $aModel->listQuery($areaIds[1]); // 广州的下级
        }
        

        $data['goodsPage'] = $m->pageQuery($goodsCatIds,1); //添加参数 $goodsCatIds
        $data['Total'] = $data['goodsPage']['Total'];//添加代码  获取商品数量
        
        return $this->fetch("goods_search",$data);
    }
    
    /**
     * 获取商品列表
     */
    public function lists(){
        $catId = Input('cat/d');
        $goodsCatIds[] = model('GoodsCats')->getParentIs($catId);
        $goodsCatIds1 = model('GoodsCats')->getParentIs($catId);
        // var_dump($goodsCatIds);die;
        reset($goodsCatIds);
        //填充参数
        $data = [];
        $data['catId'] = $catId;
        $data['isStock'] = Input('isStock/d');
        $data['isNew'] = Input('isNew/d');
        $data['isFreeShipping'] = input('isFreeShipping/d');
        $data['orderBy'] = Input('orderBy/d');
        $data['order'] = Input('order/d',1);
        $data['sprice'] = Input('sprice');
        $data['eprice'] = Input('eprice');
        $data['attrs'] = [];

        $data['areaId'] = (int)Input('areaId');
        $aModel = model('home/areas');

        // 获取地区
        $data['area1'] = $data['area2'] = $data['area3'] = $aModel->listQuery(); // 省级

        // 如果有筛选地区 获取上级地区信息
        if($data['areaId']!==0){
            $areaIds = $aModel->getParentIs($data['areaId']);
            /*
              2 => int 440000
              1 => int 440100
              0 => int 440106
            */
            $selectArea = [];
            $areaName = '';
            foreach($areaIds as $k=>$v){
                $a = $aModel->getById($v);
                $areaName .=$a['areaName'];
                $selectArea[] = $a;
            }
            // 地区完整名称
            $selectArea['areaName'] = $areaName;
            // 当前选择的地区
            $data['areaInfo'] = $selectArea;

            $data['area2'] = $aModel->listQuery($areaIds[2]); // 广东的下级
 
            $data['area3'] = $aModel->listQuery($areaIds[1]); // 广州的下级
        }
        
        $vs = input('vs');
        $vs = ($vs!='')?explode(',',$vs):[];
        foreach ($vs as $key => $v){
            if($v=='' || $v==0)continue;
            $v = (int)$v;
            $data['attrs']['v_'.$v] = input('v_'.$v);
        }
        $data['vs'] = $vs;

        //添加代码start
        $goodsCatIds2 = model('GoodsCats')->getParentIs($catId);
        reset($goodsCatIds2);
        $data['brandFilter'] = model('Brands')->listQuery((int)current($goodsCatIds2));
        //添加代码end

        // $data['brandFilter'] = model('Brands')->listQuery((int)current($goodsCatIds));//原代码
        $data['brandId'] = Input('brand/d');
        $data['price'] = Input('price');
        //封装当前选中的值
        $selector = [];
        //处理品牌
        if($data['brandId']>0){
            foreach ($data['brandFilter'] as $key =>$v){
                if($v['brandId']==$data['brandId'])$selector[] = ['id'=>$v['brandId'],'type'=>'brand','label'=>"品牌","val"=>$v['brandName']];
            }
            unset($data['brandFilter']);
        }
        //处理价格
        if($data['sprice']!='' && $data['eprice']!=''){
            $selector[] = ['id'=>0,'type'=>'price','label'=>"价格","val"=>$data['sprice']."-".$data['eprice']];
        }
        if($data['sprice']!='' && $data['eprice']==''){
            $selector[] = ['id'=>0,'type'=>'price','label'=>"价格","val"=>$data['sprice']."以上"];
        }
        if($data['sprice']=='' && $data['eprice']!=''){
            $selector[] = ['id'=>0,'type'=>'price','label'=>"价格","val"=>"0-".$data['eprice']];
        }
        //处理已选属性
        $goodsFilter = model('Attributes')->listQueryByFilter($catId);
        $ngoodsFilter = [];
        foreach ($goodsFilter as $key =>$v){
            if(!in_array($v['attrId'],$vs))$ngoodsFilter[] = $v;
        }
        if(count($vs)>0){
            foreach ($goodsFilter as $key =>$v){
                if(in_array($v['attrId'],$vs)){
                    foreach ($v['attrVal'] as $key2 =>$vv){
                        if($vv==input('v_'.$v['attrId']))$selector[] = ['id'=>$v['attrId'],'type'=>'v_'.$v['attrId'],'label'=>$v['attrName'],"val"=>$vv];;
                    }
                }
            }
        }
        $data['selector'] = $selector;
        $data['goodsFilter'] = $ngoodsFilter;
        //获取商品记录
        $m = new M();
        $data['priceGrade'] = $m->getPriceGrade($goodsCatIds1);
        $data['goodsPage'] = $m->pageQuery($goodsCatIds,0);
        $catPaths = model('goodsCats')->getParentNames($catId);
        $data['catNamePath'] = '全部商品分类';
        if(!empty($catPaths))$data['catNamePath'] = implode(' - ',$catPaths);
        
        return $this->fetch("goods_list",$data);
    }
    function getCombinationToString($arr, $len){
        if ($len == 1) {
            return $arr[0];
        }
        $tempArr = $arr;
        unset($tempArr[0]);
        $returnarr = [];
        $len2 = count($arr);
        $ret = $this->getCombinationToString(array_values($tempArr), ($len - 1));
        foreach ($arr[$len2 - $len] as $alv) {
            foreach ($ret as $rv) {
                if (is_array($rv)) {
                    array_unshift($rv, $alv);
                    $returnarr[] = array_values($rv);
                } else {
                    $returnarr[] = [$alv,$rv];
                }
            }
        }
        return $returnarr;
    }

    /**
     * 查看商品详情
     */
    // public function detail_yuan(){
    //     $m = new M();
    //     $goods = $m->getBySale(input('id/d',0));

    //     if(!empty($goods)){
    //         if ($goods['isSpec'] == 2  && $goods['skuProps'] != '') {       //添加  || $goods['isSpec'] == 3
    //             //组合淘宝导入csv销售属性
    //             $saleAttr = combineSaleAttr($goods['skuProps']);
    //             if (!empty($saleAttr)) {
    //                 $saleAttrArr = array();     //属性组合数组
    //                 $model = new GoodsAttr();
    //                 foreach ($saleAttr as $item) {
    //                     $valueArr = explode(';', $item);
    //                     if (!!strstr($valueArr[0], '1627207')) {
    //                         for ($i=0; $i<count($valueArr); $i++) {
    //                             $result = $model -> where('code', $valueArr[$i]) -> find();
    //                             if ($result) {
    //                                 $valueArr[$i] = $result['value'];
    //                             }else {
    //                                 $valueArr[$i] = urlencode('暂无');
    //                             }
    //                         }
    //                     }else {
    //                         $j = 0;
    //                         for ($i=count($valueArr); $i>0; $i--) {
    //                             $result = $model -> where('code', $valueArr[($i - 1)]) -> find();
    //                             if ($result) {
    //                                 $valueArr[$j] = $result['value'];
    //                             }else {
    //                                 $valueArr[$j] = urlencode('暂无');
    //                             }
    //                             $j++;
    //                         }
    //                     }
    //                     $saleAttrArr[] = $valueArr;
    //                 }

    //                 //添加代码start
    //                 //根据商品规格生成  当isSpec = 3  时spec_items 和 goods_spec  表的基本规格数据
    //                 if($goods['isSpec'] == 2){
    //                     $isSpec3 = Db::name('goods')->where(['goodsId' =>$goods['goodsId']])->update(['isSpec' =>1]);//isSpec=2 -> isSpec=1
    //                     //把原先 isSpec == 2 的基本规格数据删除
    //                     $det = Db::name('spec_items')->where(['goodsId' =>$goods['goodsId']])->delete();
    //                     $det = Db::name('goods_specs')->where(['goodsId' =>$goods['goodsId']])->delete();
    //                     // 生成catId
    //                     // 生成 itemName
                       
    //                     $arr = ['颜色'=>[],'尺寸'=>[]];
                        

    //                     foreach ($saleAttrArr as $key1 => $value1) {
    //                         foreach ($value1 as $key2 => $value2) {
    //                             if(isset($value1[0]) && !in_array($value1[0],$arr['颜色'])){
    //                                 $arr['颜色'][] = $value1[0];
    //                             }
    //                             if(isset($value1[1]) && !in_array($value1[1],$arr['尺寸'])){
    //                                 $arr['尺寸'][] = $value1[1];
    //                             }
    //                         }
    //                     }

    //                     //生成spec_items数据
    //                     foreach ($arr as $catName => $value) {
    //                         foreach ($value as $key2 => $itemName) {
    //                             if($itemName == urlencode('暂无')){
    //                                 $itemName = urldecode($itemName);
    //                             }
    //                             //添加代码start
    //                            $goodsCatIdPath= $goodsCatIdPath1=$goods['goodsCatIdPath'];
    //                             //生成catId
    //                             $map['catName'] = $catName;
    //                             $map['dataFlag'] = 1;
    //                             $map['goodsCatPath'] = $goodsCatIdPath;
    //                             $spec_cats = Db::name('spec_cats')->field('catId')->where($map)->find();
    //                             if (!$spec_cats) {
    //                                 $goodsCatIdPath=explode('_',$goodsCatIdPath);
    //                                 $map['goodsCatPath']=$goodsCatIdPath[0].'_'.$goodsCatIdPath[1].'_';
    //                                 $spec_cats = Db::name('spec_cats')->field('catId')->where($map)->find();
    //                                 if (!$spec_cats) {
    //                                     $map['goodsCatPath']=$goodsCatIdPath[0].'_';
    //                                     $spec_cats = Db::name('spec_cats')->field('catId')->where($map)->find();
    //                                     if (!$spec_cats) {
    //                                         $data5['goodsCatPath']=$goodsCatIdPath1;
    //                                         $data5['goodsCatId']=$goods['goodsCatId'];
    //                                         $data5['catName']=$catName;
    //                                         $data5['goodsCatPath']=$goodsCatIdPath1;
    //                                         $data5['goodsCatId']=$goods['goodsCatId'];
    //                                         $data5['createTime']=date("Y-m-d H:i:s");
    //                                         $spec_cats['catId']=Db::name('spec_cats')->insertGetId($data5);
    //                                     }
    //                                 }
    //                             }
    //                             //添加代码end
    //                             $data = [
    //                                 'shopId' =>$goods['shopId'],
    //                                 'catId' =>$spec_cats['catId'],
    //                                 'goodsId' =>$goods['goodsId'],
    //                                 // 'itemImg' =>$goods['goodsImg'],
    //                                 'createTime' =>date('Y-m-d H:i:s',time()),
    //                                 'itemName' =>$itemName
    //                             ];
    //                             $spec_items = Db::name('spec_items')->insert($data);
    //                         }
    //                     }

    //                     //2.生成goods_spec数据
    //                     //组织商品规格生成新的数组
    //                     $specIds = [];
    //                     $arrNew = [];
    //                     $arr1 = [];//颜色
    //                     $arr2 = [];//尺寸
    //                     $flag = '';
    //                     foreach ($arr as $key => $value) {
    //                         foreach ($value as $k => $v) {
    //                             if($key=='颜色'){
    //                                 $arr1[] = $v;
    //                             }
    //                             if($key=='尺寸'){
    //                                 $arr2[] = $v;
    //                             }
    //                         }
    //                     }

    //                     //判断商品规格
    //                     if(!empty($arr1) && !empty($arr2)){//颜色和尺寸存在
    //                         $temp = $arr1;
    //                     }
    //                     if(!empty($arr1) && empty($arr2)){//颜色存在  尺寸不存在
    //                         $temp = $arr1;
    //                     }
    //                     if(empty($arr1) && !empty($arr2)){//颜色不存在  尺寸存在
    //                         $temp = $arr2;
    //                     }

    //                     //生成新的数组
    //                     foreach ($temp as $key => $value) {
    //                         if($value == urlencode('暂无')){
    //                             $value = urldecode($value);
    //                         }
    //                         if(!empty($arr2) && $temp != $arr2){
    //                             for($i=0;$i<count($arr2);$i++){
    //                                 if($arr2[$i] == urlencode('暂无')){
    //                                     $arr2[$i] = urldecode($arr2[$i]);
    //                                 }
    //                                 $arrNew[] = [$value=>$arr2[$i]];
    //                             }
    //                         }else{
    //                             $arrNew[] = [$value];
    //                         }
    //                     }

    //                     //生成specIds
    //                     foreach ($arrNew as $key => $value) {
    //                         foreach ($value as $k => $v) {
    //                             if($k !== 0){
    //                                 $colorID = Db::query("SELECT * FROM   `jingo_spec_items` where goodsId=? and itemName=? limit 1",[$goods['goodsId'],$k]);
    //                             }

    //                             $sizeID = Db::query("SELECT * FROM   `jingo_spec_items` where goodsId=? and itemName=? limit 1",[$goods['goodsId'],$v]);

    //                             foreach ($sizeID as $key => $value) {
    //                                 if(!empty($colorID)){
    //                                     for ($i=0; $i<1 ; $i++) {
    //                                         if(!in_array($colorID[$i]['itemId'].':'.$value['itemId'],$specIds)){
    //                                             $specIds[] = $colorID[$i]['itemId'].':'.$value['itemId'];  //原代码  
    //                                             // $specIds[] = $value['itemId'].':'.$colorID[$i]['itemId']; //添加代码
    //                                         }
    //                                     }
    //                                 }else{
    //                                     $specIds[] = $value['itemId'];
    //                                 }
    //                             }
    //                         }
    //                     }

    //                     //生成goods_specs表数据
    //                     foreach ($specIds as $key => $value) {
    //                         //header("content-type:text/html; charset=utf-8"); 
    //                         if($key ==0){
    //                              $data = [
    //                                 'shopId' =>$goods['shopId'],
    //                                 'goodsId' =>$goods['goodsId'],
    //                                 'productNo' =>$goods['productNo'],
    //                                 'specIds' =>$value,
    //                                 'isDefault' =>1,
    //                                 'marketPrice' =>$goods['marketPrice'],
    //                                 'specPrice' =>$goods['shopPrice'],
    //                                 'specStock' =>$goods['goodsStock'],
    //                                 'warnStock' =>$goods['warnStock']
    //                             ];
                            
    //                         }else{
    //                             $data = [
    //                                 'shopId' =>$goods['shopId'],
    //                                 'goodsId' =>$goods['goodsId'],
    //                                 'productNo' =>$goods['productNo'],
    //                                 'specIds' =>$value,
    //                                 'marketPrice' =>$goods['marketPrice'],
    //                                 'specPrice' =>$goods['shopPrice'],
    //                                 'specStock' =>$goods['goodsStock'],
    //                                 'warnStock' =>$goods['warnStock']
    //                             ];
    //                         }
    //                         $goods_spec = Db::name('goods_specs')->insert($data);
    //                     }
    //                 }
    //                 // //添加代码end
    //                 //         header("content-type:text/html; charset=utf-8");
    //                 //         echo "<pre>";
    //                 //         print_r($specIds);die;

    //                 $this -> assign('goodsSpec', urldecode(json_encode($saleAttrArr)));
    //             }
    //             /*$value1 = array();
    //             $value2 = array();
    //             foreach ($saleAttrArr as $item) {
    //                 $value1[] = $item[0];
    //                 $value1 = array_unique($value1);
    //                 if (count($item) > 1) {
    //                     $value2[] = $item[1];
    //                     $value2 = array_unique($value2);
    //                 }

    //                 $num = count($item);
    //                 if ($item[0] == "黑色\r" && $num > 1) {
    //                     echo $item[1];
    //                 }
    //                 if ($item[0] == "黑色" && $item[1] == 'l' && $num > 2 ) {
    //                     echo $item[2];
    //                 }
    //             }
    //             $this -> assign('valueNum', count($saleAttrArr[0]));
    //             $this -> assign('goodsSpec', $saleAttrArr);
    //             $this -> assign('specLine1', $value1);
    //             $this -> assign('specLine2', $value2);*/

    //             /*$unique = array();
    //             foreach ($saleAttrArr as $item) {
    //                 $unique[] = $item[0];
    //             }
    //             $unique = array_unique($unique);
    //             $attrCombine = array();
    //             foreach ($unique as $item) {
    //                 $attrCombine[$item][] = $item;
    //                 foreach ($saleAttrArr as $v) {
    //                     if ($v[0] == $item && count($v) == 2) {
    //                         $attrCombine[$item][] = $v[1];
    //                     }
    //                 }
    //             }
    //             $attrCombine = urldecode(json_encode($attrCombine));
    //             $this -> assign('goodsSpec', $attrCombine);*/
    //             //组合完毕,将值传给模板
    //         }

    //         $history = cookie("history_goods");
    //         $history = is_array($history)?$history:[];
    //         array_unshift($history, (string)$goods['goodsId']);
    //         $history = array_values(array_unique($history));
            
    //         if(!empty($history)){
    //             cookie("history_goods",$history,25920000);
    //         }
    //         $this->assign('goods',$goods);
    //         $this->assign('shop',$goods['shop']);
    //         return $this->fetch("goods_detail");
    //     }else{
    //         return $this->fetch("error_lost");
    //     }
    // }

    /**
     * 查看商品详情
     */
    public function detail(){
        // die('请等几分钟后重试');

        $m = new M();
        $goods = $m->getBySale(input('id/d',0));

        if(!empty($goods)){
            if ($goods['isSpec'] == 2  && $goods['skuProps'] != '') {       //添加  || $goods['isSpec'] == 3
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
                    //根据商品规格生成  当isSpec = 2  时spec_items 和 goods_spec  表的基本规格数据
                    if($goods['isSpec'] == 2){

                        $a = explode('_',$goods['goodsCatIdPath']);

                        $spec_cats = Db::name('spec_cats')->field('catId')
                                                   ->where(['goodsCatPath'=>$goods['goodsCatIdPath'],'isShow'=>1,'dataFlag'=>1])->select();
                        if (!$spec_cats) {
                            $spec_cats = Db::name('spec_cats')->field('catId')
                                                   ->where(['goodsCatId'=>$a[2],'isShow'=>1,'dataFlag'=>1])->select();
                        }
                        if (!$spec_cats) {
                            $spec_cats = Db::name('spec_cats')->field('catId')
                                                   ->where(['goodsCatPath'=>$a[0].'_'.$a[1].'_','isShow'=>1,'dataFlag'=>1])->select();
                        }
                        if (!$spec_cats) {
                            $spec_cats = Db::name('spec_cats')->field('catId')
                                                   ->where(['goodsCatPath'=>$a[0].'_','isShow'=>1,'dataFlag'=>1])->select();
                        }
                        if (!$spec_cats) {
                           die('商品规格有误，请联系管理员');
                        }

                        $isSpec3 = Db::name('goods')->where(['goodsId' =>$goods['goodsId']])->update(['isSpec' =>1]);//isSpec=2 -> isSpec=1
                        //把原先 isSpec == 2 的基本规格数据删除
                        $det = Db::name('spec_items')->where(['goodsId' =>$goods['goodsId']])->delete();
                        $det = Db::name('goods_specs')->where(['goodsId' =>$goods['goodsId']])->delete();
                        // 生成catId
                        // 生成 itemName    

                        foreach ($spec_cats as $key => $value) {
                            $arr[$key] = [];
                        }                                            

                        foreach ($saleAttrArr as $key1 => $value1) {
                            foreach ($value1 as $key2 => $value2) {
                                if(isset($value1[$key2]) && !in_array($value1[$key2],$arr[$key2])){
                                    $arr[$key2][] = $value1[$key2];
                                }
                            }
                        }
                        // echo "<pre>";
                        // print_r($saleAttrArr);                  
                        // echo "<pre>";
                        // print_r($arr);

                        //生成spec_items数据
                        foreach ($arr as $key1 => $value1) {
                            foreach ($value1 as $key2 => $itemName) {

                                if($itemName == urlencode('暂无')){
                                    $itemName = urldecode($itemName);
                                }                                                           
                                // echo "<pre>";
                                // print_r($spec_cats);
                                foreach ($spec_cats as $k=> $v) {
                                    if ($k == $key1) {
                                         $arr2[$v['catId']][] = $itemName;
                                    }
                                }
                                // echo "<pre>";
                                // print_r($arr2);die;                              
                            }
                        }
                        // header("content-type:text/html; charset=utf-8");
                        // echo "<pre>";
                        // print_r($arr2); 

                        foreach ($arr2 as $k => $v) {
                            foreach ($v as $key => $value) {
                                $data = [
                                    'shopId' =>$goods['shopId'],
                                    'catId' =>$k,
                                    'goodsId' =>$goods['goodsId'],
                                    // 'itemImg' =>$goods['goodsImg'],
                                    'createTime' =>date('Y-m-d H:i:s',time()),
                                    'itemName' =>$value
                                ];
                                $spec_items = Db::name('spec_items')->insert($data);
                            }
                        }

                        foreach ($arr2 as $key1 => $value1) {
                            foreach ($value1 as $k => $v) {
                                $sizeID = Db::query("SELECT * FROM   `jingo_spec_items` where goodsId=? and itemName=? limit 1",[$goods['goodsId'],$v]);
                                foreach ($sizeID as $key2 => $value2) {
                                    $arr3[$key1][$k] = $value2['itemId'];

                                }
                            }
                        }

                        if(count($arr3) > 1){
                            // echo "<pre>";
                            // print_r($arr3);
                            foreach ($arr3 as  $value) {
                                $arr4[] = $value;
                            }
                            // echo "<pre>";
                            // print_r($arr4);
                            $len = count($arr4);
                            $specIds = $this->getCombinationToString($arr4, $len);
                            foreach ($specIds as &$av) {
                                    $av = implode(':', $av);
                            } 
                        }else{
                            foreach ($arr3 as  $value) {
                                foreach ($value as  $v) {
                                    $specIds[] = $v;
                                }
                            }
                        }                                                 
                        
                        // var_dump($data);
                      
                       
                        
                        // header("content-type:text/html; charset=utf-8");
                        // echo "<pre>";
                        // print_r($specIds);     

                        //生成goods_specs表数据
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
                        foreach ($specIds as $key => $value) {
                            header("content-type:text/html; charset=utf-8"); 
                            if($key ==0){
                                 $data['specIds'] = $value;
                                 $data['isDefault'] = 1;
                            
                            }else{
                                $data['specIds'] = $value;
                                $data['isDefault'] = 0;
                            }
                            $goods_spec = Db::name('goods_specs')->insert($data);
                        }
                    }
                    // //添加代码end
                    //         header("content-type:text/html; charset=utf-8");
                    //         echo "<pre>";
                    //         print_r($specIds);die;

                    $this -> assign('goodsSpec', urldecode(json_encode($saleAttrArr)));
                }
                /*$value1 = array();
                $value2 = array();
                foreach ($saleAttrArr as $item) {
                    $value1[] = $item[0];
                    $value1 = array_unique($value1);
                    if (count($item) > 1) {
                        $value2[] = $item[1];
                        $value2 = array_unique($value2);
                    }

                    $num = count($item);
                    if ($item[0] == "黑色\r" && $num > 1) {
                        echo $item[1];
                    }
                    if ($item[0] == "黑色" && $item[1] == 'l' && $num > 2 ) {
                        echo $item[2];
                    }
                }
                $this -> assign('valueNum', count($saleAttrArr[0]));
                $this -> assign('goodsSpec', $saleAttrArr);
                $this -> assign('specLine1', $value1);
                $this -> assign('specLine2', $value2);*/

                /*$unique = array();
                foreach ($saleAttrArr as $item) {
                    $unique[] = $item[0];
                }
                $unique = array_unique($unique);
                $attrCombine = array();
                foreach ($unique as $item) {
                    $attrCombine[$item][] = $item;
                    foreach ($saleAttrArr as $v) {
                        if ($v[0] == $item && count($v) == 2) {
                            $attrCombine[$item][] = $v[1];
                        }
                    }
                }
                $attrCombine = urldecode(json_encode($attrCombine));
                $this -> assign('goodsSpec', $attrCombine);*/
                //组合完毕,将值传给模板
            }

            $history = cookie("history_goods");
            $history = is_array($history)?$history:[];
            array_unshift($history, (string)$goods['goodsId']);
            $history = array_values(array_unique($history));
            
            if(!empty($history)){
                cookie("history_goods",$history,25920000);
            }
            $this->assign('goods',$goods);
            $this->assign('shop',$goods['shop']);
            return $this->fetch("goods_detail");
        }else{
            return $this->fetch("error_lost");
        }
    }
    /**
     * 预警库存
     */
    public function stockwarnbypage(){
        return $this->fetch("shops/stockwarn/list");
    }
    /**
     * 获取预警库存列表
     */
    public function stockByPage(){
        $m = new M();
        $rs = $m->stockByPage();
        $rs['status'] = 1;
        return $rs;
    }
    /**
     * 修改预警库存
     */
    public function editwarnStock(){
        $m = new M();
        return $m->editwarnStock();
    }
    
    /**
     * 获取商品浏览记录
     */
    public function historyByGoods(){
        $rs = model('Tags')->historyByGoods(8);
        return WSTReturn('',1,$rs);
    }
    
}
