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
		
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
		
		hook('homeControllerBase');
		$news_count = \think\Db::name('news')->where(['user_name'=>session('WST_USER.loginName')])->where('state',0)->count();

		session('WST_USER.NEWS_COUNT',$news_count);
		//获取当前用户别的信息
		$users = \think\Db::name('users')->where(array('userId' => session('WST_USER.userId')))->find();
		//判断当前时间和还款日期
		if(strtotime($users['repayment_date'])<=(time()-86400) && $users['residual_repayment']>0) {
			// 启动事务
			\think\Db::startTrans();
			try{
			//将信息添加到数据库
			$data['user_name'] = session('WST_USER.loginName');
			$data['nickname'] = session('WST_USER.userName');
			$data['news_content'] = '您的本期账单￥'.$users['residual_repayment'].'元，还款日：'.$users['repayment_date'].'。即将到期。';
			$data['news_title'] = '还款';
			$data['news_add_time'] = date('Y-m-d H:i:s');
			$data['news_amount'] = $users['residual_repayment'];
			$data['sender_id'] = 0;
			$data['news_desc'] = '还款';
			$data['repayment_date'] = $users['repayment_date'];
			$news = \think\Db::name('news')->where(array('user_name' => $data['user_name'],'repayment_date'=>$data['repayment_date']))->find();
//                    var_dump($data);die;
			if($news) {

			} else {

				\think\Db::name('news')->insert($data);
			}     // 提交事务
				\think\Db::commit();
			} catch (\Exception $e) {
				// 回滚事务
				\think\Db::rollback();
			}
		}
		//查看消息表的时间
		$new = \think\Db::name('news')->select();
		foreach($new as $value){
//			dump($value);
			$date = time()-strtotime($value['news_add_time']);
			if($date>=86400 && $value['is_complete']==0 && $value['news_title']=='收款'){
				// 启动事务
				\think\Db::startTrans();
				try{
				//将消息表的改为已完成
				\think\Db::name('news')->where(['id'=>$value['id'],'news_title'=>'收款'])->update(['is_complete'=>1]);
				//获取用户表的信息
				$user = \think\Db::name('users')->where(['userId'=>$value['sender_id']])->find();
				//将资金流水表的信息修改为失败
				\think\Db::name('log_moneys')->where(['tradeNo' => $value['tradeNo']])->update(['dataFlag'=>0]);
				//想收款人发送信息提醒
				$in['user_name'] = $user['loginName'];
				$in['nickname'] = $user['userName'];
				$in['news_content'] = '您向用户'.$value['user_name'].'收款￥'.$value['news_amount'].',说明：'.$value['news_desc'].'。对方超过24小时未付款，交易关闭。';
				$in['news_title'] = '收款';
				$in['news_add_time'] = date('Y-m-d H:i:s');
				$in['news_amount'] = $value['news_amount'];
				$in['sender_id'] = 0;
				$in['news_desc'] = '收款';
				$in['tradeNo'] = $value['tradeNo'];
				$in['is_complete'] = 1;
				\think\Db::name('news')->insert($in);
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