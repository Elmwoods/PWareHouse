<?php
/**
 * Created by PhpStorm.
 * User: Lynn
 * Date: 2017/4/26
 * Time: 9:21
 */

namespace wstmart\vv\controller;


use think\Controller;
use think\Db;
use think\Loader;
use wstmart\vv\model\GroupMembers;
use wstmart\vv\model\Groups;

class Group extends Controller {
    public $request;
    private $RongCloud;

    public function __construct() {
        $this -> request = request();
        $this->RongCloud = new Rongyun();
    }

    //创建群
    public function create() {

        $param = $this -> request -> param();
        if (!empty($param)) {      //判断是否post发送userId
            $user_group = db('user_groups');            //群表
            $members = explode(',', $param['groupMembers']);    //将群成员以逗号分割成数组
            unset($param['groupMembers']);                      //去除群成员字段
            $users = db('users');                       //用户总表
            $group_member = db('user_groupmembers');    //群用户表
            if (!($user_group -> where('adminID', $param['adminID']) -> where('name', $param['name']) -> find())) {
                $groupID = $user_group -> insertGetId($param);       //建群并返回群组ID
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
                        'groupID' => $groupID,
                    );
                }
                if ($group_member -> insertAll($values)) {
                    return json(array('result'=>'success', 'value'=>array('groupID'=>$groupID,'text'=>'已开启群聊,可以开始斗图了')));
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
    public function delete($name, $adminID) {
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
    public function info($groupID, $userId) {
        $param = $this -> request -> param();
        if (!empty($param['groupID'])) {
            if (Db::table('jingo_user_groups') -> where('groupID', $param['groupID']) -> where('adminID', $param['userId']) -> find()) {        //判断是否为群主
                $data = Db::table('jingo_user_groups a') -> join('jingo_user_groupmembers b', 'a.groupID = b.groupID', 'left') -> where(['a.groupID'=>$param['groupID'],'b.userId'=>$param['userId']]) -> find();
            }else {
                $data = Db::table('jingo_user_groups a') -> join('jingo_user_groupmembers b', 'a.groupID = b.groupID', 'left') -> where(['a.groupID'=>$param['groupID'],'b.userId'=>$param['userId']]) -> find();
                unset($data['isJoin']);
            }
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
    public function remove($name, $adminID, $userId) {
        $param = $this -> request -> param();
        if (!empty($param)) {
            $user_group = db('user_groups');            //群表
            $group_member = db('user_groupmembers');    //群用户表
            $arr = $user_group -> field('groupID') -> where('adminID', $param['adminID']) -> where('name', $param['name']) -> find();       //通过ID和群名查找群ID
            $userArr = explode(',', $param['userId']);      //要移除的成员数组了
            //if ($user_group -> where('adminID', $param['adminID']) -> where('groupID', $arr['groupID']) -> find()) {
            if ($arr) {
                //if ($group_member -> where('userId', $param['userId']) -> where('groupID', $arr['groupID']) -> delete()) {
                if ($group_member -> where('userId', 'in', $userArr) -> where('groupID', $arr['groupID']) -> delete()) {
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
    public function quit($groupID, $userId) {
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
            $map['userId'] = $param['userId'];
            $map['groupID'] = $param['groupID'];
            if (Db::table('jingo_user_groupmembers') -> where($map) -> update(['groupNickName'=>$param['nickName']])) {
                return json(array('result'=>'success', 'value'=>'您的大名已上史册'));
            }else {
                return json(array('result'=>'error', 'value'=>'网络又开小差了,请稍后重试'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }

    //群设置
    public function setting() {
        $param = $this -> request -> param();
        if (!empty($param['groupID'])) {
            $groups = new Groups();                     //实例化群表模型
            $groupMembers = new GroupMembers();         //实例化群用户表模型
            if (Db::table('jingo_user_groups') -> where('groupID', $param['groupID']) -> where('adminID', $param['userId']) -> find()) {        //判断是否为群主
                $boolean1 = $groups -> allowField(true) -> isUpdate(true) -> save($param);      //更新群表设置
                $boolean2 = $groupMembers -> allowField(true) -> isUpdate(true) -> save($param, ['groupID' => $param['groupID'], 'userId' => $param['userId']]);        //更新群用户表设置
            }else {
                if (!empty($param['isJoin'])) {     //如果存在isJoin字段则去除(群主权限,非群主不得更改)
                    unset($param['isJoin']);
                }
                $boolean1 = $groups -> allowField(true) -> isUpdate(true) -> save($param);      //更新群表设置
                $boolean2 = $groupMembers -> allowField(true) -> isUpdate(true) -> save($param, ['groupID' => $param['groupID'], 'userId' => $param['userId']]);        //更新群用户表设置
            }
            if ($boolean1 || $boolean2) {       //判断是否有更新成功
                return json(array('result'=>'success', 'value'=>'已修改群设置'));
            }else {
                return json(array('result'=>'error', 'value'=>'网络又开小差了,请稍候重试'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }

    //更换群主
    public function changeAdmin($groupID, $userId, $targetID) {
        $param = $this->request->param();
        if (!empty($param)) {
            if (Db::table('jingo_user_groups') -> where('groupID', $param['groupID']) -> where('adminID', $param['userId']) -> find()) {        //判断是否为群主
                if (Db::table('jingo_user_groups') -> where('groupID', $param['groupID']) -> update(['adminID'=>$param['targetID']])) {
                    return json(array('result'=>'success', 'value'=>'无上的权力你已经交给别人了'));
                }else {
                    return json(array('result'=>'error', 'value'=>'网络又开小差了,请稍候重试'));
                }
            }else {
                return json(array('result'=>'error', 'value'=>'群主也是你能换的?'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }

    //拉好友进群
    public function add($groupID, $userId, $targetID) {
        $param = $this->request->param();
        $groupMember = new GroupMembers();
        if (!empty($param)) {
            $map = [
                'userId' => $param['userId'],
                'groupID' => $param['groupID']
            ];
            $targetID = explode(',', $param['targetID']);
            $data = [];
            if (count($targetID) > 1) {         //判断目标ID是否为多个
                $arr = Db::table('jingo_users') -> field(['userId', 'userName', 'userPhoto']) -> where('userId', 'in', $targetID) -> select();
                for ($i=0; $i < count($arr); $i++) {
                    $data[$i]['groupID'] = $param['groupID'];
                    $data[$i]['userId'] = $arr[$i]['userId'];
                    $data[$i]['groupNickName'] = $arr[$i]['userName'];
                    $data[$i]['groupPhoto'] = $arr[$i]['userPhoto'];
                }
            }else {
                $arr = Db::table('jingo_users') -> field(['userId', 'userName', 'userPhoto']) -> where('userId', $targetID[0]) -> find();
                $data['userId'] = $arr['userId'];
                $data['groupNickName'] = $arr['userName'];
                $data['groupPhoto'] = $arr['userPhoto'];
                $data['groupID'] = $param['groupID'];
            }
            if (Db::table('jingo_user_groupmembers') -> where($map) -> find()) {        //判断是否为群内成员
                if (count($targetID) > 1) {                                             //判断目标ID是否为多个
                    if ($groupMember -> saveAll($data)) {           //添加好友进群
                        return json(array('result'=>'success', 'value'=>'你已经邀请该好友进群,请等待群主通过'));
                    }else {
                        return json(array('result'=>'error', 'value'=>'网络又开小差了,请稍候重试'));
                    }
                }else {
                    if ($groupMember -> save($data)) {           //添加好友进群
                        return json(array('result'=>'success', 'value'=>'你已经邀请该好友进群,请等待群主通过'));
                    }else {
                        return json(array('result'=>'error', 'value'=>'网络又开小差了,请稍候重试'));
                    }
                }
            }else {
                return json(array('result'=>'error', 'value'=>'你不是该群成员,无法拉好友进群'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }


    //生成群二维码
    public function setQRcode($groupID, $userId) {
        include_once "phpqrcode.php";
        $param = $this->request->param();
        $serialize = json_encode([              //二维码信息
            'groupID'=>$param['groupID'],
            'userId'=>$param['userId']
        ]);
        $rand = mt_rand(10000000, 99999999);
        $time = time();
        $name = md5($rand).md5($time);          //生成二维码名
        $date = date("Ymd",time());
        if (!file_exists(ROOT_PATH.'upload/qrcode/group/'.$date)) {
            mkdir(ROOT_PATH.'upload/qrcode/group/'.$date);
        }

        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 1;//生成图片大小
        $path = $date.'/'.$name.'.png';
        $map = [
            'groupID' => $param['groupID'],
            'userId' => $param['userId']
        ];
        QRcode::png($serialize, ROOT_PATH.'upload/qrcode/group/'.$date.'/'.$name.'.png', $errorCorrectionLevel, $matrixPointSize, 2);

        Db::table('jingo_user_groupmembers') -> where($map) -> update(['qrcode'=>$path]);
    }

}