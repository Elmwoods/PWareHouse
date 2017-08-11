<?php if (!defined('THINK_PATH')) exit(); /*a:8:{s:64:"E:\wamp\www\jingomall\jingo/wstmart/home\view\default\index.html";i:1501488632;s:63:"E:\wamp\www\jingomall\jingo/wstmart/home\view\default\base.html";i:1497261098;s:69:"E:\wamp\www\jingomall\jingo/wstmart/home\view\default\header_top.html";i:1499654586;s:69:"E:\wamp\www\jingomall\jingo/wstmart/home\view\default\shop_apply.html";i:1489135872;s:75:"E:\wamp\www\jingomall\jingo/wstmart/home\view\default\self_shop_header.html";i:1498444973;s:65:"E:\wamp\www\jingomall\jingo/wstmart/home\view\default\header.html";i:1499654722;s:69:"E:\wamp\www\jingomall\jingo/wstmart/home\view\default\right_cart.html";i:1500529031;s:65:"E:\wamp\www\jingomall\jingo/wstmart/home\view\default\footer.html";i:1497249046;}*/ ?>
<!doctype html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo WSTConf('CONF.mallName'); ?><?php echo WSTConf('CONF.mallTitle'); ?></title>
<meta name="auther" content="WSTMart,www.wstmart.net" />
<meta name="copyright" content="Copyright©2016-2066 Powered By WSTMart" />
<link rel="shortcut icon" href="favicon.ico"><link rel="Bookmark" href="favicon.ico">

<meta name="description" content="<?php echo WSTConf('CONF.seoMallDesc'); ?>">
<meta name="Keywords" content="<?php echo WSTConf('CONF.seoMallKeywords'); ?>">

<link href="__STYLE__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet">

<link href="__STYLE__/css/index.css?v=<?php echo $v; ?>" rel="stylesheet">

<script type="text/javascript" src="__STATIC__/js/jquery.min.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__STATIC__/plugins/layer/layer.js?v=<?php echo $v; ?>"></script>

<script type="text/javascript" src="__STATIC__/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>
<script type='text/javascript' src='__STATIC__/js/common.js?v=<?php echo $v; ?>'></script>

<script type='text/javascript' src='__STYLE__/js/common.js?v=<?php echo $v; ?>'></script>


<?php if(((int)session('WST_USER.userId')<=0)): ?>
<link href="__STATIC__/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="__STYLE__/css/login.css?v=<?php echo $v; ?>" rel="stylesheet">
<script type="text/javascript" src="__STATIC__/plugins/validator/jquery.validator.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__STATIC__/plugins/validator/local/zh-CN.js?v=<?php echo $v; ?>"></script>
<script type='text/javascript' src='__STYLE__/js/login.js?v=<?php echo $v; ?>'></script>
<?php endif; ?>
<script>
window.conf = {"ROOT":"__ROOT__","APP":"__APP__","STATIC":"__STATIC__","SUFFIX":"<?php echo config('url_html_suffix'); ?>","SMS_VERFY":"<?php echo WSTConf('CONF.smsVerfy'); ?>","SMS_OPEN":"<?php echo WSTConf('CONF.smsOpen'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>","IS_LOGIN":"<?php if((int)session('WST_USER.userId')>0): ?>1<?php else: ?>0<?php endif; ?>","TIME_TASK":"1","ROUTES":'<?php echo WSTRoute(); ?>'}
$(function() {
	WST.initVisitor();
});
</script>
</head>

<body>

<a name="return_top" id="return_top"></a>

	<div class="wst-header">
    <div class="wst-nav">
		<ul class="headlf">
		

			<li class="drop-info">
			  <div>服务热线：<?php echo WSTConf('CONF.serviceTel'); ?></div>
			</li>
			<li class="spacer">|</li>
			<!-- <li class="drop-info">
			  <div><a href="<?php echo Url('home/users/regist'); ?>">免费注册</a></div>
			</li> -->
			<li class="j-dorpdown">
				<div class="drop-down drop-down2 pdr5"><i class="di-left"></i><a href="#" target="_blank">手机商城</a></div>
				<div class='j-dorpdown-layer sweep-list'>
				   <div class="qrcodea">
					   <div id='qrcodea' class="qrcodeal">
					   	<img src="__STYLE__/img/erweima.png" style="width:114px;height:114px;margin-left: 5px;">
					   </div>
					   <div class="qrcodear">
					   	<p>扫描二维码</p><span>下载手机客户端</span>
					   	<br/>
					   	<a >Android</a>
					   	<br/>
					   	<a>iPhone</a>
					   </div>
				   </div>
				</div>
			</li>
		</ul>
		<ul class="headrf" style='float:right;'>
		    <!-- <li class="j-dorpdown" style="width: 86px;">
				<div class="drop-down" style="padding-left:0px;">
					<a href="<?php echo Url('home/users/index'); ?>" target="_blank">我的订单<i class="di-right"><s>◇</s></i></a>
				</div>
				<div class='j-dorpdown-layer order-list'>
				   <div><a href='<?php echo Url("home/orders/waitPay"); ?>' onclick='WST.position(3,0)'>待付款订单</a></div>
				   <div><a href='<?php echo Url("home/orders/waitReceive"); ?>' onclick='WST.position(5,0)'>待发货订单</a></div>
				   <div><a href='<?php echo Url("home/orders/waitAppraise"); ?>' onclick='WST.position(6,0)'>待评价订单</a></div>
				</div>
			</li>	 -->
			
			<!-- <li class="spacer">|</li> -->
			<!-- <li class="j-dorpdown">
				<div class="drop-down drop-down2 pdr5"><i class="di-left"></i><a href="#" target="_blank">手机商城</a></div>
				<div class='j-dorpdown-layer sweep-list'>
				   <div class="qrcodea">
					   <div id='qrcodea' class="qrcodeal"></div>
					   <div class="qrcodear">
					   	<p>扫描二维码</p><span>下载手机客户端</span>
					   	<br/>
					   	<a >Android</a>
					   	<br/>
					   	<a>iPhone</a>
					   </div>
				   </div>
				</div>
			</li> -->
			<?php if(session('WST_USER.userId') >0): ?>
		   <li class="drop-info">
			  <div class="drop-infos">
			  <a href="<?php echo Url('home/users/index'); ?>">欢迎您，<?php echo session('WST_USER.userName')?session('WST_USER.userName'):session('WST_USER.loginName'); ?></a>
			  </div>
			  <div class="wst-tag dorpdown-user">
			  	<div class="wst-tagt">
			  	   <div class="userImg" >
				  	<img class='usersImg' data-original="<?php echo WSTUserPhoto(session('WST_USER.userPhoto')); ?>"/>
				   </div>	
				  <div class="wst-tagt-n">
				    <div>
					  	<span class="wst-tagt-na"><?php echo session('WST_USER.userName')?session('WST_USER.userName'):session('WST_USER.loginName'); ?></span>
					  	<?php if((int)session('WST_USER.rankId') > 0): ?>
					  		<img src="__ROOT__/<?php echo session('WST_USER.userrankImg'); ?>" title="<?php echo session('WST_USER.rankName'); ?>"/>
					  	<?php endif; ?>
				  	</div>
				  	<div class='wst-tags'>
			  	     <span class="w-lfloat"><a onclick='WST.position(15,0)' href='<?php echo Url("home/users/edit"); ?>'>用户资料</a></span>
			  	     <span class="w-lfloat" style="margin-left:10px;"><a onclick='WST.position(16,0)' href='<?php echo Url("home/users/security"); ?>'>安全设置</a></span>
			  	    </div>
				  </div>
			  	  <div class="wst-tagb" >
			  		<a onclick='WST.position(5,0)' href='<?php echo Url("home/orders/waitReceive"); ?>'>待收货订单</a>
			  		<a onclick='WST.position(60,0)' href='<?php echo Url("home/logmoneys/usermoneys"); ?>'>我的余额</a>
			  		<a onclick='WST.position(49,0)' href='<?php echo Url("home/messages/index"); ?>'>我的消息</a>
			  		<a onclick='WST.position(13,0)' href='<?php echo Url("home/userscores/index"); ?>'>我的积分</a>
			  		<a onclick='WST.position(41,0)' href='<?php echo Url("home/favorites/goods"); ?>'>我的关注</a>
			  		<a style='display:none'>咨询回复</a>
			  	  </div>
			  	<div class="wst-clear"></div>
			  	</div>
			  </div>
			</li>
			<li class="spacer">|</li>
			<li class="drop-info">
			<a href='<?php echo Url("home/messages/index"); ?>' target='_blank' onclick='WST.position(49,0)'>消息（<span id='wst-user-messages'>0</span>）</a>
			</li>
			<li class="spacer">|</li>
			<li class="drop-info">
			  <div><a href="javascript:WST.logout();">退出</a></div>
			</li>
			<li class="spacer">|</li>
			<?php else: ?>
				<li class="drop-info">
				  <div class="drop-info_logo">您好&nbsp;,&nbsp;&nbsp;<a href="<?php echo Url('home/users/login'); ?>">请登录</a></div>
				</li>
				<!-- <li class="spacer">|</li> -->
				<li class="drop-info">
				  <div><a href="<?php echo Url('home/users/regist'); ?>">注册</a></div>
				</li>
				<li class="spacer">|</li>
			<?php endif; ?>
			
			<!-- <li class="j-dorpdown" style="width:78px;">
				<div class="drop-down" style="padding:0 5px;"><a href="#" target="_blank">关注我们</a></div>
				<div class='j-dorpdown-layer des-list' style="width:120px;">
					<div style="height:114px;"><img src="__STYLE__/img/wst_qr_code.jpg" style="height:114px;"></div>
					<div>关注我们</div>
				</div>
			</li> -->

			<!-- <li class="" style="width:91px;text-align: center;">
				<div class="drop-down" style="padding:0 5px;"><a href="#" target="_blank">邀请掘金</a></div> -->
				<!-- <div class='j-dorpdown-layer des-list' style="width:120px;"> -->
				<!-- 	<div style="height:114px;"><img src="__STYLE__/img/wst_qr_code.jpg" style="height:114px;"></div>
					<div>关注我们</div>
				</div> -->
			<!-- </li> -->


			<!-- <li class="spacer">|</li> -->

				<li class="j-dorpdown">
				<div class="drop-downn" style="cursor:pointer;"><a>我的商城<i class="di-right1"><s>◇</s></i></a></div>
				<div class='j-dorpdown-layer foucs-list'>
				   <div><a href="<?php echo Url('home/favorites/goods'); ?>" onclick='WST.position(41,0)'>商品收藏</a></div>
				   <div><a href="<?php echo Url('home/favorites/shops'); ?>" onclick='WST.position(46,0)'>店铺收藏</a></div>
				</div>
			</li>
		<!-- 	<li class="j-dorpdown">
				<div class="drop-down drop-down4 pdr5"><a href="#" target="_blank">我的收藏</a></div>
				<div class='j-dorpdown-layer foucs-list'>
				   <div><a href="<?php echo Url('home/favorites/goods'); ?>" onclick='WST.position(41,0)'>商品收藏</a></div>
				   <div><a href="<?php echo Url('home/favorites/shops'); ?>" onclick='WST.position(46,0)'>店铺收藏</a></div>
				</div>
			</li> -->
			<!-- <li class="spacer">|</li>
			<li class="j-dorpdown">
				<div class="drop-down drop-down5 pdr5" style="cursor:pointer;"><a>客户服务</a></div>
				<div class='j-dorpdown-layer des-list'>
				   <div><a href='<?php echo Url("home/helpcenter/view","id=1"); ?>' target='_blank'>帮助中心</a></div>
				   <div><a href='<?php echo Url("home/helpcenter/view","id=8"); ?>' target='_blank'>售后服务</a></div>
				   <div><a href='<?php echo Url("home/helpcenter/view","id=3"); ?>' target='_blank'>常见问题</a></div>
				</div>
			</li> -->
			<li class="spacer">|</li>
			<?php if(session('WST_USER.userId') > 0): if(session('WST_USER.userType') == 0): ?>

				<li class="j-dorpdown">
				<div class="drop-down pdl5" style="cursor:pointer;"><a>商家管理<i class="di-right"><s>◇</s></i></a></div>
				<div class='j-dorpdown-layer foucs-list'>
				   <div><a href="<?php echo url('home/shops/login'); ?>">商家登录</a></div>
				   <div><a href="javascript:shopApply();" rel="nofollow">我要开店</a></div>
				</div>
				</li>
				
				<?php else: ?>
				<li class="j-dorpdown">
				    <div class="drop-down pdl5" >
				       <a href="<?php echo Url('home/shops/index'); ?>" rel="nofollow" target="_blank">卖家中心<i class="di-right"><s>◇</s></i></a>
				    </div>
				    <div class='j-dorpdown-layer product-list last-menu'>
					   <div><a href='<?php echo Url("home/orders/waitdelivery"); ?>' onclick='WST.position(24,1)'>待发货订单</a></div>
					   <div><a href='<?php echo Url("home/orders/waitdelivery"); ?>' onclick='WST.position(25,1)'>投诉订单</a></div>
					   <div><a href='<?php echo Url("home/home/goods/sale"); ?>' onclick='WST.position(32,1)'>商品管理</a></div>
					   <div><a href='<?php echo Url("home/shopcats/index"); ?>' onclick='WST.position(30,1)'>商品分类</a></div>
					</div>
				</li>
				<?php endif; else: ?>
				<li class="j-dorpdown">
				<div class="drop-down pdl5" style="cursor:pointer;"><a>商家管理<i class="di-right"><s>◇</s></i></a></div>
				<div class='j-dorpdown-layer foucs-list'>
				   <div><a href="<?php echo url('home/shops/login'); ?>">商家登录</a></div>
				   <div><a href="javascript:shopApply();" rel="nofollow">我要开店</a></div>
				</div>
				</li>
				
			<?php endif; ?>
			</li>
		</ul>
		<div class="wst-clear"></div>
  </div>
