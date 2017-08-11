/**
 * Created by holyf on 2017/4/8.
 */
	var check = true;
	var ifreg = false;
var timeleft = 60;
var date = new Date();
var year = date.getFullYear();
var month = date.getMonth() + 1;
month = month < 10 ? ('0' + month) : month;
var day = date.getDate();
day = day < 10 ? ('0' + day) : day;
console.log(year + month + day);
$("._img").attr("src", $('#serialize').html() + "/serialize/" + year + month + day + '/' + $('#check').html() + ".png")

$(".agreement input").click(function(){
	check = !check;
	if(check){
		$("#regnotice").hide();
	}
})
var ifcheck2 = true;
$("#ifcheck2").click(function(){
	ifcheck2 = !ifcheck2;
	if(ifcheck2){
		$("#regnotice2").hide();
	}
	else {
		$("#regnotice2").show();
	}
})
$(".post_tj").click(function(){

   if(!ifcheck2){
$("#regnotice2").show();
	   return false;
   }
})


$(".zh_login_r").click(function(){
	var reg = /^1(3|4|5|7|8)\d{9}$/;
	 var mobile = $("#zh_01").val();
	 var $input =$('.agreement input')[0];
	 var checked = $input.checked;
	 if( checked && mobile && reg.test(mobile)&&check ){

	 window.location.href="jump.html";
	 }
	 else if(!check){
		$("#regnotice").show();
		 return false;
	 }
});


/*$(".denglu").click(function() {

	$.ajax({
		type: "POST",
		url: $('#setqrcode').html(),
		data: {
			//              check : $('#check').html(),
		},
		async: true,
		//          timeout: 5000,
		dataType: "html",
		success: function(picn) {

			var time = new Date();
			var yy = time.getFullYear();
			var mm = time.getMonth();
			mm++;
			var dd = time.getDate();
			var hh = time.getHours();
			var min = time.getMinutes();
			var ss = time.getSeconds();
			mm = mm < 10 ? 0 + '' + mm : mm;
			dd = dd < 10 ? 0 + '' + dd : dd;

			var thatTime = '' + yy + mm + dd;
			//				console.log(thatTime) ;
			//				alert(thatTime);

			var code = picn;
			var cc = code.split('"').join("");

			var pic = cc + '.png';
			$(".code_s").attr("src", "/public/vvoff/serialize/" + thatTime + "/" + pic);

			console.log('remote url isn\'t correct');

			var picn1 = picn.split('"').join("");
			console.log(picn1);

			function show() {
				$.ajax({
					type: "POST",
					url: $('#remote').html(),
					data: {
						check: picn1,
					},
					async: true,
					// timeout: 5000,
					dataType: "html",
					success: function(res) {
						if(res == 'true') {
							window.location.href = $('#back').html();
						} else {
							console.log('reconnecting to remote url');
						}
					},
					error: function() {
						console.log('remote url isn\'t correct');
					}
				})
				return false
			}
			// setInterval(show,2000);

			var t = setInterval(show, 2000);

			function clear() {
				clearInterval(t);
				$(".code_s").css("display", "none");
				$(".code_cs").css("display", "block");
			}

			setTimeout(clear, 60000);

		}
	})
})*/
		var picn1 = $('#setqrcode').text();

		console.log(picn1);

		function show() {
			$.ajax({
				type: "POST",
				url: $('#remote').html(),
				data: {
					check: picn1,
				},
				async: true,
				// timeout: 5000,
				dataType: "html",
				success: function(res) {
					if(res == 'true') {
						window.location.href = $('#back').html();
					} else {
						console.log('reconnecting to remote url');
					}
				},
				error: function() {
					console.log('remote url isn\'t correct');
				}
			})
			return false
		}
		// setInterval(show,2000);

		var t = setInterval(show, 2000);

		function clear() {
			clearInterval(t);
			$(".code_s").css("display", "none");
			$(".code_cs").css("display", "block");
		}

		//setTimeout(clear, 100000);

$(".code_cs").click(function() {
	window.location.href = "http://www.jingomall.com"
});

