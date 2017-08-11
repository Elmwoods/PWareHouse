<?php
namespace wstmart\purse\controller;

//还贷款控制器
class Loan extends Base
{

    public function login(){
        return $this->fetch('purse1');
    }

    //贷款须知
    public function loanNotes(){
        return $this->fetch('loan_notes');
    }

    //我的贷款
    public function index()
    {
        $users = \think\Db::name('users')->where(array('userId' => session('WST_USER.userId')))->find();
        $al = \think\Db::name('log_moneys')->where(['targetId' => session('WST_USER.userId'),'dataSrc'=>'贷款'])->where('dataFlag','not in','-1')->order('id desc')->paginate(10);
        if(!empty($_GET['id'])){
            //获取表的数据
            $news = \think\Db::name('messages')->where(array('id' => $_GET['id']))->find();
            //如果还款金额为0，或者还款日期改变
            if($users['residual_repayment']==0 || $users['repayment_date'] != date('Y-m-d H:i:s',$news['repaymentDate'])) {
                //将信息标记为完成
                \think\Db::name('messages')->where(['id'=>$_GET['id']])->update(['dataFlag'=>1]);
            }
            $this->assign('news',$news);
        }
        $page = $al->render();
        $this->assign('page',$page);
        $this->assign('users',$users);
        $this->assign('al',$al);
        return $this->fetch('loan');
    }

    //去贷款
    public function loan(){
//        var_dump($_POST);
        //查询贷款用户的信息
        $user = \think\Db::name('users')->where(['userId' => session('WST_USER.userId')])->find();
//        dump($user);
        if(is_numeric($_POST['loan_amount']) && $_POST['loan_amount']>0){
            if($user['residual_repayment'] + $_POST['loan_amount'] <= 10000) {
                if($user['loanTimes']<3){
                    // 启动事务
                    \think\Db::startTrans();
                    try{
                        //修改用户表的信息
                        $users['userMoney'] = $user['userMoney'] + $_POST['loan_amount'];
                        $users['residual_repayment'] = $user['residual_repayment'] + round($_POST['loan_amount']*1.0082,2);
                        $users['loan_date'] = date('Y-m-d H:i:s');
                        $users['repayment_date'] = date('Y-m-d H:i:s',strtotime("+1 month"));
                        $users['loanAmount'] = $users['residual_repayment'];
                        $users['overdue'] = 0;
                        $users['loanTimes'] = $user['loanTimes']+1;
                        //                dump($users);die;
                        $userId = \think\Db::name('users')->where(['userId' => session('WST_USER.userId')])->update($users);
                        //将信息添加到还贷款日志
                        $alNo = $this->makeSn();
                        //                dump($alNo);die;
                        $data['user_id'] = session('WST_USER.userId');
                        $data['al_type'] = '贷款';
                        $data['al_sn'] = $alNo;
                        $data['al_amonut'] = $_POST['loan_amount'];

                        $data['al_payment'] = '钱袋子';
                        $data['al_status'] = '贷款成功';
                        $data['al_add_time'] = date('Y-m-d H:i:s');
                        $da = \think\Db::name('also_loan')->insert($data);
                        //                dump($da);die;
                        //将信息添加到资金流水
                        $insert['targetType'] = session('WST_USER.userType');
                        $insert['targetId'] = session('WST_USER.userId');
                        $insert['dataId'] = 8;
                        $insert['dataSrc'] = '贷款';
                        $insert['moneyType'] = 1;
                        $insert['payType'] = 1;
                        $insert['money'] = $_POST['loan_amount'];
                        $insert['tradeNo'] = $alNo;
                        $insert['createTime'] = date('Y-m-d H:i:s');
                        $insert['endTime'] = date('Y-m-d H:i:s');
                        $in = \think\Db::name('log_moneys')->insert($insert);
                        // 提交事务
                        \think\Db::commit();
                    } catch (\Exception $e) {
                        // 回滚事务
                        \think\Db::rollback();
                    }

                    if($userId && $da && $in) {
                        return WSTReturn('贷款成功',1);
                    } else {
                        return WSTReturn('贷款失败',2);
                    }

                }else{
                    return WSTReturn('每月最多贷款三次',2);
                }
            } else {
                return WSTReturn('贷款额度不足',2);
            }
        } else {
            return WSTReturn('请输入正确的金额',2);
        }
    }