</div>
<script>
$(function(){
	//二维码
	//参数1表示图像大小，取值范围1-10；参数2表示质量，取值范围'L','M','Q','H'
	var a = qrcode(8, 'M');
	var url = window.location.host+window.conf.APP;

	a.addData(url);

	a.make();
	// $('#qrcodea').html(a.createImgTag());
});
function goShop(id){
  location.href=WST.U('home/shops/home','shopId='+id);
}
</script>
<script type='text/javascript' src='__STYLE__/js/qrcode.js?v=<?php echo $v; ?>'></script>



<?php if(session('WST_USER.userId') > 0): if(session('WST_USER.userType') == 0): ?>
		<link href="__STATIC__/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="__STYLE__/css/shopapply.css?v=<?php echo $v; ?>" rel="stylesheet">
<div id="wst-shopapp" class="wst-shopapp" style="display:none;">
	<div class="wst-shopapp-fo">
	<a href="javascript:void(0)" onclick="javascript:getClose()" class="wst-shopapp-close"></a>
	<form id="apply_form"  method="post" autocomplete="off">
	<table class="wst-table" style="margin:15px;margin-left:35px;">
	    <tr>
	    	<td class="wst-shopapp-td">联系人</td>
	    	<td><input id="linkman" name="linkman" class="wst_ipt2 wst-shopapp-input" tabindex="1" maxlength="30" autocomplete="off" onpaste="return false;" style="ime-mode:disabled;" placeholder="联系人"  type="text" data-rule="联系人 required;" data-msg-mobile="请输入联系人" data-msg-required="请输入联系人" data-tip="请输入联系人"/></td>
	    </tr>
		<tr>
			<td class="wst-shopapp-td">手机号</td>
			<td><input id="userPhone2" name="userPhone" class="wst_ipt2 wst-shopapp-input" tabindex="1" maxlength="30" autocomplete="off" onpaste="return false;" style="ime-mode:disabled;" placeholder="手机号"  type="text"data-rule="手机号 required;mobile;remote(post:<?php echo url('home/shopapplys/checkShopPhone'); ?>)" data-msg-mobile="请输入有效的手机号" data-msg-required="请输入手机号" data-tip="请输入手机号" data-target="#userPhone2"/></td>
		</tr>
		<?php if((WSTConf('CONF.smsOpen')==1)): ?>
		<tr>
			<td class="wst-shopapp-td">短信验证码</td>
			<td>
				<input maxlength="6" autocomplete="off" tabindex="6" class="wst_ipt2 wst-shopapp-input2" name="mobileCode" style="ime-mode:disabled" id="mobileCode" type="text" placeholder="短信验证码" />
				<button type="button"  onclick="javascript:getShopCode();" class="wst-shopapp-obtain">获取短信验证码</button>
				<span id="mobileCodeTips"></span>
			</td>
		</tr>
		<?php else: ?>
		<tr>
			<td class="wst-shopapp-td">验证码</td>
			<td>
				<div class="wst-apply-code">
				<input id="verifyCodea" style="ime-mode:disabled" name="verifyCodea"  class="wst_ipt2 wst-apply-codein" tabindex="6" autocomplete="off" maxlength="6" type="text" placeholder="验证码"/>
				<img id='verifyImga' class="wst-apply-codeim" src="" onclick='javascript:WST.getVerify("#verifyImga")' style="width:101px;border-top-right-radius:2px;border-bottom-right-radius:2px;"><span id="verifya"></span>    	
			   	</div>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<td class="wst-shopapp-td">备注</td>
			<td>
				<textarea id="remark" name="remark" class="wst_ipt2 wst-remark" id="rejectionRemarks" autocomplete="off" style="width: 350px;height:170px;"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding-left:80px;">
				<label>
					<input id="protocol" name="protocol" type="checkbox" class="wst_ipt2" value="1"/>我已阅读并同意
	           	</label>
	           	<a href="javascript:;" style="color:#69b7b5;" id="protocolInfo" onclick="showProtocol();">《商家注册协议》</a>
			</td>
		</tr>
		<tr style="height:45px;">
			<td colspan="2" style="padding-left:165px;">
				<input id="reg_butt" class="wst-shopapp-but" type="submit" value="注册" style="width: 100px;height:30px;"/>
			</td>
		</tr>
	</table>
	</form>
	</div>
</div>
     <form method="post" id="shopVerifys" autocomplete="off" style="display:none;">
      <input type='hidden' id='VerifyId' value='' autocomplete="off"/>
      <table class='wst-table' style="width:400x;margin:15px;margin-left:35px;">
      	<tr>
			<td class="wst-shopapp-td">验证码</td>
			<td>
				<input id="smsVerfy"  name="smsVerfy"  class="wst_ipt2 wst-shopapp-input2" tabindex="6" autocomplete="off" maxlength="6" type="text" data-target="#smsVerfyTips" placeholder="验证码" data-rule="验证码: required;" data-msg-required="请输入验证码" data-tip="请输入验证码"/>
				<span id="smsVerfyTips" style="float:right;"></span>      	
			   	<label style="float:right;margin-top:5px;"><a href="javascript:WST.getVerify('#verifyImg3')">&nbsp;换一张</a></label>
				<label style="float:right;">
					<img id='verifyImg3' src="" onclick='javascript:WST.getVerify("#verifyImg3")' style="width:100px;"> 
				</label>
			</td>
		</tr>
         <tr>
           <td colspan='2' style='padding-left:170px;height:50px;'>
               <button class="wst-shopapp-but" type="submit" style="width:100px;height: 30px;">确认</button>
           </td>
         </tr>
        </table>
      </form>
<script type="text/javascript" src="__STATIC__/plugins/validator/jquery.validator.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__STATIC__/plugins/validator/local/zh-CN.js?v=<?php echo $v; ?>"></script>
<script type='text/javascript' src='__STYLE__/js/shop_applys.js?v=<?php echo $v; ?>'></script>	
	<?php endif; else: ?>
	<link href="__STATIC__/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="__STYLE__/css/shopapply.css?v=<?php echo $v; ?>" rel="stylesheet">
<div id="wst-shopapp" class="wst-shopapp" style="display:none;">
	<div class="wst-shopapp-fo">
	<a href="javascript:void(0)" onclick="javascript:getClose()" class="wst-shopapp-close"></a>
	<form id="apply_form"  method="post" autocomplete="off">
	<table class="wst-table" style="margin:15px;margin-left:35px;">
	    <tr>
	    	<td class="wst-shopapp-td">联系人</td>
	    	<td><input id="linkman" name="linkman" class="wst_ipt2 wst-shopapp-input" tabindex="1" maxlength="30" autocomplete="off" onpaste="return false;" style="ime-mode:disabled;" placeholder="联系人"  type="text" data-rule="联系人 required;" data-msg-mobile="请输入联系人" data-msg-required="请输入联系人" data-tip="请输入联系人"/></td>
	    </tr>
		<tr>
			<td class="wst-shopapp-td">手机号</td>
			<td><input id="userPhone2" name="userPhone" class="wst_ipt2 wst-shopapp-input" tabindex="1" maxlength="30" autocomplete="off" onpaste="return false;" style="ime-mode:disabled;" placeholder="手机号"  type="text"data-rule="手机号 required;mobile;remote(post:<?php echo url('home/shopapplys/checkShopPhone'); ?>)" data-msg-mobile="请输入有效的手机号" data-msg-required="请输入手机号" data-tip="请输入手机号" data-target="#userPhone2"/></td>
		</tr>
		<?php if((WSTConf('CONF.smsOpen')==1)): ?>
		<tr>
			<td class="wst-shopapp-td">短信验证码</td>
			<td>
				<input maxlength="6" autocomplete="off" tabindex="6" class="wst_ipt2 wst-shopapp-input2" name="mobileCode" style="ime-mode:disabled" id="mobileCode" type="text" placeholder="短信验证码" />
				<button type="button"  onclick="javascript:getShopCode();" class="wst-shopapp-obtain">获取短信验证码</button>
				<span id="mobileCodeTips"></span>
			</td>
		</tr>
		<?php else: ?>
		<tr>
			<td class="wst-shopapp-td">验证码</td>
			<td>
				<div class="wst-apply-code">
				<input id="verifyCodea" style="ime-mode:disabled" name="verifyCodea"  class="wst_ipt2 wst-apply-codein" tabindex="6" autocomplete="off" maxlength="6" type="text" placeholder="验证码"/>
				<img id='verifyImga' class="wst-apply-codeim" src="" onclick='javascript:WST.getVerify("#verifyImga")' style="width:101px;border-top-right-radius:2px;border-bottom-right-radius:2px;"><span id="verifya"></span>    	
			   	</div>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<td class="wst-shopapp-td">备注</td>
			<td>
				<textarea id="remark" name="remark" class="wst_ipt2 wst-remark" id="rejectionRemarks" autocomplete="off" style="width: 350px;height:170px;"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding-left:80px;">
				<label>
					<input id="protocol" name="protocol" type="checkbox" class="wst_ipt2" value="1"/>我已阅读并同意
	           	</label>
	           	<a href="javascript:;" style="color:#69b7b5;" id="protocolInfo" onclick="showProtocol();">《商家注册协议》</a>
			</td>
		</tr>
		<tr style="height:45px;">
			<td colspan="2" style="padding-left:165px;">
				<input id="reg_butt" class="wst-shopapp-but" type="submit" value="注册" style="width: 100px;height:30px;"/>
			</td>
		</tr>
	</table>
	</form>
	</div>
</div>
     <form method="post" id="shopVerifys" autocomplete="off" style="display:none;">
      <input type='hidden' id='VerifyId' value='' autocomplete="off"/>
      <table class='wst-table' style="width:400x;margin:15px;margin-left:35px;">
      	<tr>
			<td class="wst-shopapp-td">验证码</td>
			<td>
				<input id="smsVerfy"  name="smsVerfy"  class="wst_ipt2 wst-shopapp-input2" tabindex="6" autocomplete="off" maxlength="6" type="text" data-target="#smsVerfyTips" placeholder="验证码" data-rule="验证码: required;" data-msg-required="请输入验证码" data-tip="请输入验证码"/>
				<span id="smsVerfyTips" style="float:right;"></span>      	
			   	<label style="float:right;margin-top:5px;"><a href="javascript:WST.getVerify('#verifyImg3')">&nbsp;换一张</a></label>
				<label style="float:right;">
					<img id='verifyImg3' src="" onclick='javascript:WST.getVerify("#verifyImg3")' style="width:100px;"> 
				</label>
			</td>
		</tr>
         <tr>
           <td colspan='2' style='padding-left:170px;height:50px;'>
               <button class="wst-shopapp-but" type="submit" style="width:100px;height: 30px;">确认</button>
           </td>
         </tr>
        </table>
      </form>
