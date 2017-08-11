<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/14 0014
 * Time: 上午 09:19
 */

namespace wstmart\purse\controller;

use think\Db;
use wstmart\purse\model\Red as r;

class Red
{
    public function __construct(){
        $r = new r();
        $reds = Db::name('red_send')->where('status',0)->select();
        foreach($reds as $val){
//            dump($val);
            if(strtotime($val['createTime'])<=(time()-86400)){
                //计算红包剩余余额
                $money = Db::name('red_receive')->where('redId',$val['id'])->sum('receiveAmount');
                $a = $val['redAmount'];
                $balance = (float)$a-(float)$money;
//                    dump($balance);
                if($balance >= 0){
                    //查看用户信息
                    $user = Db::name('users')->where(['userId'=>$val['senderId']])->find();
//                    dump($user);
                    // 启动事务
                    \think\Db::startTrans();
                    try{
                    //将剩余余额回流
                    Db::name('users')->where('userId',$val['senderId'])->update(['userMoney' => ['exp','userMoney+'.$balance]]);
                    //将信息添加到资金流水
                    $tradeNo = $r->makeSn($user['userId']);
                    $insert = [
                        'userType'=>$user['userType'],
                        'userId'=>$user['userId'],
                        'dataId'=>21,
                        'dataSrc'=>'撒金豆退还',
                        'moneyType'=>1,
                        'money'=>$balance,
                        'tradeNo'=>$tradeNo,
                        'payName'=>$user['loginName'],
                        'payType'=>0,
                        'desc'=>'24小时内红包没有领完'
                    ];
                    /*dump($val['senderId']);
                    dump($user);
                    dump($insert);die;*/
                    $r->addLogMoney($insert);
                    //将表状态变成完成
                    Db::name('red_send')->where(['id'=>$val['id']])->update(['status'=>1]);
                        // 提交事务
                        \think\Db::commit();
                    } catch (\Exception $e) {
                        // 回滚事务
                        \think\Db::rollback();
                    }
                }
            }
        }
        return json(array('result' => 'success', 'value' => $reds));
    }

    public function redPac() {
        /*$fp = @fopen("D:/wamp/www/test.txt", "a+");
        fwrite($fp, "自动播报时间：\n" . date("Y-m-d H:i:s"));
        fclose($fp);*/
        $total      =   100;         //红包总金额
        $num        =   21;         //红包个数
        $redPacket  =   $this -> packet($total, $num);
        echo '<pre />';
        shuffle($redPacket);
        print_r($redPacket);
    }
    /**
     * 随机红包函数
     * @param $total 红包总金额
     * @param $num  红包个数
     * @return array
    */
    public function packet($total, $num) {
        $min        =   0.01;       //红包最小值
        $redPacket  =   [];         //初始化红包数组
        for ($i=1;$i<$num;$i++)
        {
            $safe_total     =       ($total-($num-$i)*$min)/($num-$i);          //随机数安全上限
            $money          =       mt_rand($min*100,$safe_total*100)/100;      //红包值
            $total          -=      $money;
            $redPacket[$i]  =       sprintf('%.2f', $money);
        }
        $redPacket[$num]    =       sprintf('%.2f', $total);
        return $redPacket;
    }

