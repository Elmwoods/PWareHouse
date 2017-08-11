<?php
/**
 * Created by PhpStorm.
 * User: Me
 * Date: 2017/7/28
 * Time: 10:22
 */

namespace wstmart\chat\controller;


class Card
{
    public $request;
    private $RongCloud;


    public function __construct()
    {
        $this->request = request();
        $this->RongCloud = new Rongyun();
    }

    public function add() {

    }
}