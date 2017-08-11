<?php 
namespace wstmart\common\model;

	class Sdk extends Base {
		//可以贷款
		public function Sure(){
			return 10000;
		} 
		//不可以贷款
		public function Maynot(){
			return 10001;
		}
		//积分不足
		public function Jf_enough(){
			return 10002;
		}
		//贷款成功
		public function Loan_success(){
			return 20000;
		}

		//贷款失败
		public function Loan_error(){
			return 20001;
		}
		//贷款额度不足
		public function Loan_quota(){
			return 20002;
		}
		//贷款次数上限
		public function Loan_Upperlimit(){
			return 20003;
		}
		//还款成功
		public function repayment_success(){
			return 30000;
		}
		//还款失败
		public function repayment_error(){
			return 30001;
		}
		//超出还款额度
		public function repayment_exceed(){
			return 30002;
		}
		//金额格式错误
		public function format_error(){
			return 30003;
		}
		//转账状态
		//成功
		public function transfer_purse_ok(){
			return 40000;
		}
		//失败//转账失败
		public function transfer_purse_fail(){
			return 40001;
		}
		//余额不足
		public function transfer_purse_monery(){
			return 40002;
		}
		//不能给自己转账
		public function transfer_purse_own(){
			return 40003;
		}
		//收款转账唯一标识 交易已经完成
		public function Receivables_purese_no(){
			return 40004;
		}	
		//交易过期
		public function Receivables_purese_gq(){
			return 40005;
		}	
		//二维码转账成功
		public function code_purse_ok(){
			return 40006;
		}
		//二维码转账失败
		public function code_purse_no(){
			return 40007;
		}
		//
		//消息发布成功
		public function new_ok(){
			return 50000;
		}
		//消息发布失败
		public function new_fail(){
			return 50001;
		}

		//不能向自己收款
		public function new_own(){
			return 50002;
		}
		//金豆转账
		//成功
		public function imazamox_success(){
			return 60000;
		}
		//失败//金豆转账失败
		public function imazamox_error(){
			return 60001;
		}


		//用户名不存在
		public function name_no(){
			return 60002;
		}
		//金额不能小于0.1
		public function Withdrawals_je(){
			return 60003;
		}

		//钱袋充值金豆成功
		public function imazamox_czpurse_ok(){
			return 60004;
		}
		//钱袋充值金豆失败
		public function imazamox_czpurse_no(){
			return 60005;
		}
		//不能给自己转金豆
		public function imazamox_purse_own(){
			return 60006;
		}
		//金豆余额不足
		public function imazamox_null(){
			return 60007;
		}
		//金豆转账消息已经完成
		public function imazamox_complete(){
			return 60008;
		}
		//金豆转账消息已经过期
		public function imazamox_over(){
			return 60009;
		}
		//金豆转账消息不存在
		public function imazamox_non(){
			return 60011;
		}
		//钱袋充值成功
		public function imazamox_qdcz(){
			return 70000;
		}
		//钱袋充值 失败
		public function imazamox_qd_no(){
			return 70001;
		}

		//钱袋收款成功
		public function receivables_ok(){
			return 70002;
		}
		//钱袋收款失败
		public function receivables_no(){
			return 70003;
		}
		//对方钱袋余额不足
		public function receivables_mq(){
			return 70004;
		}

		//钱带给别人充值金豆 成功
		public function givefriendsRecharge_ok(){
			return 70005;
		}
		// 给好友充值金豆失败
		public function givefriendsRecharge_no(){
			return 70006;
		}
		//支付宝充值 //成功
		public function alipay_ok(){
			return 80000;
		}
		//失败
		public function alipay_no(){
			return 80001;
		}
		//支付宝还款 成功
		public function alipay_hy_ok(){
			return 80002;
		}
		//支付宝还款失败
		public function alipay_hy_no(){
			return 80003;
		}
		//提现成功
		public function Withdrawals_success(){
			return 90000;
		}
		//提现失败
		public function Withdrawals_error(){
			return 90001;
		}

		//金豆提钱袋成功
		public function Cash_ok(){
			return 90002;
		}
		//金豆提钱袋失败
		public function Cash_no(){
			return 90003;
		}
		
		//token不匹配
		public function token_ne(){
			return 101010;
		}
		//id token 不能为空
		public function token_id(){
			return 11111;
		}


	}


 ?>