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

class Index extends Controller {
    public $request;
    private $RongCloud;

    public function __construct() {
        $this -> request = request();
        $this->RongCloud = new Rongyun();
    }

    public function system() {
        $param = $this->request->param();
        if (!empty($param)) {
            $info = $this->RongCloud->PublishSystems($param['fromUserId'],$param['toUserId'],$param['objectName'],$param['content'],$param['pushContent'],$param['pushData']);
            print_r($info);
        }

    }

    //随机红包函数
    public function packet($total, $num) {
        $min        =   0.01;       //红包最小值
        $redPacket  =   [];         //初始化红包数组
        for ($i=1;$i<$num;$i++)
        {
            $safe_total     =       ($total-($num-$i)*$min)/($num-$i);          //随机数安全上限
            $money          =       mt_rand($min*100,$safe_total*100)/100;      //红包值
            $total          -=      $money;
            $redPacket[$i]  =       sprintf('%.2f', $money);
        }
        $redPacket[$num]    =       sprintf('%.2f', $total);
        return $redPacket;
    }

    public function redPacket() {
        $total      =   100;         //红包总金额
        $num        =   21;         //红包个数
        $redPacket  =   $this -> packet($total, $num);
        echo '<pre />';
        shuffle($redPacket);
        print_r($redPacket);
    }

}