<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:54:"F:\wamp\www\jingo/wstmart/purse\view\default\loan.html";i:1500095820;s:62:"F:\wamp\www\jingo/wstmart/purse\view\default\purse\header.html";i:1500261424;s:62:"F:\wamp\www\jingo/wstmart/purse\view\default\purse\bottom.html";i:1494837028;}*/ ?>
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

<script type="text/javascript" src="__STATIC__/plugins/validator/jquery.validator.js?v=<?php echo $v; ?>"></script>
<link href="__STATIC__/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">
<div class="nav">
<div class="center public">
    <div class="nav_main ">
            <div class="nav_left">
            <ul>
                <li><a href="<?php echo url('consume/index'); ?>">我的钱袋</a>|</li>
                <li><a href="<?php echo url('imazamox/index'); ?>">我的金豆</a>|</li>
                <li><a href="<?php echo url('consume/bill'); ?>">我的账单</a>|</li>
                <li class="red"><a href="<?php echo url('loan/index'); ?>">我的贷款</a>|</li>
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

</div>
<?php if($users['userScore'] >= 1000): ?>
<div class="center" style="background: #fff;">

<div class="loan">



      <div id="mymodal" class="modal fade">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                    <form class="form-horizontal-loan" action="" method="post">
                    <!-- <div class="tk"> -->
                        <div class="modal-header">
                            <div class="close" data-dismiss="modal">&times;</div>
                            <h4>借款</h4>
                            <div class="jk">
                                <span class="glyphicon glyphicon-yen"></span>&nbsp;<input name="loan_amount" type="text"  placeholder="请输入借款金额" data-rule="收款金额: required;" data-msg-required="请输入收款金额" data-tip="请输入收款金额">
                            <span role="alert" class="msg-wrap n-error" style="margin-left:5px;margin-top:7px;">
                                <span class="n-icon" style="display:none" id='yu_s'></span>
                                <span class="n-msg" style="display:none" id='yu_j'></span>
                            </span>
                            </div>
                        </div>
                        <div class="modal-body">


                            <div class="ed">
                                    <dl>
                                        <dt>可用额度&nbsp;:<span>&nbsp;&nbsp;<span id="money_jie"><?php echo !empty($users['residual_repayment'])?10000-$users['residual_repayment']:'10000'; ?></span></span></dt>
                                        <!--<dt>还款日&nbsp;&nbsp;&nbsp;&nbsp;:<span>&nbsp;&nbsp;<?php echo $users['repayment_date']; ?></span></dt>-->
                                    </dl>
                            </div>


                            
                        </div>
                        <div class="modal-footer">
                            <div class="col-md-4 col-md-offset-3">
                                <button type="submit" class="borrow" id="sub">借贷</button>
                            </div>
                        </div>
                     </form>
                    </div>
                </div>
            </div>


    <?php if($users['residual_repayment'] == 0): ?>
    <div style="width:600px;height:300px;margin:0 auto;">
    <div class="loan_left">
        <dl class="loan_left_top">
            <dt>当前信用积分<span></span>,可用额度</dt>
        </dl> 
        <dl class="loan_left_center">
            <h1><?php echo !empty($users['residual_repayment'])?10000-$users['residual_repayment']:'10000'; ?></h1>
            <a class="dh" href="#" data-toggle="modal" data-target="#mymodal" id="dhd">去借款</a>
        </dl>
        <dl class="loan_left_bottom">
            <dt><span>总额度：</span><span><?php if($users['userScore'] >1000): ?>10000<?php else: ?>你的额度不足<?php endif; ?></span><span>　利率</span><span>0.82%</span></dt>
            <dt class="dt"><a href="<?php echo url('loan/loanNotes'); ?>" target="_blank">贷款须知</a></dt>
        </dl>
    </div>
    </div>
