<?php
/**
 * Created by PhpStorm.
 * User: Lynn
 * Date: 2017/4/26
 * Time: 9:21
 */

namespace wstmart\vv\controller;


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

}