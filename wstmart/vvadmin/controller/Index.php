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
    
	public function index()
	{
		return  $this->fetch('index');
	}
}
