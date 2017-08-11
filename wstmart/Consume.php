<?php
namespace wstmart\purse\controller;

//消费记录控制器
class Consume extends Base{
        //我的钱袋
    public function index() {
        $users = \think\Db::name('users')->where(array('userId' => (int)session('WST_USER.userId')))->find();
        $log = \think\Db::name('log_moneys')->where(array('targetId' => (int)session('WST_USER.userId')))->where('dataFlag','not in','-1')->order('id desc')->paginate();

        $page = $log->render();
        $this->assign('page',$page);
        $this->assign('users',$users);
        $this->assign('log',$log);
        return $this->fetch('purse');
    }


    //我的账单
    public function bill() {
//        dump($_GET);
        $where ="targetId=".session('WST_USER.userId');
        $da1 = date('Y-m-d H:i:s',strtotime('-1 month'));
        $da2 = date('Y-m-d H:i:s',strtotime('-3 month'));
        $da3 = date('Y-m-d H:i:s',strtotime('-1 year'));
        $month_array = array(1=>$da1,2=>$da2,3=>$da3);
        $type_array = array(1=>'充值',2=>'提现',3=>'%转账%',4=>'消费',5=>'贷款',6=>'还款',7=>'撒金豆');
        $status_array = array(1=>2,2=>1,3=>0);

        if(isset($_GET['month'])&&($_GET['month']!=0)) $where .= " AND createTime>'".$month_array[$_GET['month']]."'";
        if(isset($_GET['type'])&&($_GET['type']!=0)) $where .= " AND dataSrc like '".$type_array[$_GET['type']]."'";
        if(isset($_GET['status'])&&($_GET['status']!=0)) $where .= " AND dataFlag='".$status_array[$_GET['status']]."'";
        if(isset($_GET['st'])&&($_GET['et']!='')&&($_GET['st']!=$_GET['et'])&&$_GET['st']!='') $where.=" AND createTime>='".$_GET['st']."'"." AND createTime<='".date('Y-m-d',strtotime($_GET['et'] . "+1 day"))."'";
        if(isset($_GET['st'])&&($_GET['et']=='')) $where.=" AND createTime>='".$_GET['st']."'";
        if(isset($_GET['et'])&&($_GET['st']=='')) $where.=" AND createTime<='".date('Y-m-d',strtotime($_GET['et'] . "+1 day"))."'";
        if(isset($_GET['st'])&&($_GET['et']==$_GET['st'])) $where.=" AND createTime LIKE '%".$_GET['st']."%'";
//        dump($where);
        $log = \think\Db::name('log_moneys')->where($where)->where('dataFlag','not in','-1')->where('dataId','not in','10,11,12,13,18,20')->order('id','desc')->paginate(10,false,['query'=>$_GET]);
//        dump($log);
        $page = $log->render();
        $this->assign('page',$page);
        $this->assign('log',$log);
        return $this->fetch('consume_list');

    }


    //获取为读信息条数Ajax
    public function news_count(){
        $news_count = \think\Db::name('messages')->where(['receiveUserId'=>session('WST_USER.userId')])->where('msgStatus',0)->count();

        echo $news_count;
    }

    //删除我的钱袋、我的账单数据
    public function del_id(){
        // echo $_POST['id'];
        $del = \think\Db::name('log_moneys')->where('id='.intval($_POST['id']))->update(['dataFlag'=>-1]);
        // echo $del;
        if($del){
            return WSTReturn("删除成功", 1);
        }else{
            return WSTReturn("删除成功", 2);
        }
    }

    //删除我的金豆
    public function del_jd(){
        // echo $_POST['id'];
        $del = \think\Db::name('log_moneys')->where('id='.intval($_POST['id']))->update(['dataFlag'=>-1]);
        // echo $del;
        if($del){
            return WSTReturn("删除成功", 1);
        }else{
            return WSTReturn("删除成功", 2);
        }
    }

    //删除我的贷款
    public function del_dl(){
        // echo $_POST['id'];
        $del = \think\Db::name('log_moneys')->where('id='.intval($_POST['id']))->update(['dataFlag'=>-1]);
        // echo $del;
        if($del){
            return WSTReturn("删除成功", 1);
        }else{
            return WSTReturn("删除成功", 2);
        }
    }

   public function Details(){
        $det = \think\Db::name('log_moneys')->where('id='.intval($_POST['id']))->find();
        // var_dump($det);
       $det['remark'] = textDecode($det['remark']);
        if($det){
            return $det;
        }else{
            return 2;
        }
   }

}

