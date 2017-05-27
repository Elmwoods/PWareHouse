<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:49:"/mnt/data/www/wstmart/vvoff/view/index/p_new.html";i:1495845069;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>京歌科技 全球货币分流技术缔造者</title>
		<link rel="icon" href="__VVOFF__/img/1.ico" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="__VVOFF__/css/reset.css" />
	</head>
	<script>
		var phoneWidth = parseInt(window.screen.width);
		var phoneHeight = parseInt(window.screen.height);
		var phoneScale = phoneWidth/640;
		
		var ua = navigator.userAgent;
		if (/Android (\d+\.\d+)/.test(ua)){
			var version = parseFloat(RegExp.$1);
			// andriod 2.3
			if(version>2.3){
				document.write('<meta name="viewport" content="width=640, minimum-scale = '+phoneScale+', maximum-scale = '+phoneScale+', target-densitydpi=device-dpi">');
			// andriod 2.3以上
			}else{
				document.write('<meta name="viewport" content="width=640, target-densitydpi=device-dpi">');
			}
			// 其他系统
		} else {
			document.write('<meta name="viewport" content="width=640, user-scalable=no, target-densitydpi=device-dpi">');
		}
	</script>
	<style type="text/css">	
		body {
			background: #000;
		}
		.p_top {
			width: 640px;
			height: 50px;
			background: #111111;
		}
		.p_top .p_logo {
			float: left;
			padding-left: 80px;
			padding-top: 10px;
		}
		
		.p_top .p_list {
			float: right;
			padding-right: 37px;
			padding-top: 10px;
		}
		.p_top .p_list li {
			float: left;
			padding: 0 9px;
			line-height: 30px;
			line-height: 30px;
		}
		.p_top .p_list a {
			float: left;
			font-size: 9px;
			color: #fff;
		}
		.p_top .p_list a:hover {
			border-bottom: 3px solid #fff;
		}
		.banner {
			width: 640px;
			height: 80px;
			background: url(/public/img/phonebanner.jpg) no-repeat center center;
		}
		.main {
			width: 640px;
			background: #1b1b1b;
			padding-bottom: 51px;
			
		}
		.main .main_top {
			height: 51px;
			width: 640px;
		}
		.main .main_center {
			width: 540px;
			margin: 0 auto;
			border: 1px solid #a2a2a2;
		}
		.main .main_center h2 {
			margin-top: 30px;
			color: #ffffff;
			font-size: 22px;
			font-weight: 500;
			text-align: center;
		}
		.main .main_center .main_list {
			height: 26px;
			line-height: 26px;
			border-bottom: 1px solid #303030;
			width: 470px;
			margin: 0 auto;
			margin-top: 10px;
		}
		.main .main_center .main_list .left {
			float: left;
		}
		.main .main_center .main_list .right {
			float: right;
		}
		.main .main_center .main_main {
			width: 470px;
			margin: 30px 30px;
			/*padding-bottom: 100px;*/
		}
		.main .main_center .main_main span {
			/*color: #fff;*/
		}
		.main .main_center .main_main h3 {
			font-size: 18px;
			font-weight: 500;
			color: #fff;
			margin: 10px 0;
		}
		.overs {
			width: 640px;
			height: 150px;
			background: #000;
		}
		.overs .over_box {
			width: 640px;
			margin: 0 auto;
		}
		.over_box .over_img {
			margin-left: 150px;
			margin-top: 40px;
			float: left;
		}
		.over_box img {
			width: 100px;
			height: 100px;
		}
		.over_txt {
			float: left;
			font-size: 12px;
			color: #fff;
			line-height: 30px;
			margin-top: 30px;
			margin-left: 20px;
		}
		

	</style>
	<body>
		<div class="p_top">
			<div class="p_logo">
				<a href="#15"><img src="__VVOFF__/img/logo1.png"/></a>
			</div>
			<div class="p_list">
				<ul>
					<li><a href="index.html#08">我们</a></li>
					<li><a href="index.html#09">VV聊天</a></li>
					<li><a href="index.html#10">钱袋子</a></li>
					<li><a href="index.html#11">金购商城</a></li>
					<li><a href="index.html#12">京歌未来</a></li>
					<li><a href="index.html#13">团队</a></li>
					<li><a href="index.html#14">新闻</a></li>
					<li><a href="<?php echo url("","",true,false);?>">ENGLISH</a></li>
				</ul>
			</div>
		</div>
		<div class="banner"></div>
		<div class="main">
			<div class="main_top"></div>
			<div class="main_center">
				<h2><?php echo $art['art_name']; ?></h2>
				<div class="main_list">
					<span class="left"><?php echo $art['art_from']; ?></span>
					<span class="right"><?php echo $art['art_addtime']; ?></span>
				</div>
				<div class="main_main">
					<?php echo $art['art_content']; ?>
				</div>
			</div>
		</div>
	</body>
</html>
