<?php if (!defined('THINK_PATH')) exit(); /*a:8:{s:76:"E:\wamp\www\jingomall\jingo\addons\decoration\view\home/shops/shop_home.html";i:1501642318;s:101:"E:\wamp\www\jingomall\jingo\addons\decoration\view\..\..\..\wstmart\home\view\default\header_top.html";i:1499654586;s:88:"E:\wamp\www\jingomall\jingo\addons\decoration\view\home\shops\shop_decoration_block.html";i:1501824290;s:94:"E:\wamp\www\jingomall\jingo\addons\decoration\view\home\shops\shop_decoration_module_html.html";i:1497401459;s:95:"E:\wamp\www\jingomall\jingo\addons\decoration\view\home\shops\shop_decoration_module_goods.html";i:1497401459;s:95:"E:\wamp\www\jingomall\jingo\addons\decoration\view\home\shops\shop_decoration_module_slide.html";i:1497401459;s:98:"E:\wamp\www\jingomall\jingo\addons\decoration\view\home\shops\shop_decoration_module_hot_area.html";i:1497401459;s:97:"E:\wamp\www\jingomall\jingo\addons\decoration\view\..\..\..\wstmart\home\view\default\footer.html";i:1497249046;}*/ ?>
<html><head>
<meta charset="utf-8">
<title>店铺装修-卖家中心</title>
<meta name="description" content="<?php echo $seoDecorationDesc; ?>">
<meta name="Keywords" content="<?php echo $seoDecorationKeywords; ?>">
<link href="__STYLE__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="__STYLE__/css/shophome.css?v=<?php echo $v; ?>" rel="stylesheet">
<link rel="stylesheet" href="__ROOT__/addons/decoration/view/home/shops/static/imgareaselect-animated.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="__ROOT__/addons/decoration/view/home/shops/static/shop_center.css?v=<?php echo $v; ?>" />
<link rel="stylesheet" type="text/css" href="__STATIC__/plugins/webuploader/webuploader.css?v=<?php echo $v; ?>" />
<link rel="stylesheet" type="text/css" href="__STATIC__/plugins/webuploader/batchupload.css?v=<?php echo $v; ?>" />
<script>
//定义api常量
var DECORATION_ID = "<?php echo $decorationId; ?>";
var LOADING_IMAGE = '__ROOT__/wstmart/home/view/default/img/loading.gif';
window.conf = {
	"ROOT"      : "__ROOT__", 
	"APP"       : "__APP__", 
	"STATIC"    : "__STATIC__", 
	"SUFFIX"    : "<?php echo config('url_html_suffix'); ?>", 
	"SMS_VERFY" : "<?php echo WSTConf('CONF.smsVerfy'); ?>",
	"PHONE_VERFY" : "<?php echo WSTConf('CONF.phoneVerfy'); ?>",
	"GOODS_LOGO"  : "<?php echo WSTConf('CONF.goodsLogo'); ?>",
	"SHOP_LOGO"   : "<?php echo WSTConf('CONF.shopLogo'); ?>",
	"MALL_LOGO"   : "<?php echo WSTConf('CONF.mallLogo'); ?>",
	"USER_LOGO"   : "<?php echo WSTConf('CONF.userLogo'); ?>",
	"IS_LOGIN"    : "<?php if((int)session('WST_USER.userId')>0): ?>1<?php else: ?>0<?php endif; ?>",
	"TIME_TASK"   : "1",
	"MESSAGE_BOX": "<?php echo WSTShopMessageBox(); ?>"
}

</script>
<script type="text/javascript" src="__STATIC__/js/jquery.min.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__ROOT__/static/js/common.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__ROOT__/wstmart/home/view/default/js/common.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__ROOT__/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__ROOT__/addons/decoration/view/home/shops/static/jquery.ui.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__ROOT__/static/plugins/webuploader/webuploader.js??v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__STATIC__/plugins/layer/layer.js?v=<?php echo $v; ?>"></script>

