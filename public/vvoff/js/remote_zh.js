/**
 * Created by holyf on 2017/4/8.
 */


var date = new Date();
var year = date.getFullYear();
var month = date.getMonth()+1;
month = month < 10 ? ('0'+month) : month;
var day = date.getDate();
day = day < 10 ? ('0'+day) : day;
console.log(year+month+day);
$("._img").attr("src",$('#serialize').html()+"/serialize/"+year+month+day+'/'+$('#check').html()+".png")



$(".denglu").click(function(){

    $.ajax({
        type:"POST",
        url:$('#setqrcode').html(),
        data: {
//              check : $('#check').html(),
        },
        async:true,
//          timeout: 5000,
        dataType: "html",
        success:function (picn) {

            var time = new Date();
            var yy = time.getFullYear();
            var mm = time.getMonth();
            mm++;
            var dd = time.getDate();
            var hh = time.getHours();
            var min = time.getMinutes();
            var ss =time.getSeconds();
            mm = mm<10?0+''+mm:mm;
            dd = dd<10?0+''+dd:dd;

            var thatTime = ''+yy+mm+dd;
//				console.log(thatTime) ;
//				alert(thatTime);

            var code = picn;
            var cc =code.split('"').join("");

            var pic = cc + '.png';
            $(".code_s").attr("src","/public/vvoff/serialize/"+thatTime+"/" + pic);

            console.log('remote url isn\'t correct');


            var picn1=picn.split('"').join("");
            console.log(picn1);

            function show() {
                $.ajax({
                    type:"POST",
                    url:$('#remote').html(),
                    data: {
                        check :picn1 ,
                    },
                    async:true,
                    // timeout: 5000,
                    dataType: "html",
                    success:function (res) {
                        if(res == 'true') {
                            window.location.href=$('#back').html();
                        } else {
                            console.log('reconnecting to remote url');
                        }
                    },
                    error:function () {
                        console.log('remote url isn\'t correct');
                    }
                })
                return false
            }
            // setInterval(show,2000);


            var t=setInterval(show,2000);
            function clear() {
                clearInterval(t);
                $(".code_s").css("display","none");
                $(".code_cs").css("display","block");
            }

            setTimeout(clear,60000);

        }
    })
})



$(".code_cs").click(function () {
    window.location.href="http://www.jingomall.com"
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
$("#zh_01").blur(function (){
    var num=$("#zh_01").val();
    //console.log(num)
    $(".send_yzm").click(function() {
        console.log(num)
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

    })
})

//发送验证码
$("#zh_03").blur(function (){
    var num1=$("#zh_03").val();
    //console.log(num)
    $(".send_yzm").click(function() {
        //console.log(num)
        $.ajax({
            type: "POST",
            url: $('#send').html(),
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

    })
})




//手机号验证

$("#zh_01").blur(function checkMobile() {
    var reg = /^1(3|4|5|7|8)\d{9}$/;
    var mobile = $("#zh_01").val();
    if(mobile && reg.test(mobile)) {
        //对的

        $("form.user_register .phone_m_zc p.sjgs").css("visibility", "hidden")

    } else {
        $("p.sjgs").css("visibility", "visible")
    }
});
$("#zh_02").blur(function checkMobile1() {
    var reg = /^1(3|4|5|7|8)\d{9}$/;
    var mobile2 = $("#zh_02").val();
    if(mobile2 && reg.test(mobile2)) {
        //对的

        $("p.sjgs").css("visibility", "hidden")

    } else {
        $(".sjgs").css("visibility", "visible")
    }
});
$("#zh_03").blur(function checkMobile2() {
    var reg = /^1(3|4|5|7|8)\d{9}$/;
    var mobile3 = $("#zh_03").val();
    if(mobile3 && reg.test(mobile3)) {
        //对的

        $("p.sjgs").css("visibility", "hidden")

    } else {
        $(".sjgs").css("visibility", "visible")
    }
});


//退出

$(".quit").click(function () {
    window.location.href=$('#quit').html();
})
