<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:55:"F:\wamp\www\jingo/wstmart/purse\view\default\purse.html";i:1501221342;s:62:"F:\wamp\www\jingo/wstmart/purse\view\default\purse\header.html";i:1500261424;s:62:"F:\wamp\www\jingo/wstmart/purse\view\default\purse\bottom.html";i:1494837028;}*/ ?>
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
<div class="nav">
<div class="center public">
    <div class="nav_main ">
        <div class="nav_left">
            <ul>
              

                <li class="red"><a href="<?php echo url('consume/index'); ?>">我的钱袋</a>|</li>
                <li><a href="<?php echo url('imazamox/index'); ?>">我的金豆</a>|</li>
                <li><a href="<?php echo url('consume/bill'); ?>">我的账单</a>|</li>
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
<div class="main">
  <div class="center public">
    <div class="main_main">
      <div class="main_left">
        可用余额：<span><?php echo $users['userMoney']; ?></span>元
      </div>
      <div class="main_right">
        冻结余额：<span><?php echo $users['lockMoney']; ?></span>元
      </div>
    </div>
  </div>
</div>
<div class="menu">
  <div class="center public">
    <div class="menu_main">
      <div class="menu_top">
        <span>最近交易记录</span>
      </div>
      <table class="menu_table">

        <tr class="list">
          <th>创建时间</th>
          <th>交易信息</th>
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
          <td><?php switch($vo['dataFlag']): case "1": ?>成功<?php break; case "2": ?>进行中<?php break; case "3": ?>退款<?php break; default: ?>失败
              <?php endswitch; ?></td>
          <td>
            <ul class="menu_select">
              <li class="menu_block" data-toggle="modal" onclick="javascript:WST.Details(<?php echo $vo['id']; ?>)" data-target="#details"><a href="javascript:void(0)"  >&nbsp;交易详情</a><span class="menu_block_img"></span></li>
              <li><a href="javascript:WST.del_qd(<?php echo $vo['id']; ?>)">删除</a></li>
            </ul>
             <!-- 交易详情 -->
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

            <!-- 交易详情 -->
          </td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>

          <tr class="sy">
              <td colspan="6" style="text-align:center;font-size:15px;padding-top:20px;"><a href="<?php echo url('consume/bill'); ?>">查看所有记录</a></td>
          </tr>
      </table>
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

</body>
</html>