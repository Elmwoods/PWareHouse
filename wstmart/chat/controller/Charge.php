<?php
/**
 * Created by PhpStorm.
 * User: Lynn
 * Date: 2017/4/26
 * Time: 9:21
 */

namespace wstmart\chat\controller;



use think\Controller;
use think\Db;

class Charge extends Controller {
    public  $request;
    private $RongCloud;
    private $loader;
    private $client;
    private $accessToken;

    public function __construct() {
        require_once("OpenSdk.php");
        $this -> request = request();
        $this -> RongCloud = new Rongyun();

        $this->loader = new \QmLoader();
        $this->loader  -> autoload_path  = array(CURRENT_FILE_DIR.DS."client");
        $this->loader  -> init();
        $this->loader  -> autoload();

        $this->client  = new \OpenClient();
        $this->accessToken = '81a8174ea8f74168973e93275160c055';

    }

    //生活缴费列表
    public function show($userId) {
        $param = $this->request->param();
        if (!empty($param)) {
            $data = Db::table('jingo_user_charge') -> field(['type', 'account', 'customerName']) -> where('userId', $param['userId']) -> select();
            if (!$data) {
                $data = [];
            }
            return json(['result'=>'success', 'value'=>$data]);
        }else {
            return json(['result' => 'error', 'msg' => '请传参']);
        }
    }

    //缴费商品列表
    public function itemList($province, $city, $page, $item) {
        $param = $this->request->param();
        if (!empty($param)) {
            //获取itemID
            $req = new \BmDirectRechargeWaterCoalItemListRequest;
            $req->setItemName($param['province']);
            $req->setCity($param['city']);
            $req->setItemName($param['item']);
            $req->setPageNo($param['page']);
            $res = $this->client->execute($req, $this->accessToken);
            $res =  json_decode( json_encode( $res),true);
            if ($res['items']['item']) {
                foreach ($res['items']['item'] as $k => $v) {
                    unset($res['items']['item'][$k]['inPrice']);
                }
            }else {
                $res['items']['item'] = [];
            }
            return json(['result'=>'success', 'value'=>$res['items']['item']]);
        }else {
            return json(['result' => 'error', 'msg' => '请传参']);
        }
    }


    //查询商品属性
    public function itemPropsList($itemId, $account, $type, $userId) {
        $param = $this->request->param();
        if (!empty($param)) {
            $req = new \BmDirectRechargeWaterCoalItemPropsListRequest;
            $req->setItemId($param['itemId']);
            $res = $this->client->execute($req, $this->accessToken);
            $res = json_decode(json_encode($res), true);
            if ($res) {
                $data['itemId'] = $res['itemId'];
                $data['account'] = $param['account'];
                $data['projectId'] = $res['cid'];
                foreach ($res['itemProps']['itemProp'] as $v) {
                    switch ($v['type']) {
                        case 'BRAND' :
                            $data['unitId'] = $v['vid'];
                            $data['unitName'] = $v['vname'];
                            break;
                        case 'PRVCIN' :
                            $data['province'] = $v['vname'];
                            break;
                        case 'CITYIN' :
                            $data['city'] = $v['vname'];
                            $data['cityId'] = $v['vid'];
                            break;
                        case 'SPECIAL' :
                            $data['modeId'] = $v['vid'];
                            break;
                    }
                }
                $data['userId'] = $param['userId'];
                $data['type'] = $param['type'];
                if (Db::table('jingo_user_charge') -> insert($data)) {
                    return json(['result'=>'success', 'msg'=>'初始化成功']);
                }else {
                    return json(['result' => 'error', 'msg' => '网络又开小差了']);
                }
            }else {
                return json(['result' => 'error', 'msg' => '未查询到该户号的相关信息']);
            }
        }else {
            return json(['result' => 'error', 'msg' => '请传参']);
        }
    }

    //查询欠费情况
    public function arrearageInfo($userId, $type) {
        $param = $this->request->param();
        if (!empty($param)) {
            $data = Db::table('jingo_user_charge') -> where(['userId'=>$param['userId'], 'type'=>$param['type']]) -> find();
            $req = new \BmDirectRechargeWaterCoalGetAccountInfoRequest;
            $req->setItemId($data['itemId']);
            $req->setAccount($data['account']);
            $req->setProjectId($data['projectId']);
            $req->setUnitId($data['unitId']);
            $req->setProvince($data['province']);
            $req->setCity($data['city']);
            $req->setUnitName($data['unitName']);
            $req->setCityId($data['cityId']);
            $req->setModeType("2");
            $req->setModeId($data['modeId']);
            $res = $this->client->execute($req, $this->accessToken);
            $res = json_decode(json_encode($res), true);
            if ($res['status'] == 1) {
                $info['customerName'] = $res['waterCoalBills']['waterCoalBill'][0]['customerName'];
                $info['customerAddress'] = $res['waterCoalBills']['waterCoalBill'][0]['customerAddress'];
                $info['payAmount'] = $res['waterCoalBills']['waterCoalBill'][0]['payAmount'];
                $info['unitName'] = $data['unitName'];
                $info['account'] = $data['account'];
                $data = Db::table('jingo_user_charge') -> field(['customerName', 'customerAddress']) -> where(['userId'=>$param['userId'], 'type'=>$param['type']]) -> find();
                if ($data['customerName'] != '' && $data['customerName'] != '') {
                    return json(['result'=>'success', 'value'=>$info]);
                }else {
                    if (Db::table('jingo_user_charge') -> where(['userId'=>$param['userId'], 'type'=>$param['type']]) -> update(['customerName'=>$info['customerName'], 'customerAddress'=>$info['customerAddress']])) {
                        return json(['result'=>'success', 'value'=>$info]);
                    }else {
                        return json(['result' => 'error', 'msg' => '网络又开小差了']);
                    }
                }
            }else {
                return json(['result' => 'error', 'msg' => '未查询到相关信息']);
            }
        }else {
            return json(['result' => 'error', 'msg' => '请传参']);
        }

    }

    //生活缴费
    public function recharge($money, $userId, $type) {
        $param = $this->request->param();
        if (!empty($param)) {
            $data = Db::table('jingo_user_charge') -> where(['userId'=>$param['userId'], 'type'=>$param['type']]) -> find();
            $req = new \BmDirectRechargeLifeRechargePayBillRequest;
            $req->setItemId($data['itemId']);
            $req->setItemNum($param['money']);
            $req->setRechargeAccount($data['account']);
            $res = $this->client->execute($req, $this->accessToken);
            if ($res -> status == 1) {
                return json(['result'=>'success', 'msg'=>'充值成功', 'value'=>$res->data]);
            }else {
                return json(['result'=>'error', 'msg'=>'充值失败']);
            }
        }else {
            return json(['result' => 'error', 'msg' => '请传参']);
        }
    }

    //删除家庭
    public function deleteFamily($userId) {
        $param = $this->request->param();
        if (!empty($param)) {
            if (Db::table('jingo_user_charge') -> where('userId', $param['userId']) -> delete()) {
                return json(['result'=>'success', 'msg'=>'删除成功']);
            }else {
                return json(['result'=>'error', 'msg'=>'删除失败']);
            }
        }else {
            return json(['result' => 'error', 'msg' => '请传参']);
        }
    }

}