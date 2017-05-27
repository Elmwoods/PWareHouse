var grid;
$(function(){initGrid()})
function initGrid(){
	grid = $("#maingrid").ligerGrid({
		url:WST.AU('auction://goods/pageAuctionLogQueryByAdmin','id='+$('#id').val()),
		pageSize:WST.pageSize,
		pageSizeOptions:WST.pageSizeOptions,
		height:'99%',
        width:'100%',
        minColToggle:6,
        rownumbers:true,
        columns: [
            { display: '竞拍人', name: 'loginName',isSort: false,render: function (rowdata, rowindex, value){
	        	return rowdata['loginName'];
	        }},
	        { display: '竞拍价格', name: 'shopName',isSort: false,render: function (rowdata, rowindex, value){
	        	return "￥"+rowdata['payPrice']+((rowdata['isTop']==1)?("&nbsp;&nbsp;<span class='label label-success'>最高价</span>"):"");
	        }},
	        { display: '竞拍时间', name: 'createTime',width:170,isSort: false},
	        { display: '订单号', name: 'orderNo',width:150,isSort: false}
        ]
    });
}
function loadGrid(){
	var params = {};
	params.id = $('#id').val();
	params.key = $('#key').val();
	grid.set('url',WST.AU('auction://goods/pageAuctionLogQueryByAdmin',params));
}