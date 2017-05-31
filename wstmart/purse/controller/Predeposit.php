<?php
namespace wstmart\purse\controller;

//金豆记录控制器
class Predeposit extends Base
{
    //充值余额
    public function recharge()
    {
        $payments = model('common/payments')->getOnlinePayments();
//        dump($payments);
        $this->assign('payments',$payments);
        return $this->fetch('recharge');
    }

    //充值金豆
    public function recharge_imazamox()
    {
        $payments = model('common/payments')->getOnlinePayments();
//        dump($payments);
        $this->assign('payments',$payments);
        return $this->fetch('recharge_imazamox');
    }

    //余额提现
    public function withdrawals()
    {
        if ($_POST) {
//            var_dump($_POST);die;
            if (!empty($_POST['name'])) {
                if ($_POST['number'] >= 0.1) {
                    //获取当前用户的信息
                    $user = \think\Db::name('users')->where(array('userId' => session('WST_USER.userId')))->find();
                    if ($_POST['number'] <= $user['userMoney']) {
                        $name = $_POST['name'];
                        $amount = $_POST['number'];
                        $out_biz_no = $this->makesn();
//                    var_dump($_POST);die;
//                    $model_withdrawals->add($alipyname,$amount,$out_biz_no);
                        $fm = "<form name='aaa' action='/api/alipay.fund.trans.toaccount.transfer/index.php' method='post'>";
                        $fm .= "<input type='hidden' name='alipyname' value=" . $name . ">";
                        $fm .= "<input type='hidden' name='amount' value=" . $amount . ">";
                        $fm .= "<input type='hidden' name='out_biz_no' value=" . $out_biz_no . ">";
                        $fm .= "<input type='hidden' name='remark' value='钱袋子余额提现'>";
                        $fm .= "<input type='hidden' name='type' value='purse'>";
                        $fm .= "<script src='__STYLE__/js/jquery-1.9.1.js'></script>";
                        $fm .= "<script>";
                        $fm .= "function sub() {";

                        $fm .= "console.log(document.aaa.submit());";
                        $fm .= "}";
                        $fm .= "setTimeout(sub,1);";
                        $fm .= "</script>";
                        echo $fm;
                    } else {
                        $this->error('提现金额小于账户可用金额');
                    }
                } else {
                    $this->error('提现金额不能小于0.1');
                }
            } else {
                $this->error('请输入你的账号');
            }

        } else {

            return $this->fetch('withdrawals');
        }

    }

    //金豆提现
    public function withdrawals_imazamox()
    {
        //获取当前用户的信息
        $user = \think\Db::name('users')->where(array('userId' => session('WST_USER.userId')))->find();
        if ($_POST) {
           // var_dump($_POST);die;
            if (!empty($_POST['name'])) {
                if ($_POST['im_number'] >= 0.1) {
                    if ($_POST['im_number'] <= $user['userMoney']) {
                        $name = $_POST['name'];
                        $amount = $_POST['im_number'];
                        $out_biz_no = $this->makesn();
//                    var_dump($_POST);die;
//                    $model_withdrawals->add($alipyname,$amount,$out_biz_no);
                        $fm = "<form name='aaa' action='/api/alipay.fund.trans.toaccount.transfer/index.php' method='post'>";
                        $fm .= "<input type='hidden' name='alipyname' value=" . $name . ">";
                        $fm .= "<input type='hidden' name='amount' value=" . $amount . ">";
                        $fm .= "<input type='hidden' name='out_biz_no' value=" . $out_biz_no . ">";
                        $fm .= "<input type='hidden' name='remark' value='钱袋子余额提现'>";
                        $fm .= "<script src='__STYLE__/js/jquery-1.9.1.js'></script>";
                        $fm .= "<script>";
                        $fm .= "function sub() {";

                        $fm .= "console.log(document.aaa.submit());";
                        $fm .= "}";
                        $fm .= "setTimeout(sub,1);";
                        $fm .= "</script>";
                        echo $fm;
                    } else {
                        $this->error('提现金额小于账户可用金额');
                    }
                } else {
                    $this->error('提现金额不能小于0.1');
                }
            } else {
                $this->error('请输入你的账号');
            }

        } else {

            $this->assign('user', $user);
            return $this->fetch('withdrawals_imazamox');
        }
    }

