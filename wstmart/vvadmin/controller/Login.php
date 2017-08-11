<?php
namespace wstmart\vvadmin\controller;
use think\Db;
use think\Session;
use think\Controller;
class Login extends Controller{

    public function turn() {
        $needle = strrpos($_SERVER['SERVER_NAME'], '.') + 1;        //获取最后一次 . 出现的位置
        $suffix =  substr($_SERVER['SERVER_NAME'], $needle);        //获取域名后缀
        if ($suffix == 'cn') {
            $this -> redirect("http://www.jingomall.com/hostadmin");
        }
    }

	public function login(){
    	$this->turn();
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
