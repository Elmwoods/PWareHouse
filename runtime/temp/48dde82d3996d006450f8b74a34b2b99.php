<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:64:"F:\wamp\www\jingo/wstmart/purse\view\default\transfer_purse.html";i:1500442116;s:62:"F:\wamp\www\jingo/wstmart/purse\view\default\purse\header.html";i:1500261424;s:62:"F:\wamp\www\jingo/wstmart/purse\view\default\purse\bottom.html";i:1494837028;}*/ ?>
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
<script type="text/javascript">

    var li = $('.nav_right ul li a');
    $(li).eq(2).css({border:'1px solid #E71D56',color:'#E71D56'});



</script>
<div class="center public">
    <div class="bean">
        <ul class="nav nav-tabs">
            <li role="presentation" class="active" id="one"><a href="<?php echo url('predeposit/transfer_purse'); ?>">转账到钱袋</a></li>
            <li role="presentation" id="two"><a href="<?php echo url('predeposit/transfer_bank'); ?>">转账到银行卡</a></li>
            <li role="presentation" id="three"><a href="<?php echo url('predeposit/gathering'); ?>">我要收款</a></li>
        </ul>
        <?php if(!empty($news)): if($news['dataFlag'] == 0): ?>
        <div class="he">
            <form method="post" action="" class="form-horizontal">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">收款人：</label>
                    <div class="col-md-2">
                        <input name="is_get" type="hidden" value="<?php echo $news['id']; ?>">
                        <input name="member_name" value="<?php echo $user['loginName']; ?>" type="text" class="form-control" placeholder="昵称" readonly="readonly">
                    </div>
                        <span role="alert" class="msg-wrap n-error" style="margin-left:5px;margin-top:7px">
                            <span class="n-icon" style="display:none;" id='yu_y'></span>
                            <span class="n-msg" style='display:none;' id='yu_h'>用户名不存在</span>
                        </span>
                </div>
                <div class="form-group">
                    <div class="hidd" style=""><div class="portrait"><img class="img" src="<?php echo !empty($user['userPhoto'])?'/'.$user['userPhoto']:'__ROOT__/upload/sysconfigs/2016-10/5804800d5841e.png'; ?>" style="width:40px;height:40px;border-radius:50%;" /></div><?php echo !empty($user['userName'])?'<span class="jy">'.$user['userName'].'</span>':'<span class="jy" style="color:red">该用户未设置昵称</span>'; ?></div>

                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label" >付款金额：</label>
                    <div class="col-md-2">
                        <input name="amount" type="text" value="<?php echo $news['amount']; ?>" class="form-control" placeholder="收款金额" readonly="readonly">


                    </div>
                        <span role="alert" class="msg-wrap n-error" style="margin-left:5px;margin-top:7px">
                            <span class="n-icon" style="display:none" id='yu_q'></span>
                            <span class="n-msg" style='display:none;' id='yu_e'></span>
                        </span>
                    <div class="col-md-2 xq"></div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">付款说明：</label>
                    <div class="col-md-2">
                        <input name="desc" value="<?php echo $news['msgDesc']; ?>" type="text" class="form-control" placeholder="收款说明" readonly="readonly">
                    </div>
                    <div class="col-md-2 xq">添加备注</div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <button type="submit" id="sub">下一步</button>
                    </div>
                </div>
            </form>
            <?php else: ?>
            已付款
            <?php endif; else: ?>
            <div class="he">
                <form method="post" action="" class="form-horizontal">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">收款人：</label>
                        <div class="col-md-2">
                            <input name="is_get" type="hidden">
                            <input name="member_name" type="text" class="form-control ng" placeholder="收款人" value="" data-rule="收款人: required;" data-msg-required="请输入收款人" data-tip="请输入收款人">
                        </div>
                        <span style="display: none;margin-top:7px;padding-left:10px;" class="name ">请输入收款人</span>
                        <span role="alert" class="msg-wrap n-error" id='yu_x' style="margin-left:5px;margin-top:7px;">
                                <span class="n-icon" style="display:none" id='yu_y'></span>
                                <span class="n-msg" style="display:none" id='yu_h'></span>
                            </span>
                    </div>
                    <div class="form-group">

                        <div class="hidd" style="display: none;"><div class="portrait"><img class="img" src="" style="width:40px;height:40px;border-radius:50%;" /></div><span class="jy">校验收款人手机号</span></div>


                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label" >付款金额：</label>
                        <div class="col-md-2">
                            <input name="amount" type="text" class="form-control ng" placeholder="收款金额"  data-rule="收款金额: required;" data-msg-required="请输入收款金额" data-tip="请输入收款金额">
                        </div>

                        <span role="alert" class="msg-wrap n-error" style="margin-left:5px;margin-top:7px">
                            <span class="n-icon" style="display:none" id='yu_q'></span>
                            <span class="n-msg" style='display:none;' id='yu_e'></span>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">付款说明：</label>
                        <div class="col-md-2">
                            <input name="desc" type="text" class="form-control" placeholder="收款说明">
                        </div>
                        <div class="col-md-2 xq">添加备注</div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <button type="submit" id="sub">下一步</button>
                        </div>
                    </div>
                </form>
            <?php endif; ?>

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