    /**
     * 发红包
     * @param post.total 红包总金额
     * @param post.num  红包个数
     * @param post.userId  发红包的id
     * @param post.redType  红包类型
     * @param post.desc  红包备注
     * @param post.ids  接收人id
     * @param post.groupId  群id
     * @return json
     */
    public function redPacket() {
        if(input('post.')){
            $total      =   input('post.total');         //红包总金额
            $num        =   input('post.num');         //红包个数

            //查找用户信息
            $users = Db::name('users')->find(input('post.userId'));
            if($users['userMoney']>=$total){
                if(input('post.redType')==1){
                    $ids = input('post.ids');
                }else{
                    if(strpos(input('post.ids'), ',')){
                        $ids = substr(input('post.ids'),0,strlen(input('post.ids'))-1);
                    }else{
                        //查看当前群用户的id
                        $ids = '';
                       /* $b = 0;
                        $a = Db::name('user_groupmembers')->field('userId')->where(['groupID'=>input('post.ids')])->select();
                        shuffle($a);
                        foreach($a as $val){
                            if($b < $num){

                                $ids .= ','.$val['userId'];
                                $b += 1;
                            }
                        }
                        $ids = substr($ids,1);
//                    dump($ids);die;*/
                    }
                }


                $redPacket  =   $this -> packet($total, $num);

                shuffle($redPacket);    //将数组随机排序
                $am = implode(',',$redPacket);
//                dump($am);die;
                $r = new r();
                //将红包信息添加到数据库
                $r->addRedSend(input('post.userId'),input('post.redType'),input('post.num'),input('post.total'),textEncode(input('post.desc')),$ids,$am,input('post.groupId'));

                //将用户的红包金额扣除
                Db::name('users')->where('userId',input('post.userId'))->update(['userMoney' => ['exp','userMoney-'.input('post.total')]]);
                $tradeNo = $r->makeSn(input('post.userId'));
                //将信息添加到资金流水
                $insert = [
                    'userType'=>$users['userType'],
                    'userId'=>$users['userId'],
                    'dataId'=>19,
                    'dataSrc'=>'撒金豆',
                    'moneyType'=>0,
                    'money'=>input('post.total'),
                    'tradeNo'=>$tradeNo,
                    'payName'=>'',
                    'payType'=>0,
                    'desc'=>textEncode(input('post.desc'))
                ];
//                dump($insert);die;
                $r->addLogMoney($insert);
                //查看红包ID
                $c = Db::name('red_send')->where(['senderId'=>input('post.userId'),'redType'=>input('post.redType')])->order('createTime DESC')->find();

                return json_encode(array('result' => 'success', 'value' => $c['id']));
            }else{
                return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('账户余额不足'))));
            }
        } else {
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }


    }

    /**
     * 抢红包
     * @param post.id 红包id
     * @param post.userId  抢红包的id
     * @param post.desc  红包备注
     * @return json
     */
    public function redReceive(){
        if(input('post.')){
            //查看红包信息
            $red = Db::name('red_send')->find(input('post.id'));

            //查看抢到红包信息
            $redUser = Db::name('red_receive')->where(['redId'=>input('post.id'),'receiver'=>input('post.userId')])->find();
//            dump($redUser);die;
//            dump($red['senderId']);die;
            //查看发红包的用户名、头像
            $us = Db::name('users')->find($red['senderId']);
            $sql = "SELECT a.*,b.userPhoto,b.userName FROM `jingo_red_receive` as a left join `jingo_users` as b on a.receiver=b.userId WHERE redId=".input('post.id')."";
            $user = Db::query($sql);
            //查看已领取金额
            $sum = Db::name('red_receive')->where('redId',input('post.id'))->sum('receiveAmount');
            //查看已领取的红包的个数
            $count = Db::name('red_receive')->where('redId',input('post.id'))->count('receiveAmount');
//            dump($us);die;
            //查看该用户是否已经领取红包
            if($count < $red['redNum']){
//                $user['max'] = '';
                $max = 0;
            }else{
                //查找当前红包的手气最佳
                $max = Db::name('red_receive')->where('redId',input('post.id'))->max('receiveAmount');
                $max = Db::name('red_receive')->where(['receiveAmount'=>$max,'redId'=>input('post.id')])->find();
                $max = $max['receiver'];
//                $user['max'] = $max;
            }
            if(strtotime($red['createTime'])>=(time()-86400) && $count = $red['redNum']){
                $a = Db::name('red_receive')->where(['redId'=>input('post.id'),'receiver'=>input('post.userId')])->find();
                if($a){
                    return urldecode(json_encode(array('result' => 'error','state'=>4, 'msg' => urlencode('你已领取该红包'),'senderName'=>$us['userName'],'senderPhoto'=>$us['userPhoto'],'count'=>$count,'sum'=>$sum,'amount'=>$red['redAmount'],'redType'=>$red['redType'],'value' => $user,'status'=>$red['status'],'redNum'=>$red['redNum'],'redAmount'=>$redUser['receiveAmount'],'max'=>$max)));
                } else {
                    //查看已领取的红包的个数
                    $count = Db::name('red_receive')->where('redId',input('post.id'))->count('receiveAmount');
                    $userId = explode(',',$red['userId']);
    //                dump($userId);
                    $uid = input('post.userId');
    //                dump($red['userId']);
                    if($count < $red['redNum']){
                        if(in_array($uid,$userId) || empty($red['userId'])){
                            if($red['status'] == 0){
                                //将字符串变为数组
                                $am = explode(',',$red['amount']);
//                            dump($am);die;
//                            $n = 0;
//                            dump($am[$red['frequency']]);die;
                                $r = new r();
                                $r->addRedReceive(input('post.id'),input('post.userId'),$am[$red['frequency']]);

                                //将用户的红包金额增加
                                Db::name('users')->where('userId',input('post.userId'))->update(['imazamox_number' => ['exp','imazamox_number+'.$am[$red['frequency']]]]);
                                $users = Db::name('users')->find(input('post.userId'));
//                            dump(input('post.userId'));
//        dump($users);
                                $sql = "SELECT a.*,b.userPhoto,b.userName FROM `jingo_red_receive` as a left join `jingo_users` as b on a.receiver=b.userId WHERE redId=".input('post.id')."";
                                $user = Db::query($sql);
                                //查看已领取的红包的个数
                                $count = Db::name('red_receive')->where('redId',input('post.id'))->count('receiveAmount');
                                //查看已领取金额
                                $sum = Db::name('red_receive')->where('redId',input('post.id'))->sum('receiveAmount');
                                $tradeNo = $r->makeSn(input('post.userId'));

                                //查看抢到红包信息
                                $redUser = Db::name('red_receive')->where(['redId'=>input('post.id'),'receiver'=>input('post.userId')])->find();
                                //将抢红包添加到流水表
                                $insert = [
                                    'userType'=>$users['userType'],
                                    'userId'=>input('post.userId'),
                                    'dataId'=>20,
                                    'dataSrc'=>'领金豆',
                                    'moneyType'=>1,
                                    'money'=>$am[$red['frequency']],
                                    'tradeNo'=>$tradeNo,
                                    'payName'=>'',
                                    'payType'=>0,
                                    'desc'=>textEncode(input('post.desc'))
                                ];
//                            dump($insert);die;
                                $r->addLogMoney($insert);
                                //判断红包是否领完
                                if($count>=$red['redNum']){

                                    //将红包的状态改变
                                    Db::name('red_send')->where('id',input('post.id'))->update(['status'=>1]);

                                    //查找当前红包的手气最佳
                                    $max = Db::name('red_receive')->where('redId',input('post.id'))->max('receiveAmount');
                                    $max = Db::name('red_receive')->where(['receiveAmount'=>$max,'redId'=>input('post.id')])->find();
                                    $max = $max['receiver'];
                                } else{
                                    $max = 0;
                                }
                                //查看红包信息
                                $red = Db::name('red_send')->find(input('post.id'));
                                //将红包的次数加1
                                Db::name('red_send')->where(['id'=>input('post.id')])->update(['frequency' => ['exp','frequency+1']]);
                                return json_encode(array('result' => 'success','state'=>0, 'value' => $user,'count'=>$count,'sum'=>$sum,'senderName'=>$us['userName'],'senderPhoto'=>$us['userPhoto'],'amount'=>$red['redAmount'],'redType'=>$red['redType'],'status'=>$red['status'],'redNum'=>$red['redNum'],'redAmount'=>$redUser['receiveAmount'],'max'=>$max));


                                //                    dump(input('post.userId'));die;
                            }else{
                                return urldecode(json_encode(array('result' => 'error','state'=>5, 'msg' => urlencode('该红包已失效'),'senderName'=>$us['userName'],'senderPhoto'=>$us['userPhoto'],'count'=>$count,'sum'=>$sum,'amount'=>$red['redAmount'],'redType'=>$red['redType'],'value' => $user,'status'=>$red['status'],'redNum'=>$red['redNum'],'redAmount'=>$redUser['receiveAmount']?$redUser['receiveAmount']:'','max'=>$max)));
                            }

                        }else{
                            return urldecode(json_encode(array('result' => 'error','state'=>3, 'msg' => urlencode('你不能领取该红包'),'senderName'=>$us['userName'],'senderPhoto'=>$us['userPhoto'],'count'=>$count,'sum'=>$sum,'amount'=>$red['redAmount'],'redType'=>$red['redType'],'value' => $user,'status'=>$red['status'],'redNum'=>$red['redNum'],'redAmount'=>$redUser['receiveAmount']?$redUser['receiveAmount']:'','max'=>$max)));
                        }
                    } else {
                        //将红包的状态改变
                        Db::name('red_send')->where('id',input('post.id'))->update(['status'=>1]);

                        //查看红包信息
                        $red = Db::name('red_send')->find(input('post.id'));
                        return urldecode(json_encode(array('result' => 'error','state'=>2, 'msg' => urlencode('红包已领完'),'senderName'=>$us['userName'],'senderPhoto'=>$us['userPhoto'],'count'=>$count,'sum'=>$sum,'amount'=>$red['redAmount'],'redType'=>$red['redType'],'value' => $user,'status'=>$red['status'],'redNum'=>$red['redNum'],'redAmount'=>$redUser['receiveAmount']?$redUser['receiveAmount']:'','max'=>$max)));
                    }
                }
            }else{
                return urldecode(json_encode(array('result' => 'error','state'=>1, 'msg' => urlencode('该红包已超过24小时'),'senderName'=>$us['userName'],'senderPhoto'=>$us['userPhoto'],'count'=>$count,'sum'=>$sum,'amount'=>$red['redAmount'],'redType'=>$red['redType'],'value' => $user,'status'=>$red['status'],'redNum'=>$red['redNum'],'redAmount'=>$redUser['receiveAmount']?$redUser['receiveAmount']:'','max'=>$max)));
            }

        } else {
            return urldecode(json_encode(array('result' => 'error','state'=>2, 'msg' => urlencode('请传参'))));
        }

    }

    /**
     * 获取当前红包的信息
     * @param post.id 红包id
    */
    public function red(){
        if(input('post.id')) {
            //查看红包信息
            $red = Db::name('red_send')->find(input('post.id'));
//            dump($red['senderId']);die;
            //查看发红包的用户名、头像
            $us = Db::name('users')->find($red['senderId']);
            $sql = "SELECT a.*,b.userPhoto,b.userName FROM `jingo_red_receive` as a left join `jingo_users` as b on a.receiver=b.userId WHERE redId=" . input('post.id') . "";
            $user = Db::query($sql);
            //查找当前红包的手气最佳
            $max = Db::name('red_receive')->where('redId', input('post.id'))->max('receiveAmount');
            $max = Db::name('red_receive')->where(['receiveAmount' => $max, 'redId' => input('post.id')])->find();
            //查看已领取的红包的个数
            $count = Db::name('red_receive')->where('redId', input('post.id'))->count('receiveAmount');
            //查看已领取金额
            $sum = Db::name('red_receive')->where('redId', input('post.id'))->sum('receiveAmount');
            return json_encode(array('value' => $user, 'max' => $max, 'count' => $count, 'sum' => $sum, 'senderName' => $us['userName'], 'senderPhoto' => $us['userPhoto']));
        }else{
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }
    }

    /**
     * 获取当前用户已抢到的红包的信息
     * @param post.userId 用户id
     */
    public function myRed(){
        if(input('post.userId')) {
    //        $red = Db::name('red_receive')->where('receiver',input('post.userId'))->select();
            //查看当前用户的信息
            $us = Db::name('users')->where('userId',input('post.userId'))->find();
            $i = 0;
            $a = 0;
            $red = Db::table('jingo_red_receive')->alias('a')->join('jingo_red_send b','a.redId = b.id')->field('a.*,b.senderId,b.redType')->where('a.receiver',input('post.userId'))->select();
            foreach($red as $value){
                $user = Db::name('users')->find($value['senderId']);
                $red[$i]['userPhoto'] = $user['userPhoto'];
                $red[$i]['userName'] = $user['userName'];
                $i++;
                //查找当前红包的手气最佳
                $max = Db::name('red_receive')->where('redId',$value['redId'])->max('receiveAmount');
                $max = Db::name('red_receive')->where(['receiveAmount'=>$max,'redId'=>$value['redId']])->find();
                $max = $max['receiver'];
                if($max == input('post.userId')){
                    $a++;
                }
            }
    //        dump($user);
            $sum = Db::name('red_receive')->where('receiver',input('post.userId'))->sum('receiveAmount');
            $count = Db::name('red_receive')->where('receiver',input('post.userId'))->count('receiveAmount');
            return json_encode(array('result' => 'success','value' => $red,'count'=>$count,'sum'=>$sum,'lucky'=>$a,'userName'=>$us['userName'],'userPhoto'=>$us['userPhoto']));
        }else{
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }
    }

    /**
     * 获取当前用户发出去的红包的信息
     * @param post.userId 用户id
     */
    public function mySendRed(){
        if(input('post.userId')){
    //        $red = Db::name('red_receive')->where('receiver',input('post.userId'))->select();
            $user = Db::name('users')->find(input('post.userId'));
            $red = Db::name('red_send')->field('id,senderId,redType,redNum,redAmount,redDesc,createTime,frequency')->where('senderId',input('post.userId'))->select();

            $i = 0;
            foreach($red as $val){
                if(strtotime($val['createTime'])>=(time()-86400)){
                    $red[$i]['overtime'] = 0;
    //                dump($val);
                }else{
                    if($val['redNum']>$val['frequency']){
                        $red[$i]['overtime'] = 1;
                    }else{
                        $red[$i]['overtime'] = 0;
                    }
                }

                $i++;
            }
    //        dump($user);
            $sum = Db::name('red_send')->where('senderId',input('post.userId'))->sum('redAmount');
            $count = Db::name('red_send')->where('senderId',input('post.userId'))->count('redAmount');
            return json_encode(array('value' => $red,'count'=>$count,'sum'=>$sum,'userPhoto'=>$user['userPhoto'],'userName'=>$user['userName']));
        }else{
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }
    }


    /**
     * 判断是否知道已经发了红包
     * @endTime 退出聊天界面时的时间
     * @startTime 进入聊天界面时的时间
     * @userId 用户id
     * @type 聊天类型 1单聊 2群聊
     * @id 单聊为对方id,群聊为群聊id
    */
    public function knowRed(){
        if(input('post.')){
            //判断退出时间是否存在
            if(input('post.endTime')){
                //判断是单聊或者群聊
                if(input('post.type')==1){
                    //判断是否有不知道的红包
                    $red = Db::name('red_send')->where(['senderId'=>input('post.id'),'userId'=>input('post.userId')])->where('knowRed',0)->select();
//                    dump($red);
                    if($red){
                        //将数据库修改为已知道该红包
                        Db::name('red_send')->where(['senderId'=>input('post.id'),'userId'=>input('post.userId')])->where('knowRed',0)->update(['knowRed'=>input('post.userId')]);
                        return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('变成已知道该红包'))));
                    }else{
                        return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('还没有不知道的红包'))));
                    }
                }elseif(input('post.type')==2){
                    //判断该群里是否有该用户不知道的红包
                    $sql = "select * from jingo_red_send where groupId=".input('post.id')." And knowRed not like '%".input('post.userId')."%'";
                    $red = Db::query($sql);
//                    dump($red);die;
                    if($red){
//                        dump($red);
                        foreach($red as $va){
                            //将数据库修改为已知道该红包
                            Db::name('red_send')->where('id',$va['id'])->update(['knowRed'=>$va['knowRed'].','.input('post.userId')]);
                        }
                        return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('有不知道的红包'))));
                    }else{
                        return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('还没有不知道的红包'))));
                    }
                }else{
                    return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传入类型'))));
                }
            }elseif(input('post.startTime')){  //进入聊天界面时间
                //判断是单聊或者群聊
                if(input('post.type')==1){
                    //判断是否有不知道的红包
                    $red = Db::name('red_send')->where(['senderId'=>input('post.id'),'userId'=>input('post.userId')])->where('knowRed',0)->select();
                    if($red){
                        //将数据库修改为已知道该红包
                        Db::name('red_send')->where(['senderId'=>input('post.id'),'userId'=>input('post.userId')])->where('knowRed',0)->update(['knowRed'=>input('post.userId')]);
                        return urldecode(json_encode(array('result' => 'success', 'msg' => urlencode('有不知道的红包'))));
                    }else{
                        return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('还没有不知道的红包'))));
                    }
                }elseif(input('post.type')==2){
                    //判断该群里是否有该用户不知道的红包
//                    $red = Db::name('red_send')->where('groupId',input('post.id'))->whereNotLike('knowRed','%'.input('post.userId').'%')->select();
                    $sql = "select * from jingo_red_send where groupId=".input('post.id')." And knowRed not like '%".input('post.userId')."%'";
//                    dump($sql);die;
                    $red = Db::query($sql);
//                    dump($red);die;
                    if($red){
//                        dump($red);
                        foreach($red as $va){
                            //将数据库修改为已知道该红包
                            Db::name('red_send')->where('id',$va['id'])->update(['knowRed'=>$va['knowRed'].','.input('post.userId')]);
                        }
                        return urldecode(json_encode(array('result' => 'success', 'msg' => urlencode('有不知道的红包'))));
                    }else{
                        return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('还没有不知道的红包'))));
                    }
                }else{
                    return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传入类型'))));
                }
            }else{
                return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传入时间'))));
            }

        }else{
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }
    }

    /**
     * 判断红包是否超过24小时,如果超过就删除数据
    */
    /*public function overtime(){
        $time = Db::name('red_send')->select();
//        dump($time);
        foreach($time as $val){
            if(strtotime($val['createTime'])<=(time()-86400)){
//                dump($val);
                Db::name('red_send')->where('id',$val['id'])->delete();
            }
        }
    }*/

    /**
     * 获取当前用户的信息
     * @param userId 用户id
     * @return json
     */
    public function user(){
        //跨域
        header('content-type:application:json;charset=utf8');
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        if(input('post.userId')){
            $user = Db::name('users')->field('userPhoto,userName,userScore,imazamox_number,pea')->where('userId',input('post.userId'))->find();
            if($user['imazamox_number']<(($user['pea']-1)*3000)){
                $pea = floor($user['imazamox_number']/3000)+1;
                //将豆苗个数减少
                Db::name('users')->where('userId',input('post.userId'))->update(['pea'=>$pea]);
            }
            //判断金豆子游戏哪个已经玩了
            $user1 = Db::name('user_joy')->where('userId',input('post.userId'))->find();
            $user['joy1'] = $user1['joy1']?$user1['joy1']:0;
            $user['joy2'] = $user1['joy2']?$user1['joy2']:0;
            $user['joy3'] = $user1['joy3']?$user1['joy3']:0;
            return json_encode($user);
        }else{
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }
    }

    /**
     * 获取当前用户的朋友的信息
     * @param userId 用户id
     * @return json
    */
    public function friends(){
        //跨域
        header('content-type:application:json;charset=utf8');
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        if(input('post.userId')){
            $friends = Db::table('jingo_user_friends')->alias('a')->join('jingo_users b','a.friendId = b.userId')->field('b.userPhoto,b.userName,a.friendId,b.imazamox_number,b.pea')->where(['isAgreed'=>1,'isDelete'=>0])->where('a.userId',input('post.userId'))->whereOr('b.userId',input('post.userId'))->order('b.imazamox_number desc')->select();
            foreach($friends[0] as $k => $v){
                $arr_inner_key[]= $k;   //先把二维数组中的内层数组的键值记录在在一维数组中
            }
            foreach ($friends as $k => $v){
                $v =join(",",$v);    //降维 用implode()也行
                $temp[$k] =$v;      //保留原来的键值 $temp[]即为不保留原来键值
            }
            $temp =array_unique($temp);    //去重：去掉重复的字符串
            foreach ($temp as $k => $v){
                $a = explode(",",$v);   //拆分后的重组 如：Array( [0] => james [1] => 30 )
                $arr_after[]= array_combine($arr_inner_key,$a);  //将原来的键与值重新合并
            }
        /*$user = Db::name('users')->where('userId',input('post.userId'))->find();
        $friends[] = $user;*/
            return json_encode($arr_after);
        }else{
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }
    }

    /**
     * 查看已开通的银行
     * @return json
    */
    public function bank(){
        //跨域
        header('content-type:application:json;charset=gbk');
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        $payments = model('common/payments')->getOnlinePayments();
//        dump($payments);
        $i = 0;
        foreach($payments as $payment){

            if($payment['isOnline']==1){
//                dump($payment['payCode']);
                $bank[$i]['payCode']=$payment['payCode'];
                $bank[$i]['payName']=$payment['payName'];
                $bank[$i]['payDesc']=$payment['payDesc'];
                $bank[$i]['photo']=$payment['photo'];
                $i++;
            }
        }
        return json_encode($bank);
    }

    /**
     * 金豆子游戏状态更新
     */
  public function gameUpdate(){
        //跨域
        header('content-type:application:json;charset=gbk');
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        $sql = "UPDATE `jingo_user_joy` SET `joy1`=0,`joy2`=0,`joy3`=0";
        Db::query($sql);

    }

    /**
     * 金豆子游戏帮好友浇水、除草、施肥
     * @param post.userId  用户id
     * @param post.friendId  好友id
     * @param post.joy1  游戏1
     * @param post.joy2  游戏2
     * @param post.joy3  游戏3
    */
    public function helpFriend(){
        //跨域
        header('content-type:application:json;charset=gbk');
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        if(input('post.userId')){
            $da = [
                'userId'=>input('post.friendId'),
                'friendId'=>input('post.userId'),
                'GPN'=>0.003,
                'createTime'=>time()
            ];
//            dump($da);die;
            // 启动事务
            \think\Db::startTrans();
            try{
                //将数据插入到收取好友金豆表
                Db::name('joy_collect')->insert($da);
                if(input('post.friendId')!=input('post.userId')){
                    //查看好友的游戏状态
//                    $friend = Db::name('user_joy')->where('userId',input('post.friendId'))->find();
                    //将好友游戏的状态改变
                    Db::name('user_joy')->where('userId',input('post.friendId'))->update(['joy1'=>input('post.joy1'),'joy2'=>input('post.joy2'),'joy3'=>input('post.joy3')]);
                    //将自己的金豆数改变
                    Db::name('users')->where('userId',input('post.userId'))->update(['imazamox_number'=>['exp','imazamox_number+0.003']]);

                    //将自己的user_joy表的金豆数改变
                    Db::name('user_joy')->where('userId',input('post.userId'))->update(['GPN'=>['exp','GPN+0.003']]);
                }else{
                    return json_encode(['result' => 'error']);
                }

                // 提交事务
                \think\Db::commit();
                return json_encode(['result' => 'success']);
            } catch (\Exception $e) {
                // 回滚事务
                \think\Db::rollback();
                return json_encode(['result' => 'error']);
            }
        }else{
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }
    }

    /**
     *查看当前一周好友被自己收取的金豆数
     * @param post.userId  用户id
     * @param post.friendId  好友id
     */
    public function seeCollect(){
        //跨域
        header('content-type:application:json;charset=gbk');
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        if(input('post.userId')){
            //当前日期
            $sdefaultDate = date("Y-m-d");
            //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
            $first=1;
            //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
            $w=date('w',strtotime($sdefaultDate));
            //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
            $week_start=date('Y-m-d',strtotime("$sdefaultDate -".($w ? $w - $first : 6).' days'));
            //本周结束时间戳
            $week_end=strtotime("$week_start +7 days");
            //本周开始时间戳
            $week_start = strtotime($week_start);
    //        dump($week_end);
            //查看自己收取好友的金豆数
            $GPN = Db::name('joy_collect')->where(['userId'=>input('post.friendId'),'friendId'=>input('post.userId')])->where('createTime','between',[$week_start,$week_end])->sum('GPN');
    //        dump($GPN);
            //查看好友收取自己的金豆数
            $GPN1 = Db::name('joy_collect')->where(['userId'=>input('post.userId'),'friendId'=>input('post.friendId')])->where('createTime','between',[$week_start,$week_end])->sum('GPN');
    //        dump($GPN1);
            return json_encode(['result' => 'success','GPN'=>$GPN,'GPN1'=>$GPN1]);
        }else{
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }
    }

    /**
     * 查看当前用户今天收取的金豆数
     * @param post.userId 用户id
    */
    public function seeImazamox(){
        //跨域
        header('content-type:application:json;charset=gbk');
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        if(input('post.userId')){
            //查看今天的时间戳区间
            $date = date('Y-m-d');
            $day_start = strtotime($date);
            $day_end = strtotime("$date+1 days");
            //查看自己收取的金豆数
            $GPN = Db::name('joy_collect')->where(['friendId'=>input('post.userId')])->where('createTime','between',[$day_start,$day_end])->sum('GPN');
            return json_encode(['result' => 'success','GPN'=>$GPN]);
        }else{
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }
    }

    /**
     * 清空数据表
    */
    public function del(){
        //设置时区，获取北京时间
        date_default_timezone_set('Asia/Shanghai');
        $fp = fopen('imazamox.txt','a+');
        //查看相同的id的总金额
        $logList = Db::name('joy_collect')->field('userId,sum(GPN)')->group('userId')->select();
//        dump($logList);die;
        foreach ($logList as $l){
            $info =" 用户id=" . $l['userId'] .',金豆数='.$l['sum(GPN)']."。\t\n";
            fwrite($fp, $info);
        }
        fwrite($fp, "\n");
        fclose($fp);
        $sql = "TRUNCATE jingo_joy_collect";
        Db::query($sql);
    }

    /**
     * 给朋友发消息
     * @param post.userId 用户id
     * @param post.friendId 朋友id
     * @param post.new 消息编号
    */
    public function news(){
        //跨域
        header('content-type:application:json;charset=gbk');
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        if(input('post.userId')){
            //将消息存入数据库
            $da = [
                'userId'=>input('post.friendId'),
                'friendId'=>input('post.userId'),
                'new'=>input('post.new')
            ];
            if(Db::name('joy_news')->insert($da)){
                return json_encode(['result' => 'success']);
            }else{
                return json_encode(['result' => 'error']);
            }
        }else{
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }
    }

    /**
     * 获取用户消息
     * @param post.userId用户id
    */
    public function playGame(){
        //跨域
        header('content-type:application:json;charset=gbk');
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        if(input('post.userId')){
            //查看用户豆苗个数
          /*  $pea = Db::name('users')->field('pea,imazamox_number')->where('userId',input('post.userId'))->find();
//                dump($pea);
//                $pea['pea'] +=1;
            if($pea['imazamox_number']>=$pea['pea']*3000){
                return json_encode(['result' => 'error','pea'=>$pea['pea']]);
            }else{*/
//                    dump($pea);die;
                //查看消息
                $new = Db::name('joy_news')->alias('a')->join('jingo_users b','a.friendId = b.userId')->field('a.new,a.userId,a.friendId,b.userName,b.userPhoto,b.userPhone')->where(['a.userId'=>input('post.userId'),'status'=>0])->select();
                //将该用户的消息改为完成
                Db::name('joy_news')->where(['userId'=>input('post.userId')])->update(['status'=>1]);
                return json_encode(['result' => 'success','value'=>$new]);
//            }

        }else{
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }
    }

    /**
     * 领取豆苗
     * @param post.userId用户id
     */