    //提现成功
    public function withdrawals_ok()
    {
        if ($_POST['msg'] == 'Success') {
            //获取当前用户的信息
            $user = \think\Db::name('users')->where(array('userId' => session('WST_USER.userId')))->find();
            if ($_POST['type'] == 'purse') {
                // 启动事务
                \think\Db::startTrans();
                try{
                    //修改用户的余额
                    $users['userMoney'] = $user['userMoney'] - $_POST['amount'];
                    $mem = \think\Db::name('users')->where(['userId' => session('WST_USER.userId')])->update($users);
                    //将数据添加到资金流水
                    $insert['targetType'] = session('WST_USER.userType');
                    $insert['targetId'] = session('WST_USER.userId');
                    $insert['dataId'] = 7;
                    $insert['dataSrc'] = '提现';
                    $insert['moneyType'] = 0;
                    $insert['money'] = $_POST['amount'];
                    $insert['payName'] = $_POST['name'];
                    $insert['tradeNo'] = $_POST['sn'];
                    $insert['payType'] = 1;
                    $insert['createTime'] = date('Y-m-d H:i:s');
                    $insert['remark'] = '钱袋子余额提现';
                    $in = \think\Db::name('log_moneys')->insert($insert);
                    //将数据添加到提现表
                    $data['targetType'] = session('WST_USER.userType');
                    $data['targetId'] = session('WST_USER.userId');
                    $data['money'] = $_POST['amount'];
                    $data['accType'] = 1;
                    $data['accNo'] = $_POST['name'];
                    $data['accUser'] = session('WST_USER.loginName');
                    $data['cashSatus'] = 1;
                    $data['cashRemarks'] = '钱袋子余额提现';
                    $data['cashConfigId'] = session('WST_USER.userId');
                    $data['createTime'] = date('Y-m-d H:i:s');
                    $data['cashNo'] = $_POST['sn'];
                    $da = \think\Db::name('cash_draws')->insert($data);
                    // 提交事务
                    \think\Db::commit();
                } catch (\Exception $e) {
                    // 回滚事务
                    \think\Db::rollback();
                }
            } else {
                // 启动事务
                \think\Db::startTrans();
                try{
                    //修改用户的金豆余额
                    $users['imazamox_number'] = $user['imazamox_number'] - $_POST['amount'];
                    $mem = \think\Db::name('users')->where(['userId' => session('WST_USER.userId')])->update($users);
                    //将数据添加到金豆变更表
                    $insert['user_id'] = session('WST_USER.userId');
                    $insert['imazamox_sn'] = $_POST['sn'];
                    $insert['imazamox_type'] = '提现';
                    $insert['imazamox_number'] = $_POST['amount'];
                    $insert['imazamox_change'] = $_POST['amount'];
                    $insert['payName'] = $_POST['name'];
                    $insert['is_recharge'] = 1;
                    $insert['imazamox_desc'] = '金豆提现';
                    $insert['add_time'] = date('Y-m-d H:i:s');
                    $in = \think\Db::name('imazamox_flow_log')->insert($insert);
                    //将数据添加到金豆提现表
                    $data['imc_sn'] = $_POST['sn'];
                    $data['imc_user_id'] = session('WST_USER.userId');
                    $data['imc_amount'] = $_POST['amount'];
                    $data['imc_bank_name'] = '支付宝';
                    $data['imc_bank_no'] = $_POST['name'];
                    $data['imc_bank_user'] = session('WST_USER.loginName');
                    $data['imc_add_time'] = date('Y-m-d H:i:s');
                    $data['imc_payment_time'] = date('Y-m-d H:i:s');
                    $data['imc_payment_state'] = 1;
                    $da = \think\Db::name('im_cash')->insert($data);
                    //将数据添加到资金流水
                    $ins['targetType'] = session('WST_USER.userType');
                    $ins['targetId'] = session('WST_USER.userId');
                    $ins['dataId'] = 11;
                    $ins['dataSrc'] = '提现';
                    $ins['moneyType'] = 0;
                    $ins['money'] = $_POST['amount'];
                    $ins['payName'] = $_POST['name'];
                    $ins['tradeNo'] = $_POST['sn'];
                    $ins['payType'] = 1;
                    $ins['createTime'] = date('Y-m-d H:i:s');
                    $ins['remark'] = '金豆提现';
                    $inse = \think\Db::name('log_moneys')->insert($ins);
                    // 提交事务
                    \think\Db::commit();
                } catch (\Exception $e) {
                    // 回滚事务
                    \think\Db::rollback();
                }
            }
            if ($in && $da && $mem) {
                $this->success('提现成功', 'consume/index');
            } else {
                $this->error('提现失败', 'consume/index');
            }
        } else {
            $this->error($_POST['sub_msg'], 'consume/index');
        }
    }

