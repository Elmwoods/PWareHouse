<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:57:"/mnt/data/www/wstmart/mobile/view/default/goods_list.html";i:1491530392;s:51:"/mnt/data/www/wstmart/mobile/view/default/base.html";i:1491530392;s:53:"/mnt/data/www/wstmart/mobile/view/default/footer.html";i:1491530392;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title>商品列表 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="__MOBILE__/css/swiper.min.css">
<link rel="stylesheet"  href="__MOBILE__/css/goods_list.css?v=<?php echo $v; ?>">

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

	<header class="ui-header ui-header-positive wst-in-header">
	    <i class="ui-icon-return" onclick="history.back()"></i>
    </header>
    <div class="wst-in-search search">
    	<span class="seleft"></span><input id="wst-search" type="text" value="<?php echo $keyword; ?>" placeholder="请输入商品名称"><span class="seright"></span><p></p><span class="ui-icon-search" onclick="javascript:WST.search(0);"></span>
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


     <input type="hidden" name="" value="<?php echo $keyword; ?>" id="keyword" autocomplete="off">
     <input type="hidden" name="" value="<?php echo $catId; ?>" id="catId" autocomplete="off">
	 <input type="hidden" name="" value="<?php echo $brandId; ?>" id="brandId" autocomplete="off">
     <input type="hidden" name="" value="" id="condition" autocomplete="off">
	 <input type="hidden" name="" value="" id="desc" autocomplete="off">
	 <input type="hidden" name="" value="" id="currPage" autocomplete="off">
     <input type="hidden" name="" value="" id="totalPage" autocomplete="off">
     <section class="ui-container">
        <div class="wst-shl-ads">
     		<div class="title">热卖推荐</div>
		   <div class="wst-gol-adsb">
			<div class="swiper-container">
	          <div class="swiper-wrapper">
	          	<?php $wstTagGoods =  model("common/Tags")->listGoods("recom",$catId,8,0); foreach($wstTagGoods as $key=>$vo){?>
	                <div class="swiper-slide" style="width:33.333333%;">
	                     <div class="wst-gol-img j-imgRec">
		                     <a href="javascript:void(0);" onclick="javascript:WST.intoGoods(<?php echo $vo['goodsId']; ?>);">
		                     <img src="__ROOT__/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="__ROOT__/<?php echo WSTImg($vo['goodsImg'],3); ?>" title="<?php echo $vo['goodsName']; ?>">
		                     </a>
	                     </div>
	                     <p>¥<?php echo $vo['shopPrice']; ?></p>
	                </div>
	             <?php } ?>
	          </div>
	        </div>
	        </div>
     	</div>
     	<div class="ui-row-flex wst-shl-head">
		    <div class="ui-col ui-col sorts active" status="down" onclick="javascript:orderCondition(this,0);">
		   		 <p class="pd0">销量</p><i class="down2"></i>
		    </div>
		    <div class="ui-col ui-col sorts" status="down" onclick="javascript:orderCondition(this,1);">
		   		 <p class="pd0">价格</p><i class="down"></i>
		    </div>
		    <div class="ui-col ui-col sorts" status="down" onclick="javascript:orderCondition(this,2);">
		   		 <p class="pd0">人气</p><i class="down"></i>
		    </div>
		    <div class="ui-col ui-col sorts" status="down" onclick="javascript:orderCondition(this,3);">
		   		 <p>上架时间</p><i class="down"></i>
		    </div>
		</div>
		<ul class="ui-tab-content">
	        <li id="goods-list"></li>
	    </ul>
     </section>
<script id="list" type="text/html">
{{# if(d && d.length>0){ }}
{{# for(var i=0; i<d.length; i++){ }}
<div class="wst-in-goods" onclick="javascript:WST.intoGoods({{ d[i].goodsId }});">
<div class="img j-imgAdapt"><a href="javascript:void(0);" onclick="javascript:WST.intoGoods({{ d[i].goodsId }});"><img src="__ROOT__/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="__ROOT__/{{ d[i].goodsImg }}" title="{{ d[i].goodsName }}"/></a></div>
<div class="name ui-nowrap-multi">{{ d[i].goodsName }}</div>
<div class="info"><span class="price">¥{{ d[i].shopPrice }}</span><span class="deal">成交数:{{ d[i].saleNum }}</span></div>
</div>
{{# } }}
<div class="wst-clear"></div>
{{# }else{ }}
	<ul class="ui-row-flex wst-flexslp">
		<li class="ui-col ui-flex ui-flex-pack-center">
		<p>对不起，没有相关商品。</p>
		</li>
	</ul>
{{# } }}
</script>



<script type='text/javascript' src='__MOBILE__/js/swiper.jquery.min.js'></script>
<script type='text/javascript' src='__MOBILE__/js/goods_list.js?v=<?php echo $v; ?>'></script>

</body>
</html>