/*    public function pea(){
        //跨域
        header('content-type:application:json;charset=gbk');
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        if(input('post.')){
            $pea = Db::name('users')->where('userId',input('post.userId'))->update(['pea'=>['exp','pea+1']]);
//            dump($pea);
            if($pea){
                return json_encode(['result' => 'success']);
            }else{
                return json_encode(['result' => 'error']);
            }
        }else{
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }
    }*/

    /**
     * 晒太阳
     * @param post.userId用户id
     * @param post.progress 0询问1增加
     */
    public function bask(){
        //跨域
        header('content-type:application:json;charset=gbk');
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        if(input('post.')){
            //查看当前用户的晒太阳状态
            $bask = Db::name('joy_bask')->where('userId',input('post.userId'))->find();
//            dump($bask);
            if(input('post.progress') == 0){
                //判断是否存在
                if(empty($bask)){
                    return json_encode(['result' => 'success','value'=>0]);
                }
                //判断是否是同一天
                if(date('d',time()) == date('d',$bask['createTime'])){
                    $time = $bask['time'];
                }else{
                    $time = 0;
                }
                return json_encode(['result' => 'success','value'=>$time]);
            }elseif(input('post.progress') == 1){
                if($bask){
                    //判断是否是同一天
                    if(date('d',time()) == date('d',$bask['createTime'])){
                        $time = $bask['time']+1;
                    }else{
                        $time = 1;
                    }
                    if($time > 6){
                        return json_encode(['result' => 'error','value'=>6,'msg'=>'你今天已玩六次了']);
                    }
                    $in = [
                        'time'=>$time,
                        'createTime'=>time()
                    ];
                    //更新数据
                    if(Db::name('joy_bask')->where('userId',input('post.userId'))->update($in)){
                        //将自己的金豆数改变
                        Db::name('users')->where('userId',input('post.userId'))->update(['imazamox_number'=>['exp','imazamox_number+0.005']]);
                        //将信息添加到金豆子游戏里面
                        $data = [
                            'userId'=>input('post.userId'),
                            'friendId'=>input('post.userId'),
                            'GPN'=>0.005,
                            'createTime'=>time()
                        ];
                        Db::name('joy_collect')->insert($data);
                        //将自己的user_joy表的金豆数改变
                        Db::name('user_joy')->where('userId',input('post.userId'))->update(['GPN'=>['exp','GPN+0.005']]);
                        return json_encode(['result' => 'success','value'=>$in['time']]);
                    }else{
                        return json_encode(['result' => 'error','value'=>$in['time']-1]);
                    }
                }else{
                    $in = [
                        'userId'=>input('post.userId'),
                        'time'=>1,
                        'createTime'=>time()
                    ];
                    //将数据插入数据表
                    if(Db::name('joy_bask')->where('userId',input('post.userId'))->insert($in)){
                        //将自己的金豆数改变
                        Db::name('users')->where('userId',input('post.userId'))->update(['imazamox_number'=>['exp','imazamox_number+0.005']]);
                        //将信息添加到金豆子游戏里面
                        $data = [
                            'userId'=>input('post.userId'),
                            'friendId'=>input('post.userId'),
                            'GPN'=>0.005,
                            'createTime'=>time()
                        ];
                        Db::name('joy_collect')->insert($data);
                        //将自己的user_joy表的金豆数改变
                        Db::name('user_joy')->where('userId',input('post.userId'))->update(['GPN'=>['exp','GPN+0.005']]);
                        return json_encode(['result' => 'success','value'=>$in['time']]);
                    }else{
                        return json_encode(['result' => 'error','value'=>0]);
                    }
                }
            }else{
                return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
            }


        }else{
            return urldecode(json_encode(array('result' => 'error', 'msg' => urlencode('请传参'))));
        }
    }

}