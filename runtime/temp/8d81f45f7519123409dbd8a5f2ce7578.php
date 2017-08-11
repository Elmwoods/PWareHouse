<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:75:"E:\wamp\www\jingomall\jingo\addons\decoration\view\home/shops/shop_set.html";i:1497401459;s:101:"E:\wamp\www\jingomall\jingo\addons\decoration\view\..\..\..\wstmart\home\view\default\shops\base.html";i:1499907752;s:101:"E:\wamp\www\jingomall\jingo\addons\decoration\view\..\..\..\wstmart\home\view\default\header_top.html";i:1499654586;s:97:"E:\wamp\www\jingomall\jingo\addons\decoration\view\..\..\..\wstmart\home\view\default\footer.html";i:1497249046;}*/ ?>
<!doctype html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>店铺装修-卖家中心<?php echo WSTConf('CONF.mallTitle'); ?></title>
<link href="__STYLE__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="__STYLE__/css/shop.css?v=<?php echo $v; ?>" rel="stylesheet">


<script type="text/javascript" src="__STATIC__/js/jquery.min.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__STATIC__/plugins/layer/layer.js?v=<?php echo $v; ?>"></script>
	  
<script type='text/javascript' src='__STATIC__/js/common.js?v=<?php echo $v; ?>'></script>

<script type='text/javascript' src='__STYLE__/js/common.js?v=<?php echo $v; ?>'></script>
<script type='text/javascript' src='__ROOT__/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>'></script>
<script>
window.conf = {"ROOT":"__ROOT__","APP":"__APP__","STATIC":"__STATIC__","SUFFIX":"<?php echo config('url_html_suffix'); ?>", "SMS_VERFY":"<?php echo WSTConf('CONF.smsVerfy'); ?>","PHONE_VERFY":"<?php echo WSTConf('CONF.phoneVerfy'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>","IS_LOGIN":"<?php if((int)session('WST_USER.userId')>0): ?>1<?php else: ?>0<?php endif; ?>","TIME_TASK":"1","MESSAGE_BOX":"<?php echo WSTShopMessageBox(); ?>","ROUTES":'<?php echo WSTRoute(); ?>'}
	<?php echo WSTLoginTarget(1); ?>
$(function() {
	WST.initShopCenter();
});
</script>
</head>
<body>


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



	
<div class='wst-lite-bac'>
<div class='wst-lite-container'>
   <div class='wst-logo'><a href='<?php echo \think\Request::instance()->root(true); ?>/home'><img src="__ROOT__/<?php echo WSTConf('CONF.mallLogo'); ?>" height="80" width='160'></a></div>
   <div class="wst-lite-tit"><span>卖家中心</span><a class="wst-lite-in" href='<?php echo \think\Request::instance()->root(true); ?>/mall'>返回商城首页</a></div>
   <div class="wst-lite-sea">
      <div class='search'>
      	  <input type="hidden" id="search-type" value="<?php echo isset($keytype)?1:0; ?>"/>

      	  <ul class="j-search-box">
            <li class="j-search-type">
              搜<span><?php if(isset($keytype)): ?>店铺<?php else: ?>商品<?php endif; ?></span>&nbsp;<i class="arrow"> </i>
            </li>
            <li class="j-type-list">
              <?php if(isset($keytype)): ?>
              <div data="0">商品</div>
              <?php else: ?>
              <div data="1">店铺</div>
              <?php endif; ?>
            </li>
          </ul>
          
	      <input type="text" id='search-ipt' class='search-ipt' value='<?php echo isset($keyword)?$keyword:""; ?>'/>
	      <div id='search-btn' class="search-btn" onclick='javascript:WST.search(this.value)'></div>
      </div>
   </div>
   <div class="wst-clear"></div>
</div>
<div class="wst-clear"></div>
</div>

