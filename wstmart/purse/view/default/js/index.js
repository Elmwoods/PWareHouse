$(function () {
    //判断是否宽屏
    var winWide = window.screen.width;
    var wideScreen = true;
    if (winWide >= 992) {

        /*$("#css").remove("href","css/phone.css");*/
        $("#css").attr("href", $('#serialize').html()+"/css/index.css");
    } else {

        /*$("#css").remove("href","css/index.css");*/
        $("#css").attr("href", $('#serialize').html()+"/css/phone.css");
        wideScreen = false;
    }
})





$(function () {
	var h = window.screen.height;
	/*$(".menu").height(h + "px");*/
	/*$(".hot").height(h + "px");*/
	/*$(".news").height(h + "px");*/
	/*$(".column").height(h + "px");*/
	/*$(".shop").height(h + "px");*/
	/*$(".weilai").height(h + "px");*/
	$(".one").height(h + "px");
	/*$(".service").height(h + "px");*/
	
})
$(window).on("load resize",function(){
        var h=$(window).height();
      	/*$(".swiper-container").height(h);*/
      	/*touch.on(".swiper-slide", swiperight, function () {
      		location.replace(location.href);
      	} )*/
       
    });

$(function () {
        var index = 0;//局部变量一般要初始化
        var timer = null;
        var $imgs = $("#pics a");//缓存jQuery集合,避免多次查询
        autoPlay();//自动播放
        function autoPlay() {
            timer = setInterval(function () {
                index++;
                if(index > $imgs.size() - 1) {
                    index = 0;
                }
                showImage();
            },3000);
        }
        //显示图片
        function  showImage() {
            //让当前的图淡入,其他的图淡出
            $imgs.eq(index).stop(true).fadeIn(500).siblings().stop(true).fadeOut(500);
        }

    })
$(function () {
        var index = 0;//局部变量一般要初始化
        var timer = null;
        var $imgs = $("#pics1 a");//缓存jQuery集合,避免多次查询
        autoPlay();//自动播放
        function autoPlay() {
            timer = setInterval(function () {
                index++;
                if(index > $imgs.size() - 1) {
                    index = 0;
                }
                showImage();
            },3000);
        }
        //显示图片
        function  showImage() {
            //让当前的图淡入,其他的图淡出
            $imgs.eq(index).stop(true).fadeIn(500).siblings().stop(true).fadeOut(500);
        }

    })
$(function () {
        var index = 0;//局部变量一般要初始化
        var timer = null;
        var $imgs = $("#pics2 a");//缓存jQuery集合,避免多次查询
        autoPlay();//自动播放
        function autoPlay() {
            timer = setInterval(function () {
                index++;
                if(index > $imgs.size() - 1) {
                    index = 0;
                }
                showImage();
            },3000);
        }
        //显示图片
        function  showImage() {
            //让当前的图淡入,其他的图淡出
            $imgs.eq(index).stop(true).fadeIn(500).siblings().stop(true).fadeOut(500);
        }

    })
$(function () {
        var index = 0;//局部变量一般要初始化
        var timer = null;
        var $imgs = $("#pics3 a");//缓存jQuery集合,避免多次查询
        autoPlay();//自动播放
        function autoPlay() {
            timer = setInterval(function () {
                index++;
                if(index > $imgs.size() - 1) {
                    index = 0;
                }
                showImage();
            },3000);
        }
        //显示图片
        function  showImage() {
            //让当前的图淡入,其他的图淡出
            $imgs.eq(index).stop(true).fadeIn(500).siblings().stop(true).fadeOut(500);
        }

    })

$(".but").click(function () {
	$(".t_none").next().css("display","block").addClass("t_none");
	
	 if($(".t_block").is(':hidden')){  
      
        
    }else{  
                
       $(".but").css("display","none")  
    }  
	
	
})


$("#li").hover(function(){$(".code").css("display", "block");},function(){$(".code").css("display", "none");})

$(".close_d").click(function(){
    $(".code_login").css("display","none");
    $(".zhezhao").css("display","none");
    $(".code_login_d").css("display","none");
    $(".user_login").css("display","none");
    $(".user_register").css("display","none");
    $(".code_down_b").css("display","none")

})
$(".close_d2").click(function(){
    $(".code_login").css("display","none");
    $(".zhezhao").css("display","none");
    $(".code_login_d").css("display","none");
    $(".user_login").css("display","none");
    $(".user_register").css("display","none");
    $(".code_down_b").css("display","none")

})
$(".denglu").click(function(){
    $(".zhezhao").css("display","block");
    $(".code_login").css("display","block");
    $(".user_register").css("display","none");
    $(".code_login_d").css("display","none");
    $(".user_login").css("display","none");
    $(".code_down_b").css("display","none");

})
$(".tab1").click(function(){
    $(".zhezhao").css("display","block");
    $(".code_login").css("display","none");
    $(".code_login_d").css("display","block");
    $(".user_login").css("display","none");

})
$(".tab2").click(function(){
    $(".zhezhao").css("display","block");
    $(".code_login").css("display","block");
    $(".code_login_d").css("display","none");
    $(".user_login").css("display","none");

})
$(".accounts").click(function(){
    $(".zhezhao").css("display","block");
    $(".code_login").css("display","none");
    $(".code_login_d").css("display","none");
    $(".user_login").css("display","block");
})
$(".note_login").click(function(){
    $(".zhezhao").css("display","block");
    $(".code_login").css("display","block");
    $(".code_login_d").css("display","none");
    $(".user_login").css("display","none");
    $(".user_register").css("display","none");
})
$(".zhuce").click(function(){
    $(".zhezhao").css("display","block");
    $(".user_register").css("display","block");
    $(".code_down_b").css("display","none");

})
$(".downvv").click(function(){
    $(".zhezhao").css("display","block");
    $(".code_login").css("display","none");
    $(".code_login_d").css("display","none");
    $(".user_login").css("display","none");
    $(".user_register").css("display","none");
    $(".code_down_b").css("display","block");

})
/*$(".zh_login_r").click(function(){
    window.location.href="jump.html";
});*/

$("#demo1").hover(function(){$(".user_detail").css("display", "block");},function(){$(".user_detail").css("display", "none");})



