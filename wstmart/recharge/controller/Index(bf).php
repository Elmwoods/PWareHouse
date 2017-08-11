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
					$arr1=array(1=>'钱袋充值',2=>'金豆充值',3=>'金购中国还款');
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
						
				}else{
					return json_encode(array('resultcode'=>$s->token_ne(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
				}
			}
		}else{
			return json_encode(array('resultcode'=>$s->token_id(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

		}
	}

	//充值后app 返回信息
	public function Callback(){
		$s = new S();
		if(!empty($_POST) && $_POST['id'] != null && $_POST['token'] != null){
			if($this->token($_POST['id'],$_POST['token'])==='success'){
				if($_POST['purse']=='qd_success'){
					// echo 1;
					//钱袋子流水表
					$recharge = \think\Db::name('pd_recharge')->where('pdr_user_id',(int)$_POST['id'])->order('pdr_id desc')->field('pdr_amount,pdr_sn,pdr_id,pdr_payment_state')->find();
					// dump($recharge);
					if($_POST['amount'] == $recharge['pdr_amount'] && $recharge['pdr_payment_state']==1 ){//判断金额或者是否充值成功
						$user = \think\Db::name('users')->where('userId',(int)$_POST['id'])->field('userMoney,userType,userId')->find();
						
						// 启动事务
							\think\Db::startTrans(); 
							try{

						if($user){ 
							$insert['targetType'] = $user['userType'];
							$insert['targetId'] = $user['userId'];
							$insert['dataId'] = 6;
							$insert['dataSrc'] = '充值';
							$insert['moneyType'] = 1;
							$insert['money'] =$recharge['pdr_amount'];
							$insert['tradeNo'] = $recharge['pdr_sn'];
							$insert['payType'] = 1;
							$insert['createTime'] = date('Y-m-d H:i:s');
							$insert['remark'] = '钱袋子充值';
							$in = \think\Db::name('log_moneys')->insert($insert);
							//钱袋充值价格修改
							$qd=\think\Db::name('users')->where('userId',(int)$_POST['id'])->update(['userMoney'=>$recharge['pdr_amount']+$user['userMoney']]);

							if($in && $qd){
								return json_encode(array('resultcode'=>$s->alipay_ok(),'info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
							}else{
								return json_encode(array('resultcode'=>$s->alipay_no(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
							}
						}else{
								return json_encode(array('resultcode'=>$s->alipay_no(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
						}
						// 提交事务
			           			\think\Db::commit();
			           		}catch(\Exception $e){
			           			// 回滚事务
			           			\think\Db::rollback();
			           		}
						
					}else{
							return json_encode(array('resultcode'=>$s->format_error(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
					}
					
				}elseif($_POST['purse']=='jd_success'){//金豆充值

					//获取表信息
					$im = \think\Db::name('im_recharge')->field('imr_sn,imr_amount,imr_add_time,imr_payment_state,imr_id')->order('imr_id desc')->where('imr_user_id',(int)$_POST['id'])->find();
					// dump($im);
					if($im && $im['imr_payment_state']==1 && $im['imr_amount']==$_POST['amount']){
						// echo 1;
						$user = \think\Db::name('users')->where('userId',(int)$_POST['id'])->field('imazamox_number,userId,userType')->find();

						// 启动事务
							\think\Db::startTrans(); 
							try{

						if($user){
							//流水表数据
							//将数据添加到金豆变更表
							$insert['user_id']           =     $user['userId'];
							$insert['imazamox_sn']       =     $im['imr_sn'];
							$insert['imazamox_type']     =     '充值';
							$insert['imazamox_number']   =     $im['imr_amount'];
							$insert['imazamox_change']   =     $im['imr_amount'];
							$insert['is_recharge']       =     1;
							$insert['imazamox_desc']     =     '金豆充值';
							$insert['add_time']          =     date('Y-m-d H:i:s');
							//将信息添加到资金流水表
							$ins['targetType']           =     $user['userType'];
							$ins['targetId']             =     $user['userId'];
							$ins['dataId']               =     10;
							$ins['dataSrc']              =     '充值';
							$ins['moneyType']            =     1;
							$ins['money']                =     $im['imr_amount'];
							$ins['tradeNo']              =     $im['imr_sn'];
							$ins['payType']              =     1;
							$ins['createTime']           =     $im['imr_add_time'];
							$ins['remark']               =     '金豆充值';
							$ins['endTime'] =date('Y-m-d H:i:s');
							$users = \think\Db::name('users')->where('userId',(int)$_POST['id'])->update(['imazamox_number'=>$user['imazamox_number']+$im['imr_amount']]);
							$ins = \think\Db::name('log_moneys')->insert($ins);
							$in = \think\Db::name('imazamox_flow_log')->insert($insert);
							return json_encode(array('resultcode'=>$s->alipay_ok(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
						}else{
							return json_encode(array('resultcode'=>$s->alipay_no(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
						}

						// 提交事务
			           			\think\Db::commit();
			           		}catch(\Exception $e){
			           			// 回滚事务
			           			\think\Db::rollback();
			           		}

					}else{
						return json_encode(array('resultcode'=>$s->alipay_no(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
					}
				}elseif($_POST['purse']=='hy_success'){//还贷款
					$also = \think\Db::name('also_loan')->where('user_id',(int)$_POST['id'])->field('id,al_sn,al_amonut,al_status,al_add_time')->order('id desc')->find();
					if($also['al_amonut'] == $_POST['amount'] && $also['al_status'] == '还款成功'){
						
							// 启动事务
							\think\Db::startTrans(); 
							try{

						$user = \think\Db::name('users')->where('userId',$_POST['id'])->field('userId,userType,residual_repayment')->find();

						if($user){
							//修该users 表
							//将信息添加到资金流水
				            $insert['targetType']    =   $user['userType'];
				            $insert['targetId']      =   $user['userId'];
				            $insert['dataId']        =   9;
				            $insert['dataSrc']       =   '贷款';
				            $insert['moneyType']     =   0;
				            $insert['money']         =   $_POST['amount'];
				            $insert['tradeNo']       =   $also['al_sn'];
				            $insert['payType']       =   1;
				            $insert['createTime']    =   $also['al_add_time'];
				            $insert['Headportrait']  =   'upload/users/2016-10/20170603155425.png';//交易对方头像
				            $insert['endTime'] = date('Y-m-d H:i:s');
				            $in = \think\Db::name('log_moneys')->insert($insert);

				            //将信息添加到 消息表中
			                $da1['msgType']			 =	 0;
			                $da1['sendUserId']		 =	 1;
			                $da1['receiveUserId']	 =   $user['userId'];
			                $da1['msgTitle']		 =   '还款';
			                $da1['msgContent']		 =	 '还款成功';
			                $da1['amount']			 =   $_POST['repayment_amount'];
			                $da1['msgDesc']			 =	 '支付宝';	
			                $da1['tradeNo']			 =	 $alNo;
			                $da1['completeTime']     =   date('Y-m-d H:i:s');
			                $da1['msgStatus']        =	 0;
			                $da1['dataFlag']		 =	 1;
			                $da1['createTime'] 		 =	 date('Y-m-d H:i:s');
			                $dd = \think\Db::name('messages')->insert($da1);
				            //修改userss
				            $users=\think\Db::name('users')->where('userId',(int)$_POST['id'])->update(['residual_repayment'=>$user['residual_repayment']-$_POST['amount']]);	
			           		

							return json_encode(array('resultcode'=>$s->alipay_hy_ok(),'info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
						}else{
							return json_encode(array('resultcode'=>$s->alipay_hy_no(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
						}
							// 提交事务
			           			\think\Db::commit();
			           		}catch(\Exception $e){
			           			// 回滚事务
			           			\think\Db::rollback();
			           		}
					}else{
						return json_encode(array('resultcode'=>$s->alipay_hy_no(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
					}
					// dump($also);
				}
			}else{
				return json_encode(array('resultcode'=>$s->token_no(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
			}
		}else{
			return json_encode(array('resultcode'=>$s->token_id(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		}

	}

	//异步回调
	public function asynchronous(){
		require_once 'aop/AopClient.php';
		$aop = new \AopClient;
		$aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuTjQgKPC980aHOPkbJQBAmbGHLwuJ4K3YPStcq1Nvc22plEfy3f5CZ7N19DhQeY+Hu/oNJJrg7KezpeXn8vkyltdgepXNWUl5xWvSezEw1SGY3uO0GpvpeXloEB4w6rQF8k1jP0aUp1eAmHdEKp3UpABaGhhsCzWYc+EG3TSH2284SNJqY6LswND/S9gOMvahIfIlH618T8QmjBJPGnhNXdsqIgLqqHpxJHypVFYc2HYL9TWZ/wUDN4ueoKuptitSBFT2KzZ6/TuK9JZGB9J1jmr1sYTboDh8ZqOeMKaJAYbO9x8eZq16A5HXg7oIPvPeDgQcP3HmGRZlYCbf7jZ1QIDAQAB';

		$flag = $aop->rsaCheckV1($_POST, NULL, "RSA");
		if($flag){
			$flag = rtrim($flag, "&");

		$arr  = parse_url($flag);

		// var_dump($arr);die;
		$queryParts = explode('&',$arr['query']);
		// var_dump($queryParts);die;
		$param =array();
		// var_dump($arr);die;
		foreach($queryParts as $param) {
			$item = explode('=',$param);
			$params[$item[0]]=$item[1];
		}
			// var_dump($params);

		// echo 'success';
		if($params['app_id'] == '2017011705151664' && $params['trade_status'] == 'TRADE_SUCCESS'){
			if($params['subject']=='钱袋充值'){
				$qd = \think\Db::name('pd_recharge')->where('pdr_sn',$params['out_trade_no'])->find();
				if($qd && $qd['pdr_amount']==$params['total_amount']){
					//修改钱袋流水表信息	
					$re=\think\Db::name('pd_recharge')->where('pdr_sn',$params['out_trade_no'])->update(['pdr_payment_state'=>1,'pdr_payment_time'=>date('Y-m-d H:i:s')]);
					if($re){
						echo 'success';
					}else{
						echo 'error';
					}
				}else{
					echo 'error';
				}
			}elseif($params['subject']=='金豆充值'){
				$jd = \think\Db::name('im_recharge')->where('imr_sn',$params['out_trade_no'])->find();
				if($jd && $jd['imr_amount']==$params['total_amount']){
					//修改信息
					$me = \think\Db::name('im_recharge')->where('imr_sn',$params['out_trade_no'])->update(['imr_payment_state'=>1,'imr_payment_time'=>date('Y-m-d H:i:s')]);
					echo 'success';
				}else{
					echo 'error';
				}
			}elseif($params['subject']=='金购中国还款'){
				$hy = \think\Db::name('also_loan')->where('al_sn',$params['out_trade_no'])->find();
				if($hy && $hy['al_amonut']==$params['total_amount']){
					//修改al_amount参数
					$al = \think\Db::name('also_loan')->where('imr_sn',$params['out_trade_no'])->update(['al_status'=>'还款成功']);
					echo 'success';
				}else{
					echo 'error';
				}
			}
		}else{
			echo 'error';
		}
	

	}
		}
		

}
	
