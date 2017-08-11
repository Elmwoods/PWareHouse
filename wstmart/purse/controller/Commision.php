<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15 0015
 * Time: 上午 09:18
 */

namespace wstmart\purse\controller;

use think\Controller;
use think\Db;

class Commision
{

    //判断手机号码的归属地
    public function phone(){

        if(preg_match("/^1[34578]{1}\d{9}$/",$_GET['p'])){
            $apiurl = 'http://apis.juhe.cn/mobile/get';
            $params = array(
                'key' => 'b4b88a8ffc09e2fd3f24251ee19fa168', //您申请的手机号码归属地查询接口的appkey
                'phone' => $_GET['p'] //要查询的手机号码
            );

            $paramsString = http_build_query($params);

            $content = @file_get_contents($apiurl.'?'.$paramsString);
            $result = json_decode($content,true);
            if($result['error_code'] == '0'){
                // dump($result);
                // echo "省份：".$result['result']['province']."\r\n";
                // echo "城市：".$result['result']['city']."\r\n";
                // echo "区号：".$result['result']['areacode']."\r\n";
                // echo "邮编：".$result['result']['zip']."\r\n";
                // echo "运营商：".$result['result']['company']."\r\n";
                // echo "类型：".$result['result']['card']."\r\n<br>";
                return json_encode($result['result'],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }else{
                echo $result['reason']."(".$result['error_code'].")";
            }
        }else{
            echo "不是手机号码";
        }

    }

    /**
     * 查看佣金
     * @param post.userId 经销终端id
    */
    public function commision(){
        if(input('post.')){
            $comm = Db::table('jingo_salesend')->alias('a')->join('jingo_users b','a.userID = b.userId')->field('a.*,b.userName,b.userPhoto,b.userPhone')->where('a.salesendID',input('post.userId'))->select();
            $i=0;
//            dump($comm);
            $user = Db::name('users')->where('salesendID',input('post.userId'))->select();
//        dump($user);
            $us = [];
            foreach($user as $val){
                $money = Db::name('salesend')->where('userID',$val['userId'])->order('createTime desc')->find();
//            dump($money);
                if($money){
                    $us[$i] = $money;
                }else{
                    $value = [
                        "id"=> "",
                        "salesendID"=> $val['userId'],
                        "userID"=> "",
                        "isProvince"=> "",
                        "CommissionFee"=> "",
                        "Province"=> "",
                        "TotalCommission"=> "",
                        "createTime"=> ""
                    ];
                    $us[$i] = $value;
                }

                $us[$i]['userName']=$val['userName'];
                $us[$i]['userPhone']=$val['userPhone'];
                $us[$i]['userPhoto']=$val['userPhoto'];
                $i++;
            }
            $result = array_merge($us, $comm);
            $temp = [];
            $temp2 = [];
            //二维数组去掉重复值,并保留键值
            foreach ($result as $k=>$v){
                $v=join(',',$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
                $temp[$k]=$v;
            }
            $temp=array_unique($temp); //去掉重复的字符串,也就是重复的一维数组
            foreach ($temp as $k => $v){
                $array=explode(',',$v); //再将拆开的数组重新组装
                //下面的索引根据自己的情况进行修改即可
                $temp2[$k]['id'] =$array[0];
                $temp2[$k]['salesendID'] =$array[1];
                $temp2[$k]['userID'] =$array[2];
                $temp2[$k]['isProvince'] =$array[3];
                $temp2[$k]['CommissionFee'] =$array[4];
                $temp2[$k]['Province'] =$array[5];
                $temp2[$k]['TotalCommission'] =$array[6];
                $temp2[$k]['createTime'] =$array[7];
                $temp2[$k]['userName'] =$array[8];
                $temp2[$k]['userPhone'] =$array[9];
                $temp2[$k]['userPhoto'] =$array[10];
            }
            return json_encode(array('result' => 'success', 'value' => $temp2));
        }else{
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }
    }

    /**
     * 查看用户的经销终端
     * @param post.userId 用户id
     */
    public function salesend(){
        if(input('post.')){
            //查看当前用户的信息
            $user = Db::name('users')->find(input('post.userId'));
//            $salesend = Db::table('jingo_salesend')->alias('a')->join('jingo_users b','a.salesendID = b.userId')->field('b.*')->where('a.userId',input('post.userId'))->select();
            $salesend = Db::name('users')->where('userId',$user['salesendID'])->select();
            return json_encode(array('result' => 'success', 'value' => $salesend));
        }else{
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }
    }

    /**
     * 查看该经销终端的用户
     * @param post.userId 经销终端id
    */
    public function user(){
        if(input('post.')){

//            $users = Db::table('jingo_salesend')->alias('a')->join('jingo_users b','a.userID = b.userId')->field('b.*')->where('a.salesendID',input('post.userId'))->select();
            $users = Db::name('users')->where('salesendID',input('post.userId'))->select();
//            dump($users);
            return json_encode(array('result' => 'success', 'value' => $users));
        }else{
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }
    }

    /**
     * 查看推荐人的收藏夹
     * @param post.userId 用户id
    */
    public function favoritegood(){
        if(input('post.')){
            $fav = Db::name('favorites')->where(['userId'=>input('post.userId'),'favoriteType'=>0])->select();
    //        dump($fav);
            $i = 0;
            foreach($fav as $val){
                $good = Db::name('goods')->where(['goodsId'=>$val['targetId']])->find();
    //            dump($good);
                $goods[$i] = $good;
                $i++;
            }
            return json_encode(array('result' => 'success', 'value' => $goods));
        }else{
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }
    }

    /**
     * 查看推荐人的店铺
     * @param post.userId 用户id
     */
    public function favoriteshop(){
        if(input('post.')){
            $fav = Db::name('favorites')->where(['userId'=>input('post.userId'),'favoriteType'=>1])->select();
    //        dump($fav);
            $i = 0;
            foreach($fav as $val){
                $shop = Db::name('shops')->where(['shopId'=>$val['targetId']])->find();
    //            dump($shop);
                $shops[$i] = $shop;
                $i++;
            }
            return json_encode(array('result' => 'success', 'value' => $shops));
        }else{
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }
    }

    /**
     * 获取经销终端
     * @param post.userId 用户id
     * @param post.id 经销终端id
     */
    public function salesender(){
        if(input('post.')){
            $user = Db::name('users')->find(input('post.userId'));
            if($user){
                if($user['salesendID'] != input('post.id')){

                    Db::name('users')->where(['userId'=>input('post.userId')])->update(['salesendID'=>input('post.id')]);
                    return urldecode(json_encode(array('result' => 'success', 'msg' => urlencode('已成功添加'))));
                }else{
                    return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('该用户已经是你的经销终端'))));
                }
            }else{
                return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('用户不存在'))));
            }
        }else{
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }
    }

    /**
     * 查看推荐人最近6个月的每月佣金总额
     * @param post.userId 用户id
     */
    public function total(){
        //查看当前时间的年月
        $year = date('Y');
        $month = date('m');
        $a = $this->getLastTimeArea($year,$month,6);
        //查看一个月时间的金额
        for($i = 0;$i<6;$i++){
            $start = $a[$i]['startMonth'];
            $end = $a[$i]['endMonth'];
            $b = Db::name('salesend')->where('createTime','between time',[$start,$end])->where('salesendID',input('post.userId'))->sum('commissionFee');
            $d['month']=$month;
            $d['money']=$b;
            $c[] = $d;
            $month--;
        }
//        dump($c);
        return urldecode(json_encode(array('result' => 'success','value'=>$c)));
    }

    /**
     * 查看最近几个月的时间
     * @param $year 给定的年份
     * @param $month 给定的月份
     * @param $legth 筛选的区间长度 取前六个月就输入6
     * @return array
     */
    public function getLastTimeArea($year,$month,$legth)
    {
        $num = 1;
        $timeAreaList = [];
        for($i=0;$i<$legth;$i++) {
            $temMonth = $month - $i;
            $temYear = $year;
            if ($temMonth <= 0) {
                $temYear = $year - $num;
                $temMonth = $temMonth + 12*$num;
                if ($temMonth <= 0) {
                    $temMonth += 12;
                    $temYear -= 1;
                }
            }
            if($temMonth+1 <= 12){
                $Month = $temMonth+1;
                $Year = $temYear;
            }else{
                $Month = $temMonth - 11;
                $Year = $temYear+1;
            }
//            echo $temYear;
            $startMonth = strtotime($temYear.'-'.$temMonth.'-01');//该月的月初时间戳
            $endMonth = strtotime($Year.'-'.$Month.'-01') - 86400;//该月的月末时间戳
            $res['startMonth'] = $temYear.'-'.$temMonth.'-01'; //该月的月初格式化时间
            $res['endMonth'] = date('Y-m-d',$endMonth);//该月的月末格式化时间
            $res['timeArea'] = implode(',',[$startMonth, $endMonth]);//区间时间戳
            $res['staretime'] = $startMonth;//区间开始时间戳
            $res['endtime'] = $endMonth;//区间结束时间戳
            $timeAreaList[] = $res;
        }
        return $timeAreaList;
    }

    /**
     * 判断是不是省级代理
     * @param post.userId用户id
     */
    public function isProvince(){
        if(input('post.userId')){
            //查看用户信息
            $user = Db::name('users')->where('userId',input('post.userId'))->find();
            if($user['isProvince'] == 1){
                return urldecode(json_encode(array('result' => 'success','value'=>1)));
            }else{
                return urldecode(json_encode(array('result' => 'success','value'=>0)));
            }
        }else{
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }

    }

    /**
     * 查看省级代理的经销终端信息
     * @param post.userId用户id
     */
    public function provinceUser(){
        if(input('post.userId')){
            //查看当前银行的信息
            $us = Db::name('users')->where('userId',input('post.userId'))->find();
            if($us['isProvince'] == 1){
                $i = 0;
                //查看当前省级代理的总佣金
                $comm = Db::name('salesend')->where('salesendID',input('post.userId'))->order('createTime desc')->find();
//            dump($comm);
                $user = Db::name('users')->where('salesendID',input('post.userId'))->select();
//        dump($user);
                $us = [];
                foreach($user as $val){
                    $money = Db::name('salesend')->where('salesendID',$val['userId'])->order('createTime desc')->find();
//            dump($money);
                    if($money){
                        $us[$i] = $money;
                    }else{
                        $value = [
                            "id"=> "",
                            "salesendID"=> $val['userId'],
                            "userID"=> "",
                            "isProvince"=> "",
                            "CommissionFee"=> "",
                            "Province"=> "",
                            "TotalCommission"=> "",
                            "createTime"=> ""
                        ];
                        $us[$i] = $value;
                    }

                    $us[$i]['userName']=$val['userName'];
                    $us[$i]['userPhone']=$val['userPhone'];
                    $us[$i]['userPhoto']=$val['userPhoto'];
                    $i++;
                }
                return urldecode(json_encode(array('result' => 'success','value'=>$us,'totalCommission'=>$comm['TotalCommission']?$comm['TotalCommission']:'')));
            }else{
                return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('你还不是省级代理'))));
            }

        }else{
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }

    }


}