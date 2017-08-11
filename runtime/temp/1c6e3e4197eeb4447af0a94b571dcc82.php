<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:88:"E:\wamp\www\jingomall\jingo\addons\decoration\view\home/shops/shop_decoration_block.html";i:1501824290;s:94:"E:\wamp\www\jingomall\jingo\addons\decoration\view\home\shops\shop_decoration_module_html.html";i:1497401459;s:95:"E:\wamp\www\jingomall\jingo\addons\decoration\view\home\shops\shop_decoration_module_goods.html";i:1497401459;s:95:"E:\wamp\www\jingomall\jingo\addons\decoration\view\home\shops\shop_decoration_module_slide.html";i:1497401459;s:98:"E:\wamp\www\jingomall\jingo\addons\decoration\view\home\shops\shop_decoration_module_hot_area.html";i:1497401459;}*/ ?>
<?php 
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
