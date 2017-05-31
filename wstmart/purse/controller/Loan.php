<?php
namespace wstmart\purse\controller;

//还贷款控制器
class Loan extends Base
{
    //我的贷款
    public function index()
    {
        $users = \think\Db::name('users')->where(array('userId' => session('WST_USER.userId')))->find();
        $al = \think\Db::name('log_moneys')->where(['targetId' => session('WST_USER.userId'),'dataSrc'=>'贷款'])->order('id desc')->paginate(10);
        if(!empty($_GET['id'])){
            //获取表的数据
            $news = \think\Db::name('news')->where(array('id' => $_GET['id']))->find();
            //如果还款金额为0，或者还款日期改变
            if($users['residual_repayment']==0 || $users['repayment_date'] != $news['repayment_date']) {
                //将信息标记为完成
                \think\Db::name('news')->where(['id'=>$_GET['id']])->update(['is_complete'=>1]);
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
                // 启动事务
                \think\Db::startTrans();
                try{
                //修改用户表的信息
                $users['userMoney'] = $user['userMoney'] + $_POST['loan_amount'];
                $users['residual_repayment'] = $user['residual_repayment'] + $_POST['loan_amount'];
                $users['loan_date'] = date('Y-m-d H:i:s');
                $users['repayment_date'] = date('Y-m-d H:i:s',strtotime("+1 month"));
                $user = \think\Db::name('users')->where(['userId' => session('WST_USER.userId')])->update($users);
//                dump($user);die;
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
                $insert['money'] = $_POST['loan_amount'];
                $insert['tradeNo'] = $alNo;
                $insert['createTime'] = date('Y-m-d H:i:s');
                $in = \think\Db::name('log_moneys')->insert($insert);
                    // 提交事务
                    \think\Db::commit();
                } catch (\Exception $e) {
                    // 回滚事务
                    \think\Db::rollback();
                }
                if($user && $da && $in) {
                    return WSTReturn('贷款成功',1);
                } else {
                    return WSTReturn('贷款失败',2);
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
                $in = \think\Db::name('log_moneys')->insert($insert);
                //判断是否是从信息页面跳转过来的
                if(!empty($_POST['loan'])){
                    //将信息标为已完成
                    \think\Db::name('news')->where(['id'=>$_POST['loan']])->update(['is_complete'=>1,'complete_time'=>date('Y-m-d H:i:s')]);
                }
                    // 提交事务
                    \think\Db::commit();
                } catch (\Exception $e) {
                    // 回滚事务
                    \think\Db::rollback();
                }
                if($user && $da && $in) {
                    return WSTReturn('还款成功',1);
                } else {
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
        $news = \think\Db::name('news')->where(['user_name'=>session('WST_USER.loginName')])->order('id desc')->select();
        $news2 =\think\Db::name('news')->where('user_name',session('WST_USER.loginName'))->update(['state'=>1]);
        session('WST_USER.NEWS_COUNT',0);
        $this->assign('news',$news);
        return $this->fetch('information');
    }





}