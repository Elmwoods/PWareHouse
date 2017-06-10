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
        $param = $this -> request -> param();
        if (!empty($param['userId'])) {      //判断是否post发送了userId
            //获取上传图片
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
            }
            $users = db('user_friends');
            $arr = $users -> where('userId', $param['userId']) -> where('isAgreed', 1) -> field('friendID') -> select();        //通过userId查询好友id集合
            $friendStr = '';            //朋友ID组合,以逗号隔开
            foreach ($arr as $v) {      //将好友id循环组合成以逗号隔开的字符串
                $friendStr .= $v['friendID'].',';
            }
            $friendStr = substr($friendStr, 0, -1);     //去除最后一个逗号
            $user_circle = db('user_circle');
            $data = array(                              //插入数据集合
                'pic' => $pic,                 //朋友圈图片
                'content' => $param['content'],         //朋友圈内容
                'createTime' => time(),                 //朋友圈发送时间
                'fromUserId' => $param['userId'],       //朋友圈发送者
                'toUserId' => $friendStr,               //朋友圈接收者
                'longitude' => $param['longitude'],   //经度
                'latitude' => $param['latitude'],      //纬度
            );
            if ($id = $user_circle -> insertGetId($data)) {
                return json(array('result'=>'success', 'value'=>$id));
            }else {
                return json(array('result'=>'error', 'value'=>'发送朋友圈失败'));
            }
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }
    
    //朋友圈信息列表查询
    public function show($userId) {
        $param = $this -> request -> param();
        if (!empty($param['userId'])) {      //判断是否post发送了userId
            $user_circle = db('user_circle');
            $arr = $user_circle -> where("toUserId like '%{$param['userId']}%'") ->order('createTime', 'DESC') -> select();     //根据发送过来的userId和时间的倒叙查询所有的朋友圈信息
            return json($arr);              //返回朋友圈内容列表
        }
    }

    //朋友圈详情
    public function detail($circleID) {
        $param = $this->request->param();
        if (!empty($param)) {
            $field = [
                'b.userId',
                'c.nickName',
                'b.userPhoto',
                'a.content',
                'a.createTime',
                'a.voteID',
            ];
            $arr = Db::table('jingo_user_circle a') -> join('jingo_users b', 'a.fromUserId = b.userId', 'LEFT') -> join('jingo_user_friends c', 'c.userId = b.userId', 'LEFT') -> field($field) -> where('circleID', $param['circleID']) -> find();
            $voteID = explode(',', $arr['voteID']);
            $arr2 = Db::table('jingo_users') -> field('userPhoto') -> where('userId', 'in', $voteID) -> select();
            echo '<pre />';
            $photoArr = [];
            foreach ($arr2 as $v) {
                $photoArr[] = $v['userPhoto'];
            }
            $arr['voteID'] = $photoArr;
            print_r($arr);
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }

    //评论朋友圈
    public function comment() {
        $param = $this->request->param();
        if (!empty($param)) {
            
        }else {
            return json(array('result'=>'error', 'value'=>'请传参'));
        }
    }
}