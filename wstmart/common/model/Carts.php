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
 * 购物车业务处理类
 */

class Carts extends Base{
	
	/**
	 * 加入购物车
	 */
	public function addCart(){
		$userId = (int)session('WST_USER.userId');
		$goodsId = (int)input('post.goodsId');
		$goodsSpecId = (int)input('post.goodsSpecId');

        //添加代码start
        //获得goodsSpecId
        $specIds = '';
        $color =  input('post.color') ?  input('post.color') : '';
        $size  =  input('post.size') ?  input('post.size') : '';
        $flag = 1;
        if(!empty($color)){
            $values = $color;
        }else{
            $values = $size;
        }


        if(empty($color) || empty($size)){
            $res = Db::query("SELECT * FROM   `jingo_spec_items` where goodsId=? and itemName=? limit 1",[$goodsId,$values]);
        }else{
            $flag = 2;
            $res = Db::query("SELECT * FROM   `jingo_spec_items` where goodsId=? and itemName=? limit 1",[$goodsId,$color]);
            $sizeID = Db::query("SELECT * FROM   `jingo_spec_items` where goodsId=? and itemName=? limit 1",[$goodsId,$size]);
        }
        foreach ($res as $key => $value) {
            if($flag == 2){
                for ($i=0; $i<1 ; $i++) {
                    $specIds = $value['itemId'].':'.$sizeID[$i]['itemId'];
                }
            }else{
                $specIds = $value['itemId'];
            }
        }

        //根据specIds 查出goodsSpecId
        $goods_specs = Db::query("SELECT * FROM `jingo_goods_specs` where goodsId = ? AND specIds =? ",[$goodsId,$specIds]);
        foreach ($goods_specs as $value) {
            $goodsSpecId = $value['id'];
        }
        //添加代码end

		$cartNum = (int)input('post.buyNum',1);
		$cartNum = ($cartNum>0)?$cartNum:1;
		//验证传过来的商品是否合法
		$chk = $this->checkGoodsSaleSpec($goodsId,$goodsSpecId);
		if($chk['status']==-1)return $chk;
		//检测库存是否足够
		if($chk['data']['stock']<$cartNum)return WSTReturn("加入购物车失败，商品库存不足", -1);
		//添加实物商品
		if($chk['data']['goodsType']==0){
			$goodsSpecId = $chk['data']['goodsSpecId'];
			$goods = $this->where(['userId'=>$userId,'goodsId'=>$goodsId,'goodsSpecId'=>$goodsSpecId])->select();
			if(empty($goods)){
				$data = array();
				$data['userId'] = $userId;
				$data['goodsId'] = $goodsId;
				$data['goodsSpecId'] = $goodsSpecId;
				$data['isCheck'] = 1;
				$data['cartNum'] = $cartNum;
				$rs = $this->save($data);
				if(false !==$rs){
					return WSTReturn("添加成功", 1);
				}
			}else{
				$rs = $this->where(['userId'=>$userId,'goodsId'=>$goodsId,'goodsSpecId'=>$goodsSpecId])->setInc('cartNum',$cartNum);
			    if(false !==$rs){
					return WSTReturn("添加成功", 1);
				}
			}
		}else{
			//非实物商品
            $carts = [];
            $carts['goodsId'] = $goodsId;
            $carts['cartNum'] = $cartNum;
            session('TMP_CARTS',$carts);
            return WSTReturn("添加成功", 1,['forward'=>'quickSettlement']);
		}
		return WSTReturn("加入购物车失败", -1);
	}
	/**
	 * 验证商品是否合法
	 */
	public function checkGoodsSaleSpec($goodsId,$goodsSpecId){
		$goods = model('Goods')->where(['goodsStatus'=>1,'dataFlag'=>1,'isSale'=>1,'goodsId'=>$goodsId])->field('goodsId,isSpec,goodsStock,goodsType')->find();
		if(empty($goods))return WSTReturn("添加失败，无效的商品信息", -1);
		$goodsStock = (int)$goods['goodsStock'];
		//有规格的话查询规格是否正确
		if($goods['isSpec']==1 || $goods['isSpec']==2){
			$specs = Db::name('goods_specs')->where(['goodsId'=>$goodsId,'dataFlag'=>1])->field('id,isDefault,specStock')->select();
			if(count($specs)==0){
				return WSTReturn("添加失败，无效的商品信息", -1);
			}
			$defaultGoodsSpecId = 0;
			$defaultGoodsSpecStock = 0;
			$isFindSpecId = false;
			foreach ($specs as $key => $v){
				if($v['isDefault']==1){
					$defaultGoodsSpecId = $v['id'];
					$defaultGoodsSpecStock = (int)$v['specStock'];
				}
				if($v['id']==$goodsSpecId){
                    $defaultGoodsSpecId = $goodsSpecId;//添加代码
					$goodsStock = (int)$v['specStock'];
					$isFindSpecId = true;
				}
			}
			
			if($defaultGoodsSpecId==0)return WSTReturn("添加失败，无效的商品信息", -1);//有规格却找不到规格的话就报错
			if(!$isFindSpecId)return WSTReturn("", 1,['goodsSpecId'=>$defaultGoodsSpecId,'stock'=>$defaultGoodsSpecStock,'goodsType'=>$goods['goodsType']]);//如果没有找到的话就取默认的规格
			return WSTReturn("", 1,['goodsSpecId'=>$goodsSpecId,'stock'=>$goodsStock,'goodsType'=>$goods['goodsType']]);
		}else{
			return WSTReturn("", 1,['goodsSpecId'=>0,'stock'=>$goodsStock,'goodsType'=>$goods['goodsType']]);
		}
	}
	/**
	 * 删除购物车里的商品
	 */
	public function delCart(){
		$userId = (int)session('WST_USER.userId');
		$id = input('post.id');
		$id = explode(',',WSTFormatIn(",",$id));
		$id = array_filter($id);
		$this->where("userId = ".$userId." and cartId in(".implode(',', $id).")")->delete();
		return WSTReturn("删除成功", 1);
	}
	/**
	 * 取消购物车商品选中状态
	 */
	public function disChkGoods($goodsId,$goodsSpecId,$userId){
		$this->save(['isCheck'=>0],['userId'=>$userId,'goodsId'=>$goodsId,'goodsSpecId'=>$goodsSpecId]);
	}

