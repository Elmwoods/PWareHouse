jQuery.noConflict();
function onSwitch(obj,n){
	$(obj).children('.ui-icon-push').removeClass('ui-icon-unchecked-s').addClass('ui-icon-checked-s wst-active');
	$(obj).siblings().children('.ui-icon-push').removeClass('ui-icon-checked-s wst-active').addClass('ui-icon-unchecked-s');
	if(n==1){
		$('#j-invoice').show();
	}else{
		$('#j-invoice').hide();
	}
}
function inDetermine(n){
	$('#'+n+' .wst-active').each(function(){
		type = $(this).attr('mode');
		word = $(this).attr('word');
		if(n=='payments')payCode = $(this).attr('payCode');
	});
	if(n=='invoices'){
		if(type==1){
			var invoiceClient =  $('#invoiceClient').val();
			if(invoiceClient==''){
				WST.msg('请填写发票抬头','info');
				return false;
			}
		}
	}
	$('#'+n+'h').val(type);
	$('#'+n+'t').html(word);
	if(n=='payments'){
		$('#'+n+'w').val(payCode);
	}
	getCartMoney();
	dataHide(n);
}
//计算价格
function getCartMoney(){
	var params = {};
	params.isUseScore = $('#scoreh').val();
	params.useScore = $('#userOrderScore').html();
	params.areaId2 = $('#areaId').val();
	params.deliverType = $('#givesh').val();
	params.sign = $('#sign').val();
	WST.load('正在计算价格...');
	if(params.sign==1){
		$.post(WST.U('mobile/carts/getCartMoney'),params,function(data,textStatus){
			WST.noload();
			var json = WST.toJson(data);
			if(json.status==1){
			    json = json.data;
			    for(var key in json.shops){
			    	// 设置每间店铺的运费及总价格
			    	$('#shopF_'+key).html('¥'+json.shops[key]['freight'].toFixed(2));
			    	$('#shopC_'+key).html('¥'+json.shops[key]['goodsMoney'].toFixed(2));
			    }
			 	$('#totalMoney').html('¥'+json.realTotalMoney.toFixed(2));
			}
		});
	}else if(params.sign==2){//虚拟商品
		params.deliverType = 1;
		$.post(WST.U('mobile/carts/getQuickCartMoney'),params,function(data,textStatus){
			WST.noload();
			var json = WST.toJson(data);
			if(json.status==1){
			    json = json.data;
			 	$('#totalMoney').html('¥'+json.realTotalMoney.toFixed(2));
			}
		});
	}
}
//提交订单
function submitOrder(){
	var addressId =  $('#addressId').val();
	if(addressId==''){
		WST.msg('请选择收货地址','info');
		return false;
	}
	WST.load('提交中···');
    var param = {};
    param.s_addressId = addressId;
    param.s_areaId = $('#areaId').val();
    param.payType = $('#paymentsh').val();
    param.payCode = $('#paymentsw').val();
    param.isUseScore = $('#scoreh').val();
    param.useScore = $('#userOrderScore').html();
	$('.wst-se-sh .shopn').each(function(){
		shopId = $(this).attr('shopId');
	    param['remark_'+shopId] = $('#remark_'+shopId).val();
	});
    param.deliverType = $('#givesh').val();
    param.isInvoice = $('#invoicesh').val();
    param.invoiceClient = $('#invoiceClient').val();
    $('.wst-se-confirm .button').attr('disabled', 'disabled');
	$.post(WST.U('mobile/orders/submit'),param,function(data,textStatus){
		var json = WST.toJson(data);
	    WST.noload();
	    if(json.status==1){
	    	WST.msg(json.msg,'success');
		      setTimeout(function(){
		    	  if(param.payType==1){
		    		  if(param.payCode=='alipays' || param.payCode==''){
			    		  location.href = WST.U('mobile/alipays/toAliPay',{"orderNo":json.data,'isBatch':1});
		    		  }else if(param.payCode=='wallets'){
		    			  location.href = WST.U('mobile/wallets/payment',{"orderNo":json.data,'isBatch':1});
		    		  }else if(param.payCode=='unionpays'){
		    			  location.href = WST.U('mobile/unionpays/toUnionpay',{"orderNo":json.data,'isBatch':1});
		    		  }
		    	  }else{
		    		  location.href = WST.U('mobile/orders/index');
		    	  }
		      },1000);
	    }else{
	    	WST.msg(json.msg,'info');
	    	$('.wst-se-confirm .button').removeAttr('disabled');
	    }
	});
}
//提交虚拟商品订单
function quickSubmitOrder(){
	WST.load('提交中···');
    var param = {};
    param.payType = $('#paymentsh').val();
    param.payCode = $('#paymentsw').val();
    param.isUseScore = $('#scoreh').val();
    param.useScore = $('#userOrderScore').html();
	$('.wst-se-sh .shopn').each(function(){
		shopId = $(this).attr('shopId');
	    param['remark_'+shopId] = $('#remark_'+shopId).val();
	});
    param.isInvoice = $('#invoicesh').val();
    param.invoiceClient = $('#invoiceClient').val();
    $('.wst-se-confirm .button').attr('disabled', 'disabled');
	$.post(WST.U('mobile/orders/quickSubmit'),param,function(data,textStatus){ 
		var json = WST.toJson(data);
	    WST.noload();
	    if(json.status==1){
	    	WST.msg(json.msg,'success');
		      setTimeout(function(){
		    	  if(param.payType==1){
		    		  if(param.payCode=='alipays' || param.payCode==''){
			    		  location.href = WST.U('mobile/alipays/toAliPay',{"orderNo":json.data,'isBatch':1});
		    		  }else if(param.payCode=='wallets'){
		    			  location.href = WST.U('mobile/wallets/payment',{"orderNo":json.data,'isBatch':1});
		    		  }else if(param.payCode=='unionpays'){
		    			  location.href = WST.U('mobile/unionpays/toUnionpay',{"orderNo":json.data,'isBatch':1});
		    		  }
		    	  }else{
		    		  location.href = WST.U('mobile/orders/index');
		    	  }
		    	  
		      },1000);
	    }else{
	    	WST.msg(json.msg,'info');
	    	$('.wst-se-confirm .button').removeAttr('disabled');
	    }
	});
}
function addAddress(type,id){
	location.href = WST.U('mobile/useraddress/index','type='+type+'&addressId='+id);
}
var dataHeight = $(".frame").css('height');
	dataHeight = parseInt(dataHeight)+50+'px';
