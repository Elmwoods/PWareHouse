<?php 
// header('Content-Type: text/html; charset=UTF-8');
	namespace  wstmart\recharge\Controller;
	use wstmart\common\model\Sdk as S;
	use think\Controller;
	
	class Index extends Controller{


		//token 身份验证
		public function token($id,$token) {
			$to = \think\Db::name('user_token')->where('member_id',(int)$id)->order('token_id desc')->find();
			if($to['token']==$token){
				return 'success';
			}else{
				return 'error';
			}
		}

		//充值 钱袋 金豆 
		public function recharge(){
			if(!empty($_POST) && $_POST['id'] != null && $_POST['token'] != null && $_POST['purse'] != null && $_POST['amount'] != null){
				$s = new S();
				if($this->token($_POST['id'],$_POST['token'])==='success'){
					require_once 'aop/AopClient.php';
					require_once 'aop/request/AlipayTradeAppPayRequest.php';
					$aop = new \AopClient;
					$aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
					$aop->appId = "2017011705151664";
					$aop->rsaPrivateKey = 'MIIEpQIBAAKCAQEAzknOfQFizD/6B1RkNcR3xQr5o8HtP74Lg5CaJqvXjEh2QRILvEhSHWhMxyJCLW38mmhmdQQ6l7TIRE1tkgU3pmcYgYmi593RuJ4V49NLRT4KTQNcU2q+bp/MvnbqYrOWoxVoIzYSnLHnVK0ymwHUmhhcg1PBdI5xzyk21pUbchTdFQ/VmqbnOghgOmLfe2PbJogfLfvu8/d8f1os70gRBYSGJzg5jIUdABCdRc5x+CikNbkDBxRJEI1xWdozLEceckyyGwSzN5otvJBm+HGTEd5Cf3SmF7zzvEi/H/2tN9tX7QE/KGjZlUxTNYZuGYJIJYa8iyJUh8tPfeE1MXaoVQIDAQABAoIBAQCMGJvfUX2jcR+AstOLoG4mp5l6mU1iqNJw+1d1Q+cTInMNJhBKQmNiDV93LdD7wWJ4CsbqWYDhXqlTmbH8JQbyP7no32x/Q6oWU2ZSX0ETOVsNima9UBUcU/Jct63eclCvWO5sW2CwgjG01Bs2IjwcmsbZeZw8aDDqm/beLE2DX2fOPPZrSqy4zRhaFQxUbMdYHX9/TQ//K201rS0m1GvcbFO/CngzvKc+xVleeQc+8CBpAMhsENB6SUMVYlIJaq3nvG0aXnwSH7Kmpj0bqIw+K997hfQMsKANSZOaBaHL5Dv2MalI4/qMs2VJUkgRr6yks5qoMIfaIpgQdZjvBmeBAoGBAOg/1CcweFF9ThXxzXLbbf/1mCJ3lHPR31XEiipn0NbG8R+dXIqEt66R0jgrzsIf6S6fOGpPDIOQGURCfTwR8IGSrnnDqR/f3JWYolfRymFXVunvGNz7TF60xvyU+2PC6jXHDr2dM7uJg6XTml2ZE0T2pMnTsfGEHExKnZoi9zm5AoGBAONiVM0gtslv62dYonSZjtrRYlWtJvM6maf0SyaO6TZUEDaMoRbHoXh88Rz/kqV3/WL93cpFcqjLSTDlLTSxxFZpcK8oJkbuwrKJ+/rZCmbEca/Pb40X50tlRkIqawQsdTfOlHqggTpjCeQW2jnYe7jScEv2WFgX/B6hUgvPk8F9AoGAKe4cJ1cg4dV1m5CkPvBO079LUC22p5Jkd9+b8jv8AEq6jbKjWn7LisDY6zs9gN6yArDMRqUu5THG3gQDCC1U9o+84E7q4c5QzNFZvfEqUJisIGACZSMZjp+krUVYfZJbJophpuoSxPD2y6GAZRWV3QWKisWlgq0PTtbJzWIysEECgYEAk1k0RN1PNggdzxHD7LVZunj3NTgIxpOR4SHQ1ULE49zjyMWm2iExhOfKQ5VmjW3NOKn0YNBSNgnN+y539e7AoZKgYBEvhMXSS2pZbLvbHq9sUJam3hLAYr5VIilkwgahSzHGTBTYyWJGlZUtg1DDFAjilocjxqp8SckWZurz/+0CgYEAxDlomIksx8Cg6H/aEXgfoPLgk7ISbXXd+HWaB/08m6Zb+AxOYDKl0sNCO0ObTfsJhr7rJetrXvki/Jm6BgCzV4vnJiEGsqUqvbA5b+pcfKVGkEqVRfKWtqNgV724ShZwrs3PfFl+G7AeArjbCNShVq4uP+rbu1uGtYqRbS3wG3U=';
					$aop->format = "json";
					$aop->charset = "UTF-8";
					$aop->signType = "RSA2";
					$aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuTjQgKPC980aHOPkbJQBAmbGHLwuJ4K3YPStcq1Nvc22plEfy3f5CZ7N19DhQeY+Hu/oNJJrg7KezpeXn8vkyltdgepXNWUl5xWvSezEw1SGY3uO0GpvpeXloEB4w6rQF8k1jP0aUp1eAmHdEKp3UpABaGhhsCzWYc+EG3TSH2284SNJqY6LswND/S9gOMvahIfIlH618T8QmjBJPGnhNXdsqIgLqqHpxJHypVFYc2HYL9TWZ/wUDN4ueoKuptitSBFT2KzZ6/TuK9JZGB9J1jmr1sYTboDh8ZqOeMKaJAYbO9x8eZq16A5HXg7oIPvPeDgQcP3HmGRZlYCbf7jZ1QIDAQAB';
					//实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
					$request = new \AlipayTradeAppPayRequest();
					//SDK已经封装掉了公共参数，这里只需要传入业务参数
					$arr1=array(1=>'钱袋充值',2=>'金豆充值',3=>'金购中国还款',4=>'给好友充值金豆');
					$make=date('YmdHis'). sprintf('%03d', (float)microtime() * 1000). $_POST['id'];
					// var_dump($make);
					// $data = date('YmdHis'). sprintf('%03d', (float)microtime() * 1000).$_POST['id'];
					// var_dump($_SESSION);
					$bizcontent = "{\"body\":\"我是测试数据\","
					                . "\"subject\": \"".$arr1[$_POST['purse']]."\","//1钱袋 2 金豆
					                . "\"out_trade_no\": \"".$make."\","
					                . "\"timeout_express\": \"30m\","
					                . "\"total_amount\": \"".$_POST['amount']."\","
					                . "\"product_code\":\"QUICK_MSECURITY_PAY\""
					                . "}";
					$request->setNotifyUrl("http://www.jingomall.com/recharge/index/asynchronous");
					$request->setBizContent($bizcontent);
					//这里和普通的接口调用不同，使用的是sdkExecute
					$response = $aop->sdkExecute($request);	

					//htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
					// echo htmlspecialchars(json_encode(array('data'=>$response)));//就是orderString 可以直接给客户端请求，无需再做处理。

					

					if($_POST['purse']==1){
						if(is_numeric($_POST['amount'])){
							//将信息添加到充值表//未支付状态
							$arr['pdr_sn'] = $make;
							$arr['pdr_user_id'] = $_POST['id'];
							$arr['pdr_amount'] = $_POST['amount'];
							$arr['pdr_payment_code'] = 'alipay';
							$arr['pdr_payment_name'] = '支付宝';
							$arr['pdr_add_time'] = date('Y-m-d H:i:s');
							$arr['pdr_payment_state']=0;
							$s=\think\Db::name('pd_recharge')->insert($arr);
							echo json_encode(array('data'=>$response));
						}else{
							//金额错误
							return json_encode(array('resultcode'=>$s->transfer_purse_jine(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
						}
						
					}elseif($_POST['purse']==2){
						if(is_numeric($_POST['amount'])){
							//金豆充值//未支付状态
							//将信息添加到充值表
							$arr['imr_sn'] = $make;
							$arr['imr_user_id'] = $_POST['id'];
							$arr['imr_amount'] = $_POST['amount'];
							$arr['imr_payment_code'] = 'alipay';
							$arr['imr_payment_name'] = '支付宝';
							$arr['imr_add_time'] = date('Y-m-d H:i:s');
							\think\Db::name('im_recharge')->insert($arr);
							echo json_encode(array('data'=>$response));
						}else{
							//金额错误
							return json_encode(array('resultcode'=>$s->transfer_purse_jine(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
						}
					}elseif($_POST['purse']==3){
						if(is_numeric($_POST['amount'])){
							$user =\think\Db::name('users')->where('userId',(int)$_POST['id'])->field('residual_repayment')->find();
							if($user['residual_repayment']>=$_POST['amount']){
								//将信息添加到还贷款表
								$arr['al_sn'] = $make;
								$arr['user_id'] = $_POST['id'];
								$arr['al_type'] = '还款';
								$arr['al_amonut'] = $_POST['amount'];
								$arr['al_payment'] = 'alipay';
								$arr['al_status'] = '待还款';
								$arr['al_add_time'] = date('Y-m-d H:i:s');
								\think\Db::name('also_loan')->insert($arr);
								echo json_encode(array('data'=>$response));

							}else{
								return json_encode(array('resultcode'=>$s->repayment_exceed(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
							}
						}
					
					}elseif($_POST['purse']==4){
						if(is_numeric($_POST['amount'])){
							$users = \think\Db::name('users')->field('userId')->where('userPhone|loginName',input('post.name'))->find();
							//将信息添加到充值表
							$arr['imr_sn'] = $make;
							$arr['imr_user_id'] = $users['userId'];
							$arr['imr_amount'] = $_POST['amount'];
							$arr['imr_payment_code'] = 'alipay';
							$arr['imr_payment_name'] = '支付宝';
							$arr['imr_add_time'] = date('Y-m-d H:i:s');
							$arr['imr_yourself']=input('post.id');
							\think\Db::name('im_recharge')->insert($arr);
							echo json_encode(array('data'=>$response,'order'=>$make));
						}

					}

			}else{
				return json_encode(array('resultcode'=>$s->token_ne(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			}
		}else{
			return json_encode(array('resultcode'=>$s->token_id(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

		}


	}

	//充值后app 返回信息
	// public function Callback(){
	// 	$s = new S();
	// 	if(!empty($_POST) && $_POST['id'] != null && $_POST['token'] != null){
	// 		if($this->token($_POST['id'],$_POST['token'])==='success'){
	// 			if($_POST['purse']=='qd_success'){
	// 				// echo 1;
	// 				return json_encode(array('resultcode'=>$s->alipay_ok(),'info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
							
	// 			}elseif($_POST['purse']=='jd_success'){//金豆充值

	// 				return json_encode(array('resultcode'=>$s->alipay_ok(),'info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
						
	// 			}elseif($_POST['purse']=='hy_success'){//还贷款
					
	// 				return json_encode(array('resultcode'=>$s->alipay_hy_ok(),'info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);		
	// 			}
	// 		}else{
	// 			return json_encode(array('resultcode'=>$s->token_no(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
	// 		}
	// 	}else{
	// 		return json_encode(array('resultcode'=>$s->token_id(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	// 	}
	// }

	//异步回调
	public function asynchronous(){
		require_once 'aop/AopClient.php';
		$aop = new \AopClient;
		$aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuTjQgKPC980aHOPkbJQBAmbGHLwuJ4K3YPStcq1Nvc22plEfy3f5CZ7N19DhQeY+Hu/oNJJrg7KezpeXn8vkyltdgepXNWUl5xWvSezEw1SGY3uO0GpvpeXloEB4w6rQF8k1jP0aUp1eAmHdEKp3UpABaGhhsCzWYc+EG3TSH2284SNJqY6LswND/S9gOMvahIfIlH618T8QmjBJPGnhNXdsqIgLqqHpxJHypVFYc2HYL9TWZ/wUDN4ueoKuptitSBFT2KzZ6/TuK9JZGB9J1jmr1sYTboDh8ZqOeMKaJAYbO9x8eZq16A5HXg7oIPvPeDgQcP3HmGRZlYCbf7jZ1QIDAQAB';
		$flag = $aop->rsaCheckV1($_POST, NULL, "RSA");

		if($flag){
			echo 'success';
		}else{
			echo 'error';
		}
	}

		

}
	
