<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:54:"F:\wamp\www\jingo/wstmart/purse\view\default\bank.html";i:1494839742;s:62:"F:\wamp\www\jingo/wstmart/purse\view\default\purse\header.html";i:1500261424;s:62:"F:\wamp\www\jingo/wstmart/purse\view\default\purse\bottom.html";i:1494837028;}*/ ?>
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
                <li class="red"><a href="<?php echo url('bank/index'); ?>">我的银行卡</a></li>
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
<div class="bank">
    <div class="center public">
        <div class="bank_main ">
        
            <div class="bank_left">
                <h3>我的银行卡</h3>
                <span>已添加<i>&nbsp;2&nbsp;</i>银行卡,&nbsp;<i>&nbsp;2&nbsp;</i>张信用卡</span>
            </div>
        
        </div>
    </div>
    <div class="center height public">
    <div class="bank_main ">
        
        <ul>
            <li class="bg">
                <ul>
                    <li><img src="__STYLE__/img/bank.png"></li>
                    <li class="wh">尾号<span>666</span></li>
                    <li><a class="cxk">储蓄卡</a></li>
                </ul>
                <dl>
                    <dt class="xz">单笔10,000元</dt>
                    <dt>单日5,000元</dt>
                    <dd><a class="zz" href="#">转账</a></dd>
                    <img src="__STYLE__/img/del.png">
                </dl>
            </li>

            <li class="bg">
                <ul>
                    <li><img src="__STYLE__/img/bank.png"></li>
                    <li class="wh">尾号<span>666</span></li>
                    <li><a class="cxk">储蓄卡</a></li>
                </ul>
                <dl>
                    <dt class="xz">单笔10,000元</dt>
                    <dt>单日5,000元</dt>
                    <dd><a class="zz" href="#">转账</a></dd>
                    <img src="__STYLE__/img/del.png">
                </dl>
            </li>
            <li class="add_bank">
                <div>
                    <dl class="add">
                        <dt><img src="__STYLE__/img/addbank.png" data-toggle="modal" data-target="#mymodal"></dt>
                    </dl>
                    <dl class="dl">
                        <dt><a href="#" id="login" data-toggle="modal" data-target="#mymodal"><h4>添加银行卡</h4></a></dt>
                    </dl>
                </div>
            </li>
        </ul>

        <div id="mymodal" class="modal fade">
            <div class="modal-dialog modal-md" style="margin-top: 250px">
                <div class="modal-content">
                    <form action="" method="post" class="form-horizontal">
                    <!-- <div class="tk"> -->
                    <div class="modal-header">
                        <div class="close" data-dismiss="modal">&times;</div>
                        <h4 style="font-weight: 700;">添加银行卡</h4>
                    </div>
                    <div class="modal-body">
                        <div class="add_bank1">


                           <div class="bank_name">
                               <label>姓名:</label>
                               <div><input type="text" placeholder="请输入姓名"></div>
                           </div>
                           <div class="bank_name">
                               <label>卡号:</label>
                               <div><input type="text"  maxlength="19" placeholder="请输入银行卡号" name=number></div>
                           </div>
                       </div>
                       <div class="hen">
                       <div class="add_bank2">
                           <div class="bank_xinxi">
                                <dl class="bank_message">
                                    <dd><img src="__STYLE__/img/bank.png" style="border-radius:50%;"><span>中国建设银行</span><span>储蓄卡</span></dd>
                                </dl>
                            </div>
                       </div>
                       <div class="back"></div>
                       <div class="add_bank1">
                           <div class="bank_name bank_xx">
                               <label>SVN2:</label>
                               <div><input type="text"  maxlength="3" placeholder="卡背面后三位"></div>
                           </div>
                       </div>
                        <div class="back"></div>
                        <div class="add_bank1">
                           <div class="bank_name bank_xx">
                               <label>手机号:</label>
                               <div><input type="text"  maxlength="11" placeholder="请输入手机号"></div>
                           </div>
                       </div>
                        <div class="back"></div>
                        <div class="add_bank1">
                           <div class="bank_name1">
                               <div class="yzm "><input type="text" style="border-radius:4px;"></div>
                               <div class="yzm" style="height:35px;line-height: 35px;"><a href="#" class="code">获取验证码</a href="#"></div>
                           </div>
                       </div>
                       </div>
                    </div>
                       
                        <div class="modal-footer" id="add_bank">
                        <button type="submit">下一步</button>

                        <label>
                            <input type="checkbox">我已阅读并同意
                            <a href=""><<金购条款>></a>
                        </label>

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

<script type="text/javascript">



        $('input[name=number]').blur(function(){
            val = this.value;
            // alert(val.length);
            if(val == ''){
                $('.hen').hide();
            }else if(val.length != 19){
                $('.hen').hide();
            }else{
                $('.hen').show();
            }
            
        });




    //在银行卡上移动出现样式
      $('.bg').each(function(){
            $(this).hover(
                function(){
                    $(this).css('border','2px solid #74abe9');
                    $(this).find('dl').find('img').css('display','block');
                },
                function(){
                    $(this).css('border','');
                    $(this).find('dl').find('img').css('display','');
                }
            );
        });

      //删除银行卡
      $('.bg').find('img').each(function(){
        $('.bg').find('img').click(function(){
            $(this).parent().parent().remove();
        });
      });

      //鼠标移入移出添加银行卡样式
    $('.add_bank').hover(

        function(){
            $(this).css({'border':'1px dashed #74abe9'}).find('h4').css({'color':'#74abe9'});
        },
        function(){
            $(this).css({'border':''}).find('h4').css({'color':''});
        }
    );

</script>
    </body>
</html>