    //去还款
    public function repayment(){
//        var_dump($_POST);die;
        //查询还款用户的信息
        $user = \think\Db::name('users')->where(['userId' => session('WST_USER.userId')])->find();
//        dump($user);
        if(is_numeric($_POST['repayment_amount']) && $_POST['repayment_amount']>0){
            if($user['residual_repayment'] >= $_POST['repayment_amount']) {
                // 启动事务
                \think\Db::startTrans();
                try{
                    //修改用户表的信息
                    $users['userMoney'] = $user['userMoney'] - $_POST['repayment_amount'];
                    //信用分
                    if($user['overdue'] == 1){
                        $score = 0;
                    }else{
                        $score = floor($_POST['repayment_amount'] * 0.01);
                    }

//                    dump($score);die;
                    $users['userScore'] = $user['userScore'] + $score;
                    session('WST_USER.userScore',$users['userScore']);
                    $users['residual_repayment'] = $user['residual_repayment'] - $_POST['repayment_amount'];
                    $user = \think\Db::name('users')->where(['userId' => session('WST_USER.userId')])->update($users);
    //                dump($user);die;
                    //将信息添加到还贷款日志
                    $alNo = $this->makeSn();
                    $data['user_id'] = session('WST_USER.userId');
                    $data['al_type'] = '还款';
                    $data['al_sn'] = $alNo;
                    $data['al_payment'] = '钱袋子';
                    $data['al_amonut'] = $_POST['repayment_amount'];
                    $data['al_status'] = '还款成功';
                    $data['al_add_time'] = date('Y-m-d H:i:s');
                    $da = \think\Db::name('also_loan')->insert($data);
    //                dump($da);die;
                    //将信息添加到资金流水
                    $insert['targetType'] = session('WST_USER.userType');
                    $insert['targetId'] = session('WST_USER.userId');
                    $insert['dataId'] = 9;
                    $insert['dataSrc'] = '贷款';
                    $insert['moneyType'] = 0;
                    $insert['money'] = $_POST['repayment_amount'];
                    $insert['tradeNo'] = $alNo;
                    $insert['payType'] = 0;
                    $insert['createTime'] = date('Y-m-d H:i:s');
                    $insert['endTime'] = date('Y-m-d H:i:s');
                    $in = \think\Db::name('log_moneys')->insert($insert);
                    //判断是否是从信息页面跳转过来的
                    if(!empty($_POST['loan'])){
                        //将信息标为已完成
                        \think\Db::name('messages')->where(['id'=>$_POST['loan']])->update(['dataFlag'=>1,'completeTime'=>date('Y-m-d H:i:s')]);
                    }
                    // 提交事务
                    \think\Db::commit();
                    return WSTReturn('还款成功',1);
                } catch (\Exception $e) {
                    // 回滚事务
                    \think\Db::rollback();
                    return WSTReturn('还款失败',2);
                }

            } else {
                return WSTReturn('还款金额超出',2);
            }
        } else {
            return WSTReturn('请输入正确的金额',2);
        }
    }

    //消息
    public function news() {
        $news = \think\Db::name('messages')->where(['receiveUserId'=>session('WST_USER.userId'),])->where('msgTitle','还款')->where('msgTitle','收款')->order('id desc')->select();
        $news2 =\think\Db::name('messages')->where('receiveUserId',session('WST_USER.userId'))->update(['msgStatus'=>1]);
        session('NEWS_COUNT',0);
        $this->assign('news',$news);
        return $this->fetch('information');
    }

    //如果还款时间超出，每天增加贷款金额的0.05%
    public function overdue(){
//        dump(date('d'));die;
        $users = \think\Db::name('users')->where('overdue',1)->select();
//        dump($users);
        foreach($users as $user){
            //将应还金额按照每天0.05%的比例增加
            $money = $user['residual_repayment'] + round($user['loanAmount'] * (5/10000),2);
//            dump($money);die;
            \think\Db::name('users')->where('userId',$user['userId'])->update(['residual_repayment'=>$money]);
        }
        //判断当前是否是当月的第一天
        if(date('d') == 01){
            //将贷款次数清零
            $loanTimes = \think\Db::name('users')->where('loanTimes','GT',0)->update(['loanTimes'=>0]);
        }
    }



}