	/**
	 * 获取session中购物车列表
	 */
	public function getQuickCarts($uId=0){
		$userId = ($uId==0)?(int)session('WST_USER.userId'):$uId;
		$tmp_carts = session('TMP_CARTS');
		$where = [];
		$where['goodsId'] = $tmp_carts['goodsId'];
		$rs = Db::name('goods')->alias('g')
		           ->join('__SHOPS__ s','s.shopId=g.shopId','left')
		           ->where($where)
		           ->field('s.userId,s.shopId,s.shopName,g.goodsId,s.shopQQ,shopWangWang,g.goodsName,g.shopPrice,g.goodsStock,g.goodsImg,g.goodsCatId,g.isFreeShipping')
		           ->find();
		if(empty($rs))return ['carts'=>[],'goodsTotalMoney'=>0,'goodsTotalNum'=>0]; 
		$rs['cartNum'] = $tmp_carts['cartNum'];
		$carts = [];
		$cartShop = [];
		$goodsTotalNum = 0;
		$goodsTotalMoney = 0;
		$cartShop['isFreeShipping'] = true;
		$cartShop['shopId'] = $rs['shopId'];
		$cartShop['shopName'] = $rs['shopName'];
		$cartShop['shopQQ'] = $rs['shopQQ'];
		$cartShop['userId'] = $rs['userId'];
		$cartShop['shopWangWang'] = $rs['shopWangWang'];
		//判断能否购买，预设allowBuy值为10，为将来的各种情况预留10个情况值，从0到9
		$rs['allowBuy'] = 10;
		if($rs['goodsStock']<0){
			$rs['allowBuy'] = 0;//库存不足
		}else if($rs['goodsStock']<$tmp_carts['cartNum']){
			//$rs['allowBuy'] = 1;//库存比购买数小
            $rs['cartNum'] = $rs['goodsStock'];
		}
		$cartShop['goodsMoney'] = $rs['shopPrice'] * $rs['cartNum'];
		$goodsTotalMoney = $goodsTotalMoney + $rs['shopPrice'] * $rs['cartNum'];
		$rs['specNames'] = [];
		unset($rs['shopName']);
		$cartShop['list'][] = $rs;
		$carts[$cartShop['shopId']] = $cartShop;
		return ['carts'=>$carts,'goodsTotalMoney'=>$goodsTotalMoney,'goodsTotalNum'=>$goodsTotalNum]; 
	}
	