<script src="__STATIC__/plugins/kindeditor/kindeditor.js?v=<?php echo $v; ?>" type="text/javascript" ></script>
<script type="text/javascript" src="__ROOT__/addons/decoration/view/home/shops/static/template.min.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__STATIC__/plugins/slide/js/slide.js?v=<?php echo $v; ?>"></script>
<style id="style_nav"><?php echo $decoration_nav['style']; ?></style>
</head>
<body style="cursor: auto;">
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
<div class="wst-container">
		<div class="wst-shop-h">
		<div class="wst-shop-img"><a href="<?php echo url('home/shops/home',array('shopId'=>$data['shop']['shopId'])); ?>"><img class="shopsImg" data-original="__ROOT__/<?php echo $data['shop']['shopImg']; ?>" title="<?php echo $data['shop']['shopName']; ?>"></a></div>
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
			<input type="text" id="goodsName" value="" placeholder="输入商品名称">
			<a class="search" href="javascript:void(0);" onclick="javascript:WST.goodsSearch($('#goodsName').val());">搜全站</a>
			<a class="search" href="javascript:void(0);" onclick="javascript:searchShopsGoods(0);">搜本店</a>
			<div class="wst-shop-word">
			<?php if(is_array($data['shop']['shopHotWords']) || $data['shop']['shopHotWords'] instanceof \think\Collection): $i = 0; $__LIST__ = $data['shop']['shopHotWords'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$shw): $mod = ($i % 2 );++$i;?>
			<a href='<?php echo Url("home/shops/home",array('shopId'=>$data['shop']['shopId'],'goodsName'=>$shw)); ?>'><?php echo $shw; ?></a><?php if($i< count($data['shop']['shopHotWords'])): ?>&nbsp;|&nbsp;<?php endif; endforeach; endif; else: echo "" ;endif; ?>
			</div>
			<?php echo hook('homeDocumentShopHomeHeader',['shop'=>$data['shop'],'getParams'=>input()]); ?>
			<div class="jiathis_style_24x24 wst-shop-share">
				<a class="jiathis_button_qzone"></a>
				<a class="jiathis_button_tsina"></a>
				<a class="jiathis_button_tqq"></a>
				<a class="jiathis_button_weixin"></a>
				<a class="jiathis_button_cqq"></a>
				<a href="http://www.jiathis.com/share" class="jiathis jiathis_txt jtico jtico_jiathis" target="_blank"></a>					
			</div>
			<div style="clear: both;"></div>
			
			<script type="text/javascript" src="http://v3.jiathis.com/code/jia.js" charset="utf-8"></script>
			
		</div>
		<div class="wst-clear"></div>
		</div>
	</div>
<div class='wst-header'>
		<div class="wst-shop-nav">
			<div class="wst-nav-box">
				<a href="<?php echo url('home/shops/home',array('shopId'=>$data['shop']['shopId'])); ?>"><li class="liselect wst-lfloat wst-nav-boxa">本店全部商品</li></a>
				<?php if(is_array($data['shopcats']) || $data['shopcats'] instanceof \think\Collection): $i = 0;$__LIST__ = is_array($data['shopcats']) ? array_slice($data['shopcats'],0,8, true) : $data['shopcats']->slice(0,8, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sc): $mod = ($i % 2 );++$i;?>
					<a href="<?php echo url('home/shops/cat',array('shopId'=>$sc['shopId'],'ct1'=>$sc['catId'])); ?>"><li class="liselect wst-lfloat "><?php echo $sc['catName']; ?></li></a>
				<?php endforeach; endif; else: echo "" ;endif; ?>
				<a class="homepage" href='<?php echo \think\Request::instance()->root(true); ?>'>返回商城首页</a>
				<div class="wst-clear"></div>
			</div>
		</div>
		<div class="wst-clear"></div>
	</div>
<div class="wrapper">