<script type="text/javascript" src="__STATIC__/plugins/validator/jquery.validator.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__STATIC__/plugins/validator/local/zh-CN.js?v=<?php echo $v; ?>"></script>
<script type='text/javascript' src='__STYLE__/js/shop_applys.js?v=<?php echo $v; ?>'></script>	
<?php endif; if(isset($selfShop)): if(!isset($_COOKIE['ads_cookie'])): $wstTagAds =  model("common/Tags")->listAds("index-top-ads",99,86400); foreach($wstTagAds as $key=>$vo){?>
<div class="index-top-ads">
  <a href="<?php echo $vo['adURL']; ?>" <?php if(($vo['adURL']!='')): ?>onclick="WST.recordClick(<?php echo $vo['adId']; ?>)"<?php endif; ?> onfocus="this.blur();"><img src="__ROOT__/<?php echo $vo['adFile']; ?>"></a>
  <a href="javascript:;" class="close-ads" onclick="WST.closeAds(this)"></a>
</div>
<?php } endif; ?>
<div class='wst-search-container'>
   
    <div class="wst-shop-h">
    <div class="wst-shop-img"><a href="<?php echo url('home/shops/home',array('shopId'=>$data['shop']['shopId'])); ?>"><img class="shopsImg" data-original="__ROOT__/<?php echo $data['shop']['shopImg']; ?>" title="<?php echo $data['shop']['shopName']; ?>" style="width:85px;height:85px;margin-top:10px;margin-left:10px;"></a></div>
    <div class="wst-shop-info">
      <p><?php echo $data['shop']['shopName']; ?></p>
      <div class="wst-shop-info2">
      <?php if(is_array($data['shop']['accreds']) || $data['shop']['accreds'] instanceof \think\Collection): $i = 0; $__LIST__ = $data['shop']['accreds'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ac): $mod = ($i % 2 );++$i;?>
      <img src="__ROOT__/<?php echo $ac['accredImg']; ?>"><span><?php echo $ac['accredName']; ?></span>
      <?php endforeach; endif; else: echo "" ;endif; if(($data['shop']['shopQQ'])): ?>
      <a href="tencent://message/?uin=<?php echo $data['shop']['shopQQ']; ?>&Site=QQ交谈&Menu=yes">
        <img border="0" style="width:65px;height:24px;" src="http://wpa.qq.com/pa?p=1:<?php echo $data['shop']['shopQQ']; ?>:7">
      </a>
      <?php endif; if(($data['shop']['shopWangWang'])): ?>
      <a href="http://www.taobao.com/webww/ww.php?ver=3&touid=<?php echo $data['shop']['shopWangWang']; ?>&siteid=cntaobao&status=1&charset=utf-8" target="_blank">
      <img border="0" src="http://amos.alicdn.com/realonline.aw?v=2&uid=<?php echo $data['shop']['shopWangWang']; ?>&site=cntaobao&s=1&charset=utf-8" alt="和我联系" class='wangwang'/>
      </a>
      <?php endif; ?>
      </div>
      <div class="wst-shop-info3">
        <span class="wst-shop-eva">商品评价：<span class="wst-shop-red"><?php echo $data['shop']['scores']['goodsScore']; ?></span></span>
        <span class="wst-shop-eva">时效评价：<span class="wst-shop-red"><?php echo $data['shop']['scores']['timeScore']; ?></span></span>
        <span class="wst-shop-eva">服务评价：<span class="wst-shop-red"><?php echo $data['shop']['scores']['serviceScore']; ?></span></span>
        <?php if(($data['shop']['favShop']>0)): ?>
        <a href='javascript:void(0);' onclick='WST.cancelFavorite(this,1,<?php echo $data['shop']['shopId']; ?>,<?php echo $data['shop']['favShop']; ?>)' class="wst-shop-evaa j-fav">已关注</a>
        <?php else: ?>
        <a href='javascript:void(0);' onclick='WST.addFavorite(this,1,<?php echo $data['shop']['shopId']; ?>,<?php echo $data['shop']['favShop']; ?>)' class="wst-shop-evaa j-fav2">关注店铺</a>
        <?php endif; ?>
        <span class="wst-shop-eva">用手机逛本店  &nbsp;&nbsp;|</span>
        <a class="wst-shop-code"><span class="wst-shop-codes hide"><div id='qrcode' style='width:142px;height:142px;'></div></span></a>
        
      </div>
    </div>
    <div class="wst-shop-sea">
      <input type="text" id="goodsName" value="<?php echo $goodsName; ?>" placeholder="输入商品名称">
      <a class="search" href="javascript:void(0);" onclick="javascript:WST.goodsSearch($('#goodsName').val());">搜全站</a>
      <a class="search" href="javascript:void(0);" onclick="javascript:searchShopsGoods(0);">搜本店</a>
      <div class="wst-shop-word">
      <?php if(is_array($data['shop']['shopHotWords']) || $data['shop']['shopHotWords'] instanceof \think\Collection): $i = 0; $__LIST__ = $data['shop']['shopHotWords'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$shw): $mod = ($i % 2 );++$i;?>
      <a href='<?php echo Url("home/shops/home",array('shopId'=>$data['shop']['shopId'],'goodsName'=>$shw)); ?>'><?php echo $shw; ?></a><?php if($i< count($data['shop']['shopHotWords'])): ?>&nbsp;|&nbsp;<?php endif; endforeach; endif; else: echo "" ;endif; ?>
      </div>
      	<div>
      	<div style="clear: both;"></div>
      	<div class="jiathis_style_24x24 wst-shop-share">
				<a class="jiathis_button_qzone"></a>
				<a class="jiathis_button_tsina"></a>
				<a class="jiathis_button_tqq"></a>
				<a class="jiathis_button_weixin"></a>
				<a class="jiathis_button_cqq"></a>
				<a href="http://www.jiathis.com/share" class="jiathis jiathis_txt jtico jtico_jiathis" target="_blank"></a>					
		</div>
		</div>
		<div style="clear: both;"></div>
		</div>
			<script type="text/javascript" src="http://v3.jiathis.com/code/jia.js" charset="utf-8"></script>
      <?php echo hook('distributHook',['module'=>1,'positionType'=>1,'shop'=>$data['shop'],'getParams'=>input()]); ?>
    </div>
    <div class="wst-clear"></div>
    </div>
</div>
<?php if(($data['shop']['shopBanner'])): ?><div class="wst-shop-tu" style="background: url(__ROOT__/<?php echo $data['shop']['shopBanner']; ?>) no-repeat  scroll center top;background-size:cover;"></div><?php endif; ?>
<div class="wst-clear"></div>
<div class="s-wst-nav-menus">
      <div id="s-wst-nav-items">
           <ul>
               <li class="s-nav-li s-cat-head"style="background-color:#DF2003"><a href="<?php echo Url('home/shops/home',['shopId'=>$data['shop']['shopId']]); ?>" target="_blank" ><em></em>本店商品分类</a></li>
               <?php if(is_array($data['shopcats']) || $data['shopcats'] instanceof \think\Collection): $l = 0;$__LIST__ = is_array($data['shopcats']) ? array_slice($data['shopcats'],0,6, true) : $data['shopcats']->slice(0,6, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cat1): $mod = ($l % 2 );++$l;?>
               <li class="s-nav-li">
                    <a href="<?php echo url('home/shops/home',['shopId'=>$data['shop']['shopId'],'ct1'=>$cat1['catId']]); ?>" target="_blank"><?php echo $cat1['catName']; ?></a>
               </li>
               <?php endforeach; endif; else: echo "" ;endif; ?>
               <li class="s-nav-li"> <a class="homepage" href="<?php echo url('/'); ?>" target="_blank">返回商城首页</a></li>
           </ul>
      </div>
      
      <span class="wst-clear"></span>
    </div>
</div>
<div class="wst-clear"></div>

	<?php else: if(!isset($_COOKIE['ads_cookie'])): $wstTagAds =  model("common/Tags")->listAds("index-top-ads",99,86400); foreach($wstTagAds as $key=>$tads){if(($tads['adFile']!='')): ?>
<!-- <div class="index-top-ads">
  <a href="<?php echo $tads['adURL']; ?>" <?php if(($tads['isOpen'])): ?>target='_blank'<?php endif; if(($tads['adURL']!='')): ?>onclick="WST.recordClick(<?php echo $tads['adId']; ?>)"<?php endif; ?> onfocus="this.blur();">
    <img src="__ROOT__/<?php echo $tads['adFile']; ?>"></a>
  <a href="javascript:;" class="close-ads" onclick="WST.closeAds(this)"></a>
</div> -->
<?php endif; } endif; ?>


