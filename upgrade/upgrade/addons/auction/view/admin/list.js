var grid1,grid2,flag = true;
$(function(){
	var tab = $("#wst-tabs").ligerTab({
         height: '99%',
         changeHeightOnResize:true,
         showSwitchInTab : false,
         showSwitch: false,
         onAfterSelectTabItem:function(n){
           if(n=='tabitem2'){
              if(flag){
                initGrid2();
                flag = false;
              }else{
                grid2.reload();
              }
           }
         }
    });
    initGrid1();
})
function initGrid1(){
	grid1 = $("#maingrid1").ligerGrid({
		url:WST.AU('auction://goods/pageQueryByAdmin'),
		pageSize:WST.pageSize,
		pageSizeOptions:WST.pageSizeOptions,
		height:'99%',
        width:'100%',
        rowHeight:65,
        minColToggle:6,
        rownumbers:true,
        columns: [
            { display: '&nbsp;', name: 'goodsName',width:60,align:'left',heightAlign:'left',isSort: false,render: function (rowdata, rowindex, value){
            	return "<img style='height:60px;width:60px;' src='"+WST.conf.ROOT+"/"+rowdata['goodsImg']+"'>";
            }},
	        { display: '商品名称', name: 'goodsName',isSort: false,render: function (rowdata, rowindex, value){
	        	return "<div class='valign-m'>"+rowdata['goodsName']+"</div>";
	        }},
	        { display: '所属店铺', name: 'shopName',isSort: false,render: function (rowdata, rowindex, value){
	        	return "<div class='valign-m'>"+rowdata['shopName']+"</div>";
	        }},
	        { display: '起拍价', name: 'auctionPrice',width:50,isSort: false,render: function (rowdata, rowindex, value){
	        	return "<div class='valign-m'>"+rowdata['auctionPrice']+"</div>";
	        }},
	        { display: '保证金', name: 'cautionMoney',width:50,isSort: false,render: function (rowdata, rowindex, value){
	        	return "<div class='valign-m'>"+rowdata['cautionMoney']+"</div>";
	        }},
	        { display: '加价幅度', name: 'fareInc',width:50,isSort: false,render: function (rowdata, rowindex, value){
	        	return "<div class='valign-m'>"+rowdata['fareInc']+"</div>";
	        }},
	        { display: '参与人数', name: 'auctionNum',width:50,isSort: false,render: function (rowdata, rowindex, value){
	        	return "<div class='valign-m'><a href='javascript:logs(" + rowdata['auctionId'] + ")'>"+rowdata['auctionNum']+"</a></div>";
	        }},
	        { display: '拍卖时间', name: 'startTime',isSort: false,render: function (rowdata, rowindex, value){
	        	return "<div class='time'>"+rowdata['startTime']+"<br/>至<br/>"+rowdata['endTime']+"</div>";
	        }},
	        { display: '状态', name: 'saleNum',width:60,isSort: false,render: function (rowdata, rowindex, value){
	        	if(rowdata['status']==1){
                    return "<br/><span class='label label-success'>进行中</span>";
	        	}else if(rowdata['status']==0){
                    return "<br/><span class='label label-info' >未开始</span>";
	        	}else{
                    return "<br/><span class='label label-gray' >已结束</span>";
	        	}
	        }},
	        { display: '订单状态', name: 'saleNum',width:60,isSort: false,render: function (rowdata, rowindex, value){
                if(rowdata['auctionNum']>0){
                    if(rowdata['bidLogId']>0){
                        return "<br/><span class='label label-gray' style='margin-top:15px;'>已下单</span>";
                    }else{
                        return "<br/><span class='label label-warning' style='margin-top:15px;'>未下单</span>";
                    }
                }
	        }},
	        { display: '操作', name: 'op',isSort: false,render: function (rowdata, rowindex, value){
	            var h = "";
	            h += "<div class='valign-m'><a target='_blank' href='"+WST.AU("auction://goods/detail","id="+rowdata['auctionId']+"&key="+rowdata['verfiycode'])+"'>查看</a> ";
	            if(rowdata['status']==1 && rowdata['auctionNum']==0){
	                if(WST.GRANT.AUCTION_PMHD_04)h += "<a href='javascript:illegal(" + rowdata['auctionId'] + ",1)'>下架</a> ";
	            }
	            if(WST.GRANT.AUCTION_PMHD_03){
	            	if(rowdata['auctionNum']>0){
	            		if(rowdata['bidLogId']>0)h += "<a href='javascript:del(" + rowdata['auctionId'] + ",0)'>删除</a></div> "; 
	            	}else{
                        h += "<a href='javascript:del(" + rowdata['auctionId'] + ",0)'>删除</a></div> "; 
	            	}
	            }
	            return h;
	        }}
        ]
    });
}
function loadGrid1(){
	var params = {};
	params.shopName = $('#shopName1').val();
	params.goodsName = $('#goodsName1').val();
	params.areaIdPath = WST.ITGetAllAreaVals('areaId1','j-areas').join('_');
	params.goodsCatIdPath = WST.ITGetAllGoodsCatVals('cat1_0','pgoodsCats').join('_');
	grid1.set('url',WST.AU('auction://goods/pageQueryByAdmin',params));
}
function loadGrid2(){
	var params = {};
	params.shopName = $('#shopName2').val();
	params.goodsName = $('#goodsName2').val();
	params.areaIdPath = WST.ITGetAllAreaVals('areaId2','j-areas').join('_');
	params.goodsCatIdPath = WST.ITGetAllGoodsCatVals('cat2_0','pgoodsCats').join('_');
	grid2.set('url',WST.AU('auction://goods/pageAuditQueryByAdmin',params));
}

