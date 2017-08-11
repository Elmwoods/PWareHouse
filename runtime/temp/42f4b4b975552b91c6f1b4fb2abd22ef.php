<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:62:"F:\wamp\www\jingo/wstmart/purse\view\default\consume_list.html";i:1501121608;s:62:"F:\wamp\www\jingo/wstmart/purse\view\default\purse\header.html";i:1500261424;s:62:"F:\wamp\www\jingo/wstmart/purse\view\default\purse\bottom.html";i:1494837028;}*/ ?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <title>钱袋子</title>
    <meta http-equiv="Content-Language" Content="zh-CN">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="__STYLE__/css/bootstrap.min.css">
    <link rel="stylesheet" href="__STYLE__/css/bank.css">
    <link rel="stylesheet" href="__STYLE__/css/reset.css">
    <link rel="stylesheet" href="__STYLE__/css/bill.css">
    <link rel="stylesheet" href="__STYLE__/css/jyxq.css">
    <link rel="stylesheet" type="text/css" href="__STYLE__/css/bootstrap-theme.min.css">
    <link rel="stylesheet" type="text/css" href="__STYLE__/css/index (2).css">



    <link href="__STYLE__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet">
    <script type="text/javascript" src="__ROOT__/static/js/jquery.min.js?v=<?php echo $v; ?>"></script>
    <script type="text/javascript" src="__ROOT__/static/plugins/layer/layer.js?v=<?php echo $v; ?>"></script>
    <script type="text/javascript" src="__ROOT__/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>
    <script type='text/javascript' src='__ROOT__/static/js/common.js?v=<?php echo $v; ?>'></script>
    <script type='text/javascript' src='__STYLE__/js/common.js?v=<?php echo $v; ?>'></script>


    <script type="text/javascript" src="__STYLE__/js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="__STYLE__/js/bootstrap.min.js"></script>

</head>
<body style="background: #f5f5f5">
	<div class="top">
    <div class="top_main">
        <div class="top_list">
            <ul>
                <li><a href="javascript:WST.logout();">退出</a>|</li>
                <li><a class="top_right" href="<?php echo url('loan/news'); ?>"><img src="__STYLE__/img/xinxi.png"><?php if(session('NEWS_COUNT') != 0): ?><span class="news_count"></span><span class="aa"><?php echo session('NEWS_COUNT'); ?></span><?php endif; ?></a></li>
                

            </ul>
        </div>
    </div>
</div>
<div class="logo public">
    <div class="logo_main">
        <a href="<?php echo url('home/index/index'); ?>"><img src="__STYLE__/img/VVpaylogo.png" alt=""></a>
    </div>
</div>
<div class="header">
    <div class="header_main">
        <dl>
            <dt>
            <?php if(session('WST_USER.userPhoto')): ?>
                <div class="hot"><img src="__ROOT__/<?php echo session('WST_USER.userPhoto'); ?>" style="border-radius: 50%"></div>
            <?php else: ?>
                <div class="hot"><img src="__ROOT__/upload/sysconfigs/2016-10/5804800d5841e.png" style="border-radius: 50%"></div>  
            <?php endif; ?>
            </dt>
            <dd>
                <div class="max">
                    <div class="top_left">
                        你好 ,<span id="top_left"><?php echo session('WST_USER.userName'); ?></span>
                    </div>
                    <div class="top_right">
                        账号:<?php echo session('WST_USER.userPhone'); ?>
                    </div>
                    <div class="top_vv" >VVID：<!--<a data-toggle="modal" data-target="#vvid">--><?php echo session('WST_USER.loginName'); ?><!--</a>-->
                        <a href="<?php echo url('home/users/edit'); ?>"><img src="__STYLE__/img/personal.png" alt=""></a></div>
                </div>
                <div class="button">
                    <div class="button_left">

                        信用分：<?php echo session('WST_USER.userScore'); ?>
                    </div>
                    <div class="button_right">
                        上次登录时间：<?php echo session('WST_USER.lastTime'); ?>
                    </div>
                </div>
            </dd>
        </dl>
    </div>