<div style="background:#fff;">
<div class='wst-search-container'>
  <div class="wst_fixed" id="fixed_top">



    <div  class="wst_fixed_top" id="fixed">
    <div class="fixed_top">
        <div class="fixed_logo">
             <!-- <a class="fl" href="javascript:void(0)" id="classification">商品分类</a>
             <div class="dd j-cate-dd" >
                <div class="dd-inner" style="display: none;" id="mm">
                     <?php $_result=WSTSideCategorys();if(is_array($_result) || $_result instanceof \think\Collection): $k = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                     <div id="cat-icon-<?php echo $k; ?>" class="item fore1 <?php if(($key>=14)): ?>over-cat<?php endif; ?>">
                         <h3>
                          <div class="<?php if(($key>=14)): ?> over-cat-icon <?php else: ?> cat-icon-<?php echo $k; endif; ?>"></div>
                          <a href="<?php echo Url('home/goods/lists','cat='.$vo['catId']); ?>" target="_blank"><?php echo $vo['catName']; ?></a>
                         </h3> 
                     </div>
                     <?php endforeach; endif; else: echo "" ;endif; ?>
                 </div>
                 <div style="display: none;" class="dorpdown-layer" id="index_menus_sub">
                     <?php $_result=WSTSideCategorys();if(is_array($_result) || $_result instanceof \think\Collection): $k = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                      <div class="item-sub" i="<?php echo $k; ?>" >
                          

                           <div class="subitems">
                              <?php if(isset($vo['list'])){ if(is_array($vo['list']) || $vo['list'] instanceof \think\Collection): $i = 0; $__LIST__ = $vo['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
                               <dl class="fore2">
                                   <dt >
                                      <a target="_blank" href="<?php echo Url('home/goods/lists','cat='.$vo2['catId']); ?>"><?php echo $vo2['catName']; ?><i>&gt;</i></a>
                                   </dt>
                                   <dd>
                                      <?php if(isset($vo2['list'])){ if(is_array($vo2['list']) || $vo2['list'] instanceof \think\Collection): $i = 0; $__LIST__ = $vo2['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo3): $mod = ($i % 2 );++$i;?>
                                      <a target="_blank" href="<?php echo Url('home/goods/lists','cat='.$vo3['catId']); ?>"><?php echo $vo3['catName']; ?></a>
                                      <?php endforeach; endif; else: echo "" ;endif; } ?>
                                   </dd>
                                </dl>
                               <?php endforeach; endif; else: echo "" ;endif; } ?>
                            </div>
                      </div>
                      <?php endforeach; endif; else: echo "" ;endif; ?>
                 </div>
              </div> -->
         <!--  <a href='<?php echo \think\Request::instance()->root(true); ?>' title="<?php echo WSTConf('CONF.mallName'); ?>" >
            <img src="__STYLE__/img/jingo_logo.png" title="<?php echo WSTConf('CONF.mallName'); ?>" alt="<?php echo WSTConf('CONF.mallName'); ?>" >
          
         </a> -->
      </div>

      <div class="ss" style="float: left;z-index: 100000;">
         


      <div class='wst-search1'>
          <input type="hidden" id="fixed-type" value="<?php echo isset($keytype)?1:0; ?>"/>
          <ul class="j-search-box1">
            <li class="j-search-type1" id="ss-type">
              <span><?php if(isset($keytype)): ?>店铺<?php else: ?>商品<?php endif; ?></span>&nbsp;<i class="arrow1"> </i>
            </li>
            <li class="j-type-list1" id="fixed-list">
              <?php if(isset($keytype)): ?>
              <div data="0">商品</div>
              <?php else: ?>
              <div data="1">店铺</div>
              <?php endif; ?>
            </li>
          </ul>
      <!--   <input type="text" id='search-ipt' class='search-ipt' placeholder='<?php echo WSTConf("CONF.adsGoodsWordsSearch"); ?>' value='<?php echo isset($keyword)?$keyword:""; ?>'/> -->
        <input type="text" id='fixed-ipt' class='search-ipt1' placeholder='输入你要搜索商品的关键字' value='<?php echo isset($keyword)?$keyword:""; ?>'/>
       
       <!--  <input type='hidden' id='adsGoodsWordsSearch' value='<?php echo WSTConf("CONF.adsGoodsWordsSearch"); ?>'>
        <input type='hidden' id='adsShopWordsSearch' value='<?php echo WSTConf("CONF.adsShopWordsSearch"); ?>'> -->
         <input type='hidden' id='yu' value='您要搜索的商品'>
        <input type='hidden' id='lan' value='您要搜索的店铺'>
        <div id='search-btn' class="search-btn1" onclick='javascript:WST.fixed(this.value)'>搜索</div>
      </div>




      </div>
    </div>
   </div>





   <div class='wst-logo'>
     <a href='<?php echo \think\Request::instance()->root(true); ?>/home' title="<?php echo WSTConf('CONF.mallName'); ?>" >
        <!-- <img src="__STYLE__/img/jingo_logo.png" title="<?php echo WSTConf('CONF.mallName'); ?>" alt="<?php echo WSTConf('CONF.mallName'); ?>"> -->
        <img src="__ROOT__/<?php echo WSTConf('CONF.mallLogo'); ?>"  title="<?php echo WSTConf('CONF.mallName'); ?>" alt="<?php echo WSTConf('CONF.mallName'); ?>">
     </a>
   </div>
   <div class="wst-search-box" >
      <div class='wst-search'>
          <input type="hidden" id="search-type" value="<?php echo isset($keytype)?1:0; ?>"/>
          <ul class="j-search-box">
            <li class="j-search-type">
              <span><?php if(isset($keytype)): ?>店铺<?php else: ?>商品<?php endif; ?></span>&nbsp;<i class="arrow"> </i>
            </li>
            <li class="j-type-list">
              <?php if(isset($keytype)): ?>
              <div data="0">商品</div>
              <?php else: ?>
              <div data="1">店铺</div>
              <?php endif; ?>
            </li>
          </ul>
      <!--   <input type="text" id='search-ipt' class='search-ipt' placeholder='<?php echo WSTConf("CONF.adsGoodsWordsSearch"); ?>' value='<?php echo isset($keyword)?$keyword:""; ?>'/> -->
        <input type="text" id='search-ipt' class='search-ipt' placeholder='输入你要搜索商品的关键字' value='<?php echo isset($keyword)?$keyword:""; ?>'/>
       
       <!--  <input type='hidden' id='adsGoodsWordsSearch' value='<?php echo WSTConf("CONF.adsGoodsWordsSearch"); ?>'>
        <input type='hidden' id='adsShopWordsSearch' value='<?php echo WSTConf("CONF.adsShopWordsSearch"); ?>'> -->
         <input type='hidden' id='adsGoodsWordsSearch' value='您要搜索的商品'>
        <input type='hidden' id='adsShopWordsSearch' value='您要搜索的店铺'>
        <div id='search-btn' class="search-btn" onclick='javascript:WST.search(this.value)'>搜索</div>
         <!-- <ul class="sousuo"> -->
            <!-- <li>bbbb</li>
            <li>bbbb</li>
            <li>bbbb</li> -->
        <!-- </ul> -->
      </div>
     <!--  <div class="wst-search-keys">
      <?php $searchKeys = WSTSearchKeys(); if(is_array($searchKeys) || $searchKeys instanceof \think\Collection): $i = 0; $__LIST__ = $searchKeys;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
       <a href='<?php echo Url("home/goods/search","keyword=".$vo); ?>'><?php echo $vo; ?></a>
       <?php if($i< count($searchKeys)): ?>&nbsp;&nbsp;|&nbsp;&nbsp;<?php endif; endforeach; endif; else: echo "" ;endif; ?>
      </div> -->
   </div>


   <script type="text/javascript">
   
      
   </script>
   <div class="wst-cart-box">
      <div class="wst-cart-img">
        <img src="__STYLE__/img/erweima.png" style="width:70px;height:70px;margin:5px 5px;">
      </div>
      <p>扫码进入移动商城</p>
   </div>
<!--    <div class="wst-cart-box">
   <a href="<?php echo url('home/carts/index'); ?>" target="_blank"><span class="word j-word">我的购物车<span class="num" id="goodsTotalNum">0</span></span></a>
    <div class="wst-cart-boxs hide">
      <div id="list-carts"></div>
      <div id="list-carts2"></div>
      <div id="list-carts3"></div>
      <div class="wst-clear"></div>
    </div>
   </div> -->

  <script id="list-cart" type="text/html">
    {{# for(var i = 0; i < d.list.length; i++){ }}
      <div class="goods" id="j-goods{{ d.list[i].cartId }}">
          <div class="imgs"><a href="{{ WST.U('home/goods/detail','id='+d.list[i].goodsId) }}"><img class="goodsImgc" data-original="__ROOT__/{{ d.list[i].goodsImg }}" title="{{ d.list[i].goodsName }}"></a></div>
          <div class="number"><p><a  href="{{ WST.U('home/goods/detail','id='+d.list[i].goodsId) }}">{{WST.cutStr(d.list[i].goodsName,26)}}</a></p><p>数量：{{ d.list[i].cartNum }}</p></div>
          <div class="price"><p>￥{{ d.list[i].shopPrice }}</p><span><a href="javascript:WST.delCheckCart({{ d.list[i].cartId }})">删除</a></span></div>
      </div>
    {{# } }}
  </script>
  </div>
</div>
</div>




<div class="wst-clear"></div>

<div style="background:#e60b49;">
<div class="wst-nav-menus">
    
    <div class="dorpdown <?php if(isset($hideCategory)): ?>j-index<?php endif; ?>" id="wst-categorys">
        <div class="dt j-cate-dt" id="classify">
             <a class="fl" href="javascript:void(0)" id="classification"><img src="__STYLE__/img/icon_fenleitubiao.png" alt="">商品分类</a>
            <div class="dd j-cate-dd" id="nn" style="height:488px;display: none;z-index: 999;position: absolute;width:190px; left:0px;background: rgba(0,0,0,0.9);" >
            <div class="dd-inner">
                 <?php $_result=WSTSideCategorys();if(is_array($_result) || $_result instanceof \think\Collection): $k = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                 <div id="cat-icon-<?php echo $k; ?>" class="item fore1 <?php if(($key>=14)): ?>over-cat<?php endif; ?>">
                     <h3>
                      <div class="<?php if(($key>=14)): ?> over-cat-icon <?php else: ?> cat-icon-<?php echo $k; endif; ?>"></div>
                      <a href="<?php echo Url('home/goods/lists','cat='.$vo['catId']); ?>" target="_blank"><?php echo $vo['catName']; ?></a>
                     </h3> 
                 </div>
                 <?php endforeach; endif; else: echo "" ;endif; ?>
             </div>
             <div style="display: none;" class="dorpdown-layer" id="index_menus_sub">
                 <?php $_result=WSTSideCategorys();if(is_array($_result) || $_result instanceof \think\Collection): $k = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                  <div class="item-sub" i="<?php echo $k; ?>" >
                      

                       <div class="subitems">
                          <?php if(isset($vo['list'])){ if(is_array($vo['list']) || $vo['list'] instanceof \think\Collection): $i = 0; $__LIST__ = $vo['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
                           <dl class="fore2">
                               <dt >
                                  <a target="_blank" href="<?php echo Url('home/goods/lists','cat='.$vo2['catId']); ?>"><?php echo $vo2['catName']; ?><i>&gt;</i></a>
                               </dt>
                               <dd>
                                  <?php if(isset($vo2['list'])){ if(is_array($vo2['list']) || $vo2['list'] instanceof \think\Collection): $i = 0; $__LIST__ = $vo2['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo3): $mod = ($i % 2 );++$i;?>
                                  <a target="_blank" href="<?php echo Url('home/goods/lists','cat='.$vo3['catId']); ?>"><?php echo $vo3['catName']; ?></a>
                                  <?php endforeach; endif; else: echo "" ;endif; } ?>
                               </dd>
                            </dl>
                           <?php endforeach; endif; else: echo "" ;endif; } ?>
                        </div>
                  </div>
                  <?php endforeach; endif; else: echo "" ;endif; ?>
             </div>
          </div>
        </div>
        
        
    
          
            <div id="wst-nav-items">
                 <ul>
                     <?php $_result=WSTNavigations(0);if(is_array($_result) || $_result instanceof \think\Collection): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                     <li class="fore1">
                          <a href="<?php echo $vo['navUrl']; ?>" <?php if($vo['isOpen']==1): ?>target="_blank"<?php endif; ?>><?php echo $vo['navTitle']; ?></a>
                     </li>
                     <?php endforeach; endif; else: echo "" ;endif; ?>
                 </ul>
            </div>
            <!--  <div id="wst-nav-items">
                 <ul>
                    
                     <li class="fore1">
                          <a href="<?php echo $vo['navUrl']; ?>" <?php if($vo['isOpen']==1): ?>target="_blank"<?php endif; ?>>首页</a>
                     </li>
                    
                 </ul>
            </div> -->

      </div>






<script type="text/javascript">    


$(function(){
  //给输入框添加键盘弹起事件
  $('#search-ipt').keyup(function(){
    $('.sousuo').css({'display':'block'});
    //发送ajax请求
    $.ajax({
      url:"home/goods/sousuo",
      type: "POST",
      dataType:'json',
      data:{wd:$(this).val()},
      success:function(val){
        // console.log(val);
      
        // var htmlstr = "";
        // for(var i=0;i<val.length;i++){
        //   htmlstr += "<li><a href='#'>"+val[i].goodsName+"</a></li>";
        // }

        // $('.sousuo').html(htmlstr);
        
      },
      error:function(e){

      }
    });
  });
});

//按回车搜索
$(function(){
  $('#search-ipt').keydown(function(e){
    if(e.keyCode == 13){
        javascript:WST.search(this.value);
    }else{

    }
  });
});


$(function(){
  $('#fixed-ipt').keydown(function(e){
    if(e.keyCode == 13){
        javascript:WST.fixed(this.value);
    }else{

    }
  });
});




              $(function(){

                //商品分类
                $('#classification').hover(
                  
                  function(){
                     
                    $('#nn').css({'display':'block'});
                  },
                  function(){

                    $('#nn').css({'display':'none'});
                  }
                );


                $('.j-cate-dd').hover(
                  function(){
                    $(this).show();
                  },
                  function(){
                    $(this).hide();
                  }
                );


                $('.imgg').hover(
                  function(){
                    $(this).next('.describe').css({'color':'red'});
                    $(this).find('img').css({'top':'-5px'});
                  },
                  function(){
                    $(this).next('.describe').css({'color':''});
                    $(this).find('img').css({'top':'-105px'});
                  }
                );

                $('.describe').hover(
                  function(){
                  
                    $(this).prev().find('img').css({'top':'-5px'});
                   
                  },
                  function(){
                    
                    $(this).prev().find('img').css({'top':'-105px'});;
                    
                  }
                );
              });
            </script>
   <div class="nav-w" >
        
      <div class="w-spacer"></div>
      <!-- <div class="dorpdown <?php if(isset($hideCategory)): ?>j-index<?php endif; ?>" id="wst-categorys">
         <div class="dt j-cate-dt" id="classify">
             <a href="javascript:void(0)">全部商品分类</a>
         </div> -->

         



         
       
         <!-- <div class="dd j-cate-dd" <?php if(!isset($hideCategory)): ?>style="display:none" <?php endif; ?>>
            <div class="dd-inner">
                 <?php $_result=WSTSideCategorys();if(is_array($_result) || $_result instanceof \think\Collection): $k = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                 <div id="cat-icon-<?php echo $k; ?>" class="item fore1 <?php if(($key>=12)): ?>over-cat<?php endif; ?>">
                     <h3>
                      <div class="<?php if(($key>=12)): ?> over-cat-icon <?php else: ?> cat-icon-<?php echo $k; endif; ?>"></div>
                      <a href="<?php echo Url('home/goods/lists','cat='.$vo['catId']); ?>" target="_blank"><?php echo $vo['catName']; ?></a>
                     </h3> 
                 </div>
                 <?php endforeach; endif; else: echo "" ;endif; ?>
             </div>
             <div style="display: none;" class="dorpdown-layer" id="index_menus_sub">
                 <?php $_result=WSTSideCategorys();if(is_array($_result) || $_result instanceof \think\Collection): $k = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                  <div class="item-sub" i="<?php echo $k; ?>">
                      <div class="item-brands">
                           <div class="brands-inner">
                            <?php $wstTagBrand =  model("common/Tags")->listBrand($vo['catId'],6,86400); foreach($wstTagBrand as $key=>$bvo){?>
                              <a target="_blank" class="img-link" href="<?php echo url('home/goods/lists',['cat'=>$bvo['catId'],'brand'=>$bvo['brandId']]); ?>">
                                  <img width="83" height="83" class='categeMenuImg' data-original="__ROOT__/<?php echo $bvo['brandImg']; ?>">
                              </a>
                            <?php } ?>
                            </div>
                            <div class='shop-inner'>
                            <?php $wstTagShop =  model("common/Tags")->listShop($vo['catId'],4,86400); foreach($wstTagShop as $key=>$bvo){?>
                              <a target="_blank" class="img-link" href="<?php echo url('home/shops/home',['shopId'=>$bvo['shopId']]); ?>">
                                  <img width="83" height="83" class='categeMenuImg' data-original="__ROOT__/<?php echo $bvo['shopImg']; ?>">
                              </a>
                            <?php } ?>
                            </div>
                       </div>

                       <div class="subitems">
                          <?php if(isset($vo['list'])){ if(is_array($vo['list']) || $vo['list'] instanceof \think\Collection): $i = 0; $__LIST__ = $vo['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
                           <dl class="fore2">
                               <dt >
                                  <a target="_blank" href="<?php echo Url('home/goods/lists','cat='.$vo2['catId']); ?>"><?php echo $vo2['catName']; ?><i>&gt;</i></a>
                               </dt>
                               <dd>
                                  <?php if(isset($vo2['list'])){ if(is_array($vo2['list']) || $vo2['list'] instanceof \think\Collection): $i = 0; $__LIST__ = $vo2['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo3): $mod = ($i % 2 );++$i;?>
                                  <a target="_blank" href="<?php echo Url('home/goods/lists','cat='.$vo3['catId']); ?>"><?php echo $vo3['catName']; ?></a>
                                  <?php endforeach; endif; else: echo "" ;endif; } ?>
                               </dd>
                            </dl>
                           <?php endforeach; endif; else: echo "" ;endif; } ?>
                        </div>
                  </div>
                  <?php endforeach; endif; else: echo "" ;endif; ?>
             </div>
        </div> -->
      <!-- </div> -->
<script type="text/javascript">
// $(function(){
//    $('#classify').hover(
//     function(){
//       $('.wst-categorys_yiji').css('display','block');
//     },
//     function(){
//       // $('.wst-categorys_yiji').css('display','none');
//     }
//   );

//    $('.wst-categorys_yiji').hover(
//     function(){
//       $('.erji').css('display','block');
//     },
//     function(){
//       $('.erji').css('display','none');
//     }
//   );
// })

// $('#classify').mouseenter(function(){
//   $('.wst-categorys_yiji').css('display','block');
// }).mouseleave(function(){
//    $('.wst-categorys_yiji').css('display','');
// });
 

//  $('.wst-categorys_yiji').mouseenter(function(){
//   $('.wst-categorys_erji').css('display','block');
// }).mouseleave(function(){
//    $('.wst-categorys_erji').css('display','');
// });
</script>

      <!-- 
      <div id="wst-nav-items">
           <ul>
               <?php $_result=WSTNavigations(0);if(is_array($_result) || $_result instanceof \think\Collection): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
               <li class="fore1">
                    <a href="<?php echo $vo['navUrl']; ?>" <?php if($vo['isOpen']==1): ?>target="_blank"<?php endif; ?>><?php echo $vo['navTitle']; ?></a>
               </li>
               <?php endforeach; endif; else: echo "" ;endif; ?>
           </ul>
      </div> -->
<!--       <div class='wst-right-panel' <?php if(!isset($hideCategory)): ?>style="display:none" <?php endif; ?>>

      
    <div id="wst-right-ads">
        <?php $wstTagAds =  model("common/Tags")->listAds("index-art",2,86400); foreach($wstTagAds as $key=>$vo){?>
          <a <?php if(($vo['isOpen'])): ?>target='_blank'<?php endif; if(($vo['adURL']!='')): ?>onclick="WST.recordClick(<?php echo $vo['adId']; ?>)"<?php endif; ?> href="<?php echo $vo['adURL']; ?>" onfocus="this.blur()">
            <img data-original="__ROOT__/<?php echo $vo['adFile']; ?>" class="goodsImg" />
          </a>
        <?php } ?>
        <div class="index-user-tab">
         

        <div id="wst-right-news" style="">
          <p>最新资讯</p>
          <a href="<?php echo url('home/news/view'); ?>">>></a>
        </div>

        <ul id="wst-right-new-list"<?php if((!session('WST_USER.userId'))): ?>class="visitor-new-list"<?php endif; ?>  >
          <?php $wstTagArticle =  model("common/Tags")->listArticle("new",5,86400); foreach($wstTagArticle as $key=>$vo){?>
          <li><a href="<?php echo url('home/news/view',['id'=>$vo['articleId']]); ?>"><?php echo $vo['articleTitle']; ?></a></li>
          <?php } ?>
        </ul>
     
      </div>
      
      <span class="wst-clear">
        
      </span>-->
       
    </div>
</div> 
</div>
<div class="wst-clear"></div>

	<?php endif; ?>



<div class="wst-ads"  style="margin-top:20px;">
	<div class="wst-slide" id="wst-slide">
		<ul class="wst-slide-items">
			<?php $wstTagAds =  model("common/Tags")->listAds("ads-index",99,86400); foreach($wstTagAds as $key=>$vo){?>
				<a href="<?php echo $vo['adURL']; ?>" <?php if(($vo['isOpen'])): ?>target='_blank'<?php endif; if(($vo['adURL']!='')): ?>onclick="WST.recordClick(<?php echo $vo['adId']; ?>)"<?php endif; ?>><li style="background: url(__ROOT__/<?php echo $vo['adFile']; ?>) no-repeat  scroll center top;background-size:cover;" ></li></a>
			<?php } ?>
		</ul>
		<div class="wst-slide-numbox">
			<div class="wst-slide-controls">
			  	<?php $wstTagAds =  model("common/Tags")->listAds("ads-index",99,86400); foreach($wstTagAds as $k=>$vo){if($k+1 == 1): ?>
				  		 <span class="curr"><?php echo $k+1; ?></span>
				  	<?php else: ?>
				  		 <span class=""><?php echo $k+1; ?></span>
				  	<?php endif; } ?>
			</div>
		</div>
	</div>
</div>

<!-- <div class=" wst-container">
    <div class="ads-lunbobottom">
	<?php $wstTagAds =  model("common/Tags")->listAds("ads-lunbobottom",5,86400); foreach($wstTagAds as $key=>$vo){?>
        <a <?php if(($vo['isOpen'])): ?>target='_blank'<?php endif; if(($vo['adURL']!='')): ?>onclick="WST.recordClick(<?php echo $vo['adId']; ?>)"<?php endif; ?> href="<?php echo $vo['adURL']; ?>" onfocus="this.blur()">
            <img data-original="__ROOT__/<?php echo $vo['adFile']; ?>" class="goodsImg" />
        </a>
    <?php } ?>
	</div>
</div> -->	
<div class='wst-main'>
   
	<div class='wst-container'>
   

    <!-- <div class="buy-new-best-hot">
		<ul class="rec">
				<li class="rec-item j-rec-selected" id="fl_f_0" onmouseover="gpanelOver1(this);">
					<em><em></em></em>
					<a href="javascript:void(0)">超值特卖</a>
				</li>
				<li class="rec-item" id="fl_f_1" onmouseover="gpanelOver1(this);"><em><em></em></em><a href="javascript:void(0)">手机专享</a></li>
				<li class="rec-item" id="fl_f_2" onmouseover="gpanelOver1(this);"><em><em></em></em><a href="javascript:void(0)">品牌活动</a></li>
				
		</ul>
		
		<div class="rec-goods-list" id="fl_f_0_pl">
			<ul class="rec-goods-list">
			    <?php $wstTagGoods =  model("common/Tags")->listGoods("recom",0,5,86400); foreach($wstTagGoods as $key=>$vo){?>
				<li>
					<a href='<?php echo Url("home/goods/detail","id=".$vo["goodsId"]); ?>' ><img src="__ROOT__/<?php echo WSTImg($vo['goodsImg']); ?>" style="width:100%;height:90%;" title="<?php echo $vo['goodsName']; ?>"></a>
				</li>
				<?php } ?>
			</ul>
			<div class="wst-clear"></div>
		</div> 
		
		<div class="rec-goods-list hide" id="fl_f_1_pl">
			<ul class="rec-goods-list">
				<?php $wstTagGoods =  model("common/Tags")->listGoods("new",0,5,86400); foreach($wstTagGoods as $key=>$vo){?>
				<li>
			
					<a href='<?php echo Url("home/goods/detail","id=".$vo["goodsId"]); ?>'><img src="__ROOT__/<?php echo WSTImg($vo['goodsImg']); ?>" style="width:100%;height:90%;" title="<?php echo $vo['goodsName']; ?>"></a>
				</li>
				<?php } ?>
			</ul>
			<div class="wst-clear"></div>
		</div>
		
		<div class="rec-goods-list hide" id="fl_f_2_pl">
			<ul class="rec-goods-list">
				<?php $wstTagGoods =  model("common/Tags")->listGoods("best",0,5,86400); foreach($wstTagGoods as $key=>$vo){?>
				<li>
					<a href='<?php echo Url("home/goods/detail","id=".$vo["goodsId"]); ?>'><img src="__ROOT__/<?php echo WSTImg($vo['goodsImg']); ?>" style="width:100%;height:90%;" title="<?php echo $vo['goodsName']; ?>"></a>
				</li>
				<?php } ?>
			</ul>
			<div class="wst-clear"></div>
		</div>
		
		 <div class="second-buy">
		 	<a href='<?php echo Url("home/goods/detail","id=".$vo["goodsId"]); ?>'>
		 		<img src='__ROOT__/<?php echo WSTImg($vo["goodsImg"]); ?>' style="width:270px;height:270px;" />
		 		<div class="second-buy-time" style="z-index: 1000;">
		 			<span>03</span>:<span>04</span>:<span>13</span>
		 		</div>
		 	</a>
		 </div>
		</div> -->
		
		<!--<div class="rec-goods-list hide" id="fl_f_3_pl">
			<ul class="rec-goods-list">
				<?php $wstTagGoods =  model("common/Tags")->listGoods("hot",0,5,86400); foreach($wstTagGoods as $key=>$vo){?>
				<li>
				    <p class="rec-goods-desc"><a href='<?php echo Url("home/goods/detail","id=".$vo["goodsId"]); ?>' target='_blank'><?php echo $vo['goodsName']; ?></a></p>
					<div class="rec-goods-bottom">
					   <p class="rec-goods-price"><span>￥<?php echo $vo['shopPrice']; ?></span></p>
					</div>
				    <a href='<?php echo Url("home/goods/detail","id=".$vo["goodsId"]); ?>' target='_blank'>
					  <img class='goodsImg' data-original="__ROOT__/<?php echo WSTImg($vo['goodsImg']); ?>" title="<?php echo $vo['goodsName']; ?>"/>
					</a>
				</li>
				<?php } ?>
			</ul>
			<div class="wst-clear"></div>
		</div>-->
	



<!-- 
	<div class="buy-new-best-hot">
		<ul class="rec">
				<li class="rec-item j-rec-selected" id="fl_f_0" onmouseover="gpanelOver1(this);">
					<em></em>
					<a href="javascript:void(0)">商品推荐</a>
				</li>
				<li class="rec-item" id="fl_f_1" onmouseover="gpanelOver1(this);"><em></em><a href="javascript:void(0)">最新上架</a></li>
				<li class="rec-item" id="fl_f_2" onmouseover="gpanelOver1(this);"><em></em><a href="javascript:void(0)">精品促销</a></li>
				<li class="rec-item" id="fl_f_3" onmouseover="gpanelOver1(this);"><em></em><a href="javascript:void(0)">热销商品</a></li>
		</ul>
		
		<div class="rec-goods-list" id="fl_f_0_pl">
			<ul class="rec-goods-list">
			    <?php $wstTagGoods =  model("common/Tags")->listGoods("recom",0,5,86400); foreach($wstTagGoods as $key=>$vo){?>
				<li>
				    <p class="rec-goods-desc"><a href='<?php echo Url("home/goods/detail","id=".$vo["goodsId"]); ?>' target='_blank'><?php echo $vo['goodsName']; ?></a></p>
					<div class="rec-goods-bottom">
					   <p class="rec-goods-price"><span>￥<?php echo $vo['shopPrice']; ?></span></p>
					</div>
					<a href='<?php echo Url("home/goods/detail","id=".$vo["goodsId"]); ?>' target='_blank'>
					  <img class='goodsImg' data-original="__ROOT__/<?php echo WSTImg($vo['goodsImg']); ?>"  title="<?php echo $vo['goodsName']; ?>"/>
					</a>
				</li>
				<?php } ?>
			</ul>
			<div class="wst-clear"></div>
		</div> 
		
		<div class="rec-goods-list hide" id="fl_f_1_pl">
			<ul class="rec-goods-list">
				<?php $wstTagGoods =  model("common/Tags")->listGoods("new",0,5,86400); foreach($wstTagGoods as $key=>$vo){?>
				<li>
				    <p class="rec-goods-desc"><a href='<?php echo Url("home/goods/detail","id=".$vo["goodsId"]); ?>' target='_blank'><?php echo $vo['goodsName']; ?></a></p>
					<div class="rec-goods-bottom">
					  <p class="rec-goods-price"><span>￥<?php echo $vo['shopPrice']; ?></span></p>
					</div>
					<a href='<?php echo Url("home/goods/detail","id=".$vo["goodsId"]); ?>' target='_blank'>
					  <img class='goodsImg' data-original="__ROOT__/<?php echo WSTImg($vo['goodsImg']); ?>" title="<?php echo $vo['goodsName']; ?>" />
					</a>
				</li>
				<?php } ?>
			</ul>
			<div class="wst-clear"></div>
		</div>
		
		<div class="rec-goods-list hide" id="fl_f_2_pl">
			<ul class="rec-goods-list">
				<?php $wstTagGoods =  model("common/Tags")->listGoods("best",0,5,86400); foreach($wstTagGoods as $key=>$vo){?>
				<li>
				    <p class="rec-goods-desc"><a href='<?php echo Url("home/goods/detail","id=".$vo["goodsId"]); ?>' target='_blank'><?php echo $vo['goodsName']; ?></a></p>
					<div class="rec-goods-bottom">
					   <p class="rec-goods-price"><span>￥<?php echo $vo['shopPrice']; ?></span></p>
				    </div>
					<a href='<?php echo Url("home/goods/detail","id=".$vo["goodsId"]); ?>' target='_blank'>
					   <img class='goodsImg' data-original="__ROOT__/<?php echo WSTImg($vo['goodsImg']); ?>" title="<?php echo $vo['goodsName']; ?>"/>
					</a>
				</li>
				<?php } ?>
			</ul>
			<div class="wst-clear"></div>
		</div>
		
		<div class="rec-goods-list hide" id="fl_f_3_pl">
			<ul class="rec-goods-list">
				<?php $wstTagGoods =  model("common/Tags")->listGoods("hot",0,5,86400); foreach($wstTagGoods as $key=>$vo){?>
				<li>
				    <p class="rec-goods-desc"><a href='<?php echo Url("home/goods/detail","id=".$vo["goodsId"]); ?>' target='_blank'><?php echo $vo['goodsName']; ?></a></p>
					<div class="rec-goods-bottom">
					   <p class="rec-goods-price"><span>￥<?php echo $vo['shopPrice']; ?></span></p>
					</div>
				    <a href='<?php echo Url("home/goods/detail","id=".$vo["goodsId"]); ?>' target='_blank'>
					  <img class='goodsImg' data-original="__ROOT__/<?php echo WSTImg($vo['goodsImg']); ?>" title="<?php echo $vo['goodsName']; ?>"/>
					</a>
				</li>
				<?php } ?>
			</ul>
			<div class="wst-clear"></div>
		</div>
	</div>
 
	<?php if(is_array($floors) || $floors instanceof \think\Collection): $l = 0;$__LIST__ = is_array($floors) ? array_slice($floors,0,10, true) : $floors->slice(0,10, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($l % 2 );++$l;?>
	
	<div class="floor-top-ads">
	<?php $adsCode = "ads-".$l."-1"; $wstTagAds =  model("common/Tags")->listAds("$adsCode",3,86400); foreach($wstTagAds as $key=>$tad){?>
		<a href="<?php echo $tad['adURL']; ?>" <?php if(($tad['adURL']!='')): ?>onclick="WST.recordClick(<?php echo $tad['adId']; ?>)"<?php endif; ?> ><img class='goodsImg' data-original="__ROOT__/<?php echo $tad['adFile']; ?>"></a>
	<?php } ?>
	</div>
	
	
	
	<div class="floor-box">
		<div class="floor-header c<?php echo $l; ?>" id="c<?php echo $l; ?>">
			<div class="floor-header-f<?php echo $l; ?>">
				<p class="floor-left-title"><a name="<?php echo $l; ?>F"></a><?php echo $l; ?>F</p>
				<p class="floor-right-title"><?php echo str_replace('、','、',$vo['catName']); ?></p>
			</div>

		</div>
       
		
	
		
		<div class="floor-right" id="fl_<?php echo $l; ?>_0_pl">
			<div class="floor-right-ads">
			    
				<div class="wst-floor-slide-<?php echo $l; ?>" id="wst-floor-slide-<?php echo $l; ?>" style="height:344px;">
					<ul class="wst-floor-slide-items">
					    <?php $adsCode = "ads-".$l."-3"; $wstTagAds =  model("common/Tags")->listAds("$adsCode",99,86400); foreach($wstTagAds as $key=>$rad){?>
							<li style="z-index: 1;">
								<a href="<?php echo $rad['adURL']; ?>" <?php if(($rad['adURL']!='')): ?>onclick="WST.recordClick(<?php echo $rad['adId']; ?>)"<?php endif; ?>><img class='goodsImg' data-original="__ROOT__/<?php echo $rad['adFile']; ?>"></a>
							</li>
						<?php } ?>
					</ul>
					<div class="wst-floor-slide-numbox">
						<div id="wst-floor-slide-controls-<?php echo $l; ?>" >
						 <?php $adsCode = "ads-".$l."-3"; $wstTagAds =  model("common/Tags")->listAds("$adsCode",99,86400); foreach($wstTagAds as $k=>$rad){if($k == 1): ?>
							  		 <span class="curr"> </span>
							 <?php else: ?>
							  		 <span class=""> </span>
							 <?php endif; } ?>
						</div>
					</div>
				</div>
				
                
			    <div class="floor-left-ads">
			        <?php $adsCode = "ads-".$l."-2"; $wstTagAds =  model("common/Tags")->listAds("$adsCode",1,86400); foreach($wstTagAds as $key=>$lad){?>
					    <a href="<?php echo $lad['adURL']; ?>" <?php if(($lad['adURL']!='')): ?>onclick="WST.recordClick(<?php echo $lad['adId']; ?>)"<?php endif; ?>><img class='goodsImg' data-original="__ROOT__/<?php echo $lad['adFile']; ?>"> </a>
				    <?php } ?>
			    </div>
			</div>

			<div class="goods-list">
		     
			  <?php $wstTagGoods =  model("common/Tags")->listGoods("hot",$vo['catId'],6,86400); foreach($wstTagGoods as $key=>$cs){?>
		      <div class="goods goods-f<?php echo $l; ?>">
		      	<div class="img"><a target='_blank' href="<?php echo Url('home/goods/detail','id='.$cs['goodsId']); ?>" title="<?php echo $cs['goodsName']; ?>"><img title="<?php echo $cs['goodsName']; ?>" class='goodsImg' data-original="__ROOT__/<?php echo $cs['goodsImg']; ?>"/></a></div>
		      	<div class="p-name"><a target='_blank' href="<?php echo Url('home/goods/detail','id='.$cs['goodsId']); ?>" class="wst-redlink" title="<?php echo $cs['goodsName']; ?>"><?php echo WSTMSubstr($cs['goodsName'],0,33); ?></a></div>
		      	<div>
		      		<div class="p-price">￥<?php echo $cs['shopPrice']; ?></div>
		      	</div>
		      </div>
		      <?php } ?>
		     <div class='wst-clear'></div>
		   	</div>
			<div class="wst-clear"></div>
		</div> 

		
		<?php if(is_array($vo['children']) || $vo['children'] instanceof \think\Collection): $l2 = 0; $__LIST__ = $vo['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($l2 % 2 );++$l2;?>
		<div class="floor-right" id="fl_<?php echo $l; ?>_<?php echo $l2; ?>_pl" style="display:none;">
			<div class="goods-list">
		     
			 <?php $wstTagGoods =  model("common/Tags")->listGoods("recom",$vo1['catId'],10,86400); foreach($wstTagGoods as $key=>$vo2){?>
		      <div class="goods goods-f<?php echo $l; ?>">
		      	<div class="img"><a target='_blank' href="<?php echo Url('home/goods/detail','id='.$vo2['goodsId']); ?>" title="<?php echo $vo2['goodsName']; ?>"><img title="<?php echo $vo2['goodsName']; ?>" class='goodsImg' data-original="__ROOT__/<?php echo $vo2['goodsImg']; ?>"/></a></div>
		      	<div class="p-name"><a target='_blank' href="<?php echo Url('home/goods/detail','id='.$vo2['goodsId']); ?>" class="wst-redlink" title="<?php echo $vo2['goodsName']; ?>"><?php echo WSTMSubstr($vo2['goodsName'],0,33); ?></a></div>
		      	<div>
		      		<div class="p-price">￥<?php echo $vo2['shopPrice']; ?></div>
		      	</div>
		      </div>
		      <?php } ?>
		     <div class='wst-clear'></div>
		   	</div>
		</div>
		<?php endforeach; endif; else: echo "" ;endif; ?>
		<div class="wst-clear"></div>
	</div>
<?php endforeach; endif; else: echo "" ;endif; ?>  -->
	




	
<div id="floor-box-all">
<?php

	foreach ($floors as $key => $value) {
		foreach ($value['data'] as $k => $vv) {
			$good = $vv;
			break;
		}
?>
        <div class="floor-box">
		<!--这是头部的图片文字-->
	
			<div class="floor-header c1" id="c1">
		<div class="floor-header-f1">
			<p class="floor-left-title">
				<a name="1F"></a></p>
			<!-- <img src="wstmart/home/view/default/img/louTi1.png"/> -->
			<h1 style="height: 32px;line-height: 32px;border-left: 2px solid red;padding-left: 10px;margin-top: 20px;"><?php echo str_replace('、','、',$value['catName']); ?></h1>
		</div>
   	</div>

		   	<div class="left-big-pic">
		   		<?php if((!empty($value['left']))): ?>
	            <a href="<?php echo Url('home/goods/detail','id='.$value['left']['goodsId']); ?>"><img class="left-big-pic-img" src="__ROOT__/<?php echo $value['left']['goodsImg']; ?>" title="<?php echo $value['left']['goodsName']; ?>"></a>
            	<dl class="left-big-name">
            		<dt><?php echo WSTMSubstr($value['left']['goodsName'],0,33); ?></dt>
            		<dd class="jx-price"><span class="jx">经销价</span> ￥<span class="price"><?php echo $value['left']['shopPrice']; ?></span></dd>&nbsp;&nbsp;
            		<dd class="yj-price">原价 ￥<span><?php echo $value['left']['marketPrice']; ?></span></dd>
            	</dl>
		   		<?php endif; ?>
			</div>

			<div class="right-small-pics">
	<?php	
				foreach ($value['data'] as $k => $cs) {
	?>				
						<a  target='_blank' href="<?php echo Url('home/goods/detail','id='.$cs['goodsId']); ?>">
						<div class="right-big-one ">
								<div class="top"></div>
					<div class="left"></div>
					<div class="bottom"></div>
					<div class="right"></div>

					
					<img src="__ROOT__/<?php echo $cs['goodsImg']; ?>" title="<?php echo $cs['goodsName']; ?>" align="" data-original="__ROOT__/<?php echo $cs['goodsImg']; ?>" />
					<dl class="right-small-name">
						<dt><?php echo WSTMSubstr($cs['goodsName'],0,33); ?></dt>
						<dd>
							<div class="jx">经销价</div>
							<div class="jx-price"><span class="colour">￥</span><span class="price"><?php echo $cs['shopPrice']; ?></span></div>
							<div class="yj-price"><span>原价</span> ￥<span><?php echo $cs['marketPrice']; ?></span></div>
							
						</dd>
					</dl>

									    

						</div>
						</a>
	<?php
			}
	?>
			</div>
		</div>	
<?php
	}	    
?>
</div>		


<script type="text/javascript">
	$(".right-big-one").mouseenter(function(){
	// $(this).children("p").animate({
	// 	"height":"40px",
	// },500);
	$(this).children("div:even").stop().animate({
		"width":"100%",
	},500).css("background","#333");
	$(this).children("div:odd").stop().animate({
		"height":"100%",
	},500).css("background","#333");
});

$(".right-big-one").mouseleave(function(){
	// $(this).children("p").animate({
	// 	"height":"0px"
	// });
	$(this).children("div:even").stop().animate({
		"width":"0%",
	},500).css("background","#333");
	$(this).children("div:odd").stop().animate({
		"height":"0%",
	},500).css("background","#333");

});


</script>



<script type="text/javascript">
	 $(function(){

	 	var offsetTop = $('.wst-ads').offset().top; //元素距离顶部的高度
	 
	    $(window).scroll(function(){
	     var scrollTop = $(document).scrollTop();
	     if(scrollTop>offsetTop){
	        $('#fixed').show();
	        $('#fixed').css({'position':'fixed'});
	        $('#classify').css({'position':'fixed','top':'10px','width':'190px'});


	        // var toleft =parseInt($(".fixed_top").css('left'))+200;
	        // console.log(toleft);
	        $("#classification").css({"background":"#E60B49",'width':'190px','height':'40px'});
	        $('#index_menus_sub').css({'margin-top':'16px'});

	        // 搜索框

	        // $('.wst-search-box').css({'position':'fixed','top':0,'margin-left':'','z-index':'10',"height":"60px"});
	        // $('.wst-search').css({'margin-top':'9px'});
	       // $('.fixed_top').html($('#lll').html());
	       // $('.wst-search').css({'position':'relative','width':'531px','margin-top':'9px','margin-left':'400px','z-index':'4'});
	     }else{
	        $('#fixed').hide();
	        $("#fixed").css("position", "static");
	        $('#classify').css({'position':'static','width':''}); 
	         $("#classification").css({"background-color":"#8a072c",'height':'36px'});
	         $('#index_menus_sub').css({'margin-top':''});

	         // 搜索框
	         // $('.wst-search-box').css({'position':'static'});
	         // $('.wst-search').css({'margin-top':'','left':''});
	          // $('.wst-search').css({'position':'','width':'','margin-top':'','margin-left':'','z-index':''});
	     }
	   });

 

});
</script>
<!-- <div id="screen-left-nav" style="left:-40px;display:block;">
	<ul>
		<?php $__FOR_START_4725__=1;$__FOR_END_4725__=$l+1;for($i=$__FOR_START_4725__;$i < $__FOR_END_4725__;$i+=1){  if ($i>10)break;  ?>
		<li class="lnav" id="F<?php echo $i; ?>" ><a href="javascript:;" onfocus="this.blur();" ><?php echo $i; ?>F</a></li>
		<?php } ?>
	</ul> 
</div>-->
<div id="screen-left-nav" style="left: 200px; display:none;">
	<!--<ul>
		<?php $__FOR_START_21932__=1;$__FOR_END_21932__=$l+1;for($i=$__FOR_START_21932__;$i < $__FOR_END_21932__;$i+=1){  if ($i>10)break;  ?>
		<li class="lnav" id="F<?php echo $i; ?>" ><a href="javascript:;" onfocus="this.blur();" ><?php echo $i; ?>F</a></li>
		<?php } ?>
	</ul>-->
<ul id="floornav">
	<li class="lnav Louti" id="F1" >
		<img src="__STYLE__/img/left1.png"/><span>服饰鞋帽</span>
		<div class="screen-left-nav-line"></div>
	</li>
	<li class="lnav Louti" id="F2">
		<img src="__STYLE__/img/lfte10.png"/><span>礼品箱包</span>
		<div class="screen-left-nav-line"></div>
	</li>
	<li class="lnav Louti" id="F3">
		<img src="__STYLE__/img/lfte9.png"/><span>家居家装</span>
		<div class="screen-left-nav-line"></div>
	</li>
	<li class="lnav Louti" id="F4">
		<img src="__STYLE__/img/lfte8.png"/><span>数码办公</span>
		<div class="screen-left-nav-line"></div>
	</li>
	<li class="lnav Louti" id="F5">
		<img src="__STYLE__/img/lfte7.png"/><span>家用电器</span>
		<div class="screen-left-nav-line"></div>
	</li>
	<li class="lnav Louti" id="F6">
		<img src="__STYLE__/img/lfte6.png"/><span>个护化妆</span>
		<div class="screen-left-nav-line"></div>
	</li>
	<li class="lnav Louti" id="F7">
		<img src="__STYLE__/img/lfte5.png"/><span>珠宝手表</span>
		<div class="screen-left-nav-line"></div>
	</li>
	<li class="lnav Louti" id="F8">
		<img src="__STYLE__/img/lfte4.png"/><span>食品饮料</span>
		<div class="screen-left-nav-line"></div>
	</li>
	<li class="lnav Louti" id="F9">
		<img src="__STYLE__/img/lfte3.png"/><span>运动健康</span>
		<div class="screen-left-nav-line"></div>
	</li>
	<li class="lnav Louti" id="F10">
		<img src="__STYLE__/img/lfte2.png"/><span>汽车用品</span>
		<!-- <div class="screen-left-nav-line"></div> -->
	</li>
	<!-- <li class="lnav Louti" id="F11">
		<img src="wstmart/home/view/default/img/left11.png"/><span>玩具乐器</span>
		<div class="screen-left-nav-line"></div>
	</li> -->
	
</ul>

	
</div>




<link href="__STYLE__/css/right_cart.css?v=<?php echo $v; ?>" rel="stylesheet">
<div class="j-global-toolbar">
	<div class="toolbar-wrap j-wrap ">
		<div class="toolbar">
			<div class="toolbar-panels j-panel">
				<div style="visibility: hidden;" id="gb" class="j-content toolbar-panel tbar-panel-cart toolbar-animate-out">
					<h3 class="tbar-panel-header j-panel-header">
						<a href="<?php echo Url('home/carts/index'); ?>" class="title"><i></i><em class="title">购物车</em></a>
						<span class="close-panel j-close" title='关闭' id="close-panel"></span>
					</h3>
					<div class="tbar-panel-main">
						<div class="tbar-panel-content j-panel-content">
						    <?php if(session('WST_USER.userId') == 0): ?>
							<div id="j-cart-tips" class="tbar-tipbox hide">
								<div class="tip-inner">
									<span class="tip-text">还没有登录，登录后商品将被保存</span>
									<a href="#none" onclick='WST.loginWindow()' class="tip-btn j-login">登录</a>
								</div>
							</div>
							<?php endif; ?>
							<div id="j-cart-render">
								<div id='cart-panel' class="tbar-cart-list"></div>
								  <script id="list-rightcart" type="text/html">
								  {{#
                                    var shop,goods,specs;
                                    for(var key in d){
                                        shop = d[key];
					                    for(var i=0;i<shop.list.length;i++){
                                           goods = shop.list[i];
						                   goods.goodsImg = WST.conf.ROOT+"/"+goods.goodsImg;
						                   specs = '';
						                   if(goods.specNames && goods.specNames.length>0){
							                  for(var j=0;j<goods.specNames.length;j++){
								                 specs += goods.specNames[j].itemName+ " ";
							                  }
						                   }
                                   }}
                                   
								   <div class="tbar-cart-item" id="shop-cart-{{shop.shopId}}">
							          <div class="jtc-item-promo">
							            <div class="promo-text">{{shop.shopName}}</div>
							          </div>
								      <div class="jtc-item-goods j-goods-item-{{goods.cartId}}" dataval="{{goods.cartId}}">
								          <div class='wst-lfloat'>
			                                 <input type='checkbox' id='rcart_{{goods.cartId}}' class='rchk' onclick='javascript:checkRightChks({{goods.cartId}},this);' {{# if(goods.isCheck==1){}}checked{{# } }}/></div>
									      <span class="p-img"><a target="_blank" href="{{WST.U('home/goods/detail','id='+goods.goodsId)}}" target="_blank"><img src="{{goods.goodsImg}}" title="{{goods.goodsName}}" height="50" width="50"></a></span>
									      <div class="p-name">
									          <a target="_blank" title="{{(goods.goodsName+((specs!='')?"【"+specs+"】":""))}}" href="{{WST.U('home/goods/detail','id='+goods.goodsId)}}">{{WST.cutStr(goods.goodsName,22)}}<br/>{{specs}}</a>
									      </div>
									      <div class="p-price">
									          <strong>¥<span id='gprice_{{goods.cartId}}'>{{goods.shopPrice}}</span></strong> 
									          <div class="wst-rfloat">
									             <a href="#none" class="buy-btn" id="buy-reduce_{{goods.cartId}}" onclick="javascript:WST.changeIptNum(-1,'#buyNum','#buy-reduce,#buy-add','{{goods.cartId}}','statRightCartMoney')">-</a>
									             <input type="text" id="buyNum_{{goods.cartId}}" class="right-cart-buy-num" value="{{goods.cartNum}}" data-max="{{goods.goodsStock}}" data-min="1" onkeyup="WST.changeIptNum(0,'#buyNum','#buy-reduce,#buy-add',{{goods.cartId}},'statRightCartMoney')" autocomplete="off"  onkeypress="return WST.isNumberKey(event);" maxlength="6"/>
									             <a href="#none" class="buy-btn" id="buy-add_{{goods.cartId}}" onclick="javascript:WST.changeIptNum(1,'#buyNum','#buy-reduce,#buy-add','{{goods.cartId}}','statRightCartMoney')">+</a>
									          </div>
									     </div>
									      <span onclick="javascript:delRightCart(this,{{goods.cartId}});" dataid="{{shop.shopId}}|{{goods.cartId}}" class="goods-remove" title="删除"></span>
									 </div>
								 </div>    
								 {{# } } }} 
                                 </script>   	
							</div>
						</div>
						
					</div>
					<div class="tbar-panel-footer j-panel-footer">
						<div class="tbar-checkout">
							<div class="jtc-number">已选<strong id="j-goods-count">0</strong>件商品 </div>
							<div class="jtc-sum"> 共计：¥<strong id="j-goods-total-money">0</strong> </div>
							<a class="jtc-btn j-btn" href="#none" onclick="javascript:jumpSettlement()">去购物车结算</a>
						</div>
					</div>
					<em class="arrow3 arrow-cart"></em>
				</div>

				<div style="visibility: hidden;" data-name="follow" class="j-content toolbar-panel  tbar-panel-follow">
					<h3 class="tbar-panel-header j-panel-header">
						<a href="<?php echo Url('home/favorites/goods'); ?>" target="_blank" class="title"> <i></i> <em class="title">我的关注</em> </a>
						<span class="close-panel j-close" title='关闭'></span>
					</h3>
					<div class="tbar-panel-main">
						<div class="tbar-panel-content j-panel-content">
							<div class="tbar-tipbox">
								<div class="tip-inner">
									<ul id="list-goods">
										<!-- <li class="lii">
											<a href=""><img src="" style="width:100px;height:100px; border:1px red solid"></a>
											<a href="" class="add-cart-button1">取消关注</a>
											<p>￥1222</p>
										</li>
										<li class="lii">
											<a href=""><img src=""></a>
											<a href="" class="add-cart-button1">取消关注</a>
											<p>￥1222</p>
										</li>
										<li class="lii">
											<a href=""><img src=""></a>
											<a href="" class="add-cart-button1">取消关注</a>
											<p>￥1222</p>
										</li>
										<li class="lii">
											<a href=""><img src=""></a>
											<a href="" class="add-cart-button1">取消关注</a>
											<p>￥1222</p>
										</li>
										<li class="lii">
											<a href=""><img src=""></a>
											<a href="" class="add-cart-button1">取消关注</a>
											<p>￥1222</p>
										</li> -->
										
									</ul>
								</div>	
							</div>
						</div>
					</div>
					<div class="tbar-panel-footer j-panel-footer"></div>
				</div>
				<script id="list" type="text/html">
					{{# for(var i = 0; i < d.length; i++){}}
						<li class="lii">
							<a href=""><img src="__ROOT__/{{ d[i].goodsImg }}" title="{{ d[i].goodsName }}" style="width:100px;height:100px;"></a>
							<a href="javascript:void(0);" class="add-cart-button1" onclick="javascript:cancelFavorite({{ d[i].favoriteId }},0)">取消关注</a>
							<p>￥{{ d[i].marketPrice }}</p>
						</li>
					{{# } }}

					<script type="text/javascript">
						$('.lii').hover(
							function(){
								
								$(this).find('.add-cart-button1').show();
							},function(){
								
								$(this).find('.add-cart-button1').hide();
						});
					</script>

				</script>	
				<div style="visibility: hidden;" class="j-content toolbar-panel tbar-panel-history toolbar-animate-in">
					<h3 class="tbar-panel-header j-panel-header">
						<a href="#none" class="title"> <i></i> <em class="title">我的足迹</em> </a>
						<span class="close-panel j-close" title='关闭'></span>
					</h3>
					<div class="tbar-panel-main">
						<div class="tbar-panel-content j-panel-content">
							<div class="jt-history-wrap">
								<ul id='history-goods-panel'></ul>
								<script id="list-history-goods" type="text/html">
								{{# 
                                 for(var i = 0; i < d.length; i++){ 
                                  d[i].goodsImg = WST.conf.ROOT+"/"+d[i].goodsImg;
                                 }}
								   <li class="jth-item">
										<a target='_blank' href="{{WST.U('home/goods/detail','id='+d[i].goodsId)}}" class="img-wrap"><img src="{{d[i].goodsImg}}" height="100" width="100"> </a>
										<a class="add-cart-button" href="javascript:WST.addCart({{d[i].goodsId}});">加入购物车</a>
										<a href="#none" class="price">￥{{d[i].shopPrice}}</a>
									</li>
								{{# } }}
                                </script>
							</div>
						</div>
					</div>
					<div class="tbar-panel-footer j-panel-footer"></div>
				</div>
			</div>
		
			<div class="toolbar-header"></div>
			
			<div class="toolbar-tabs j-tab">
				<?php if(session('WST_USER.userId') >0): ?>
				<div class="toolbar-tab tbar-tab-self tab-fade" >
					<i class="tab-ico"></i>
					<em class="tab-fade-box i-tab-login">
						<span id="close" title='关闭' ></span>
						<div class="tab-fade-photo">
							<img class='usersImg' data-original="<?php echo WSTUserPhoto(session('WST_USER.userPhoto')); ?>" />
						</div>
						<span class="logged">欢迎您，<?php echo session('WST_USER.userName')?session('WST_USER.userName'):session('WST_USER.loginName'); ?>
								&nbsp;|&nbsp;<a href="javascript:WST.logout();" style="font-size: 16px;color: #000000;">退出</a>
						</span>
						<!-- <div style="position: absolute;bottom: 20px;text-align: center;width: 280px;">
							<a href="<?php echo Url('home/users/regist'); ?>">注册</a>
							<a  class="fgxl" href="#" style="margin:0px 5px -3px 5px;"></a>
							<a href="#">第三方登录</a>
						</div> -->
					<em class="arrow3 arrow-login"></em>	
					</em>
					<!-- <span class="tab-sub j-cart-count hide"></span> -->
				</div>
				<?php else: ?>
				<div class="toolbar-tab tbar-tab-self tab-fade" >
					<i class="tab-ico"></i>
					<em class="tab-fade-box i-tab-login">
					<span id="close" title='关闭' ></span>
						<div class="tab-fade-photo"></div>
						<span class="no_logged" >您好&nbsp;请先&nbsp;<a href="#" onclick='WST.loginWindow()' style="font-size: 16px;color: #000000;">登录</a>&nbsp;!</span>
						<div class="register">
							<a href="<?php echo Url('home/users/regist'); ?>">注册</a>
							<a  class="fgxl" href="#" style="margin:0px 5px -3px 5px;"></a>
							<a href="#">第三方登录</a>
						</div>
					<em class="arrow3 arrow-login"></em>	
					</em>
					<!-- <span class="tab-sub j-cart-count hide"></span> -->
				</div>
					<?php endif; ?>
				<div class="fgx"></div>
				
				<div class="toolbar-tab tbar-tab-cart carsNum" style="height:130px;">
					<i class="tab-ico"></i>
					<span class="tab-cart" style="">购物车</span>
					<!--<i class="tab-cart-buy-num "></i>-->
					<span class="tab-cart-buy-num num" id="goodsTotalNum">0</span>
				</div>
				<div class="fgx"></div>
				
				<div class="toolbar-tab tbar-tab-follow" <?php  if(session('WST_USER.userId') == 0){ echo "onclick='WST.loginWindow()' ";}else{ echo "onclick='WST.collection()'"; } ?>>
					<i class="tab-ico"></i>
					<em class="tab-text">收藏</em>
					<!-- <span class="tab-sub j-count hide">0</span>  -->
				</div>
				<div class="fgx"></div>
			
				<div class=" toolbar-tab tbar-tab-history " <?php  if(session('WST_USER.userId') == 0){ echo "onclick='WST.loginWindow()'";}else{ echo "onclick='WST.record()'";} ?>>
					<i class="tab-ico"></i>
					<em class="tab-text">浏览记录</em>
					<!-- <span class="tab-sub j-count hide">1</span> -->
				</div>
				<div class="fgx"></div>
				 <a target='_blank' href='<?php echo Url("home/messages/index"); ?>' onclick='WST.position(50,0)'>
				<div class="toolbar-tab tbar-tab-message">
				 
					<i class="tab-ico"></i>
					<em class="tab-text">信息</em>
					<span class="tab-sub j-message-count hide"></span> 
				 
				</div> </a>
				<div class="fgx"></div>
			</div>
			
			<div class="toolbar-footer">
				<!-- <div class="toolbar-tab tbar-tab-service"> 
					<a href="#"> <i class="tab-ico  "></i> <em class="footer-tab-text">客服</em> </a>
				</div> -->
				<div class="toolbar-tab tbar-tab-code tab-fade" id="code"> 
					<a href="#"> 
						<i class="tab-ico  "></i> 
						<span class="tab-fade-box i-tab-code">
							<img class="tab-code-img" src="__STYLE__/img/erweima.png" />
							<em class="arrow3 arrow-code"></em>
						</span>
						
					</a> 
				</div>
				<script type="text/javascript">
					$("#code").hover(function(){$(this).find(".tab-fade-box").show();},function(){$(this).find(".tab-fade-box").hide();});
				</script>
				 <a href="#return_top"><div class="toolbar-tab tbar-tab-top" style="margin-top:0px;"> <i class="tab-ico  "></i> <em class="footer-tab-text">返回顶部</em>  </div></a>
				<div class=" toolbar-tab tbar-tab-feedback"  style='display:none'> <a href="#" target="_blank"> <i class="tab-ico"></i> <em class="footer-tab-text ">反馈</em> </a> </div>
			</div>
			<div class="toolbar-mini"></div>
			
			
		</div>
		<div id="j-toolbar-load-hook"></div>		
	</div>
</div>
<script type='text/javascript' src='__STYLE__/users/favorites/favorites.js?v=<?php echo $v; ?>'></script>
<script type='text/javascript' src='__STYLE__/js/right_cart.js?v=<?php echo $v; ?>'></script>




	<!-- <div style="border-top: 1px solid #df2003;padding-bottom:25px;margin-top:35px;min-width:1200px;"></div>
<div class="wst-footer-flink">
	<div class="wst-footer" >

		<div class="wst-footer-cld-box">
			<div class="wst-footer-fl" style="text-align: left;padding-left:10px;">友情链接</div>

			<div style="padding-left:40px;">
				<?php $wstTagFriendlink =  model("common/Tags")->listFriendlink(99,86400); foreach($wstTagFriendlink as $key=>$vo){?>
				<div style="float:left;"><a class="flink-hover" href="<?php echo $vo['friendlinkUrl']; ?>"  target="_blank"><?php echo $vo["friendlinkName"]; ?></a>&nbsp;&nbsp;</div> 
				<?php } ?>
				<div class="wst-clear"></div>
			</div>
		</div>

	</div>
</div> -->

<!-- <ul class="wst-footer-info">
	<li><div class="wst-footer-info-img"><img src="__STYLE__/img/icon_play.png"></div>
		<div class="wst-footer-info-text">
			<h1>支付宝支付</h1>
			<p>支付宝签约商家</p>
		</div>
	</li>
	<li><div class="wst-footer-info-img"><img src="__STYLE__/img/icon_zhengpin.png"></div>
		<div class="wst-footer-info-text">
			<h1>正品保证</h1>
			<p>100%原产地</p>
		</div>
	</li>
	<li><div class="wst-footer-info-img"><img src="__STYLE__/img/icon_thwy.png"></div>
		<div class="wst-footer-info-text">
			<h1>退货无忧</h1>
			<p>前天退货保障</p>
		</div>
	</li>
	<li><div class="wst-footer-info-img"><img src="__STYLE__/img/icon_mfps.png"></div>
		<div class="wst-footer-info-text">
			<h1>免费配送</h1>
			<p>满98元包邮</p>
		</div>
	</li>
	<li><div class="wst-footer-info-img"><img src="__STYLE__/img/icon_hdfk.png"></div>
		<div class="wst-footer-info-text">
			<h1>货到付款</h1>
			<p>400城市送货上门</p>
		</div>
	</li>
</ul> -->
<div style="clear:both;"></div>
<div class="wst-footer-info">
	<img class="wst-footer-info-img" src="__STYLE__/img/juxing-401.png"/>
</div>
<div class="wst-footer-help">
	<div class="wst-footer">
		<!-- <div class="wst-footer-hp-ck1">
			<?php $_result=WSTHelps(5,6);if(is_array($_result) || $_result instanceof \think\Collection): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?>
			<div class="wst-footer-wz-ca">
				<div class="wst-footer-wz-pt">
					<span class="wst-footer-wz-pn"><?php echo $vo1["catName"]; ?></span>
					<ul style='margin-left:25px;'>
						<?php if(is_array($vo1['articlecats']) || $vo1['articlecats'] instanceof \think\Collection): $i = 0; $__LIST__ = $vo1['articlecats'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
						<li style='list-style:disc;color:#999;font-size:9px;'>
						<a href="<?php echo Url('Home/Helpcenter/view',array('id'=>$vo2['articleId'])); ?>"><?php echo WSTMSubstr($vo2['articleTitle'],0,8); ?></a>
						</li>
						<?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
				</div>
			</div>
			<?php endforeach; endif; else: echo "" ;endif; ?>

			<div class="wst-contact">
				<ul>
					<li style="height:30px;">
						<div class="icon-phone"></div><p class="call-wst">服务热线：</p>
					</li>
					<li style="height:38px;">
						<?php if((WSTConf('CONF.serviceTel')!='')): ?><p class="email-wst"><?php echo WSTConf('CONF.serviceTel'); ?></p><?php endif; ?>
					</li>
					<li style="height:85px;">
						<div class="qr-code" style="position:relative;">
							<img src="__STYLE__/img/wst_qr_code.jpg" style="height:110px;">
							<div class="focus-wst">
							    <?php if((WSTConf('CONF.serviceQQ')!='')): ?>
								<p class="focus-wst-qr">在线客服：</p>
								<p class="focus-wst-qra">
						          <a href="tencent://message/?uin=<?php echo WSTConf('CONF.serviceQQ'); ?>&Site=QQ交谈&Menu=yes">
									  <img border="0" src="http://wpa.qq.com/pa?p=1:<?php echo WSTConf('CONF.serviceQQ'); ?>:7" alt="QQ交谈" width="71" height="24" />
								  </a>
								</p>
          						<?php endif; if((WSTConf('CONF.serviceEmail')!='')): ?>
								<p class="focus-wst-qr">商城邮箱：</p>
								<p class="focus-wst-qre"><?php echo WSTConf('CONF.serviceEmail'); ?></p>
								<?php endif; ?>
							</div>
						</div>
					</li>
				</ul>
			</div>


			<div class="wst-clear"></div>
		</div> -->

	    <div class="wst-footer-hp-ck3">
	        <div class="links">
	           <?php $navs = WSTNavigations(1); if(is_array($navs) || $navs instanceof \think\Collection): $i = 0; $__LIST__ = $navs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
               <a href="<?php echo $vo['navUrl']; ?>" <?php if($vo['isOpen']==1): ?>target="_blank"<?php endif; ?>><?php echo $vo['navTitle']; ?></a>
               <?php if($i< count($navs)): ?>&nbsp;&nbsp;|&nbsp;&nbsp;<?php endif; endforeach; endif; else: echo "" ;endif; ?>
	        </div>
	        <div class="copyright">
	        <?php 
	        	if(WSTConf('CONF.mallFooter')!=''){
	         		echo htmlspecialchars_decode(WSTConf('CONF.mallFooter'));
	        	}
	         
				
			 if(WSTConf('CONF.mallLicense') == ''): ?>
	        <div>
				<!-- Copyright©2016 Powered By <a target="_blank" href="http://www.wstmart.net">WSTMart</a> -->
				杭州市江干区解放东路37号财富金融中心西楼3202
			</div>
			 <div>
				备案号 浙ICP备17002020号
			</div>
			<?php else: ?>
				<div id="wst-mallLicense" data='1' style="display:none;">Copyright©2016 Powered By <a target="_blank" href="http://www.wstmart.net">WSTMart</a></div>
	        <?php endif; ?>
	        </div>
	    </div>
	</div>
</div>
<?php echo hook('initCronHook'); ?>


<script type='text/javascript' src='__ROOT__/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>'></script>
<script type='text/javascript' src='__STYLE__/js/index.js?v=<?php echo $v; ?>'></script>



</body>


</html>