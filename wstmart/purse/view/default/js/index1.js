/**
 * Created by Me on 2017/5/31.
 */
$(function () {
    var h = window.innerHeight;
    $(".main").height(h + "px");
    $(".main_main").height(h + "px");



})

//二维码账号切换
$(function () {
    var a = true;
    $(".IMG").click(function () {
        if(a) {
            $(".IMG").attr("src","img/8.png");
            $(".QR_code").css("display","none");
            $(".p_login").css("display","block");
            a = false;
        } else {
            $(".IMG").attr("src","img/7.png");
            $(".QR_code").css("display","block");
            $(".p_login").css("display","none");
            a = true;
        }
    })
})

