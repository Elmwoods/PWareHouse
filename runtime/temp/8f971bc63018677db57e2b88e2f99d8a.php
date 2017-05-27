<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:56:"E:\wamp64\wamp\www/wstmart/vvoff\view\login\engjump.html";i:1495763178;}*/ ?>
<!DOCTYPE html>

<html>

	<head>
		<meta charset="UTF-8">
		<title></title>
		<script src="__VVOFF__/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<link rel="stylesheet" type="text/css" href="__VVOFF__/css/Eng_index.css" />
		<link rel="stylesheet" type="text/css" href="__VVOFF__/css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="__VVOFF__/css/reset.css" />
	</head>

	<body>
		<div class="container-fluid menu">
			<div class="img">
				<img src="__VVOFF__/img/touxiang.png" />
			</div>
			<p class="nickname">nickname：<?php echo \think\Session::get('WST_USER.userName'); ?></p>
			<div class="btn_a">
				<button class="btn_lt"><img src="__VVOFF__/images/BUTTON_EINGLISH_VVCHAT.png"/></button>
				<button class="btn_qdz"><img src="__VVOFF__/images/BUTTON_EINGLISH_CASHBAG.png"/></button>
				<button class="btn_sc"><img src="__VVOFF__/images/BUTTON_EINGLISH_JINGOMALL.png" alt="" /></button>
			</div>
			<p class="ready">Hello, has been logged in, the new registered user system automatically assigned nickname, avatar and password, can be modified to personal Center
			</p>
			<p class="ready">Please select the link address and enter the appropriate module
				<span class="time">10</span>s After the clock, the system automatically returned to Beijing Song official website
				<a class="return" href="javascript:viod(0)">Immediate return
				</a>
			</p>
		</div>
		<script type="text/javascript">
			var a = 10;
			st = setInterval(function() {
				a--;
				if(a == 0) {
					clearInterval(st);
					window.location.href = "<?php echo url('vvoff/index/english'); ?>"
				}
				$(".time").html(a);
			}, 1000)
			$(".return").click(function() {
				window.location.href = "<?php echo url('vvoff/index/english'); ?>"
			})
		</script>
	</body>

</html>