$(document).ready(function(){
	WST.imgAdapt('j-imgAdapt');
    $(".frame").css('bottom','-'+dataHeight);
});
//弹框
function dataShow(n){
	jQuery('#cover').attr("onclick","javascript:dataHide('"+n+"');").show();
	jQuery('#'+n).animate({"bottom": 0}, 500);
	//显示已保存的数据
	var type = $('#'+n+'h').val();
	if(type==0){
		jQuery('i[class*="'+n+'"]').removeClass('ui-icon-checked-s wst-active').addClass('ui-icon-unchecked-s');
		jQuery('.'+n+'0').removeClass('ui-icon-unchecked-s').addClass('ui-icon-checked-s wst-active');
	}else{
		jQuery('i[class*="'+n+'"]').removeClass('ui-icon-checked-s wst-active').addClass('ui-icon-unchecked-s');
		jQuery('.'+n+'1').removeClass('ui-icon-unchecked-s').addClass('ui-icon-checked-s wst-active');
	}
	if(n=='payments'){
		var payCode = $('#'+n+'w').val();
		jQuery('i[class*="'+n+'"]').removeClass('ui-icon-checked-s wst-active').addClass('ui-icon-unchecked-s');
		jQuery('.'+n+'_'+payCode).removeClass('ui-icon-unchecked-s').addClass('ui-icon-checked-s wst-active');
	}
	if(n=='invoices'){
		if(type==0){
			jQuery('#j-invoice').hide();
		}else{
			jQuery('#j-invoice').show();
		}
	}
}
function dataHide(n){
	jQuery('#'+n).animate({'bottom': '-'+dataHeight}, 500);
	jQuery('#cover').hide();
}
document.addEventListener('touchmove', function(event) {
    //判断条件,条件成立才阻止背景页面滚动,其他情况不会再影响到页面滚动
    if(!jQuery("#cover").is(":hidden")){
        event.preventDefault();
    }
})