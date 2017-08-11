<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:54:"F:\wamp\www\jingo/wstmart/admin\view\styles\index.html";i:1496208774;s:46:"F:\wamp\www\jingo/wstmart/admin\view\base.html";i:1496208774;}*/ ?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>后台管理中心 - <?php echo WSTConf('CONF.mallName'); ?></title>
<meta name="Keywords" content=""/>
<meta name="Description" content=""/> 
<link href="__ADMIN__/js/ligerui/skins/Aqua/css/ligerui-all.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" /> 
<link href="__STATIC__/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">

<link href="__ADMIN__/css/style.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />   
<script src="__STATIC__/js/jquery.min.js?v=<?php echo $v; ?>"></script>  
<script src="__ADMIN__/js/ligerui/js/ligerui.all.js?v=<?php echo $v; ?>" type="text/javascript"></script> 
<script type='text/javascript' src='__STATIC__/plugins/layer/layer.js?v=<?php echo $v; ?>'></script> 
<script src="__STATIC__/js/common.js?v=<?php echo $v; ?>"></script>
<script>
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',\think\Request::instance()->root(true)); ?>","ROOT":"__ROOT__","APP":"__APP__","STATIC":"__STATIC__","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'<?php echo implode(",",session("WST_STAFF.privileges")); ?>',"ROUTES":'<?php echo WSTRoute(); ?>'}
</script>
<script src="__ADMIN__/js/common.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__STATIC__/plugins/validator/jquery.validator.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__STATIC__/plugins/validator/local/zh-CN.js?v=<?php echo $v; ?>"></script>
</head>
<body>

<div class="l-loading" style='display:block' id="wst-loading"></div>
<div id="wst-tabs" style="width:100%; height:100%;overflow: hidden; border: 1px solid #D3D3d3;" class="liger-tab">
   <?php if(is_array($cats) || $cats instanceof \think\Collection): $i = 0; $__LIST__ = $cats;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
   <div id="<?php echo $vo['styleSys']; ?>" tabId="<?php echo $vo['styleSys']; ?>"  title="<?php echo $vo['styleSys']; ?>" class='wst-tab'  style="height: 99%">
   <div id='style_<?php echo $vo['styleSys']; ?>'></div>
   </div>
   <?php endforeach; endif; else: echo "" ;endif; ?>
</div>
<script id="tblist" type="text/html">
{{# var dl = d['list'];for(var i = 0; i < dl.length; i++){ }}
<div class='style-box'>
   <div class='style-img'>
     <a href='#'>
      <img src='__ROOT__/wstmart/{{d["sys"]}}/view/{{dl[i]["stylePath"]}}/img/screenshot.png'/>
     </a>
   </div>
   <div class='style-txt'>标题：{{dl[i]['styleName']}}</div>
   <div class='style-author'>作者：{{dl[i]['styleAuthor']}}</div>
   <div class='style-author'>介绍：{{# if(dl[i]['styleShopSite']!=''){}}<a target='_blank' href='{{dl[i]['styleShopSite']}}'>访问网址</a>{{# }else{ }}无{{#}}}</div>
   <div class='style-op'>
   {{# if(dl[i]['isUse']==1){}}
   <input class='btn btn-disabled style_{{dl[i]['id']}}' dataid='{{dl[i]['id']}}' type='button' value='应用中' disabled />
   {{# }else{ }}
   <input class='btn btn-blue style_{{dl[i]['id']}}' dataid='{{dl[i]['id']}}' type='button' value='应用' />
   {{# } }}
   </div>
</div>
{{#}}}
</script>


<script src="__ADMIN__/styles/styles.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<script>
function showImg(opt){
	layer.photos(opt);
}
function showBox(opts){
	return WST.open(opts);
}
</script>
<?php echo hook('initCronHook'); ?>
</body>
</html>