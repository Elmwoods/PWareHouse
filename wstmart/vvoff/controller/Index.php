<?php
namespace  wstmart\vvoff\Controller;
use think\Db;
use think\Controller;
use wstmart\vvoff\model\VvoffArticle as M;
use wstmart\vvoff\model\VvoffStaff as MM;
use wstmart\vvoff\model\VvoffEnarticle as D;
use wstmart\vvoff\model\VvoffEnstaff as T;

class  index extends Controller
 {
    public function index(){
        return view('lead');
    }
    public function purse(){
        return view('purse');
    }

    public function Ephone(){
        $m=new T;
        $ii=$m->where('e_is_see',1)->order('id', 'asc')->select();
        $this->assign('ensta',$ii);
        $iii=$m->where('e_is_see',1)->order('id', 'asc')->limit(0,3)->select();
        $this->assign('ensta2',$iii);
        $mm=new D;
        $dd=$mm->where('e_is_see',1)->order('id', 'asc')->select();
        $this->assign('enart',$dd);
        $ddd=$mm->where('e_is_see',1)->order('id', 'asc')->limit(0,3)->select();
        $this->assign('enart2',$ddd);
        $serialize = $this->setqrcode();
        $date = date("Ymd",time());
        $this->assign('data',$date);
        $this -> assign('serialize', $serialize);
        return view('Ephone');
    }

    public function Eteam(){
        $list = Db::name('vvoff_enstaff')->where('e_is_see',1)->order('id', 'asc')->paginate(3);
        $this->assign('list',$list);
        return view('eteam');
    }

	public function web()
	{
		$m=new MM;
		$ii=$m->where('is_see',1)->order('id', 'asc')->select();
		$iii=$m->where('is_see',1)->limit(0,3)->select();
		$this->assign('sta',$ii);
		$this->assign('sta2',$iii);
		$mm=new M;
		$dd=$mm->where('is_see',1)->order('art_addtime', 'DESC')->select();
		$arr = [];
		$i=0;
		foreach ($dd as $v) {
		    $arr[$i]['id'] = $v['id'];
		    $arr[$i]['art_img'] = $v['art_img'];
		    $arr[$i]['art_name'] = $v['art_name'];
		    $arr[$i]['art_content'] = $v['art_content'];
		    $arr[$i]['art_short'] = mb_substr($v['art_content'], 0, 120, 'utf-8').'...';
		    $i++;
        }
		$this->assign('art2',$arr);
		$this->assign('art',$dd);
		$serialize = $this->setqrcode();
        $date = date("Ymd",time());
        $this->assign('data',$date);
		$this -> assign('serialize', $serialize);
		return  $this->fetch('index');
	}

    public function phone()
    {
        $m=new MM;
        $ii=$m->where('is_see',1)->order('id', 'asc')->select();
        $iii=$m->where('is_see',1)->limit(0,3)->select();
        $this->assign('sta',$ii);
        $this->assign('sta2',$iii);
        $mm=new M;
        $dd=$mm->where('is_see',1)->limit(0,3)->select();
        $arr = [];
        $i=0;
        foreach ($dd as $v) {
            $arr[$i]['id'] = $v['id'];
            $arr[$i]['art_img'] = $v['art_img'];
            $arr[$i]['art_name'] = $v['art_name'];
            $arr[$i]['art_content'] = $v['art_content'];
            $arr[$i]['art_short'] = mb_substr($v['art_content'], 0, 120, 'utf-8').'...';
            $i++;
        }
        $this->assign('art2',$arr);
        $this->assign('art',$dd);
        $serialize = $this->setqrcode();
        $date = date("Ymd",time());
        $this->assign('data',$date);
        $this -> assign('serialize', $serialize);
        return  $this->fetch('phone');
    }

	public function english()
	{
		$m=new T;
		$ii=$m->where('e_is_see',1)->order('id', 'asc')->select();
		$this->assign('ensta',$ii);
		$mm=new D;
		$dd=$mm->where('e_is_see',1)->order('id', 'asc')->select();
		$this->assign('enart',$dd);
        $serialize = $this->setqrcode();
        $date = date("Ymd",time());
        $this->assign('data',$date);
        $this -> assign('serialize', $serialize);
		return  $this->fetch('english');
	}
	public function news()
	{
		$mm=new M;
		$ii=input('param.');
		$dd=$mm->where('id',$ii['id'])->order('id', 'asc')->select();
		$this->assign('art',$dd);
		return $this->fetch('news');
	}
    public function webnew(){
        $mm=new D;
        $ii=input('param.');
        $dd=$mm->where('id',$ii['id'])->order('id', 'asc')->select();
        $this->assign('enart',$dd);
        return view('webnew');
    }
	public function ennews()
	{
		$mm=new D;
		$ii=input('param.');
		$dd=$mm->where('id',$ii['id'])->order('id', 'asc')->select();
		$this->assign('enart',$dd);
		return $this->fetch('ennews');
	}

	public function check() {
        if (isset($_POST['check'])) {
            echo $_POST['check'];exit();
        //echo ($_POST['check']);
            //$sql = "SELECT member_name FROM jingo_user_token WHERE serialize = '{$_POST['check']}'";      //查询是否存在登录页面的二维码序列号
            //$result = pdo() -> query($sql);
            //$arr = $result -> fetch(2);
            $arr = Db::table('jingo_user_token') -> field(['member_name', 'member_id']) -> where('serialize', $_POST['check']) -> find();
            if ($arr['member_name'] != null) {
                $expire = time() - 120;      //2分钟之前的时间点
                Db::table('jingo_user_token') -> where('set_time', '<', $expire) -> delete();
                //setcookie('name', $arr['member_name'], time() + 24*3600);           //设置cookie,用于免登陆
                session('WST_USER.userName',$arr['member_name']);
                session('WST_USER.userId',$arr['member_id']);
                echo 'true';
            }else {
                echo 'false';
            }
        }else {
            echo 'false';
        }
    }

    public function checkToken($token_id, $token) {
        //$sql = "SELECT login_time, member_name FROM jingo_user_token WHERE token_id = '$token_id' AND token = '$token' LIMIT 1";
        //$result = pdo() -> query($sql);
        //$arr = $result -> fetch(2);
        $arr = Db::table('jingo_user_token') -> field(['login_time', 'member_name', 'member_id']) -> where(['token_id'=>$token_id, 'token'=>$token]) -> find();
        if ($arr) {
            if ((time() - intval($arr['login_time'])) > 7*24*3600) {
                return 'invalid';
            }else {
                $request = array();
                $request['value'] = 'success';
                $request['name'] = $arr['member_name'];
                $request['id'] = $arr['member_id'];
                return $request;
            }
        }else {
            return 'error';
        }
    }

    public function setqrcode() {
	    include_once 'phpqrcode.php';

        //生成序列号
        $rand = mt_rand(10000000, 99999999);
        $time = time();
        $serialize = md5($rand).md5($time);
        $date = date("Ymd",time());
        if (!file_exists(ROOT_PATH.'public/vvoff/serialize/'.$date)) {
            mkdir(ROOT_PATH.'public/vvoff/serialize/'.$date);
        }

        //生成二维码
        $expire = $time - 60;      //1分钟之前的时间点
        Db::table('jingo_user_token') -> where('set_time', '<', $expire) -> delete();
        Db::table('jingo_user_token') -> insert(['serialize'=>$serialize, 'set_time'=>$time]);
        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 6;//生成图片大小
        //生成二维码图片
        QRcode::png($serialize, ROOT_PATH.'public/vvoff/serialize/'.$date.'/'.$serialize.'.png', $errorCorrectionLevel, $matrixPointSize, 2);
        $logo = ROOT_PATH.'public/vvoff/serialize/logo.png';//准备好的logo图片
        $QR = ROOT_PATH.'public/vvoff/serialize/'.$date.'/'.$serialize.'.png';//已经生成的原始二维码图

        if ($logo !== FALSE) {
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;

            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }

        //输出图片
        imagepng($QR, ROOT_PATH.'public/vvoff/serialize/'.$date.'/'.$serialize.'.png');
        return $serialize;
    }

    //用于手机扫码响应
    public function valid() {
        if (isset($_POST['token_id'])) {
            $_POST['token'] = str_replace('\/', '/', $_POST['token']);          //将json格式转换的\/替换为/
            $request = $this->checkToken($_POST['token_id'], $_POST['token']);         //获取检查token之后的返回值
            if (is_array($request) && $request['value'] == 'success') {         //如果返回值是数组并且有success值则往下执行
                $boolen = Db::table('jingo_user_token') -> where('serialize', $_POST['serialize']) -> update(['token'=>$_POST['token'], 'member_name'=>$request['name'], 'member_id'=>$request['id']]);
                //$expire = time() - 60;
                //$sql1 = "DELETE FROM jingo_user_token WHERE set_time < '$expire'";
                //pdo() -> exec($sql1);
                //Db::table('jingo_user_token') -> where('set_time', '<', $expire) -> delete();
                if ($boolen) {
                    return urldecode(json_encode(array('result' => 'success','value' => urlencode('验证成功,已写入用户名'))));
                }else {
                    return urldecode(json_encode(array('result' => 'error','value' => urlencode('数据库更新失败'))));
                }
            }else {
                return urldecode(json_encode(array('result' => 'error','value' => urlencode('检查登录秘钥出错'))));
            }
        }else {
            return urldecode(json_encode(array('result' => 'error','value' => urlencode('请确认是否发送登录秘钥'))));
        }
    }
    public function team() {
		$list = Db::name('vvoff_staff')->where('is_see',1)->order('id', 'asc')->paginate(4);
		$this->assign('list', $list);
		return view('team');
    }
    
    public function news_list() {
        $list = Db::name('vvoff_article')->where('is_see',1)->order('id', 'asc')->paginate(3);
		$this->assign('list', $list);
		return view('news_list');
	}
	
	public function p_news() {
	    $param = request()->param();
        if (!empty($param['id'])) {
            $mm=new M;
		    $dd=$mm->where('id',$param['id'])->find();
		    $this->assign('art',$dd);
            return view('p_new');
        }else {
            $this -> error('网络又开小差了，请稍后重试');
        }
	 }

     public function ep_new1() {
        $param = request()->param();
        if (!empty($param['id'])) {
            $mm=new M;
            $dd=$mm->where('id',$param['id'])->find();
            $this->assign('e_art',$dd);
            return view('ep_new1');
        }else {
            $this -> error('网络又开小差了，请稍后重试');
        }
     }

     public function agreement() {
        return view('agreement');
     }
}
