<?php
use wstmart\vv\controller;
use think\Db;
use wstmart\purse\controller\Commision;

if(version_compare(PHP_VERSION,'5.4.0','<'))  die('require PHP > 5.4.0 !');
//进入安装目录
if(is_dir("install") && !file_exists("install/install.ok")){
  header("Location:install/index.php");
  exit();
}
// [ 应用入口文件 ]
// 定义应用目录
define('APP_PATH', __DIR__ . '/wstmart/');
define('CONF_PATH', __DIR__.'/wstmart/common/conf/');
define('WST_COMM', __DIR__.'/wstmart/common/common/');
define('WST_HOME_COMM', __DIR__.'/wstmart/home/common/');
define('WST_ADMIN_COMM', __DIR__.'/wstmart/admin/common/');
define('WST_WECHAT_COMM', __DIR__.'/wstmart/wechat/common/');
// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';


$arr = [
  [1,2,3],
  [4,5,6],
  [7,8,9],
  [10,11,12],
  [10,11,12]
 
];

$arr1 = [
  [[1,2,3,4]],
  [[24,27,45]]
  
];

// unset($arr[0]);
// header("content-type:text/html; charset=utf-8");
// echo "<pre>";
// print_r($arr);die;
// for ($i=0; $i < count($arr)-1; $i++) { 

// $arrNew = [];
// 
// foreach ($arr as $key => $value) {
// 	if ($key%2 == 1) {
// 		break;
// 	}
// 	foreach ($arr[$key] as $k1 => $value1) {
// 		if ($key < count($arr)-1) {
// 			foreach ($arr[$key+1] as $k2 => $value2) {
// 				$arrNew[$key][] = $value1.':'.$value2;
			
// 			}
// 		}
// 	}
// 	unset($arr[$key]);
// 	unset($arr[$key+1]);
// 	$arr2 = f1($arr);	
// }
// f1($arr);
// // unset($arr[$key]);
// // unset($arr[$key+1]);
// // $arrNew = [];	
// function f1($arr){
// 	global $arrNew;
// 	// echo "<pre>";
// 	// print_r($GLOBALS['arr']);die;
// 	foreach ($GLOBALS['arr'] as $key => $value) {
// 		if ($key%2 == 1) {
// 			break;
// 		}
// 		foreach ($GLOBALS['arr'][$key] as $k1 => $value1) {
// 			if ($key < count($arr)-1) {
// 				foreach ($GLOBALS['arr'][$key+1] as $k2 => $value2) {
// 					$arrNew[$key][] = $value1.':'.$value2;
				
// 				}
// 			}
// 		}	
// 	}
// 	unset($GLOBALS['arr'][$key]);
// 	unset($GLOBALS['arr'][$key-1]);
// 	if (count($GLOBALS['arr'])>1) {
// 		f1($arr);
// 	}
// }

// function f2($arr){
//   global $arrNewXin;
//   // echo "<pre>";
//   // print_r($GLOBALS['arr']);die;
//   foreach ($GLOBALS['arr3'] as $key => $value) {
//     if ($key%2 == 1) {
//       break;
//     }
//     foreach ($GLOBALS['arr3'][$key] as $k1 => $value1) {
//       if ($key < count($arr)-1) {
//         foreach ($GLOBALS['arr3'][$key+1] as $k2 => $value2) {
//           $arrNewXin[$key][] = $value1.':'.$value2;
        
//         }
//       }
//     } 
//   }
//   unset($GLOBALS['arr3'][$key]);
//   unset($GLOBALS['arr3'][$key-1]);
//   if (count($GLOBALS['arr3'])>1) {
//     f1($arr);
//   }
// }
// $i = 0;
// foreach ($arrNew as $key => $value) {
//     $arr3[$i] = $value;
//     $i++;
// }
// if (count($arr) == 1) {
//     foreach ($arr as $key => $value) {
//         array_push($arr3, $value);
//     }
// }
// // echo "<pre>";
// // print_r($arr);
// $arr = $arr3;
// unset($arrNew);
// header("content-type:text/html; charset=utf-8");
// echo "<pre>";
// print_r($arr);
// f1($arr);


// echo "<pre>";
// print_r($arr);
// // echo "<pre>";
// // print_r($arr3);
// echo "<pre>";
// print_r($arrNew);


// function getArr3($arr){
//     $result = [];
//     $arrLength = count($arr);
//     $elementLength = count($arr[1]);
//     for ($i=0; $i < count($arr[0]) ; $i++) {
//         for ($j=0; $j < $elementLength; $j++) {
//             if (is_array( $arr[$arrLength-1][$j] ) && count( $arr[$arrLength-1][$j] ) > 2){
//                 foreach ($arr[$arrLength-1][$j] as $key=>$val){
//                     $resultArr[$j] = array(
//                         "$j" => $arr[0][$i] . "_" . $j ."_" . $val
//                     );
//                 }
//             }else{
//                 $resultArr[$j] = array(
//                     "$j" => $arr[0][$i]."_".$arr[1][$j]
//                 );
//             }
//         }