    //转账到钱袋
    public function transfer_purse()
    {
        if ($_POST) {
//            dump($_POST);die;
            //获取当前用户的信息
            $user = \think\Db::name('users')->where(array('userId' => session('WST_USER.userId')))->find();

            if($_POST['member_name'] != session('WST_USER.loginName')) {
                if (is_numeric($_POST['amount']) && $_POST['amount'] > 0) {
                    if ($user['userMoney'] >= $_POST['amount']) {
                        //获取收款人的信息
                        $users = \think\Db::name('users')->where(array('loginName' => $_POST['member_name']))->find();
                        if ($users) {
                            //修改用户的余额
                            $me = \think\Db::name('users')->where(array('userId' => session('WST_USER.userId')))->update(['userMoney' => $user['userMoney'] - $_POST['amount']]);
                            //修改收款人的余额
                            $mem = \think\Db::name('users')->where(array('loginName' => $_POST['member_name']))->update(['userMoney' => $users['userMoney'] + $_POST['amount']]);
                            $tradeNo = $this->makeSn();
                            if ($me && $mem) {
                                // 启动事务
                                \think\Db::startTrans();
                                try{
                                    if ($_POST['is_get'] != '') {
                                        //获取信息表中的信息
                                        $news = \think\Db::name('news')->where(['id' => $_POST['is_get']])->find();
                                        //将信息标为已完成
                                        \think\Db::name('news')->where(['id' => $_POST['is_get']])->update(['is_complete' => 1,'complete_time'=>date('Y-m-d H:i:s')]);
                                        //将资金流水的状态改为完成
                                        \think\Db::name('log_moneys')->where(['tradeNo' => $news['tradeNo']])->update(['dataFlag' => 1]);
                                    } else {
                                        //将转账消息添加到资金流水
                                        $data = array('targetType' => session('WST_USER.userType'), 'targetId' => session('WST_USER.userId'), 'dataId' => 4, 'dataSrc' => '转账', 'moneyType' => 0, 'money' => $_POST['amount'], 'payName' => $_POST['member_name'],'tradeNo' => $tradeNo, 'payType' => 0, 'dataFlag' => 1, 'createTime' => date('Y-m-d H:i:s'), 'remark' => $_POST['desc']);
                                        $data1 = array('targetType' => $users['userType'], 'targetId' => $users['userId'], 'dataId' => 5, 'dataSrc' => '转账', 'moneyType' => 1, 'money' => $_POST['amount'], 'payName' => session('WST_USER.loginName'),'tradeNo' => $tradeNo, 'payType' => 0, 'dataFlag' => 1, 'createTime' => date('Y-m-d H:i:s'), 'remark' => $_POST['desc']);
                                        \think\Db::name('log_moneys')->insert($data);
                                        \think\Db::name('log_moneys')->insert($data1);
                                    }
                                    // 提交事务
                                    \think\Db::commit();
                                } catch (\Exception $e) {
                                    // 回滚事务
                                    \think\Db::rollback();
                                }
                                return WSTReturn('转账成功！', 1);
                            } else {
                                return WSTReturn('转账失败', 2);
                            }
                        } else {
                            return WSTReturn('用户名错误！', 2);
                        }

                    } else {
                        return WSTReturn('您的账户余额不足！', 2);
                    }
                } else {
                    return WSTReturn('请输入正确的金额！', 2);
                }
            } else {
                return WSTReturn('不能转账给自己！', 2);
            }
        } else {
            if ($_GET) {
                //获取表的数据
                $news = \think\Db::name('news')->where(array('id' => $_GET['id']))->find();
                //获取收款人的信息
                $user = \think\Db::name('users')->where(array('userId' => $news['sender_id']))->find();
                $this->assign('news', $news);
                $this->assign('user', $user);
                return $this->fetch('transfer_purse');
            } else {
                return $this->fetch('transfer_purse');
            }
        }

    }