</div>
    <!--<div id="vvid" class="modal fade">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form class="form-horizontal" action="" method="post">
                    &lt;!&ndash; <div class="tk"> &ndash;&gt;
                    <div class="modal-header">
                        <div class="close" data-dismiss="modal">&times;</div>
                        <h4>设置VVID</h4>

                        <div class="mrid">默认ID：<?php echo session('WST_USER.loginName'); ?></div>
                    </div>
                    <div class="modal-body">
                        <div class="message">
                                <div class="hk">新ID：<input type="text" name="" id="vv" value="" placeholder="请输入VVID"><img src="__STYLE__/img/vvid1.png" alt=""></div>
                        </div>
                        <div class="ed">此账号以注册过</div>
                        <div style="height:100px;"></div>
                     </div>
                    <div class="modal-footer ">
                        <div class="col-md-7 col-md-offset-3">
                            <button type="submit" id="subm">设置</button>
                            <div class="ts"><img src="__STYLE__/img/vvid2.png" alt="">VVID只能设置一次，修改后不能更改</div>
                        </div>
                    </div>
                    &lt;!&ndash; </div> &ndash;&gt;
                </form>
            </div>
        </div>
    </div>-->

    <script type="text/javascript">
       window.conf = {"ROOT":"__ROOT__","APP":"__APP__","STATIC":"__STATIC__","SUFFIX":"<?php echo config('url_html_suffix'); ?>","SMS_VERFY":"<?php echo WSTConf('CONF.smsVerfy'); ?>","SMS_OPEN":"<?php echo WSTConf('CONF.smsOpen'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>","IS_LOGIN":"<?php if((int)session('WST_USER.userId')>0): ?>1<?php else: ?>0<?php endif; ?>","TIME_TASK":"1","ROUTES":'<?php echo WSTRoute(); ?>'}
$(function() {
	WST.initVisitor();
});

    $(function(){
        var li = $('.nav_right ul li a');
        li.each(function(){
            if($($(this))[0].href==String(window.location)){
                $(this).css({border:'1px solid #E71D56',color:'#E71D56'});
            }

            //鼠标放在充值提现收付款标签上的效果
           
            $(this).hover(
                function(){
                     $(this).css({background:'#e60b49',color:"#fff",border:'1px #e60b49 solid'});

                },
                function(){
                        if($($(this))[0].href==String(window.location)){
                            $(this).css({background:'',color:""}); 
                        }else{
                             $(this).css({background:'',color:"",border:''}); 
                        }
                    
                }
            );
           
        });




    });
    



       

      
    </script>

    <script type="text/javascript">
        $('.hk').find('img').click(function(){
            $('#vv').val('');
        });
    //ajax 刷新信息条数
    $(function(){
        function reset(){
            a =  $('#top_left').html();
            javascript:WST.news_count(a);
        }
        setInterval(reset,1800000);
    });

    </script>
<script type="text/javascript" src="__ROOT__/static/js/laydate-master/laydate.js"></script>
<div class="nav">
<div class="center public">
    <div class="nav_main ">
        <div class="nav_left">
            <ul>
              

                <li><a href="<?php echo url('consume/index'); ?>">我的钱袋</a>|</li>
                <li><a href="<?php echo url('imazamox/index'); ?>">我的金豆</a>|</li>
                <li class="red"><a href="<?php echo url('consume/bill'); ?>">我的账单</a>|</li>
                <li><a href="<?php echo url('loan/index'); ?>">我的贷款</a>|</li>
                <li><a href="<?php echo url('bank/index'); ?>">我的银行卡</a></li>
            </ul>
        </div>
        <script type="text/javascript">

           
        </script>
        <div class="nav_right">
            <ul>
                <li>
                   <a href="<?php echo url('predeposit/recharge'); ?>">充&nbsp;值</a>
                </li>
                <li>
                    <a href="<?php echo url('predeposit/withdrawals'); ?>">提&nbsp;现</a>
                </li>
                <li>
                    <a href="<?php echo url('predeposit/transfer_purse'); ?>">收付款</a>
                </li>
            </ul>

        </div>
    </div>
