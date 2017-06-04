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
    public function index() {
        $param = $this->request->param();
        $time = strtotime(date("Y-m-d 00:00:00"));
        if (!empty($param)) {
            $joy = new Joys();
            $arr = $joy -> where('userId', $param['userId']) -> find();
            if ($arr) {
                return json(array('result' => 'success', 'value' => ['joy1'=>$arr['joy1'], 'joy2'=>$arr['joy2'], 'joy3'=>$arr['joy3']], 'GPN'=>$arr['GPN']));
            }else {
                $data = [
                    'userId'=>$param['userId'],
                    'joy1'=>0,
                    'joy2'=>0,
                    'joy3'=>0,
                    'time'=>$time,
                ];
                if ($joy -> save($data)) {
                    return json(array('result' => 'success', 'value' => ['joy1'=>0, 'joy2'=>0, 'joy3'=>0]));        //返回动作状态
                }else {
                    return json(array('result' => 'error', 'value' => '网络又开小差了,请稍后重试'));
                }
            }
        }else {
            return json(array('result' => 'error', 'value' => '请传参'));
        }
    }

    public function update() {
        $param = $this->request->param();
        $now = strtotime(date("Y-m-d 00:00:00"));
        if (!empty($param)) {
            $joy = new Joys();
            $arr = $joy -> where('userId', $param['userId']) -> find();
            if ($arr) {
                if (($now - $arr['time']) > 86400) {     //判断过了一天,更新动作状态
                    $arr['joy1'] = 0;
                    $arr['joy2'] = 0;
                    $arr['joy3'] = 0;
                    $arr['time'] = $now;
                    if ($joy -> isUpdate('true') -> save($arr)) {
                        return json(array('result' => 'success', 'value' => '更新成功,可以开始动作了'));
                    }else {
                        return json(array('result' => 'error', 'value' => '更新失败,请重试'));
                    }
                }
                $arr['joy1'] = $param['joy1'];
                $arr['joy2'] = $param['joy2'];
                $arr['joy3'] = $param['joy3'];
                $arr['time'] = $now;
                if ($joy -> isUpdate('true') -> save($arr)) {
                    return json(array('result' => 'success', 'value' => '更新成功,可以开始动作了'));
                }else {
                    return json(array('result' => 'error', 'value' => '更新失败,请重试'));
                }
            }else {
                return json(array('result' => 'error', 'value' => '非法操作'));
            }
        }else {
            return json(array('result' => 'error', 'value' => '请传参'));
        }
    }
}