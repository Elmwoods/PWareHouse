<?php
/**
 * Created by PhpStorm.
 * User: Lynn
 * Date: 2017/4/26
 * Time: 9:21
 */

namespace wstmart\home\controller;


use think\Controller;
use think\Db;

class Group extends Controller {
    public $request;

    public function __construct() {
        $this -> request = request();
    }

    //创建群
    public function groupCreate($name, $adminID, $groupMembers, $icon=null, $notice=null, $intro=null) {
        $param = $this -> request -> param();
        if (!empty($param)) {      //判断是否post发送userId
            $user_group = db('user_groups');            //群表
            $members = explode(',', $param['groupMembers']);
            $users = db('users');                       //用户总表
            $group_member = db('user_groupmembers');    //群用户表
            $data = array(                          //群信息集合
                'name' => $param['name'],
                'adminID' => $param['adminID'],     //群主ID
                'icon' => isset($param['icon']) ? $param['icon'] : null,
                'notice' => isset($param['notice']) ? $param['notice'] : null,
                'intro' => isset($param['intro']) ? $param['intro'] : '群主很懒,什么都没留下',
            );
            if (!($user_group -> where('adminID', $param['userId']) -> where('name', $param['name']) -> find())) {
                $groupID = $user_group -> insertGetId($data);       //建群并返回群组ID
            }else {
                return json(array('result'=>'error', 'value'=>'群主大人,您已经建过该群了'));
            }
            $values = array();
            if ($groupID) {
                foreach ($members as $v) {
                    $arr = $users -> field(['userId', 'loginName', 'userPhoto']) -> where('userId', $v) -> find();      //循环获取用户信息
                    $values[] = array(          //将用户信息组合成一个二维数组集合
                        'userId' => $arr['userId'],
                        'groupNickName' => $arr['loginName'],
                        'groupPhoto' => $arr['userPhoto'],
                        'groupID' => $groupID,      //要写进的群ID
                    );
                }
                if ($group_member -> insertAll($values)) {
                    return json(array('result'=>'error', 'value'=>array('groupID'=>$groupID,'text'=>'已开启群聊,可以开始斗图了')));
                }else {
                    return json(array('result'=>'error', 'value'=>'未能写入群成员表'));
                }
            }else {
                return json(array('result'=>'error', 'value'=>'建群失败,请稍后重试'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }

    //解散群
    public function groupDelete($name, $adminID) {
        $param = $this -> request -> param();
        if (!empty($param)) {
            $user_group = db('user_groups');            //群表
            $group_member = db('user_groupmembers');    //群用户表
            $arr = $user_group -> field('groupID') -> where('adminID', $param['adminID']) -> where('name', $param['name']) -> find();
            if ($arr) {
                if ($group_member -> where('groupID', $arr['groupID']) -> delete() && $user_group -> where('groupID', $arr['groupID']) -> delete()) {   //同时删除群和群用户
                    return json(array('result'=>'success', 'value'=>'已成功解散该群,让斗图的见鬼去吧'));
                }else {
                    return json(array('result'=>'error', 'value'=>'网络又偷懒了,未能解散该群'));
                }
            }else {
                return json(array('result'=>'error', 'value'=>'该群不存在,请确认后重试'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }

    //获取群信息
    public function groupInfo($groupID) {
        $param = $this -> request -> param();
        if (!empty($param['groupID'])) {
            $data = Db::table('jingo_user_groups') -> where(['groupID'=>$param['groupID']]) -> find();
            if ($data) {
                return json(array('result'=>'success', 'value'=>$data));
            }else {
                return json(array('result'=>'error', 'value'=>'该群不存在,请确认后重试'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }

    //群主移除群成员
    public function removeMember($name, $adminID, $userId) {
        $param = $this -> request -> param();
        if (!empty($param)) {
            $user_group = db('user_groups');            //群表
            $group_member = db('user_groupmembers');    //群用户表
            $arr = $user_group -> field('groupID') -> where('adminID', $param['adminID']) -> where('name', $param['name']) -> find();
            if ($user_group -> where('adminID', $param['adminID']) -> where('groupID', $arr['groupID']) -> find()) {
                if ($group_member -> where('userId', $param['userId']) -> where('groupID', $arr['groupID']) -> delete()) {
                    return json(array('result'=>'success', 'value'=>'已移除该成员'));
                }else {
                    return json(array('result'=>'error', 'value'=>'移除成员失败,请稍候重试'));
                }
            }else {
                return json(array('result'=>'error', 'value'=>'您不是群主,无权移除成员'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }

    //群成员退出群组
    public function groupQuit($groupID, $userId) {
        $param = $this -> request -> param();
        if (!empty($param)) {
            //$user_group = db('user_groups');            //群表
            $group_member = db('user_groupmembers');    //群用户表
            //$arr = $user_group -> field('groupID') -> where('adminID', $param['adminID']) -> where('name', $param['name']) -> find();
            if ($group_member -> where('groupID', $param['groupID']) -> where('userId', $param['userId']) -> delete()) {
                return json(array('result'=>'success', 'value'=>'已退出该群组'));
            }else {
                return json(array('result'=>'success', 'value'=>'退出群组失败,请稍后重试'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }

    //群成员修改昵称
    public function changeNickName($nickName, $userId, $groupID) {
        $param = $this->request -> param();
        if (!empty($param)) {
            $map['groupNickName'] = $param['nickName'];
            $map['groupID'] = $param['groupID'];
            $name = Db::table('jingo_user_groupmembers') -> where($map) -> find();
            if (!$name) {
                if (Db::table('jingo_user_groupmembers') -> where('groupID', $param['groupID']) -> where('userId',$param['userId']) -> update(['groupNickName'=>$param['nickName']])) {
                    return json(array('result'=>'success', 'value'=>'您的大名已上史册'));
                }else {
                    return json(array('result'=>'error', 'value'=>'网络又开小差了,请稍后重试'));
                }
            }else {
                return json(array('result'=>'error', 'value'=>'该昵称群内已有人使用'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }
}