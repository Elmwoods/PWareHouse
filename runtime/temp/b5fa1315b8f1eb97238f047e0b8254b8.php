<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:55:"/mnt/data/www/wstmart/mobile/view/default/register.html";i:1491530392;s:51:"/mnt/data/www/wstmart/mobile/view/default/base.html";i:1491530392;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title>注册 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="__MOBILE__/css/user.css?v=<?php echo $v; ?>">

<script type='text/javascript' src='__MOBILE__/frozenui/js/zepto.min.js'></script>
<script type='text/javascript' src='__MOBILE__/frozenui/js/frozen.js'></script>
<script type='text/javascript' src='__MOBILE__/js/laytpl/laytpl.js?v=<?php echo $v; ?>'></script>
<script src="__MOBILE__/js/echo.min.js"></script>
<script type='text/javascript' src='__MOBILE__/js/common.js?v=<?php echo $v; ?>'></script>
<script>
window.conf = {"ROOT":"__ROOT__","APP":"__APP__","STATIC":"__STATIC__","SUFFIX":"<?php echo config('url_html_suffix'); ?>","SMS_VERFY":"<?php echo WSTConf('CONF.smsVerfy'); ?>","SMS_OPEN":"<?php echo WSTConf('CONF.smsOpen'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>","IS_LOGIN":"<?php if((int)session('WST_USER.userId')>0): ?>1<?php else: ?>0<?php endif; ?>","ROUTES":'<?php echo WSTRoute(); ?>'}
</script>
</head>
<body ontouchstart="">

       <header style="background:#ffffff;" class="ui-header ui-header-positive wst-header">
       	   <i id="return" class="ui-icon-return" onclick="javascript:history.go(-1)" ></i><h1 id="login-w">注册</h1>
       </header>


		
		<div class="ui-loading-block" id="Loadl">
		    <div class="ui-loading-cnt">
		        <i class="ui-loading-bright"></i>
		        <p id="j-Loadl">正在加载中...</p>
		    </div>
		</div>


      
      <section class="ui-container" id="login0">
      	 <div class="wst-lo-frame">
			<div class="frame"><input id="regName" type="text" placeholder="邮箱/用户名/手机号" onkeyup="javascript:onTesting(this)"></div>
			<div class="frame"><input id="regPwd" type="password" placeholder="密码"></div>
			<div class="frame"><input id="regcoPwd" type="password" placeholder="确认密码"></div>
			<div class="verify" id="mailbox">
				<input id="regVerfy" type="text" placeholder="输入验证码" maxlength="10">
				<img id='verifyImg0' src="<?php echo url('mobile/users/getVerify'); ?>" onclick='javascript:WST.getVerify("#verifyImg0")'>
			</div>
			
			<?php if((WSTConf('CONF.smsVerfy')==1)): ?>
			<div class="verify phone" style="display:none;">
				<input id="smsVerfy" type="text" placeholder="输入验证码" maxlength="10">
				<img id='verifyImg3' src="<?php echo url('mobile/users/getVerify'); ?>" onclick='javascript:WST.getVerify("#verifyImg3")'>
			</div>
			<?php endif; ?>
			
			<div class="verify phone" style="display:none;">
				<input id="phoneCode" type="text" placeholder="输入短信验证码" maxlength="8">
				<button id="obtain" class="ui-btn ui-btn-primary" onclick="javascript:obtainCode()">获取验证码</button>
			</div>
    	</div>
    	<div class="wst-lo-button">
			<button id="regButton" class="button" onclick="javascript:register();">注册</button>
		</div>
      </section>
      



<script type='text/javascript' src='__MOBILE__/js/login.js?v=<?php echo $v; ?>'></script>

</body>
</html>