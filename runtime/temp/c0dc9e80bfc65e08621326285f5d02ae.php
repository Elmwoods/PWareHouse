<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:59:"F:\wamp\www\jingo/wstmart/admin\view\orderrefunds\list.html";i:1496208774;s:46:"F:\wamp\www\jingo/wstmart/admin\view\base.html";i:1496208774;}*/ ?>
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

<div class="l-loading" style="display: block" id="wst-loading"></div>
<div class="wst-toolbar">
商家所在地：
<select id="areaId1" class='ipt j-ipt' level="0" onchange="WST.ITAreas({id:'areaId1',val:this.value,className:'j-ipt'});">
  <option value="-1">-请选择-</option>
  <?php if(is_array($areaList) || $areaList instanceof \think\Collection): $i = 0; $__LIST__ = $areaList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
  <option value="<?php echo $vo['areaId']; ?>"><?php echo $vo['areaName']; ?></option>
  <?php endforeach; endif; else: echo "" ;endif; ?>
</select>
    <br/>
订单编号：<input type="text" name="orderNo"  placeholder='订单编号' id="orderNo" class='j-ipt'/>
店铺：<input type="text" name="shopName"  placeholder='店铺名称/店铺编号' id="shopName" class='j-ipt'/>
配送方式：<select id='deliverType' class='j-ipt'>
         <option value='-1'>全部</option>
         <option value='1'>自提</option>
         <option value='0'>送货上门</option>
       </select>
退款状态：<select id='isRefund' class='j-ipt'>
         <option value='-1'>全部</option>
         <option value='1'>已退款</option>
         <option value='0'>未退款</option>
       </select>
   <button class="btn btn-blue" onclick='javascript:loadRefundGrid(0)'>查询</button>
   <div style='clear:both'></div>
</div>
<div id="maingrid"></div>
<script>
$(function(){initRefundGrid();})
</script>


<script src="__ADMIN__/orderrefunds/orderrefunds.js?v=<?php echo $v; ?>" type="text/javascript"></script>

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