	/**
	 * 获取购物车列表
	 */
	public function getCarts($isSettlement = false, $uId=0){
		$userId = ($uId==0)?(int)session('WST_USER.userId'):$uId;
		$where = [];
		$where['c.userId'] = $userId;
		if($isSettlement)$where['c.isCheck'] = 1;
		$rs = $this->alias('c')->join('__GOODS__ g','c.goodsId=g.goodsId','inner')
		           ->join('__SHOPS__ s','s.shopId=g.shopId','left')
		           ->join('__GOODS_SPECS__ gs','c.goodsSpecId=gs.id','left')
		           ->where($where)
		           ->field('c.goodsSpecId,c.cartId,s.userId,s.shopId,s.shopName,g.goodsId,s.shopQQ,shopWangWang,g.goodsName,g.shopPrice,g.goodsStock,g.isSpec,gs.specPrice,gs.specStock,g.goodsImg,c.isCheck,gs.specIds,c.cartNum,g.goodsCatId,g.isFreeShipping')
		           ->select();
		$carts = [];
		$goodsIds = [];
		$goodsTotalNum = 0;
		$goodsTotalMoney = 0;
		foreach ($rs as $key =>$v){
			if(!isset($carts[$v['shopId']]['goodsMoney']))$carts[$v['shopId']]['goodsMoney'] = 0;
			if(!isset($carts[$v['shopId']]['isFreeShipping']))$carts[$v['shopId']]['isFreeShipping'] = true;
			$carts[$v['shopId']]['shopId'] = $v['shopId'];
			$carts[$v['shopId']]['shopName'] = $v['shopName'];
			$carts[$v['shopId']]['shopQQ'] = $v['shopQQ'];
			$carts[$v['shopId']]['userId'] = $v['userId'];
			//如果店铺一旦不包邮了，那么就不用去判断商品是否包邮了
			if($v['isFreeShipping']==0 && $carts[$v['shopId']]['isFreeShipping'])$carts[$v['shopId']]['isFreeShipping'] = false;
			$carts[$v['shopId']]['shopWangWang'] = $v['shopWangWang'];
			if($v['isSpec']==1){
				$v['shopPrice'] = $v['specPrice'];
				$v['goodsStock'] = $v['specStock'];
			}
			//判断能否购买，预设allowBuy值为10，为将来的各种情况预留10个情况值，从0到9
			$v['allowBuy'] = 10;
			if($v['goodsStock']<0){
				$v['allowBuy'] = 0;//库存不足
			}else if($v['goodsStock']<$v['cartNum']){
				//$v['allowBuy'] = 1;//库存比购买数小
				$v['cartNum'] = $v['goodsStock'];
			}
			//如果是结算的话，则要过滤了不符合条件的商品
			if($isSettlement && $v['allowBuy']!=10){
				$this->disChkGoods($v['goodsId'],(int)$v['goodsSpecId'],(int)session('WST_USER.userId'));
				continue;
			}
			if($v['isCheck']==1){
				$carts[$v['shopId']]['goodsMoney'] = $carts[$v['shopId']]['goodsMoney'] + $v['shopPrice'] * $v['cartNum'];
				$goodsTotalMoney = $goodsTotalMoney + $v['shopPrice'] * $v['cartNum'];
				$goodsTotalNum++;
			}
			$v['specNames'] = [];
			unset($v['shopName'],$v['isSpec']);
			$carts[$v['shopId']]['list'][] = $v;
			if(!in_array($v['goodsId'],$goodsIds))$goodsIds[] = $v['goodsId'];
		}
		//加载规格值
		if(count($goodsIds)>0){
		    $specs = DB::name('spec_items')->alias('s')->join('__SPEC_CATS__ sc','s.catId=sc.catId','left')
		        ->where(['s.goodsId'=>['in',$goodsIds],'s.dataFlag'=>1])->field('catName,itemId,itemName')->select();
		    if(count($specs)>0){ 
		    	$specMap = [];
		    	foreach ($specs as $key =>$v){
		    		$specMap[$v['itemId']] = $v;
		    	}
			    foreach ($carts as $key =>$shop){
			    	foreach ($shop['list'] as $skey =>$v){
			    		$strName = [];
			    		if($v['specIds']!=''){
			    			$str = explode(':',$v['specIds']);
			    			foreach ($str as $vv){
			    				if(isset($specMap[$vv]))$strName[] = $specMap[$vv];
			    			}
			    		}
                        
			    		$carts[$key]['list'][$skey]['specNames'] = $strName;
			    	}
			    }
		    }
		}
		return ['carts'=>$carts,'goodsTotalMoney'=>$goodsTotalMoney,'goodsTotalNum'=>$goodsTotalNum];     
	}
	
