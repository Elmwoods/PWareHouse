<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:54:"F:\wamp\www\jingo/wstmart/admin\view\images\index.html";i:1496208774;s:46:"F:\wamp\www\jingo/wstmart/admin\view\base.html";i:1496208774;}*/ ?>
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
<div id="main">
<table width='100%' cellspacing='0' cellpadding='0' class="l-grid-header-table wst-grid-tree">
   <tr class='l-grid-hd-row wst-grid-tree-hd'>
       <td width='30' class='l-grid-hd-cell l-grid-hd-cell-rownumbers' height='28' style="width:26px;">&nbsp;</td>
       <td width='150' class='l-grid-hd-cell' height='28'>目录</td>
       <td class='l-grid-hd-cell' height='28'>有效图片/无效图片</td>
       <td width='80' class='l-grid-hd-cell' height='28'>M</td>
       <td width='80' class='l-grid-hd-cell' height='28'>操作</td>
   </tr>
   <tbody id='list'></tbody>
</table>
</div>
<script>
$(function(){initSummary();});
</script>


<script src="__ADMIN__/images/images.js?v=<?php echo $v; ?>" type="text/javascript"></script>

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