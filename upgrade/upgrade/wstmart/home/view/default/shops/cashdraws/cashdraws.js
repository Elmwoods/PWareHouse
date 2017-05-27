$(function () {
	$('#tab').TabPanel({tab:0,callback:function(tab){
		switch(tab){
		   case 0:pageQuery(0);break;
		   case 1:pageConfigQuery(0);break;
		}	
	}})
});
var isSetPayPwd = 1;
function getShopMoney(){
	$.post(WST.U('home/shops/getShopMoney'),{},function(data,textStatus){
		var json = WST.toJson(data);
		if(json.status==1){
			$('#shopMoney').html('¥'+json.data.shopMoney);
			$('#lockMoney').html('¥'+json.data.lockMoney);
			if(json.data.isDraw==1){
               $('#drawBtn').show();
			}else{
               $('#drawBtn').hide();
			}
			isSetPayPwd = json.data.isSetPayPwd;
		}
	});
}
function pageQuery(p){
	var tips = WST.load({msg:'正在获取数据，请稍后...'});
	var params = {};
	params.page = p;
	$.post(WST.U('home/cashdraws/pageQueryByShop'),params,function(data,textStatus){
		layer.close(tips);
	    var json = WST.toJson(data);
	    if(json.status==1){
	    	json = json.data;
	    	if(params.page>json.TotalPage && json.TotalPage >0){
               pageQuery(json.TotalPage);
               return;
            }
		    var gettpl = document.getElementById('draw-list').innerHTML;
		    laytpl(gettpl).render(json.Rows, function(html){
		       	$('#draw-page-list').html(html);
		    });
		    laypage({
			        cont: 'draw-pager', 
			        pages:json.TotalPage, 
			        curr: json.CurrentPage,
			        skin: '#e23e3d',
			        groups: 3,
			        jump: function(e, first){
			        	if(!first){
			        		pageQuery(e.curr);
			        	}
			        } 
			});
	    }
	});
}
var w;
function toDrawMoney(){
	if(isSetPayPwd==0){
		WST.msg('您尚未设置支付密码，请先设置支付密码',{icon:2,time:1000},function(){
			location.href = WST.U('home/users/security');
		});
		return;
	}
    var tips = WST.load({msg:'正在获取数据，请稍后...'});
	$.post(WST.U('home/cashdraws/toEditByShop'),{},function(data,textStatus){
		layer.close(tips);
		w = WST.open({
		    type: 1,
		    title:"申请提现",
		    shade: [0.6, '#000'],
		    border: [0],
		    content: data,
		    area: ['550px', '250px'],
		    offset: '100px'
		});
	});
}
function drawMoney(){
	$('#drawForm').isValid(function(v){
		if(v){
			var params = WST.getParams('.j-ipt');
			var tips = WST.load({msg:'正在提交数据，请稍后...'});
			$.post(WST.U('home/cashdraws/drawMoneyByShop'),params,function(data,textStatus){
				layer.close(tips);
			    var json = WST.toJson(data);
			    if(json.status==1){
		            WST.msg(json.msg,{icon:1},function(){
		            	pageQuery(WSTCurrPage);
		            	getShopMoney();
		            	layer.close(w);
		            });
			    }else{
			    	WST.msg(json.msg,{icon:2});
			    }
			});
		}
	});
}
function layerclose(){
  layer.close(w);
}