	/**
	 * 获取购物车商品列表
	 */
	public function getCartInfo($isSettlement = false){
		$userId = (int)session('WST_USER.userId');
		$where = [];
		$where['c.userId'] = $userId;
		if($isSettlement)$where['c.isCheck'] = 1;
		$rs = $this->alias('c')->join('__GOODS__ g','c.goodsId=g.goodsId','inner')
		           ->join('__GOODS_SPECS__ gs','c.goodsSpecId=gs.id','left')
		           ->where($where)
		           ->field('c.goodsSpecId,c.cartId,g.goodsId,g.goodsName,g.shopPrice,g.goodsStock,g.isSpec,gs.specPrice,gs.specStock,g.goodsImg,c.isCheck,gs.specIds,c.cartNum')
		           ->select();
		$goodsIds = []; 
		$goodsTotalMoney = 0;
		$goodsTotalNum = 0;
		foreach ($rs as $key =>$v){
			if(!in_array($v['goodsId'],$goodsIds))$goodsIds[] = $v['goodsId'];
			if($v['isSpec']==1){
				$v['shopPrice'] = $v['specPrice'];
				$v['goodsStock'] = $v['specStock'];
			}
			if($v['goodsStock']<$v['cartNum']){
				$v['cartNum'] = $v['goodsStock'];
			}
			$goodsTotalMoney = $goodsTotalMoney + $v['shopPrice'] * $v['cartNum'];
			$rs[$key]['goodsImg'] = WSTImg($v['goodsImg']);
		}
	    //加载规格值
		if(count($goodsIds)>0){
		    $specs = DB::name('spec_items')->alias('s')->join('__SPEC_CATS__ sc','s.catId=sc.catId','left')
		        ->where(['s.goodsId'=>['in',$goodsIds],'s.dataFlag'=>1])->field('itemId,itemName')->select();
		    if(count($specs)>0){ 
		    	$specMap = [];
		    	foreach ($specs as $key =>$v){
		    		$specMap[$v['itemId']] = $v;
		    	}
			    foreach ($rs as $key =>$v){
			    	$strName = [];
			    	if($v['specIds']!=''){
			    		$str = explode(':',$v['specIds']);
			    		foreach ($str as $vv){
			    			if(isset($specMap[$vv]))$strName[] = $specMap[$vv]['itemName'];
			    		}
			    	}
			    	$rs[$key]['specNames'] = $strName;
			    }
		    }
		}
		$goodsTotalNum = count($rs);
		return ['list'=>$rs,'goodsTotalMoney'=>sprintf("%.2f", $goodsTotalMoney),'goodsTotalNum'=>$goodsTotalNum];
	}
	