<script >

    i=0;
    $('input[type="checkbox"]').click(function(){

        $("#phonetext").attr( "data-rule","手机号: required;");
        $("#phonetext").attr( "data-msg-required","请输入手机号");
        $("#phonetext").attr( "data-tip","请输入手机号");
        if(i%2==0){
            $('.block').css('display','block');
            $('.hidd').css('display','none');
        }else{
            $('.block').css('display','none');
            $('.hidd').css('display','block');
        }
        i++;
    });
    $("#two").click(function(){
        $(this).toggleClass('active');
        $("#one").removeClass('active');
    });
    $("#one").click(function(){
        $(this).toggleClass('active');
        $("#two").removeClass('active');
    });
////////////
    $("input[name='member_name']").focus(function(){
    	// alert(20);
        $('#yu_y').hide();
        $('#yu_h').hide();
        $('.hidd').hide();
    });
    $("input[name='member_name']").blur(function(){
        a = $("input[name='member_name']").val();
        if(a==''){

        } else {
            javascript:WST.Payee(a);
        }
    });
/////////////
   $("input[name='amount']").focus(function(){
    	$('#yu_q').hide();
        $('#yu_e').hide();
    });

    $("input[name='amount']").blur(function(){
        va = $("input[name='amount']").val();
        if(!isNaN(va)){
//            alert(a);
            javascript:WST.With(va);
        } else {
            $('#yu_q').show();
            $('#yu_e').show().html('请输入正确的数字');
            $('#sub').attr("disabled",true);
        }
    });

    //用户名是否存在查询
    WST.Payee = function(){
        $.post(WST.U('purse/Predeposit/Payee'),{loginName:a},function(data){

            // var json = WST.toJson(data);
            // alert(data.userName);
            if(data=='error'){
                $('#yu_y').show();
                $('#yu_h').show().html('用户名不存在');
                $('#sub').attr("disabled",true);
            }else{
                $('.hidd').show();
                // alert(data.userName);
                var user = data['userName'];
                var photo = data['userPhoto'];
                var phone = data['userPhone'];
                $('.jy').html(user);
                if(photo){
                    $('.img').attr('src','__ROOT__/'+photo);
                }else{
                    $('.img').attr('src','__ROOT__/upload/sysconfigs/2016-10/5804800d5841e.png');
                }
                if(user){
                    $('.jy').html(user).css('color','black');
                }else{
                    $('.jy').html('该用户未设置昵称').css('color','red');
                }
                // alert(photo);
                $('#sub').attr("disabled",false);
            }
        });
    }
////////  	

     $('.form-horizontal').validator(function() {
         member_name = $("input[name='member_name']").val();
         amount = $("input[name='amount']").val();
         desc = $("input[name='desc']").val();
         is_get = $("input[name='is_get']").val();
         if(member_name == ''){
             WST.msg('不能转账给自己', {icon: 5});
             return;
         }
         var loading = WST.msg('正在提交数据，请稍后...', {icon: 16, time: 60000});
         $.post(WST.U('Purse/Predeposit/transfer_purse'), {
             member_name: member_name,
             amount: amount,
             desc: desc,
             is_get: is_get
         }, function (data, textStatus) {
             var json = WST.toJson(data);
             if (json.status == 1) {
                 WST.msg(json.msg, {icon: 1});
                 location.href = WST.U('purse/Consume/index');
             } else {
                 layer.close(loading);
                 WST.msg(json.msg, {icon: 2});
             }
         })
     });
</script>
</body>
</html>