function del(id,type){
	var box = WST.confirm({content:"您确定要删除该拍卖商品吗?<br/>若有参与者则退回参与者保证金!",yes:function(){
	           var loading = WST.msg('正在提交请求，请稍后...', {icon: 16,time:60000});
	           $.post(WST.AU('auction://goods/delByAdmin'),{id:id},function(data,textStatus){
	           			layer.close(loading);
	           			var json = WST.toAdminJson(data);
	           			if(json.status=='1'){
	           			    WST.msg(json.msg,{icon:1});
	           			    layer.close(box);
	           			    if(type==0){
	           		            grid1.reload();
	           			    }else{
	           			    	grid2.reload();
	           			    }
	           			}else{
	           			    WST.msg(json.msg,{icon:2});
	           			}
	           		});
	            }});
}
function illegal(id,type){
	var w = WST.open({type: 1,title:((type==1)?"下架原因":"不通过原因"),shade: [0.6, '#000'],border: [0],
	    content: '<textarea id="illegalRemarks" rows="7" style="width:96%" maxLength="200"></textarea>',
	    area: ['500px', '260px'],btn: ['确定', '关闭窗口'],
        yes: function(index, layero){
        	var illegalRemarks = $.trim($('#illegalRemarks').val());
        	if(illegalRemarks==''){
        		WST.msg('请输入原因 !', {icon: 5});
        		return;
        	}
        	var ll = WST.msg('数据处理中，请稍候...',{time:6000000});
		    $.post(WST.AU('auction://goods/illegal'),{id:id,illegalRemarks:illegalRemarks},function(data){
		    	layer.close(w);
		    	layer.close(ll);
		    	var json = WST.toAdminJson(data);
				if(json.status>0){
					WST.msg(json.msg, {icon: 1});
					if(type==1){
                        grid1.reload();
					}else{
						grid2.reload();
					}
				}else{
					WST.msg(json.msg, {icon: 2});
				}
		   });
        }
	});
}