    //转账到银行卡
    public function transfer_bank()
    {
        return $this->fetch('transfer_bank');
    }

    //我要收款
    public function gathering()
    {

            return $this->fetch('gathering');

    }



    //金豆数量验证
    public function balance()
    {
        // echo $_POST['imazamox_number'];
        $ajax = \think\Db::name('users')->where(array('userId' => session('WST_USER.userId')))->find();
        $aj = $ajax['imazamox_number'];

        if ($aj >= $_POST['imazamox_number']) {
            echo 'success';
        } else {
            echo 'errotr';
        }
        // var_dump($ajax);
    }

    //验证收款人账户是否存在
    public function Payee(){
        if($_POST['loginName']==''){
            echo 'success';
        }else{
            $name = $_POST['loginName'];
            $Payee = \think\Db::name('users')->where('loginName',$name)->find();
            //判断是否存在loginName 不存在去查询userName
            // echo $Payee['userPhoto'];
            if($Payee){
                $val=[];
                $val['userName'] = $Payee['userName'];
                $val['userPhoto'] = $Payee['userPhoto'];
                $val['userPhone'] = $Payee['userPhone'];
                // $vals = json_encode($val);
                return $val;
            }else{
                echo 'error';
            }
        }
    }

    //验证收款人是否存在users表. userName loginName 
    public function account(){
        if($_POST['loginName']==''){
            echo 'success';
        }else{
            $name = $_POST['loginName'];
            $Payee = \think\Db::name('users')->where('loginName|userPhone',$name)->find();
            // $sql = "select * from `wst_users` where(`userName`='{$name}' or `loginName`='{$name}')";
            // $Payee = \think\Db::query($sql);
            // var_dump($Payee);
            if($Payee){
                $val=[];
                $val['userName'] = $Payee['userName'];
                $val['userPhoto'] = $Payee['userPhoto'];
                $val['userPhone'] = $Payee['userPhone'];
                return $val;
            }else{
                echo 'error';
            }
        }
    }

    public function Payment()
    {

        $Payment = \think\Db::name('users')->where(array('userId' => session('WST_USER.userId')))->find();

        if ($Payment['userMoney'] >= $_POST['userMoney']) {
            echo 'success';
        } else {
            echo 'error';
        }

    }

    public function Other()
    {
        $Other = \think\Db::name('users')->where('loginName', "{$_POST['loginName']}")->find();
        if ($Other) {
            echo 'success';
        } else {
            echo 'error';
        }
    }

