//列表
function indexList(){
	 $('#Load').show();
	 loading = true;
	 var param = {};
	 param.currPage = Number( $('#currPage').val() ) + 1;
    $.post(WST.U('wechat/index/pageQuery'), param,function(data){
        var json = WST.toJson(data);
        if(json && json.catId){
	        $('#currPage').val(json.currPage);
            var gettpl = document.getElementById('list').innerHTML;
            laytpl(gettpl).render(json, function(html){
                $('#goods-list').append(html);
            });
	         WST.imgAdapt('j-imgAdapt');
        }
	     loading = false;
	     $('#Load').hide();
    });
}
var currPage = 0;
var loading = false;
$(document).ready(function(){
	WST.initFooter('home');
	//搜索
    $(window).scroll(function(){
        if( $(window).scrollTop() > 42 ){
            $('#j-header').removeClass('wst-in-header').addClass('wst-in-active');
            $('#j-icon').removeClass('wst-in-search').addClass('wst-in-activei');
            $('#j-search').removeClass('wst-in-icon').addClass('wst-in-actives');
        }else{
        	$('#j-header').removeClass('wst-in-active').addClass('wst-in-header');
        	$('#j-icon').removeClass('wst-in-activei').addClass('wst-in-icon');
        	$('#j-search').removeClass('wst-in-actives').addClass('wst-in-search');
        }
    });
    if($('.ui-slider li').hasClass("advert1")){
    	//广告
        var sliderh = $('.ui-slider-content img').width();
        if(sliderh!=null){
            var slider = new fz.Scroll('.ui-slider', {
                role: 'slider',
                indicator: true,
                autoplay: true,
                interval: 3000
            });
        }
    }else{
    	$('.ui-slider').hide();
    }
    
	var w = WST.pageWidth();
	//中间大广告
	var wl = (w-18)*47.222222/100;
	var hl = wl*32/57;
	if($('.wst-in-adst a').hasClass("advert2")){
		$('.wst-in-adst .adst').css('height',hl);
	}else{
		$('.wst-in-adst ').hide();
	}
	
	//中间小广告	
	if($('.wst-in-adsb a').hasClass("advert3")){
	    new Swiper('.swiper-container', {
	        slidesPerView: 4,
	        freeMode : true,
	        spaceBetween: 3,
	        autoplay : 2000,
	        speed:1200,
	        loop : true,
	        autoplayDisableOnInteraction : false,
	        onSlideChangeEnd: function(swiper){
	            echo.init();//图片懒加载
	        }
	    });
		var ws = (w-20)*24.666666/100;
		var hs = ws*110/87;
		$('.wst-in-adsb').css('height',hs);
	}else{
		$('.wst-in-adsb').hide();
	}


	//刷新
	indexList();

    $(window).scroll(function(){
        if (loading) return;
        if (5 + ($(window).scrollTop()) >= ($(document).height() - $(window).height())) {
            currPage = Number( $('#currPage').val() );
            if(currPage < 10 ){
                indexList();
            }
        }
    });
});