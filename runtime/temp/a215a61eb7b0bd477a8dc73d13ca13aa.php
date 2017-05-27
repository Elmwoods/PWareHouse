<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:52:"/mnt/data/www/wstmart/mobile/view/default/index.html";i:1491530392;s:51:"/mnt/data/www/wstmart/mobile/view/default/base.html";i:1491530392;s:53:"/mnt/data/www/wstmart/mobile/view/default/footer.html";i:1491530392;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title>首页 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="__MOBILE__/css/swiper.min.css">
<link rel="stylesheet"  href="__MOBILE__/css/index.css?v=<?php echo $v; ?>">

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

    <header class="ui-header ui-header-positive wst-in-header" id="j-header">
    </header>
    <div class="wst-in-icon" id="j-icon">
    <a href="<?php echo url('mobile/goodscats/index'); ?>"><span class="cats"></a></span>
    <span onclick="location.href='<?php echo url('mobile/messages/index'); ?>'" class="news"><?php if(($news['message']['num']>0)): ?><i class="wst-in-num"><?php echo $news['message']['num']; ?></i><?php endif; ?></span>
    </div>
    <div class="wst-in-search search" id="j-search">
    	<span class="seleft"></span><input id="wst-search" type="text" placeholder=""><span class="seright"></span><p></p><span class="ui-icon-search" onclick="javascript:WST.search(0);"></span>
    </div>


	        
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


<input type="hidden" name="" value="-1" id="currPage" autocomplete="off">
<section class="ui-container">
        <div class="ui-slider" style="padding-top:50%;">
		    <ul class="ui-slider-content" style="width: 300%">
		    	<?php $wstTagAds =  model("common/Tags")->listAds("wx-ads-index",99,86400); foreach($wstTagAds as $key=>$vo){?>
		        <li class="advert1"><span><a href="<?php echo $vo['adURL']; ?>"><img style="width:100%; height:100%; display:block;" src="__ROOT__/<?php echo WSTImg($vo['adFile'],2); ?>"></a></span></li>
		        <?php } ?>
		    </ul>
		</div>
		<div class="ui-row wst-in-choose">
		    <?php $_result=WSTMobileBtns(0);if(is_array($_result) || $_result instanceof \think\Collection): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$btn): $mod = ($i % 2 );++$i;?>
		    <div class="ui-col ui-col-25">
		    <a href="<?php echo url($btn['btnUrl']); ?>">
		    <p><img width='56' src="__ROOT__/<?php echo $btn['btnImg']; ?>" style='margin-top:7px;'/></p>
		    <span><?php echo $btn['btnName']; ?></span>
		    </a></div>
		    <?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
		<div class="wst-in-news">
		<span class="new">最新资讯：</span>
		<marquee scrollamount='3' behavior='right'><?php $wstTagArticle =  model("common/Tags")->listArticle("new",6,86400); foreach($wstTagArticle as $key=>$vo){?><a href="<?php echo url('mobile/news/view',['articleId'=>$vo['articleId']]); ?>"><?php echo $vo['articleTitle']; ?>。</a><?php } ?></marquee>
		<span class="more" onclick="location.href='<?php echo url('mobile/news/view'); ?>'">更多</span>
		<div class="wst-clear"></div>
		</div>
		<div class="wst-in-adst">
			<?php $wstTagAds =  model("common/Tags")->listAds("wx-index-large",4,86400); foreach($wstTagAds as $key=>$vo){?>
			<a class="advert2" href="<?php echo $vo['adURL']; ?>"><div class="adst"><img src="__ROOT__/<?php echo WSTImg($vo['adFile'],2); ?>"/></div></a>
			<?php } ?>
			<div class="wst-clear"></div>
		</div>
		<div class="wst-in-adsb">
		<div class="swiper-container">
          <div class="swiper-wrapper">
          	<?php $wstTagAds =  model("common/Tags")->listAds("wx-index-small",4,86400); foreach($wstTagAds as $key=>$vo){?>
                <div class="swiper-slide" style="width:33.333333%;">
                  <div class="goodsinfo-container">
                    <a class="advert3" href="<?php echo $vo['adURL']; ?>"><img src="__ROOT__/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="__ROOT__/<?php echo WSTImg($vo['adFile'],2); ?>"></a>
                  </div>
                </div>
            <?php } ?>
          </div>
        </div>
        </div>
		<div id="goods-list"></div>
</section>
<script id="list" type="text/html">
<div class="wst-in-title colour{{ d.currPage }}">
	<div class="left">
		<p class="line1"></p><span></span><p class="line2"></p><span></span><p></p><span></span>
		<p class="line2"></p><span></span><p class="line1"></p></div>
	<div class="right">
		<p class="line1"></p><span></span><p class="line2"></p><span></span><p></p><span></span>
		<p class="line2"></p><span></span><p class="line1"></p></div>{{ d.catName }}</div>
	{{# if(d.ads && d.ads.length>0){ }}
		<div class="wst-in-adscats"><a href="{{ d.ads[0].adURL }}"><img src="__ROOT__/{{ d.ads[0].adFile }}"/></a></div>
	{{# } }}
	{{# if(d.goods.length>0){ }}
		{{# for(var i=0; i<d.goods.length; i++){ }}
			<div class="wst-in-goods" onclick="javascript:WST.intoGoos({{ d.goods[i].goodsId }});">
				<div class="img j-imgAdapt"><a href="javascript:void(0);" onclick="javascript:WST.intoGoods({{ d.goods[i].goodsId }});"><img src="__ROOT__/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="__ROOT__/{{ d.goods[i].goodsImg }}" title="{{ d.goods[i].goodsName }}"/></a></div>
				<div class="name ui-nowrap-multi">{{ d.goods[i].goodsName }}</div>
				<div class="info"><span class="price">¥{{ d.goods[i].shopPrice }}</span><span class="deal">成交数:{{ d.goods[i].saleNum }}</span></div>
			</div>
		{{# } }}
	{{# } }}
<div class="wst-clear"></div>
</script>



<script type='text/javascript' src='__MOBILE__/js/swiper.jquery.min.js'></script>
<script type='text/javascript' src='__MOBILE__/js/index.js?v=<?php echo $v; ?>'></script>

</body>
</html>