<?php
namespace wstmart\common\model;
use wstmart\common\model\GoodsCats;
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
 * 商品分类类
 */
class GoodsCats extends Base{
	/**
	 * 获取列表
	 */
	public function listQuery($parentId,$isFloor = -1){
		$dbo = $this->where(['dataFlag'=>1,'isShow'=>1,'parentId'=>$parentId]);
		if($isFloor!=-1)$dbo->where('isFloor',$isFloor);
		return $dbo->order('catSort asc')->select();
	}
	
	/**
	 * 根据子分类获取其父级分类
	 */
	public function getParentIs($id,$data = array()){
		$data[] = $id;
		$parentId = $this->where('catId',$id)->value('parentId');
		if($parentId==0){
			krsort($data);
			return $data;
		}else{
			return $this->getParentIs($parentId, $data);
		}
	}
	public function getParentNames($id){
		if($id<=0)return [];
	    $ids = $this->getParentIs($id);
        $rs = Db::name('goodsCats')->where('catId','in',$ids)->field('catName')->order('catId desc')->select();
        $names = [];
        foreach($rs as $v){
            $names[] = $v['catName'];
        }
        return $names;
	}
   /**
     * 获取首页楼层
     */
    public function getFloors(){
	    $cats1 = Db::name('goods_cats')->where(['dataFlag'=>1, 'isShow' => 1,'parentId'=>0,'isFloor'=>1])
		             ->field("catName,catId")->order('catId asc')->limit(10)->select();
		if(!empty($cats1)){
			$ids = [];
			foreach ($cats1 as $key =>$v){
				$ids[] = $v['catId'];
			}
			$cats2 = [];
			$rs = Db::name('goods_cats')->where(['dataFlag'=>1, 'isShow' => 1,'parentId'=>['in',$ids],'isFloor'=>1])
				             ->field("parentId,catName,catId")->order('catId asc')->select();
			foreach ($rs as $key => $v){
				$cats2[$v['parentId']][] = $v;
			}
			foreach ($cats1 as $key =>$v){
				$cats1[$key]['children'] = (isset($cats2[$v['catId']]))?$cats2[$v['catId']]:[];
			}
		}

		//添加代码start
		$gc = new GoodsCats();
		if(!empty($cats1)){
			foreach ($cats1 as $key =>$v){
				$catId = "'".$v['catId']."|_%" ."'". "escape '|' ";
				header("content-type:text/html; charset=utf-8");
				$rs = Db::name('goods')->where("goodsCatIdPath like ".$catId)
									   ->where("isHot",1)
									   ->where("isSale",1)
									   ->where("goodsStatus",1)
									   ->where("dataFlag",1)
									   ->order('visitNum desc')
				                       ->limit(7)->select();
				foreach ($rs as $key1 => $v1){
					if($key1 == 0){
						$cats1[$key]['left'] = $v1;
						unset($rs[$key1]);
						break;
					}
				}
				$cats1[$key]['data'] = $rs;
			}
		}
		//添加代码end

		return $cats1;
    }
}
