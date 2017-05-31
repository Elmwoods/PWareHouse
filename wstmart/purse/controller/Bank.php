<?php
namespace wstmart\purse\controller;

//银行卡控制器
class Bank extends Base
{
    //我的银行卡
    public function index()
    {
        return $this->fetch('bank');
    }


}