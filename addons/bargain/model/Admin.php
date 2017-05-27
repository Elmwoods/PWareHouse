<?php
namespace addons\bargain\model;
use think\addons\BaseModel as Base;
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
 * 全民砍价活动插件-管理员端
 */
class Admin extends Base{
	/**
	 * 管理员查看砍价活动列表
	 */
	public function pageQuery($grouponStatus){
		$goodsName = input('goodsName');
		$shopName = input('shopName');
		$areaIdPath = input('areaIdPath');
		$goodsCatIdPath = input('goodsCatIdPath');
		$where = ['b.dataFlag'=>1];
		$where['bargainStatus'] = $grouponStatus;
		if($goodsName !='')$where['g.goodsName'] = ['like','%'.$goodsName.'%'];
		if($shopName !='')$where['s.shopName|s.shopSn'] = ['like','%'.$shopName.'%'];
		if($areaIdPath !='')$where['s.areaIdPath'] = ['like',$areaIdPath."%"];
		if($goodsCatIdPath !='')$where['g.goodsCatIdPath'] = ['like',$goodsCatIdPath."%"];
        $page =  Db::name('bargains')->alias('b')
                   ->join('__GOODS__ g','g.goodsId=b.goodsId','inner')
                   ->join('__SHOPS__ s','s.shopId=b.shopId','left')
                   ->where($where)
                   ->field('g.goodsName,b.*,g.goodsImg,s.shopId,s.shopName')
                   ->order('b.updateTime desc')
                   ->paginate(input('pagesize/d'))->toArray();
        if(count($page['Rows'])>0){
        	$time = time();
        	foreach($page['Rows'] as $key =>$v){
        		$page['Rows'][$key]['goodsImg'] = WSTImg($v['goodsImg']);
        		if(strtotime($v['startTime'])<=$time && strtotime($v['endTime'])>=$time){
        			$page['Rows'][$key]['status'] = 1; 
        		}else if(strtotime($v['startTime'])>$time){
                    $page['Rows'][$key]['status'] = 0; 
        		}else{
        			$page['Rows'][$key]['status'] = -1; 
        		}
        	}
        }
        return $page;
	}

	/**
	* 设置商品违规状态
	*/
	public function illegal(){
		$illegalRemarks = input('post.illegalRemarks');		
		$id = (int)input('post.id');
		if($illegalRemarks=='')return WSTReturn("请输入违规原因");
		//判断商品状态
		$rs = Db::name('bargains')->alias('b')
		           ->join('__SHOPS__ s','b.shopId=s.shopId','left')
		           ->join('__GOODS__ g','g.goodsId=b.goodsId')
		           ->where('bargainId',$id)
		           ->field('b.bargainId,s.userId,g.goodsName,b.bargainStatus,b.goodsId')->find();
		if((int)$rs['bargainId']==0)return WSTReturn("无效的商品");
		if((int)$rs['bargainStatus']==-1)return WSTReturn("操作失败，商品状态已发生改变，请刷新后再尝试");
		Db::startTrans();
		try{
			$res = Db::name('bargains')->where('bargainId',$id)->update(['bargainStatus'=>-1,'illegalRemarks'=>$illegalRemarks]);
			if($res!==false){
				//发送一条商家信息
				$tpl = WSTMsgTemplates('BARGAIN_GOODS_REJECT');
		        if($tpl['tplContent']!=''){
		            $find = ['${GOODS}','${TIME}','${REASON}'];
		            $replace = [$rs['goodsName'],date('Y-m-d H:i:s'),$illegalRemarks];
		            WSTSendMsg($rs['userId'],str_replace($find,$replace,$tpl['tplContent']),['from'=>7,'dataId'=>$id]);
		        }
		        if((int)WSTConf('CONF.wxenabled')==1){
					$params = [];
					$params['GOODS'] = $rs['goodsName'];
					$params['TIME'] = date('Y-m-d H:i:s'); 
					$params['REASON'] = $illegalRemarks;          
					WSTWxMessage(['CODE'=>'WX_BARGAIN_GOODS_REJECT','userId'=>$rs['userId'],'params'=>$params]);
				}
				Db::commit();
				return WSTReturn('操作成功',1);
			}
		}catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('操作失败',-1);
	}
   /**
	* 通过商品审核
	*/
	public function allow(){	
		$id = (int)input('post.id');
		//判断商品状态
		$rs = Db::name('bargains')->alias('b')
		           ->join('__SHOPS__ s','b.shopId=s.shopId','left')
		           ->join('__GOODS__ g','g.goodsId=b.goodsId')
		           ->where('bargainId',$id)
		           ->field('b.bargainId,s.userId,g.goodsName,b.bargainStatus,b.goodsId')->find();
		if((int)$rs['bargainId']==0)return WSTReturn("无效的商品");
		if((int)$rs['bargainStatus']!=0)return WSTReturn("操作失败，商品状态已发生改变，请刷新后再尝试");
		Db::startTrans();
		try{
			$res = Db::name('bargains')->where('bargainId',$id)->update(['bargainStatus'=>1]);
			if($res!==false){
				//发送一条商家信息
				$tpl = WSTMsgTemplates('BARGAIN_GOODS_ALLOW');
		        if($tpl['tplContent']!=''){
		            $find = ['${GOODS}','${TIME}'];
		            $replace = [$rs['goodsName'],date('Y-m-d H:i:s')];
		            WSTSendMsg($rs['userId'],str_replace($find,$replace,$tpl['tplContent']),['from'=>7,'dataId'=>$id]);
		        } 
		        if((int)WSTConf('CONF.wxenabled')==1){
					$params = [];
					$params['GOODS'] = $rs['goodsName'];
					$params['TIME'] = date('Y-m-d H:i:s');          
					WSTWxMessage(['CODE'=>'WX_BARGAIN_GOODS_ALLOW','userId'=>$rs['userId'],'params'=>$params]);
				}
				Db::commit();
				return WSTReturn('操作成功',1);
			}
		}catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('操作失败',-1);
	}

    /**
	 * 删除拍卖
	 */
	public function del(){
		$id = (int)input('id');
        Db::startTrans();
        try{
        	Db::name('bargains')->where('bargainId',$id)->update(['dataFlag'=>-1]);
            Db::commit();
        }catch (\Exception $e) {
            Db::rollback();
        }
		
        return WSTReturn('删除成功',1);
	}

	/**
	 * 获取参与者记录
	 */
	public function pageyByJoins(){
		$key = input('key');
		$where = [];
		if($key!='')$where['u.loginName'] = ['like','%'.$key.'%'];
		$where['bu.bargainId'] = (int)input('bargainId');
		return Db::name('bargain_users')->alias('bu')
		         ->join('__BARGAINS__ b','b.bargainId=bu.bargainId','inner')
		         ->join('__USERS__ u','u.userId=bu.userId')
                 ->where($where)
                 ->field('bu.*,u.userName,u.userPhoto,b.startPrice,u.loginName')
                 ->order('bu.createTime desc')
                 ->paginate(input('pagesize/d'))->toArray();
	}

	/**
	 * 获取亲友团列表
	 */
    public function pageByHelps(){
		$where = [];
		$where['bargainJoinId'] = (int)input('bargainJoinId');
		return Db::name('bargain_helps')
                 ->where($where)
                 ->order('createTime desc')
                 ->paginate(input('pagesize/d'))->toArray();
	}
}