	/**
	 * 修改购物车商品状态
	 */
	public function changeCartGoods(){
		$isCheck = Input('post.isCheck/d',-1);
		$buyNum = Input('post.buyNum/d',1);
		if($buyNum<1)$buyNum = 1;
		$id = Input('post.id/d');
		$userId = (int)session('WST_USER.userId');
		$data = [];
		if($isCheck!=-1)$data['isCheck'] = $isCheck;
		$data['cartNum'] = $buyNum;
		$this->where(['userId'=>$userId,'cartId'=>$id])->update($data);
		return WSTReturn("操作成功", 1);
	}

	/**
	 * 计算订单金额
	 */
	public function getCartMoney(){
		$data = ['shops'=>[],'totalMoney'=>0,'totalGoodsMoney'=>0];
        $areaId = input('post.areaId2/d',-1);
		//计算各店铺运费及金额
		$deliverType = (int)input('deliverType');
		$carts = $this->getCarts(true);
		$shopFreight = 0;
		foreach ($carts['carts'] as $key =>$v){
			if($v['isFreeShipping']){
                $data['shops'][$v['shopId']]['freight'] = 0;
			}else{
				$shopFreight = ($deliverType==1)?0:WSTOrderFreight($v['shopId'],$areaId);
                $data['shops'][$v['shopId']]['freight'] = $shopFreight;
			}
			$data['shops'][$v['shopId']]['goodsMoney'] = $v['goodsMoney']+$shopFreight;
			$data['totalGoodsMoney'] += $v['goodsMoney'];
			$data['totalMoney'] += $v['goodsMoney'] + $shopFreight;
		}
        $data['useScore'] = 0;
		$data['scoreMoney'] = 0;
		//计算积分
		$isUseScore = (int)input('isUseScore');
		if($isUseScore==1){
            $userId = (int)session('WST_USER.userId');
			$useScore = (int)input('useScore');
			$user = model('users')->getFieldsById($userId,'userScore');
			//不能比用户积分还多
			if($useScore>$user['userScore'])$useScore = $user['userScore'];
			//不能比本次金额所需的积分还多
			$moneyToScore = WSTScoreToMoney($data['totalGoodsMoney'],true);
			if($useScore>$moneyToScore)$useScore = $moneyToScore;
			$money = WSTScoreToMoney($useScore);
			$data['useScore'] = $useScore;
			$data['scoreMoney'] = $money;
		}
		$data['realTotalMoney'] = $data['totalMoney'] - $data['scoreMoney'];
		return WSTReturn('',1,$data);
	}

	public function getQuickCartMoney(){
		$data = ['shops'=>[],'totalMoney'=>0,'totalGoodsMoney'=>0];
        $areaId = input('post.areaId2/d',-1);
		//计算各店铺运费及金额
		$carts = $this->getQuickCarts(true);
		$carts = current($carts['carts']);
		$data['shops']['freight'] = 0;
		$data['shops']['goodsMoney'] = $carts['goodsMoney'];
		$data['totalGoodsMoney'] = $carts['goodsMoney'];
		$data['totalMoney'] += $carts['goodsMoney'];
		$data['useScore'] = 0;
		$data['scoreMoney'] = 0;
		//计算积分
		$isUseScore = (int)input('isUseScore');
		if($isUseScore==1){
            $userId = (int)session('WST_USER.userId');
			$useScore = (int)input('useScore');
			$user = model('users')->getFieldsById($userId,'userScore');
			if($useScore>$user['userScore'])$useScore = $user['userScore'];
			$moneyToScore = WSTScoreToMoney($data['totalGoodsMoney'],true);
			if($useScore>$moneyToScore)$useScore = $moneyToScore;
			$money = WSTScoreToMoney($useScore);
			$data['useScore'] = $useScore;
			$data['scoreMoney'] = $money;
		}
		$data['realTotalMoney'] = $data['totalMoney'] - $data['scoreMoney'];
		return WSTReturn('',1,$data);
	}

}
