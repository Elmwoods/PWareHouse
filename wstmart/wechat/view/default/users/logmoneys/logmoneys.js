jQuery.noConflict();
$(document).ready(function(){
  WST.initFooter('user');
  // 弹出层
   $("#frame").css('top',0);
});
//资金流水列表
function getRecordList(){
	  $('#Load').show();
	    loading = true;
	    var param = {};
	    param.type = $('#type').val() || -1;
	    param.pagesize = 10;
	    param.page = Number( $('#currPage').val() ) + 1;
	    $.post(WST.U('wechat/logMoneys/pageQuery'), param, function(data){
	        var json = WST.toJson(data.data);
	        var html = '';
	        if(json && json.Rows && json.Rows.length>0){
	          var gettpl = document.getElementById('scoreList').innerHTML;
	          laytpl(gettpl).render(json.Rows, function(html){
	            $('#score-list').append(html);
	          });

	          $('#currPage').val(json.CurrentPage);
	          $('#totalPage').val(json.TotalPage);
	        }else{
	           var mhtml = '<ul class="ui-row-flex wst-flexslp">';
	           mhtml += '<li class="ui-col ui-flex ui-flex-pack-center">';
	           mhtml += '<p>暂无相关信息</p>';
	           mhtml += '</li>';
	           mhtml += '</ul>';
	          $('#score-list').html(mhtml);
	        }
	        loading = false;
	        $('#Load').hide();
	        echo.init();//图片懒加载
	    });
	}
// 验证支付密码资金
function check(){
  var isSetPayPwd = $('#isSet').val();
  if(isSetPayPwd==0){
  		$('#wst-event2').html('去设置');
  		WST.dialog('您未设置支付密码','location.href="'+WST.U('wechat/users/editPayPass')+'"');
		return;
	}else{
		showPayBox();
	}
  	
}
// 支付密码对话框
function showPayBox(){
    $("#wst-event3").attr("onclick","javascript:checkSecret()");
    $("#payPwdBox").dialog("show");
}
function checkSecret(){
	var payPwd = $.trim($('#payPwd').val());
	if(payPwd==''){
		WST.msg('请输入支付密码','info');
		return;
	}
	$.post(WST.U('wechat/logmoneys/checkPayPwd'),{payPwd:payPwd},function(data){
		var json = WST.toJson(data);
		if(json.status==1){
			$("#payPwdBox").dialog("hide");
			location.href=WST.U('wechat/cashconfigs/index');
		}else{
			WST.msg(json.msg);
		}
	})
}
//资金流水
function toRecord(){
	location.href = WST.U('wechat/logmoneys/record');
}

/********************  提现层 *************************/

function getCash(){
	$('#money').val('');
	$('#cashPayPwd').val('');
	$.post(WST.U('wechat/cashconfigs/pageQuery'),{},function(data){
		var json = WST.toJson(data);
		var html = '<option value="">请选择</option>';
		if(json.status==1){
			$(json.data.Rows).each(function(k,v){
				html +='<option value='+v.id+'>'+v.accUser+'|'+v.accNo+'</option>';
			});
			$('#accId').html(html);
			// 判断是否禁用按钮
			if($('#userMoney').attr('money')<100)$('#submit').attr('disabled','disabled');
			dataShow();
		}else{
			WST.msg(json.msg,'info');
		}
	})
}

// 检测提现金额是否小于用户可用金额
$('#money').keyup(function(){
	var uMoney = $('#userMoney').attr('money');

	if($('#money').val()<uMoney){
		return true;
	}else{
		$('#submit').attr('disabled','disabled');
	}
});

// 申请提现
function drawMoney(){
	var accId = $('#accId').val();
	var money = $('#money').val();
	var payPwd = $('#cashPayPwd').val();

	if(accId==''){
		WST.msg('请选择提现账号','info');
		return;
	}
	if(money==''){
		WST.msg('请输入提现金额','info');
		return
	}
	if(payPwd==''){
		WST.msg('请输入支付密码','info');
		return
	}
	var param = {};
	param.accId = accId;
	param.money = money;
	param.payPwd = payPwd;
	$.post(WST.U('wechat/cashdraws/drawMoney'),param,function(data){
		var json = WST.toJson(data);
		if(json.status==1){
			WST.msg('提现申请已提交','success');
			setTimeout(function(){
				history.go(0);
			},1000);
		}else{
			WST.msg(json.msg,'info');
		}
	})
}












//弹框
function dataShow(){
    jQuery('#cover').attr("onclick","javascript:dataHide();").show();
    jQuery('#frame').animate({"right": 0}, 500);
}
function dataHide(){
    var dataHeight = $("#frame").css('height');
    var dataWidth = $("#frame").css('width');
    jQuery('#frame').animate({'right': '-'+dataWidth}, 500);
    jQuery('#cover').hide();
}