<?php else: ?>
      <div class="loan_right">
        <dl class="loan_right_top">
            <dt>本期账单</dt>
        </dl> 
        <dl class="loan_right_center">
            <h1><?php echo $users['residual_repayment']; ?></h1>
            <a class="dh" href="#" data-toggle="modal" data-target="#daikuang">去还款</a>
        </dl>
        <dl class="loan_right_bottom">
            <dt><span>还款日：</span><span><?php echo $users['repayment_date']; ?></span><a href="<?php echo url('loan/loanNotes'); ?>" target="_blank" class="dt">贷款须知</a></dt>
        </dl>
    </div>
    <?php endif; ?>

     <div id="daikuang" class="modal fade">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                    <form class="form-horizontal" action="" method="post">
                    <!-- <div class="tk"> -->
                        <div class="modal-header">
                            <div class="close" data-dismiss="modal">&times;</div>
                            <h4>还款</h4>
                        </div>
                        <div class="modal-body">
                            <div class="message">
                                <dl class="dl">
                                    <div class="hk">
                                        <?php if(empty($news)): ?>
                                        <span class="glyphicon glyphicon-yen"></span>&nbsp;<input name="repayment_amount" type="text"  placeholder="请输入还款金额" data-rule="还款金额: required;" data-msg-required="请输入还款金额" data-tip="请输入还款金额">

                                        <?php else: if($news['dataFlag'] === 0): ?>
                                        <span class="glyphicon glyphicon-yen"></span>&nbsp;<input name="repayment_amount" type="text"  placeholder="请输入还款金额" value="<?php echo $users['residual_repayment']; ?>"  readonly="readonly">
                                        <input type="hidden" name="loan" value="<?php echo $news['id']; ?>">
                                        <?php else: ?>
                                        <span class="glyphicon glyphicon-yen"></span>&nbsp;<input name="repayment_amount" type="text"  placeholder="请输入还款金额" data-rule="还款金额: required;" data-msg-required="请输入还款金额" data-tip="请输入还款金额">

                                        <?php endif; endif; ?>
                                    </div>
                                     <span role="alert" class="msg-wrap n-error" style="margin-left:5px;margin-top:7px;">
                                            <span class="n-icon" style="display:none" id='yu_h'></span>
                                            <span class="n-msg" id='yu_k'></span>
                                     </span>

                                </dl>
                            </div>


                            <div class="mode">

                                <div class="repayment_mode">
                                    <span>还款方式</span>
                                    <a href="#" onclick="hk()" id="q" style="text-decoration:none">钱袋子</a><input id="fs" name="" type="hidden" value="wallets"/>&nbsp;<img src="__STYLE__/img/user_icon_sider_zhankai.png">
                                </div>
                                <div class="kxx" style="display: none">
                                    <dl>
                                        <dt>
                                            <label>&nbsp;&nbsp;<span>支付宝</span><input type="hidden" name="payCode" value="alipays"  readonly="readonly"> <em class="em"> </em></label>


                                        </dt>
                                    </dl>
                                    <dl>
                                        <dt>
                                            <label>&nbsp;&nbsp;<span>钱袋子</span><input type="hidden" name="payCode" value="wallets"  readonly="readonly"> <em class="em"> </em></label>


                                        </dt>
                                    </dl>
                                </div>
                            </div>
                            <div class="ed">
                                <dl>

                                    <dt>到期还款日&nbsp;&nbsp;&nbsp;&nbsp;:<span>&nbsp;&nbsp;<?php echo $users['repayment_date']; ?></span></dt>
                                    <dt id="ye">钱袋子余额&nbsp;&nbsp;&nbsp;&nbsp;:<span>&nbsp;&nbsp;￥<?php echo $users['userMoney']; ?></span></dt>
                                </dl>
                            </div>
                            
                            
                        </div>
                        <div class="modal-footer ">
                            <div class="col-md-4 col-md-offset-3">
                                <button type="submit" id="subm">还款</button>
                            </div>
                        </div>
                    <!-- </div> -->
                     </form>
                    </div>
                </div>
            </div>
</div>

</div>

<script type="text/javascript">



    var val = 0;
    function hk(){
        if(val==0){
            $('.kxx').show();
            $('#ye').addClass('ye');
            val=1;
        }else{
            $('.kxx').hide();
            $('#ye').removeClass('ye');
            val=0;
        }
    }

//    $('.kxx').find('dl').each(function(){
        $('.kxx').find('dl').click(function(){
            val = $(this).find('span').text();

            if(val=="钱袋子"){
                $('#ye').show().css('color','black');
            }else{
                $('#ye').hide();
            }
            $('#q').html(val);
            $(this).parent().hide();
            $('#ye').addClass('ye');
            val=0;

            var vv = $(this).find('input').val();
            $(this).parent().prev().find('input').val(vv);





        });
//    });


</script>
<div class="menu">
    <div class="center">
    <div class="menu_main public">