// $(function () {
//     function refresh() {
//         $.ajax({
//             type:"POST",
//             url:$('#setqrcode').html(),
//             data: {
// //              check : $('#check').html(),
//             },
//             async:true,
// //          timeout: 5000,
//             dataType: "html",
//             success:function (pic1) {
//                 var pic2=pic1.split('"').join("");
//                 console.log(pic2);
//
//                 // function show() {
//                 //     $.ajax({
//                 //         type:"POST",
//                 //         url:$('#remote').html(),
//                 //         data: {
//                 //             check :pic2 ,
//                 //         },
//                 //         async:true,
//                 //         // timeout: 5000,
//                 //         dataType: "html",
//                 //         success:function (res) {
//                 //             if(res == 'true') {
//                 //                 window.location.href=$('#back').html();
//                 //             } else {
//                 //                 console.log('reconnecting to remote url');
//                 //             }
//                 //         },
//                 //         error:function () {
//                 //             console.log('remote url isn\'t correct');
//                 //         }
//                 //     })
//                 //     return false
//                 // }
//                 // setInterval(show,2000);
//
//                 var time = new Date();
//                 var yy = time.getFullYear();
//                 var mm = time.getMonth();
//                 mm++;
//                 var dd = time.getDate();
//                 var hh = time.getHours();
//                 var min = time.getMinutes();
//                 var ss =time.getSeconds();
//                 mm = mm<10?0+''+mm:mm;
//                 dd = dd<10?0+''+dd:dd;
//
//                 var thatTime1 = ''+yy+mm+dd;
// //				console.log(thatTime) ;
// //				alert(thatTime);
//
//                 var code1 = pic1;
//                 var cc1 =code1.split('"').join("");
//
//                 var pic2 = cc1 + '.png';
//                 $(".code_s").attr("src","/public/vvoff/serialize/"+thatTime1+"/" + pic2);
//
//               if(pic1 == 'true') {
//                   window.location.href=$('#back').html();
//               } else {
//                   console.log('reconnecting to remote url');
//               }
//           },
//           error:function () {
//               console.log('remote url isn\'t correct');
//             }
//         })
//         return false
//     }
//     setInterval(refresh,10000);
// })

//发送验证码

//console.log(num)
$("#send_yzm1").click(function() {
	var num = $("#zh_01").val();
	console.log(num);
	if(ifSend){
$.ajax({
		type: "POST",
		url: $('#send').html(),
		data: {
			mobile: num
		},
		async: true,
		//          timeout: 5000,
		dataType: "json",
		success: function(res) {

		}
	})
	return false
	}


})

//发送验证码

//console.log(num)
$("#send_yzm2").click(function() {
	var num1 = $("#zh_03").val();
	//console.log(num)
	if(ifSend){
		$.ajax({
		type: "POST",
		url: $('#sends').html(),
		data: {
			mobile: num1
		},
		async: true,
		//          timeout: 5000,
		dataType: "json",
		success: function(res) {
		}
	})
	return false
	}
	

})

//手机号验证
var ifSend = false;
$("#zh_01").blur(function checkMobile() {
	var reg = /^1(3|4|5|7|8)\d{9}$/;
	var mobile = $("#zh_01").val();
	if(mobile && reg.test(mobile)) {
		//对的
		ifSend = true;
		$("form.user_register .phone_m_zc p.sjgs").css("visibility", "hidden")


			if(ifSend&&!ifreg&&check){
				$("#send_yzm1").click(function(){
				$(this).attr("disabled","disabled");
				st=setInterval(function(){
					timeleft--;
					$("#send_yzm1").html(timeleft+"秒后重发");
					if(timeleft<1||timeleft==1){

						clearInterval(st);
						timeleft=60;
						$("#send_yzm1").removeAttr("disabled","disabled").html("点击发送验");
						return false;
					}
				},1000);


				})
			}else if(!check) {
				alert();
			}



	} else {
		$("p.sjgs").css("visibility", "visible")
	}
});

$("#zh_01").focus(function(){
	$("p.sjgs").css("visibility", "hidden")
})
//$("#zh_02").blur(function checkMobile1() {
//	var reg = /^1(3|4|5|7|8)\d{9}$/;
//	var mobile2 = $("#zh_02").val();
//	if(mobile2 && reg.test(mobile2)) {
//		//对的
//
//		$("p.sjgs").css("visibility", "hidden")
//
//	} else {
//		$(".sjgs").css("visibility", "visible")
//	}
//});
$("#zh_03").blur(function checkMobile2() {
	var reg = /^1(3|4|5|7|8)\d{9}$/;
	var mobile3 = $("#zh_03").val();
	if(mobile3 && reg.test(mobile3)) {
		//对的
ifSend = true;

		$("#send_yzm2").click(function(){

			if(ifSend){
				$(this).attr("disabled","disabled");
				st=setInterval(function(){
					timeleft--;
					$("#send_yzm2").html(timeleft+"秒后重发");
					if(timeleft<1){

						clearInterval(st);
						timeleft=60;
						$("#send_yzm2").removeAttr("disabled","disabled").html("点击发送验证码");
					}
				},1000);

			}
		})


$("p.sjgs").css("visibility", "hidden")

	} else {
		$(".sjgs").css("visibility", "visible")
	}
});
//已注册的手机号不能重复注册
$("#zh_01").keyup(function(){
	var mobile=$("#zh_01").val();
	var len = mobile.length;
	if(len==11){
	if(mobile!==''){
		$.post("/purse/Login/regs", { mobile: mobile },
			function(data){
				if(data=="您的手机号码已注册，请直接登录"){
					$(".sjgs").css({"visibility":"visible"})
					$(".sjgs").html(data);
                    ifreg = true
				}
				else{

					ifreg = false;
				}

			});

	}
	}
});

//退出

$(".quit").click(function() {
	window.location.href = $('#quit').html();
})



