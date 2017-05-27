var grid;
$(function(){initGrid()})
function initGrid(){
	grid = $("#maingrid").ligerGrid({
		url:WST.AU('bargain://admin/pageyByJoins','bargainId='+$('#bargainId').val()),
		pageSize:WST.pageSize,
		pageSizeOptions:WST.pageSizeOptions,
		height:'99%',
        width:'100%',
        minColToggle:6,
        rownumbers:true,
        columns: [
            { display: '参与人', name: 'loginName',isSort: false,render: function (rowdata, rowindex, value){
	        	return rowdata['loginName'];
	        }},
	        { display: '原价', name: 'startPrice',isSort: false,render: function (rowdata, rowindex, value){
	        	return "￥"+rowdata['startPrice'];
	        }},
	        { display: '当前价', name: 'currPrice',isSort: false,render: function (rowdata, rowindex, value){
	        	return "￥"+rowdata['currPrice'];
	        }},
	        { display: '亲友团', name: 'shopName',isSort: false,render: function (rowdata, rowindex, value){
	        	return "<a style='color:blue' href='"+WST.AU('bargain://admin/showHelps','bargainId='+rowdata['bargainId']+'&bargainJoinId='+rowdata['id'])+"'>"+rowdata['helpNum']+"</a>";
	        }},
	        { display: '参与时间', name: 'createTime',width:170,isSort: false},
	        { display: '订单号', name: 'orderNo',width:150,isSort: false}
        ]
    });
}
function loadGrid(){
	var params = {};
	params.bargainId = $('#bargainId').val();
	params.key = $('#key').val();
	grid.set('url',WST.AU('bargain://admin/pageyByJoins',params));
}