</div>
</div>
<div class="menu">
  <div class="center public">
    <div class="menu_main">

      <div class="menu_time">
        <div class="menu_left">
          <ul id="month">

              <li><a href="javascript:goSort('month',0)"  class="qb" id="qb_">全部</a></li>
              <li class="string">|</li>
              <li><a href="<?php echo url('consume/bill',array('month'=>1)); ?>" class='str'>近一个月</a></li>
              <li class="string">|</li>
              <li><a href="<?php echo url('consume/bill',array('month'=>2)); ?>" class='str_'>近三个月</a></li>
          </ul>
        </div>
        <div class="cx">
          <form class="form-inline" action="<?php echo url('Consume/bill'); ?>" method="" style="float:right;">
            <div class="form-group">
              <label for="exampleInputName2">日期:&nbsp;&nbsp;&nbsp;</label>


                <input type="text" name='st' id="J-xl">
            </div>
            <div class="form-group">
              <label for="exampleInputEmail2">－</label>


                <input type="text" name='et' id="J-xl2">
            </div>
            <div class="form-group">
              <button id="but" class="form-control">查询</button></div>
          </form>
        </div>
      </div>
      <script type="text/javascript">
        $('#but').hover(
                function(){
                  $(this).css({background:'#e60b49',color:"#fff"});
                },
                function(){
                  $(this).css({background:'',color:""});
                }
        );
      </script>
        <div class="menu_zt">
            <div class="menu_zt1">

                <ul id="type">
                    <li>类型：</li>
                    <li ><a class="qb" href="javascript:goSort('type',0)">全部</a></li>
                    <li><a href="javascript:goSort('type',1)">充值</a></li>
                    <li><a href="javascript:goSort('type',2)">提现</a></li>
                    <li><a href="javascript:goSort('type',3)">转账</a></li>
                    <li><a href="javascript:goSort('type',4)">消费</a></li>
                    <li><a href="javascript:goSort('type',5)">贷款</a></li>
                    <li><a href="javascript:goSort('type',6)">信用卡还款</a></li>
                    <li><a href="javascript:goSort('type',7)">撒金豆</a></li>
                </ul>
            </div>
            <div class="menu_zt2">
                <ul id="status">
                    <li>状态：</li>
                    <li ><a class="qb" href="javascript:goSort('status',0)">全部</a></li>
                    <li><a href="javascript:goSort('status',1)">进行中</a></li>
                    <li><a href="javascript:goSort('status',2)">成功</a></li>
                    <li><a href="javascript:goSort('status',3)">失败</a></li>
                </ul>
            </div>
        </div>
      <div class="menu">
        <div class="menu">
          <div class="menu_main">
            <table class="menu_table">

              <tr class="list">
                <th>创建时间</th>
                <th>交易方式</th>
                <th>类型</th>
                <th>金额/数量</th>
                <th>交易状态</th>
                <th>操作</th>
              </tr>
              <?php if(is_array($log) || $log instanceof \think\Collection): if( count($log)==0 ) : echo "" ;else: foreach($log as $key=>$vo): ?>
              <tr class="center">
                <td>
                  <p><?php echo $vo['createTime']; ?></p>
                </td>
                <td>
                  <p><?php echo $vo['dataSrc']; ?></p>
                  <p>流水号：<?php echo $vo['tradeNo']; ?></p>
                </td>
                <td><?php echo $vo['dataSrc']; ?></td>
                <td class="money"><?php echo !empty($vo['moneyType'])?'+':'-'; ?><?php echo $vo['money']; ?></td>
                <td><?php switch($vo['dataFlag']): case "1": ?>成功<?php break; case "2": ?>进行中<?php break; default: ?>失败
                    <?php endswitch; ?></td>
                <td>
                  <ul class="menu_select">
                    <li class="menu_block" onclick="javascript:WST.Details(<?php echo $vo['id']; ?>)" data-toggle="modal" data-target="#details"><a href="javascript:void(0)" >&nbsp;交易详情</a><span class="menu_block_img" ></span></li>
                      <li><a href="javascript:WST.del_id(<?php echo $vo['id']; ?>)">删除</a></li>
                  </ul>
                </td>
              </tr>
              <?php endforeach; endif; else: echo "" ;endif; ?>

            </table>
            

              <?php echo $page; ?>
            <div class="menu_border"></div>
            <div class="a">
              <a href="">查看所有交易记录</a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<div id="details" class="modal fade">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <form class="form-horizontal" action="" method="post">
                    <!-- <div class="tk"> -->
                        <div class="modal-header" style="border:none;">
                            <div class="close" data-dismiss="modal">&times;</div>
                            <h3 style="text-align:left;" id="aa">交易<span id='dataFlag'></span></h3>
                            <!-- <div class="jk">
                                <span class="glyphicon glyphicon-yen"></span><input type="text"  placeholder="请输入借款金额">
                            </div> -->
                        </div>
                        <div class="modal-body">
                          <div class="consumption_bill">


                            <table style="margin:0;padding: 0;">
                              <tr class="top">
                                <th class="cen">类型</th>
                                <th class="name">消费名称</th>
                                <th class="cen">数量/金额</th>
                                <th class="cen">总额</th>
                              </tr>
                              <tr class="record">
                                <th class="cen" id='dataSrc'>消费</th>
                                <th class="name">
                                  
                                  <p>流水账号：<span id="tradeNo"></span></p>
                                </th>
                                <th class="cen"><span id='moneyType'></span><span id='money'></span></th>
                                <th class="cen"><span id='moneyType_z'></span><span id='money_z'></span></th>
                              </tr>
                            </table>
                          </div>
                         </div>


                         <div class="consumption_details">
                           <table>
                             <tr>
                                <th class="th">交易描述</th>
                                <td colspan="3" style="text-align: left;padding-left: 20px;" id='remark'></td>
                                
                            </tr>
                            <tr>
                              <th class="th">交易信息</th>
                              <td colspan="3" style="text-align: left;padding-left: 20px;">
                                <span id='dataSrc_z'></span>&nbsp;&nbsp;&nbsp;对方账户:<span id='userName'>&nbsp;</span>
                              </td>
                            </tr>
                            <tr>
                              <th class="th" style="width:80px;">时间报告</th>
                              <td colspan="3">
                              <table style="border:0;">
                                <tr>
                                  <th class="time">创建时间</th>
                                  <th class="time">结束时间</th>
                                </tr>
                                <tr>
                                  <td class="time1" id='createTime'></td>
                                  <td class="time1" id='createTime_z'></td>
                                </tr>
                                </table>
                              </td>
                              
                            </tr>
                           </table>
                         </div>  
                     </form>
                    </div>
                </div>
            </div>

