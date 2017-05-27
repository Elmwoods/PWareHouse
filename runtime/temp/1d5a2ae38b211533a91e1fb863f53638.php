<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:56:"/mnt/data/www/wstmart/mobile/view/default/self_shop.html";i:1491530392;s:51:"/mnt/data/www/wstmart/mobile/view/default/base.html";i:1491530392;s:53:"/mnt/data/www/wstmart/mobile/view/default/footer.html";i:1491530392;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title>自营店铺 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="__MOBILE__/css/swiper.min.css">
<link rel="stylesheet"  href="__MOBILE__/css/self_shop.css?v=<?php echo $v; ?>">

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
        <span class="seleft"></span><input type="text" id="wst-search" placeholder="搜索本店商品"><span class="seright"></span><p></p><span class="ui-icon-search" onclick="javascript:WST.search(2);"></span>
    </div>
    <div class="wst-in-icon" id="j-icon">
        <span class="cats" style="left:initial;right:2px;" onclick="javascript:dataShow();"></span>
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


<input type="hidden" name="" value="<?php echo $data['shop']['shopId']; ?>" id="shopId" autocomplete="off">
<input type="hidden" name="" value="-1" id="currPage" autocomplete="off">

     <section class="ui-container">
         <div class="shop-banner" <?php if($data['shop']['shopBanner']!=''): ?>style="background:url(__ROOT__/<?php echo $data['shop']['shopBanner']; ?>) no-repeat center top;background-size:cover;" <?php endif; ?>>
            <div class="ui-row-flex ui-whitespace">
                <div class="ui-col ui-col-3 banner-box" style="margin-right:-60px;">
                    <div class="shop-photo">
                        <img src="__ROOT__/<?php echo $data['shop']['shopImg']; ?>">
                    </div>
                    <div class="shop-info">
                            <div class="shop-info-name ui-nowrap ui-whitespace"><?php echo $data['shop']['shopName']; ?></div>
                            <div class="shop-info-trade ui-nowrap ui-whitespace">主营:<?php echo $data['shop']['shopKeeper']; ?></div>
                    </div>
                    <div class="clear"></div>

                </div>

                <div class="ui-col ui-col banner-box">
                    <div class="ui-btn-group">
                    <button class="ui-btn-lg shop-btn" id="fBtn" onclick="<?php if(($isFavor>0)): ?>WST.cancelFavorite(<?php echo $isFavor; ?>,1)<?php else: ?>WST.favorites(1,1)<?php endif; ?>">
                        <?php if(($isFavor>0)): ?>
                        <img src="__MOBILE__/img/icon_gz.png">
                            <span id="fStatus">已关注</span>
                        <?php else: ?>
                        <img src="__MOBILE__/img/icon_gz.png">
                            <span id="fStatus">关注店铺</span>
                        <?php endif; ?>
                    </button>
                    </div>
                </div>

            </div>
         </div>
         <?php if(!empty($data['shop']['shopAds'])): ?>
         <div class="shop-ads">
            <div class="ui-slider">
            <ul class="ui-slider-content" style="width: 300%">
                <?php if(is_array($data['shop']['shopAds']) || $data['shop']['shopAds'] instanceof \think\Collection): $i = 0; $__LIST__ = $data['shop']['shopAds'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ads): $mod = ($i % 2 );++$i;?>
                <li><span><a href="<?php echo $ads['adUrl']; ?>"><img style="width:100%; height:100%; display:block;" src="__ROOT__/<?php echo $ads['adImg']; ?>"></a></span></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            </div>
         </div>
         <?php endif; ?>


         <div class="wst-shl-ads" >
            <div class="title">店主推荐</div>
           <div class="wst-gol-adsb">
            <div class="swiper-container">
              <div class="swiper-wrapper">
                <?php if(is_array($data['rec']) || $data['rec'] instanceof \think\Collection): $i = 0; $__LIST__ = $data['rec'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$re): $mod = ($i % 2 );++$i;?>
                    <div class="swiper-slide" style="width:33.333333%;">
                         <div class="wst-gol-img j-imgRec"><a href="javascript:void(0)" onclick="WST.intoGoods(<?php echo $re['goodsId']; ?>)"><img src="__ROOT__/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="__ROOT__/<?php echo WSTImg($re['goodsImg'],3); ?>"></a></div>
                         <p>¥<?php echo $re['shopPrice']; ?></p>
                    </div>
                 <?php endforeach; endif; else: echo "" ;endif; ?>
              </div>
            </div>
            </div>
        </div>

        <div class="wst-shl-ads" >
            <div class="title">热卖商品</div>
           <div class="wst-gol-adsb">
            <div class="swiper-container">
              <div class="swiper-wrapper">
                <?php if(is_array($data['hot']) || $data['hot'] instanceof \think\Collection): $i = 0; $__LIST__ = $data['hot'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$hot): $mod = ($i % 2 );++$i;?>
                    <div class="swiper-slide" style="width:33.333333%;">
                         <div class="wst-gol-img j-imgRec1"><a href="javascript:void(0)" onclick="WST.intoGoods(<?php echo $hot['goodsId']; ?>)"><img src="__ROOT__/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="__ROOT__/<?php echo WSTImg($hot['goodsImg'],3); ?>"></a></div>
                         <p>¥<?php echo $hot['shopPrice']; ?></p>
                    </div>
                 <?php endforeach; endif; else: echo "" ;endif; ?>
              </div>
            </div>
            </div>
        </div>
        <script id="gList" type="text/html">
             <div class="wst-in-title">
             <ul class="ui-row shop-floor-title f{{d.currPage}}">
             <li class="ui-col ui-col-80">{{d.catName}}</li>
             <li class="ui-col ui-col-20"><a href="{{WST.U('mobile/shops/home','shopId=1&ct1='+d.catId)}}" class="shop-more">更多</a></li>
             </ul>
            {{# if(d.goods.length>0){ }}
              {{# for(var i=0; i<d.goods.length; i++){ }}
                       <div class="wst-in-goods" onclick="javascript:WST.intoGoos({{d.goods[i].goodsId}});">
                       <div class="img j-imgAdapt"><a href="javascript:void(0);" onclick="javascript:WST.intoGoods({{d.goods[i].goodsId}});">
                       <img src="{{# window.conf.ROOT+'/'+window.conf.GOODS_LOGO}}" data-echo="__ROOT__/{{d.goods[i].goodsImg}}" title="{{d.goods[i].goodsName}}"/></a></div>
                       <div class="name ui-nowrap-multi">{{d.goods[i].goodsName}}</div>
                       <div class="info"><span class="price">¥{{d.goods[i].shopPrice}}</span><span class="deal">成交数:{{d.goods[i].saleNum}}</span></div>
                       </div>
               {{# } }}
             {{# } }}
             <div class="wst-clear"></div>
        </script>

        <!-- 商品列表 -->
        <div id="goods-list"></div>


<div class="wst-cover" id="cover"></div>

<div class="wst-fr-box" id="frame">
    <div class="title"><span>商品分类</span><i class="ui-icon-close-page" onclick="javascript:dataHide();"></i><div class="wst-clear"></div></div>
    <div class="content" id="content">


       <div class="ui-scrollerl" id="ui-scrollerl">
            <ul>
                <?php if(is_array($data['shopcats']) || $data['shopcats'] instanceof \think\Collection): $k = 0; $__LIST__ = $data['shopcats'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$go): $mod = ($k % 2 );++$k;?>
                <li id="goodscate" class="wst-goodscate <?php if(($k==1)): ?>wst-goodscate_selected<?php endif; ?>" onclick="javascript:showRight(this,<?php echo $k-1; ?>);"><?php echo $go['catName']; ?></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        <?php if(is_array($data['shopcats']) || $data['shopcats'] instanceof \think\Collection): $k = 0; $__LIST__ = $data['shopcats'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$go): $mod = ($k % 2 );++$k;?>
        <div class="wst-scrollerr goodscate1" <?php if(($k!=1)): ?>style="display:none;"<?php endif; ?>>

            <ul>
                <li class="wst-goodsca">
                    <a href="javascript:void(0);" onclick="javascript:goGoodsList(<?php echo $go['catId']; ?>);"><span>&nbsp;<?php echo $go['catName']; ?></span></a>
                    <a href="javascript:void(0);" onclick="javascript:goGoodsList(<?php echo $go['catId']; ?>);"><i class="ui-icon-arrow"></i></a>
                </li>
                <li>
                    <div class="wst-goodscat">
                        <?php if(is_array($go['children']) || $go['children'] instanceof \think\Collection): $i = 0; $__LIST__ = $go['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$go1): $mod = ($i % 2 );++$i;?>
                        <span><a href="javascript:void(0);" onclick="javascript:goGoodsList(<?php echo $go['catId']; ?>,<?php echo $go1['catId']; ?>);"><?php echo $go1['catName']; ?></a></span>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </li>
            </ul>


            <ul>
                <li>
                    <div class="wst-goodscats">
                        <span>&nbsp;</span>
                    </div>
                </li>
            </ul>
        </div>
        <?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
</section>



<script type='text/javascript' src='__MOBILE__/js/jquery.min.js'></script>
<script type='text/javascript' src='__MOBILE__/js/swiper.jquery.min.js'></script>
<script type='text/javascript' src='__MOBILE__/js/self_shop.js'></script>

<script>
$(function(){
   <?php if(!empty($data['shop']['shopAds'])): ?>
    shopAds();
   <?php endif; ?>
   WST.initFooter('home');
});
</script>

</body>
</html>