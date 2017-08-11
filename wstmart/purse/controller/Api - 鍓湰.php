<?php 
namespace wstmart\purse\controller;

use wstmart\common\model\Sdk as S;

	class Api extends Base{

		//token 身份验证
		public function token($id,$token) {
			$to = \think\Db::name('user_token')->where('member_id',(int)$id)->find();
			if($to['token']==$token){
				return 'success';
			}else{
				return 'error';
			}
		}
		//账单查询
		public function bill(){
			// $s = new S();
			if($this->token($_POST['id'],$_POST['token'])==='success'){
			$where ="targetId=".$_POST['id'];
	        $da1 = date('Y-m-d H:i:s',strtotime('-1 month'));
	        $da2 = date('Y-m-d H:i:s',strtotime('-3 month'));
	        $da3 = date('Y-m-d H:i:s',strtotime('-1 year'));
	        $month_array = array(1=>$da1,2=>$da2,3=>$da3);
	        $type_array = array(1=>'充值',2=>'提现',3=>'%转账%',4=>'消费',5=>'贷款',6=>'还款');
	        $status_array = array(1=>2,2=>1,3=>0);
	        if(isset($_POST['month'])&&($_POST['month']!=0))
	         	$where .= " AND createTime>'".$month_array[$_POST['month']]."'";
	        if(isset($_POST['type'])&&($_POST['type']!=0))
	        if(isset($_POST['status'])&&($_POST['status']!=0))
	         	$where .= " AND dataFlag='".$status_array[$_POST['status']]."'";
	        if(isset($_POST['st'])&&($_POST['et']!='')&&($_POST['st']!=$_POST['et'])&&$_POST['st']!='')
	         	$where.=" AND createTime>='".$_POST['st']."'"." AND createTime<='".date('Y-m-d',strtotime($_POST['et'] . "+1 day"))."'";
	        if(isset($_POST['st'])&&($_POST['et']==''))
	         	$where.=" AND createTime>='".$_POST['st']."'";
	        if(isset($_POST['et'])&&($_POST['st']==''))
	         	$where.=" AND createTime<='".date('Y-m-d',strtotime($_POST['et'] . "+1 day"))."'";
	        if(isset($_POST['st'])&&($_POST['et']==$_POST['st']))
	         	$where.=" AND createTime LIKE '%".$_POST['st']."%'";

	         if(isset($_POST['sk'])&&($_POST['sk']))
	         	$where.=" AND dataId= '".$_POST['sk']."'";

	         // dump($where);
	        $users = \think\Db::name('log_moneys')
	        ->where($where)
	        ->order('id','desc')
	        ->order('id','desc')
	        ->paginate(10,false,['query'=>$_POST]);
	        	// dump($users);
	        $user = \think\Db::name('users')->where('userId',$_POST['id'])->find();
	        // dump($user);die;
	        foreach ($users as $k => $v) {
	        	$v['monday'] = date('w',strtotime($v['createTime']));
	        	// $v['photo'] = $user['userPhoto'];
	        	$users[$k] = $v;
	        }
	       		// echo '<pre>';
				return json_encode(array('data'=>$users,'info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
				// echo '</pre>'; 

			}else{
				return json_encode(array('resultcode'=>101010,'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			}

	
		}


		//10000 可以贷款
		//20001 需要还款后才能贷款
		//30002 积分不足
		//是否可以贷款 可以返回success 不可以返回error
		public function loan_index($id) {
			// echo 1;
			$s = new S();
			if($this->token($_POST['id'],$_POST['token'])==='success'){
				$users = \think\Db::name('users')
				->where(array('userId' => (int)$id))
				->find();
				
				if($users['userScore']>=1000 && $users['residual_repayment'] == 0){
					$re=[];
					// $re['monery'] = $users['residual_repayment'];
					// echo '<pre>';
					if($users['residual_repayment']==0){
						// $re['monery'] = '10000';
						$re['money']=sprintf("%.2f", 10000);
						$re['userScore']=$users['userScore'];
					}else{
						// $re['monery'] = $users['residual_repayment'];
						$re['money']=sprintf("%.2f", $users['residual_repayment']);
						$re['userScore']=$users['userScore'];
					}
					// echo 111;
					return json_encode(array('data'=>$re,'resultcode'=>$s->Sure(),'info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
					// echo '</pre>';
				}elseif($users['userScore']>=1000 && $users['residual_repayment'] > 0){
					$re=[];
					// $re['repayment_date'] = $users['repayment_date'];
					$re['repayment_date'] = date('Y-m-d',strtotime($users['repayment_date']));
					$re['money'] = $users['residual_repayment'];
					$re['userScore'] = $users['userScore'];
					// echo '<pre>';
					return json_encode(array('data'=>$re,'resultcode'=>$s->Maynot(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
					// echo '</pre>';
				}elseif($users['userScore']<1000){
					$re=[];
					$re['userScore'] = $users['userScore'];
					// echo '<pre>';
					return json_encode(array('data'=>$re,'resultcode'=>$s->Jf_enough(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
					// echo '</pre>';
				}
			}else{
				return json_encode(array('data'=>'null','resultcode'=>$s->token_ne(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			}
		}

		//

		//我要贷款
		public function loan($id){
			$s = new S();
			// dump($_POST);die;
			if($this->token($_POST['id'],$_POST['token'])==='success'){
				//查询贷款用户的信息
		        $user = \think\Db::name('users')
		        ->where(['userId' => $id])
		        ->find();
		// dump($user['userType']);
		        if(is_numeric($_POST['loan_amount']) && $_POST['loan_amount']>0 && $user['residual_repayment']==0){
		            if($user['residual_repayment'] + $_POST['loan_amount'] <= 10000) {
		                // 启动事务
		                \think\Db::startTrans();
		                try{
		                //修改用户表的信息
		                $users['userMoney']           =   $user['userMoney'] + $_POST['loan_amount'];
		                $users['residual_repayment']  =   $user['residual_repayment'] + $_POST['loan_amount'];
		                $users['loan_date']           =   date('Y-m-d H:i:s');
		                $users['repayment_date']      =   date('Y-m-d H:i:s',strtotime("+1 month"));
		                $userId = \think\Db::name('users')
		                ->where(['userId' => (int)$id])
		                ->update($users);
						//dump($user);die;
		                //将信息添加到还贷款日志
		                $alNo = $this->makeSn();
		//                dump($alNo);die;
		                $data['user_id']        =  (int)$id;
		                $data['al_type']        =  '贷款';
		                $data['al_sn']          =  $alNo;
		                $data['al_amonut']      =  $_POST['loan_amount'];
		                $data['al_payment']     =  '钱袋子';
		                $data['al_status']      =  '贷款成功';
		                $data['al_add_time']    =  date('Y-m-d H:i:s');
		                $da = \think\Db::name('also_loan')->insert($data);
		//                dump($da);die;
		                //将信息添加到资金流水
		                $insert['targetType']   =  $user['userType'];
		                $insert['targetId']     =  $user['userId'];
		                $insert['dataId']       =  8;
		                $insert['dataSrc']      =  '贷款';
		                $insert['moneyType']    =  1;
		                $insert['money']        =  $_POST['loan_amount'];
		                $insert['tradeNo']      =  $alNo;
		                $insert['createTime']   =  date('Y-m-d H:i:s');
		                $insert['Headportrait'] =  'upload/users/2016-10/20170603155425.png';//交易对方头像
		                $insert['endTime'] = date('Y-m-d H:i:s');
		                // dump($insert);
		                $in = \think\Db::name('log_moneys')->insert($insert);
		                    // 提交事务
		                    \think\Db::commit();
		                } catch (\Exception $e) {
		                    // 回滚事务
		                    \think\Db::rollback();
		                }
		                if($userId && $da && $in) {
		                   
		        			return json_encode(array('resultcode'=>$s->Loan_success(),'info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		        			
		                } else {
		                    return json_encode(array('resultcode'=>$s->Loan_success(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		                }

		            } else {
		               return json_encode(array('resultcode'=>$s->Loan_error(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		            }
		        } else {
		            return json_encode(array('resultcode'=>$s->Loan_quota(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		    }

	       }else{
	       		return json_encode(array('data'=>'null','resultcode'=>$s->token_ne(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	       }
		}


		//还款
		public function repayment($id){
			$s = new S();
			if($this->token($_POST['id'],$_POST['token'])==='success'){
		//        var_dump($_POST);die;
		        //查询还款用户的信息
		        $user = \think\Db::name('users')->where(['userId' => (int)$id])->find();
		       // dump($user);
		        if(is_numeric($_POST['repayment_amount']) && $_POST['repayment_amount']>0){
		            if($user['residual_repayment'] >= $_POST['repayment_amount']) {
		                // 启动事务
		                \think\Db::startTrans();
		                try{
		                //修改用户表的信息
		                $users['userMoney']           = $user['userMoney'] - $_POST['repayment_amount'];
		                $users['residual_repayment']  = $user['residual_repayment'] - $_POST['repayment_amount'];
		                $userId = \think\Db::name('users')->where(['userId' => (int)$id])->update($users);
		               // dump($user);die;
		                //将信息添加到还贷款日志
		                $alNo = $this->makeSn();
		                $data['user_id']         =   $user['userId'];
		                $data['al_type']         =   '还款';
		                $data['al_sn']           =   $alNo;
		                $data['al_payment']      =   '钱袋子';
		                $data['al_amonut']       =   $_POST['repayment_amount'];
		                $data['al_status']       =   '还款成功';
		                $data['al_add_time']     =   date('Y-m-d H:i:s');
		                $da = \think\Db::name('also_loan')->insert($data);
		               // dump($da);die;
		                //将信息添加到资金流水
		                $insert['targetType']    =   $user['userType'];
		                $insert['targetId']      =   $user['userId'];
		                $insert['dataId']        =   9;
		                $insert['dataSrc']       =   '贷款';
		                $insert['moneyType']     =   0;
		                $insert['money']         =   $_POST['repayment_amount'];
		                $insert['tradeNo']       =   $alNo;
		                $insert['payType']       =   0;
		                $insert['createTime']    =   date('Y-m-d H:i:s');
		                $insert['Headportrait']  =   'upload/users/2016-10/20170603155425.png';//交易对方头像
		                $insert['endTime'] = date('Y-m-d H:i:s');
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
		                if($userId && $da && $in) {

		                    return json_encode(array('resultcode'=>$s->repayment_success(),'info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		                    
		                } else {
		                	//将信息添加到资金流水//失败信息
			                $insert['targetType']    =   $user['userType'];
			                $insert['targetId']      =   $user['userId'];
			                $insert['dataId']        =   9;
			                $insert['dataSrc']       =   '贷款';
			                $insert['moneyType']     =   0;
			                $insert['money']         =   $_POST['repayment_amount'];
			                $insert['tradeNo']       =   $alNo;
			                $insert['payType']       =   1;
			                $insert['createTime']    =   date('Y-m-d H:i:s');
			                $insert['Headportrait']  =   'upload/users/2016-10/20170603155425.png';//交易对方头像
			                $insert['endTime'] = date('Y-m-d H:i:s');
			                $in = \think\Db::name('log_moneys')->insert($insert);
		                    return json_encode(array('resultcode'=>$s->repayment_error(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		                }
		            } else {
		            	//将信息添加到资金流水//失败信息
			                $insert['targetType']    =   $user['userType'];
			                $insert['targetId']      =   $user['userId'];
			                $insert['dataId']        =   9;
			                $insert['dataSrc']       =   '贷款';
			                $insert['moneyType']     =   0;
			                $insert['money']         =   $_POST['repayment_amount'];
			                $insert['tradeNo']       =   $alNo;
			                $insert['payType']       =   1;
			                $insert['createTime']    =   date('Y-m-d H:i:s');
			                $insert['Headportrait']  =   'upload/users/2016-10/20170603155425.png';//交易对方头像
			                $insert['endTime'] = date('Y-m-d H:i:s');
			                $in = \think\Db::name('log_moneys')->insert($insert);
		                return json_encode(array('resultcode'=>$s->repayment_exceed(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		            }
		        } else {
		        	//将信息添加到资金流水//失败信息
			                $insert['targetType']    =   $user['userType'];
			                $insert['targetId']      =   $user['userId'];
			                $insert['dataId']        =   9;
			                $insert['dataSrc']       =   '贷款';
			                $insert['moneyType']     =   0;
			                $insert['money']         =   $_POST['repayment_amount'];
			                $insert['tradeNo']       =   $alNo;
			                $insert['payType']       =   1;
			                $insert['createTime']    =   date('Y-m-d H:i:s');
			                $insert['Headportrait']  =   'upload/users/2016-10/20170603155425.png';//交易对方头像
			                $insert['endTime'] = date('Y-m-d H:i:s');
			                $in = \think\Db::name('log_moneys')->insert($insert);
		            return json_encode(array('resultcode'=>$s->format_error(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		        }
		    }else{
	       		return json_encode(array('resultcode'=>$s->token_ne(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

		    }
    }


		//金豆查询
		public function imazamox_index($id){
			if($this->token($_POST['id'],$_POST['token'])==='success'){
				$users = \think\Db::name('users')
				->where(array('userId' => (int)$_POST['id']))
				->find();
				$u=[];
				$u['imazamox_number'] = $users['imazamox_number'];
				//dump($users);
	       		//获取金豆变更表的记录
		        $im = \think\Db::name('log_moneys')
		        ->where(array('targetId' => (int)$id))
		        ->where('dataId','in','10,11,12,13')
		        ->order('id desc')
		        ->paginate(10);
		        // echo '<pre>';
		        //添加星期 
	    		foreach ($im as $k => $v) {
		        	$v['monday'] = date('w',strtotime($v['createTime']));
		        	// $v['photo'] = $user['userPhoto'];
		        	$im[$k] = $v;
	        	}
	        	// echo '<pre>';
		        return json_encode(array('data'=>$im,'info'=>'success','money'=>$u),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		        // echo '</pre>';
			}else{
				return json_encode(array('data'=>array('Rows'=>'null'),'info'=>'error'));

			}

		}

		//金豆充值
		
	//转账到钱袋//用户转账
    public function transfer_purse() {
    	$s = new S();
    	if($this->token($_POST['id'],$_POST['token'])==='success'){
    		//信息过滤
	            //获取当前用户的信息
	            $user = \think\Db::name('users')->where(array('userId' => (int)$_POST['id']))->find();
	            //获取收款人的信息
	            $users = \think\Db::name('users')->where('loginName|userPhone',$_POST['member_name'])->find();
	            if($users['loginName'] != $user['loginName']) {
	                if (is_numeric($_POST['amount']) && $_POST['amount'] > 0) {
	                    if ($user['userMoney'] >= $_POST['amount']) {
	                        if ($users) {
	           						// dump($users);die;
	                                // 启动事务
	                                \think\Db::startTrans();
	                                try{
	                          
	                                	//修改用户的余额
	                                    $me = \think\Db::name('users')
	                                    ->where(array('userId' => $_POST['id']))
	                                    ->update(['userMoney' => $user['userMoney'] - $_POST['amount']]);
	                                    //修改收款人的余额
	                                    $mem = \think\Db::name('users')
	                                    ->where(array('loginName' => $users['loginName']))
	                                    ->update(['userMoney' => $users['userMoney'] + $_POST['amount']]);

	                                    $tradeNo = $this->makeSn();
	                               
	                                    if ($_POST['is_get'] != '') {
	                                        //获取信息表中的信息
	                                        $news = \think\Db::name('messages')->where('id' , input('is_get'))->find();
	                                        //将信息标为已完成
	                                        \think\Db::name('messages')
	                                        ->where('id', input('is_get'))
	                                        ->update(['dataFlag' => 1,'completeTime'=>date('Y-m-d H:i:s')]);
	                                        
	                                        //将资金流水的状态改为完成
	                                        \think\Db::name('log_moneys')->where(['tradeNo' => $news['tradeNo']])->update(['dataFlag' => 1]);
	                                    } else {
	                                        //将转账消息添加到资金流水
	                                        $data = array(
	                                        	'targetType'   =>   $user['userType'], 
	                                        	'targetId'     =>   $_POST['id'], 
	                                        	'dataId'       =>   4, 
	                                        	'dataSrc'      =>   '转账', 
	                                        	'moneyType'    =>   0, 
	                                        	'money'        =>   $_POST['amount'], 
	                                        	'payName'      =>   $users['loginName'],
	                                        	'tradeNo'      =>   $tradeNo, 
	                                        	'Headportrait' =>   isset($users['userPhoto'])?$users['userPhoto']:'',
	                                        	'payType'      =>   0, 
	                                        	'dataFlag'     =>   1, 
	                                        	'createTime'   =>   date('Y-m-d H:i:s'), 
	                                        	'remark'       =>   htmlspecialchars($_POST['desc']),
	                                        	'endTime'      =>   date('Y-m-d H:i:s'));
	                                        $data1 = array(
	                                        	'targetType'   =>   $users['userType'], 
	                                        	'targetId'     =>   $users['userId'], 
	                                        	'dataId'       =>   5, 
	                                        	'dataSrc'      =>   '转账', 
	                                        	'moneyType'    =>   1, 
	                                        	'money'        =>   $_POST['amount'], 
	                                        	'payName'      =>   $user['loginName'],
	                                        	'tradeNo'      =>   $tradeNo, 
	                                        	'Headportrait' =>   isset($user['userPhoto'])?$user['userPhoto']:'',
	                                        	'payType'      =>   0, 
	                                        	'dataFlag'     =>   1, 
	                                        	'createTime'   =>   date('Y-m-d H:i:s'), 
	                                        	'remark'       =>   htmlspecialchars($_POST['desc']),
	                                        	'endTime'      =>   date('Y-m-d H:i:s'),
	                                        	);
	                                        // dump($data);
	                                        // dump($data1);
	                                       \think\Db::name('log_moneys')->insert($data);
	                                       // echo $s;
	                                       \think\Db::name('log_moneys')->insert($data1);
	                                       // echo $a;
	                                    }
	                                    // dump($user['userType']);die;
	                                    //将信息添加到转账表里面
		                                    $da = array(
		                                    	'targetType'  =>    $user['userType'], 
		                                    	'targetId'    =>    $user['userId'],  
		                                    	'moneyType'   =>    0, 
		                                    	'money'       =>    $_POST['amount'], 
		                                    	'payName'     =>    $users['loginName'],
		                                    	'tradeNo'     =>    $tradeNo, 
		                                    	'payType'     =>    0, 
		                                    	'dataFlag'    =>    1, 
		                                    	'createTime'  =>    date('Y-m-d H:i:s'), 
		                                    	'remark'      =>    htmlspecialchars($_POST['desc']));

	                                    // dump($da);die;
		                                    $da1 = array(
		                                    	'targetType'  =>    $users['userType'], 
		                                    	'targetId'    =>    $users['userId'], 
		                                    	'moneyType'   =>    1, 
		                                    	'money'       =>    $_POST['amount'], 
		                                    	'payName'     =>    $user['loginName'],
		                                    	'tradeNo'     =>    $tradeNo, 
		                                    	'payType'     =>    0, 
		                                    	'dataFlag'    =>    1, 
		                                    	'createTime'  =>    date('Y-m-d H:i:s'), 
		                                    	'remark'      =>    htmlspecialchars($_POST['desc']));

	                                    \think\Db::name('transfer')->insert($da);
	                                    \think\Db::name('transfer')->insert($da1);
	                                    

	                                    // 提交事务
	                                    \think\Db::commit();
	                                    return json_encode(array('resultcode'=>$s->transfer_purse_ok(),'info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

	                                } catch (\Exception $e) {
	                                //     // 回滚事务
	                                    \think\Db::rollback();
	                                    return json_encode(array('resultcode'=>$s->transfer_purse_fail(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

	                                    // echo 0;
	                                }

	                        } else {
	                        	// echo 1;
	                            return json_encode(array('resultcode'=>$s->transfer_purse_name(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	                        }
	                    } else {
	                    	// echo 2;
	                        return json_encode(array('resultcode'=>$s->transfer_purse_monery(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	                    }
	                } else {
	                	// echo 3;
	                    return json_encode(array('resultcode'=>$s->transfer_purse_jine(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	                }
	            } else {
	            	// echo 4;
	                return json_encode(array('resultcode'=>$s->transfer_purse_own(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	            }

		}else{
			return json_encode(array('data'=>'null','resultcode'=>$s->token_ne(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		} 

	}   

	


	//我要收款
	public function News() {
		$s = new S();
		if($this->token($_POST['id'],$_POST['token'])==='success'){
				$users = \think\Db::name('users')->where('userId',(int)$_POST['id'])->find();
				// dump($users);die;
				if($_POST['user_name'] != $users['loginName'] && $_POST['user_name'] != $users['userPhone']) {
	            if (is_numeric($_POST['news_amount']) && $_POST['news_amount'] >= 0.01) {
	                //获取转账用户的信息
	                $user = \think\Db::name('users')->where('loginName|userPhone',$_POST['user_name'])->find();
	                if($user) {
	                    $tradeNo = $this->makeSn();
	                    // 启动事务
	                    \think\Db::startTrans();
	                    try{
	                        $data['msgType']       =   2;
	                        $data['receiveUserId'] =   $user['userId'];
	                        $data['sendUserId']    =   $users['userId'];
	                        $data['msgTitle']      =   '收款';
	                        $data['msgContent']    =   '用户' . $users['loginName'] . '向你收款' . $_POST['news_amount'] . '元，说明：' . htmlspecialchars($_POST['news_desc']);
	                        $data['amount']        =   $_POST['news_amount'];
	                        $data['tradeNo']       =   $tradeNo;
	                        $data['dataFlag']      =   0;
	                        $data['msgDesc']       =   htmlspecialchars($_POST['news_desc']);
	                        $data['createTime']    =   date('Y-m-d H:i:s');
	                        $news = \think\Db::name('messages')->insert($data);
	                        // dump($news);die;
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
	                        $data1 = array(
	                        	'targetType' =>   $user['userType'], 
	                        	'targetId'   =>   $user['userId'], 
	                        	'dataId'     =>   4, 
	                        	'dataSrc'    =>   '转账', 
	                        	'moneyType'  =>   0, 
	                        	'money'      =>   $_POST['news_amount'], 
	                        	'payName'    =>   $users['loginName'],
	                        	'tradeNo'    =>   $tradeNo, 
	                        	'payType'    =>   0, 
	                        	'dataFlag'   =>   2, 
	                        	'createTime' =>   date('Y-m-d H:i:s'), 
	                        	'remark'     =>   htmlspecialchars($_POST['news_desc']),
	                        	'endTime'    =>   date('Y-m-d H:i:s'));
	                        \think\Db::name('log_moneys')->insert($data1);
	                        //将信息添加到资金流水表
	                        $in = array(
	                        	'targetType' =>   $users['userType'], 
	                        	'targetId'   =>   $users['userId'], 
	                        	'dataId'     =>   5, 
	                        	'dataSrc'    =>   '转账', 
	                        	'moneyType'  =>   1, 
	                        	'money'      =>   $_POST['news_amount'], 
	                        	'payName'    =>   $user['loginName'],
	                        	'tradeNo'    =>   $tradeNo, 
	                        	'payType'    =>   0, 
	                        	'dataFlag'   =>   2, //进行中
	                        	'createTime' =>   date('Y-m-d H:i:s'), 
	                        	'remark'     =>   htmlspecialchars($_POST['news_desc']),
	                        	'endTime'    =>   date('Y-m-d H:i:s'));
	                        \think\Db::name('log_moneys')->insert($in);
	                        // 提交事务
	                        \think\Db::commit();
	                    } catch (\Exception $e) {
	                        // 回滚事务
	                        \think\Db::rollback();
	                    }
							return json_encode(array('resultcode'=>$s->new_ok(),'info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

	                    } else {
	                    	//将数据添加到资金流水//失败数据
		                    $insert['targetType']  =   $user['userType'];
		                    $insert['targetId']    =   $user['userId'];
		                    $insert['dataId']      =   5;
		                    $insert['dataSrc']     =   '转账';
		                    $insert['moneyType']   =   1;
		                    $insert['dataFlag']    =   0;
		                    $insert['money']       =   $_POST['news_amount'];
		                    $insert['payName']     =   $user['loginName'];
		                    $insert['tradeNo']     =   $tradeNo;
		                    $insert['payType']     =   1;
		                    $insert['createTime']  =   date('Y-m-d H:i:s');
		                    $insert['remark']      =   htmlspecialchars($_POST['news_desc']);
		                    $in = \think\Db::name('log_moneys')->insert($insert);

	                        return json_encode(array('resultcode'=>$s->new_fail(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	                    }
	                } else {
	                	//将数据添加到资金流水//失败数据
		                    $insert['targetType']  =   $user['userType'];
		                    $insert['targetId']    =   $user['userId'];
		                    $insert['dataId']      =   5;
		                    $insert['dataSrc']     =   '转账';
		                    $insert['moneyType']   =   1;
		                    $insert['dataFlag']    =   0;
		                    $insert['money']       =   $_POST['news_amount'];
		                    $insert['payName']     =   $user['loginName'];
		                    $insert['tradeNo']     =   $tradeNo;
		                    $insert['payType']     =   1;
		                    $insert['createTime']  =   date('Y-m-d H:i:s');
		                    $insert['remark']      =   htmlspecialchars($_POST['news_desc']);
		                    $in = \think\Db::name('log_moneys')->insert($insert);
	                    return json_encode(array('resultcode'=>$s->new_name(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	                }
	            } else {
	            	//将数据添加到资金流水//失败数据
		                    $insert['targetType']  =   $user['userType'];
		                    $insert['targetId']    =   $user['userId'];
		                    $insert['dataId']      =   5;
		                    $insert['dataSrc']     =   '转账';
		                    $insert['moneyType']   =   1;
		                    $insert['dataFlag']    =   0;
		                    $insert['money']       =   $_POST['news_amount'];
		                    $insert['payName']     =   $user['loginName'];
		                    $insert['tradeNo']     =   $tradeNo;
		                    $insert['payType']     =   1;
		                    $insert['createTime']  =   date('Y-m-d H:i:s');
		                    $insert['remark']      =   htmlspecialchars($_POST['news_desc']);
		                    $in = \think\Db::name('log_moneys')->insert($insert);
	                return json_encode(array('resultcode'=>$s->new_money(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	            }
	        } else {
	        	//将数据添加到资金流水//失败数据
		                    $insert['targetType']  =   $user['userType'];
		                    $insert['targetId']    =   $user['userId'];
		                    $insert['dataId']      =   5;
		                    $insert['dataSrc']     =   '转账';
		                    $insert['moneyType']   =   1;
		                    $insert['dataFlag']    =   0;
		                    $insert['money']       =   $_POST['news_amount'];
		                    $insert['payName']     =   $user['loginName'];
		                    $insert['tradeNo']     =   $tradeNo;
		                    $insert['payType']     =   1;
		                    $insert['createTime']  =   date('Y-m-d H:i:s');
		                    $insert['remark']      =   htmlspecialchars($_POST['news_desc']);
		                    $in = \think\Db::name('log_moneys')->insert($insert);
	            return json_encode(array('resultcode'=>$s->new_own(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	        }
		}else{
			return json_encode(array('data'=>'null','resultcode'=>$s->token_ne(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		}
        
    }

   //  //贷款记录查询
    public function loan_record(){
    	$s = new S();
    	if($this->token($_POST['id'],$_POST['token'])==='success'){
	    	$al = \think\Db::name('log_moneys')->where(['targetId' => (int)$_POST['id'],'dataSrc'=>'贷款'])->order('id desc')->paginate(10);
	    	// return 
	    	if($al){
	    		//添加星期 
	    		foreach ($al as $k => $v) {
		        	$v['monday'] = date('w',strtotime($v['createTime']));
		        	// $v['photo'] = $user['userPhoto'];
		        	$al[$k] = $v;
	        	}
	    		// echo '<pre>';
	    		return json_encode(array('data'=>$al,'info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	    		// echo '</pre>';
	    	}else{
				return json_encode(array('data'=>'null','info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	    	}
    	}else{
    		return json_encode(array('data'=>'null','resultcode'=>$s->token_ne(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    	}
  
    }

    //钱袋子记录
    public function index(){
    	$s= new S();
    	// dump($s);
    	if($this->token($_POST['id'],$_POST['token'])==='success'){
	    	$user=\think\Db::name('users')->where('userId',$_POST['id'])->find();
	    	// if($user){
		    	$u=[];
		    	$u['userMoney']  =  $user['userMoney'];
		    	$u['lockMoney']  =  $user['lockMoney'];
		    	$al = \think\Db::name('log_moneys')->where('targetId',$_POST['id'])->order('id desc')->paginate(10);
		    	foreach ($al as $k => $v) {
				        	$v['monday'] = date('w',strtotime($v['createTime']));
				        	$al[$k] = $v;
			        	}
			    // echo '<pre>';
		    	return json_encode(array('data'=>$al,'info'=>'success','money'=>$u),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			// echo '</pre>' ;
    	}else{
    		// echo $s->token_ne()
	    	return json_encode(array('data'=>null,'info'=>'error','resultcode'=>$s->token_ne()),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

    	}
    	
    }
    

    //金豆转账
    public function imazamox(){
    	$s= new S();
    	if($this->token($_POST['id'],$_POST['token'])==='success'){
	    	// dump($_POST);
	    	//用户名是否存在判断
	    	$users = \think\Db::name('users')->where('loginName|userPhone',$_POST['target'])->find();
	    	if($users){
	    		//本人
	    		$user = \think\Db::name('users')->where('userId',(int)$_POST['id'])->find();
	    		if($user['imazamox_number']-$_POST['amount']>=0){
	    			// 启动事物
	    			//修改自己金豆信息
	    			\think\Db::startTrans();
	                try{
		    			$me = \think\Db::name('users')
		    			->where('userId',(int)$_POST['id'])
		    			->update(['imazamox_number'=>$user['imazamox_number']-$_POST['amount']]);

		    			$mem = \think\Db::name('users')
		    			->where('loginName|userPhone',$_POST['target'])
		    			->update(['imazamox_number'=>$users['imazamox_number']+$_POST['amount']]);
		    			// 提交事务
		                \think\Db::commit();
	    			} catch (\Exception $e) {
	                    // 回滚事务
	                    \think\Db::rollback();
	                }
	    			if($me==1 && $mem==1){
	    				$tradeNo = $this->makeSn();
	    				//将信息添加到资金流水表
	    				\think\Db::startTrans();
	                    try{
		                    $data1 = array(
		                   		'targetType'   =>    $user['userType'],
		                    	'targetId'     =>    $user['userId'], 
		                    	'dataId'       =>    12,
		                    	'dataSrc'      =>    '转账',
		                    	'moneyType'    =>    0,
		                    	'money'        =>    $_POST['amount'],
		                    	'payName'      =>    $users['loginName'],
		                    	'tradeNo'      =>    $tradeNo,
		                    	'payType'      =>    0,
		                    	'dataFlag'     =>    1,
		                    	'createTime'   =>    date('Y-m-d H:i:s'),
		                    	'remark'       =>    htmlspecialchars($_POST['desc']),
		                    	'endTime'      =>    date('Y-m-d H:i:s'));
		                	\think\Db::name('log_moneys')->insert($data1);	    		
		                    //将信息添加到资金流水表
		                    $in = array(
		                    	'targetType'   =>    $users['userType'], 
		                    	'targetId' 	   =>    $users['userId'], 
		                    	'dataId'       =>    13, 
		                    	'dataSrc'      =>    '转账', 
		                    	'moneyType'    =>    1, 
		                    	'money'        =>    $_POST['amount'], 
		                    	'payName'      =>    $user['loginName'],
		                    	'tradeNo'      =>    $tradeNo, 
		                    	'payType'      =>    0, 
		                    	'dataFlag'     =>    1, 
		                    	'createTime'   =>    date('Y-m-d H:i:s'), 
		                    	'remark'       =>    htmlspecialchars($_POST['desc']),
		                   		'endTime'      =>    date('Y-m-d H:i:s'));
		                        \think\Db::name('log_moneys')->insert($in);

			                    //将信息添加到转账表里面
			                    $tradeNo = $this->makeSn();
			                    $da = array(//支出
			                    'targetType'   =>    $user['userType'], 
			                    'targetId'     =>    $user['userId'],  
			                    'moneyType'    =>    0, 
			                    'money'        =>    $_POST['amount'], 
			                    'payName'      =>    $users['loginName'],
			                    'tradeNo'      =>    $tradeNo, 
			                    'payType'      =>    0, 
			                    'imazamox'     =>    1,
			                    'dataFlag'     =>    1, 
			                    'createTime'   =>    date('Y-m-d H:i:s'), 
			                    'remark'       =>    htmlspecialchars($_POST['desc']));

		                     // dump($da);die;
			                     $da1 = array(//收入
			                    'targetType'   =>    $users['userType'], 
			                    'targetId'     =>    $users['userId'], 
			                    'moneyType'    =>    1, 
			                    'money'        =>    $_POST['amount'], 
			                    'payName'      =>    $user['loginName'],
			                    'tradeNo'      =>    $tradeNo, 
			                    'payType'      =>    0, 
			                    'imazamox'     =>    1,
			                    'dataFlag'     =>    1, 
			                    'createTime'   =>    date('Y-m-d H:i:s'), 
			                    'remark'       =>    htmlspecialchars($_POST['desc']));

		                        \think\Db::name('transfer')->insert($da);
		                        \think\Db::name('transfer')->insert($da1);

		                	\think\Db::commit();
	                    } catch (\Exception $e) {
	                        // 回滚事务
	                        \think\Db::rollback();
	                    }
		                return json_encode(array('info'=>'success','resultcode'=>$s->imazamox_success()),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	    			}else{
	    				//将数据添加到资金流水//失败数据
		   				$in = array(
		                    	'targetType' =>   $users['userType'], 
		                    	'targetId' 	 =>   $users['userId'], 
		                    	'dataId'     =>   13, 
		                    	'dataSrc'    =>   '转账', 
		                    	'moneyType'  =>   1, 
		                    	'money'      =>   $_POST['amount'], 
		                    	'payName'    =>   $user['loginName'],
		                    	'tradeNo'    =>   $tradeNo, 
		                    	'payType'    =>   0, 
		                    	'dataFlag'   =>   0, 
		                    	'createTime' =>   date('Y-m-d H:i:s'), 
		                    	'remark' 	 =>   htmlspecialchars($_POST['desc']),
		                   		'endTime'    =>   date('Y-m-d H:i:s'));
		                        \think\Db::name('log_moneys')->insert($in);
		                return json_encode(array('info'=>'error','resultcode'=>$s->imazamox_error()),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
	    			}
	    		}else{
	    			//将数据添加到资金流水//失败数据
		   				$in = array(
		                    	'targetType' =>   $users['userType'], 
		                    	'targetId' 	 =>   $users['userId'], 
		                    	'dataId'     =>   13, 
		                    	'dataSrc'    =>   '转账', 
		                    	'moneyType'  =>   1, 
		                    	'money'      =>   $_POST['amount'], 
		                    	'payName'    =>   $user['loginName'],
		                    	'tradeNo'    =>   $tradeNo, 
		                    	'payType'    =>   0, 
		                    	'dataFlag'   =>   0, 
		                    	'createTime' =>   date('Y-m-d H:i:s'), 
		                    	'remark' 	 =>   htmlspecialchars($_POST['desc']),
		                   		'endTime'    =>   date('Y-m-d H:i:s'));
		                        \think\Db::name('log_moneys')->insert($in);
		            return json_encode(array('info'=>'error','resultcode'=>$s->imazamox_balance()),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
	    		}
	    	}else{
	    		//将数据添加到资金流水//失败数据
		   				$in = array(
		                    	'targetType' =>   $users['userType'], 
		                    	'targetId' 	 =>   $users['userId'], 
		                    	'dataId'     =>   13, 
		                    	'dataSrc'    =>   '转账', 
		                    	'moneyType'  =>   1, 
		                    	'money'      =>   $_POST['amount'], 
		                    	'payName'    =>   $user['loginName'],
		                    	'tradeNo'    =>   $tradeNo, 
		                    	'payType'    =>   0, 
		                    	'dataFlag'   =>   0, 
		                    	'createTime' =>   date('Y-m-d H:i:s'), 
		                    	'remark' 	 =>   htmlspecialchars($_POST['desc']),
		                   		'endTime'    =>   date('Y-m-d H:i:s'));
		                        \think\Db::name('log_moneys')->insert($in);
	    		return json_encode(array('info'=>'error','resultcode'=>$s->imazamox_name()),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	    	}
	    }else{
	    	return json_encode(array('info'=>'error','resultcode'=>$s->token_ne()),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	    }

	}

	//收金豆消息发送
	public function imazamox_new(){
		$s = new S();
		if($this->token($_POST['id'],$_POST['token'])==='success'){
				$users = \think\Db::name('users')->where('userId',(int)$_POST['id'])->find();
				// dump($users);die;
				if($_POST['target'] != $users['loginName'] && $_POST['target'] != $users['userPhone']) {
	            if (is_numeric($_POST['amount']) && $_POST['amount'] >= 0.01) {
	                //获取转账用户的信息
	                $user = \think\Db::name('users')->where('loginName|userPhone',$_POST['target'])->find();
	                if($user) {
	                    $tradeNo = $this->makeSn();
	                    // 启动事务
	                    \think\Db::startTrans();
	                    try{
	                        $data['msgType']       =    2;
	                        $data['receiveUserId'] =    $user['userId'];
	                        $data['sendUserId']    =    $users['userId'];
	                        $data['msgTitle']      =    '收款';
	                        $data['msgContent']    =    '用户' . $users['loginName'] . '向你收款' . $_POST['amount'] . '金豆，说明：' . htmlspecialchars($_POST['desc']);
	                        $data['amount']        =    $_POST['amount'];
	                        $data['tradeNo']       =    $tradeNo;
	                        $data['dataFlag']      =    1;//成功1 0失败
	                        $data['msgDesc']       =    htmlspecialchars($_POST['desc']);
	                        $data['createTime']    =    date('Y-m-d H:i:s');
	                        $news = \think\Db::name('messages')->insert($data);
	                        // dump($news);die;
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
	                        $data1 = array(
	                        	'targetType' =>   $user['userType'], 
	                        	'targetId'   =>   $user['userId'], 
	                        	'dataId'     =>   4, 
	                        	'dataSrc'    =>   '转账', 
	                        	'moneyType'  =>   0, 
	                        	'money'      =>   $_POST['amount'], 
	                        	'payName'    =>   $users['loginName'],
	                        	'tradeNo'    =>   $tradeNo, 
	                        	'payType'    =>   0, 
	                        	'dataFlag'   =>   2, 
	                        	'createTime' =>   date('Y-m-d H:i:s'), 
	                        	'remark'     =>   htmlspecialchars($_POST['desc']),
	                        	'endTime'    =>   date('Y-m-d H:i:s')
	                        	);
	                        \think\Db::name('log_moneys')->insert($data1);
	                        //将信息添加到资金流水表
	                        $in = array(
	                        	'targetType' =>   $users['userType'], 
	                        	'targetId'   =>   $users['userId'], 
	                        	'dataId'     =>   5, 
	                        	'dataSrc'    =>   '转账', 
	                        	'moneyType'  =>   1, 
	                        	'money'      =>   $_POST['amount'], 
	                        	'payName'    =>   $user['loginName'],
	                        	'tradeNo'    =>   $tradeNo, 
	                        	'payType'    =>   0, 
	                        	'dataFlag'   =>   2, 
	                        	'createTime' =>   date('Y-m-d H:i:s'), 
	                        	'remark'     =>   htmlspecialchars($_POST['desc']),
	                        	'endTime'    =>   date('Y-m-d H:i:s'));
	                        \think\Db::name('log_moneys')->insert($in);
	                        // 提交事务
	                        \think\Db::commit();
	                    } catch (\Exception $e) {
	                        // 回滚事务
	                        \think\Db::rollback();
	                    }
							return json_encode(array('resultcode'=>$s->new_ok(),'info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

	                    } else {
	                    	$data['msgType']       =    2;
	                        $data['receiveUserId'] =    $user['userId'];
	                        $data['sendUserId']    =    $users['userId'];
	                        $data['msgTitle']      =    '收款';
	                        $data['msgContent']    =    '用户' . $users['loginName'] . '向你收款' . $_POST['amount'] . '金豆，说明：' . htmlspecialchars($_POST['desc']);
	                        $data['amount']        =    $_POST['amount'];
	                        $data['tradeNo']       =    $tradeNo;
	                        $data['dataFlag']      =    0;//成功1 0失败
	                        $data['msgDesc']       =    htmlspecialchars($_POST['desc']);
	                        $data['createTime']    =    date('Y-m-d H:i:s');
	                        $news = \think\Db::name('messages')->insert($data);

	                        return json_encode(array('resultcode'=>$s->new_fail(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	                    }
	                } else {
	                		$data['msgType']       =    2;
	                        $data['receiveUserId'] =    $user['userId'];
	                        $data['sendUserId']    =    $users['userId'];
	                        $data['msgTitle']      =    '收款';
	                        $data['msgContent']    =    '用户' . $users['loginName'] . '向你收款' . $_POST['amount'] . '金豆，说明：' . htmlspecialchars($_POST['desc']);
	                        $data['amount']        =    $_POST['amount'];
	                        $data['tradeNo']       =    $tradeNo;
	                        $data['dataFlag']      =    0;//成功1 0失败
	                        $data['msgDesc']       =    htmlspecialchars($_POST['desc']);
	                        $data['createTime']    =    date('Y-m-d H:i:s');
	                        $news = \think\Db::name('messages')->insert($data);
	                    return json_encode(array('resultcode'=>$s->new_name(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	                }
	            } else {
	            			$data['msgType']       =    2;
	                        $data['receiveUserId'] =    $user['userId'];
	                        $data['sendUserId']    =    $users['userId'];
	                        $data['msgTitle']      =    '收款';
	                        $data['msgContent']    =    '用户' . $users['loginName'] . '向你收款' . $_POST['amount'] . '金豆，说明：' . htmlspecialchars($_POST['desc']);
	                        $data['amount']        =    $_POST['amount'];
	                        $data['tradeNo']       =    $tradeNo;
	                        $data['dataFlag']      =    0;//成功1 0失败
	                        $data['msgDesc']       =    htmlspecialchars($_POST['desc']);
	                        $data['createTime']    =    date('Y-m-d H:i:s');
	                        $news = \think\Db::name('messages')->insert($data);
	                return json_encode(array('resultcode'=>$s->new_money(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	            }
	        } else {
	        				$data['msgType']       =    2;
	                        $data['receiveUserId'] =    $user['userId'];
	                        $data['sendUserId']    =    $users['userId'];
	                        $data['msgTitle']      =    '收款';
	                        $data['msgContent']    =    '用户' . $users['loginName'] . '向你收款' . $_POST['amount'] . '金豆，说明：' . htmlspecialchars($_POST['desc']);
	                        $data['amount']        =    $_POST['amount'];
	                        $data['tradeNo']       =    $tradeNo;
	                        $data['dataFlag']      =    0;//成功1 0失败
	                        $data['msgDesc']       =    htmlspecialchars($_POST['desc']);
	                        $data['createTime']    =    date('Y-m-d H:i:s');
	                        $news = \think\Db::name('messages')->insert($data);
	            return json_encode(array('resultcode'=>$s->new_own(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	        }
		}else{
			return json_encode(array('data'=>'null','resultcode'=>$s->token_ne(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		}
	}


	//金豆充值 钱袋子充金豆
	public function imazamox_Recharge(){
		$s = new S();
		// dump($_POST);
		if ($this->token($_POST['id'],$_POST['token'])==='success') {
			$user = \think\Db::name('users')->field('userMoney,imazamox_number,userId,userType,loginName')->where('userId',(int)$_POST['id'])->find();
			if(is_numeric($_POST['number'])){
				// dump($user['userMoney']);die;
				if($user['userMoney'] > $_POST['number']){
					$users = \think\Db::name('users')->where('userId',(int)$_POST['id'])->update([
						'userMoney'=>$user['userMoney']-$_POST['number'],
						'imazamox_number'=>$user['imazamox_number']+$_POST['number'],
						]);
					if($users){
						$data1 = array(
		                   		'targetType' =>   $user['userType'],
		                    	'targetId'   =>   $user['userId'], 
		                    	'dataId'     =>   12,
		                    	'dataSrc'    =>   '金豆充值',
		                    	'moneyType'  =>   0,
		                    	'money'      =>   $_POST['number'],
		                    	'payName'    =>   $users['loginName'],
		                    	'tradeNo'    =>   $this->makeSn(),
		                    	'payType'    =>   0,
		                    	'dataFlag'   =>   1,
		                    	'createTime' =>   date('Y-m-d H:i:s'),
		                    	'remark'     =>   '钱袋子');
		                		\think\Db::name('log_moneys')->insert($data1);

		                		$im = \think\Db::name('imazamox_flow_log')->where('user_id',(int)$_POST['id'])->find();

		                		//将数据添加到金豆变更表
								$insert['user_id']         =  $user['userId'];
								$insert['imazamox_sn']     =  $this->makeSn();
								$insert['imazamox_type']   =  '充值';
								$insert['imazamox_number'] =  $im['imazamox_number'];
								$insert['imazamox_change'] =  $im['imazamox_number'];
								$insert['is_recharge']     =  1;
								$insert['imazamox_desc']   =  '钱袋子';
								$insert['add_time']        =  date('Y-m-d H:i:s');
								$in = \think\Db::name('imazamox_flow_log')->insert($insert);

								// 将信息添加到充值表
								$arr['imr_sn']             =   $insert['imazamox_sn'];
								$arr['imr_user_id']        =   $user['userId'];
								$arr['imr_amount']         =   $insert['imazamox_number'];
								$arr['imr_payment_code']   =   'purse';
								$arr['imr_payment_name']   =   '钱袋子';
								$arr['imr_add_time']       =   date('Y-m-d H:i:s');
								\think\Db::name('im_recharge')->insert($arr);

						return json_encode(array('resultcode'=>$s->imazamox_qdcz(),'info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
					}else{
						//失败信息添加
						$data1 = array(
		                   		'targetType' =>   $user['userType'],
		                    	'targetId'   =>   $user['userId'], 
		                    	'dataId'     =>   12,
		                    	'dataSrc'    =>   '金豆充值',
		                    	'moneyType'  =>   0,
		                    	'money'      =>   $_POST['number'],
		                    	'payName'    =>   $users['loginName'],
		                    	'tradeNo'    =>   $this->makeSn(),
		                    	'payType'    =>   0,
		                    	'dataFlag'   =>   0,
		                    	'createTime' =>   date('Y-m-d H:i:s'),
		                    	'remark'     =>   '钱袋子');
		                		\think\Db::name('log_moneys')->insert($data1);
						return json_encode(array('resultcode'=>$s->imazamox_qdbz(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
					}
				}else{
					//失败信息添加
						$data1 = array(
		                   		'targetType' =>   $user['userType'],
		                    	'targetId'   =>   $user['userId'], 
		                    	'dataId'     =>   12,
		                    	'dataSrc'    =>   '金豆充值',
		                    	'moneyType'  =>   0,
		                    	'money'      =>   $_POST['number'],
		                    	'payName'    =>   $users['loginName'],
		                    	'tradeNo'    =>   $this->makeSn(),
		                    	'payType'    =>   0,
		                    	'dataFlag'   =>   0,
		                    	'createTime' =>   date('Y-m-d H:i:s'),
		                    	'remark'     =>   '钱袋子');
		                		\think\Db::name('log_moneys')->insert($data1);
					return json_encode(array('data'=>'null','resultcode'=>$s->imazamox_qdbz(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
				}	
			}else{
						//失败信息添加
						$data1 = array(
		                   		'targetType' =>   $user['userType'],
		                    	'targetId'   =>   $user['userId'], 
		                    	'dataId'     =>   12,
		                    	'dataSrc'    =>   '金豆充值',
		                    	'moneyType'  =>   0,
		                    	'money'      =>   $_POST['number'],
		                    	'payName'    =>   $users['loginName'],
		                    	'tradeNo'    =>   $this->makeSn(),
		                    	'payType'    =>   0,
		                    	'dataFlag'   =>   0,
		                    	'createTime' =>   date('Y-m-d H:i:s'),
		                    	'remark'     =>   '钱袋子');
		                		\think\Db::name('log_moneys')->insert($data1);
				return json_encode(array('data'=>'null','resultcode'=>$s->imazamox_qd_no(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
			}
		}else{
			return json_encode(array('data'=>'null','resultcode'=>$s->token_ne(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

		}
	}




	 //金豆提现 提现到钱袋
    public function Cash_in_cash(){
    	$s = new S();
    	$make = $this->makeSn();
        //获取当前用户的信息
        if($this->token($_POST['id'],$_POST['token'])==='success'){
        	$user=\think\Db::name('users')->where('userId',(int)$_POST['id'])->find();
        	//参数判断
        	if(is_numeric($_POST['amount'])){
        		if($user['userMoney']>=$_POST['amount']){
	        		//修改钱袋和金豆的钱
		        	$m = \think\Db::name('users')->where('userId',(int)$_POST['id'])->update([
		        		'userMoney'=>$user['userMoney']+$_POST['amount'],
		        		'imazamox_number'=>$user['imazamox_number']-$_POST['amount']
		        		]);

		        	//流水表记录
		        	$data1 = array(
		                   		'targetType' =>   $user['userType'],
		                    	'targetId'   =>   $user['userId'], 
		                    	'dataId'     =>   11,
		                    	'dataSrc'    =>   '金豆提钱袋',
		                    	'moneyType'  =>   0,
		                    	'money'      =>   $_POST['amount'],
		                    	'payName'    =>   $user['loginName'],
		                    	'tradeNo'    =>   $make,
		                    	'payType'    =>   0,
		                    	'dataFlag'   =>   1,
		                    	'createTime' =>   date('Y-m-d H:i:s'),
		                    	'remark'     =>   null
		                    	);
		                		\think\Db::name('log_moneys')->insert($data1);

		            // $data1 = array(
		            //        		'targetType' =>   $user['userType'],
		            //         	'targetId'   =>   $user['userId'], 
		            //         	'dataId'     =>   11,
		            //         	'dataSrc'    =>   '金豆提钱袋',
		            //         	'moneyType'  =>   0,
		            //         	'money'      =>   $_POST['amount'],
		            //         	'payName'    =>   $user['loginName'],
		            //         	'tradeNo'    =>   $make,
		            //         	'payType'    =>   0,
		            //         	'dataFlag'   =>   1,
		            //         	'createTime' =>   date('Y-m-d H:i:s'),
		            //         	'remark'     =>   null
		            //         	);
		            //     		\think\Db::name('log_moneys')->insert($data1);    		

		            $da = array(//金豆转钱袋
			                     'targetType'  =>   $user['userType'], 
			                    'targetId'     =>    $user['userId'], 
			                    'moneyType'    =>    1, 
			                    'money'        =>    $_POST['amount'], 
			                    'payName'      =>    $user['loginName'],
			                    'tradeNo'      =>    $make, 
			                    'payType'      =>    0, 
			                    'imazamox'     =>    2,
			                    'dataFlag'     =>    1, 
			                    'createTime'   =>    date('Y-m-d H:i:s'), 
			                    'remark'       =>    '');
		                        \think\Db::name('transfer')->insert($da);

					            //金豆变更表
				          		$insert['user_id']         =   $user['userId'];
			                    $insert['imazamox_sn']     =   $make;
			                    $insert['imazamox_type']   =   '转钱袋';
			                    $insert['imazamox_number'] =   $_POST['amount'];
			                    $insert['imazamox_change'] =   $_POST['amount'];
			                    $insert['payName']         =   $user['loginName'];
			                    $insert['is_recharge']     =   1;
			                    $insert['imazamox_desc']   =   '金豆转钱袋';
			                    $insert['add_time']        =   date('Y-m-d H:i:s');
			                    $in = \think\Db::name('imazamox_flow_log')->insert($insert);
		        	if($m){
    					return json_encode(array('resultcode'=>$s->transfer_purse_ok(),'info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
		        	}else{
		        				//流水表记录//失败记录
		        				$data1 = array(
		                   		'targetType' =>   $user['userType'],
		                    	'targetId'   =>   $user['userId'], 
		                    	'dataId'     =>   11,
		                    	'dataSrc'    =>   '金豆提钱袋',
		                    	'moneyType'  =>   0,
		                    	'money'      =>   $_POST['amount'],
		                    	'payName'    =>   $user['loginName'],
		                    	'tradeNo'    =>   $make,
		                    	'payType'    =>   0,
		                    	'dataFlag'   =>   0,
		                    	'createTime' =>   date('Y-m-d H:i:s'),
		                    	'remark'     =>   null
		                    	);
		                		\think\Db::name('log_moneys')->insert($data1);
    					return json_encode(array('resultcode'=>$s->transfer_purse_fail(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
		        	}
	        	}else{
	        					//流水表记录//失败记录
		        				$data1 = array(
		                   		'targetType' =>   $user['userType'],
		                    	'targetId'   =>   $user['userId'], 
		                    	'dataId'     =>   11,
		                    	'dataSrc'    =>   '金豆提钱袋',
		                    	'moneyType'  =>   0,
		                    	'money'      =>   $_POST['amount'],
		                    	'payName'    =>   $user['loginName'],
		                    	'tradeNo'    =>   $make,
		                    	'payType'    =>   0,
		                    	'dataFlag'   =>   0,
		                    	'createTime' =>   date('Y-m-d H:i:s'),
		                    	'remark'     =>   null
		                    	);
		                		\think\Db::name('log_moneys')->insert($data1);
    				return json_encode(array('resultcode'=>$s->transfer_purse_monery(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
	        	}
        	}else{
        						//流水表记录//失败记录
		        				$data1 = array(
		                   		'targetType' =>   $user['userType'],
		                    	'targetId'   =>   $user['userId'], 
		                    	'dataId'     =>   11,
		                    	'dataSrc'    =>   '金豆提钱袋',
		                    	'moneyType'  =>   0,
		                    	'money'      =>   $_POST['amount'],
		                    	'payName'    =>   $user['loginName'],
		                    	'tradeNo'    =>   $make,
		                    	'payType'    =>   0,
		                    	'dataFlag'   =>   0,
		                    	'createTime' =>   date('Y-m-d H:i:s'),
		                    	'remark'     =>   null
		                    	);
		                		\think\Db::name('log_moneys')->insert($data1);
        		return json_encode(array('resultcode'=>$s->transfer_purse_jine(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);        	
        	}
    	}else{
    		return json_encode(array('resultcode'=>$s->token_ne(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
   	 	}
        
    }




   //还款后的详情
   public function repayment_details(){
   		$s = new S();
   		if($this->token($_POST['id'],$_POST['token'])==='success'){
   			$al=\think\Db::name('log_moneys')->field('createTime,tradeNo,money,payType,dataFlag')->where(['targetId'=>(int)$_POST['id'],'dataSrc'=>'贷款'])->order('id desc')->find();
   			if($al){
				return json_encode(array('data'=>$al,'info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
   			}else{
				return json_encode(array('data'=>null,'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
   			}
   		}else{
	        return json_encode(array('resultcode'=>$s->Withdrawals_error(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);    
   		}
   }






   //删除log_money
   // public function delete(){
   // 		if($this->token($_POST['id'],$_POST['token'])==='success'){
   // 			$del = \think\Db::name('log_moneys')->where('id',(int)$_POST['delid'])->delete();

   // 		}
   // }
   




	//金豆钱袋数量
	public function purse_imazamox(){
		$s = new S();
		if($this->token($_POST['id'],$_POST['token'])==='success'){
			$user = \think\Db::name('users')->field('userMoney,imazamox_number')->where('userId',(int)$_POST['id'])->find();
			if($user){
				return json_encode(array('data'=>$user,'info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
			}else{
				return json_encode(array('data'=>null,'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
			}

		}else{
	    	return json_encode(array('data'=>'null','resultcode'=>$s->token_ne(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		}

	}


		//流水号生成
    public function makeSn()
	{
		return date('YmdHis')
		. sprintf('%03d', (float)microtime() * 1000)
		. session('WST_USER.userId');

	}

	//支付方式
	public function payments(){
		$s = new S();
		if($this->token($_POST['id'],$_POST['token'])==='success'){
		$pay = \think\Db::name('payments')->field('payName,payDesc,payOrder')->where('payOrder','in','4,5')->where('enabled',1)->select();
		$user = \think\Db::name('users')->field('userMoney,imazamox_number')->where('userId',(int)$_POST['id'])->find();
		// dump($pay);
		// echo json($pay);
			if($pay && $user){
				// echo '<pre>';
				// $pay['money']=$user;
				return json_encode(array('data'=>$pay,'info'=>'success','money'=>$user),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
				// echo '</pre>';
			}else{
				return json_encode(array('data'=>'','info'=>'error','money'=>''),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			}
		
		}else{
	    	return json_encode(array('data'=>'null','resultcode'=>$s->token_ne(),'resule'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		}
	}

}

 