<div class="bottom">
    <div class="bottom_main">
        <dl>
            <dt class="bottom_dt"><a href="<?php echo url('home/index/index'); ?>">金购商城</a> &nbsp; | &nbsp; <a href="javascript:void(0)">VV聊天</a> &nbsp; | &nbsp;<a href="<?php echo url('consume/index'); ?>">钱袋子</a>
</dt>
            <dd class="bottom_dd">
浙公网安备 33010402002972 号 &nbsp; 浙ICP备 17002020号
</dd>
        </dl>
        <p>客服热线：400-167-5655</p>
    </div>
</div>

<script type="text/javascript">

    $('.click').each(function(){
        $('.click').click(function(){
            $(this).css('background','#f5f5f5').siblings().css('background','');
        });
    });



    $('.menu_block').each(function(){
        $(this).mouseover(function(){
            $(this).siblings('li').css('display','block');
            $(this).find('span').removeClass('menu_block_img').parent().parent().css({border:'1px #ccc solid','border-radius':'5px','color':'#fff'});
        });

    });

    //鼠标放上去出现背景颜色
    $(".menu_select").find('li').mouseover(function(){
        $(this).css('background','#ccc');
    }).mouseout(function(){
        $(this).css('background','');
    });


    // 鼠标移出ul
    $('.menu_select').mouseleave(function(){
        $('.menu_block').find('span').addClass('menu_block_img').parent().parent().css({background:'white'});
        $(this).css('border',"").find('li').first().css('display','block');
        $(this).find('li').eq(1).css('display','none');
        $(this).find('li').eq(2).css('display','none');
    });

    $(function(){
        $('#but').hover(
                function(){
                    $(this).css({background:'#e60b49',color:"#fff"});
                },
                function(){
                    $(this).css({background:'',color:""});
                }
        );

        $('.menu_left').find('a').hover(
                function(){
                    $('.menu_left').find('.hover').removeClass();
                    $(this).css({'background':' #EAEDF4','border-radius':'2px'});
                },
                function(){
                    $(this).parent().find('a').css({'background':''});
                }
        );

        $('.menu_zt').find('a').hover(
                function(){
                    $('.menu_zt').find('.hover').removeClass();
                    $(this).css({'background':' #EAEDF4','border-radius':'2px'});
                },
                function(){
                    $(this).parent().find('a').css({'background':''});
                }
        );
    });
  //查询
  function getQueryString(){

      var result = location.search.match(new RegExp("[\?\&][^\?\&]+=[^\?\&]+","g"));

      if(result == null){
          return "";
      }
      for(var i = 0; i < result.length; i++){
          result[i] = result[i].substring(1);
      }
      return result;
  }

  function goSort(name,value){
      var string_array = getQueryString();

      var oldUrl = (document.URL.indexOf("screen.php")==-1)?document.URL+"":document.URL;

      var newUrl;
      if(string_array.length>0){
          var repeatField = false;
          for(var i=0;i<string_array.length;i++){
              if(!(string_array[i].indexOf(name)==-1)){
                  //如果有重复筛选条件，替换条件值
                  repeatField = true;
                  newUrl = oldUrl.replace(string_array[i],name+"="+value);
              }
          }
          //如果没有重复的筛选字段
          if(repeatField == false){
              newUrl = oldUrl+"&"+name+"="+value;
          }
      }else{
          //如果还没有筛选条件
          newUrl = oldUrl+"?"+name+"="+value;
      }
      //跳转
      window.location = newUrl;
  }


  function setSelected(name,value){
      // var all_li = $("#"+name).find("li");
      var all_li = $("#"+name).find("a");
      console.log(value);

      // //清除所有li标签的selected类
      all_li.each(function(){
          $(this).removeClass("qb");
      });

      // //为选中的li增加selected类
      all_li.eq(value).addClass("qb");

  }
  $(document).ready(function(){
      var string_array = getQueryString();
      for(var i=0;i<string_array.length;i++){
          var tempArr = string_array[i].split("=");

          setSelected(tempArr[0],tempArr[1]);//设置选中的筛选条件
      }
  });

  var start = {
      elem: '#J-xl',
      format: 'YYYY-MM-DD',
      max: laydate.now(), //最大日期
      istime: true,
      istoday: false,
      choose: function(datas){
          end.min = datas; //开始日选好后，重置结束日的最小日期
          end.start = datas //将结束日的初始值设定为开始日
      }
  };
  var end = {
      elem: '#J-xl2',
      format: 'YYYY-MM-DD',
      max: laydate.now(),
      istime: true,
      istoday: false,
      choose: function(datas){
          start.max = datas; //结束日选好后，重置开始日的最大日期
      }
  };
  laydate(start);
  laydate(end);
