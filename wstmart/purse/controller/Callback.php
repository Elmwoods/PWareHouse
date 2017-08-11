<?php 
namespace wstmart\purse\controller;

use \think\Db;

	class Callback extends Base{
		//支付宝异步回调
		public function receive(){
			require_once '/api/alipay-sdk-PHP-20170607114147/aop/AopClient.php';
			$aop = new AopClient;
			$aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuTjQgKPC980aHOPkbJQBAmbGHLwuJ4K3YPStcq1Nvc22plEfy3f5CZ7N19DhQeY+Hu/oNJJrg7KezpeXn8vkyltdgepXNWUl5xWvSezEw1SGY3uO0GpvpeXloEB4w6rQF8k1jP0aUp1eAmHdEKp3UpABaGhhsCzWYc+EG3TSH2284SNJqY6LswND/S9gOMvahIfIlH618T8QmjBJPGnhNXdsqIgLqqHpxJHypVFYc2HYL9TWZ/wUDN4ueoKuptitSBFT2KzZ6/TuK9JZGB9J1jmr1sYTboDh8ZqOeMKaJAYbO9x8eZq16A5HXg7oIPvPeDgQcP3HmGRZlYCbf7jZ1QIDAQAB';
			$flag = $aop->rsaCheckV1($_POST, NULL, "RSA");

			if($flag){
				echo 1;
			}else{
				echo 2;
			}

		}



	}