<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:61:"/mnt/data/www/wstmart/mobile/view/default/goods_category.html";i:1491530392;s:51:"/mnt/data/www/wstmart/mobile/view/default/base.html";i:1491530392;s:53:"/mnt/data/www/wstmart/mobile/view/default/footer.html";i:1491530392;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title>商品分类 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet" href="__MOBILE__/css/goods_category.css?v=<?php echo $v; ?>">

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


	        
        <div class="ui-loading-wrap wst-Load" id="Load">
		    <i class="ui-loading"></i>
		</div>
		
		<div class="ui-loading-block" id="Loadl">
		    <div class="ui-loading-cnt">
		        <i class="ui-loading-bright"></i>
		        <p id="j-Loadl">正在加载中...</p>
		    </div>
		</div>
        <footer class="ui-footer wst-footer-btns" style="height:45px; border-top: 1px solid #e8e8e8;" id="footer">
	        <div class="wst-toTop" id="toTop">
			  <i class="wst-toTopimg"><span>顶部</span></i>
			</div>
			<?php $cartNum = WSTCartNum(); ?>
            <div class="ui-row-flex wst-menus">
			    <div class="ui-col ui-col"><a href="<?php echo url('mobile/index/index'); ?>"><p id="home"></p></a></div>
			    <div class="ui-col ui-col"><a href="<?php echo url('mobile/goodscats/index'); ?>"><p id="category"></p></a></div>
			    <div class="ui-col ui-col carsNum"><a href="<?php echo url('mobile/carts/index'); ?>"><p id="cart">
                </p></a><?php if(($cartNum>0)): ?><i><?php  echo $cartNum; ?></i><?php endif; ?></div>
                <div class="ui-col ui-col"><a href="<?php echo url('mobile/favorites/goods'); ?>"><p id="follow"></p></a></div>
			    <div class="ui-col ui-col"><a href="<?php echo url('mobile/users/index'); ?>"><p id="user"></p></a></div>
			</div>
        </footer>
        <?php echo hook('initCronHook'); ?>


	<section class="ui-container">
		<div class="ui-scrollerl" id="ui-scrollerl">
		    <ul>
		    	<?php if(is_array($list) || $list instanceof \think\Collection): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$go): $mod = ($k % 2 );++$k;?>
		        <li id="goodscate" class="wst-goodscate <?php if(($k==1)): ?>wst-goodscate_selected<?php endif; ?>" onclick="javascript:showRight(this,<?php echo $k-1; ?>);"><?php echo str_replace('、', '<br/>', $go['catName']); ?></li>
		        <?php endforeach; endif; else: echo "" ;endif; ?>
		    </ul>
		</div>
		<?php if(is_array($list) || $list instanceof \think\Collection): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$go): $mod = ($k % 2 );++$k;?>
		<div class="wst-scrollerr goodscate1" <?php if(($k!=1)): ?>style="display:none;"<?php endif; ?>>
			<div class="wst-gc-br">
			<?php $wstTagBrand =  model("common/Tags")->listBrand($go['catId'],16,86400); foreach($wstTagBrand as $key=>$bvo){?>
				<a href="javascript:void(0)" onclick="javascript:getBrandGoodsList(<?php echo $bvo['brandId']; ?>);"><img src="__ROOT__/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="__ROOT__/<?php echo WSTImg($bvo['brandImg'],2); ?>"></a>
			<?php } ?>
			</div>
			<div class="wst-clear"></div>
			<?php if(is_array($go['childList']) || $go['childList'] instanceof \think\Collection): $i = 0; $__LIST__ = $go['childList'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$go1): $mod = ($i % 2 );++$i;?>
		    <ul>
		        <div class="wst-gc-ads">
		     		<a href="javascript:void(0);" onclick="javascript:getGoodsList(<?php echo $go1['catId']; ?>);"><div class="title"><?php echo $go1['catName']; ?></div></a>
		     	</div>
		        <li>
			        <div class="wst-goodscat">
			        	<?php if(is_array($go1['childList']) || $go1['childList'] instanceof \think\Collection): $i = 0; $__LIST__ = $go1['childList'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$go2): $mod = ($i % 2 );++$i;?>
			        	<span><a href="javascript:void(0);" onclick="javascript:getGoodsList(<?php echo $go2['catId']; ?>);"><?php echo $go2['catName']; ?></a></span>
			        	<?php endforeach; endif; else: echo "" ;endif; ?>
			        </div>
		        </li>
		        <div class="wst-clear"></div>
		    </ul>
		    <?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
		<?php endforeach; endif; else: echo "" ;endif; ?>
		<div class="wst-clear"></div>
	</section>



<script type='text/javascript' src='__MOBILE__/js/goods_category.js?v=<?php echo $v; ?>'></script>

</body>
</html>