</script>
<script>
    WST.Details = function(id){
        $.post(WST.U('purse/Consume/Details'),{id:id},function(data){
            // alert(data['dataFlag']);
            $("#dataSrc").html(data['dataSrc']);
            $('#tradeNo').html(data['tradeNo']);
            var flag = data['dataFlag'];
            // alert(flag);
            if(flag==1){
                $('#dataFlag').html('成功');
            }else if(flag==0){
                $('#dataFlag').html('失败');
            }else if(flag==2){
                $('#dataFlag').html('进行中');
            }
            $('#createTime').html(data['createTime']);
            $('#createTime_z').html(data['endTime']);

            var mt = data['moneyType'];
            if(mt==1){
                $('#moneyType').html('+');
            }else{
                $('#moneyType').html('-');
            }
            $('#money').html(data['money']);

            if(mt==1){
                $('#moneyType_z').html('+');
            }else{
                $('#moneyType_z').html('-');
            }
            $('#money_z').html(data['money']);
            $('#remark').html(data['remark']);
            $('#dataSrc_z').html(data['dataSrc']);

            if(data['payName']==null){
                $('#userName').html('  金购中国  ');
            }else if(data['payName'] !=null){
                $('#userName').html(data['payName']);
            }
        });
    }
</script>
<script>
    $.getUrlParam = function (name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]); return null;
    }
    var st = $.getUrlParam('st');
    var et = $.getUrlParam('et');
    if(st||et){
        $('.str').attr('href',"<?php echo url('consume/bill',array('month'=>1)); ?>");
        $('.str_').attr('href',"<?php echo url('consume/bill',array('month'=>2)); ?>");
        $('#qb_').attr('href',"<?php echo url('consume/bill'); ?>");
    }else{
        $('.str').attr('href',"javascript:goSort('month',1)");
        $('.str_').attr('href',"javascript:goSort('month',2)");
        $('#qb_').attr('href',"javascript:goSort('month',0)");
    }
</script>
</body>
</html>