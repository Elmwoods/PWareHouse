<?php
namespace wstmart\purse\controller;

//金豆记录控制器
class Imazamox extends Base
{
    //我的金豆
    public function index()
    {
        $users = \think\Db::name('users')->where(array('userId' => session('WST_USER.userId')))->find();
//        dump($users);
        $users['imazamox_number'] = floor($users['imazamox_number']*100)/100;
        //获取金豆变更表的记录
        $im = \think\Db::name('log_moneys')->where(array('targetId' => (int)session('WST_USER.userId')))->where('dataId','in','10,11,12,13,18,20')->where('dataFlag','not in','-1')->order('id desc')->select();
        $this->assign('im',$im);
        $this->assign('users',$users);
        return $this->fetch('gold_bean');
    }


}