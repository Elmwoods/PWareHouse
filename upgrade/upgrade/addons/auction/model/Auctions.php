<?php
namespace addons\auction\model;
use think\addons\BaseModel as Base;
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
 * 拍卖活动插件
 */
class Auctions extends Base{
	/***
     * 安装插件
     */
    public function installMenu(){
    	Db::startTrans();
		try{
			$hooks = ['beforeCancelOrder','wechatDocumentUserIndexTools','mobileDocumentUserIndexTools'];
			$this->bindHoods("Auction", $hooks);
			//管理员后台
			$rs = Db::name('menus')->insert(["parentId"=>93,"menuName"=>"拍卖活动","menuSort"=>1,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"auction"]);
			if($rs!==false){
				$datas = [];
				$parentId = Db::name('menus')->getLastInsID();
				$datas[] = ["menuId"=>$parentId,"privilegeCode"=>"AUCTION_PMHD_00","privilegeName"=>"查看拍卖活动","isMenuPrivilege"=>1,"privilegeUrl"=>"addon/auction-goods-pageByAdmin","otherPrivilegeUrl"=>"addon/auction-goods-pageQueryByAdmin,addon/auction-goods-pageAuditQueryByAdmin,addon/auction-goods-auctionLogByAdmin,,addon/auction-goods-pageAuctionLogQueryByAdmin","dataFlag"=>1,"isEnable"=>1];
				$datas[] = ["menuId"=>$parentId,"privilegeCode"=>"AUCTION_PMHD_04","privilegeName"=>"拍卖商品操作","isMenuPrivilege"=>0,"privilegeUrl"=>"","otherPrivilegeUrl"=>"addon/auction-goods-allow,addon/auction-goods-illegal","dataFlag"=>1,"isEnable"=>1];
				$datas[] = ["menuId"=>$parentId,"privilegeCode"=>"AUCTION_PMHD_03","privilegeName"=>"删除拍卖商品","isMenuPrivilege"=>0,"privilegeUrl"=>"addon/auction-goods-delByAdmin","otherPrivilegeUrl"=>"","dataFlag"=>1,"isEnable"=>1];
				Db::name('privileges')->insertAll($datas);
			}
			
			$now = date("Y-m-d H:i:s");
			//商家中心
			Db::name('home_menus')->insert(["parentId"=>77,"menuName"=>"拍卖活动","menuUrl"=>"addon/auction-shops-auction","menuOtherUrl"=>"addon/auction-shops-auction,addon/auction-shops-pageQuery,addon/auction-shops-searchGoods,addon/auction-shops-edit,addon/auction-shops-toEdit,addon/auction-shops-del","menuType"=>1,"isShow"=>1,"menuSort"=>1,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"auction"]);
			//用户中心
			$rs = Db::name('home_menus')->insert(["parentId"=>100,"menuName"=>"拍卖活动","menuUrl"=>"addon/auction-users-auction","menuOtherUrl"=>"addon/auction-users-pageQuery","menuType"=>0,"isShow"=>1,"menuSort"=>1,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"auction"]);
			if($rs!==false){
				$parentId = Db::name('home_menus')->getLastInsID();
			    Db::name('home_menus')->insert(["parentId"=>$parentId,"menuName"=>"我参与的拍卖","menuUrl"=>"addon/auction-users-auction","menuOtherUrl"=>"addon/auction-users-pageQuery,addon/auction-users-checkPayStatus,addon/auction-users-submit","menuType"=>0,"isShow"=>1,"menuSort"=>1,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"auction"]);
			    Db::name('home_menus')->insert(["parentId"=>$parentId,"menuName"=>"我的保证金","menuUrl"=>"addon/auction-users-money","menuOtherUrl"=>"addon/auction-users-pageQueryByMoney","menuType"=>0,"isShow"=>1,"menuSort"=>1,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"auction"]);
			}
			installSql("auction");
			$this->addMobileBtn();
			Db::commit();
			return true;
		}catch (\Exception $e) {
	 		Db::rollback();
	  		return false;
	   	}
    }

	/**
	 * 删除菜单
	 */
	public function uninstallMenu(){
		Db::startTrans();
		try{
			$hooks = ['beforeCancelOrder','wechatDocumentUserIndexTools','mobileDocumentUserIndexTools'];
			$this->unbindHoods("Auction", $hooks);
			Db::name('menus')->where(["menuMark"=>"auction"])->delete();
			Db::name('home_menus')->where(["menuMark"=>"auction"])->delete();
			Db::name('privileges')->where(["privilegeCode"=>array("like","AUCTION_%")])->delete();
            //删除微信参数数据
			$tplMsgIds = Db::name('template_msgs')->where('tplCode','in',explode(',','AUCTION_GOODS_ALLOW,AUCTION_GOODS_REJECT,WX_AUCTION_GOODS_ALLOW,WX_AUCTION_GOODS_REJECT,AUCTION_USER_RESULT,AUCTION_SHOP_RESULT,WX_AUCTION_USER_RESULT,WX_AUCTION_SHOP_RESULT'))
			  ->column('id');
			if((int)WSTConf('CONF.wxenabled')==1)Db::name('wx_template_params')->where('parentId','in',$tplMsgIds)->delete();
			uninstallSql("auction");//传入插件名
			$this->delMobileBtn();
			Db::commit();
			return true;
		}catch (\Exception $e) {
	 		Db::rollback();
	  		return false;
	   	}
	}

	
	/**
	 * 菜单显示隐藏
	 */
	public function toggleShow($isShow = 1){
		Db::startTrans();
		try{
			Db::name('menus')->where(["menuMark"=>"auction"])->update(["isShow"=>$isShow]);
			Db::name('home_menus')->where(["menuMark"=>"auction"])->update(["isShow"=>$isShow]);
			Db::name('navs')->where(["navUrl"=>"index.php/addon/auction-goods-lists.html"])->update(["isShow"=>$isShow]);
			if($isShow==1){
				$this->addMobileBtn();
			}else{
				$this->delMobileBtn();
			}
			Db::commit();
			return true;
		}catch (\Exception $e) {
			Db::rollback();
			return false;
		}
	}
	
	public function addMobileBtn(){
	
		$data = array();
		$data["btnName"] = "拍卖活动";
		$data["btnSrc"] = 0;
		$data["btnUrl"] = "/addon/auction-goods-molists";
		$data["btnImg"] = "addons/auction/view/mobile/index/img/auction.png";
		$data["addonsName"] = "Auction";
		$data["btnSort"] = 6;
		Db::name('mobile_btns')->insert($data);
	
		$data = array();
		$data["btnName"] = "拍卖活动";
		$data["btnSrc"] = 1;
		$data["btnUrl"] = "/addon/auction-goods-wxlists";
		$data["btnImg"] = "addons/auction/view/wechat/index/img/auction.png";
		$data["addonsName"] = "Auction";
		$data["btnSort"] = 6;
		Db::name('mobile_btns')->insert($data);
	}
	
	public function delMobileBtn(){
		Db::name('mobile_btns')->where(["addonsName"=>"Auction"])->delete();
	}


	/**
	 * 取消团购订单
	 */
	public function beforeCancelOrder($params){
		$order = DB::name('orders')->where('orderId',$params['orderId'])->field('orderCode')->find();
		if($order['orderCode']=='auction')die('{"status":-1,msg:"对不起，拍卖订单不允许取消"}');
	}
	
