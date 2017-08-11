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
use wstmart\chat\model\Joys;

class Joy extends Controller {
    public $request;

    public function __construct() {
        $this -> request = request();
    }

    //游戏初始化
    public function index($userId)
    {
        header('content-type:application:json;charset=utf8');
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        // $param =  input('post.');
        $data  =  rsaDecrypt(input('post.'));
        $param =  json_decode($data);  
        //$param = $this->request->param();
        $time = strtotime(date("Y-m-d 00:00:00"));
        $GPN = Db::table('jingo_users')->field(['imazamox_number', 'userPhoto'])->where('userId', $param->userId)->find();
        if (!empty($param)) {
            $joy = new Joys();
            $arr = $joy->where('userId', $param->userId)->find();         //查询是否有该用户的游戏记录
            if ($arr) {
                if (($time - $arr['time']) > 86400) {     //判断过了一天,更新动作状态
                    $data['userId'] = $param->userId;
                    $data['joy1'] = 0;
                    $data['joy2'] = 0;
                    $data['joy3'] = 0;
                    $data['time'] = $time;
                    $joy -> startTrans();
                    if ($joy->isUpdate(true)->save($data, ['userId' => $param->userId])) {          //更新游戏状态
                        $joy -> commit();
                        return json(array('result' => 'success', 'msg' => '更新成功,可以开始动作了'));
                    } else {
                        $joy -> rollback();
                        return json(array('result' => 'error', 'msg' => '更新失败,请重试'));
                    }
                }
                return json(array('result' => 'success', 'value' => ['joy1' => $arr['joy1'], 'joy2' => $arr['joy2'], 'joy3' => $arr['joy3'], 'GPN' => $GPN['imazamox_number'], 'userPhoto'=>$GPN['userPhoto']]));
            } else {     //如果是第一次玩,则初始化
                $data = [
                    'userId' => $param->userId,
                    'joy1' => 0,
                    'joy2' => 0,
                    'joy3' => 0,
                    'time' => $time,
                    'GPN' => $GPN['imazamox_number']
                ];
                if ($joy->save($data)) {
                    return json(array('result' => 'success', 'value' => ['joy1' => 0, 'joy2' => 0, 'joy3' => 0, 'GPN' => $GPN['imazamox_number']]));        //返回动作状态
                } else {
                    return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍后重试'));
                }
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    public function update()
    {
        header('content-type:application:json;charset=utf8');
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        // $param = input('post.');
        $data  = rsaDecrypt(input('post.'));
        if($data){
            $param = json_decode($data);
            //$param = $this->request->param();
            $now = strtotime(date("Y-m-d 00:00:00"));
            if (!empty($param)) {
                $joy = new Joys();
                $arr = $joy->where('userId', $param->userId)->find();         //查询用户游戏记录
                $data = [];
                if ($arr) {

                    /*if($param['userId']==114){
                        $data['joy1'] = 0;
                        $data['joy2'] = 0;
                        $data['joy3'] = 0;
                    }else{*/

                        $data['joy1'] = $param->joy1;
                        $data['joy2'] = $param->joy2;
                        $data['joy3'] = $param->joy3;
    //                }
                    $data['time'] = $now;
                    $data['GPN'] = $arr['GPN'];
                    $data['userId'] = $param->userId;
                    //将信息添加到金豆子游戏里面
                    $data1 = [
                        'userId'=>$param->userId,
                        'friendId'=>$param->userId,
                        'GPN'=>0.003,
                        'createTime'=>time()
                    ];
                    $joy -> startTrans();
                    Db::table('jingo_users') -> startTrans();
                    if ($joy->isUpdate(true)->save($data, ['userId' => $param->userId]) && Db::table('jingo_users')->where('userId', $param->userId)->update(['imazamox_number' => ['exp','imazamox_number+0.003']]) && Db::table('jingo_joy_collect')->insert($data1)) {

                        $joy -> commit();
                        Db::table('jingo_users') -> commit();
                        return json(array('result' => 'success', 'msg' => '更新成功,可以开始动作了'));
                    } else {
                        $joy -> rollback();
                        Db::table('jingo_users') -> rollback();
                        return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
                    }
                } else {
                    return json(array('result' => 'error', 'msg' => '非法操作'));
                }
            } else {
                return json(array('result' => 'error', 'msg' => '请传参'));
            }
        }else{
            return '参数错误';
        }
    }


}