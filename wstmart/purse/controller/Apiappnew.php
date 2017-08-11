<?php 
namespace wstmart\purse\controller;

use wstmart\common\model\Sdk as S;
use \think\Db;

	class Apiappnew {

		// token 身份验证
		public function token($id,$token) {
			$to = Db::name('user_token')->where('member_id',(int)$id)->order('token_id desc')->find();
			if($to['token']==$token){
				return 'success';
			}else{
				return 'error';
			}
		}
		
		//贷款还款
		public function messagesnew(){
			$s = new S();
			//if(!empty($_POST) && $_POST['tokern'] != null && $_POST['id'] != null ){
				//if($this->token($_POST['id'],$_POST['token'])==='success'){
					// $data = Db::name('log_moneys')
					// ->field('dataSrc,money,moneyType,createTime,endTime')
					// ->where('targetId',$_POST['id'])
					// ->where('dataId','in','8,9')
					// ->select();
					// dump($data);
					$da = Db::name('messages')
					->field('receiveUserId,msgTitle,msgContent,amount,msgDesc,repaymentDate,completeTime,msgStatus,dataFlag,createTime')
					->where('receiveUserId',(int)$_POST['id'])
					->where('msgTitle','in','还款,贷款')
					->paginate(10);
					// dump($da);
					// echo '<pre>';
					return json_encode(array('data'=>$da,'info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
					// echo '</pre>';
				// }else{
					//return json_encode(array('resultcode'=>$s->token_ne(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
				//}
			//}else{
				//return json_encode(array('resultcode'=>$s->token_id(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			// }
			
		}

		//只有贷款还款 详情log_moneys
		public function details(){
			$s = new S();
			// if(!empty($_POST) && $_POST['token'] != null && $_POST['token'] != null){
			// 	if($this->token($_POST['id'],$_POST['token'])==='success'){
					$data = Db::name('log_moneys')->where('targetId',(int)$_POST['id'])->where('dataId','in','8,9')->order('id desc')->paginate(10);
					if($data){
						// echo '<pre>';
						return json_encode(array('data'=>$data,'info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
						// echo '</pre>';
					}else{
						return json_encode(array('data'=>'','info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
					}
			// 	}else{
			// 		return json_encode(array('resultcode'=>$s->token_ne(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			// 	}
			// }else{
			// 		return json_encode(array('resultcode'=>$s->token_id(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			// }
		}

		//转账消息
		public function transferAccountsnew(){
			$s = new S();
			if(!empty($_POST) && $_POST['token'] != null && $_POST['token'] != null){
				if($this->token($_POST['id'],$_POST['token'])==='success'){
					$data = Db::name('messages')
					->field('receiveUserId,msgTitle,msgContent,amount,msgDesc,completeTime,msgStatus,dataFlag,createTime,tradeNo,sendUserId')
					->where(['dataFlag'=>0,'receiveUserId'=>(int)$_POST['id']])
					->where('msgTitle','in','金豆收款,收款')
					->order('id desc')
					->paginate(10);
					foreach ($data as $k => $value) {
						$p=Db::name('users')->where('userId',$value['sendUserId'])->field('userPhoto,userName,loginName,userPhone')->find();
						$value['photo']=$p['userPhoto'];
						$value['userName']=$p['userName'];
						$value['userPhone']=$p['userPhone'];
						$value['loginName']=$p['loginName'];
						$data[$k]=$value;
					}
					if($data){
						return json_encode(['data'=>$data,'info'=>'success'],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
					}else{
						return json_encode(['data'=>'','info'=>'error']);
					}

				}else{
					return json_encode(array('resultcode'=>$s->token_ne(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
				}
			}else{
					return json_encode(array('resultcode'=>$s->token_id(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			}
		}

		//发送者id头像查询
		// public function sendUserIdphoto(){
		// 	$photo=Db::name('users')->where('userId',$_POST[''])
		// }




		//转账消息过期
		public function newsOverdue(){
			// 当前时间
			// $date =  date('Y-m-d H:i:s');
			//所以的未完成消息
			$over = Db::name('messages')->where('dataFlag',0)->select();
			foreach($over as $ke => $v){
				$user=Db::name('users')->where('userId',$v['receiveUserId'])->field('loginName')->find();
				Db::name('messages')->where('id',$v['id'])->update(['dataFlag'=>2]);
			// 	//过期时间
				if(strtotime($v['createTime']) <= time()-86400){
					$new = Db::name('messages')->where('id',$v['id'])->update(['dataFlag'=>2]);
					// $user=Db::name('users')->where('userId',$v['receiveUserId']);
					//想收款人发送信息提醒
					$in['msgType'] = 1;
					$in['receiveUserId'] = $v['receiveUserId'];
					$in['sendUserId'] = 0;
					$in['msgTitle'] = '收款';
					$in['msgContent'] = '您向用户'.$user['loginName'].'收款￥'.$v['amount'].',说明：'.$v['msgDesc'].'。对方超过24小时未付款，交易关闭。';
					$in['amount'] = $v['amount'];
					$in['msgDesc'] = $v['msgDesc'];
					$in['tradeNo'] = $v['tradeNo'];
					$in['dataFlag'] = 1;
					$in['createTime'] = date('Y-m-d H:i:s');
					Db::name('messages')->insert($in);
				}
			}
			// $time = date('Y-m-d H:i:s',time()-86400);
			$overs = Db::name('log_moneys')
			->where('dataFlag',2)
			->where('endTime',null)
			->select();
			foreach($overs as $v){
				if(strtotime($v['createTime'])<=time()-86400){
					Db::name('log_moneys')->where('id',$v['id'])->update(['endTime'=>date('Y-m-d H:i:s'),'dataFlag'=>0]);
				}
			}
		}

		//还款消息定时发送消息
		public function newRepayment(){
			$user = Db::name('users')
			->field('userId,loginName,repayment_date,residual_repayment')
			->where('residual_repayment','>',0)
			->select();
			foreach($user as $v){
				//获取到需要发送的id
				if(strtotime($v['repayment_date']) >= time() - 259200 ){
					//判断消息是否已经发送过
					$mes = Db::name('messages')->where(['repaymentDate'=>$v['repayment_date'],'receiveUserId'=>$v['userId']])->find();
					if($mes == null){//
						//将信息添加到数据库
						$data['msgType'] = 1;
						$data['receiveUserId'] = $v['userId'];
						$data['sendUserId'] = 0;
						$data['msgContent'] = '您的本期账单￥'.$v['residual_repayment'].'元，还款日：'.$v['repayment_date'].'。即将到期。';
						$data['msgTitle'] = '还款通知';
						$data['amount'] = $v['residual_repayment'];
						$data['msgDesc'] = '还款';
						$data['createTime'] = date('Y-m-d H:i:s');
						$data['dataFlag'] = 0;
						$data['repaymentDate'] = $v['repayment_date'];
						Db::name('messages')->insert($data);
					}
				}
			}	
		}
		

		//每一个月删除 已经完成的消息
		public function deleteNew(){
			$del = Db::name('messages')
			->where('dataFlag',1)
			->whereTime('createTime','yesterday')	
			->select();
			dump($del);
		}

		//信用积分
		public function creditScore(){
			$s = new S();
			// if(!empty($_POST) && $_POST['tokern'] != null && $_POST['id'] != null ){
			// 	if($this->token($_POST['id'],$_POST['token'])==='success'){
					$user = Db::name('users')->where('userId',(int)$_POST['id'])->field('userScore')->find();
					if($user){
						if($user['userScore'] >= 1000){
							$user['money'] = 10000;
						}else{
							$user['money'] = 0;
						}
						return json_encode(array('data'=>$user,'info'=>'success'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
					}else{
						return json_encode(array('data'=>'','info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);	
					}

			// 	}else{
			// 		return json_encode(array('resultcode'=>$s->token_ne(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			// 	}
			// }else{
			// 		return json_encode(array('resultcode'=>$s->token_id(),'info'=>'error'),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			// }
		}

		//图片上传
		public function file(){
			// dump($_POST);
			$file = $_FILES['file'];//得到传输的数据
			//得到文件名称
			$name = $file['name'];
			$type = strtolower(substr($name,strrpos($name,'.')+1)); //得到文件类型，并且都转化成小写
			$allow_type = array('jpg','jpeg','gif','png','mp4'); //定义允许上传的类型
			//判断文件类型是否被允许上传
			if(!in_array($type, $allow_type)){
			  //如果不被允许，则直接停止程序运行
			  return ;
			}
			//判断是否是通过HTTP POST上传的
			if(!is_uploaded_file($file['tmp_name'])){
			  //如果不是通过HTTP POST上传的
			  return ;
			}
			// $width	=	imagesx($file);
			// $heighe	=	imagesy($file);
			// echo $width.'<br/>'.$height;die;
			$upload_path = "upload/share/".date('Y-m-d').'/'; //上传文件的存放路径

			if(file_exists($upload_path)){

			}else{
				mkdir($upload_path);
			}
			$url = $upload_path.md5($file['name'].time().rand(4,100)).'.'.$type;
			//开始移动文件到相应的文件夹
			if(move_uploaded_file($file['tmp_name'],$url)){
				$data['uid']=input('post.id');
				$data['url']=$url;
				$data['addtime']=time();
				Db::name('share')->insert($data);
			  return json_encode(['data'=>$url,'info'=>'success'],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			}else{
			  return json_encode(['info'=>'error'],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			}
		}


		//一个星期删除一个文件夹
		public function delete_file(){
			//查询已经过期的链接
			$stare = Db::name('share')->select();
			$times = date('Y-m-d',time()-604800);//7天前的时间
			//删除文件夹
			foreach($stare as $v){
				//获取超过7天的数据并删除
				if(time()-$v['addtime'] >= 604800){
					unlink($v['url']);//删除服务器图片
					//删除消息存在超过7天
					$de = Db::name('share')->where('id',$v['id'])->delete();
				}
			}
			if(file_exists($times)){
			}else{
				rmdir('upload/share/'.$times);
			}
			
		}

		//逾期未还款信用减少
		public function beOverdue(){
			$user = \think\Db::name('users')->where('residual_repayment','>',0)->field('userId,residual_repayment,repayment_date,loan_date,overdue,userScore')->select();
			foreach($user as $v){
				if(strtotime($v['repayment_date'])+259200 <= time() && $v['overdue']==0){//一个月未还
					$score = $v['userScore']-$v['loanAmount']*0.05;
					//将逾期的账户 overdue 改为1 并减少信用分
					$users = \think\Db::name('users')->where('userId',$v['userId'])->update(['overdue'=>1,'userScore'=>$score]);
				}elseif(strtotime($v['repayment_date'])+7776000 <= time() && $v['overdue']==1){//3个月未还 拉黑
					$score = $v['userScore']-$v['loanAmount']*0.05;
					$users = \think\Db::name('users')->where('userId',$v['userId'])->update(['userScore'=>$score,'blackList'=>1]);
				}
			}

		}


	}
	


