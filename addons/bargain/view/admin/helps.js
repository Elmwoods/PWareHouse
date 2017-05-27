var grid;
$(function(){initGrid()})
function initGrid(){
	grid = $("#maingrid").ligerGrid({
		url:WST.AU('bargain://admin/pageByHelps','bargainJoinId='+$('#bargainJoinId').val()),
		pageSize:WST.pageSize,
		pageSizeOptions:WST.pageSizeOptions,
		height:'99%',
        width:'100%',
        minColToggle:6,
        rownumbers:true,
        columns: [
            { display: '亲友名称', name: 'userName',isSort: false,render: function (rowdata, rowindex, value){
	        	return rowdata['userName'];
	        }},
	        { display: '帮砍金额', name: 'minusMoney',isSort: false,render: function (rowdata, rowindex, value){
	        	return "￥"+rowdata['minusMoney'];
	        }},
	        { display: '砍价时间', name: 'createTime',width:170,isSort: false}
        ]
    });
}
function loadGrid(){
	var params = {};
	params.bargainJoinId = $('#bargainJoinId').val();
	params.key = $('#key').val();
	grid.set('url',WST.AU('bargain://admin/pageByHelps',params));
}