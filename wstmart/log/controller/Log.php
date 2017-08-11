<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/27 0027
 * Time: 上午 09:45
 */

namespace wstmart\log\controller;

use think\Controller;
use think\Db;

class Log extends Controller
{
    public function index(){
        $log = Db::name('log_moneys')->field('targetId,dataSrc,moneyType,payType,money,payName,tradeNo,dataFlag')->where('dataFlag','1')->paginate(100);
//        dump($log);
        $page = $log->render();
        $this->assign('page',$page);
        $this->assign('log',$log);
        return $this->fetch('index/log');
    }

    //钱袋
    public function purse(){
        $log = Db::name('log_moneys')->field('targetId,dataSrc,moneyType,payType,money,payName,tradeNo,dataFlag')->where('dataFlag','1')->where('dataId','not in','10,11,12,13,18,20')->paginate(100);
//        dump($log);
        $page = $log->render();
        $this->assign('page',$page);
        $this->assign('log',$log);
        return $this->fetch('index/log');
    }

    //金豆
    public function imazamox(){
        $log = Db::name('log_moneys')->field('targetId,dataSrc,moneyType,payType,money,payName,tradeNo,dataFlag')->where('dataFlag','1')->where('dataId','in','10,11,12,13,18,20')->paginate(100);
//        dump($log);
        $page = $log->render();
        $this->assign('page',$page);
        $this->assign('log',$log);
        return $this->fetch('index/log');
    }

    //消费
    public function xiaofei(){
        $log = Db::name('log_moneys')->field('targetId,dataSrc,moneyType,payType,money,payName,tradeNo,dataFlag')->where('dataFlag','1')->where('dataSrc','消费')->paginate(100);
//        dump($log);
        $page = $log->render();
        $this->assign('page',$page);
        $this->assign('log',$log);
        return $this->fetch('index/log');
    }

    //提现
    public function tixian(){
        $log = Db::name('log_moneys')->field('targetId,dataSrc,moneyType,payType,money,payName,tradeNo,dataFlag')->where('dataFlag','1')->where('dataId','in','7,11')->paginate(100);
//        dump($log);
        $page = $log->render();
        $this->assign('page',$page);
        $this->assign('log',$log);
        return $this->fetch('index/log');
    }

    //查出两个二维数组的不同值
    public function arrDiff($arrayA,$arrayB){
        foreach ($arrayA as $v)
        {
            $v = join(",",$v);  //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
            $temp[] = $v;
        }
        foreach ($arrayB as $k=>$v)
        {
            $v = join(",",$v);  //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
            $temp1[] = $v;
        }
        $temp2 = array_diff($temp,$temp1);    //获取不同的一位数组
        $temp3 = array_diff($temp1,$temp);    //获取不同的一位数组
        $temp = array_merge($temp2,$temp3);   //将数组合并
        foreach ($temp as $k => $v)
        {
            $temp[$k] = explode(",",$v);   //再将拆开的数组重新组装
        }
        return $temp;
    }

    //从csv文件里面读取数据到数组
    public function csvArr(){
        $file = fopen('write.csv','r');
        $data1 = array();
        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
//            dump($data);
            foreach($data as $k=>$v){
                $data[$k] = mb_convert_encoding($v,'utf-8','gb2312');//将乱码转换
            }
            if(count($data)>5){
                $data1 = $data;
            }
//            dump($data1);
//            $data = mb_convert_encoding($data,'gb2312','utf-8');
            //print_r($data); //此为一个数组，要获得每一个数据，访问数组下标即可
            $goods_list[] = $data1;
            foreach( $goods_list as $k=>$v){//删除数组中的空值
                if( !$v )
                    unset( $goods_list[$k] );
            }
        }
        dump($goods_list);
        fclose($file);
//        return $goods_list;
    }

    //查看当前所有账户的总金额
    public function allMoney(){
        $money = Db::name('users')->sum('userMoney');
        dump($money);
    }

    //查看当前所有账户的金豆总数
    public function allImazamox(){
        $money = Db::name('users')->sum('imazamox_number');
        dump($money);
    }

    //查看玩金豆子游戏获得的金豆总额
    public function joy(){
        $money = Db::name('joy_collect')->sum('GPN');
        //判断文件是否存在
        if(file_exists('imazamox.txt')){
//            echo '存在';
            //逐行读取txt文件内容
            $handle = @fopen("imazamox.txt", "r");

            $arr = array();

            if ($handle) {
                while (!feof($handle)) {
                    $item = fgets($handle, 4096);
                    $arr[] = $item;
                }
                fclose($handle);

                header("Content-type:text/html;charset=utf-8");

                echo"<pre>";
                print_r($arr);
                echo"</pre>";
                //截取字符串
                $a = 0;
                foreach($arr as $v){
                    $imazamox = substr($v,-10,5);
                    $a=$a+$imazamox;
                }
                dump($a);
            } else {
                echo "文件错误！";
            }
        }
        dump($money);
        dump($a+$money);
    }

    //查看商城一天的支付宝充值、提现记录
    public function alipay(){
        $log = Db::name('log_moneys')->field('targetId,dataSrc,moneyType,payType,money,payName,tradeNo,dataFlag')->where('dataFlag','1')->where('payType','1')->paginate(100);
//        dump($log);
        $page = $log->render();
        $this->assign('page',$page);
        $this->assign('log',$log);
        return $this->fetch('index/log');
    }

    //查看个人账户的余额与资金流水余额是否相符
    public function balance(){
        //查看当前用户的资金余额
        $userMoney = Db::name('users')->field('userMoney')->where('userId',$_GET['id'])->find();
        //查看当前用户收入的资金
        $income = Db::name('log_moneys')->where('targetId',$_GET['id'])->where('moneyType',1)->where('dataId','not in','10,11,12,13,18,20')->sum('money');
        //查看当前用户支出的资金
        $pay = Db::name('log_moneys')->where('targetId',$_GET['id'])->where('moneyType',0)->where('dataId','not in','10,11,12,13,18,20')->sum('money');
        //查看当前用户的资金总额
        $money = $income - $pay;
        $a = [
            'income'=>$income,
            'pay'=>$pay,
            'money'=>$money,
            'userMoney'=>$userMoney['userMoney']
        ];
        dump($a);
    }

    //查看个人金豆总额与流水余额是否相符
    public function balance1(){
        //查看当前用户的金豆余额
        $userMoney = Db::name('users')->field('imazamox_number')->where('userId',$_GET['id'])->find();
        //查看当前用户收入的资金
        $income = Db::name('log_moneys')->where('targetId',$_GET['id'])->where('moneyType',1)->where('dataId','in','10,11,12,13,18,20')->sum('money');
        $joy = Db::name('joy_collect')->where('userId',$_GET['id'])->sum('GPN');//金豆子游戏
        $income = $income + $joy;
        //查看当前用户支出的资金
        $pay = Db::name('log_moneys')->where('targetId',$_GET['id'])->where('moneyType',0)->where('dataId','in','10,11,12,13,18,20')->sum('money');
        //查看当前用户的资金总额
        $money = $income - $pay;
        $a = [
            'joy'=>$joy,
            'income'=>$income,
            'pay'=>$pay,
            'money'=>$money,
            'userMoney'=>$userMoney['imazamox_number']
        ];
        dump($a);
    }
}