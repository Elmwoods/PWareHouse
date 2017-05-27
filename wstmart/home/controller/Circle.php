<?php
/**
 * Created by PhpStorm.
 * User: Lynn
 * Date: 2017/4/26
 * Time: 9:21
 */

namespace wstmart\home\controller;


use think\Controller;

class Circle extends Controller {
    public $request;

    public function __construct() {
        $this -> request = request();
    }

    //新增一条朋友圈信息
    public function circleAdd() {
        $param = $this -> request -> param();
        if (!empty($param['userId'])) {      //判断是否post发送了userId
            $users = db('user_friends');
            $arr = $users -> where('userId', $param['userId']) -> field('friendID') -> select();        //通过userId查询好友id集合
            $friendStr = '';        //朋友ID组合,以逗号隔开
            foreach ($arr as $v) {      //将好友id循环组合成以逗号隔开的字符串
                $friendStr .= $v['friendID'].',';
            }
            $friendStr = substr($friendStr, 0, -1);     //去除最后一个逗号
            $user_circle = db('user_circle');
            $data = array(                              //插入数据集合
                'content' => $param['content'],
                'createTime' => time(),
                'fromUserId' => $param['userId'],
                'toUserId' => $friendStr,
                'longitude' => isset($param['longitude']) ? $param['longitude'] : '',
                'latitude' => isset($param['latitude']) ? $param['latitude'] : '',
            );
            if ($user_circle -> insert($data)) {
                return json(array('result'=>'success', 'value'=>'发送朋友圈成功'));
            }else {
                return json(array('result'=>'error', 'value'=>'发送朋友圈失败'));
            }
        }
    }
    
    //朋友圈信息列表查询
    public function circleList() {
        $param = $this -> request -> param();
        if (!empty($param['userId'])) {      //判断是否post发送了userId
            $user_circle = db('user_circle');
            $arr = $user_circle -> where("toUserId like '%{$param['userId']}%'") ->order('createTime', 'DESC') -> select();     //根据发送过来的userId和时间的倒叙查询所有的朋友圈信息
            return json($arr);      //返回朋友圈内容列表
        }
    }
}