function initGrid2(){
	grid2 = $("#maingrid2").ligerGrid({
		url:WST.AU('auction://goods/pageAuditQueryByAdmin'),
		pageSize:WST.pageSize,
		pageSizeOptions:WST.pageSizeOptions,
		height:'99%',
        width:'100%',
        rowHeight:65,
        minColToggle:6,
        rownumbers:true,
        columns: [
	        { display: '&nbsp;', name: 'goodsName',width:60,align:'left',heightAlign:'left',isSort: false,render: function (rowdata, rowindex, value){
            	return "<img style='height:60px;width:60px;' src='"+WST.conf.ROOT+"/"+rowdata['goodsImg']+"'>";
            }},
	        { display: '商品名称', name: 'goodsName',isSort: false,render: function (rowdata, rowindex, value){
	        	return "<div class='valign-m'>"+rowdata['goodsName']+"</div>";
	        }},
	        { display: '所属店铺', name: 'shopName',isSort: false,render: function (rowdata, rowindex, value){
	        	return "<div class='valign-m'>"+rowdata['shopName']+"</div>";
	        }},
	        { display: '起拍价', name: 'auctionPrice',width:70,isSort: false,render: function (rowdata, rowindex, value){
	        	return "<div class='valign-m'>"+rowdata['auctionPrice']+"</div>";
	        }},
	        { display: '保证金', name: 'cautionMoney',width:50,isSort: false,render: function (rowdata, rowindex, value){
	        	return "<div class='valign-m'>"+rowdata['cautionMoney']+"</div>";
	        }},
	        { display: '加价幅度', name: 'fareInc',width:50,isSort: false,render: function (rowdata, rowindex, value){
	        	return "<div class='valign-m'>"+rowdata['fareInc']+"</div>";
	        }},
	        { display: '参与人数', name: 'auctionNum',width:50,isSort: false,render: function (rowdata, rowindex, value){
	        	return "<div class='valign-m'><a href='javascript:logs(" + rowdata['auctionId'] + ")'>"+rowdata['auctionNum']+"</a></div>";
	        }},
	        { display: '拍卖时间', name: 'startTime',isSort: false,render: function (rowdata, rowindex, value){
	        	return "<div class='time'>"+rowdata['startTime']+"<br/>至<br/>"+rowdata['endTime']+"</div>";
	        }},
	        { display: '状态', name: 'saleNum',width:60,isSort: false,render: function (rowdata, rowindex, value){
	        	if(rowdata['status']==1){
                    return "<br/><span class='label label-success'>进行中</span>";
	        	}else if(rowdata['status']==0){
                    return "<br/><span class='label label-info'>未开始</span>";
	        	}else{
                    return "<br/><span class='label label-gray'>已结束</span>";
	        	}
	        }},
	        { display: '操作', name: 'op',isSort: false,render: function (rowdata, rowindex, value){
	            var h = "";
	            h += "<div class='valign-m'><a target='_blank' href='"+WST.AU("auction://goods/detail","id="+rowdata['auctionId']+"&key="+rowdata['verfiycode'])+"'>查看</a> ";
	            if(WST.GRANT.AUCTION_PMHD_04){
	            	h += "<a href='javascript:allow(" + rowdata['auctionId'] + ")'>通过</a> ";
	            	h += "<a href='javascript:illegal(" + rowdata['auctionId'] + ",1)'>不通过</a> ";
	            }
	            if(WST.GRANT.AUCTION_PMHD_03)h += "<a href='javascript:del(" + rowdata['auctionId'] + ",1)'>删除</a></div> "; 
	            return h;
	        }}
        ]
    });
}

function allow(id,type){
	var box = WST.confirm({content:"您确定审核通过该拍卖商品吗?",yes:function(){
        var loading = WST.msg('正在提交请求，请稍后...', {icon: 16,time:60000});
        $.post(WST.AU('auction://goods/allow'),{id:id},function(data,textStatus){
        			layer.close(loading);
        			var json = WST.toAdminJson(data);
        			if(json.status=='1'){
        			    WST.msg(json.msg,{icon:1});
        			    layer.close(box);
        		        grid2.reload();
        		        grid1.reload();
        		    }else{
        			    WST.msg(json.msg,{icon:2});
        			}
        		});
         }});
}

function logs(id){
	parent.showBox({type:2,title:'竞拍记录',area: ['800px', '450px'],content:WST.AU('auction://goods/auctionLogByAdmin','id='+id+"&rd="+Math.random())});
}