<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:58:"F:\wamp\www\jingo/wstmart/purse\view\default\recharge.html";i:1496279650;s:62:"F:\wamp\www\jingo/wstmart/purse\view\default\purse\header.html";i:1500261424;s:62:"F:\wamp\www\jingo/wstmart/purse\view\default\purse\bottom.html";i:1494837028;}*/ ?>
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
                <li><a href="<?php echo url('consume/index'); ?>">我的钱袋</a>|</li>
                <li><a href="<?php echo url('imazamox/index'); ?>">我的金豆</a>|</li>
                <li><a href="<?php echo url('consume/bill'); ?>">我的账单</a>|</li>
                <li><a href="<?php echo url('loan/index'); ?>">我的贷款</a>|</li>
                <li><a href="<?php echo url('bank/index'); ?>">我的银行卡</a></li>
            </ul>
        </div>
        <div class="nav_right">
            <ul>
                <li>
                   <a href="<?php echo url('predeposit/recharge'); ?>" class="yr">充&nbsp;值</a>
                </li>
                <li>
                    <a href="<?php echo url('predeposit/withdrawals'); ?>" class="yr">提&nbsp;现</a>
                </li>
                <li>
                    <a href="<?php echo url('predeposit/transfer_purse'); ?>" class="yr">收付款</a>
                </li>
            </ul>

        </div>
    </div>
</div>
</div>

<script type="text/javascript">
    var li = $('.nav_right ul li a');
    $(li).eq(0).css({border:'1px solid #E71D56',color:'#E71D56'});
</script>

<div class="center public">
    <div class="bean">
        <ul class="nav nav-tabs">
            <li role="presentation"  class="active" id="one"><a href="<?php echo url('predeposit/recharge'); ?>">充值到余额</a></li>
            <li role="presentation"  id="two" ><a href="<?php echo url('predeposit/recharge_imazamox'); ?>" >充值到金豆</a></li>
        </ul>


        <div class="he form-horizontal form">
            <!--<form class="form-horizontal form" method="post">-->
                <div class="form-group">
                    <label for="needPay" class="col-sm-2 control-label">充值金额：</label>
                    <div class="col-md-2">
                        <input id="needPay" name="pdr_amount" type="text" class="form-control">
                    </div>
                    <div class="col-md-2 xq">元</div>
                </div>

                <div class="form-group explain">
                    <dl class="ye">
                        <dt>
                            <input type="hidden" id="payCode" name="payment_code" value="alipays">
                            <input type="hidden" id="payPurse" value="purse">
                            <span class="img"><img src="__STYLE__/img/alipay_logo.gif" ></span>
                            <span><a href="#" data-toggle="modal" data-target="#mymodal">更改</a></span>
                        </dt>
                    </dl>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <button onclick='javascript:getPayUrl();'>下一步</button>
                    </div>
                </div>
            <!--</form>-->
            <div id="mymodal" class="modal fade" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-lg" style="margin-top: 250px">
                    <div class="modal-content">
                        <form action="" method="post" class="form-horizontal">
                            <div class="tk">
                                <div class="modal-header">
                                    <div class="close" data-dismiss="modal">&times;</div>
                                    <h4>选择银行</h4>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="payCode" name="payCode" />
                                        <?php if(is_array($payments) || $payments instanceof \think\Collection): $i = 0; $__LIST__ = $payments;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$payment): $mod = ($i % 2 );++$i;if($payment['isOnline'] == 1): if($payment['payCode'] == 'wallets'): else: ?>
                                        <div class="bank">
                                            <label for="bank">
                                                <input  type="radio" name="payment_code" id="bank" value="<?php echo $payment['payCode']; ?>">
                                                <input type="hidden" id="payPurse" value="purse">
                                                <img class="img1" src="<?php echo !empty($payment['payCode']) && $payment['payCode']=='alipays'?'__STYLE__/img/alipay_logo.gif':'__STYLE__/img/wxpay_logo.gif'; ?>" alt="" name="bank">
                                            </label>

                                        </div>
                                        <?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
                                       </div>
                                      <!-- <div class="bank">
                                           <label for="bankss">
                                               <input type="radio" name="bank" id="bankss" value="wangyin">
   
                                               <img class="img3" src="__STYLE__/img/chinabank_logo.gif" alt="" name="bank">
                                           </label>
                                       </div>-->

                                </div>
                                <div class="modal-footer">
                                    <button data-dismiss="modal">下一步</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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

<script type='text/javascript' src='__STYLE__/shops/recharge/recharge.js?v=<?php echo $v; ?>'></script>
<script >



    $('.bank').each(function(){

        $(this).click(function(){
            link = $(this).find('img').attr('src');
            value = $(this).find('input').val();

            $('.ye img').attr('src',link);
            $('.ye').find('input:eq(0)').attr('value',value);
        });
    });


</script>
</body>
</html>
