$(".close_d").click(function(){
    $(".code_login").css("display","none");
    $(".zhezhao").css("display","none");
    $(".code_login_d").css("display","none");
    $(".user_login").css("display","none");
    $(".user_register").css("display","none");
    

})
$(".close_d2").click(function(){
    $(".code_login").css("display","none");
    $(".zhezhao").css("display","none");
    $(".code_login_d").css("display","none");
    $(".user_login").css("display","none");
    $(".user_register").css("display","none");
    

})
$(".guan").click(function(){
    $(".zhezhao").css("display","none");
    $(".code_down_b").css("display","none")

})

$(".tab1").click(function(){
   
    $(".code_login").css("display","none");
    $(".code_login_d").css("display","block");
    $(".user_login").css("display","none");

})
$(".tab2").click(function(){
    
    $(".code_login").css("display","block");
    $(".code_login_d").css("display","none");
    $(".user_login").css("display","none");

})
$(".accounts").click(function(){
    $(".code_login").css("display","none");
    $(".code_login_d").css("display","none");
    $(".user_login").css("display","block");
})
$(".note_login").click(function(){
    $(".code_login").css("display","none");
    $(".code_login_d").css("display","block");
    $(".user_login").css("display","none");
    $(".user_register").css("display","none");
})
$(".zhuce").click(function(){
    $(".zhezhao").css("display","block");
    $(".user_register").css("display","block");
   

})

$(".downvv").click(function(){
    $(".zhezhao").css("display","block");
    $(".code_down_b").css("display","block");

})
$(".zh_login_r").click(function(){
    window.location.href="jump.html";
});


$(function () {
    var h = window.innerHeight;
    $(".main").height(h + "px");
    $(".main_main").height(h + "px");
})
$(function () {
    var h = window.innerWidth;
    $(".main").width(h + "px");
    $(".main_main").width(h + "px");
})