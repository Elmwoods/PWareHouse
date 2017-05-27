
//跳转支付
function toPay(payCode){
	var params = {};
	params.auctionId = $.trim($("#auctionId").val());
	params.payObj = $.trim($("#payObj").val());
	params.payFrom = 2;
	var client = (payCode=="weixinpays")?"wx":"";
	$.post(WST.AU('auction://'+payCode+client+'/get'+payCode+"url"),params,function(data) {
		var json = WST.toJson(data);
		if(json.status==1){
			if(payCode=="unionpays"){
				location.href = WST.AU('auction://unionpays/tounionpays',params);
			}else if(payCode=="weixinpays" && client=="wx"){
				location.href = WST.AU('auction://weixinpayswx/topay',params);
			}else{
				location.href = json.url;
			}
		}else{
			WST.msg(json.msg, {icon: 5,time:1500},function(){});
		}
	});
}

//余额支付
function walletPay(){
	var payPwd = $('#payPwd').val();
	if(!payPwd){
		WST.msg('请输入支付密码','info');
		return;
	}
	WST.load('正在核对密码···');
    var auctionId = $('#auctionId').val();
    var payObj = $('#payObj').val();
	var params = {};
    params.payPwd = payPwd;
    params.key = $('#paykey').val();
    $('.wst-btn-dangerlo').attr('disabled', 'disabled');
	$.post(WST.AU('auction://wallets/paybywallet'),params,function(data,textStatus){
		WST.noload(); 
		var json = WST.toJson(data);
	    if(json.status==1){
	    	WST.msg(json.msg,'success');
	        setTimeout(function(){
	        	if(payObj=='bao'){
		        	location.href=WST.AU('auction://goods/wxdetail','id='+auctionId);
	        	}else{
	        		location.href=WST.AU('auction://users/wxcheckPayStatus','id='+auctionId);
	        	}
	        },2000);
	    }else{
	    	WST.msg(json.msg,'info');
	        setTimeout(function(){
	            $('.wst-btn-dangerlo').removeAttr('disabled');
	        },2000);
	    }
	});
}