    public function With()
    {
        $with = \think\Db::name('users')->where('userId', session('WST_USER.userId'))->find();

        if ($with['userMoney'] >= $_POST['userMoney']) {
            echo 'success';
        } else {
            echo 'error';
        }
    }

    public function Verification()
    {
        // var_dump($_POST);
        $var = \think\Db::name('users')->where('loginName', "{$_POST['loginName']}")->find();

        if ($var['userPhone'] == $_POST['userPhone']) {
            echo 'success';
        } else {
            echo 'error';
        }
    }

    //剩余还款
    public function Loan()
    {
        $loan = \think\Db::name('users')->where('userId', session('WST_USER.userId'))->find();

        if ($loan['residual_repayment'] >= $_POST['Money']) {
            echo 'success';
        } else {
            echo 'error';
        }
    }

    //我要收款
    public function News()
    {
        if($_POST['user_name'] != session('WST_USER.loginName') && $_POST['user_name'] != session('WST_USER.userPhone')) {
            if (is_numeric($_POST['news_amount']) && $_POST['news_amount'] >= 0.01) {
                //获取转账用户的信息
                $user = \think\Db::name('users')->where('loginName|userPhone',$_POST['user_name'])->find();
                if($user) {
                    $tradeNo = $this->makeSn();
                    // 启动事务
                    \think\Db::startTrans();
                    try{
                        $data['user_name'] = $_POST['user_name'];
                        // $data['nickname'] = $user['userName'];
                        $data['news_content'] = '用户' . session('WST_USER.loginName') . '向你收款' . $_POST['news_amount'] . '元，说明：' . $_POST['news_desc'];
                        $data['news_title'] = '收款';
                        $data['news_add_time'] = date('Y-m-d H:i:s');
                        $data['news_amount'] = $_POST['news_amount'];
                        $data['sender_id'] = session('WST_USER.userId');
                        $data['tradeNo'] = $tradeNo;
                        $data['news_desc'] = $_POST['news_desc'];
                        $news = \think\Db::name('news')->insert($data);
                        // 提交事务
                        \think\Db::commit();
                    } catch (\Exception $e) {
                        // 回滚事务
                        \think\Db::rollback();
                    }
                    if ($news) {
                        // 启动事务
                        \think\Db::startTrans();
                        try{
                        //将信息添加到资金流水表
                        $data1 = array('targetType' => $user['userType'], 'targetId' => $user['userId'], 'dataId' => 4, 'dataSrc' => '转账', 'moneyType' => 0, 'money' => $_POST['news_amount'], 'payName' => session('WST_USER.loginName'),'tradeNo' => $tradeNo, 'payType' => 0, 'dataFlag' => 2, 'createTime' => date('Y-m-d H:i:s'), 'remark' => $_POST['news_desc']);
                        \think\Db::name('log_moneys')->insert($data1);
                        //将信息添加到资金流水表
                        $in = array('targetType' => session('WST_USER.userType'), 'targetId' => session('WST_USER.userId'), 'dataId' => 5, 'dataSrc' => '转账', 'moneyType' => 1, 'money' => $_POST['news_amount'], 'payName' => $_POST['user_name'],'tradeNo' => $tradeNo, 'payType' => 0, 'dataFlag' => 2, 'createTime' => date('Y-m-d H:i:s'), 'remark' => $_POST['news_desc']);
                        \think\Db::name('log_moneys')->insert($in);
                        // 提交事务
                        \think\Db::commit();
                    } catch (\Exception $e) {
                        // 回滚事务
                        \think\Db::rollback();
                    }
                        return WSTReturn('信息发布成功', 1);
                    } else {
                        return WSTReturn('信息发布失败', 2);
                    }
                } else {
                    return WSTReturn('请输入正确的用户名', 2);
                }
            } else {
                return WSTReturn('请输入正确的金额', 2);
            }
        } else {
            return WSTReturn('不能向自己收款', 2);
        }
    }
}