<div class="menu">
    <div class="menu_main">
        <table class="menu_table">
                
           <tr class="list">
                <th>时间</th>
                <th>单号</th>
                <!--<th>支付方式</th>-->
                <th>金额</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            <?php if(is_array($al) || $al instanceof \think\Collection): $i = 0; $__LIST__ = $al;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <tr class="center">
                <td><?php echo $vo['createTime']; ?></td>
                <td><?php echo $vo['tradeNo']; ?></td>
                <td class="money"><?php echo !empty($vo['moneyType'])?'+':'-'; ?><?php echo $vo['money']; ?></td>
                <td><?php switch($vo['dataFlag']): case "1": ?>成功<?php break; case "2": ?>进行中<?php break; default: ?>失败
                    <?php endswitch; ?></td>
                <td>
                    <ul class="menu_select">
                            <li class="menu_block" onclick="javascript:WST.Details(<?php echo $vo['id']; ?>)" data-toggle="modal" data-target="#details"><a href="javascript:void(0)">&nbsp;交易详情</a><span class="menu_block_img" ></span></li>
                        <li><a href="javascript:WST.del_dl(<?php echo $vo['id']; ?>)">删除</a></li>
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

    </div>
    </div>
    <?php else: ?>
    <div style="min-height:500px;background-color: white;width:1200px;margin:0 auto;"><div style="width:1100px;height:500px;border-left:1px #ccc solid;border-right:1px #ccc solid;margin:0 auto;line-height:500px;font-size: 18px;text-align: center;color:#e60b49;"><span>你的贷款分不足，不能贷款</span></div></div>
    <?php endif; ?>
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

      //点击更改让银行卡选项显示
        
    var hd = true;
    $('.gg').each(function(){
        $(this).click(function(){
            
        if(hd){
            $(this).parent().parent().parent().parent().next().css('display','block');
            hd = false;
        }else{
            $(this).parent().parent().parent().parent().next().css('display','none');
            hd = true;
        }
        
      });
    });

    //还贷款效果
    $('.dh').hover(
        function(){
            $(this).css({background:'#e60b49',color:"#fff"});
        },
        function(){
            $(this).css({background:'',color:""});
        }
    );


    $('.table tr').hover(
        function(){
            $(this).css('background','#ccc');
        },
        function(){
            $(this).css('background','');
        }
    );

    i = 0
    $('.bank a').click(function(){
        if(i%2==0){
            $('.table').css('display','block');
            $('.ed').css('display','none');
            $(this).attr('class','glyphicon glyphicon-chevron-up');

        }else{
            $('.table').css('display','');
            $('.ed').css('display','block');
            $(this).attr('class','glyphicon glyphicon-chevron-down');
        }
        i++;
    });


    $(function(){
        a=$('#money_jie').html();
        // alert(a);
        if(a==0){
            $('#dhd').remove();

        }else{
    ;
        }
    })


    $('.table tr').hover(
        function(){
            $(this).css('background','#ccc');
        },
        function(){
            $(this).css('background','');
        }
    );

    i = 0
    $('.bank a').click(function(){
        if(i%2==0){
            $('.table').css('display','block');
            $('.ed').css('display','none');
            $(this).attr('class','glyphicon glyphicon-chevron-up');

        }else{
            $('.table').css('display','');
            $('.ed').css('display','block');
            $(this).attr('class','glyphicon glyphicon-chevron-down');
        }
        i++;
    });


    $("input[name='loan_amount']").focus(function(){
        $('#yu_s').hide();
        $('#yu_j').hide();
    });

    $("input[name='loan_amount']").blur(function(){
        va = $("input[name='loan_amount']").val();
        if(!isNaN(va)){
            if(va<=10000){
                $('#sub').attr("disabled",false);
            } else {
                $('#yu_s').show();
                $('#yu_j').show().html('借款金额超出');
                $('#sub').attr("disabled",true);
            }
//            javascript:WST.With(va);
        } else {
            $('#yu_s').show();
            $('#yu_j').show().html('请输入正确的数字');
            $('#sub').attr("disabled",true);
        }
    });

    $("input[name='repayment_amount']").focus(function(){
        $('#yu_h').hide();
        $('#yu_k').hide();
    });

    $("input[name='repayment_amount']").blur(function(){
        va = $("input[name='repayment_amount']").val();
        if(!isNaN(va)){

            javascript:WST.Loan(va);
        } else {
            $('#yu_h').show();
            $('#yu_k').show().html('请输入正确的数字');
            $('#subm').attr("disabled",true);
        }
    });

    $('.form-horizontal-loan').validator(function(){
        loan_amount = $("input[name='loan_amount']").val();

        var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:10000});
        //WST.confirm({content:'您确定要发送该信息？',yes:function(index){
        $.post(WST.U('Purse/Loan/loan'),{loan_amount:loan_amount},function(data,textStatus){
            // alert(data);
            var json = WST.toJson(data);
            if(json.status==1){
                WST.msg(json.msg,{icon:1});
                location.href=WST.U('purse/Loan/index');
            }else{
                layer.close(loading);
                WST.msg(json.msg, {icon: 5});
            }
        });
//        }});

    });

    $('.form-horizontal').validator(function(){
        repayment_amount = $("input[name='repayment_amount']").val();
        payCode = $("#fs").val();
//        alert(payCode);return;
        if(payCode=="wallets"){
            var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:10000});
            //WST.confirm({content:'您确定要发送该信息？',yes:function(index){
            $.post(WST.U('Purse/Loan/repayment'),{repayment_amount:repayment_amount},function(data,textStatus){
                // alert(data);
                var json = WST.toJson(data);
                if(json.status==1){
                    WST.msg(json.msg,{icon:1});
                    location.href=WST.U('purse/Loan/index');
                }else{
                    layer.close(loading);
                    WST.msg(json.msg, {icon: 5});
                }
            });
//        }});
        }else if(payCode=="alipays"){
//            alert(payCode);
            var params = {};
            params.payObj = "loan";
            params.targetType = 1;
            params.needPay = repayment_amount;
            params.payCode = payCode;
//             alert(params.payObj)
            if(params.needPay<=0){
                WST.msg('请输入充值金额', {icon: 5});
                return;
            }
            if(params.payCode==""){
                WST.msg('请先选择支付方式', {icon: 5});
                return;
            }
            jQuery.post(WST.U('purse/'+params.payCode+'/get'+params.payCode+"URL"),params,function(data) {
                var json = WST.toJson(data);
                if(json.status==1){
                    if(params.payCode=="weixinpays"){
                        location.href = json.url;
                    }else{
                        location.href = json.url;
                    }
                }else{
                    WST.msg('充值失败', {icon: 5});
                }
            });
        }



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