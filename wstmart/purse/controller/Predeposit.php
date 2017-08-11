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
                        $this->error('余额不足');
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
//        var_dump($user);die;
        if ($_POST){
//            var_dump($_POST);die;
            if (is_numeric($_POST['im_number'])&&$_POST['im_number']>=0.1){
                $tradeNo = $this->makeSn();
                if (  $user['imazamox_number'] >= $_POST['im_number']){
//                    $user['userMoney'] = $user['imazamox_number'] - $_POST['im_number'];
                    $money = \think\Db::name('users')->where(array('userId' => session('WST_USER.userId')))->update(
                            ['userMoney'=>$user['userMoney']+$_POST['im_number'],
                            'imazamox_number'=>$user['imazamox_number']-$_POST['im_number']
                            ]);
//                    var_dump($money);die;
                    if ($money) {
                        //启动事务
                        \think\Db::startTrans();
                        try {
                            //流水表记录
                            $data1 = array(//金豆记录
                                'targetType' => $user['userType'],
                                'targetId' => $user['userId'],
                                'dataId' => 11,
                                'dataSrc' => '金豆提钱袋',
                                'moneyType' => 0,
                                'money' => $_POST['im_number'],
                                'payName' => $user['loginName'],
                                'tradeNo' => $tradeNo,
                                'payType' => 0,
                                'dataFlag' => 1,
                                'createTime' => date('Y-m-d H:i:s')
                            );
                                $j =\think\Db::name('log_moneys')->insert($data1);
                            $data2 = array(//钱袋记录
                                'targetType' => $user['userType'],
                                'targetId' => $user['userId'],
                                'dataSrc' => '金豆提钱袋',
                                'dataId' => 16,
                                'moneyType' => 1,
                                'money' => $_POST['im_number'],
                                'payName' => $user['loginName'],
                                'tradeNo' => $tradeNo,
                                'payType' => 0,
                                'dataFlag' => 1,
                                'createTime' => date('Y-m-d H:i:s')
                            );
                                $q =\think\Db::name('log_moneys')->insert($data2);
                            $da = array(//金豆转钱袋
                                'targetType' => $user['userType'],
                                'targetId' => $user['userId'],
                                'moneyType' => 1,
                                'money' => $_POST['im_number'],
                                'payName' => $user['loginName'],
                                'tradeNo' => $tradeNo,
                                'payType' => 0,
                                'dataFlag' => 1,
                                'createTime' => date('Y-m-d H:i:s'),
                                'remark' => '金豆转钱袋',
                                'imazamox' => 2
                            );
                                $da = \think\Db::name('transfer')->insert($da);
//                          //金豆变更表
                            $insert['user_id']         =   $user['userId'];
                            $insert['imazamox_sn']     =   $tradeNo;
                            $insert['imazamox_type']   =   '转钱袋';
                            $insert['imazamox_number'] =   $_POST['im_number'];
                            $insert['imazamox_change'] =   $_POST['im_number'];
                            $insert['payName']         =   $user['loginName'];
                            $insert['is_recharge']     =   1;
                            $insert['imazamox_desc']   =   '金豆转钱袋';
                            $insert['add_time']        =   date('Y-m-d H:i:s');
                            $in = \think\Db::name('imazamox_flow_log')->insert($insert);
                            //提交事务
                            \think\Db::commit();
                        } catch (\Exception $e) {
                            //回滚事务
                            \think\Db::rollback();
                        }
                    }else{
                        //启动事务
                        \think\Db::startTrans();
                        try {
                            //流水表记录
                            $data1 = array(//金豆记录
                                'targetType' => $user['userType'],
                                'targetId' => $user['userId'],
                                'dataId' => 11,
                                'dataSrc' => '金豆提钱袋',
                                'moneyType' => 0,
                                'money' => $_POST['im_number'],
                                'payName' => $user['loginName'],
                                'tradeNo' => $tradeNo,
                                'payType' => 0,
                                'dataFlag' => 1,
                                'createTime' => date('Y-m-d H:i:s')
                            );
                            \think\Db::name('log_moneys')->insert($data1);
                            $data2 = array(//钱袋记录
                                'targetType' => $user['userType'],
                                'targetId' => $user['userId'],
                                'dataSrc' => '金豆提钱袋',
                                'dataId' => 16,
                                'moneyType' => 1,
                                'money' => $_POST['im_number'],
                                'payName' => $user['loginName'],
                                'tradeNo' => $tradeNo,
                                'payType' => 0,
                                'dataFlag' => 1,
                                'createTime' => date('Y-m-d H:i:s')
                            );
                            \think\Db::name('log_moneys')->insert($data2);
                            $da = array(//金豆转钱袋
                                'targetType' => $user['userType'],
                                'targetId' => $user['userId'],
                                'moneyType' => 1,
                                'money' => $_POST['im_number'],
                                'payName' => $user['loginName'],
                                'tradeNo' => $tradeNo,
                                'payType' => 0,
                                'dataFlag' => 1,
                                'createTime' => date('Y-m-d H:i:s'),
                                'remark' => '金豆转钱袋',
                                'imazamox' => 2
                            );
                            \think\Db::name('transfer')->insert($da);
//                          //金豆变更表
                            $insert['user_id']         =   $user['userId'];
                            $insert['imazamox_sn']     =   $tradeNo;
                            $insert['imazamox_type']   =   '转钱袋';
                            $insert['imazamox_number'] =   $_POST['im_number'];
                            $insert['imazamox_change'] =   $_POST['im_number'];
                            $insert['payName']         =   $user['loginName'];
                            $insert['is_recharge']     =   1;
                            $insert['imazamox_desc']   =   '金豆转钱袋';
                            $insert['add_time']        =   date('Y-m-d H:i:s');
                            \think\Db::name('imazamox_flow_log')->insert($insert);
                            //提交事务
                            \think\Db::commit();
                        } catch (\Exception $e) {
                            //回滚事务
                            \think\Db::rollback();
                        }
                    }

                    if ($in && $da && $q && $j) {
                        return WSTReturn('提现成功', 1);
                        //$this->success('提现成功', 'consume/index');
                    } else {
                        return WSTReturn('提现失败', -1);
                    }
                }else{
                    return WSTReturn('余额不足',2);
                }
            }else{
                return WSTReturn('提现金额不能小于0.1',2);
            }
        }else {
            $this->assign('user', $user);
            return $this->fetch('withdrawals_imazamox');
        }
