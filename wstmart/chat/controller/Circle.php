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

class Circle extends Controller {
    public $request;

    public function __construct() {
        $this -> request = request();
    }

    //新增一条朋友圈信息
    public function add() {

        /*//获取上传图片
        $files = request()->file('pic');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($files) {
            if (!file_exists(ROOT_PATH.'upload/circle/')) {
                mkdir(ROOT_PATH.'upload/circle/');
            }
            foreach($files as $file){
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->validate(['size'=>2*1024*1024,'ext'=>'jpg,png,gif'])->move(ROOT_PATH.'upload/circle/');
                if($info){
                    $path[] = date('Ymd').'/'.$info->getFilename();
                }else{
                    // 上传失败获取错误信息
                    echo $file->getError();
                }
            }
            $pic = implode(',', $path);
            echo $pic;
        }else {
            return json(array('result'=>'error', 'value'=>'上传图片失败'));
        }exit();*/

        $param = $this -> request -> param();
        if (!empty($param['userId'])) {      //判断是否post发送了userId
            /*//获取上传图片
            $files = request()->file('pic');
            // 移动到框架应用根目录/public/uploads/ 目录下
            if ($files) {
                if (!file_exists(ROOT_PATH.'upload/circle/')) {
                    mkdir(ROOT_PATH.'upload/circle/');
                }
                foreach($files as $file){
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->validate(['size'=>2*1024*1024,'ext'=>'jpg,png,gif'])->move(ROOT_PATH.'upload/circle/');
                    if($info){
                        $path[] = date('Ymd').'/'.$info->getFilename();
                    }else{
                        // 上传失败获取错误信息
                        echo $file->getError();
                    }
                }
                $pic = implode(',', $path);
            }else {
                return json(array('result'=>'error', 'value'=>'上传图片失败'));
            }*/
            $users = db('user_friends');
            $arr = $users -> where('userId', $param['userId']) -> where('isAgreed', 1) -> field('friendID') -> select();        //通过userId查询好友id集合
            $friendStr = '';            //朋友ID组合,以逗号隔开
            foreach ($arr as $v) {      //将好友id循环组合成以逗号隔开的字符串
                $friendStr .= $v['friendID'].',';
            }
            $friendStr = substr($friendStr, 0, -1);     //去除最后一个逗号
            $user_circle = db('user_circle');
            $data = array(                              //插入数据集合
                //'pic' => $pic,                          //朋友圈图片
                'content' => $param['content'],         //朋友圈内容
                'createTime' => time(),                 //朋友圈发送时间
                'fromUserId' => $param['userId'],       //朋友圈发送者
                'toUserId' => $friendStr,               //朋友圈接收者
                'longitude' => $param['longitude'],     //经度
                'latitude' => $param['latitude'],       //纬度
            );
            if ($id = $user_circle -> insertGetId($data)) {
                return json(array('result'=>'success', 'value'=>$id));
            }else {
                return json(array('result'=>'error', 'value'=>'发送朋友圈失败,请稍候重试'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }
    
    //朋友圈信息列表查询
    public function show($userId) {
        $param = $this -> request -> param();
        if (!empty($param)) {      //判断是否post发送了userId
            $arr = Db::table('jingo_user_circle') -> field('pic') -> where('fromUserId', $param['userId']) -> select();
            $arr2 = [];
            foreach ($arr as $v) {
                $arr2[] = $v['pic'];
            }
            $pic = implode(',', $arr2);
            $fields = [
                'a.pic',            //朋友圈图片
                'a.content',        //朋友圈内容
                'a.voteID',         //点赞者ID
                'a.commentNum',     //评论数
                'b.userPhoto',      //朋友头像
                'c.nickName'        //朋友备注名
            ];
            $arr3 = Db::table('jingo_user_circle a') -> field($fields) -> join('jingo_users b', 'b.userId = a.fromUserId', 'LEFT') -> join('jingo_user_friends c', 'c.friendID = a.fromUserId') -> where("a.toUserId like '%{$param['userId']}%'") -> where('c.userId', $param['userId']) -> order('a.createTime', 'DESC') -> select();
            for ($i=0; $i < count($arr3); $i++) {
                $arr3[$i]['voteID'] = count(explode(',', $arr3[$i]['voteID']));
            }
            /*echo '<pre />';
            print_r($arr3);
            exit();*/
            if ($arr3) {
                return json(array('result'=>'success', 'value'=>['album' =>$pic, 'list' => $arr3]));
            }else {
                return json(array('result'=>'error', 'value'=>'网络又开小差了,请稍候重试'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }

    //朋友圈详情
    public function detail($circleID, $userId) {
        $param = $this->request->param();
        if (!empty($param)) {
            $field = [
                'c.nickName',
                'b.userPhoto',
                'a.content',
                'a.createTime',
                'a.voteID',
                'a.fromUserId',
                'a.circleID',
                'a.commentNum'
            ];
            $arr = Db::table('jingo_user_circle a') -> join('jingo_users b', 'a.fromUserId = b.userId', 'LEFT') -> join('jingo_user_friends c', 'c.friendID = a.fromUserId', 'LEFT') -> field($field) -> where('circleID', $param['circleID']) -> where('c.userId', $param['userId']) -> find();        //查询朋友圈信息
            $voteID = explode(',', $arr['voteID']);
            $arr2 = Db::table('jingo_users') -> field('userPhoto') -> where('userId', 'in', $voteID) -> select();       //查询评论者的头像
            $arr3 = Db::table('jingo_user_circlecomment a') -> field(['a.commentID', 'a.commentText', 'a.commentTime', 'c.nickName', 'b.userPhoto']) -> join('jingo_users b', 'b.userId = a.commentID', 'LEFT') -> join('jingo_user_friends c', 'c.friendID = a.commentID', 'LEFT') -> where('a.circleID', $arr['circleID']) -> where('c.userId', $param['userId']) -> order('a.commentTime', 'ASC') -> select();       //联表查询评论人的相关信息级评论信息
            $commentID = Db::table('jingo_user_circlecomment') -> field(['commentID', 'commentText', 'commentTime']) -> select();       //查询朋友圈评论信息
            $self = [];     //自己的评论信息
            $i = 0;
            foreach ($commentID as $v) {            //组合自己发的评论消息内容
                if ($param['userId'] == $v['commentID']) {
                    $self[$i]['commentID'] = $v['commentID'];
                    $self[$i]['commentText'] = $v['commentText'];
                    $self[$i]['commentTime'] = $v['commentTime'];
                    $self[$i]['nickName'] = '你自己';
                    $self[$i]['userPhoto'] = '自己头像';
                    $i++;
                }
            }
            /*echo '<pre />';
            print_r($self);
            exit();*/
            $arr3[] = $self;
            $arr['comment'] = $arr3;
            $photoArr = [];
            foreach ($arr2 as $v) {
                $photoArr[] = $v['userPhoto'];
            }
            $arr['voteID'] = $photoArr;
            return json(array('result'=>'success', 'value'=>$arr));
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }

    //评论朋友圈
    public function comment() {
        $param = $this->request->param();
        if (!empty($param)) {
            $data = [
                'userId' => $param['fromUserId'],       //朋友圈发送者
                'circleID' => $param['circleID'],       //朋友圈ID
                'commentID' => $param['userId'],        //评论者ID
                'commentText' => $param['content'],     //评论内容
                'commentTime' => $param['date'],        //评论时间
            ];
            if (Db::table('jingo_user_circlecomment') -> insert($data)) {
                $commentNum = Db::table('jingo_user_circlecomment') -> where('circleID', $param['circleID']) -> count('circleID');
                if (Db::table('jingo_user_circle') -> where('circleID', $param['circleID']) -> update(['commentNum' => $commentNum])) {
                    return json(array('result'=>'success', 'value'=>'评论成功'));
                }else {
                    return json(array('result'=>'error', 'value'=>'评论数更新失败'));
                }
            }else {
                return json(array('result'=>'error', 'value'=>'网络又开小差了,请稍候重试'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }

    //朋友圈点赞
    public function vote($circleID, $userId) {
        $param = $this->request->param();
        if (!empty($param)) {
            $arr = Db::table('jingo_user_circle') -> field('voteID') -> where('circleID', $param['circleID']) -> find();
            $voteID = $arr['voteID'].','.$param['userId'];
            if (Db::table('jingo_user_circle') -> where('circleID', $param['circleID']) -> update(['voteID'=>$voteID])) {
                return json(array('result'=>'success', 'value'=>'点赞成功'));
            }else {
                return json(array('result'=>'error', 'value'=>'网络又开小差了,请稍候重试'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }


}