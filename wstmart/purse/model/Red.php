<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/14 0014
 * Time: 下午 03:58
 */

namespace wstmart\purse\model;

use think\Db;

class Red
{
    //将信息添加到流水表
    public function addLogMoney($insert){
        $data = [
            'targetType' => $insert['userType'],
            'targetId' => $insert['userId'],
            'dataId' => $insert['dataId'],
            'dataSrc' => $insert['dataSrc'],
            'moneyType' => $insert['moneyType'],
            'money' => $insert['money'],
            'payName' => $insert['payName'],
            'tradeNo' => $insert['tradeNo'],
            'payType' => $insert['payType'],
            'dataFlag' => 1,
            'createTime' => date('Y-m-d H:i:s'),
            'endTime' => date('Y-m-d H:i:s'),
            'remark' => $insert['desc']
        ];
        Db::name('log_moneys')->insert($data);
    }

    //将信息添加到发送红包表
    public function addRedSend($userId,$redType,$redNum,$amount,$desc,$ids,$am,$groupId){
        $red = [
            'senderId'=>$userId,
            'redType'=>$redType,
            'redNum'=>$redNum,
            'redAmount'=>$amount,
            'redDesc'=>$desc,
            'userId'=>$ids,
            'createTime'=>date('Y-m-d H:i:s'),
            'amount'=>$am,
            'groupId'=>$groupId
        ];
        Db::name('red_send')->insert($red);
    }

    //将信息添加到接收红包表
    public function addRedReceive($redId,$receiver,$amount){
        $da = [
            'redId'=>$redId,
            'receiver'=>$receiver,
            'receiveTime'=>date('Y-m-d H:i:s'),
            'receiveAmount'=>$amount
        ];
//        dump($da);

        //执行写入操作
        Db::name('red_receive')->lock(true)->insert($da);
    }
    /**
     * 生成编号
     * @return string
     */
    public function makeSn($id)
    {
        return date('YmdHis')
        . sprintf('%03d', (float)microtime() * 1000)
        . $id;

    }
}