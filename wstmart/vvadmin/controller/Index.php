<?php
namespace  wstmart\vvadmin\Controller;
use think\Controller;
use think\Session;
class  index  extends Controller
 {
 
    public function _initialize(){
		if (!Session::has('loginuser')) {
		    $this->redirect(url('Login/login'));
			//$this->error('不登录,不让进',url('Login/login'));
		}
	}

    public function turn() {
        $needle = strrpos($_SERVER['SERVER_NAME'], '.') + 1;        //获取最后一次 . 出现的位置
        $suffix =  substr($_SERVER['SERVER_NAME'], $needle);        //获取域名后缀
        if ($suffix == 'cn') {
            $this -> redirect("http://www.jingomall.com/hostadmin");
        }
    }

	public function index()
	{
	    $this -> turn();
		return  $this->fetch('index');
	}
}