//         foreach ($resultArr as $k=>$v){
//             $result[] = $v[$k];
//         }
//     }
//     echo "<pre>";
//     print_r($result);echo "<br>";
//     return;
// }
//getArr3($arr1);
//
// include ("phpqrcode.php");
// //use wstmart\QRcode;
// //容错率大小
// $errorCorrectionLevel = "L";
// //生成二维码的大小
// $matrixPointSize = "4";
// $strUrl = "https://www.baidu.com";
// QRcode::png( $strUrl, 'lwb.png', $errorCorrectionLevel, $matrixPointSize);
// $QR = './lwb.png';//已经生成的原始二维码图
// echo "'< img src=". $QR .">'";


 // echo date('Y-m-d H:i:s');
 // echo "<pre>";
 // echo date('Y-m-d H:i:s',time()+(86400*30));
 // echo fmod(20,3);
 // echo $rand = rand(1,8);
 // $sql = "INSERT INTO `jingo_users` (`userId`, `loginName`, `loginSecret`, `loginPwd`, `userType`, `userSex`, `userName`, `trueName`, `brithday`, `userAge`, `signature`, `userPhone`, `userNation`, `userProvince`, `userCity`, `longitude`, `latitude`, `positionCreateTime`, `userPhoto`, `qrcode`, `setAge`, `setDistance`, `setStranger`, `isValidate`, `isRec`, `userQQ`, `userEmail`, `userScore`, `userTotalScore`, `lastIP`, `lastTime`, `userFrom`, `userMoney`, `lockMoney`, `userStatus`, `isSeted`, `dataFlag`, `createTime`, `payPwd`, `wxOpenId`, `wxUnionId`, `imazamox_number`, `pea`, `loan_date`, `repayment_date`, `residual_repayment`, `loanAmount`, `overdue`, `salesendID`, `isProvince`, `blackList`, `loanTimes`) VALUES (NULL, 'test4', '1734', 'a78278c8e830819e886bc0b8713abf3d', '0', '1', 'test', '测试', '1970-01-01', '', '', '', '中国', '', '', '', '', '0', 'upload/userPhotos/userPhoto.png', '', '26,45', '45', '1', '1', '1', NULL, '', '0', '0', NULL, '2017-07-20 00:00:00', '0', '0.00', '0.00', '1', '0', '1', '2017-07-20 00:00:00', NULL, NULL, NULL, '0.000', '0', '2017-07-20 00:00:00', '2017-07-20 00:00:00', '0.00', '0.00', '0', NULL, '0', '0', '0')";
 // $test = 'test';
 // for ($i=6; $i <= 100; $i++) { 
 // 	$loginName = $test."$i";
 // 	// Db::query("select * from think_user where id=? AND status=?",[8,1]);
	// $res1 = Db::execute($sql);
	// $res2 = Db::name('users')->getLastInsID();
	// echo $res2;
 // }
 


// $array = [
//     [1,2,3],
//     [6,7,8],
//     [22,33],
//     [9,10,11,12],
//     [22,33],
//     [6,7,8],
//     [9,10,11,12,55,55,22,33],
// ];
// $aa = count($array);
// $data = [];
// $data = getCombinationToString($array, $aa);
// foreach ($data as &$av) {
//         $av = implode(':', $av);

// }
// var_dump($data);
// function getCombinationToString($arr, $len)
// {
//     if ($len == 1) {
//         return $arr[0];
//     }
//     $tempArr = $arr;
//     unset($tempArr[0]);
//     $returnarr = [];
//     $len2 = count($arr);
//     $ret = getCombinationToString(array_values($tempArr), ($len - 1));
//     foreach ($arr[$len2 - $len] as $alv) {
//         foreach ($ret as $rv) {
//             if (is_array($rv)) {
//                 array_unshift($rv, $alv);
//                 $returnarr[] = array_values($rv);
//             } else {
//                 $returnarr[] = [$alv,$rv];
//             }
//         }
//     }
//     return $returnarr;
// }
// $startMonth = strtotime(2017.'-'.$temMonth.'-01');//该月的月初时间戳
// $endMonth = strtotime($Year.'-'.$Month.'-01') - 86400;//该月的月末时间戳
// $res['startMonth'] = $temYear.'-'.$temMonth.'-01'; //该月的月初格式化时间
// $res['endMonth'] = date('Y-m-d',$endMonth);//该月的月末格式化时间
// $res['timeArea'] = implode(',',[$startMonth, $endMonth]);//区间时间戳
// $res['staretime'] = $startMonth;//区间开始时间戳
// $res['endtime'] = $endMonth;//区间结束时间戳
// $timeAreaList[] = $res;
// $start = strtotime(date('Y').'-'.date('m').'-1');//该月的月初时间戳
// echo date('Y-m-d',$start);
// echo "<pre>";
// echo date('Y');
// echo "<pre>";
// echo date('m');
// echo "<pre>";
//          var_dump(date('m')+1);
// $end = strtotime(date('Y').'-'.(date('m')+1).'-1')-86400;//该月的月末时间戳
// echo date('Y-m-d',$end);

$start = strtotime(date('Y').'-'.date('m').'-01');//该月的月初时间戳
$end = strtotime(date('Y').'-'.(date('m')+1).'-01')-86400;//该月的月末时间戳
// $CommisionFee = $salesend->where('createTime','>',)->select();
$salesend = Db::name('salesend');
$a = 139.42;
echo round($a);
$sql = "SELECT sum(CommissionFee) FROM `jingo_salesend` where salesendID = $a and UNIX_TIMESTAMP(`createTime`) > $start and UNIX_TIMESTAMP(`createTime`) < $end ";
$date = Db::query($sql);
// $date = $salesend->where($sql)->sum('CommissionFee');
// $CommissionFee = $salesend->where('salesendID',$value['userId'])->field(UNIX_TIMESTAMP(`createTime`))->select();
header("content-type:text/html; charset=utf-8");
            echo "<pre>";
            print_r($date);die;


?>