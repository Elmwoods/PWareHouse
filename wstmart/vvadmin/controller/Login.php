<?php
namespace wstmart\vvadmin\controller;
use think\Db;
use think\Session;
use think\Controller;
class Login extends Controller{
    
	public function login(){
		return $this->fetch();
	}
	public function dologin(){
		$model=Db::name('vvchat','wst_');
		$data['username']=empty(input('post.username'))?'':trim(input('post.username'));
		$data['password']=empty(input('post.password'))?'':md5(trim(input('post.password')));
		$result=$model->where('loginName=:loginName and loginpwd=:loginpwd')->bind(['loginName'=>$data['username'],'loginpwd'=>$data['password']])->find();
		if ($result) {
			Session::set('loginuser',$result);
			$this->success('登陆成功',url('Index/index'), '', 1);
		}else{
			$this->error('账号或密码错误');
		}
	}
	public function loginout(){
		Session::delete('loginuser');
		$this->redirect('http://www.jingomall.com/');
	}
}
?>
