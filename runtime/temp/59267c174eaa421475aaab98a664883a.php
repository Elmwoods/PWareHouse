<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:48:"/mnt/data/www/wstmart/vvoff/view/index/news.html";i:1495176366;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>京歌科技 全球货币分流技术缔造者</title>
		<link rel="icon" href="img/1.ico" type="__VVOFF__/image/x-icon">
		<link rel="stylesheet" type="text/css" href="__VVOFF__/css/reset.css" />
	</head>
	<style type="text/css">
		.header {
			width:100%;
			height: 120px;
			background: #111;
		}
		.header .header_top {
			width:1200px;
			height:120px;
			line-height:120px;
			margin:0 auto;
		}
		.header .header_top .logo {
			float: right;
			padding-right:18px;
		}
		.header .header_top .list {
			float: right;
			padding-right:59px;
		}
		.header .header_top .list ul {
			float: left;
		}
		.header .header_top .list li {
			float: left;
			margin:0 18px;
		}
		.header .header_top .list a {
			float: left;
			color: #fff;
		}
		.banner {
			width: 100%;
			height: 120px;
			background: url(__VVOFF__/img/pcbanner.jpg) no-repeat center center;
		}
		.main {
			width: 100%;
			background: #0b0b0b;
			padding-bottom: 80px;
			
		}
		.main .main_top {
			height: 80px;
			width: 100%;
		}
		.main .main_center {
			width: 1100px;
			margin: 0 auto;
			border: 1px solid #a2a2a2;
		}
		.main .main_center h2 {
			margin-top: 36px;
			color: #ffffff;
			font-size: 36px;
			font-weight: 500;
			text-align: center;
		}
		.main .main_center .main_list {
			height: 26px;
			line-height: 26px;
			border-bottom: 1px solid #303030;
			width: 900px;
			margin: 0 auto;
			margin-top: 40px;
		}
		.main .main_center .main_list .left {
			float: left;
		}
		.main .main_center .main_list .right {
			float: right;
		}
		.main .main_center .main_main {
			width: 900px;
			margin: 30px 100px;
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
			width: 100%;
			height: 150px;
			background: #000;
		}
		.overs .over_box {
			width: 1200px;
			margin: 0 auto;
		}
		.over_box .over_img {
			margin-left: 150px;
			margin-top: 25px;
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
		<div class="header" id="header">
			<div class="header_top">
				<div class="list">
					<ul>
						<li><a href="index.html#01">京歌介绍</a></li>
						<li><a href="index.html#02">VV聊天</a></li>
						<li><a href="index.html#03">钱袋子</a></li>
						<li><a href="index.html#04">金购商城</a></li>
						<li><a href="index.html#05">京歌未来</a></li>
						<li><a href="index.html#06">团队介绍</a></li>
						<li><a href="index.html#07">京歌新闻</a></li>
						<li><a href="English.html">ENGLISH SITE</a></li>
					</ul>
				</div>
				<div class="logo">
					<img src="__VVOFF__/img/logo.png"/>
				</div>
			</div>
		</div>
		<div class="banner"></div>
		<div class="main">
			<div class="main_top"></div>
			<div class="main_center">
				<h2><?php echo $art[0]['art_name']?></h2>
				<div class="main_list">
					<span class="left"><?php echo $art[0]['art_from']?></span>
					<span class="right"><?php echo $art[0]['art_addtime']?></span>
				</div>
				<div class="main_main">
					<span>&nbsp;&nbsp;<?php echo $art[0]['art_content']?></span>
					
				</div>
			</div>
				
		</div>
		<div class="overs">
			<div class="over_box">
				<div class="over_img">
					<img src="__VVOFF__/images/foot03/zfew.jpg"/>
				</div>
				<div class="over_txt">
					<p>杭州京歌科技有限公司版权所有&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;400-167-5655</p>
					<p>地址：杭州市江干区解放东路37号财富金融中心西楼3202</p>
					<p>备案号 浙ICP备17002020号</p>
				</div>
			</div>
		</div>
		
	</body>
</html>
