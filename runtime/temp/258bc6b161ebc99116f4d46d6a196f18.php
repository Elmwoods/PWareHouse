<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:48:"/mnt/data/www/wstmart/vvoff/view/login/jump.html";i:1495763153;}*/ ?>
<!DOCTYPE html>

<html>

	<head>
		<meta charset="UTF-8">
		<title></title>
		<script src="__VVOFF__/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<link rel="stylesheet" type="text/css" href="__VVOFF__/css/index.css" />
		<link rel="stylesheet" type="text/css" href="__VVOFF__/css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="__VVOFF__/css/reset.css" />
	</head>

	<body>
		<div class="container-fluid menu">
			<div class="img">
				<img src="__VVOFF__/img/touxiang.png" />
			</div>
			<p class="nickname">昵称：<?php echo \think\Session::get('WST_USER.userName'); ?></p>
			<div class="btn_a">
				<button class="btn_lt"><img src="__VVOFF__/images/button_VV.png"/></button>
				<button class="btn_qdz"><img src="__VVOFF__/images/button_VVPAY.png"/></button>
				<button class="btn_sc"><img src="__VVOFF__/images/button_jingomall.png" alt="" /></button>
			</div>
			<p class="ready">您好，已登录，新注册用户系统自动分配昵称、头像和密码，可到个人中心修改 </p>
			<p class="ready">请选择链接地址进入相应模块 <span class="time">10</span>s 钟后系统自动返回京歌官网
				<a class="return" href="javascript:viod(0)">立即返回</a>
			</p>
		</div>
		<script type="text/javascript">
			var a = 10;
			st = setInterval(function() {
				a--;
				if(a == 0) {
					clearInterval(st);
					window.location.href = "<?php echo url('vvoff/index/index'); ?>"
				}
				$(".time").html(a);
			}, 1000)
			$(".return").click(function(){
				window.location.href = "<?php echo url('vvoff/index/index'); ?>"
			})
		</script>
	</body>

</html>