<div class="wst-decoration-layout">
  <div class="wst-decoration-menu" id="waypoints">
    <div class="title"><i style="float: left;margin-right: 5px;"><img src="__ROOT__/addons/decoration/view/home/shops/static/img/shop_decoration.png" width="50"/></i>
      <h3 style="margin-top:5px;">店铺装修选项</h3>
      <h5>店铺首页模板设计操作</h5>
    </div>
    <ul class="menu">
      <li><a id="btn_edit_background" href="javascript:void(0);"><i><img src="__ROOT__/addons/decoration/view/home/shops/static/img/edit_bg.png" width="40"/></i>编辑背景</a></li>
      <li><a id="btn_edit_head" href="javascript:void(0);"><i><img src="__ROOT__/addons/decoration/view/home/shops/static/img/edit_head.png" width="40"/></i>编辑头部</a></li>
      <li><a id="btn_add_block" href="javascript:void(0);"><i><img src="__ROOT__/addons/decoration/view/home/shops/static/img/loyout.png" width="40"/></i>添加布局块</a></li>
      <li><a id="btn_preview" href="<?php echo addon_url('decoration://decoration/preview',array('id'=>$decorationId)); ?>" target="_blank"><i><img src="__ROOT__/addons/decoration/view/home/shops/static/img/priview.png" width="40"/></i>设计预览</a></li>
      <li><a id="btn_close" href="javascript:void(0);"><i><img src="__ROOT__/addons/decoration/view/home/shops/static/img/exit.png" width="40"/></i>完成退出</a></li>
    </ul>
    <div class="faq">下方区域为1200像素宽度即时编辑区域；“添加布局块”后选择模块类型进行详细设置；“设计预览”可查看生成后效果；内容将实时保存，设置完成后直接选择“完成退出”。</div>
  </div>
  <div id="shop_decoration_content" style="<?php echo $decoration_background_style; ?>">
    <div id="decoration_banner" class="ncsl-nav" style="display: none;"><img src="" alt=""></div>
    <div id="shop_decoration_area" class="store-decoration-page wst-shop-list ui-sortable">
      <?php if(!empty($block_list) && is_array($block_list)) {foreach($block_list as $block) {
$control_flag = false;

$op = request()->routeInfo()["var"]["route"];
if($op == 'decoration-decoration-edit' || $op == 'decoration-decoration-blockadd') { 
    $control_flag = true;
} 
$block_content = isSet($block['blockContent'])?$block['blockContent']:"";
if($control_flag) { 
    $block_title = '上下拖拽布局块位置可改变排列顺序，无效的可删除。<br/>编辑布局块内容请点击“编辑模块”并选择操作。';
} else {
    $block_title = '';
}
$extend_class = (isSet($block['blockFullWidth']) && $block['blockFullWidth'] == '1') ? 'store-decoration-block-full-width' : '';?>
<div id="block_<?php echo $block['blockId'];?>" data-block-id="<?php echo $block['blockId'];?>" nctype="shop_decoration_block" class="wst-decration-block store-decoration-block-1 <?php echo $extend_class;?> tip"  title="<?php echo $block_title;?>">
    <div nctype="shop_decoration_block_content" class="wst-decration-block-content store-decoration-block-1-content">
        <div nctype="shop_decoration_block_module" class="store-decoration-block-1-module">
            <?php if(isSet($block['blockModuleType']) && !empty($block['blockModuleType'])) { 
            	if($block['blockModuleType']=='html'){$block_content = empty($block_content) ? $output['block_content'] : $block_content; ?>
<?php echo html_entity_decode($block_content);}else if($block['blockModuleType']=='goods'){ 
	if(empty($gdata)) {
    	$goods_list = unserialize($block_content);
	} else {
   	 	$goods_list = $gdata['Rows'];
	}
if(!empty($goods_list) && is_array($goods_list)){?>

	<div class="wst-shop-listg">
  	<?php foreach($goods_list as $key=>$val){?>
	  	<div class="wst-shop-goods" nctype="goods_item" data-goods-id="<?php echo $val['goodsId']; ?>" data-goods-name="<?php echo $val['goodsName']; ?>" data-goods-price="<?php echo $val['shopPrice']; ?>"  data-goods-image="<?php if(empty($gdata)){ ?> <?php echo $val['goodsImg']; }else{ ?>  __ROOT__/<?php echo $val['goodsImg']; } ?>">
	    	<div class="wst-shop-goimg">
		    	<a href="<?php echo url('home/goods/detail',array('id'=>$val['goodsId'])); ?>" target="_blank">
		    		<?php if(empty($gdata)){ ?>
		    		<img class="goodsImg" src="<?php echo $val['goodsImg']; ?>" title="<?php echo $val['goodsName']; ?>">
		    		<?php }else{ ?>
		    		<img class="goodsImg" src="__ROOT__/<?php echo $val['goodsImg']; ?>" title="<?php echo $val['goodsName']; ?>">
		    		<?php } ?>
		    	</a>
	    	</div>
			<p class="wst-shop-gonam">
				<a href="<?php echo url('home/goods/detail',array('id'=>$val['goodsId'])); ?>" target="_blank"><?php echo WSTMSubstr($val['goodsName'],0,15); ?></a>
			</p>
			<div class="wst-shop-rect">
				<span>￥<?php echo $val['shopPrice']; ?></span>
				<?php if(empty($gdata)) { ?>
				<a nctype="btn_goods_cart" class="wst-shop-recta" href="javascript:WST.addCart(<?php echo $val['goodsId']; ?>)">加入购物车</a>
				<?php } ?>
			</div>
			
		    <?php if(!empty($gdata)) { ?>
		    <a nctype="btn_module_goods_operate" class="wst-btn-mini" href="javascript:;"><i class="icon-plus"></i>选择添加</a>
		    <?php } ?>
	  	</div>
  	<?php } ?>
  	<div style="clear:both;"></div>
	</div>
	<?php if(!empty($gdata)) { ?>
		<div id="pagination" class="pagination"></div>
	<?php } ?>
	
	<script>
	$(function(){
		<?php if(isset($gdata) && $gdata['TotalPage']>1){ ?>
			laypage({
			    cont: 'pagination',
			    pages: <?php echo $gdata['TotalPage']; ?>, //总页数
			    curr: <?php echo $gdata['CurrentPage']; ?>,
			    skip: true, //是否开启跳页
			    skin: '#fd6148',
			    groups: 3, //连续显示分页数
			   	prev: '<<',
				next: '>>',
			    jump: function(e, first){ //触发分页后的回调
			        if(!first){ //一定要加此判断，否则初始时会无限刷新
			        	var param = {};
			        	param.shopCatId1 = $("#shopCatId1").val();
			        	param.shopCatId2 = $("#shopCatId2").val();
			        	param.goodsName = $("#goodsName").val();
			        	param.page = e.curr;
			        	var load = layer.load(0, {shade: false})
			            $('#div_module_goods_search_list').load(WST.AU('decoration://decoration/goodssearch') ,param,function(){
			            	layer.close(load);
			            });
			        }
			    }
			});
		<?php } ?>
	
	});
	</script>
<?php } else { if(!empty($goods_list)) { ?>
		<div>没有商品信息</div>
	<?php } } }else if($block['blockModuleType']=='slide'){ $block_content = empty($block_content) ? '' : $block_content; $block_content = unserialize($block_content);?>

<div class="s-wst-slide" style="width:100%;height:<?php echo $block_content['height']; ?>px;overflow:hidden;">
	<div class="s-wst-slide-numbox" data-slide-height="<?php echo $block_content['height']; ?>" >
		<div class="s-wst-slide-controls">
		<?php if(is_array($block_content['images']) || $block_content['images'] instanceof \think\Collection): $k = 0; $__LIST__ = $block_content['images'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;if($k+1 == 1): ?>
				<span class="curr"> </span>
			<?php else: ?>
				<span class=""> </span>
			<?php endif; endforeach; endif; else: echo "" ;endif; ?>
		</div>
	</div>
	<?php if(!empty($block_content['images']) && is_array($block_content['images'])) {?>
	<ul class="s-wst-slide-items">
		<?php if(is_array($block_content['images']) || $block_content['images'] instanceof \think\Collection): $i = 0; $__LIST__ = $block_content['images'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;$image_url = $vo['image_name'];?>
		<a href="<?php echo $vo['image_link']; ?>" target="_blank">
		<li data-image-name="<?php echo $image_url; ?>" data-image-url="<?php echo $image_url; ?>" data-image-link="<?php echo $vo['image_link']; ?>" style="height:<?php echo $block_content['height']; ?>px; background: url(<?php echo $image_url; ?>) 50% 0% / cover no-repeat scroll;"></li>
		</a>
		<?php endforeach; endif; else: echo "" ;endif; ?>
	</ul>
	<?php } ?>
</div>

<script type='text/javascript'>
WST.slides();
</script>
           		<?php }else if($block['blockModuleType']=='hot_area'){ $block_content = empty($block_content) ? $output['block_content'] : $block_content; $block_content = unserialize($block_content);?>
<div>
    <?php $image_url = $block_content['image'];$hot_area_flag = str_replace('.', '',$block_content['image']);?>
    <img data-image-name="<?php echo $block_content['image'];?>" usemap="#<?php echo $hot_area_flag;?>" src="<?php echo $image_url;?>" alt="<?php echo $block_content['image'];?>">
    <map name="<?php echo $hot_area_flag;?>" id="<?php echo $hot_area_flag;?>">
        <?php if(!empty($block_content['areas']) && is_array($block_content['areas'])) {foreach($block_content['areas'] as $value) {?>
        <area target="_blank" shape="rect" coords="<?php echo $value['x1'];?>,<?php echo $value['y1'];?>,<?php echo $value['x2'];?>,<?php echo $value['y2'];?>" href ="<?php echo $value['link'];?>" alt="<?php echo $value['link'];?>" />
        <?php } } ?>
    </map>
</div>


           		<?php } } ?>
        </div>
        <?php if($control_flag) { ?>
        <a class="edit" nctype="btn_edit_module" data-module-type="<?php echo isSet($block['blockModuleType'])?$block['blockModuleType']:'';?>" href="javascript:;" data-block-id="<?php echo $block['blockId'];?>"><i class="icon-edit"></i>编辑模块</a>
        <?php } ?>
    </div>
    <?php if($control_flag) { ?>
    <a class="delete" nctype="btn_del_block" href="javascript:;" data-block-id="<?php echo $block['blockId']; ?>" title="删除该布局块"><i class="icon-trash"></i>删除布局块</a>    
    <?php } ?>
</div>

      <?php } } ?>
    </div>
  </div>
</div>
<!-- 背景编辑对话框 -->

<div id="dialog_edit_background" class="eject_con dialog-decoration-edit" style="display:none;">
  <dl>
    <dt>背景颜色：</dt>
    <dd>
      <input id="txt_background_color" class="text w80" type="text" name="" value="<?php echo $decoration_setting['background_color']; ?>" maxlength="7">
      <input class="wst-color" type="color" name="favcolor" style="height: 30px;padding:2px;" data='bg' value="<?php echo $decoration_setting['background_color']; ?>"/>
      <p class="hint">设置背景颜色请使用十六进制形式(#XXXXXX)，默认留空为白色背景。</p>
    </dd>
  </dl>
  <dl>
    <dt>背景图：</dt>
    <dd>
      <div id="div_background_image" style='<?php echo !empty($decoration_setting['background_image_url'])?"":"display:none;"; ?>' class="background-image-thumb"> 
      <img id="img_background_image" src="<?php echo $decoration_setting['background_image_url']; ?>" alt="">
        <input id="txt_background_image" type="hidden" name="" value="<?php echo $decoration_setting['background_image_url']; ?>">
        <a id="btn_del_background_image" class="del" href="javascript:void(0);" title="移除背景图">X</a>
       </div>
        
    	<div class="wst-upload-btn"> 
	        <div id='file_background_image'>图片上传</div>
        </div>
    </dd>
  </dl>
  <dl>
    <dt>背景图定位：</dt>
    <dd>
      <input id="txt_background_position_x" class="text w40" type="text" value="<?php echo $decoration_setting['background_position_x']; ?>"><label class="add-on">X</label>
      &nbsp;&nbsp;&nbsp;&nbsp;
      <input id="txt_background_position_y" class="text w40" type="text" value="<?php echo $decoration_setting['background_position_y']; ?>"><label class="add-on">Y</label>
      <p class="hint">设置背景图像的起始位置。</p>
    </dd>
  </dl>
  <dl>
    <dt>背景图填充方式：</dt>
    <dd>
      <input id="input_no_repeat" type="radio" value="no-repeat" name="background_repeat" <?php echo !empty($decoration_setting['background_image_repeat']) && $decoration_setting['background_image_repeat']=="no-repeat"?"checked":""; ?>>
      <label for="input_no_repeat">不重复</label>
      <input id="input_repeat" type="radio" value="repeat" name="background_repeat" <?php echo !empty($decoration_setting['background_image_repeat']) && $decoration_setting['background_image_repeat']=="repeat"?"checked":""; ?>>
      <label for="input_repeat">平铺</label>
      <input id="input_repeat_x" type="radio" value="repeat-x" name="background_repeat" <?php echo !empty($decoration_setting['background_image_repeat']) && $decoration_setting['background_image_repeat']=="repeat-x"?"checked":""; ?>>
      <label for="input_repeat_x">x轴平铺</label>
      <input id="input_repeat_y" type="radio" value="repeat-y" name="background_repeat" <?php echo !empty($decoration_setting['background_image_repeat']) && $decoration_setting['background_image_repeat']=="repeat-y"?"checked":""; ?>>
      <label for="input_repeat_y">y轴平铺</label>
    </dd>
  </dl>
  <dl>
    <dt>背景滚动模式：</dt>
    <dd>
      <input id="txt_background_attachment" class="text w80" type="text" value="<?php echo $decoration_setting['background_attachment']; ?>">
      <p class="hint">设置背景随屏幕滚动或固定，例如："scroll"或"fixed"。 </p>
    </dd>
  </dl>
</div>



<!-- 头部编辑对话框 -->
<div id="dialog_edit_head" class="eject_con dialog-decoration-edit" style="display:none;">
  <div id="dialog_edit_head_tabs">
    
    <div id="dialog_edit_head_tabs_1">
      <dl>
        <dt>颜色值参考：</dt>
        <dd>
          <input class="wst-color" type="color" name="favcolor" style="height: 30px;padding:2px;" data='hd'/><span id="showColor"></span>
          <p class="hint">“导航样式”中的颜色值，可以选择对应的颜色进行参考。</p>
        </dd>
      </dl>
      <dl>
        <dt>导航样式：</dt>
        <dd>
          <textarea id="decoration_nav_style" style="width:450px;height:200px;"><?php echo $decoration_nav['style']; ?></textarea>
          <p> <a id="btn_default_nav_style" class="wst-btn-mini" href="javascript:void(0);"><i class="icon-refresh"></i>恢复默认</a> </p>
          <p class="hint">导航条对应CSS文件，如修改后显示效果不符可恢复默认值。</p>
        </dd>
      </dl>
    </div>
  </div>
</div>
<!-- 选择模块对话框 -->
<div id="dialog_select_module" class="dialog-decoration-module" style="display:none;">
  <ul>
    <li><a nctype="btn_show_module_dialog" data-module-type="slide" href="javascript:void(0);"><i><img src="__ROOT__/addons/decoration/view/home/shops/static/img/silde.png" width="40"/></i>
      <dl>
        <dt>图片和幻灯</dt>
        <dd>添加图片和可切换幻灯</dd>
      </dl>
      </a></li>
    <li><a nctype="btn_show_module_dialog" data-module-type="hot_area" href="javascript:void(0);"><i><img src="__ROOT__/addons/decoration/view/home/shops/static/img/hot_area.png" width="40"/></i>
      <dl>
        <dt>图片热点</dt>
        <dd>添加图片并设置热点区域链接</dd>
      </dl>
      </a></li>
    <li> <a nctype="btn_show_module_dialog" data-module-type="goods" href="javascript:void(0);"><i><img src="__ROOT__/addons/decoration/view/home/shops/static/img/shop_goods.png" width="40"/></i>
      <dl>
        <dt>店铺商品</dt>
        <dd>选择添加店铺内的在售商品</dd>
      </dl>
      </a> </li>
    <li> <a nctype="btn_show_module_dialog" data-module-type="html" href="javascript:void(0);"><i><img src="__ROOT__/addons/decoration/view/home/shops/static/img/diy.png" width="40"/></i>
      <dl>
        <dt>自定义</dt>
        <dd>使用编辑器子自定义编辑html</dd>
      </dl>
      </a> </li>
  </ul>
</div>
<!-- 自定义模块编辑对话框 -->
<div id="dialog_module_html" class="eject_con dialog-decoration-edit" style="display:none;">
	<div class='wst-tips-box' style="margin-top:10px;margin-right:10px; ">
		<div class='icon'></div>
		<div class='tips'>
			1. 可将预先设置好的网页文件内容复制粘贴到文本编辑器内，或直接在工作窗口内进行编辑操作。<br/>
			2. 默认为可视化编辑，选择第一个按钮切换到html代码编辑。css文件可以Style=“...”形式直接写在对应的html标签内。</div>
			<div style="clear:both">
		</div>
	</div>
  <div style="padding-left: 10px;">
  <textarea id="module_html_editor" name="module_html_editor" style=" width:1016px; height:400px; visibility:hidden;"></textarea>
  </div>
</div>
<!-- 幻灯模块编辑对话框 -->
<div id="dialog_module_slide" class="eject_con dialog-decoration-edit" style="display:none;">
  <div class='wst-tips-box' style="margin-top:10px;margin-right:10px; ">
		<div class='icon'></div>
		<div class='tips'>
			1. 可选择图片以全屏或非全屏形式显示，<strong class="orange">且必须设定图片的高度</strong>，否则将无法正常浏览。<br/>
			2. 上传单张图片时系统默认显示为<strong>“图片链接”</strong>形式显示，如一次上传多图将以<strong>“幻灯片”</strong>形式显示。
		</div>
		<div style="clear:both"></div>
	</div>
  <div id="module_slide_html" class="slide-upload-thumb">
    <ul class="module-slide-content">
    </ul>
  </div>
  <h4 class="mt10">相关设置：</h4>
  <dl class="display-set">
    <dt>显示高度<font color="red">*</font>:</dt>
    <dd><span style="display: none;">全屏显示
      <input id="txt_slide_full_width" type="checkbox" class="checkobx" name="">
      </span><span>
      <input id="txt_slide_height" type="text" class="text w40" value=""><em class="add-on">像素</em></span>
    </dd>
  </dl>
  <div id="div_module_slide_upload">
    <form action="">
      <dl>
        <dt>图片上传：</dt>
        <dd>
          <div id="div_module_slide_image" class="module-upload-image-preview"></div>
          	<div class="wst-upload-btn">
            	<div id='btn_module_slide_upload'>图片上传</div>
   			</div>
          	<p class="hint">请上传宽度为1200像素的jpg/gif/png格式图片。</p>
        </dd>
      </dl>
      <dl>
        <dt>图片链接：</dt>
        <dd>
          <input id="module_slide_url" class="text w400" type="text"><a id="btn_save_add_slide_image" class="wst-btn ml5" href="javascript:void(0);">添加</a> 
          <p class="hint">请输入以http://为开头的图片链接地址，仅作为图片使用时请留空此选项</p>
        </dd>
      </dl>
    </form>
  </div>
</div>
<!-- 图片热点模块编辑对话框 -->
<div id="dialog_module_hot_area" class="eject_con dialog-decoration-edit" style="display:none;">
	<div class='wst-tips-box' style="margin-top:10px;margin-right:10px; ">
		<div class='icon'></div>
		<div class='tips'>
			1. 在已上传的图片范围拖动鼠标形成选择区域，对该区域添加以http://格式开头的链接地址并点击“添加网址”按钮生效。<br/>
			2. 对已添加的热点可做编辑链接地址修改，如需调整位置，请删除该热点区域并保存，之后重新选择添加。</div>
			<div style="clear:both">
		</div>
	</div>
  <div id="div_module_hot_area_image" class="hot-area-image" style="position: relative;"></div>
  <ul id="module_hot_area_select_list" class="hot-area-select-list">
  </ul>

  
  <h4 class="mt10">相关设置：</h4>
  <form action="">
    <dl>
      <dt>图片上传：</dt>
      <dd>
        <div class="wst-upload-btn"> 
          <div id='btn_module_hot_area_upload'>图片上传</div>
        </div>
        <p class="hint">选择上传jpg/gif/png格式图片，建议宽度不超过1200像素，如超出此范围，请先自行对图片进行裁切调整。</p>
      </dd>
    </dl>
  </form>
  <dl>
    <dt>热点链接设置：</dt>
    <dd>
      <input id="module_hot_area_url" class="text w400" type="text" />
      <a id="btn_module_hot_area_add" class="wst-btn ml5" href="javascript:void(0);"><i class="icon-anchor"></i>添加网址</a>
      <p class="hint">在输入框中添加以“http://”格式开头的热点区域跳转网址。</p>
    </dd>
  </dl>
</div>
<!-- 商品模块编辑对话框 -->
<div id="dialog_module_goods" class="eject_con dialog-decoration-edit" style="display:none;">
	<div class='wst-tips-box' style="margin-top:10px;margin-right:10px; ">
		<div class='icon'></div>
		<div class='tips'>
			1. 搜索店铺内在售商品并“选择添加”，设置窗口上部将出现已选择的商品列表，也可对其进行“取消选择”操作，点击保存设置后生效。<br/>
			2. 当已选择的商品超过10个时，系统默认未全部显示，可通过在已选区域滚动鼠标或拉动侧边条进行查看及操作。
		</div>
		<div style="clear:both"></div>
	</div>
  <div id="decorationGoods">
    <ul id="div_module_goods_list" class="wst-shop-listg">
    </ul>
  </div>
  <h4 class="mt10">店铺在售商品选择</h4>
  <div class="decoration-search-goods">
    <div class="search-bar">
    	<select id="shopCatId1" class='j-ipt' onchange="WST.shopsCats('shopCatId2',this.value,'');">
   			<option value="">-请选择-</option>
     		<?php $_result=WSTShopCats(0);if(is_array($_result) || $_result instanceof \think\Collection): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
      		<option value="<?php echo $vo['catId']; ?>"><?php echo $vo['catName']; ?></option>
        	<?php endforeach; endif; else: echo "" ;endif; ?>
 		</select>
    	<select id='shopCatId2' class='j-ipt'>
      		<option value=''>请选择</option>
    	</select>
    	<input type='text' id='goodsName' placeholder="请输入要搜索的商品名称" />
     	<a id="btn_module_goods_search" type="button" class='s-btn'>查&nbsp;询</a>
    	<span class="ml10 orange">小提示： 留空搜索显示店铺全部在售商品，每页显示10个。</span></div>
    <div id="div_module_goods_search_list"></div>
  </div>
</div>
<!-- 幻灯模板 --> 
<script id="template_module_slide_image_list" type="text/html">
<li data-image-name="<%=image_name%>" data-image-url="<%=image_url%>" data-image-link="<%=image_link%>">
<span><img src="<%=image_url%>"></span>
<a nctype="btn_del_slide_image" href="javascript:void(0);" title="删除">X</a>
</li>
</script> 
<!-- 热点块控制模板 --> 
<script id="template_module_hot_area_list" type="text/html">
<li data-hot-area-link="<%=link%>" data-hot-area-position="<%=position%>">
<i></i>
<p>热点区域<%=index%></p>
<p><a nctype="btn_module_hot_area_select" data-hot-area-position="<%=position%>" class="wst-btn-mini wst-btn-acidblue" href="javascript:void(0);">选择</a>
<a data-index="<%=index%>" nctype="btn_module_hot_area_del" class="wst-btn-mini wst-btn-red" href="javascript:void(0);">删除</a></p>
</li>
</script> 
<!-- 热点块标识模板 --> 
<script id="template_module_hot_area_display" type="text/html">
<div class="store-decoration-hot-area-display" style="width:<%=width%>px;height:<%=height%>px;position:absolute;left:<%=left%>px;top:<%=top%>px;border:1px solid #cccccc;" id="hot_area_display_<%=index%>">热点区域<%=index%></div>
</li>
</script> 

<script type="text/javascript" src="__ROOT__/addons/decoration/view/home/shops/static/jquery.imgareaselect.min.js?v=<?php echo $v; ?>"></script> 
<script type="text/javascript" src="__ROOT__/addons/decoration/view/home/shops/static/shop_decoration.js?v=<?php echo $v; ?>" charset="utf-8"></script> 

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
<script type="text/javascript">
$(function(){
	WST.dropDownLayer(".j-dorpdown",".j-dorpdown-layer");
});
</script>
</body></html>