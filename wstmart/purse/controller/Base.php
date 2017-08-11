<?php
namespace wstmart\purse\controller;
/**
 * ============================================================================
 * WSTMart多用户商城
 * 版权所有 2016-2066 广州商淘信息科技有限公司，并保留所有权利。
 * 官网地址:http://www.wstmart.net
 * 交流社区:http://bbs.shangtaosoft.com
 * 联系QQ:153289970
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！未经本公司授权您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 基础控制器
 */
use think\Controller;
class Base extends Controller {
	public function __construct(){

		$request = request();
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
		
		hook('homeControllerBase');
		//判断用户是否登录
		$userType = -1;
		if((int)session('WST_USER.userId')>0)$userType = 0;
		if((int)session('WST_USER.shopId')>0)$userType = 1;
		$visit = strtolower($request->module()."/".$request->controller()."/".$request->action());
//		dump($visit);die;
		if($visit == 'purse/index/login' || $visit == 'purse/index/check' || $visit == 'purse/index/setqrcode'){
//			exit();
		}else{
			//未登录不允许访问受保护的资源
			if($userType==-1){
				if($request->isAjax()){
					echo json_encode(['status'=>-999,'msg'=>'对不起，您还没有登录，请先登录']);
				}else{
					header("Location:".url('purse/index/login'));
				}
				exit();
			}
		}

		$news_count = \think\Db::name('messages')->where(['receiveUserId'=>session('WST_USER.userId')])->where('msgStatus',0)->where('msgTitle','还款')->where('msgTitle','收款')->count();

		session('NEWS_COUNT',$news_count);
		//获取当前用户别的信息
		$valu = \think\Db::name('users')->where('repayment_date','ELT',date('Y-m-d H:i:s',strtotime("-3 day")))->order('repayment_date desc')->select();

//		dump($valu);
		foreach($valu as $users){
			//判断当前时间和还款日期
			if($users['repayment_date']<=date('Y-m-d H:i:s',strtotime("-3 day")) && $users['residual_repayment']>0) {
				// 启动事务
				\think\Db::startTrans();
				try{
//					dump($users);
					//将信息添加到数据库
					$data['msgType'] = 1;
					$data['receiveUserId'] = $users['userId'];
					$data['sendUserId'] = 0;
					$data['msgContent'] = '您的本期账单￥'.$users['residual_repayment'].'元，还款日：'.$users['repayment_date'].'。即将到期。';
					$data['msgTitle'] = '还款';
					$data['amount'] = $users['residual_repayment'];
					$data['msgDesc'] = '还款';
					$data['createTime'] = date('Y-m-d H:i:s');
					$data['dataFlag'] = 0;
					$data['repaymentDate'] = strtotime($users['repayment_date']);
					$news = \think\Db::name('messages')->where(array('receiveUserId' => $data['receiveUserId'],'repaymentDate'=>$data['repaymentDate']))->find();
//                    var_dump($data);die;
					if($news) {

					} else {

						\think\Db::name('messages')->insert($data);
					}     // 提交事务
					\think\Db::commit();
				} catch (\Exception $e) {
					// 回滚事务
					\think\Db::rollback();
				}
			}
			//判断当前时间和还款日期,如果超出还款日期两天就减少信用分
			if($users['repayment_date']<=date('Y-m-d H:i:s',strtotime("+2 day")) && $users['residual_repayment']>0) {
				// 启动事务
				\think\Db::startTrans();
				try{
					//修改信用分
					if($users['overdue']==0){

						$score = $users['userScore']-floor($users['userScore']*0.05);
						session('WST_USER.userScore',$score);
						\think\Db::name('users')->where(array('userId' => $users['userId']))->update(['userScore'=>$score,'overdue'=>1]);
						//将信息添加到数据库
						$data['msgType'] = 1;
						$data['receiveUserId'] = $users['userId'];
						$data['sendUserId'] = 0;
						$data['msgContent'] = '您的本期账单￥'.$users['residual_repayment'].'元，还款日：'.$users['repayment_date'].'。已逾期。将按照日息0.05%增长，并减少'.floor($users['userScore']*0.05).'信用分。';
						$data['msgTitle'] = '还款';
						$data['amount'] = $users['residual_repayment'];
						$data['msgDesc'] = '还款超期';
						$data['createTime'] = date('Y-m-d H:i:s');
						$data['dataFlag'] = 0;
						$data['repaymentDate'] = strtotime($users['repayment_date']);
						\think\Db::name('messages')->insert($data);
					}
//					dump($score);die;

					// 提交事务
					\think\Db::commit();
				} catch (\Exception $e) {
					// 回滚事务
					\think\Db::rollback();
				}
			}
			//如果贷款逾期3个月，将用户存入黑名单
			if($users['repayment_date']<=date('Y-m-d H:i:s',strtotime("+3 month")) && $users['residual_repayment']>0) {
//				dump($users);
				\think\Db::name('users')->where('userId',$users['userId'])->update(['blackList'=>1]);
			}

		}
//查看消息表的时间
		$new = \think\Db::name('messages')->where(['dataFlag'=>0])->where('msgTitle','收款')->select();
//		dump($new);
		foreach($new as $value){
//			dump($value);
			$date = time()-strtotime($value['createTime']);
			if($date>=86400 && $value['dataFlag']==0){
				// 启动事务
				\think\Db::startTrans();
				try{
					//将消息表的改为已完成
					\think\Db::name('messages')->where(['id'=>$value['id'],'msgTitle'=>'收款'])->update(['dataFlag'=>1]);
					//获取用户表的信息
					$user = \think\Db::name('users')->where(['userId'=>$value['receiveUserId']])->find();
					//将资金流水表的信息修改为失败
					\think\Db::name('log_moneys')->where(['tradeNo' => $value['tradeNo']])->update(['dataFlag'=>0,'endTime'=>date('Y-m-d H:i:s',strtotime("".$value['createTime']." +1 day"))]);
//					dump($value);
					//想收款人发送信息提醒
					$in['msgType'] = 1;
					$in['receiveUserId'] = $value['sendUserId'];
					$in['sendUserId'] = 0;
					$in['msgTitle'] = '收款';
					$in['msgContent'] = '您向用户'.$user['loginName'].'收款￥'.$value['amount'].',说明：'.$value['msgDesc'].'。对方超过24小时未付款，交易关闭。';
					$in['amount'] = $value['amount'];
					$in['msgDesc'] = $value['msgDesc'];
					$in['tradeNo'] = $value['tradeNo'];
					$in['dataFlag'] = 2;
					$in['createTime'] = date('Y-m-d H:i:s');
//					dump($in);
					\think\Db::name('messages')->insert($in);
//					dump(1);
					// 提交事务
					\think\Db::commit();
				} catch (\Exception $e) {
					// 回滚事务
					\think\Db::rollback();
				}
			}
		}
	}

	protected function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
    	$style = WSTConf('CONF.wstPCStyle')?WSTConf('CONF.wsthomeStyle'):'default';
    	$replace['__STYLE__'] = str_replace('/index.php','',\think\Request::instance()->root()).'/wstmart/purse/view/'.WSTConf('CONF.wsthomeStyle');
        return $this->view->fetch($style."/".$template, $vars, $replace, $config);
    }

	/**
	 * 上传图片
	 */
	public function uploadPic(){
		return WSTUploadPic(0);
	}
	/**
    * 编辑器上传文件
    */
    public function editorUpload(){
           return WSTEditUpload(0);
    }
	
	/**
	 * 获取验证码
	 */
	public function getVerify(){
		WSTVerify();
	}

	/**
	 * 生成编号
	 * @return string
	 */
	public function makeSn()
	{
		return date('YmdHis')
		. sprintf('%03d', (float)microtime() * 1000)
		. session('WST_USER.userId');

	}

}