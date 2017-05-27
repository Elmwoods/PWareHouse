<?php
/**
 * Created by PhpStorm.
 * User: Lynn
 * Date: 2017/4/19
 * Time: 11:33
 */

namespace wstmart\home\controller;


class Test {
    public $v = '';
    public function abc() {
        $this -> v = strtotime('-1d');
        //echo $this -> v;
    }
    public function index() {
        $this -> abc();
        echo $this -> v;
    }
}