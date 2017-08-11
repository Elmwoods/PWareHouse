<?php
/**
 * Created by PhpStorm.
 * User: Lynn
 * Date: 2017/4/26
 * Time: 9:21
 */

namespace wstmart\chat\controller;

use RongYunServer\YunXin\Rongcloud;
use think\Controller;
use think\Db;
use wstmart\chat\model\GroupMembers;
use wstmart\chat\model\GroupNotice;
use wstmart\chat\model\Groups;

class Group extends Controller {
    public $request;
    private $RongCloud;

    public function __construct() {
        $this -> request = request();
        $this->RongCloud = new Rongyun();
    }

    //创建群
    public function create($adminID, $groupMembers)
    {
        //return json(array('result' => 'success', 'value' => ['groupID'=>376, 'name'=>'abc', 'icon'=>'upload/groupIcon/groupPhoto.png', 'groupName'=>'', 'text'=>'已开启群聊,可以开始斗图了']));
        $param = $this->request->param();
        if (!empty($param)) {      //判断是否post发送userId
            //memcached缓存
            /*$dataInfo = Db::table('jingo_users') -> field(['userId', 'loginName', 'trueName', 'userPhoto', 'userPhone', 'userSex', 'brithday', 'userAge', 'signature', 'userEmail', 'longitude', 'latitude', 'userName', 'userNation', 'userProvince', 'userCity', 'setAge', 'setDistance', 'isValidate', 'setStranger', 'isRec', 'isSeted']) -> select();
            $m = new \Memcached();
            $m -> addServer('127.0.0.1', '11211');
            foreach ($dataInfo as $v) {
                $m -> add($v['userId'], $v);
            }*/

            $user_group = db('user_groups');            //群表
            $members = explode(',', $param['groupMembers']);    //将群成员以逗号分割成数组
            unset($param['groupMembers']);                      //去除群成员字段
            $users = db('users');                       //用户总表
            $group_member = db('user_groupmembers');    //群用户表
            array_unshift($members, $param['adminID']);
            //组合群名
            $arr2 = Db::table('jingo_users') -> field(['userId', 'userName']) -> where('userId', 'in', $members) -> select();       //查询群成员
            $combine = [];
            for($i=0,$count = count($arr2);$i < $count; $i++) {
                if ($arr2[$i]['userId'] == $param['adminID']) {     //判断是否为群主,若是则放在最前
                    array_unshift($combine, $arr2[$i]['userName']);
                }else {
                    array_push($combine, $arr2[$i]['userName']);
                }
            }
            $name = implode('、', $combine);
            if (strlen($name) > 25) {       //如果群名长度超过25,则截取25个字符
                $name = mb_substr($name, 0, 25).'...';
            }
            $user_group -> startTrans();
            $group_member -> startTrans();
            $groupID = $user_group->insertGetId(['adminID'=>$param['adminID'], 'name'=>$name]);       //建群并返回群组ID
            $values = array();
            if ($groupID) {
                foreach ($members as $v) {
                    $arr = $users->field(['userId', 'loginName', 'userPhoto', 'userName'])->where('userId', $v)->find();      //循环获取用户信息
                    $values[] = array(          //将用户信息组合成一个二维数组集合
                        'userId' => $arr['userId'],
                        //'groupNickName' => $arr['userName'],
                        'groupNickName' => '',
                        'groupPhoto' => $arr['userPhoto'],
                        'groupID' => $groupID,
                    );
                }
                if ($group_member->insertAll($values)) {
                    $info = $this->RongCloud->creates($members, $groupID, $name);
                    $info1 = $this->RongCloud->publishGroups($param['adminID'], $groupID, 'RC:InfoNtf', '{"message":"已经可以开始斗图了","extra":""}', '', '', 1, 1, 1);
                    if (json_decode($info)->code == 200 && json_decode($info1)->code == 200) {
                        $user_group -> commit();
                        $group_member -> commit();
                        $data = Db::table('jingo_user_groups') -> field(['groupID', 'name', 'icon']) -> where(['groupID'=>$groupID, 'adminID'=>$param['adminID']]) -> find();
                        $data['text'] = '已开启群聊,可以开始斗图了';
                        return json(array('result' => 'success', 'value' => $data));
                    } else {
                        $user_group -> rollback();
                        $group_member -> rollback();
                        return json(array('result' => 'error', 'msg' => '接入融云第三方失败'));
                    }
                }else {
                    return json(array('result' => 'error', 'msg' => '未能写入群成员表'));
                }
            } else {
                return json(array('result' => 'error', 'msg' => '建群失败,请稍后重试'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //解散群
    public function delete($groupID, $adminID)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            $user_group = db('user_groups');            //群表
            $group_member = db('user_groupmembers');    //群用户表
            $arr = $user_group->field('groupID')->where('adminID', $param['adminID'])->where('groupID', $param['groupID'])->find();
            if ($arr) {
                $user_group -> startTrans();
                $group_member -> startTrans();
                if ($group_member->where('groupID', $arr['groupID'])->delete() && $user_group->where('groupID', $arr['groupID'])->delete()) {   //同时删除群和群用户
                    $info = $this->RongCloud->dismisss($param['adminID'], $arr['groupID']);
                    if (json_decode($info)->code == 200) {
                        $user_group -> commit();
                        $group_member -> commit();
                        return json(array('result' => 'success', 'msg' => '已成功解散该群,让斗图的见鬼去吧'));
                    } else {
                        $user_group -> rollback();
                        $group_member -> rollback();
                        return json(array('result' => 'error', 'msg' => '接入融云第三方失败'));
                    }
                } else {
                    return json(array('result' => 'error', 'msg' => '网络又偷懒了,未能解散该群'));
                }
            } else {
                return json(array('result' => 'error', 'msg' => '该群不存在,请确认后重试'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //获取群信息
    public function info($groupID, $userId)
    {
        $param = $this->request->param();
        if (!empty($param['groupID'])) {
            if (Db::table('jingo_user_groups')->where('groupID', $param['groupID'])->where('adminID', $param['userId'])->find()) {        //判断是否为群主
                $data = Db::table('jingo_user_groups a')
                        ->field(['a.groupID', 'name', 'a.icon', 'a.isJoin', 'a.adminID', 'b.groupNickName', 'b.groupName', 'b.isDisturb', 'b.isTop', 'b.isSave', 'b.groupPhoto'])
                        ->join('jingo_user_groupmembers b', 'a.groupID = b.groupID', 'left')
                        ->where(['a.groupID' => $param['groupID'], 'b.userId' => $param['userId']])
                        ->find();
                $arr = Db::table('jingo_group_notice a') -> field(['b.groupNickName', 'b.groupPhoto', 'a.content', 'a.time']) -> join('jingo_user_groupmembers b', ['a.groupID = b.groupID', 'a.userId = b.userId'], 'LEFT') -> where(['a.groupID'=>$param['groupID']]) -> find();
                if ($arr) {
                    $data['notice'] = $arr;
                }else {
                    $data['notice'] = new \stdClass();
                }
                $data['state'] = 1;
            } else {
                $data = Db::table('jingo_user_groups a')
                        ->field(['a.groupID', 'a.name', 'b.groupName', 'a.icon', 'a.adminID', 'a.isJoin', 'b.groupNickName', 'b.groupName', 'b.isDisturb', 'b.isTop', 'b.isSave', 'b.groupPhoto'])
                        ->join('jingo_user_groupmembers b', 'a.groupID = b.groupID', 'left')
                        ->where(['a.groupID' => $param['groupID'], 'b.userId' => $param['userId']])
                        ->find();
                $arr = Db::table('jingo_group_notice a') -> field(['b.groupNickName', 'b.groupPhoto', 'a.content', 'a.time']) -> join('jingo_user_groupmembers b', ['a.groupID = b.groupID', 'a.userId = b.userId'], 'LEFT') -> where(['a.groupID'=>$param['groupID']]) -> find();
                if ($arr) {
                    $data['notice'] = $arr;
                }else {
                    $data['notice'] = new \stdClass();
                }
                $data['state'] = 0;
            }
            if ($data) {
                return json(array('result' => 'success', 'value' => $data));
            } else {
                return json(array('result' => 'error', 'msg' => '该群不存在,请确认后重试'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //群成员信息
    public function members($userId, $groupID) {
        $param = $this->request->param();
        if (!empty($param)) {
            $fields = [
                'a.userId',
                'a.groupNickName',
                'a.groupPhoto',
                'b.userPhone',
                'b.userName',
                'b.loginName',
            ];
            if (Db::table('jingo_user_groupmembers') -> where(['userId'=>$param['userId'], 'groupID'=>$param['groupID']]) -> find()) {
                $data = Db::table('jingo_user_groupmembers a') -> field($fields) -> join('jingo_users b', 'a.userId = b.userId', 'LEFT') -> where('a.groupID', $param['groupID']) -> select();
                foreach ($data as $k => $v) {
                    $data[$k]['userPhoto'] = $v['groupPhoto'];
                    unset($data[$k]['groupPhoto']);
                }
                return json(array('result' => 'success', 'value' => $data));
            }else {
                return json(array('result' => 'success', 'msg' => '您不是该群成员'));
            }
        }else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }


    //群主移除群成员
    public function remove($groupID, $adminID, $userId)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            $user_group = db('user_groups');            //群表
            $group_member = db('user_groupmembers');    //群用户表
            $userArr = explode(',', $param['userId']);      //要移除的成员数组
            if ($user_group->where('adminID', $param['adminID'])->where('groupID', $param['groupID'])->find()) {    //判断是否为群主
                $group_member -> startTrans();
                if ($group_member->where('userId', 'in', $userArr)->where('groupID', $param['groupID'])->delete()) {
                    $info = $this->RongCloud->quits($userArr, $param['groupID']);
                    if (json_decode($info)->code == 200) {
                        $group_member -> commit();
                        return json(array('result' => 'success', 'msg' => '已移除该成员'));
                    } else {
                        $group_member -> rollback();
                        return json(array('result' => 'error', 'msg' => '接入融云第三方失败'));
                    }
                } else {
                    return json(array('result' => 'error', 'msg' => '移除成员失败,请稍候重试'));
                }
            } else {
                return json(array('result' => 'error', 'msg' => '您不是群主,无权移除成员'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //群成员退出群组
    public function quit($groupID, $userId)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            $group_member = db('user_groupmembers');    //群用户表
            $group_member -> startTrans();
            if ($group_member->where('groupID', $param['groupID'])->where('userId', $param['userId'])->delete()) {
                $info = $this->RongCloud->quits($param['userId'], $param['groupID']);
                if (json_decode($info)->code == 200) {
                    $group_member -> commit();
                    return json(array('result' => 'success', 'msg' => '已退出该群组'));
                } else {
                    $group_member -> rollback();
                    return json(array('result' => 'error', 'msg' => '接入融云第三方失败'));
                }
            } else {
                return json(array('result' => 'success', 'msg' => '退出群组失败,请稍后重试'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //群成员修改昵称
    public function changeNickName($nickName, $userId, $groupID)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            $map['userId'] = $param['userId'];
            $map['groupID'] = $param['groupID'];
            if (Db::table('jingo_user_groupmembers')->where($map)->update(['groupNickName' => $param['nickName']])) {
                return json(array('result' => 'success', 'msg' => '您的大名已上史册'));
            } else {
                return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍后重试'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //群设置
    public function setting($groupID, $userId)
    {
        $param = $this->request->param();
        if (!empty($param['groupID'])) {
            $groups = new Groups();                     //实例化群表模型
            if (isset($param['upload'])) {
                //获取上传图片
                $file = request()->file('icon');
                //设置群图标
                // 移动到框架应用根目录/public/uploads/ 目录下
                if (!empty($file)) {
                    if (!file_exists(ROOT_PATH . 'upload/groupIcon/')) {
                        mkdir(ROOT_PATH . 'upload/groupIcon/');
                    }
                    // 移动到框架应用根目录/public/uploads/目录下
                    $info = $file->validate(['size' => 5 * 1024 * 1024, 'ext' => 'jpg,png,gif'])->rule('uniqid')->move(ROOT_PATH . 'upload/groupIcon/');
                    if ($info) {
                        $path = 'upload/groupIcon/'.$info->getFilename();
                    } else {
                        // 上传失败获取错误信息
                        echo $file->getError();
                    }
                } else {
                    return json(array('result' => 'error', 'msg' => '上传图片失败'));
                }
            }
            if (isset($path)) {
                $param['icon'] = $path;
                $pre = Db::table('jingo_user_groups') -> field('icon') -> find($param['groupID']);
                $str = substr($pre['icon'], -14, 10);        //截取图片路径中的图片名
            }
            $groupMembers = new GroupMembers();         //实例化群用户表模型
            $groups -> startTrans();
            $groupMembers -> startTrans();
            if (Db::table('jingo_user_groups')->where('groupID', $param['groupID'])->where('adminID', $param['userId'])->find()) {        //判断是否为群主
                $boolean1 = $groups->allowField(true)->isUpdate(true)->save($param);      //更新群表设置
                $boolean2 = $groupMembers->allowField(true)->isUpdate(true)->save($param, ['groupID' => $param['groupID'], 'userId' => $param['userId']]);        //更新群用户表设置
            } else {
                if (isset($param['isJoin'])) {     //如果存在isJoin字段则去除(群主权限,非群主不得更改)
                    unset($param['isJoin']);
                }
                if (isset($param['icon'])) {     //如果存在isJoin字段则去除(群主权限,非群主不得更改)
                    unset($param['icon']);
                }
                $boolean1 = $groups->allowField(true)->isUpdate(true)->save($param);      //更新群表设置
                $boolean2 = $groupMembers->allowField(true)->isUpdate(true)->save($param, ['groupID' => $param['groupID'], 'userId' => $param['userId']]);        //更新群用户表设置
            }
            if ($boolean1 || $boolean2) {       //判断是否有更新成功
                if (isset($str) && $str != 'groupPhoto' && isset($path) && file_exists(ROOT_PATH.$pre['icon'])) {
                    unlink($pre['icon']);
                }
                $groups -> commit();
                $groupMembers -> commit();
                return json(array('result' => 'success', 'msg' => '已修改群设置'));
            } else {
                $groups -> rollback();
                $groupMembers -> rollback();
                return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //发布群公告
    public function commitNotice($groupID, $userId, $content) {
        $param = $this->request->param();
        //$param['content'] = str_replace('&quot;', '"', $param['content']);
        $info = '{"content" : "@所有人\n'.$param['content'].'"}';
        if (!empty($param)) {
            $notice = new GroupNotice();
            if (!empty($param['content'])) {
                $info = $this->RongCloud->publishGroups($param['userId'], $param['groupID'], 'RC:TxtMsg', $info, '', '', 1, 1, 1);
                $info = json_decode($info) -> code;
            }else {
                $info = 200;
            }
            $notice -> startTrans();
            if ($notice -> find($param['groupID'])) {
                $notice -> save($param, ['groupID'=>$param['groupID']]);
                if ($info == 200) {
                    $notice -> commit();
                    return json(array('result' => 'success', 'msg' => '已发布群公告'));
                }else {
                    $notice -> rollback();
                    return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
                }
            }else {
                $notice -> save($param);
                if ($info == 200) {
                    $notice -> commit();
                    return json(array('result' => 'success', 'msg' => '已发布群公告'));
                }else {
                    $notice -> rollback();
                    return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
                }
            }
        }else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //更换群主
    public function changeAdmin($groupID, $userId, $targetID)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            if (Db::table('jingo_user_groups')->where('groupID', $param['groupID'])->where('adminID', $param['userId'])->find()) {        //判断是否为群主
                if (Db::table('jingo_user_groups')->where('groupID', $param['groupID'])->update(['adminID' => $param['targetID']])) {
                    return json(array('result' => 'success', 'msg' => '无上的权力你已经交给别人了'));
                } else {
                    return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
                }
            } else {
                return json(array('result' => 'error', 'msg' => '群主也是你能换的?'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //拉好友进群
    public function add($groupID, $userId, $targetID)
    {
        $param = $this->request->param();
        $groupMember = new GroupMembers();
        $isJoin = Db::table('jingo_user_groups') -> field(['isJoin', 'adminID']) -> where('groupID', $param['groupID']) -> find();
        if (!empty($param)) {
            $map = [
                'userId' => $param['userId'],
                'groupID' => $param['groupID']
            ];
            $targetID = explode(',', $param['targetID']);
            $arr = Db::table('jingo_user_groupmembers')->field(['userId'])->where('groupID', $param['groupID'])->select();
            $members = [];
            foreach ($arr as $v) {
                $members[] = $v['userId'];      //获取并组合成一维群用户数组
            }
            $count = count($targetID);
            if (count($targetID) > 1) {         //剔除出已添加到群成员表里的人员
                for ($i = 0; $i < $count; $i++) {
                    if (in_array($targetID[$i], $members)) {
                        unset($targetID[$i]);
                    }
                }
            } else {
                if (in_array($targetID[0], $members)) {
                    exit();
                }
            }
            $data = [];
            if (empty($targetID)) return json(array('result' => 'error', 'msg' => '您添加的好友已在该群中'));
            $arr1 = Db::table('jingo_users')->field(['userId', 'userName', 'userPhoto'])->where('userId', 'in', $targetID)->select();
            $persons = '';
            $groupNickName = Db::table('jingo_user_groupmembers') -> field('groupNickName') -> where(['groupID'=>$param['groupID'], 'userId'=>$param['userId']]) -> find();
            if ($groupNickName['groupNickName'] == '') {
                $selfInfo = Db::table('jingo_users') -> field('userName') -> find($param['userId']);
                $groupNickName['groupNickName'] = $selfInfo['userName'];
            }
            for ($i = 0; $i < count($arr1); $i++) {
                $data[$i]['groupID'] = $param['groupID'];
                $data[$i]['userId'] = $arr1[$i]['userId'];
                //$data[$i]['groupNickName'] = $arr1[$i]['userName'];
                $data[$i]['groupNickName'] = '';
                $data[$i]['groupPhoto'] = $arr1[$i]['userPhoto'];
                $persons .= $arr1[$i]['userName'].'、';
            }
            if ($isJoin['adminID'] == $param['userId']) {           //判断是否为群主
                $groupMember -> startTrans();
                if ($groupMember->saveAll($data)) {           //添加多个好友进群
                    $info = $this->RongCloud->joins($targetID, $param['groupID'], ' ');
                    $persons = mb_substr($persons, 0, -1);
                    $content = '{"message":"'.$groupNickName['groupNickName'].'邀请\''.$persons.'\'进群了"}';
                    $info1 = $this->RongCloud->publishGroups($param['userId'], $param['groupID'], 'RC:InfoNtf', $content, '', '', 1, 1, 1);
                    if (json_decode($info)->code == 200 && json_decode($info1)->code == 200) {
                        $groupMember -> commit();
                        return json(array('result' => 'success', 'msg' => '你已经邀请该好友进群'));
                    } else {
                        $groupMember -> rollback();
                        return json(array('result' => 'error', 'msg' => '接入融云第三方失败'));
                    }
                } else {
                    return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
                }
            }else {
                if ($isJoin['isJoin'] == 0 ) {
                    if (Db::table('jingo_user_groupmembers')->where($map)->find()) {        //判断是否为群内成员
                        $groupMember -> startTrans();
                        if ($groupMember->saveAll($data)) {           //添加多个好友进群
                            $info = $this->RongCloud->joins($targetID, $param['groupID'], ' ');
                            $persons = mb_substr($persons, 0, -1);
                            $content = '{"message":"'.$groupNickName['groupNickName'].'邀请\''.$persons.'\'进群了"}';
                            $info1 = $this->RongCloud->publishGroups($param['userId'], $param['groupID'], 'RC:InfoNtf', $content, '', '', 1, 1, 1);
                            if (json_decode($info)->code == 200 && json_decode($info1)->code == 200) {
                                $groupMember -> commit();
                                return json(array('result' => 'success', 'msg' => '你已经邀请该好友进群'));
                            } else {
                                $groupMember -> rollback();
                                return json(array('result' => 'error', 'msg' => '接入融云第三方失败'));
                            }
                        } else {
                            return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
                        }
                    } else {
                        return json(array('result' => 'error', 'msg' => '你不是该群成员,无法拉好友进群'));
                    }
                }
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //非群主邀请好友(开启群聊邀请确认的情况下)
    public function join($groupID, $userId, $targetID) {
        $param = $this->request->param();
        if (!empty($param)) {
            $groupInfo = Db::table('jingo_user_groups') -> field(['adminID', 'name']) -> where('groupID', $param['groupID']) -> find();
            $inviter = Db::table('jingo_users') -> field(['userId', 'userPhoto', 'userName']) -> where('userId', $param['userId']) -> find();
            $content = '{"userId":"'.$param["userId"].'", "userName":"'.$inviter["userName"].'", "userPhoto":"'.$inviter["userPhoto"].'", "groupID":"'.$param["groupID"].'", "name":"'.$groupInfo["name"].'", "targetID":"'.$targetID.'"}';
            $info = $this->RongCloud->PublishSystems(1, $groupInfo['adminID'], 'JMG:JoinGroupChat', $content);
            if (json_decode($info) -> code == 200) {
                return json(array('result' => 'success', 'msg'=>'系统消息发送成功'));
            }else {
                return json(array('result' => 'error', 'msg' => '系统消息发送失败'));
            }
        }else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //群主确认列表
    public function confirmList($groupID, $userId, $targetID) {
        $param = $this->request->param();
        if (!empty($param)) {
            if ($param['targetID']) {
                $targetID = explode(',', $param['targetID']);
            }else {
                $targetID = [];
            }
            $inviter = Db::table('jingo_users') -> field(['userId', 'userPhoto', 'userName']) -> where('userId', $param['userId']) -> find();
            $inviter['groupID'] = $param['groupID'];
            $askList = Db::table('jingo_users') -> field(['userId', 'userPhoto', 'userName']) -> where('userId', 'in', $targetID) -> select();
            $groups = Db::table('jingo_user_groupmembers') -> field('userId') -> where('groupID', $param['groupID']) -> select();
            $members = [];
            foreach ($groups as $v) {
                $members[] = $v['userId'];
            }
            foreach ($targetID as $k => $v) {
                if (in_array($v, $members)) {
                    unset($targetID[$k]);
                }
            }
            if (!empty($targetID)) {
                $state = 0;
            }else {
                $state = 1;
            }
            if ($askList) {
                return json(array('result' => 'success', 'state'=>$state, 'inviter'=>$inviter, 'value'=>$askList));
            }else {
                return json(array('result' => 'success', 'state'=>$state, 'inviter'=>$inviter, 'value'=>$askList));
            }

        }else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //群主确认
    public function confirm($groupID, $userId, $inviteID, $targetID) {
        $param = $this->request->param();
        $groupMember = new GroupMembers();
        $isJoin = Db::table('jingo_user_groups') -> field(['isJoin', 'adminID']) -> where('groupID', $param['groupID']) -> find();
        if (!empty($param)) {
            $targetID = explode(',', $param['targetID']);
            $arr = Db::table('jingo_user_groupmembers')->field(['userId'])->where('groupID', $param['groupID'])->select();
            $members = [];
            foreach ($arr as $v) {
                $members[] = $v['userId'];      //获取并组合成一维群用户数组
            }
            $count = count($targetID);
            if (count($targetID) > 1) {         //剔除出已添加到群成员表里的人员
                for ($i = 0; $i < $count; $i++) {
                    if (in_array($targetID[$i], $members)) {
                        unset($targetID[$i]);
                    }
                }
            } else {
                if (in_array($targetID[0], $members)) {
                    exit();
                }
            }
            $data = [];
            if (empty($targetID)) return json(array('result' => 'error', 'msg' => '您添加的好友已在该群中'));
            $arr = Db::table('jingo_users')->field(['userId', 'userName', 'userPhoto'])->where('userId', 'in', $targetID)->select();
            $persons = '';
            for ($i = 0, $count = count($arr); $i < $count; $i++) {
                $data[$i]['groupID'] = $param['groupID'];
                $data[$i]['userId'] = $arr[$i]['userId'];
                //$data[$i]['groupNickName'] = $arr[$i]['userName'];
                $data[$i]['groupNickName'] = '';
                $data[$i]['groupPhoto'] = $arr[$i]['userPhoto'];
                $persons .= $arr[$i]['userName'].'、';
            }
            if ($isJoin['adminID'] == $param['userId']) {           //判断是否为群主
                $groupMember -> startTrans();
                if ($groupMember->saveAll($data)) {           //添加多个好友进群
                    $info = $this->RongCloud->joins($targetID, $param['groupID'], ' ');
                    $self = Db::table('jingo_user_groupmembers') -> field('groupNickName') -> where(['userId'=>$param['inviteID'], 'groupID'=>$param['groupID']]) -> find();
                    if ($self['groupNickName'] == '') {
                        $selfInfo = Db::table('jingo_users') -> field('userName') -> find($param['inviteID']);
                        $self['groupNickName'] = $selfInfo['userName'];
                    }
                    $persons = mb_substr($persons, 0, -1);
                    $content = '{"message":"'.$self['groupNickName'].'邀请\''.$persons.'\'进群了"}';
                    $info1 = $this->RongCloud->publishGroups($param['userId'], $param['groupID'], 'RC:InfoNtf', $content, '', '', 1, 1, 1);
                    if (json_decode($info)->code == 200 && json_decode($info1)->code == 200) {
                        $groupMember -> commit();
                        return json(array('result' => 'success', 'msg' => '你已经邀请该好友进群'));
                    } else {
                        $groupMember -> rollback();
                        return json(array('result' => 'error', 'msg' => '接入融云第三方失败'));
                    }
                } else {
                    return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
                }
            }else {
                return json(array('result' => 'error', 'msg' => '您不是群主,无权进行此操作'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //生成群二维码
    public function setQRcode($groupID, $userId)
    {
        include_once "phpqrcode.php";
        $param = $this->request->param();
        $serialize = json_encode([              //二维码信息
            'groupID' => $param['groupID'],
            'userId' => $param['userId']
        ]);
        $rand = mt_rand(10000000, 99999999);
        $time = time();
        $name = md5($rand) . md5($time);          //生成二维码名
        $date = date("Ymd", time());
        if (!file_exists(ROOT_PATH . 'upload/qrcode/group/' . $date)) {
            mkdir(ROOT_PATH . 'upload/qrcode/group/' . $date);
        }

        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 1;//生成图片大小
        $path = $date . '/' . $name . '.png';
        $map = [
            'groupID' => $param['groupID'],
            'userId' => $param['userId']
        ];
        $data = Db::table('jingo_user_groups') -> field(['icon']) -> where('groupID', $param['groupID']) -> find();
        QRcode::png($serialize, ROOT_PATH . 'upload/qrcode/group/' . $date . '/' . $name . '.png', $errorCorrectionLevel, $matrixPointSize, 2);
        Db::startTrans();
        if (file_exists(ROOT_PATH . 'upload/qrcode/group/' . $date . '/' . $name . '.png') && Db::table('jingo_user_groupmembers')->where($map)->update(['qrcode' => $path])) {
            if (file_exists(ROOT_PATH . 'upload/qrcode/group/'.$data['icon'])) {
                unlink(ROOT_PATH . 'upload/qrcode/group/'.$data['icon']);
            }
            Db::commit();
            return json(array('result' => 'success', 'msg' => '已生成群二维码'));
        } else {
            Db::rollback();
            return json(array('result' => 'error', 'msg' => '生成群二维码失败,请稍候重试'));
        }
    }

    public function QRcodeInfo($groupID, $userId) {
        $param = $this->request->param();
        $data = Db::table('jingo_user_groups') -> field(['groupID', 'adminID', 'name', 'icon']) -> where('groupID', $param['groupID']) -> find();
        if ($data) {
            $count = Db::table('jingo_user_groupmembers') -> field(['id']) -> where('groupID', $param['groupID']) -> count();
            $data['count'] = $count;
        }else {
            $data = [];
        }
        if (!empty($param)) {
            if (Db::table('jingo_user_groupmembers') -> where(['groupID'=>$param['groupID'], 'userId'=>$param['userId']]) -> find()) {
                return json(array('result' => 'success', 'isIn'=>1, 'value' => $data));
            }else {
                return json(array('result' => 'success', 'isIn'=>0, 'value' => $data));
            }
        }else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }


}