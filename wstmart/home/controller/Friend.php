<?php
/**
 * Created by PhpStorm.
 * User: Lynn
 * Date: 2017/4/26
 * Time: 9:21
 */

namespace wstmart\home\controller;


use think\Controller;

class Friend extends Controller {
    public $request;

    public function __construct() {
        $this -> request = request();
    }

    //获取朋友列表
    public function friendList($userId) {
        $param = $this -> request -> param();
        if (!empty($param)) {
            $user_friend = db('user_friends');
            $arr = $user_friend ->field(['friendID', 'nickName', 'type']) -> where('userId', $param['userId']) -> where('isBlackList', 0) -> select();
            $users = db('users');
            $user_group = db('user_groups');
            $friendArr[] = array();
            for ($i=0; $i < count($arr); $i++) {
                if ($arr[$i]['type'] == 0) {
                    $friendArr[$i] = $users -> field(['userId', 'userPhoto']) -> where('userId', $arr[$i]['friendID']) -> find();
                    $friendArr[$i]['nickName'] = $arr[$i]['nickName'];
                    $friendArr[$i]['type'] = '好友';

                }else {
                    $friendArr[$i] = $user_group -> field(['groupID', 'name', 'icon']) -> where('groupID', $arr[$i]['friendID']) -> find();
                    $friendArr[$i]['type'] = '群组';
                }
            }
            return json($friendArr);
        }
    }

    //添加朋友
    public function friendAdd($userId, $friendID, $nickName) {
        $param = $this -> request -> param();
        if (!empty($param)) {      //判断是否post发送userId
            $user_friend = db('user_friends');
            $data = array(
                'friendID' => $param['friendID'],
                'userId' => $param['userId'],
                'nickName' => $param['nickName'],
            );
            $friendArr = $user_friend -> where('friendID', $param['friendID']) -> where('userId', $param['userId']) -> find();
            if ($friendArr) {
                if ($friendArr['isBlackList'] == 1) {       //判断该好友是否在黑名单
                    $user_friend -> where('id', $friendArr['id']) -> update(['isBlackList'=>0]);
                }else {     //如果不在黑名单
                    return json(array('result'=>'error', 'value'=>'她已在您的好友列表中'));
                }
            }elseif ($user_friend -> insert($data)) {       //如果查不到该好友则添加好友
                $arr = $user_friend -> where('userId', $param['userId']) -> where('isBlackList', 0) -> field(['friendID', 'nickName']) ->order('time', 'DESC') -> select();
                for ($i=0; $i < count($arr); $i++) {
                    $arr[$i]['nickName'] = urlencode($arr[$i]['nickName']);     //将昵称进行url编码
                }
                $users = db('users');
                $friend_json = urldecode(json_encode($arr));        //将昵称和朋友的ID组合成json数组并用url解码
                if ($users ->field('friendID') -> where('userId', $param['userId']) -> update(['friendID' => $friend_json])) {
                    return json(array('result'=>'success', 'value'=>'添加朋友成功'));
                }else {
                    return json(array('result'=>'error', 'value'=>'未能更新用户总表的friendID'));
                }
            }else {
                return json(array('result'=>'error', 'value'=>'添加朋友失败'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传递自己和朋友的ID以及昵称'));
        }
    }

    //拉黑好友
    public function deFriend($userId, $friendID) {
        $param = $this -> request -> param();
        if (!empty($param)) {
            $user_friend = db('user_friends');
            if ($user_friend -> where('userId', $param['userId']) -> where('friendID', $param['friendID']) -> update(['isBlackList'=>1])) {
                $arr = $user_friend -> where('userId', $param['userId']) -> where('isBlackList', 0) -> field(['friendID', 'nickName']) ->order('time', 'DESC') -> select();
                for ($i=0; $i < count($arr); $i++) {
                    $arr[$i]['nickName'] = urlencode($arr[$i]['nickName']);     //将昵称进行url编码
                }
                $users = db('users');
                $friend_json = urldecode(json_encode($arr));        //将昵称和朋友的ID组合成json数组并用url解码
                if ($users ->field('friendID') -> where('userId', $param['userId']) -> update(['friendID' => $friend_json])) {
                    return json(array('result'=>'success', 'value'=>'已成功拉黑好友'));
                }else {
                    return json(array('result'=>'error', 'value'=>'未能更新用户总表的friendID'));
                }
            }else {
                return json(array('result'=>'error', 'value'=>'拉黑好友失败,请稍候重试'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传递自己和朋友的ID'));
        }
    }

    //删除好友
    public function friendDelete($userId, $friendID) {
        $param = $this -> request -> param();
        if (!empty($param)) {
            $user_friend = db('user_friends');
            if ($user_friend -> where('userId', $param['userId']) -> where('friendID', $param['friendID']) -> delete()) {
                $arr = $user_friend -> where('userId', $param['userId']) -> where('isBlackList', 0) -> field(['friendID', 'nickName']) ->order('time', 'DESC') -> select();
                for ($i=0; $i < count($arr); $i++) {
                    $arr[$i]['nickName'] = urlencode($arr[$i]['nickName']);     //将昵称进行url编码
                }
                $users = db('users');
                $friend_json = urldecode(json_encode($arr));        //将昵称和朋友的ID组合成json数组并用url解码
                if ($users ->field('friendID') -> where('userId', $param['userId']) -> update(['friendID' => $friend_json])) {
                    return json(array('result'=>'success', 'value'=>'您已经将她遗忘,注孤生'));
                }else {
                    return json(array('result'=>'error', 'value'=>'未能更新用户总表的friendID'));
                }
            }else {
                return json(array('result'=>'error', 'value'=>'好险,差点就注孤生了'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传递自己和朋友的ID'));
        }
    }

}