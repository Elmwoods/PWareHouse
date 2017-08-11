<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/10 0010
 * Time: 下午 03:49
 */
namespace  wstmart\withdrawals\Controller;
use wstmart\common\model\Sdk as S;
use think\Controller;

class Index extends Controller
{
    //token 身份验证
    public function token($id,$token) {
            $to = \think\Db::name('user_token')->where('member_id',(int)$id)->order('token_id desc')->find();
            if($to['token']==$token){
                return 'success';
            }else{
                return 'error';
            }
    }

    public function yas(){
            $s = new S();
        if(!empty($_POST) && $_POST['id'] != null && $_POST['token'] != null){
            if ($this->token($_POST['id'],$_POST['token'])==='success') {

                $user = \think\Db::name('users')->where('userId',(int)$_POST['id'])->find();

                if($_POST['number']<=$user['userMoney']){
                    require_once 'AopSdk.php';
                    $aop = new \AopClient ();
                    $aop->appId = '2017011705151664';
                    $aop->rsaPrivateKey = 'MIIEpQIBAAKCAQEAzknOfQFizD/6B1RkNcR3xQr5o8HtP74Lg5CaJqvXjEh2QRILvEhSHWhMxyJCLW38mmhmdQQ6l7TIRE1tkgU3pmcYgYmi593RuJ4V49NLRT4KTQNcU2q+bp/MvnbqYrOWoxVoIzYSnLHnVK0ymwHUmhhcg1PBdI5xzyk21pUbchTdFQ/VmqbnOghgOmLfe2PbJogfLfvu8/d8f1os70gRBYSGJzg5jIUdABCdRc5x+CikNbkDBxRJEI1xWdozLEceckyyGwSzN5otvJBm+HGTEd5Cf3SmF7zzvEi/H/2tN9tX7QE/KGjZlUxTNYZuGYJIJYa8iyJUh8tPfeE1MXaoVQIDAQABAoIBAQCMGJvfUX2jcR+AstOLoG4mp5l6mU1iqNJw+1d1Q+cTInMNJhBKQmNiDV93LdD7wWJ4CsbqWYDhXqlTmbH8JQbyP7no32x/Q6oWU2ZSX0ETOVsNima9UBUcU/Jct63eclCvWO5sW2CwgjG01Bs2IjwcmsbZeZw8aDDqm/beLE2DX2fOPPZrSqy4zRhaFQxUbMdYHX9/TQ//K201rS0m1GvcbFO/CngzvKc+xVleeQc+8CBpAMhsENB6SUMVYlIJaq3nvG0aXnwSH7Kmpj0bqIw+K997hfQMsKANSZOaBaHL5Dv2MalI4/qMs2VJUkgRr6yks5qoMIfaIpgQdZjvBmeBAoGBAOg/1CcweFF9ThXxzXLbbf/1mCJ3lHPR31XEiipn0NbG8R+dXIqEt66R0jgrzsIf6S6fOGpPDIOQGURCfTwR8IGSrnnDqR/f3JWYolfRymFXVunvGNz7TF60xvyU+2PC6jXHDr2dM7uJg6XTml2ZE0T2pMnTsfGEHExKnZoi9zm5AoGBAONiVM0gtslv62dYonSZjtrRYlWtJvM6maf0SyaO6TZUEDaMoRbHoXh88Rz/kqV3/WL93cpFcqjLSTDlLTSxxFZpcK8oJkbuwrKJ+/rZCmbEca/Pb40X50tlRkIqawQsdTfOlHqggTpjCeQW2jnYe7jScEv2WFgX/B6hUgvPk8F9AoGAKe4cJ1cg4dV1m5CkPvBO079LUC22p5Jkd9+b8jv8AEq6jbKjWn7LisDY6zs9gN6yArDMRqUu5THG3gQDCC1U9o+84E7q4c5QzNFZvfEqUJisIGACZSMZjp+krUVYfZJbJophpuoSxPD2y6GAZRWV3QWKisWlgq0PTtbJzWIysEECgYEAk1k0RN1PNggdzxHD7LVZunj3NTgIxpOR4SHQ1ULE49zjyMWm2iExhOfKQ5VmjW3NOKn0YNBSNgnN+y539e7AoZKgYBEvhMXSS2pZbLvbHq9sUJam3hLAYr5VIilkwgahSzHGTBTYyWJGlZUtg1DDFAjilocjxqp8SckWZurz/+0CgYEAxDlomIksx8Cg6H/aEXgfoPLgk7ISbXXd+HWaB/08m6Zb+AxOYDKl0sNCO0ObTfsJhr7rJetrXvki/Jm6BgCzV4vnJiEGsqUqvbA5b+pcfKVGkEqVRfKWtqNgV724ShZwrs3PfFl+G7AeArjbCNShVq4uP+rbu1uGtYqRbS3wG3U=';
                    $aop->alipayrsaPublicKey='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuTjQgKPC980aHOPkbJQBAmbGHLwuJ4K3YPStcq1Nvc22plEfy3f5CZ7N19DhQeY+Hu/oNJJrg7KezpeXn8vkyltdgepXNWUl5xWvSezEw1SGY3uO0GpvpeXloEB4w6rQF8k1jP0aUp1eAmHdEKp3UpABaGhhsCzWYc+EG3TSH2284SNJqY6LswND/S9gOMvahIfIlH618T8QmjBJPGnhNXdsqIgLqqHpxJHypVFYc2HYL9TWZ/wUDN4ueoKuptitSBFT2KzZ6/TuK9JZGB9J1jmr1sYTboDh8ZqOeMKaJAYbO9x8eZq16A5HXg7oIPvPeDgQcP3HmGRZlYCbf7jZ1QIDAQAB';
                    $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
                    $aop->apiVersion = '1.0';
                    $aop->signType = 'RSA2';
                    $aop->postCharset='utf-8';
                    $aop->format='json';
                    $make= $this->makeSn();
            //var_dump($_POST);die;
                    $request = new \AlipayFundTransToaccountTransferRequest ();
                    $request->setBizContent("{" .
                        "\"out_biz_no\":\"".$make."\"," .
                        "\"payee_type\":\"ALIPAY_LOGONID\"," .
                        "\"payee_account\":\"".$_POST['name']."\"," .
                        "\"amount\":\"".$_POST['number']."\"," .
                        "\"remark\":\"".'钱袋提现'."\"" .
                        "}");
                    $result = $aop->execute($request);


                    // var_dump($result);
                    if($result->alipay_fund_trans_toaccount_transfer_response->msg == 'Success'){
                        $user = \think\Db::name('users')->where('userId',(int)$_POST['id'])->find();
                            //修改用户的余额
                            $users['userMoney'] = $user['userMoney'] - $_POST['number'];
                            $mem = \think\Db::name('users')->where(['userId' => (int)$_POST['id']])->update($users);
                            //将数据添加到资金流水
                            $insert['targetType'] = $user['userType'];
                            $insert['targetId'] = $user['userId'];
                            $insert['dataId'] = 7;
                            $insert['dataSrc'] = '提现';
                            $insert['moneyType'] = 0;
                            $insert['money'] = $_POST['number'];
                            $insert['payName'] = $_POST['name'];
                            $insert['tradeNo'] = $make;
                            $insert['payType'] = 1;
                            $insert['Headportrait']  =   htmlspecialchars('upload/users/2016-10/20170708161631.png');
                            $insert['createTime'] = date('Y-m-d H:i:s');
                            $insert['remark'] = '钱袋子余额提现';
                            $in = \think\Db::name('log_moneys')->insert($insert);
                            //将数据添加到提现表
                            $data['targetType'] = $user['userType'];
                            $data['targetId'] = $user['userId'];
                            $data['money'] = $_POST['number'];
                            $data['accType'] = 1;
                            $data['accNo'] = $_POST['name'];
                            $data['accUser'] = $user['loginName'];
                            $data['cashSatus'] = 1;
                            $data['cashRemarks'] = '钱袋子余额提现';
                            $data['cashConfigId'] = $user['userId'];
                            $data['createTime'] = date('Y-m-d H:i:s');
                            $data['cashNo'] = $make;
                            $da = \think\Db::name('cash_draws')->insert($data);
                            return json_encode(array('resultcode'=>$s->Withdrawals_success(),'info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    }else{

                        //将数据添加到资金流水//失败数据
                        $insert['targetType'] = $user['userType'];
                        $insert['targetId'] = $user['userId'];
                        $insert['dataId'] = 7;
                        $insert['dataSrc'] = '提现';
                        $insert['moneyType'] = 0;
                        $insert['dataFlag']=0;
                        $insert['money'] = $_POST['number'];
                        $insert['payName'] = $_POST['name'];
                        $insert['tradeNo'] = $make;
                        $insert['payType'] = 1;
                        $insert['Headportrait']  =   htmlspecialchars('upload/users/2016-10/20170708161631.png');
                        $insert['createTime'] = date('Y-m-d H:i:s');
                        $insert['remark'] = '钱袋子余额提现';
                        $in = \think\Db::name('log_moneys')->insert($insert);
                        return json_encode(array('resultcode'=>$s->Withdrawals_error(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

                        // echo $result->alipay_fund_trans_toaccount_transfer_response->sub_msg;
                    }
                }else{
                        //将数据添加到资金流水//失败数据
                        $insert['targetType'] = $user['userType'];
                        $insert['targetId'] = $user['userId'];
                        $insert['dataId'] = 7;
                        $insert['dataSrc'] = '提现';
                        $insert['moneyType'] = 0;
                        $insert['dataFlag']=0;
                        $insert['money'] = $_POST['number'];
                        $insert['payName'] = $_POST['name'];
                        $insert['tradeNo'] = $make;
                        $insert['payType'] = 1;
                        $insert['Headportrait']  =   htmlspecialchars('upload/users/2016-10/20170708161631.png');
                        $insert['createTime'] = date('Y-m-d H:i:s');
                        $insert['remark'] = '钱袋子余额提现';
                        $in = \think\Db::name('log_moneys')->insert($insert);
                        return json_encode(array('resultcode'=>$s->transfer_purse_monery(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
            }else{
                return json_encode(array('resultcode'=>$s->token_ne(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);     
            }
        }else{
            return json_encode(array('resultcode'=>$s->token_id(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

        }
    }

        //流水号生成
    public function makeSn()
    {
        return date('YmdHis')
        . sprintf('%03d', (float)microtime() * 1000)
        . session('WST_USER.userId');

    }

}