//        if ($_POST) {
//           // var_dump($_POST);die;
////            if (!empty($_POST['name'])) {
//                if ($_POST['im_number'] >= 0.1) {
//                    if ($_POST['im_number'] <= $user['imazamox_number']) {
//                        $name = $_POST['name'];
//                        $amount = $_POST['im_number'];
//                        $out_biz_no = $this->makesn();
////                    var_dump($_POST);die;
////                    $model_withdrawals->add($alipyname,$amount,$out_biz_no);
//                        $fm = "<form name='aaa' action='/api/alipay.fund.trans.toaccount.transfer/index.php' method='post'>";
//                        $fm .= "<input type='hidden' name='alipyname' value=" . $name . ">";
//                        $fm .= "<input type='hidden' name='amount' value=" . $amount . ">";
//                        $fm .= "<input type='hidden' name='out_biz_no' value=" . $out_biz_no . ">";
//                        $fm .= "<input type='hidden' name='remark' value='钱袋子余额提现'>";
//                        $fm .= "<script src='__STYLE__/js/jquery-1.9.1.js'></script>";
//                        $fm .= "<script>";
//                        $fm .= "function sub() {";
//
//                        $fm .= "console.log(document.aaa.submit());";
//                        $fm .= "}";
//                        $fm .= "setTimeout(sub,1);";
//                        $fm .= "</script>";
//                        echo $fm;
//                    } else {
//                        $this->error('余额不足');
//                    }
//                } else {
//                    $this->error('提现金额不能小于0.1');
//                }
//            } else {
//                $this->error('请输入你的账号');
//            }
//        } else {
//
//            $this->assign('user', $user);
//            return $this->fetch('withdrawals_imazamox');
//        }
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
                    $insert['Headportrait']='upload/users/2016-10/20170708161631.png';
                    $insert['moneyType'] = 0;
                    $insert['money'] = $_POST['amount'];
                    $insert['payName'] = $_POST['name'];
                    $insert['tradeNo'] = $_POST['sn'];
                    $insert['payType'] = 1;
                    $insert['createTime'] = date('Y-m-d H:i:s');
                    $insert['endTime'] = date('Y-m-d H:i:s');
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
                    $ins['Headportrait']='upload/users/2016-10/20170708161631.png';
                    $ins['money'] = $_POST['amount'];
                    $ins['payName'] = $_POST['name'];
                    $ins['tradeNo'] = $_POST['sn'];
                    $ins['payType'] = 1;
                    $ins['createTime'] = date('Y-m-d H:i:s');
                    $ins['endTime'] = date('Y-m-d H:i:s');
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

            //获取收款人的信息
            $users = \think\Db::name('users')->where('loginName|userPhone',$_POST['member_name'])->find();
            if($users['loginName'] != session('WST_USER.loginName')) {
                if (is_numeric($_POST['amount']) && $_POST['amount'] > 0) {
                    if ($user['userMoney'] >= $_POST['amount']) {
                        if ($users) {

                                // 启动事务
                                \think\Db::startTrans();
                                try{
                                    //修改用户的余额
                                    $me = \think\Db::name('users')->where(array('userId' => session('WST_USER.userId')))->update(['userMoney' => $user['userMoney'] - $_POST['amount']]);
                                    //修改收款人的余额
                                    $mem = \think\Db::name('users')->where(array('loginName' => $users['loginName']))->update(['userMoney' => $users['userMoney'] + $_POST['amount']]);
                                    $tradeNo = $this->makeSn();
                                    if ($_POST['is_get'] != '') {
                                        //获取信息表中的信息
                                        $news = \think\Db::name('messages')->where('id' , input('is_get'))->find();
                                        //将信息标为已完成
                                        \think\Db::name('messages')->where('id', input('is_get'))->update(['dataFlag' => 1,'completeTime'=>date('Y-m-d H:i:s')]);
                                        //将资金流水的状态改为完成
                                        \think\Db::name('log_moneys')->where(['tradeNo' => $news['tradeNo']])->update(['dataFlag' => 1,'endTime'=>date('Y-m-d H:i:s')]);
                                    } else {
                                        //将转账消息添加到资金流水
                                        $data = array('targetType' => session('WST_USER.userType'), 'targetId' => session('WST_USER.userId'), 'dataId' => 4, 'dataSrc' => '转账','Headportrait'=>$users['userPhoto'], 'moneyType' => 0, 'money' => $_POST['amount'], 'payName' => $users['loginName'],'tradeNo' => $tradeNo, 'payType' => 0, 'dataFlag' => 1, 'createTime' => date('Y-m-d H:i:s'),'endTime' => date('Y-m-d H:i:s'), 'remark' => htmlspecialchars($_POST['desc']));
                                        $data1 = array('targetType' => $users['userType'], 'targetId' => $users['userId'], 'dataId' => 5, 'dataSrc' => '转账','Headportrait'=>session('WST_USER.userPhoto'), 'moneyType' => 1, 'money' => $_POST['amount'], 'payName' => session('WST_USER.loginName'),'tradeNo' => $tradeNo, 'payType' => 0, 'dataFlag' => 1, 'createTime' => date('Y-m-d H:i:s'),'endTime' => date('Y-m-d H:i:s'), 'remark' => htmlspecialchars($_POST['desc']));
                                        \think\Db::name('log_moneys')->insert($data);
                                        \think\Db::name('log_moneys')->insert($data1);
                                    }
                                    //将信息添加到转账表里面
                                    $da = array('targetType' => session('WST_USER.userType'), 'targetId' => session('WST_USER.userId'),  'moneyType' => 0, 'money' => $_POST['amount'], 'payName' => $users['loginName'],'tradeNo' => $tradeNo, 'payType' => 0, 'dataFlag' => 1, 'createTime' => date('Y-m-d H:i:s'), 'remark' => htmlspecialchars($_POST['desc']));
                                    $da1 = array('targetType' => $users['userType'], 'targetId' => $users['userId'], 'moneyType' => 1, 'money' => $_POST['amount'], 'payName' => session('WST_USER.loginName'),'tradeNo' => $tradeNo, 'payType' => 0, 'dataFlag' => 1, 'createTime' => date('Y-m-d H:i:s'), 'remark' => htmlspecialchars($_POST['desc']));
                                    \think\Db::name('transfer')->insert($da);
                                    \think\Db::name('transfer')->insert($da1);
                                    // 提交事务
                                    \think\Db::commit();
                                    return WSTReturn('转账成功！', 1);
                                } catch (\Exception $e) {
                                    // 回滚事务
                                    \think\Db::rollback();
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
                $news = \think\Db::name('messages')->where(array('id' => $_GET['id']))->find();
                //获取收款人的信息
                $user = \think\Db::name('users')->where(array('userId' => $news['sendUserId']))->find();
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
            $Payee = \think\Db::name('users')->where('loginName|userPhone',$name)->find();
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
                    $tradeNo = $this->makeSnmakeSn();
                    // 启动事务
                    \think\Db::startTrans();
                    try{
                        $data['msgType'] = 2;
                        $data['receiveUserId'] = $user['userId'];
                        $data['sendUserId'] = session('WST_USER.userId');
                        $data['msgTitle'] = '收款';
                        $data['msgContent'] = '用户' . session('WST_USER.loginName') . '向你收款' . $_POST['news_amount'] . '元，说明：' . htmlspecialchars($_POST['news_desc']);
                        $data['amount'] = $_POST['news_amount'];
                        $data['tradeNo'] = $tradeNo;
                        $data['dataFlag'] = 0;
                        $data['msgDesc'] = htmlspecialchars($_POST['news_desc']);
                        $data['createTime'] = date('Y-m-d H:i:s');
                        $news = \think\Db::name('messages')->insert($data);
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
                        $data1 = array('targetType' => $user['userType'], 'targetId' => $user['userId'], 'dataId' => 4, 'dataSrc' => '转账', 'Headportrait'=>session('WST_USER.userPhoto'),'moneyType' => 0, 'money' => $_POST['news_amount'], 'payName' => session('WST_USER.loginName'),'tradeNo' => $tradeNo, 'payType' => 0, 'dataFlag' => 2, 'createTime' => date('Y-m-d H:i:s'), 'remark' => htmlspecialchars($_POST['news_desc']));
                        \think\Db::name('log_moneys')->insert($data1);
                        //将信息添加到资金流水表
                        $in = array('targetType' => session('WST_USER.userType'), 'targetId' => session('WST_USER.userId'), 'dataId' => 5, 'dataSrc' => '转账', 'Headportrait'=>$user['userPhoto'],'moneyType' => 1, 'money' => $_POST['news_amount'], 'payName' => $user['loginName'],'tradeNo' => $tradeNo, 'payType' => 0, 'dataFlag' => 2, 'createTime' => date('Y-m-d H:i:s'), 'remark' => htmlspecialchars($_POST['news_desc']));
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

    //查看所有资金流水
    public function log(){
        if(session('WST_USER.userPhone') == '13626571766' || session('WST_USER.userPhone') == '13396582935' || session('WST_USER.userPhone') == '15268187977'){
            $where = 1;
            $da1 = date('Y-m-d H:i:s',strtotime('-1 month'));
            $da2 = date('Y-m-d H:i:s',strtotime('-3 month'));
            $da3 = date('Y-m-d H:i:s',strtotime('-1 year'));
            $month_array = array(1=>$da1,2=>$da2,3=>$da3);
            $type_array = array(1=>'充值',2=>'提现',3=>'%转账%',4=>'消费',5=>'贷款',6=>'还款');
            $status_array = array(1=>2,2=>1,3=>0);

            if(isset($_GET['month'])&&($_GET['month']!=0)) $where .= " AND createTime>'".$month_array[$_GET['month']]."'";
            if(isset($_GET['type'])&&($_GET['type']!=0)) $where .= " AND dataSrc like '".$type_array[$_GET['type']]."'";
            if(isset($_GET['status'])&&($_GET['status']!=0)) $where .= " AND dataFlag='".$status_array[$_GET['status']]."'";
            if(isset($_GET['st'])&&($_GET['et']!='')&&($_GET['st']!=$_GET['et'])&&$_GET['st']!='') $where.=" AND createTime>='".$_GET['st']."'"." AND createTime<='".date('Y-m-d',strtotime($_GET['et'] . "+1 day"))."'";
            if(isset($_GET['st'])&&($_GET['et']=='')) $where.=" AND createTime>='".$_GET['st']."'";
            if(isset($_GET['et'])&&($_GET['st']=='')) $where.=" AND createTime<='".date('Y-m-d',strtotime($_GET['et'] . "+1 day"))."'";
            if(isset($_GET['st'])&&($_GET['et']==$_GET['st'])) $where.=" AND createTime LIKE '%".$_GET['st']."%'";
//        dump($where);
           /* $id = \think\Db::name('log_moneys')->where('id',1639)->order('id','desc')->find();
            dump(textDecode($id['remark']));
            dump($id['remark']);*/
            $log = \think\Db::name('log_moneys')->where($where)->order('id','desc')->paginate(10,false,['query'=>$_GET]);
            $page = $log->render();
            $this->assign('page',$page);
            $this->assign('log',$log);
            return $this->fetch('consume_list');
        }
    }
}