	/**
     * 商家获取拍卖列表
     */
	public function pageQueryByShop(){
		$goodsName = input('goodsName');
		$shopId = (int)session('WST_USER.shopId');
		$where = ['shopId'=>$shopId,'dataFlag'=>1];
		if($goodsName !='')$where['goodsName'] = ['like','%'.$goodsName.'%'];
        $page =  $this->where($where)
                      ->order('updateTime desc')
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
        		$page['Rows'][$key]['editable'] = ($v['auctionNum']==0 && $v['isClose']==0 )?1:0;
        	}
        }
        $page['status'] = 1;
        return $page;
	}

    /**
     * 搜索商品
     */
    public function searchGoods(){
    	$shopId = (int)session('WST_USER.shopId');
    	$shopCatId1 = (int)input('post.shopCatId1');
    	$shopCatId2 = (int)input('post.shopCatId2');
    	$goodsName = input('post.goodsName');
    	$where = [];
    	$where['goodsStatus'] = 1;
    	$where['dataFlag'] = 1;
    	$where['isSale'] = 1;
    	$where['goodsType'] = 0;
    	$where['shopId'] = $shopId;
    	if($shopCatId1>0)$where['shopCatId1'] = $shopCatId1;
    	if($shopCatId2>0)$where['shopCatId2'] = $shopCatId2;
    	if($goodsName!='')$where['goodsName'] = ['like','%'.$goodsName.'%'];
    	$rs = Db::name('goods')->where($where)->field('goodsName,goodsId,marketPrice,shopPrice')->select();
        return WSTReturn('',1,$rs);
    }

	/**
	 *  获取拍卖商品
	 */
	public function getById($id){
		$where = [];
		$where['gu.shopId'] = (int)session('WST_USER.shopId');
		$where['gu.auctionId'] = $id;
		$where['gu.dataFlag'] = 1;
		$where['gu.dataFlag'] = 1;
		return $this->alias('gu')->join('__GOODS__ g','g.goodsId=gu.goodsId','left')->where($where)->field('gu.goodsName,gu.goodsImg,g.marketPrice,g.shopPrice,gu.*')->find();
	}

	/**
	 * 获取规格数组
	 */
	public function getSpecs($goodsId){
		$sales = Db::name('goods_specs')->where(['goodsId'=>$goodsId,'isDefault'=>1])->field('specIds')->find();
		$specIds = [];
		if(!empty($sales)){
			$specIds = explode(':',$sales['specIds']);
			sort($specIds);
		}
		$spec = [];
		//获取默认规格值
		$specs = Db::name('spec_cats')->alias('gc')
				   ->join('__SPEC_ITEMS__ sit','gc.catId=sit.catId','inner')
				   ->where(['sit.goodsId'=>$goodsId,'gc.isShow'=>1,'sit.dataFlag'=>1])
				   ->field('gc.isAllowImg,gc.catName,sit.catId,sit.itemId,sit.itemName,sit.itemImg')
				   ->order('gc.isAllowImg desc,gc.catSort asc,gc.catId asc')->select();                     
		foreach ($specs as $key =>$v){
			if(in_array($v['itemId'],$specIds)){
				$catId = $v['catId'];
				$spec[$catId]['name'] = $v['catName'];
				unset($v['catName'],$v['catId']);
				$spec[$catId]['list'][] = $v;
			}
		}
		return $spec;
	}

	/**
	 * 新增拍卖
	 */
	public function add(){
		$data = input('post.');
		$shopId = (int)session('WST_USER.shopId');
		$goods = model('common/Goods')->get((int)$data['goodsId']);
		if($goods->goodsStatus!=1 || $goods->goodsType!=0 || $goods->isSale!=1 || $goods->dataFlag!=1 || $goods->shopId != $shopId)return WSTReturn('无效的商品');
		if((int)$data['auctionPrice']<0)return WSTReturn('起拍价不能小于0');
		if((int)$data['fareInc']<=0)return WSTReturn('加价幅度必须大于0');
		if($data['startTime']=='' || $data['endTime']=='')return WSTReturn('请选择有效拍卖时间');
		if(strtotime($data['startTime']) >= strtotime($data['endTime']))return WSTReturn('拍卖开始时间必须比拍卖结束时间早');
		//判断是否已经存在同时间的拍卖
		$where = [];
		$where['goodsId'] = (int)$data['goodsId'];
		$where['dataFlag'] = 1;
		$whereOr = ' ( ("'.date('Y-m-d H:i:s',strtotime($data['startTime'])).'" between startTime and endTime) or ( "'.date('Y-m-d H:i:s',strtotime($data['endTime'])).'" between startTime and endTime) ) ';
		$rn = $this->where($where)->where($whereOr)->Count();
		if($rn>0)return WSTReturn('该商品已存在另外一个相同时段的拍卖活动中');
		WSTUnset($data,'auctionId,cat_0,illegalRemarks');
		$specs = [];
		if($goods->isSpec==1){
			$specs = $this->getSpecs($goods->goodsId);
		}
		$data['shopId'] = $shopId;
		$data['goodsName'] = $goods->goodsName;
		$data['goodsImg'] = $goods->goodsImg;
		$data['goodsJson'] = json_encode(['gallery'=>$goods->gallery,'specs'=>$specs]);
		$data['dataFlag'] = 1;
		$data['orderNum'] = 0;
		$data['currPrice'] = $data['auctionPrice'];
		$data['auctionStatus'] = 0;
		$data['updateTime'] = date('Y-m-d H:i:s');
		$data['createTime'] = date('Y-m-d H:i:s');
		$result = $this->allowField(true)->save($data);
		if(false !== $result){
			return WSTReturn('新增成功',1);
		}
		return WSTReturn('新增失败');
	}

	/**
	 * 编辑拍卖 
	 */
	public function edit(){
		$data = input('post.');
		$shopId = (int)session('WST_USER.shopId');
		$grouponId = $data['auctionId'];
		$auction = $this->get($grouponId);
		if($auction->shopId!=$shopId)return WSTReturn('无效的拍卖记录');
		if($auction->isClose==1)return WSTReturn('已结束的拍卖活动不允许修改');
		if($auction->auctionNum>0)return WSTReturn('已有拍卖者参与的活动不允许修改');
		//如果有改变商品则更新内容
		if($auction->goodsId!=(int)$data['goodsId']){
			$goods = model('common/Goods')->get((int)$data['goodsId']);
		    if($goods->goodsStatus!=1 || $goods->goodsType!=0 || $goods->isSale!=1 || $goods->dataFlag!=1 || $goods->shopId != $shopId)return WSTReturn('无效的商品');
		    $specs = [];
			if($goods->isSpec==1){
				$specs = $this->getSpecs($goods->goodsId);
			}
			$auction->goodsId = $goods->goodsId;
			$auction->goodsName = $goods->goodsName;
		    $auction->goodsImg = $goods->goodsImg;
		    $auction->goodsJson = json_encode(['gallery'=>$goods->gallery,'specs'=>$specs]);
		}
		if((int)$data['auctionPrice']<0)return WSTReturn('起拍价数量不能小于0');
		if((int)$data['fareInc']<=0)return WSTReturn('加价幅度必须大于0');
		if($data['startTime']=='' || $data['endTime']=='')return WSTReturn('请选择有效拍卖时间');
		if(strtotime($data['startTime']) >= strtotime($data['endTime']))return WSTReturn('拍卖开始时间必须比拍卖结束时间早');
		//判断是否已经存在同时间的拍卖
		$where = [];
		$where['goodsId'] = $data['goodsId'];
		$where['auctionId'] = ['<>',$data['auctionId']];
		$where['dataFlag'] = 1;
		$whereOr = ' ( ("'.date('Y-m-d H:i:s',strtotime($data['startTime'])).'" between startTime and endTime) or ( "'.date('Y-m-d H:i:s',strtotime($data['endTime'])).'" between startTime and endTime) ) ';
		$rn = $this->where($where)->where($whereOr)->Count();
		if($rn>0)return WSTReturn('该商品已存在另外一个相同时段的拍卖活动中');
		$auction->auctionStatus = 0;
		$auction->updateTime = date('Y-m-d H:i:s');
		$result = $auction->save();
		if(false !== $result){
			return WSTReturn('编辑成功',1);
		}
		return WSTReturn('编辑失败');
	}

	/**
	 * 删除拍卖
	 */
	public function del(){
		$id = (int)input('id');
		$shopId = (int)session('WST_USER.shopId');
		$auction = $this->get($id);
		if($auction->shopId != $shopId)return WSTReturn('非法的操作');
		if($auction->auctionNum>0)return WSTReturn('已有拍卖者参与的活动不允许删除');
        $auction->dataFlag = -1;
        $auction->save();
        return WSTReturn('删除成功',1);
	}

    /***
	 * 获取前台团购列表
	 */
	public function pageQuery(){
		$goodsCatId = (int)input('catId');
		$goodsName = input('goodsName');
		$areaId = (int)input('areaId');
		$where = [];
		if($goodsCatId>0){
			$gc = new GoodsCats();
			$goodsCatIds = $gc->getParentIs($goodsCatId);
			$where['goodsCatIdPath'] = ['like',implode('_',$goodsCatIds).'_%'];
		}
		if($goodsName!='')$where['gu.goodsName'] = ['like','%'.$goodsName.'%'];
		$page = Db::name('auctions')->alias('gu')->join('__GOODS__ g','gu.goodsId=g.goodsId','inner')
		          ->where('gu.dataFlag=1 and gu.auctionStatus=1')
		          ->where($where)
		          ->field('gu.goodsId,gu.goodsImg,gu.goodsName,gu.currPrice,gu.startTime,gu.endTime,gu.auctionId,gu.auctionNum')
		          ->order('gu.startTime asc,gu.updateTime desc')
		          ->paginate(input('pagesize/d'))->toArray();
		if(count($page)>0){
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
	 * 获取团购详情
	 */
	public function getBySale($auctionId){
		$key = input('key');
		$where = ['dataFlag'=>1,'auctionId'=>$auctionId];
		$gu = $this->where($where)->find();
		$viKey = WSTShopEncrypt($gu['shopId']);
        if($key!=''){	
            if($viKey!=$key && $gu['auctionStatus']!=1)return [];
        }else{
        	if($gu['auctionStatus']!=1)return [];
        }
		$goodsId = $gu['goodsId'];
		if(empty($gu))return [];
		$gu = $gu->toArray();
		$goods = Db::name('goods')->where('goodsId',$gu['goodsId'])->field('goodsCatId')->find();
		$gu['goodsCatId'] = $goods['goodsCatId'];
		WSTUnset($gu,'illegalRemarks,dataFlag,updateTime,createTime,orderId,bidLogId,isPay');
		Db::name('auctions')->where('auctionId',$auctionId)->setInc('visitNum',1);
		$time = time();
		if(strtotime($gu['startTime'])<=$time && strtotime($gu['endTime'])>=$time){
        	$gu['status'] = 1; 
        }else if(strtotime($gu['startTime'])>$time){
            $gu['status'] = 0; 
        }else{
        	$gu['status'] = -1; 
        }
		$gu['read'] = false;
		//判断是否可以公开查看
		if($key!='')$gu['read'] = true;
		//获取店铺信息
		$gu['shop'] = model('common/shops')->getBriefShop((int)$gu['shopId']);

		if(empty($gu['shop']))return [];
		$gu['goodsJson'] = json_decode($gu['goodsJson'],true);
		$gallery = [];
		$gallery[] = $gu['goodsImg'];
		if($gu['goodsJson']['gallery']!=''){
			$tmp = explode(',',$gu['goodsJson']['gallery']);
			$gallery = array_merge($gallery,$tmp);
		}
		$gu['gallery'] = $gallery;
		if(!empty($gu['goodsJson']['specs']))$gu['spec'] = $gu['goodsJson']['specs'];
        unset($gu['goodsJson']);
		//关注
		$gu['favShop'] = model('common/Favorites')->checkFavorite($gu['shopId'],1);
		//判断是否支付保证金
		$userId = (int)session('WST_USER.userId');
		$gu['payMoney'] = 0;
		if($userId>0){
			$gu['payMoney']  = Db::name('auction_moneys')->where(['auctionId'=>$gu['auctionId'],'userId'=>$userId])->count();
		}
		$conf=$this->getConf('Auction');
		//获取拍卖须知
		$article = Db::name('articles')->field('articleContent')->where(['isShow'=>1,'dataFlag'=>1,'articleId'=>(int)$conf['auctionArticleId']])->find();
		$gu['article'] = $article['articleContent'];
		return $gu;
	}




	/**
	 * 管理员查看拍卖列表
	 */
	public function pageQueryByAdmin($grouponStatus){
		$goodsName = input('goodsName');
		$shopName = input('shopName');
		$areaIdPath = input('areaIdPath');
		$goodsCatIdPath = input('goodsCatIdPath');
		$where = ['gu.dataFlag'=>1];
		$where['auctionStatus'] = $grouponStatus;
		if($goodsName !='')$where['gu.goodsName'] = ['like','%'.$goodsName.'%'];
		if($shopName !='')$where['s.shopName|s.shopSn'] = ['like','%'.$shopName.'%'];
		if($areaIdPath !='')$where['s.areaIdPath'] = ['like',$areaIdPath."%"];
		if($goodsCatIdPath !='')$where['g.goodsCatIdPath'] = ['like',$goodsCatIdPath."%"];
        $page =  $this->alias('gu')->join('__GOODS__ g','g.goodsId=gu.goodsId','inner')
                      ->join('__SHOPS__ s','s.shopId=gu.shopId','left')
                      ->where($where)
                      ->field('gu.goodsName,gu.*,gu.goodsImg,s.shopId,s.shopName')
                      ->order('gu.updateTime desc')
                      ->paginate(input('pagesize/d'))->toArray();
        if(count($page['Rows'])>0){
        	$time = time();
        	foreach($page['Rows'] as $key =>$v){
        		$page['Rows'][$key]['goodsImg'] = WSTImg($v['goodsImg']);
        		$page['Rows'][$key]['verfiycode'] = WSTShopEncrypt($v['shopId']);
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
		$rs = $this->alias('gu')
		           ->join('__SHOPS__ s','gu.shopId=s.shopId','left')
		           ->where('auctionId',$id)
		           ->field('gu.auctionId,s.userId,gu.goodsName,gu.auctionStatus,gu.goodsId,gu.auctionNum')->find();
		if((int)$rs['auctionId']==0)return WSTReturn("无效的商品");
		if((int)$rs['auctionNum']>0)return WSTReturn('已有参与者的拍卖只能删除不能下架');
		if((int)$rs['auctionStatus']==-1)return WSTReturn("操作失败，商品状态已发生改变，请刷新后再尝试");
		Db::startTrans();
		try{
			$res = $this->where('auctionId',$id)->setField(['auctionStatus'=>-1,'illegalRemarks'=>$illegalRemarks]);
			if($res!==false){
				//发送一条商家信息
				$tpl = WSTMsgTemplates('AUCTION_GOODS_REJECT');
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
					WSTWxMessage(['CODE'=>'WX_AUCTION_GOODS_REJECT','userId'=>$rs['userId'],'params'=>$params]);
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
		$rs = $this->alias('gu')
		           ->join('__SHOPS__ s','gu.shopId=s.shopId','left')
		           ->where('auctionId',$id)
		           ->field('gu.auctionId,s.userId,gu.goodsName,gu.auctionStatus,gu.goodsId')->find();
		if((int)$rs['auctionId']==0)return WSTReturn("无效的商品");
		if((int)$rs['auctionStatus']!=0)return WSTReturn("操作失败，商品状态已发生改变，请刷新后再尝试");
		Db::startTrans();
		try{
			$res = $this->where('auctionId',$id)->setField(['auctionStatus'=>1]);
			if($res!==false){
				//发送一条商家信息
				$tpl = WSTMsgTemplates('AUCTION_GOODS_ALLOW');
		        if($tpl['tplContent']!=''){
		            $find = ['${GOODS}','${TIME}'];
		            $replace = [$rs['goodsName'],date('Y-m-d H:i:s')];
		            WSTSendMsg($rs['userId'],str_replace($find,$replace,$tpl['tplContent']),['from'=>7,'dataId'=>$id]);
		        } 
		        if((int)WSTConf('CONF.wxenabled')==1){
					$params = [];
					$params['GOODS'] = $rs['goodsName'];
					$params['TIME'] = date('Y-m-d H:i:s');          
					WSTWxMessage(['CODE'=>'WX_AUCTION_GOODS_ALLOW','userId'=>$rs['userId'],'params'=>$params]);
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
	public function delByAdmin(){
		$id = (int)input('id');
        $auction = $this->get($id);
        if($auction->auctionNum>0 && $auction->bidLogId==0)return WSTReturn('未完成拍卖订单的活动不允许删除');
        Db::startTrans();
        try{
        	$auction->dataFlag = -1;
            $auction->save();
            //没有结束的订单则全部退回保证金
            if($auction->isClose==0){
                $rs = Db::name('auction_moneys')->where(['auctionId'=>$auctionId,'moneyType'=>1,'cautionStatus'=>1])->field('userId,cautionMoney,createTime')->select();
	            if(!empty($rs)){
	            	$logUsers = [];
	            	foreach($rs as $key =>$v){
	            		$logUsers[] = $v['userId'];
	            		$lm = [];
						$lm['targetType'] = 0;
						$lm['targetId'] = $lv['userId'];
						$lm['dataId'] = $id;
						$lm['dataSrc'] = 'auction';
						$lm['remark'] ='下架拍卖活动【'.$auction->goodsName.'】，退回保证金¥'.$v['cautionMoney'];
						$lm['moneyType'] = 1;
						$lm['money'] = $v['cautionMoney'];
						$lm['payType'] = '0';
						$lm['tradeNo'] = '';
						$lm['createTime'] = date('Y-m-d H:i:s');
						model('common/LogMoneys')->add($lm);

						$tpl = WSTMsgTemplates('AUCTION_USER_RESULT');
				        if($tpl['tplContent']!=''){
				            $find = ['${GOODS}','${JOIN_TIME}','${ASTART_TIME}','${RESULT}'];
				            $replace = [$auction->goodsName,$v['createTime'],$auction->startTime,'拍卖下架，退回保证金'];
				            WSTSendMsg($log['userId'],str_replace($find,$replace,$tpl['tplContent']),['from'=>'auction','dataId'=>$auction->auctionId]);
				        }
				        if((int)WSTConf('CONF.wxenabled')==1){
				        	$params = [];
			                $params['GOODS'] = $auction->goodsName;
			                $params['JOIN_TIME'] = $v['createTime'];
		                    $params['ASTART_TIME'] = $auction->startTime;
		                    $params['RESULT'] = '拍卖下架，退回保证金';
				            WSTWxMessage(['CODE'=>'WX_AUCTION_USER_RESULT','userId'=>$log['userId'],'params'=>$params]);
				        }
			        }
			        if(count($logUsers)>0){
				        Db::name('auction_moneys')
				          ->where('cautionStatus=1 and auctionId='.$id.' and moneyType=1 and userId in('.implode(',',$logUsers).')')
				          ->update(['cautionStatus'=>2]);
			        }
	            }
            }
            Db::commit();
        }catch (\Exception $e) {
            Db::rollback();
        }
		
        return WSTReturn('删除成功',1);
	}

	/**
	 * 查询查询竞拍记录
	 */
	public function pageAuctionLogQuery($auctionId,$isAdmin = false){
		$where = ['auctionId'=>$auctionId];
		if(!$isAdmin)$where['shopId'] = (int)session('WST_USER.shopId');
		$auction = Db::name('auctions')->where($where)
		             ->field('orderId,currPrice')->find();
        if(empty($auction))return WSTReturn('',-1);
        $page =  Db::name('auction_logs')->alias('a')
                  ->join('__USERS__ u','a.userId=u.userId')
                  ->where('a.dataFlag>=0 and a.auctionId='.$auctionId)
                  ->field('u.loginName,a.payPrice,a.createTime,a.isTop')
                  ->order('payPrice desc,createTime desc')
                  ->paginate(input('pagesize/d'))->toArray();
        if(count($page['Rows'])>0){
        	 $order = ['orderNo'=>''];
        	 if($auction['orderId'] > 0){
        	      $order = Db::name('orders')->where('orderId',$auction['orderId'])->field('orderNo')->find();
             }
        	foreach ($page['Rows'] as $k => $v) {
        		$page['Rows'][$k]['orderId'] = '';
        		$page['Rows'][$k]['orderNo'] = '';
        		if($auction['currPrice']==$v['payPrice']){
        			$page['Rows'][$k]['orderNo'] = $order['orderNo'];
        			$page['Rows'][$k]['orderId'] = $auction['orderId'];
        		}
        	}
        }
        return WSTReturn('',1,$page);
	}


	/**
	 * 拍卖报价
	 */
	public function addAcution(){
		$userId = (int)session('WST_USER.userId');
		$auctionId = (int)input('id');
		$payPrice = (int)input('payPrice');
		//判断出价是否大于当前报价
		$auction = $this->get($auctionId);
		if($auction->isClose!=0)return WSTReturn('出价失败，拍卖已结束');
		//判断是否支付保证金
		$isPay = Db::name('auction_moneys')
		           ->where(['userId'=>$userId,'auctionId'=>$auctionId,'cautionStatus'=>1])
		           ->count();
		if($isPay==0)return WSTReturn('出价失败,您尚未支付保证金');
		if($auction->currPrice >= $payPrice)return WSTReturn('您的出价小于当前拍卖价【￥'.$auction->currPrice.'】，请刷新后重试',-2);
		if((($payPrice-$auction->currPrice)%$auction->fareInc)>0)return WSTReturn('出价失败,非法的出价幅度');
		Db::startTrans();
		try{
			//修改当前价格
			$auction->currPrice = $payPrice;
			$auction->auctionNum = $auction->auctionNum + 1;
			$auction->save();
			//获取上一条要通知的用户
			$log = Db::name('auction_logs')->where('auctionId='.$auctionId.' and userId!='.$userId.' and isTop=1')->find();
			//标记之前的出价信息为隐藏状态
			Db::name('auction_logs')->where(['auctionId'=>$auctionId,'userId'=>$userId])->update(['dataFlag'=>0]);
			Db::name('auction_logs')->where(['auctionId'=>$auctionId])->update(['isTop'=>0]);
		    $data = [];
		    $data['userId'] = $userId;
		    $data['auctionId'] = $auctionId;
		    $data['payPrice'] = $payPrice;
		    $data['createTime'] = date('Y-m-d H:i:s');
		    $data['isTop'] = 1;
		    $data['dataFlag'] = 1;
		    $result = Db::name('auction_logs')->insert($data);
		    if(false !== $result){
		    	//发送系统消息-用户
		        if(!empty($log)){
		        	$tpl = WSTMsgTemplates('AUCTION_USER_RESULT');
			        if($tpl['tplContent']!=''){
			            $find = ['${GOODS}','${JOIN_TIME}','${ASTART_TIME}','${RESULT}'];
			            $replace = [$auction->goodsName,$log['createTime'],$auction->startTime,'拍卖出局'];
			            WSTSendMsg($log['userId'],str_replace($find,$replace,$tpl['tplContent']),['from'=>'auction','dataId'=>$auction->auctionId]);
			        }
			        if((int)WSTConf('CONF.wxenabled')==1){
			        	$params = [];
		                $params['GOODS'] = $auction->goodsName;
		                $params['JOIN_TIME'] = $log['createTime'];
	                    $params['ASTART_TIME'] = $auction->startTime;
	                    $params['RESULT'] = '拍卖出局';
			            WSTWxMessage(['CODE'=>'WX_AUCTION_USER_RESULT','userId'=>$log['userId'],'params'=>$params]);
			        }
		        }
		    	Db::commit();
		    	return WSTReturn('出价成功',1);
		    }
		}catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('出价失败',-1);
	}

	/**
	 *  获取出价记录
	 */
	public function pageQueryByAuctionLog($auctionId,$isReplace = true){
		$auction = Db::name('auctions')->where('auctionId',$auctionId)->field('currPrice')->find();
        $page =  Db::name('auction_logs')->alias('a')
                  ->join('__USERS__ u','a.userId=u.userId')
                  ->where('a.dataFlag >=0 and a.auctionId='.$auctionId)
                  ->field('u.loginName,a.payPrice,a.createTime,a.isTop')
                  ->order('payPrice desc,createTime desc')
                  ->paginate(input('pagesize/d'))->toArray();
        if(count($page['Rows'])>0){
        	foreach ($page['Rows'] as $k => $v) {
        		if($isReplace)$page['Rows'][$k]['loginName'] = WSTStrReplace($v['loginName'],'*',2);
        	}
        }
        return WSTReturn('',1,$page);
	}

	/**
	 * 获取用户竞拍记录
	 */
	public function pageQueryByUser(){
         $page = Db::name('auction_logs')->alias('al')
           ->join('__AUCTIONS__ a','al.auctionId=a.auctionId','inner')
           ->join('__GOODS__ g','a.goodsId=g.goodsId')
           ->where(['al.dataFlag'=>1,'al.userId'=>(int)session('WST_USER.userId')])
           ->field('a.bidLogId,a.auctionId,a.goodsId,a.goodsName,a.goodsImg,a.auctionPrice,a.currPrice,a.startTime,a.endTime,al.id,al.payPrice,al.isTop,a.isPay,a.isClose')
           ->order('al.createTime desc')
           ->paginate(input('pagesize/d'))->toArray();
        if(count($page['Rows'])>0){
        	$time = time();
        	foreach ($page['Rows'] as $key => $v) {
        		if($v['bidLogId']==$v['id'])$page['Rows'][$key]['bid'] = 1;
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
        return WSTReturn('',1,$page);
	}

	
	/**
	 * 支付保证金信息
	 */
	public function getPayInfo($auctionId,$payfor){
		$data = [];
		$auction = $this->get($auctionId);
		//判断时间是否合适
		$time = time();
		if($payfor !=2 && (strtotime($auction->startTime) > $time || strtotime($auction->endTime) < $time)){
			return WSTReturn('非法的操作');
		}
		$data["auction"] = $auction;
		if($payfor==2){
			$where = [];
			$bidLogId = (int)$auction["bidLogId"];
			$where['id'] = $bidLogId;
			$where['dataFlag'] = 1;
			$where['isTop'] = 1;
			$log = Db::name('auction_logs')->where($where)->field(["payPrice"])->find();
			$data["auction"]['cautionMoney'] = $log["payPrice"];
		}
		
		//获取支付信息
		$data['payments'] = $this->getPayments();
		return WSTReturn('',1,$data);
	}
	
	
	public function getPayments(){
		//获取支付信息
		$payments = Db::name('payments')->where(['isOnline'=>1,'enabled'=>1])->order('payOrder asc')->select();
		return $payments;
	}
	
	public function getUserAuction($auctionId){
		$where = [];
		$userId = (int)session('WST_USER.userId');
		$where['gu.auctionId'] = $auctionId;
		$where['gu.dataFlag'] = 1;
		$rs = $this->alias('gu')->join('__AUCTION_MONEYS__ a','a.auctionId=gu.auctionId and a.moneyType=1 and a.userId='.$userId,'left')->where($where)->field('a.userId,gu.*')->find();
		return $rs;
	}
	
	public function getAuctionPay($auctionId){
		$where = [];
		$userId = (int)session('WST_USER.userId');
		$where['gu.auctionId'] = $auctionId;
		$where['gu.dataFlag'] = 1;
		$rs = $this->alias('gu')->join('__AUCTION_LOGS__ a','a.auctionId=gu.auctionId and gu.bidLogId=a.id and a.userId='.$userId)->where($where)->field('a.payPrice,gu.*')->find();
		return $rs;
	}
	
	
	/**
	 * 完成保证金支付
	 */
	public function complateCautionMoney($obj){
		
		$trade_no = $obj["trade_no"];
		$userId = (int)$obj["userId"];
		$auctionId = (int)$obj["auctionId"];
		$payFrom = (int)$obj["payFrom"];
		$payMoney = (float)$obj["total_fee"];
		$moneyType = ($obj["payObj"]=="bao")?1:2;
		
		$auction = Db::name('auction_moneys')->where(["userId"=>$userId,"moneyType"=>$moneyType,"tradeNo"=>$trade_no,"payType"=>$payFrom])->find();
		if(!empty($auction)){
			return WSTReturn(($moneyType==1)?'保证金已支付':'拍卖货款已支付',-1);
		}
		$auction = Db::name('auctions')->where(["auctionId"=>$auctionId,"dataFlag"=>1])->field(["cautionMoney","bidLogId","goodsName","endPayTime"])->find();
		if($moneyType==1){
			$cautionMoney = $auction["cautionMoney"];
		}else{
			$where = [];
			$bidLogId = (int)$auction["bidLogId"];
			$where['id'] = $bidLogId;
			$where['dataFlag'] = 1;
			$where['isTop'] = 1;
			$log = Db::name('auction_logs')->where($where)->field(["payPrice"])->find();
			$cautionMoney = $log["payPrice"];
		}
		
		if($payMoney<$cautionMoney){
			return WSTReturn(($moneyType==1)?'保证金金额不正确':'拍卖货款不正确',-1);
		}
		
		Db::startTrans();
		try {
			//创建一条充值流水记录
			$lm = [];
			$lm['targetType'] = 0;
			$lm['targetId'] = $userId;
			$lm['dataId'] = $auctionId;
			$lm['dataSrc'] = 'auction';
			$lm['remark'] = ($moneyType==1)?('拍卖活动【'.$auction['goodsName'].'】保证金充值¥'.$payMoney):('拍卖活动【'.$auction['goodsName'].'】货款充值¥'.$payMoney);
			$lm['moneyType'] = 1;
			$lm['money'] = $payMoney;
			$lm['payType'] = $payFrom;
			$lm['tradeNo'] = $trade_no;
			model('common/LogMoneys')->add($lm);
			
			$mauction = Db::name('auction_moneys')->where(["userId"=>$userId,"moneyType"=>$moneyType,"auctionId"=>$auctionId])->find();
			if(empty($mauction)){
				
				if($moneyType==2){
					
					if($auction["endPayTime"]<date("Y-m-d H:i:s")){
						Db::commit();
						return WSTReturn('您已过拍卖支付货款期限',-1);
					}
					
					$data = array();
					$data["isPay"] = 1;
					$data["isClose"] = 1;
					Db::name('auctions')->where(["auctionId"=>$auctionId])->update($data);
					
					//退回保证金
					$cmoney = Db::name('auction_moneys')->where(["auctionId"=>$auctionId,"moneyType"=>1,"cautionStatus"=>1])->field(["id","cautionMoney"])->find();
					$cautionMoney = $cmoney["cautionMoney"];
					$cId = $cmoney["id"];
					Db::name('auction_moneys')->where(["id"=>$cId])->update(["cautionStatus"=>2]);
					//创建一条收出流水记录
					$lm = [];
					$lm['targetType'] = 0;
					$lm['targetId'] = $userId;
					$lm['dataId'] =  $auctionId;
					$lm['dataSrc'] = 'auction';
					$lm['remark'] = '退回支付拍卖活动【'.$auction['goodsName'].'】保证金¥'.$cautionMoney;
					$lm['moneyType'] = 1;
					$lm['money'] = $cautionMoney;
					$lm['payType'] = 'wallets';
					model('common/LogMoneys')->add($lm);
				}
				
				//创建一条支出流水记录
				$lm = [];
				$lm['targetType'] = 0;
				$lm['targetId'] = $userId;
				$lm['dataId'] = $auctionId;
				$lm['dataSrc'] = 'auction';
				$lm['remark'] = ($moneyType==1)?('支付拍卖活动【'.$auction['goodsName'].'】保证金¥'.$payMoney):('支付拍卖活动【'.$auction['goodsName'].'】货款¥'.$payMoney);
				$lm['moneyType'] = 0;
				$lm['money'] = $payMoney;
				$lm['payType'] = $payFrom;
				model('common/LogMoneys')->add($lm);
				
				//创建一条拍卖金记录
				$am = [];
				$am['auctionId'] = $auctionId;
				$am['userId'] = $userId;
				$am['cautionMoney'] =  $payMoney;
				$am['cautionStatus'] = 1;
				$am['payType'] = $payFrom;
				$am['tradeNo'] = $trade_no;
				$am['moneyType'] = $moneyType;
				$am['createTime'] = date('Y-m-d H:i:s');
				Db::name('auction_moneys')->insert($am);
			}
			Db::commit();
			return WSTReturn('支付成功',1);
		} catch (Exception $e) {
			Db::rollback();
			return WSTReturn('支付失败',-1);
		}
	}
	
	/**
	 * 用户钱包支付保证金
	 */
	public function payByWallet(){
		
		$payPwd = input('payPwd');
		$key = input('key');
		$key = base64_decode($key);
		$base64 = new \org\Base64();
		$key = $base64->decrypt($key,"WSTMart");
		$key = explode('@',$key);
		$moneyType = ($key[0]=="bao")?1:2;
		
		$userId = (int)session('WST_USER.userId');
		//判断是否开启余额支付
		$isEnbalePay = model('common/Payments')->isEnablePayment('wallets');
		if($isEnbalePay==0)return WSTReturn('非法的支付方式',-1);
		$payMoney = 0;
		$auctionId = (int)$key[1];
		$auction = Db::name('auctions')->where(["auctionId"=>$auctionId,"dataFlag"=>1])->field(["cautionMoney","bidLogId","goodsName","endPayTime"])->find();
		$now = date("Y-m-d H:i:s");
		if($auction["bidLogId"]>0 && $auction["endPayTime"]<$now){
			return WSTReturn('您已过拍卖支付货款期限',-1);
		}
		$data = array();
		if($moneyType==1){
			$payMoney = $auction["cautionMoney"];
		}else{
			$where = [];
			$bidLogId = (int)$auction["bidLogId"];
			$where['id'] = $bidLogId;
			$where['dataFlag'] = 1;
			$where['isTop'] = 1;
			$log = Db::name('auction_logs')->where($where)->field(["payPrice"])->find();
			$payMoney = $log["payPrice"];
		}
		$data = Db::name('auction_moneys')->where(["userId"=>$userId,"moneyType"=>$moneyType,"auctionId"=>$auctionId])->find();
		//获取用户钱包
		$user = model('common/users')->get($userId);
		if($user->payPwd=='')return WSTReturn('您未设置支付密码，请先设置密码',-1);
		if($user->payPwd!=md5($payPwd.$user->loginSecret))return WSTReturn('您的支付密码不正确',-1);
		if($payMoney > $user->userMoney)return WSTReturn('您的钱包余额不足',-1);
		
		Db::startTrans();
		try {
			if(empty($data)){

				//创建一条支出流水记录
				$lm = [];
				$lm['targetType'] = 0;
				$lm['targetId'] = $userId;
				$lm['dataId'] =  $auctionId;
				$lm['dataSrc'] = 'auction';
				$lm['remark'] = ($moneyType==1)?('支付拍卖活动【'.$auction['goodsName'].'】保证金¥'.$payMoney):('支付拍卖活动【'.$auction['goodsName'].'】货款¥'.$payMoney);
				$lm['moneyType'] = 0;
				$lm['money'] = $payMoney;
				$lm['payType'] = 'wallets';
				model('common/LogMoneys')->add($lm);
				
				if($moneyType==2){
					$data = array();
					$data["isPay"] = 1;
					$data["isClose"] = 1;
					Db::name('auctions')->where(["auctionId"=>$auctionId])->update($data);
					//退回保证金
					$cmoney = Db::name('auction_moneys')->where(["auctionId"=>$auctionId,"moneyType"=>1,"cautionStatus"=>1])->field(["id","cautionMoney"])->find();
					$cautionMoney = $cmoney["cautionMoney"];
					$cId = $cmoney["id"];
					Db::name('auction_moneys')->where(["id"=>$cId])->update(["cautionStatus"=>2]);
					//创建一条收出流水记录
					$lm = [];
					$lm['targetType'] = 0;
					$lm['targetId'] = $userId;
					$lm['dataId'] =  $auctionId;
					$lm['dataSrc'] = 'auction';
					$lm['remark'] = '退回支付拍卖活动【'.$auction['goodsName'].'】保证金¥'.$cautionMoney;
					$lm['moneyType'] = 1;
					$lm['money'] = $cautionMoney;
					$lm['payType'] = 'wallets';
					model('common/LogMoneys')->add($lm);
				}

				
				//创建一条保证金记录
				$am = [];
				$am['auctionId'] = $auctionId;
				$am['userId'] = $userId;
				$am['cautionMoney'] =  $payMoney;
				$am['cautionStatus'] = 1;
				$am['payType'] = "wallets";
				$am['tradeNo'] = '';
				$am['moneyType'] = $moneyType;
				$am['createTime'] = date('Y-m-d H:i:s');
				Db::name('auction_moneys')->insert($am);
				
				Db::commit();
				return WSTReturn('支付成功',1);
			}else{
				return WSTReturn('您已支付，请勿重复支付',-1);
			}
		} catch (Exception $e) {
			Db::rollback();
			return WSTReturn('支付失败',-1);
		}
	}

	/**
	 * 检测是否完成竞拍支付
	 */
	public function checkAuctionPayStatus($auctionId){
		$userId = (int)session('WST_USER.userId');
        return Db::name('auctions')->alias('a')
                     ->join('__AUCTION_LOGS__ al','a.auctionId=al.auctionId and a.bidLogId=al.id','inner')
                     ->where(['a.auctionId'=>$auctionId,'a.dataFlag'=>1,'al.dataFlag'=>1,'al.userId'=>$userId])
                     ->field('a.auctionId,a.isPay,al.payPrice,al.userId')
                     ->find();
	}

	/**
	 * 完成下单
	 */
	public function submit($orderSrc){
		$auctionId = (int)input('post.auctionId');
		$addressId = (int)input('post.s_addressId');
		$deliverType = ((int)input('post.deliverType')!=0)?1:0;
		$isInvoice = ((int)input('post.isInvoice')!=0)?1:0;
		$invoiceClient = ($isInvoice==1)?input('post.invoiceClient'):'';
		$userId = (int)session('WST_USER.userId');
		//检测是否提交了订单/是否具体提交订单的资格
		$auction = $this->get($auctionId);
		if($auction->orderId>0)return WSTReturn('对不起，该拍卖已下单完成');
        $log = Db::name('auction_logs')->where(['auctionId'=>$auctionId,'userId'=>$userId,'isTop'=>1,'dataFlag'=>1])->find();
		if(empty($log))return WSTReturn('无效的拍卖记录');
		//检测地址是否有效
		$address = Db::name('user_address')->where(['userId'=>$userId,'addressId'=>$addressId,'dataFlag'=>1])->find();
		if(empty($address)){
			return WSTReturn("无效的用户地址");
		}
	    $areaIds = [];
        $areaMaps = [];
        $tmp = explode('_',$address['areaIdPath']);
        $address['areaId2'] = $tmp[1];//记录配送城市
        foreach ($tmp as $vv){
         	if($vv=='')continue;
         	if(!in_array($vv,$areaIds))$areaIds[] = $vv;
        }
        if(!empty($areaIds)){
	         $areas = Db::name('areas')->where(['dataFlag'=>1,'areaId'=>['in',$areaIds]])->field('areaId,areaName')->select();
	         foreach ($areas as $v){
	         	 $areaMaps[$v['areaId']] = $v['areaName'];
	         }
	         $tmp = explode('_',$address['areaIdPath']);
	         $areaNames = [];
		     foreach ($tmp as $vv){
	         	 if($vv=='')continue;
	         	 $areaNames[] = $areaMaps[$vv];
	         	 $address['areaName'] = implode('',$areaNames);
	         }
        }
		$address['userAddress'] = $address['areaName'].$address['userAddress'];
		WSTUnset($address, 'isDefault,dataFlag,createTime,userId');
		//获取支付记录
		$auctionMoney = Db::name('auction_moneys')->where(['auctionId'=>$auction->auctionId,'userId'=>$userId,'moneyType'=>2])->find();
		if(empty($auctionMoney))return WSTReturn('请先支付拍卖成交价');
		//生成订单
		Db::startTrans();
		try{
			$orderunique = WSTOrderQnique();
			$orderNo = WSTOrderNo(); 
			//创建订单
			$order = [];
			$order = array_merge($order,$address);
			$order['payFrom'] = $auctionMoney['payType'];
			$order['tradeNo'] = $auctionMoney['tradeNo'];
			$order['orderNo'] = $orderNo;
			$order['userId'] = $userId;
			$order['shopId'] = $auction->shopId;
			$order['payType'] = 1;
			$order['goodsMoney'] = $auction->currPrice;
			//计算运费和总金额
			$order['deliverType'] = $deliverType;
			$order['deliverMoney'] = 0;
			$order['totalMoney'] = $auction->currPrice;
            $order['scoreMoney'] = 0;
			$order['useScore'] = 0;

			//实付金额要减去积分兑换的金额
			$order['realTotalMoney'] = $auction->currPrice;
			$order['needPay'] = 0;
			$order['orderCode'] = 'auction';
			$order['orderCodeTargetId'] = $auction->auctionId;
			$order['extraJson'] = json_encode(['auctionId'=>$auction->auctionId]);
            $order['orderStatus'] = 0;//待发货
			$order['isPay'] = 1;
			//积分
			$orderScore = 0;
			//如果开启下单获取积分则有积分
			if(WSTConf('CONF.isOrderScore')==1){
				$orderScore = WSTMoneyGiftScore($order['goodsMoney']);
			}
			$order['orderScore'] = $orderScore;
			$order['isInvoice'] = $isInvoice;
			$order['invoiceClient'] = $invoiceClient;
			$order['orderRemarks'] = input('post.remark');
			$order['orderunique'] = $orderunique;
			$order['orderSrc'] = $orderSrc;
			$order['dataFlag'] = 1;
			$order['payRand'] = 1;
			$order['createTime'] = date('Y-m-d H:i:s');
			$m = model('common/orders');
			$result = $m->data($order,true)->isUpdate(false)->allowField(true)->save($order);
			if(false !== $result){
				$orderId = $m->orderId;
				$auction->orderId = $orderId;
				$auction->isClose = 2;
				$auction->save();
				
				$goods = Db::name('goods')->where('goodsId',$auction->goodsId)->field('goodsCatId')->find();
				//创建订单商品记录
				$orderGgoods = [];
				$orderGoods['orderId'] = $orderId;
				$orderGoods['goodsId'] = $auction->goodsId;
				$orderGoods['goodsNum'] = 1;
				$orderGoods['goodsPrice'] = $auction->currPrice;
				$orderGoods['goodsSpecId'] = 0;
				$specNams = [];
				$specs = $this->getSpecs($auction->goodsId);
				if(!empty($specs)){
					foreach($specs as $spkey =>$svv){
						$specNams[] = $svv['name'].":".$svv['list'][0]['itemName'];
					}
					$orderGoods['goodsSpecNames'] = implode('@@_@@',$specNams);
				}
				
				$orderGoods['goodsName'] = $auction->goodsName;
				$orderGoods['goodsImg'] = $auction->goodsImg;
				$orderGoods['commissionRate'] = WSTGoodsCommissionRate($goods['goodsCatId']);
				Db::name('order_goods')->insert($orderGoods);
                    
				//建立订单记录
				$logArr = [];
				$logOrder = [];
				$logOrder['orderId'] = $orderId;
				$logOrder['orderStatus'] = 0;
				$logOrder['logContent'] = "拍卖订单提交成功";
				$logOrder['logUserId'] = $userId;
				$logOrder['logType'] = 0;
				$logOrder['logTime'] = date('Y-m-d H:i:s');
				$logArr[] = $logOrder;
				$logOrder = [];
				$logOrder['orderId'] = $orderId;
				$logOrder['orderStatus'] = -2;
				$logOrder['logContent'] = "拍卖订单支付成功 ";
				$logOrder['logUserId'] = $userId;
				$logOrder['logType'] = 0;
				$logOrder['logTime'] = date('Y-m-d H:i:s');
				$logArr[] = $logOrder;
				Db::name('log_orders')->insertAll($logArr);

				$shop = Db::name('shops')->where('shopId',$auction->shopId)->field('userId')->find();
				//给店铺增加提示消息
				$tpl = WSTMsgTemplates('ORDER_SUBMIT');
		        if($tpl['tplContent']!=''){
		            $find = ['${ORDER_NO}'];
		            $replace = [$orderNo];
		            WSTSendMsg($shop['userId'],str_replace($find,$replace,$tpl['tplContent']),['from'=>1,'dataId'=>$orderId]);
		        }
		        //微信消息
		        if((int)WSTConf('CONF.wxenabled')==1){
		            $params = [];
		            $params['ORDER_NO'] = $orderNo;
	                $params['ORDER_TIME'] = date('Y-m-d H:i:s');             
		            $params['GOODS'] = $auction->goodsName."*1";
		            $params['MONEY'] = $order['realTotalMoney'];
		            $params['ADDRESS'] = $order['userAddress']." ".$order['userName'];
		            $params['PAY_TYPE'] = WSTLangPayType(1);
			        WSTWxMessage(['CODE'=>'WX_ORDER_SUBMIT','userId'=>$shop['userId'],'URL'=>Url('wechat/orders/sellerorder','',true,true),'params'=>$params]);
			    }
			}
		    Db::commit();
			return WSTReturn("提交拍卖订单成功", 1,$orderunique);
		}catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('提交订单失败',-1);
        }
	}

	/**
	 * 定时任务
	 */
	public function scanTask(){
		$cron = Db::name('crons')->where('cronCode','autoAuctionEnd')->find();
		$runNum = 0;
		if($cron && $cron['isEnable']==1){
			 $cron['cronJson'] = unserialize($cron['cronJson']);
			 $runNum = (int)$cron['cronJson'][0]['fieldVal'];
		}
		//获取到期的拍卖
		$date = date('Y-m-d H:i:s');
		$dbo = $this->where('endTime<"'.$date.'" and auctionStatus=1 and dataFlag=1 and isClose=0');
		if($runNum>0)$dbo->limit($runNum);
		$rs = $dbo->select();
		if(!empty($rs)){
			 $acutionLog = [];
		     $auctionConf = $this->getConf('Auction');
		     Db::startTrans();
		     try{
		        //先改变拍卖状态
	            foreach ($rs as $key => $v) {
	             	$al = Db::name('auction_logs')->where(['auctionId'=>$v->auctionId,'isTop'=>1])->find();
	             	if(!empty($al)){
	             		$v->bidLogId = $al['id'];
	             		$acutionLog[$v->auctionId] = $al;
	             		$v->isClose = 1;
	             	}else{
                        $v->isClose = 2;
	             	}
	             	$v->endPayTime = date('Y-m-d H:i:s',strtotime("+".(int)$auctionConf['endPayDate']." day"));
	             	$v->save();
                    $bidUserId = empty($al)?0:$al['userId'];
	             	//退回除中标人以外的保证金
		            $logUsers = Db::name('auction_moneys')->where('cautionStatus=1 and auctionId='.$v->auctionId.' and moneyType=1 and userId !='.$bidUserId)->column('userId');
		            foreach ($logUsers as $lkey => $lv) {
		             	$lm = [];
						$lm['targetType'] = 0;
						$lm['targetId'] = $lv;
						$lm['dataId'] = $v->auctionId;
						$lm['dataSrc'] = 'auction';
						$lm['remark'] ='退回拍卖活动【'.$v['goodsName'].'】保证金¥'.$v->cautionMoney;
						$lm['moneyType'] = 1;
						$lm['money'] = $v->cautionMoney;
						$lm['payType'] = '0';
						$lm['tradeNo'] = '';
						$lm['createTime'] = date('Y-m-d H:i:s');
						model('common/LogMoneys')->add($lm);
		            }
		            if(count($logUsers)>0){
			            Db::name('auction_moneys')
			              ->where('cautionStatus=1 and auctionId='.$v->auctionId.' and moneyType=1 and userId in('.implode(',',$logUsers).')')
			              ->update(['cautionStatus'=>2]);
                    }

	             }
	             //发送拍卖消息
	             foreach ($rs as $key => $v) {
	             	$log = isset($acutionLog[$v->auctionId])?$acutionLog[$v->auctionId]:[];
	             	//获取商家资料
	             	$shop = Db::name('shops')->where('shopId',$v->shopId)->field('userId')->find();
	             	//发送系统消息-商家
	             	$tpl = WSTMsgTemplates('AUCTION_SHOP_RESULT');
			        if($tpl['tplContent']!=''){
			            $find = ['${GOODS}','${ASTART_TIME}','${RESULT}'];
			            $replace = [$v->goodsName,$v->startTime,!empty($log)?'拍卖成功':'流拍'];
			            WSTSendMsg($shop['userId'],str_replace($find,$replace,$tpl['tplContent']),['from'=>'auction','dataId'=>$v->auctionId]);
			        }
			        //发送系统消息-用户
			        if(!empty($log) && isset($log['userId'])){
			        	$tpl = WSTMsgTemplates('AUCTION_USER_RESULT');
				        if($tpl['tplContent']!=''){
				            $find = ['${GOODS}','${JOIN_TIME}','${ASTART_TIME}','${RESULT}'];
				            $replace = [$v->goodsName,$log['createTime'],$v->startTime,'拍卖成功'];
				            WSTSendMsg($log['userId'],str_replace($find,$replace,$tpl['tplContent']),['from'=>'auction','dataId'=>$v->auctionId]);
				        }
			        }
			        //发送微信消息-商家
			        if((int)WSTConf('CONF.wxenabled')==1){
			            $params = [];
			            $params['GOODS'] = $v->goodsName;
		                $params['ASTART_TIME'] = $v->startTime;
		                $params['RESULT'] = !empty($log)?'拍卖成功':'流拍';
				        WSTWxMessage(['CODE'=>'WX_AUCTION_SHOP_RESULT','userId'=>$shop['userId'],'params'=>$params]);
				        if(!empty($log) && isset($log['userId'])){
				        	$params = [];
			                $params['GOODS'] = $v->goodsName;
			                $params['JOIN_TIME'] = $log['createTime'];
		                    $params['ASTART_TIME'] = $v->startTime;
		                    $params['RESULT'] = '拍卖成功';
				            WSTWxMessage(['CODE'=>'WX_AUCTION_USER_RESULT','userId'=>$log['userId'],'params'=>$params]);
				        }
				    }
				     
			    } 
			    Db::commit();
			}catch (\Exception $e) {
			    Db::rollback();
			}        
		}
		//计算逾期未支付拍卖，没收保证金
		$dbo = Db::name('auctions')->where('endPayTime<"'.$date.'" and bidLogId!=0 and isPay=0 and isClose=1')
		         ->field('bidLogId,auctionId,shopId,goodsName,cautionMoney,startTime');
		if($runNum>0)$dbo->limit($runNum);
		$rs = $dbo->select();
		if(!empty($rs)){
			Db::startTrans();
		    try{
			    foreach($rs as $key =>$v){
			    	Db::name('auctions')->where('auctionId',$v['auctionId'])->update(['isClose'=>2]);
	                $auctionlog = Db::name('auction_logs')->where('id',$v['bidLogId'])->field('userId,createTime')->find();
	                $auctionMoney = Db::name('auction_moneys')->where(['userId'=>$auctionlog['userId'],'auctionId'=>$v['auctionId']])->field('id')->find();
				    $shop = Db::name('shops')->where('shopId',$v['shopId'])->field('userId')->find();
				    //将保证金划拨给商家
				    $lm = [];
					$lm['targetType'] = 1;
					$lm['targetId'] = $v['shopId'];
					$lm['dataId'] = $v['auctionId'];
					$lm['dataSrc'] = 'auction';
					$lm['remark'] ='拍卖活动【'.$v['goodsName'].'】用户逾期支付拍卖款，商家获得保证金¥'.$v['cautionMoney'];
					$lm['moneyType'] = 1;
					$lm['money'] = $v['cautionMoney'];
					$lm['payType'] = '0';
					$lm['tradeNo'] = '';
					$lm['createTime'] = date('Y-m-d H:i:s');
					model('common/LogMoneys')->add($lm);
					//改变保证金状态
					Db::name('auction_moneys')->where('id',$auctionMoney['id'])->update(['cautionStatus'=>-1]);
				    //发送系统消息-商家
		            $tpl = WSTMsgTemplates('AUCTION_SHOP_RESULT');
				    if($tpl['tplContent']!=''){
				        $find = ['${GOODS}','${ASTART_TIME}','${RESULT}'];
				        $replace = [$v['goodsName'],$v['startTime'],'用户逾期未缴纳拍卖款，没收保证金'];
				        WSTSendMsg($shop['userId'],str_replace($find,$replace,$tpl['tplContent']),['from'=>'auction','dataId'=>$v['auctionId']]);
				    }
				    //发送系统消息-用户
				    $tpl = WSTMsgTemplates('AUCTION_USER_RESULT');
					if($tpl['tplContent']!=''){
					    $find = ['${GOODS}','${JOIN_TIME}','${ASTART_TIME}','${RESULT}'];
					    $replace = [$v['goodsName'],$auctionlog['createTime'],$v['startTime'],'逾期未缴纳拍卖款，没收保证金'];
					    WSTSendMsg($auctionlog['userId'],str_replace($find,$replace,$tpl['tplContent']),['from'=>'auction','dataId'=>$v['auctionId']]);
				    }
				    //发送微信消息-商家
				    if((int)WSTConf('CONF.wxenabled')==1){
				        $params = [];
				        $params['GOODS'] = $v['goodsName'];
			            $params['ASTART_TIME'] = $v['startTime'];
			            $params['RESULT'] = '用户逾期未缴纳拍卖款，没收保证金';
					    WSTWxMessage(['CODE'=>'WX_AUCTION_SHOP_RESULT','userId'=>$shop['userId'],'params'=>$params]);
					    $params = [];
				        $params['GOODS'] = $v['goodsName'];
				        $params['JOIN_TIME'] = $auctionlog['createTime'];
			            $params['ASTART_TIME'] = $v['startTime'];
			            $params['RESULT'] = '拍卖成功';
					    WSTWxMessage(['CODE'=>'WX_AUCTION_USER_RESULT','userId'=>$auctionlog['userId'],'params'=>$params]);
					}
			    }
			    Db::commit();
			}catch (\Exception $e) {
			    Db::rollback();
			} 
		}
		return WSTReturn('执行成功',1);
	}

	/**
	 * 获取我的保证金
	 */
	public function pageQueryByMoney(){
		$userId = (int)session('WST_USER.userId');
		$page = $this->alias('a')->join('__AUCTION_MONEYS__ m','a.auctionId=m.auctionId','inner')
		             ->where(['m.userId'=>$userId,'m.moneyType'=>1])
		             ->order('m.createTime desc')
		             ->field('a.auctionId,a.goodsName,a.goodsImg,a.currPrice,a.startTime,a.endTime,m.cautionStatus,m.cautionMoney')
                     ->paginate(input('pagesize/d'))->toArray();
        if(count($page['Rows'])>0){
        	$time = time();
        	foreach ($page['Rows'] as $key => $v) {
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
        return WSTReturn('',1,$page);
	}

	/**
	 * 获取热门拍卖
	 */
	public function getHotActions($num){
		$rs = Db::name('auctions')->where(['dataFlag'=>1,'isClose'=>0,'auctionStatus'=>1])
		           ->limit($num)->order('auctionNum desc,visitNum desc')
		           ->field('auctionId,goodsName,goodsImg,currPrice')->cache(600)->select();
		return $rs;
	}
}