<div class="wst-wrap">
          <div class='wst-header'>
			<div class="wst-shop-nav">
				<div class="wst-nav-box">
				    <?php $homeMenus = WSTHomeMenus(1); if(is_array($homeMenus['menus']) || $homeMenus['menus'] instanceof \think\Collection): $i = 0; $__LIST__ = $homeMenus['menus'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
						<a href="__ROOT__/<?php echo $vo['menuUrl']; ?>?homeMenuId=<?php echo $vo['menuId']; ?>"><li class="liselect wst-lfloat <?php if(($vo['menuId'] == $homeMenus['menuId1'])): ?>wst-nav-boxa<?php endif; ?>"><?php echo $vo['menuName']; ?></li></a>
					<?php endforeach; endif; else: echo "" ;endif; ?>
					<div class="wst-clear"></div>
				</div>
			</div>
			<div class="wst-clear"></div>
		</div>
          <div class='wst-nav'></div>
          <div class='wst-main'>
            <div class='wst-menu'>
              <?php if(isset($homeMenus['menus'][$homeMenus['menuId1']]['list'])): if(is_array($homeMenus['menus'][$homeMenus['menuId1']]['list']) || $homeMenus['menus'][$homeMenus['menuId1']]['list'] instanceof \think\Collection): $i = 0; $__LIST__ = $homeMenus['menus'][$homeMenus['menuId1']]['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menus): $mod = ($i % 2 );++$i;?>
              	<span class='wst-menu-title'><?php echo $menus['menuName']; ?><img src="__STYLE__/img/user_icon_sider_zhankai.png"></span>
              	<ul>
                <?php if(isset($menus['list'])): if(is_array($menus['list']) || $menus['list'] instanceof \think\Collection): $k = 0; $__LIST__ = $menus['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($k % 2 );++$k;?>
                  	<li class="<?php if(($homeMenus['menuId3']==$menu['menuId'])): ?>wst-menua<?php endif; ?> wst-menuas" onclick="getMenus('<?php echo $menu['menuId']; ?>','<?php echo $menu['menuUrl']; ?>')">
                  	<?php echo $menu['menuName']; ?>
                  	<span id="mId_<?php echo $menu['menuId']; ?>"></span>
                  	</li>
                	<?php endforeach; endif; else: echo "" ;endif; endif; ?>
              	</ul>
              	<?php endforeach; endif; else: echo "" ;endif; endif; ?>
              
             
             
            </div>
            <div class='wst-content'>
            
<style>
label{margin-right:10px;}
</style>
<div id='tab' class="wst-tab-box">
	<div class="wst-shop-head"><span>店铺装修</span></div>
    <div class="wst-tab-content" style='width:99%;margin-bottom: 10px;'>
        <div class="wst-tab-item" style="position: relative;">
           	<table id='vinfo_1' class='wst-form'> 
			  	<tr>
					<th width='150'>启用店铺装修：</th>
			     	<td>
			     		<label><input type="radio" name="userDecoration" value="1" <?php echo ($userDecoration==1)?"checked":"" ?>>是</label>
			          	<label><input type="radio" name="userDecoration" value="0" <?php echo ($userDecoration==0)?"checked":"" ?>>否</label>
			          	<p>选择是否使用店铺装修模板；<br>
			         		 如选择“是”，店铺首页背景、头部、导航以及上方区域都将根据店铺装修模板所设置的内容进行显示；<br>
			         		 如选择“否”则使用的系统预设值风格进行显示。
			         	</p>
			     	</td>
			  	</tr>
			  	<tr><td colspan="2" style="text-align: center;"></td></tr>
			  	<tr>
					<th width='150'>店铺装修：</th>
			     	<td>
				        <a class="s-btn" href="<?php echo addon_url('decoration://decoration/edit',array('id'=>$decorationId)); ?>" target="_blank" style="background-color: #f0a869;">装修页面</a>
				        <a class="s-btn" href="javascript:;" onclick="decorationBuild(<?php echo $decorationId; ?>);" style="background-color: #5BB75B;">生成页面</a>
				        <p>点击“装修页面”按钮，在新窗口对店铺首页进行装修设计；<br/>
							预览效果满意后，点击“生成页面”按钮则可将预览效果保存为您的“店铺装修”风格模板。
						</p>
			     	</td>
			  	</tr>
			  	<tr>
			     	<td colspan="2" style="text-align: center;"><br/>
				       <a class="s-btn" onclick="javascript:saveConf()">保&nbsp;存</a>
			     	</td>
			  	</tr>
			</table>
        </div>
        <div class="wst-tab-item" style="display:none;">
           
        </div>
    </div>
</div>

            </div>
          </div>
          <div style='clear:both;'></div>
          <br/>
        </div>

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


<script type='text/javascript' src='__ROOT__/addons/decoration/view/home/shops/static/shop_config.js?v=<?php echo $v; ?>'></script>

<script>
function getMenus(menuId,menuUrl){
    $.post(WST.U('home/index/getMenuSession'), {menuId:menuId}, function(data){
    	location.href=WST.U(menuUrl);
    });
}
</script>
</body>
</html>