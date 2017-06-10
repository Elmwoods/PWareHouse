<?php
/**
 * Created by PhpStorm.
 * User: Lynn
 * Date: 2017/4/26
 * Time: 9:21
 */

namespace wstmart\chat\controller;


use think\Controller;
use think\Db;
use wstmart\chat\model\Friends;
use wstmart\chat\model\Users;

class Friend extends Controller {
    public $request;
    private $RongCloud;


    public function __construct() {
        $this -> request = request();
        $this->RongCloud = new Rongyun();
    }

    //获取朋友列表
    public function friends($userId) {
        $param = $this -> request -> param();
        if (!empty($param)) {
            //联接朋友表和用户总表查询好友信息
            $user_friend = db('user_friends a');
            $arr1 = $user_friend -> join('users b', 'a.friendID = b.userId', 'LEFT') -> field(['a.friendID', 'a.nickName', 'b.userName', 'b.userPhoto', 'b.loginName', 'b.userPhone']) -> where('a.userId', $param['userId']) -> where('a.isBlackList', 0) -> where('a.isAgreed', 1) -> select();
            for ($i=0;$i<count($arr1);$i++) {
                $arr1[$i]['state'] = 0;     //0表示朋友
            }
            //$arr = $user_friend ->field(['friendID', 'nickName', 'type']) -> where('userId', $param['userId']) -> where('isBlackList', 0) -> select();
            //联接群表和群用户表查询群信息
            $user_group = db('user_groups a');
            $arr2 = $user_group -> field(['a.groupID', 'a.name', 'a.icon', 'b.groupName']) -> join('user_groupmembers b', 'a.groupID = b.groupID', 'LEFT') -> where('b.userId', $param['userId']) -> where('b.isSave',1) -> select();
            for ($i=0;$i<count($arr2);$i++) {
                $arr2[$i]['state'] = 1;     //1表示群
            }
            //合并朋友表和群表
            $arr3[] = ['friendID' => 1, 'userName' => 'administrator', 'userPhoto' => 'http://www.jingomall.com/logo.png', 'state' => 2];
            $arr = array_merge($arr1, $arr2, $arr3);
            return json(array('result'=>'success', 'value'=>$arr));
            /*$friendArr[] = array();
            for ($i=0; $i < count($arr); $i++) {
                if ($arr[$i]['type'] == 0) {
                    $friendArr[$i] = $users -> field(['userId', 'userPhoto']) -> where('userId', $arr[$i]['friendID']) -> find();
                    $friendArr[$i]['nickName'] = $arr[$i]['nickName'];
                    $friendArr[$i]['type'] = '好友';
                }else {
                    $friendArr[$i] = $user_group -> field(['a.groupID', 'a.name', 'a.icon']) ->join('user_groupmembers b', 'a.groupID = b.groupID', 'LEFT') -> where('a.groupID', $arr[$i]['friendID']) -> where('b.isSave',1) -> find();
                    if ($friendArr[$i]) {
                        $friendArr[$i]['type'] = '群组';
                    }else {
                        unset($friendArr[$i]);
                    }
                }
            }
            return json($friendArr);*/
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }

    //好友请求列表(好友未同意时)
    public function askList($userId) {
        $param = $this -> request -> param();
        if (!empty($param)) {
            $data = Db::table('jingo_user_friends a') -> join('users b', 'a.friendID = b.userId', 'LEFT') -> field(['a.friendID', 'a.nickName', 'b.userPhoto']) -> where('a.userId', $param['userId']) -> where('a.isAgreed', 0) -> select();       //联表查询好友请求列表
            if ($data) {
                return json(array('result' => 'success', 'value' => $data));        //返回好友请求列表
            }else {
                return json(array('result' => 'error', 'value' => '没有好友请求'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }

    //好友同意请求
    public function agree($userId, $friendID, $nickName) {
        $param = $this -> request -> param();
        if (!empty($param)) {
            DB::table('jingo_user_friends') -> where('friendID', $param['useId']) -> where('userId', $param['friendID']) -> update(['isAgreed', 1]);        //更新好友请求值为1(0未同意1同意)
            $data = [
                'userId' => $param['userId'],
                'friendID' => $param['friendID'],
                'nickName' => $param['nickName']
            ];
            if (Db::table('jingo_user_friends') -> insert($data)) {         //好友表添加对方好友的相关信息
                return json(array('result'=>'success', 'value'=>'多一个朋友，少一个敌人'));
            }else {
                return json(array('result'=>'error', 'value'=>'网络又开小差了,请稍候重试'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }


    //获取朋友信息
    public function friendInfo($userId, $friendID) {
        $param = $this -> request -> param();
        if (!empty($param)) {
            $fields = array('a.userId', 'a.userName', 'a.signature', 'a.userPhoto', 'a.userSex', 'a.userNation', 'a.userProvince', 'a.userCity', 'b.nickName', 'b.isDisturb', 'b.isTop', 'b.circleEnable', 'c.picturePath');
            $data = Db::table('jingo_users a') -> join('user_friends b', 'b.friendID = a.userId', 'LEFT') -> join('user_album c', 'c.userId = a.userId', 'LEFT') -> field($fields) -> where('b.userId', $param['userId']) -> where('b.friendID', $param['friendID']) -> select();
            $values[] = array();        //初始化朋友信息组合
            $picArr = array();          //朋友相册组合
            for ($i=0; $i < count($data); $i++) {
                if ($i==0) {            //将第一个数组的朋友信息赋值,后续数组的朋友信息相同,不重复赋值
                    $values = $data[$i];
                    $picArr[] = $data[$i]['picturePath'];
                }else {
                    $picArr[] = $data[$i]['picturePath'];
                }
            }
            $values['picturePath'] = $picArr;       //将相册加入朋友信息组合
            if ($values) {
                return json(array('result'=>'success', 'value'=>$values));
            }else {
                return json(array('result'=>'error', 'value'=>'网络又开小差了,请稍候重试'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }

    //添加朋友
    public function add($userId, $friendID, $nickName) {
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
                    return json(array('result'=>'success', 'value'=>'已从黑名单中移出'));
                }else {                                     //如果不在黑名单
                    return json(array('result'=>'error', 'value'=>'她已在您的好友列表中'));
                }
            }elseif ($user_friend -> insert($data)) {       //如果查不到该好友则添加好友
                return json(array('result'=>'success', 'value'=>'多一个朋友，少一个敌人'));
                /*$arr = $user_friend -> where('userId', $param['userId']) -> where('isBlackList', 0) -> field(['friendID', 'nickName']) ->order('time', 'DESC') -> select();
                for ($i=0; $i < count($arr); $i++) {
                    $arr[$i]['nickName'] = urlencode($arr[$i]['nickName']);     //将昵称进行url编码
                }
                $users = db('users');
                $friend_json = urldecode(json_encode($arr));        //将昵称和朋友的ID组合成json数组并用url解码
                if ($users ->field('friendID') -> where('userId', $param['userId']) -> update(['friendID' => $friend_json])) {
                    return json(array('result'=>'success', 'value'=>'添加朋友成功'));
                }else {
                    return json(array('result'=>'error', 'value'=>'未能更新用户总表的friendID'));
                }*/
            }else {
                return json(array('result'=>'error', 'value'=>'网络又开小差了,请稍候重试'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }



    //拉黑好友
    public function deFriend($userId, $friendID) {
        $param = $this -> request -> param();
        if (!empty($param)) {
            $user_friend = db('user_friends');
            if ($user_friend -> where('userId', $param['userId']) -> where('friendID', $param['friendID']) -> update(['isBlackList'=>1])) {
                return json(array('result'=>'success', 'value'=>'您已将好友关进小黑屋'));
                /*$arr = $user_friend -> where('userId', $param['userId']) -> where('isBlackList', 0) -> field(['friendID', 'nickName']) ->order('time', 'DESC') -> select();
                for ($i=0; $i < count($arr); $i++) {
                    $arr[$i]['nickName'] = urlencode($arr[$i]['nickName']);     //将昵称进行url编码
                }
                $users = db('users');
                $friend_json = urldecode(json_encode($arr));        //将昵称和朋友的ID组合成json数组并用url解码
                if ($users ->field('friendID') -> where('userId', $param['userId']) -> update(['friendID' => $friend_json])) {
                    return json(array('result'=>'success', 'value'=>'已成功拉黑好友'));
                }else {
                    return json(array('result'=>'error', 'value'=>'未能更新用户总表的friendID'));
                }*/
            }else {
                return json(array('result'=>'error', 'value'=>'网络又开小差了,请稍候重试'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }

    //删除好友
    public function delete($userId, $friendID) {
        $param = $this -> request -> param();
        if (!empty($param)) {
            $user_friend = db('user_friends');
            if ($user_friend -> where('userId', $param['userId']) -> where('friendID', $param['friendID']) -> delete()) {
                return json(array('result'=>'success', 'value'=>'好友三两个足矣'));
                /*$arr = $user_friend -> where('userId', $param['userId']) -> where('isBlackList', 0) -> field(['friendID', 'nickName']) ->order('time', 'DESC') -> select();
                for ($i=0; $i < count($arr); $i++) {
                    $arr[$i]['nickName'] = urlencode($arr[$i]['nickName']);     //将昵称进行url编码
                }
                $users = db('users');
                $friend_json = urldecode(json_encode($arr));        //将昵称和朋友的ID组合成json数组并用url解码
                if ($users ->field('friendID') -> where('userId', $param['userId']) -> update(['friendID' => $friend_json])) {
                    return json(array('result'=>'success', 'value'=>'您已经将她遗忘,注孤生'));
                }else {
                    return json(array('result'=>'error', 'value'=>'未能更新用户总表的friendID'));
                }*/
            }else {
                return json(array('result'=>'error', 'value'=>'好险,差点就注孤生了'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传递自己和朋友的ID'));
        }
    }

    //保存最近联系人列表
    public function addLastList() {         //该接口暂时不做测试(需和前端沟通)
        $param = $this->request->param();
        if (!empty($param)) {
            Db::table('jingo_user_contacts') -> where('userId', $param['userId']) -> delete();      //删除之前联系人
            if (Db::table('jingo_user_contacts') -> insertAll($param)) {            //新增联系人列表
                return json(array('result'=>'success', 'value'=>'保存最近联系人成功'));
            }else {
                return json(array('result'=>'error', 'value'=>'网络又开小差了,请稍候重试'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }

    //最近联系人列表
    public function lastList($userId) {
        $param = $this->request->param();
        if (!empty($param)) {
            $data = Db::table('jingo_user_contacts') -> field(['contactID', 'name', 'photo', 'type']) -> where('userId', $param['userId']) -> order('id', 'ASC') -> select();      //查询联系人列表
            if ($data) {
                return json(array('result'=>'success', 'value'=>$data));
            }else {
                return json(array('result'=>'error', 'value'=>'网络又开小差了,请稍候重试'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }

    //朋友设置(用于设置昵称和置顶等)
    public function setting($userID, $friendID) {
        $param = $this->request->param();
        $user_friend = new Friends();           //实例化用户朋友表
        if (!empty($param)) {
            if ($user_friend -> allowField(true) -> isUpdate(true) -> save($param, ['userId'=>$param['userId'], 'friendID'=>$param['friendID']])) {         //更新朋友设置
                return json(array('result'=>'success', 'value'=>'设置已修改'));
            }else {
                return json(array('result'=>'error', 'value'=>'网络又开小差了,请稍候重试'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }
}