var grid;
function initGrid(){
	grid = $("#maingrid").ligerGrid({
		url:WST.AU('cron://cron/pageQuery'),
		pageSize:WST.pageSize,
		pageSizeOptions:WST.pageSizeOptions,
		height:'99%',
        width:'100%',
        minColToggle:6,
        rownumbers:true,
        columns: [
	        { display: '计划任务名称', name: 'cronName', width:150,isSort: false},
	        { display: '计划任务描述', name: 'cronDesc', isSort: false},
	        { display: '上次执行时间', name: 'runTime', width:150, isSort: false,render: function (rowdata, rowindex, value){
	        	return (rowdata['runTime']==0)?'-':rowdata['runTime'];
	        }},
	        { display: '执行状态', name: 'isEnable', width:60, isSort: false,render: function (rowdata, rowindex, value){
	        	return (rowdata['isRunSuccess']==1)?"<span class='label label-success'>成功</span>":"<span class='label label-danger'>失败";
	        }},
	        { display: '下次执行时间', name: 'nextTime', width:150, isSort: false,render: function (rowdata, rowindex, value){
	        	return (rowdata['nextTime']==0)?'-':rowdata['nextTime'];
	        }},
	        { display: '作者', name: 'auchor', isSort: false,width:120,render: function (rowdata, rowindex, value){
	        	return '<a href="'+rowdata['authorUrl']+'" target="_blank">'+rowdata['author']+'</a>';
	        }},
	        { display: '计划状态', name: 'isEnable', width:60, isSort: false,render: function (rowdata, rowindex, value){
	        	return (rowdata['isEnable']==1)?"<span class='label label-success'>启用</span>":"<span class='label label-gray'>停用";
	        }},
	        { display: '操作', name: 'op',isSort: false,width:120,render: function (rowdata, rowindex, value){
	        	var h="";
	            if(WST.GRANT.CRON_JHRW_04){
	            	h += "<a href='javascript:toEdit(" + rowdata['id'] + ")'>修改</a> ";
	            	if(rowdata['isEnable']==0){
	            	    h += "<a href='javascript:changgeEnableStatus(" + rowdata['id'] + ",1)'>启用</a> "; 
		            }else{
		            	h += "<a href='javascript:changgeEnableStatus(" + rowdata['id'] + ",0)'>停用</a> "; 
		                h += '<a href="javascript:run(\'' + rowdata['id'] + '\')">执行</a>';
		            }
		            
	            }
	            return h;
	        }}
        ]
    });
}


function toEdit(id){
	location.href=WST.AU('cron://cron/toEdit','id='+id);
}
function checkType(v){
   $('.cycle').hide();
   $('.cycle'+v).show();
}
function run(id){
	var box = WST.confirm({content:'你确定要执行该任务吗？',yes:function(){
		var loading = WST.msg('正在执行计划任务，请稍后...',{icon: 16,time:6000000000});
		$.post(WST.AU('cron://cron/runCron'),{id:id},function(data,textStatus){
			layer.close(loading);
	        var json = WST.toAdminJson(data);
	        if(json.status=='1'){
	           	WST.msg(json.msg,{icon:1});
	           	layer.close(box);
	           	grid.reload();
	        }else{
	           	WST.msg(json.msg,{icon:2});
	        }
		})
	}});
}
function edit(id){
    var params = WST.getParams('.ipt');
	params.id = id;
	var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	$.post(WST.AU('cron://cron/edit'),params,function(data,textStatus){
		layer.close(loading);
		var json = WST.toAdminJson(data);
		if(json.status=='1'){
		   	WST.msg("操作成功",{icon:1},function(){
		   		location.href=WST.AU('cron://cron/index');
		   	});
		}else{
		   	WST.msg(json.msg,{icon:2});
		}
	});
}
function changgeEnableStatus(id,type){
	var msg = (type==1)?"您确定要启用该计划任务吗?":"您确定要停用该计划任务吗?"
	var box = WST.confirm({content:msg,yes:function(){
	           var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	           	$.post(WST.AU('cron://cron/changeEnableStatus'),{id:id,status:type},function(data,textStatus){
	           			  layer.close(loading);
	           			  var json = WST.toAdminJson(data);
	           			  if(json.status=='1'){
	           			    	WST.msg(json.msg,{icon:1});
	           			    	layer.close(box);
	           		            grid.reload();
	           			  }else{
	           			    	WST.msg(json.msg,{icon:2});
	           			  }
	           		});
	            }});
}






		