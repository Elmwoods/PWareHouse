<?php
/**
 * Created by PhpStorm.
 * User: Lynn
 * Date: 2017/4/26
 * Time: 9:21
 */

namespace wstmart\chatBak\controller;


use think\Controller;
use think\Db;

class Circle extends Controller {
    public $request;

    public function __construct()
    {
        $this->request = request();
    }

    //新增一条朋友圈信息
    public function add($userId)
    {
        $param = $this->request->param();
        if (!empty($param['userId'])) {      //判断是否post发送了userId
            if (Api::checkApi($param['userId'], $param['token']) == 'false') exit();
            //获取上传图片
            $files = request()->file('pic');
            // 移动到框架应用根目录uploads/ 目录下
            if ($files) {
                if (!file_exists(ROOT_PATH . 'upload/circle/')) {
                    mkdir(ROOT_PATH . 'upload/circle/');
                }
                foreach ($files as $file) {
                    // 移动到框架应用根目录/uploads/ 目录下
                    $info = $file->validate(['size' => 5 * 1024 * 1024, 'ext' => 'jpg,png,gif,jpeg'])->move(ROOT_PATH . 'upload/circle/');
                    if ($info) {
                        $path[] = date('Ymd') . '/' . $info->getFilename();
                    } else {
                        // 上传失败获取错误信息
                        return json(array('result' => 'error', 'msg' => $file->getError()));
                    }
                }
                $pic = implode(',', $path);
            } else {
                return json(array('result' => 'error', 'msg' => '上传图片失败'));
            }
            $users = db('user_friends');
            $arr = $users->where('userId', $param['userId'])->where('isAgreed', 1)->field('friendID')->select();        //通过userId查询好友id集合
            $friendStr = '';            //朋友ID组合,以逗号隔开
            foreach ($arr as $v) {      //将好友id循环组合成以逗号隔开的字符串
                $friendStr .= $v['friendID'] . ',';
            }
            $friendStr = ','.$friendStr;
            //$friendStr = substr($friendStr, 0, -1);     //去除最后一个逗号
            $user_circle = db('user_circle');
            $data = array(                              //插入数据集合
                'pic' => $pic,                          //朋友圈图片
                'content' => $param['content'],         //朋友圈内容
                'createTime' => time(),                 //朋友圈发送时间
                'fromUserId' => $param['userId'],       //朋友圈发送者
                'toUserId' => $friendStr,               //朋友圈接收者
                'longitude' => $param['longitude'],     //经度
                'latitude' => $param['latitude'],       //纬度
            );
            if ($id = $user_circle->insertGetId($data)) {
                return json(array('result' => 'success', 'value' => ['circle' => $id]));
            } else {
                return json(array('result' => 'error', 'msg' => '发送朋友圈失败,请稍候重试'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //朋友圈信息列表查询
    /*public function show($userId)
    {
        $param = $this->request->param();
        if (!empty($param)) {      //判断是否post发送了userId
            //查询自己发送的朋友圈
            $arr = Db::table('jingo_user_circle')
                    ->field(['circleID', 'pic', 'content', 'voteID', 'commentNum', 'createTime'])
                    ->where('fromUserId', $param['userId'])
                    ->select();
            $fields = [             //字段合集
                'a.circleID',       //朋友圈ID
                'a.pic',            //朋友圈图片
                'a.content',        //朋友圈内容
                'a.voteID',         //点赞者ID
                'a.commentNum',     //评论数
                'a.createTime',     //朋友圈发送时间
                'b.userPhoto',      //朋友头像
                'c.nickName',       //朋友备注名
                'c.friendID',       //朋友ID
            ];
            //查询不看的朋友圈的朋友ID
            $arr4 = Db::table('jingo_user_friends') -> field('friendID') -> where(['userId'=>$param['userId'], 'circleEnable'=>1]) -> select();
            $unable = [];       //不看的朋友ID集合
            if ($arr4) {
                foreach ($arr4 as $item) {
                    $unable[] = $item['friendID'];
                }
            }
            if (empty($unable)) {
                //获取朋友发送的朋友圈(筛选掉不看的朋友圈)
                $arr3 = Db::table('jingo_user_circle a')
                    ->field($fields)
                    ->join('jingo_users b', 'b.userId = a.fromUserId', 'LEFT')
                    ->join('jingo_user_friends c', 'c.friendID = a.fromUserId')
                    ->where("a.toUserId like '%{$param['userId']}%'")
                    ->where('c.isBlackList', 0)
                    ->where('c.userId', $param['userId'])
                    ->order('a.createTime', 'DESC')
                    ->select();
            }else {
                //获取朋友发送的朋友圈(筛选掉不看的朋友圈)
                $arr3 = Db::table('jingo_user_circle a')
                    ->field($fields)
                    ->join('jingo_users b', 'b.userId = a.fromUserId', 'LEFT')
                    ->join('jingo_user_friends c', 'c.friendID = a.fromUserId')
                    ->where("a.toUserId like '%{$param['userId']}%'")
                    ->where('c.isBlackList', 0)
                    ->where('c.userId', $param['userId'])
                    ->where('a.fromUserId', 'not in', $unable)
                    ->order('a.createTime', 'DESC')
                    ->select();
            }
            $friends = [];      //接收到朋友圈的朋友ID
            if ($arr3) {
                foreach ($arr3 as $v) {
                    $friends[] = $v['friendID'];
                }
                $friends = array_unique($friends);
                //查询不让看的朋友ID
                $arr5 = Db::table('jingo_user_friends')
                        -> field('userId')
                        -> where('userId', 'in', $friends)
                        -> where('friendID', $param['userId'])
                        -> where(function ($query) {
                            $query->where('isCheck', 1)->whereor('isBlackList', 1);
                        }) -> select();
                $unable1 = [];      //不让看的朋友ID集合
                foreach ($arr5 as $v) {
                    $unable1[] = $v['userId'];
                }
                for ($i=0;$i < count($arr3); $i++) {
                    if (in_array($arr3[$i]['friendID'], $unable1)) {
                        $arr3[$i]['isShow'] = 0;
                        $arr3[$i]['isMe'] = 0;
                    }else {
                        $arr3[$i]['isShow'] = 1;
                        $arr3[$i]['isMe'] = 0;
                    }
                }
            }
            for ($i = 0; $i < count($arr); $i++) {
                $arr[$i]['nickName'] = '你自己';
                $arr[$i]['userPhoto'] = '你的头像';
                $arr[$i]['isMe'] = 1;
            }
            $data = array_merge($arr3, $arr);
            $sort = [];
            for ($i = 0; $i < count($data); $i++) {
                if (strstr($data[$i]['voteID'], $param['userId'])) {          //判断点赞人中是否有自己
                    $data[$i]['state'] = 1;
                } else {
                    $data[$i]['state'] = 0;
                }
                if ($data[$i]['voteID'] != null) {                          //判断是否有人点赞
                    $data[$i]['voteID'] = count(explode(',', $data[$i]['voteID']));     //如果有则统计点赞总数
                } else {
                    $data[$i]['voteID'] = 0;
                }
                $sort[] = $data[$i]['createTime'];
            }
            array_multisort($sort, SORT_DESC, $data);
            $data = page($data, $param['page'], 10);
            return json(array('result' => 'success', 'value' => $data));
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }*/

    //朋友圈信息查询(优化版)
    public function test() {
        $param = $this->request->param();
        if (!empty($param)) {
            //获取自己发的和发给自己的朋友圈
            $data = Db::table('jingo_user_circle')
                -> field(['circleID', 'pic', 'content', 'createTime', 'fromUserId', 'voteID', 'commentNum'])
                -> where('fromUserId', $param['userId'])
                -> whereOr("toUserId like '%,{$param['userId']},%'")
                -> order('createTime', 'DESC')
                -> paginate(10);
            $info = $data->toArray()['Rows'];
            //查询不看的朋友圈
            $arr1 = Db::table('jingo_user_friends') -> field(['friendID']) -> where(['userId'=>$param['userId']]) -> where(function ($query) {$query -> where('isBlackList', 1) -> whereOr('circleEnable', 1);}) -> select();
            //查询不让看的朋友圈
            $arr2 = Db::table('jingo_user_friends') -> field(['userId']) -> where(['friendID'=>$param['userId']]) -> where(function ($query) {$query -> where('isBlackList', 1) -> whereOr('isCheck', 1);}) -> select();
            //查询个人信息
            $self = Db::table('jingo_users') -> field(['userName', 'userPhoto']) -> where('userId', $param['userId']) -> find();
            //查询朋友列表
            $arr3 = Db::table('jingo_user_friends') -> field('friendID') -> where('userId', $param['userId']) -> select();
            $friends = [];      //朋友ID集合
            $unable = [];      //禁止看的朋友圈集合
            foreach ($arr1 as $v) {
                $unable[] = $v['friendID'];
            }
            foreach ($arr2 as $v) {
                $unable[] = $v['userId'];
            }
            foreach ($arr3 as $v) {
                $friends[] = $v['friendID'];
            }
            $friends[] = $param['userId'];
            array_unique($unable);      //去除重复字段
            foreach ($info as $k => $v) {
                if ($v['fromUserId'] == $param['userId']) {
                    $info[$k]['userPhoto'] = $self['userPhoto'];
                    $info[$k]['nickName'] = $self['userName'];
                    $info[$k]['isMe'] = '1';        //是否是自己 (0否1是)
                }else {
                    $data2 = Db::table('jingo_users a') -> field(['a.userPhoto', 'b.nickName']) -> join('jingo_user_friends b', 'a.userId = b.friendID', 'LEFT') -> where(['b.userId'=>$param['userId'], 'b.friendID'=>$v['fromUserId']]) -> find();
                    $info[$k]['userPhoto'] = $data2['userPhoto'];
                    $info[$k]['nickName'] = $data2['nickName'];
                    $info[$k]['isMe'] = '0';
                }
                if (in_array($v['fromUserId'], $unable)) {
                    $info[$k]['isShow'] = '0';      //是否显示 (0否1是)
                }else {
                    $info[$k]['isShow'] = '1';
                }
                if (strstr($v['voteID'], $param['userId'])) {
                    $info[$k]['state'] = '1';       //自己是否点赞 (0否1是)
                }else {
                    $info[$k]['state'] = '0';
                }
                //调整点赞人显示内容
                if ($v['voteID'] != null) {
                    if (isset($param['clientType'])) {
                        $voteID = explode(',', $v['voteID']);
                        $arr3 = Db::table('jingo_users') -> field(['userPhoto']) -> where('userId', 'in', $voteID) -> select();
                        $info[$k]['voteID'] = [];
                        if ($arr3) {
                            foreach ($arr3 as $item) {
                                $info[$k]['voteID'][] = $item['userPhoto'];
                            }
                        }
                    }else {
                        $info[$k]['voteID'] = count(explode(',', $v['voteID']));
                    }
                }else {
                    if (isset($param['clientType'])) {
                        $info[$k]['voteID'] = [];
                    }else {
                        $info[$k]['voteID'] = 0;
                    }
                }
                //增加评论信息详情(ios使用)
                if (isset($param['clientType'])) {
                    //查询朋友圈评论
                    $comment = Db::table('jingo_user_circlecomment a')->field(['a.commentID', 'a.commentText', 'a.commentTime', 'b.userPhoto', 'b.userName'])->join('jingo_users b', 'b.userId = a.commentID', 'LEFT')->where('a.circleID', $info[$k]['circleID'])->order('a.commentTime', 'DESC')->select();
                    $info[$k]['comment'] = [];
                    if ($comment) {
                        foreach ($comment as $val) {
                            $info[$k]['comment'][] = $val;
                        }
                    }
                }
            }
            $data = [];
            foreach ($info as $k => $v) {
                if (!(in_array($v['fromUserId'], $friends))) {
                    $info[$k]['isShow'] = 0;
                }
                if ($v['isShow'] == 1) {
                    $data[] = $info[$k];
                }
            }
            return json(array('result' => 'success', 'value' => $data));
        }else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }


    //朋友圈信息列表查询
    public function circleList($userId)
    {
        $param = $this->request->param();
        if (!empty($param)) {      //判断是否post发送了userId
            //查询自己发送的朋友圈
            $arr = Db::table('jingo_user_circle')
                ->field(['circleID', 'pic', 'content', 'voteID', 'createTime'])
                ->where('fromUserId', $param['userId'])
                ->select();
            $fields = [             //字段合集
                'a.circleID',       //朋友圈ID
                'a.pic',            //朋友圈图片
                'a.content',        //朋友圈内容
                'a.voteID',         //点赞者ID
                'a.createTime',     //朋友圈发送时间
                'b.userPhoto',      //朋友头像
                'c.nickName',       //朋友备注名
                'c.friendID',       //朋友ID
            ];
            //查询不看的朋友圈的朋友ID
            $arr4 = Db::table('jingo_user_friends') -> field('friendID') -> where(['userId'=>$param['userId'], 'circleEnable'=>1]) -> select();
            $unable = [];       //不看的朋友ID
            foreach ($arr4 as $item) {
                $unable[] = $item['friendID'];
            }
            if (empty($unable)) {
                //获取朋友发送的朋友圈(筛选掉不看的朋友圈)
                $arr3 = Db::table('jingo_user_circle a')
                    ->field($fields)
                    ->join('jingo_users b', 'b.userId = a.fromUserId', 'LEFT')
                    ->join('jingo_user_friends c', 'c.friendID = a.fromUserId')
                    ->where("a.toUserId like '%{$param['userId']}%'")
                    ->where('c.isBlackList', 0)
                    ->where('c.userId', $param['userId'])
                    ->order('a.createTime', 'DESC')
                    ->select();
            }else {
                //获取朋友发送的朋友圈(筛选掉不看的朋友圈)
                $arr3 = Db::table('jingo_user_circle a')
                    ->field($fields)
                    ->join('jingo_users b', 'b.userId = a.fromUserId', 'LEFT')
                    ->join('jingo_user_friends c', 'c.friendID = a.fromUserId')
                    ->where("a.toUserId like '%{$param['userId']}%'")
                    ->where('c.isBlackList', 0)
                    ->where('c.userId', $param['userId'])
                    ->where('a.fromUserId', 'not in', $unable)
                    ->order('a.createTime', 'DESC')
                    ->select();
            }
            $friends = [];      //接收到朋友圈的朋友ID
            if ($arr3) {
                foreach ($arr3 as $v) {
                    $friends[] = $v['friendID'];
                }
                $friends = array_unique($friends);
                //查询不让看的朋友ID
                $arr5 = Db::table('jingo_user_friends')
                    -> field('userId')
                    -> where('userId', 'in', $friends)
                    -> where('friendID', $param['userId'])
                    -> where(function ($query) {
                        $query->where('isCheck', 1)->whereor('isBlackList', 1);
                    }) -> select();
                $unable1 = [];      //不让看的朋友ID集合
                foreach ($arr5 as $v) {
                    $unable1[] = $v['userId'];
                }
                for ($i=0;$i < count($arr3); $i++) {
                    if (in_array($arr3[$i]['friendID'], $unable1)) {
                        $arr3[$i]['isShow'] = 0;
                    }else {
                        $arr3[$i]['isShow'] = 1;
                    }
                }
            }
            for ($i = 0; $i < count($arr); $i++) {
                $arr[$i]['nickName'] = '你自己';
                $arr[$i]['userPhoto'] = '你的头像';
                $arr[$i]['isMe'] = 1;
            }
            $data = array_merge($arr3, $arr);
            $sort = [];
            for ($i = 0; $i < count($data); $i++) {
                if (strstr($data[$i]['voteID'], $param['userId'])) {          //判断点赞人中是否有自己
                    $data[$i]['state'] = 1;
                } else {
                    $data[$i]['state'] = 0;
                }
                if ($data[$i]['voteID'] != null) {
                    $data[$i]['voteID'] = explode(',', $data[$i]['voteID']);
                    $pictures = Db::table('jingo_users') -> field(['userPhoto']) -> where('userId', 'in', $data[$i]['voteID']) -> select();
                    $data[$i]['voteID'] = [];
                    foreach ($pictures as $v) {
                        $data[$i]['voteID'][] = $v['userPhoto'];
                    }
                }else {
                    $data[$i]['voteID'] = [];
                }
                $comment = Db::table('jingo_user_circlecomment a')->field(['a.commentID', 'a.commentText', 'a.commentTime', 'c.nickName', 'b.userPhoto'])->join('jingo_users b', 'b.userId = a.commentID', 'LEFT')->join('jingo_user_friends c', 'c.friendID = a.commentID', 'LEFT')->where('a.circleID', $data[$i]['circleID'])->where('c.userId', $param['userId'])->order('a.commentTime', 'DESC')->select();
                if ($comment != null) {
                    $data[$i]['comment'] = [];
                    foreach ($comment as $v) {
                        $data[$i]['comment'][] = $v;
                    }
                }else {
                    $data[$i]['comment'] = [];
                }
                $sort[] = $data[$i]['createTime'];
            }
            array_multisort($sort, SORT_DESC, $data);
            $data = page($data, $param['page'], 10);
            return json(array('result' => 'success', 'value' => $data));
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //朋友圈图片
    public function circlePic() {
        $param = $this->request->param();
        if (!empty($param)) {
            if (Api::checkApi($param['userId'], $param['token']) == 'false') exit();
            $arr = Db::table('jingo_user_circle')
                ->field(['pic'])
                ->where('fromUserId', $param['userId'])
                ->order('createTime', 'DESC')
                ->paginate(5);
            $arr = $arr -> toArray()['Rows'];
            /*echo '<pre />';
            print_r($arr);
            exit();*/
            if ($arr) {
                $arr2 = [];
                foreach ($arr as $v) {      //将自己发送的朋友圈图片组合成一维数组
                    if ($v['pic'] != null) {
                        $arr2[] = $v['pic'];
                    }
                }
                if (is_array($arr2)) {              //判断是不是数组
                    $pic = implode(',', $arr2);     //按逗号组合成字符串
                } else {
                    $pic = '';                      //如果没有图片则赋值为空字符串
                }
            }else {
                $pic = '';
            }
            return json(array('result' => 'success', 'value' => $pic));
        }
    }

    //朋友圈详情
    public function detail($circleID, $userId)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            if (Api::checkApi($param['userId'], $param['token']) == 'false') exit();
            $field = [
                'c.nickName',
                'b.userPhoto',
                'a.pic',
                'a.content',
                'a.createTime',
                'a.voteID',
                'a.fromUserId',
                'a.circleID',
                'a.commentNum',
            ];
            //查询朋友圈详情(不包括自己)
            $arr = Db::table('jingo_user_circle a')->join('jingo_users b', 'a.fromUserId = b.userId', 'LEFT')->join('jingo_user_friends c', 'c.friendID = a.fromUserId', 'LEFT')->field($field)->where('circleID', $param['circleID'])->where('c.userId', $param['userId'])->find();
            //如果没有朋友圈详情则表示是自己发的,查询相关信息
            if (empty($arr)) {
                $arr = Db::table('jingo_user_circle')->field(['pic', 'content','createTime','voteID','fromUserId','circleID','commentNum']) -> where(['fromUserId'=>$param['userId'], 'circleID'=>$param['circleID']]) -> find();        //查询自己发送的朋友圈
                $self = Db::table('jingo_users') -> field(['userName', 'userPhoto']) -> where('userId', $arr['fromUserId']) -> find();
                $arr['nickName'] = $self['userName'];
                $arr['userPhoto'] = $self['userPhoto'];
            }
            if (strstr($arr['voteID'], ',')) {
                $voteID = explode(',', $arr['voteID']);
                rsort($voteID);
            }elseif ($arr['voteID'] != ''){
                $voteID[] = $arr['voteID'];
            }else {
                $voteID = [];
            }
            //判断自己是否点赞
            if (in_array($param['userId'], $voteID)) {
                $a = 1;
            }else {
                $a = 0;
            }
            //查询点赞者的头像
            if (!empty($voteID)) {
                $arr2 = Db::table('jingo_users')->field('userPhoto')->where('userId', 'in', $voteID)->limit(0,8)->select();
                $photoArr = [];
                //将点赞者的头像组合成一维数组
                foreach ($arr2 as $v) {
                    $photoArr[] = $v['userPhoto'];
                }
            }else {
                $photoArr = [];
            }
            //联表查询评论人的相关信息及评论信息
            $arr3 = Db::table('jingo_user_circlecomment a')->field(['a.commentID', 'a.commentText', 'a.commentTime', 'b.userName', 'b.userPhoto'])->join('jingo_users b', 'b.userId = a.commentID', 'LEFT')->where('a.circleID', $param['circleID'])->order('a.commentTime', 'DESC')->paginate(10);
            $arr3 = $arr3 ->toArray()['Rows'];

            foreach ($arr3 as $k => $v) {
                $arr3[$k]['commentText'] = textDecode($v['commentText']);
            }

            /*//查询朋友圈评论信息
            $commentID = Db::table('jingo_user_circlecomment')->field(['commentID', 'commentText', 'commentTime'])->where('circleID', $param['circleID'])->select();
            $self = [];     //自己的评论信息
            $i = 0;
            $sort = [];     //排序数组
            foreach ($commentID as $v) {            //组合自己发的评论消息内容
                if ($param['userId'] == $v['commentID']) {
                    $self[$i]['commentID'] = $v['commentID'];
                    $self[$i]['commentText'] = $v['commentText'];
                    $self[$i]['commentTime'] = $v['commentTime'];
                    $self[$i]['nickName'] = '你自己';
                    $self[$i]['userPhoto'] = '自己头像';
                    $arr3[] = $self[$i];
                    $i++;
                }
            }
            foreach ($arr3 as $v) {     //按时间获取排序数组
                $sort[] = $v['commentTime'];
            }
            array_multisort($sort, SORT_DESC, $arr3);*/
            if ($arr) {
                $arr['comment'] = $arr3;
                $arr['voteID'] = $photoArr;
                $arr['isVote'] = $a;
            }else {
                $arr = new \stdClass();
            }
            return json(array('result' => 'success', 'value' => $arr));
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //朋友圈详情(仅限于查看陌生人朋友圈详情)
    public function strangerDetail($circleID, $userId)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            if (Api::checkApi($param['userId'], $param['token']) == 'false') exit();
            $field = [
                'b.userName',
                'b.userPhoto',
                'a.pic',
                'a.content',
                'a.createTime',
                'a.voteID',
                'a.fromUserId',
                'a.circleID',
                'a.commentNum',
            ];
            //查询朋友圈详情(不包括自己)
            $arr = Db::table('jingo_user_circle a')->join('jingo_users b', 'a.fromUserId = b.userId', 'LEFT')->field($field)->where('circleID', $param['circleID'])->find();
            if (strstr($arr['voteID'], ',')) {
                $voteID = explode(',', $arr['voteID']);
                rsort($voteID);
            }elseif ($arr['voteID'] != ''){
                $voteID[] = $arr['voteID'];
            }else {
                $voteID = [];
            }
            //判断自己是否点赞
            if (in_array($param['userId'], $voteID)) {
                $a = 1;
            }else {
                $a = 0;
            }
            //查询点赞者的头像
            if (!empty($voteID)) {
                $arr2 = Db::table('jingo_users')->field('userPhoto')->where('userId', 'in', $voteID)->limit(0,8)->select();
                $photoArr = [];
                //将点赞者的头像组合成一维数组
                foreach ($arr2 as $v) {
                    $photoArr[] = $v['userPhoto'];
                }
            }else {
                $photoArr = [];
            }
            //联表查询评论人的相关信息及评论信息
            $arr3 = Db::table('jingo_user_circlecomment a')->field(['a.commentID', 'a.commentText', 'a.commentTime', 'b.userName', 'b.userPhoto'])->join('jingo_users b', 'b.userId = a.commentID', 'LEFT')->where('a.circleID', $param['circleID'])->order('a.commentTime', 'DESC')->paginate(10);
            $arr3 = $arr3 ->toArray()['Rows'];
            //评论内容转码(处理emoji表情)
            foreach ($arr3 as $k => $v) {
                $arr3[$k]['commentText'] = textDecode($v['commentText']);
            }
            if ($arr) {
                $arr['comment'] = $arr3;
                $arr['voteID'] = $photoArr;
                $arr['isVote'] = $a;
            }else {
                $arr = new \stdClass();
            }
            return json(array('result' => 'success', 'value' => $arr));
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //评论朋友圈
    public function comment()
    {
        $param = $this->request->param();
        if (!empty($param)) {
            if (Api::checkApi($param['userId'], $param['token']) == 'false') exit();
            $data = [
                'userId' => $param['fromUserId'],       //朋友圈发送者
                'circleID' => $param['circleID'],       //朋友圈ID
                'commentID' => $param['userId'],        //评论者ID
                'commentText' => textEncode($param['content']),     //评论内容
                'commentTime' => $param['time'],        //评论时间
            ];
            Db::startTrans();
            if (Db::table('jingo_user_circlecomment')->insert($data)) {
                $commentNum = Db::table('jingo_user_circlecomment')->where('circleID', $param['circleID'])->count('circleID');
                if (Db::table('jingo_user_circle')->where('circleID', $param['circleID'])->update(['commentNum' => $commentNum])) {
                    Db::commit();
                    return json(array('result' => 'success', 'msg' => '评论数更新成功'));
                } else {
                    Db::rollback();
                    return json(array('result' => 'error', 'msg' => '评论数更新失败'));
                }
            } else {
                return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //朋友圈点赞
    public function vote($circleID, $userId)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            if (Api::checkApi($param['userId'], $param['token']) == 'false') exit();
            $arr = Db::table('jingo_user_circle')->field('voteID')->where('circleID', $param['circleID'])->find();
            if (!empty($arr['voteID'])) {
                if (!strstr($arr['voteID'], $param['userId'])) {
                    $voteID = $arr['voteID'] . ',' . $param['userId'];
                }else {
                    return json(array('result' => 'success', 'msg' => '点赞成功'));
                }
            } else {
                $voteID = $param['userId'];
            }
            if (Db::table('jingo_user_circle')->where('circleID', $param['circleID'])->update(['voteID' => $voteID])) {
                return json(array('result' => 'success', 'msg' => '点赞成功'));
            } else {
                return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //取消点赞
    public function unVote($circleID, $userId) {
        $param = $this->request->param();
        if (!empty($param)) {
            if (Api::checkApi($param['userId'], $param['token']) == 'false') exit();
            $arr = Db::table('jingo_user_circle')->field('voteID')->where('circleID', $param['circleID'])->find();      //获取点赞人信息
            if (!empty($arr['voteID'])) {
                if (!strstr($arr['voteID'], $param['userId'])) {
                    return json(array('result' => 'success', 'msg' => '取消点赞成功'));
                }
                $voteID = explode(',', $arr['voteID']);
                foreach ($voteID as $k => $v) {
                    if ($v == $param['userId']) {       //取消点赞
                        unset($voteID[$k]);
                    }
                }
                $voteID = implode(',', $voteID);
                if (Db::table('jingo_user_circle')->where('circleID', $param['circleID'])->update(['voteID' => $voteID])) {
                    return json(array('result' => 'success', 'msg' => '取消点赞成功'));
                } else {
                    return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
                }
            }
        }else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //我的相册
    public function album($userId, $page)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            if (Api::checkApi($param['userId'], $param['token']) == 'false') exit();
            $fields = [
                'circleID',         //朋友圈ID
                'pic',              //朋友圈图片
                'content',          //朋友圈内容
                'createTime',       //发送时间
                'voteID',           //点赞数
                'commentNum',       //评论数
            ];
            //查询自己的信息
            $arr = Db::table('jingo_users')->field(['userName', 'userPhoto', 'signature', 'userSex'])->where('userId', $param['userId'])->find();
            //查询朋友圈信息
            $arr2 = Db::table('jingo_user_circle')->field($fields)->where('fromUserId', $param['userId'])->order('createTime', 'DESC')->paginate(10);
            $arr2 = $arr2 -> toArray()['Rows'];
            if ($arr2) {
                for ($i = 0,$count = count($arr2); $i < $count; $i++) {
                    //判断是否点赞,点赞为1
                    if (strstr($arr2[$i]['voteID'], $param['userId'])) {
                        $arr2[$i]['isVote'] = 1;
                    }else {
                        $arr2[$i]['isVote'] = 0;
                    }
                    //调整点赞人显示内容
                    if ($arr2[$i]['voteID'] != null) {
                        /*if (isset($param['clientType'])) {
                            $voteID = explode(',', $arr2[$i]['voteID']);
                            $arr3 = Db::table('jingo_users') -> field(['userPhoto']) -> where('userId', 'in', $voteID) -> select();
                            $arr2[$i]['voteID'] = [];
                            if ($arr3) {
                                foreach ($arr3 as $item) {
                                    $arr2[$i]['voteID'][] = $item['userPhoto'];
                                }
                            }
                        }else {*/
                            $arr2[$i]['voteID'] = count(explode(',', $arr2[$i]['voteID']));
                        /*}*/
                    }else {
                        /*if (isset($param['clientType'])) {
                            $arr2[$i]['voteID'] = [];
                        }else {*/
                            $arr2[$i]['voteID'] = 0;
                        /*}*/
                    }
                    //增加评论信息详情(ios使用)
                    /*if (isset($param['clientType'])) {
                        //查询朋友圈评论
                        $comment = Db::table('jingo_user_circlecomment a')->field(['a.commentID', 'a.commentText', 'a.commentTime', 'b.userPhoto', 'b.userName'])->join('jingo_users b', 'b.userId = a.commentID', 'LEFT')->where('a.circleID', $arr2[$i]['circleID'])->order('a.commentTime', 'DESC')->select();
                        $arr2[$i]['comment'] = [];
                        if ($comment) {
                            foreach ($comment as $val) {
                                $arr2[$i]['comment'][] = $val;
                            }
                        }
                    }*/
                }
            }
            if ($arr) {
                return json(array('result' => 'success', 'data'=>$arr, 'value' => $arr2));
            } else {
                return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //我的相册(ios版)
    public function iosAlbum($userId, $page)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            $fields = [
                'circleID',         //朋友圈ID
                'pic',              //朋友圈图片
                'content',          //朋友圈内容
                'createTime',       //发送时间
                'voteID',           //点赞数
                'commentNum',       //评论数
            ];
            //查询自己的信息
            $arr = Db::table('jingo_users')->field(['userName', 'userPhoto', 'signature', 'userSex'])->where('userId', $param['userId'])->find();
            //查询朋友圈信息
            $arr2 = Db::table('jingo_user_circle')->field($fields)->where('fromUserId', $param['userId'])->order('createTime', 'DESC')->select();
            if ($arr2) {
                for ($i = 0, $count=count($arr2); $i < $count; $i++) {
                    //判断是否点赞,点赞为1
                    if (strstr($arr2[$i]['voteID'], $param['userId'])) {
                        $arr2[$i]['isVote'] = 1;
                    }else {
                        $arr2[$i]['isVote'] = 0;
                    }
                    if ($arr2[$i]['voteID'] != null) {
                        $arr2[$i]['voteID'] = explode(',', $arr2[$i]['voteID']);
                        $pictures = Db::table('jingo_users') -> field(['userPhoto']) -> where('userId', 'in', $arr2[$i]['voteID']) -> select();
                        $arr2[$i]['voteID'] = [];
                        foreach ($pictures as $v) {
                            $arr2[$i]['voteID'][] = $v['userPhoto'];
                        }
                    } else {
                        $arr2[$i]['voteID'] = [];
                    }
                    $comment = Db::table('jingo_user_circlecomment a')->field(['a.commentID', 'a.commentText', 'a.commentTime', 'c.nickName', 'b.userPhoto'])->join('jingo_users b', 'b.userId = a.commentID', 'LEFT')->join('jingo_user_friends c', 'c.friendID = a.commentID', 'LEFT')->where('a.circleID', $arr2[$i]['circleID'])->where('c.userId', $param['userId'])->order('a.commentTime', 'ASC')->select();
                    if ($comment != null) {
                        $arr2[$i]['comment'] = [];
                        foreach ($comment as $v) {
                            $arr2[$i]['comment'][] = $v;
                        }
                    }else {
                        $arr2[$i]['comment'] = [];
                    }
                }
            }
            if ($arr) {
                $arr2 = page($arr2, $param['page'], 10);
                return json(array('result' => 'success', 'data'=>$arr, 'value' => $arr2));
            } else {
                return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //好友相册
    public function friendAlbum($userId, $friendID, $page) {
        $param = $this->request->param();
        if (!empty($param)) {
            if (Api::checkApi($param['userId'], $param['token']) == 'false') exit();
            //查询朋友信息
            $friendInfo = Db::table('jingo_user_friends a')
                    -> field(['a.nickName', 'b.userName', 'b.userPhoto', 'b.userSex', 'b.signature'])
                    -> join('jingo_users b', 'b.userId = a.friendID', 'LEFT')
                    -> where(['a.friendID'=>$param['friendID'], 'a.userId'=>$param['userId']])
                    -> find();
            if (Db::table('jingo_user_friends') -> where(['friendID'=>$param['userId'], 'userId'=>$param['friendID'], 'isBlackList'=>0, 'isCheck'=>0]) -> find()) {
                //查询朋友圈信息
                $arr2 = Db::table('jingo_user_circle')
                    -> field(['circleID', 'pic', 'content', 'createTime', 'voteID', 'commentNum'])
                    -> where('fromUserId', $param['friendID'])
                    -> order('createTime', 'DESC')
                    -> paginate(10);
                $arr2 = $arr2->toArray()['Rows'];
                if ($arr2) {
                    for ($i = 0,$count = count($arr2); $i < $count; $i++) {
                        if (strstr($arr2[$i]['voteID'], $param['userId'])) {
                            $arr2[$i]['isVote'] = 1;
                        }else {
                            $arr2[$i]['isVote'] = 0;
                        }
                        //调整点赞人显示内容
                        if ($arr2[$i]['voteID'] != null) {
                            /*if (isset($param['clientType'])) {
                                $voteID = explode(',', $arr2[$i]['voteID']);
                                $arr3 = Db::table('jingo_users') -> field(['userPhoto']) -> where('userId', 'in', $voteID) -> select();
                                $arr2[$i]['voteID'] = [];
                                if ($arr3) {
                                    foreach ($arr3 as $item) {
                                        $arr2[$i]['voteID'][] = $item['userPhoto'];
                                    }
                                }
                            }else {*/
                                $arr2[$i]['voteID'] = count(explode(',', $arr2[$i]['voteID']));
                            /*}*/
                        }else {
                            /*if (isset($param['clientType'])) {
                                $arr2[$i]['voteID'] = [];
                            }else {*/
                                $arr2[$i]['voteID'] = 0;
                            /*}*/
                        }
                        //增加评论信息详情(ios使用)
                        /*if (isset($param['clientType'])) {
                            //查询朋友圈评论
                            $comment = Db::table('jingo_user_circlecomment a')->field(['a.commentID', 'a.commentText', 'a.commentTime', 'b.userPhoto', 'b.userName'])->join('jingo_users b', 'b.userId = a.commentID', 'LEFT')->where('a.circleID', $arr2[$i]['circleID'])->order('a.commentTime', 'DESC')->select();
                            $arr2[$i]['comment'] = [];
                            if ($comment) {
                                foreach ($comment as $val) {
                                    $arr2[$i]['comment'][] = $val;
                                }
                            }
                        }*/
                    }
                }
            }else {
                $arr2 = [];         //如果被朋友拉黑,则相册信息为空数组
            }
            if ($friendInfo) {
                return json(array('result' => 'success', 'data'=>$friendInfo, 'value' => $arr2));
            } else {
                return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
            }
        }else {
            return json(array('result' => 'error', 'msg' => '请传参'));

        }
    }

    //好友相册(ios版)
    public function iosFriendAlbum($userId, $friendID, $page) {
        $param = $this->request->param();
        if (!empty($param)) {
            //查询朋友信息
            $friendInfo = Db::table('jingo_user_friends a')
                -> field(['a.nickName', 'b.userPhoto', 'b.userSex', 'b.signature'])
                -> join('jingo_users b', 'b.userId = a.friendID', 'LEFT')
                -> where(['a.friendID'=>$param['friendID'], 'a.userId'=>$param['userId']])
                -> find();
            if (Db::table('jingo_user_friends') -> where(['friendID'=>$param['userId'], 'userId'=>$param['friendID'], 'isBlackList'=>0]) -> find()) {
                //查询朋友圈信息
                $arr2 = Db::table('jingo_user_circle')
                    -> field(['circleID', 'pic', 'content', 'createTime', 'voteID', 'commentNum'])
                    -> where('fromUserId', $param['friendID'])
                    -> order('createTime', 'DESC')
                    -> select();
                if ($arr2) {
                    for ($i = 0,$count=count($arr2); $i < $count; $i++) {
                        if ($arr2[$i]['voteID'] != null) {
                            $arr2[$i]['voteID'] = explode(',', $arr2[$i]['voteID']);
                            $pictures = Db::table('jingo_users') -> field(['userPhoto']) -> where('userId', 'in', $arr2[$i]['voteID']) -> select();
                            $arr2[$i]['voteID'] = [];
                            foreach ($pictures as $v) {
                                $arr2[$i]['voteID'][] = $v['userPhoto'];
                            }
                        } else {
                            $arr2[$i]['voteID'] = [];
                        }
                        $comment = Db::table('jingo_user_circlecomment a')->field(['a.commentID', 'a.commentText', 'a.commentTime', 'c.nickName', 'b.userPhoto'])->join('jingo_users b', 'b.userId = a.commentID', 'LEFT')->join('jingo_user_friends c', 'c.friendID = a.commentID', 'LEFT')->where('a.circleID', $arr2[$i]['circleID'])->where('c.userId', $param['userId'])->order('a.commentTime', 'ASC')->select();
                        if ($comment != null) {
                            $arr2[$i]['comment'] = [];
                            foreach ($comment as $v) {
                                $arr2[$i]['comment'][] = $v;
                            }
                        }else {
                            $arr2[$i]['comment'] = [];
                        }
                    }
                }
            }else {
                $arr2 = [];         //如果被朋友拉黑,则相册信息为空数组
            }
            if ($friendInfo) {
                $arr2 = page($arr2, $param['page'], 10);
                return json(array('result' => 'success', 'data'=>$friendInfo, 'value' => $arr2));
            } else {
                return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
            }
        }else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }


    //删除朋友圈
    public function delete($userId, $circleID) {
        $param = $this->request->param();
        if (!empty($param)) {
            if (Api::checkApi($param['userId'], $param['token']) == 'false') exit();
            $circleArr = explode(',', $param['circleID']);
            Db::startTrans();
            if (Db::table('jingo_user_circle') -> where('fromUserId', $param['userId']) -> where('circleID', 'in', $circleArr) -> delete()) {
                Db::table('jingo_user_circlecomment') -> where('circleID', 'in', $circleArr) -> delete();
                Db::commit();
                return json(array('result' => 'success', 'msg' => '已删除朋友圈'));
            }else {
                Db::rollback();
                return json(array('result' => 'error', 'msg' => '该条朋友圈不是您的,您不能删除'));
            }
        }else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //查看陌生人的相册
    public function strangerAlbum($targetID) {
        $param = $this->request->param();
        if (!empty($param)) {
            $fields = [
                'circleID',         //朋友圈ID
                'pic',              //朋友圈图片
                'content',          //朋友圈内容
                'createTime',       //发送时间
                'voteID',           //点赞数
                'commentNum',       //评论数
            ];
            //查询自己的信息
            $arr = Db::table('jingo_users')->field(['userName', 'userPhoto', 'signature', 'userSex'])->where('userId', $param['targetID'])->find();
            //查询朋友圈信息
            $arr2 = Db::table('jingo_user_circle')->field($fields)->where('fromUserId', $param['targetID'])->limit(0, 10)->order('createTime', 'DESC')->select();
            if ($arr2) {
                for ($i = 0, $count = count($arr2); $i < $count; $i++) {
                    //判断是否点赞,点赞为1
                    if (strstr($arr2[$i]['voteID'], $param['userId'])) {
                        $arr2[$i]['isVote'] = 1;
                    }else {
                        $arr2[$i]['isVote'] = 0;
                    }

                    if ($arr2[$i]['voteID'] != null) {
                        $arr2[$i]['voteID'] = count(explode(',', $arr2[$i]['voteID']));
                    } else {
                        $arr2[$i]['voteID'] = 0;
                    }
                }
            }
            if ($arr) {
                return json(array('result' => 'success', 'data'=>$arr, 'value' => $arr2));
            } else {
                return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //朋友圈信息查询(优化版)
    public function test2() {
        $param = $this->request->param();
        if (!empty($param)) {
            //查询不看的朋友圈
            $arr1 = Db::table('jingo_user_friends') -> field(['friendID']) -> where(['userId'=>$param['userId']]) -> where(function ($query) {$query -> where('isBlackList', 1) -> whereOr('circleEnable', 1);}) -> select();
            //查询不让看的朋友圈
            $arr2 = Db::table('jingo_user_friends') -> field(['userId']) -> where(['friendID'=>$param['userId']]) -> where(function ($query) {$query -> where('isBlackList', 1) -> whereOr('isCheck', 1);}) -> select();
            //查询个人信息
            $self = Db::table('jingo_users') -> field(['userName', 'userPhoto']) -> where('userId', $param['userId']) -> find();
            //查询朋友列表
            $arr3 = Db::table('jingo_user_friends') -> field('friendID') -> where('userId', $param['userId']) -> select();
            $friends = [];      //朋友ID集合
            $unable = [];      //禁止看的朋友圈集合
            foreach ($arr1 as $v) {
                $unable[] = $v['friendID'];
            }
            foreach ($arr2 as $v) {
                $unable[] = $v['userId'];
            }
            foreach ($arr3 as $v) {
                $friends[] = $v['friendID'];
            }
            $friends[] = $param['userId'];
            session('userId',$param['userId']);
            session('unable',$unable);
            session('friends',$friends);
            array_unique($unable);      //去除重复字段
            //获取自己发的和发给自己的朋友圈
            $data = Db::table('jingo_user_circle')
                -> field(['circleID', 'pic', 'content', 'createTime', 'fromUserId', 'voteID', 'commentNum'])
                -> where('fromUserId', $param['userId'])
                -> whereOr(function ($query) {
                    $query -> where('toUserId', 'like', '%,'.session("userId").',%') -> where('fromUserId', 'not in', session('unable')) -> where('fromUserId', 'in', session('friends'));
                })
                -> order('createTime', 'DESC')
                -> paginate(10);
            $info = $data->toArray()['Rows'];
            session('userId', null);
            session('unable', null);
            session('friends', null);
            /*echo "<pre />";
            print_r($info);
            exit();*/
            foreach ($info as $k => $v) {
                if ($v['fromUserId'] == $param['userId']) {
                    $info[$k]['userPhoto'] = $self['userPhoto'];
                    $info[$k]['nickName'] = $self['userName'];
                    $info[$k]['isMe'] = '1';        //是否是自己 (0否1是)
                }else {
                    $data2 = Db::table('jingo_users a') -> field(['a.userPhoto', 'b.nickName']) -> join('jingo_user_friends b', 'a.userId = b.friendID', 'LEFT') -> where(['b.userId'=>$param['userId'], 'b.friendID'=>$v['fromUserId']]) -> find();
                    $info[$k]['userPhoto'] = $data2['userPhoto'];
                    $info[$k]['nickName'] = $data2['nickName'];
                    $info[$k]['isMe'] = '0';
                }
                /*if (in_array($v['fromUserId'], $unable)) {
                    $info[$k]['isShow'] = '0';      //是否显示 (0否1是)
                }else {
                    $info[$k]['isShow'] = '1';
                }*/
                if (strstr($v['voteID'], $param['userId'])) {
                    $info[$k]['state'] = '1';       //自己是否点赞 (0否1是)
                }else {
                    $info[$k]['state'] = '0';
                }
                //调整点赞人显示内容
                if ($v['voteID'] != null) {
                    if (isset($param['clientType'])) {
                        $voteID = explode(',', $v['voteID']);
                        $arr3 = Db::table('jingo_users') -> field(['userPhoto']) -> where('userId', 'in', $voteID) -> select();
                        $info[$k]['voteID'] = [];
                        if ($arr3) {
                            foreach ($arr3 as $item) {
                                $info[$k]['voteID'][] = $item['userPhoto'];
                            }
                        }
                    }else {
                        $info[$k]['voteID'] = count(explode(',', $v['voteID']));
                    }
                }else {
                    if (isset($param['clientType'])) {
                        $info[$k]['voteID'] = [];
                    }else {
                        $info[$k]['voteID'] = 0;
                    }
                }
                //增加评论信息详情(ios使用)
                if (isset($param['clientType'])) {
                    //查询朋友圈评论
                    $comment = Db::table('jingo_user_circlecomment a')->field(['a.commentID', 'a.commentText', 'a.commentTime', 'b.userPhoto', 'b.userName'])->join('jingo_users b', 'b.userId = a.commentID', 'LEFT')->where('a.circleID', $info[$k]['circleID'])->order('a.commentTime', 'DESC')->select();
                    $info[$k]['comment'] = [];
                    if ($comment) {
                        foreach ($comment as $val) {
                            $info[$k]['comment'][] = $val;
                        }
                    }
                }
            }
            return json(array('result' => 'success', 'value' => $info));
        }else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //朋友圈信息查询(二次优化版)
    public function show() {
        $param = $this->request->param();
        if (!empty($param)) {
            if (Api::checkApi($param['userId'], $param['token']) == 'false') exit();
            session('userId',$param['userId']);
            //查询不看的朋友圈
            $arr1 = Db::table('jingo_user_friends') -> field(['friendID']) -> where(['userId'=>$param['userId']]) -> where(function ($query) {$query -> where('isBlackList', 1) -> whereOr('circleEnable', 1) -> whereOr('isAgreed',0);}) -> select();
            //查询不让看的朋友圈
            $arr2 = Db::table('jingo_user_friends') -> field(['userId']) -> where(['friendID'=>$param['userId']]) -> where(function ($query) {$query -> where('isBlackList', 1) -> whereOr('isCheck', 1);}) -> select();
            //查询个人信息
            $self = Db::table('jingo_users') -> field(['userName', 'userPhoto']) -> where('userId', $param['userId']) -> find();
            //查询好友列表
            $arr3 = Db::table('jingo_user_friends') -> field('friendID') -> where(['userId'=>$param['userId'], 'isAgreed'=>1]) -> select();
            $friends = [];      //好友集合
            foreach ($arr3 as $v) {
                $friends[] = $v['friendID'];
            }
            $friends[] = $param['userId'];
            //查询朋友列表
            $unable = [];      //禁止看的朋友圈集合
            foreach ($arr1 as $v) {
                $unable[] = $v['friendID'];
            }
            foreach ($arr2 as $v) {
                $unable[] = $v['userId'];
            }
            $unable[] = $param['userId'];
            session('unable',$unable);
            array_unique($unable);      //去除重复字段
            //获取自己发的和发给自己的朋友圈
            $data = Db::table('jingo_user_circle')
                -> field(['circleID', 'pic', 'content', 'createTime', 'fromUserId', 'voteID', 'commentNum'])
                -> where('fromUserId', $param['userId'])
                -> whereOr(function ($query) {
                    $query -> where('toUserId', 'like', '%,'.session("userId").',%') -> where('fromUserId', 'not in', session('unable'));
                })
                -> order('createTime', 'DESC')
                -> paginate(10);
            $info = $data->toArray()['Rows'];
            session('userId', null);
            session('unable', null);
            $info2 = [];
            foreach ($info as $k => $v) {
                if (!in_array($v['fromUserId'], $friends)) {            //清除不是好友的数据
                    $info[$k] = null;
                    continue;
                }
                if ($v['fromUserId'] == $param['userId']) {
                    $info[$k]['userPhoto'] = $self['userPhoto'];
                    $info[$k]['nickName'] = $self['userName'];
                    $info[$k]['isMe'] = '1';        //是否是自己 (0否1是)
                }else {
                    $data2 = Db::table('jingo_users a') -> field(['a.userPhoto', 'b.nickName']) -> join('jingo_user_friends b', 'a.userId = b.friendID', 'LEFT') -> where(['b.userId'=>$param['userId'], 'b.friendID'=>$v['fromUserId']]) -> find();
                    $info[$k]['userPhoto'] = $data2['userPhoto'];
                    $info[$k]['nickName'] = $data2['nickName'];
                    $info[$k]['isMe'] = '0';
                }
                /*if (in_array($v['fromUserId'], $unable)) {
                    $info[$k]['isShow'] = '0';      //是否显示 (0否1是)
                }else {
                    $info[$k]['isShow'] = '1';
                }*/
                if (strstr($v['voteID'], $param['userId'])) {
                    $info[$k]['state'] = '1';       //自己是否点赞 (0否1是)
                }else {
                    $info[$k]['state'] = '0';
                }
                //调整点赞人显示内容
                if ($v['voteID'] != null) {
                    /*if (isset($param['clientType'])) {
                        $voteID = explode(',', $v['voteID']);
                        $arr3 = Db::table('jingo_users') -> field(['userPhoto']) -> where('userId', 'in', $voteID) -> select();
                        $info[$k]['voteID'] = [];
                        if ($arr3) {
                            foreach ($arr3 as $item) {
                                $info[$k]['voteID'][] = $item['userPhoto'];
                            }
                        }
                    }else {
                        $info[$k]['voteID'] = count(explode(',', $v['voteID']));
                    }*/
                    $info[$k]['voteID'] = count(explode(',', $v['voteID']));
                }else {
                    $info[$k]['voteID'] = 0;
                    /*if (isset($param['clientType'])) {
                        $info[$k]['voteID'] = [];
                    }else {
                        $info[$k]['voteID'] = 0;
                    }*/
                }
                $info2[] = $info[$k];
                //增加评论信息详情(ios使用)
                /*if (isset($param['clientType'])) {
                    //查询朋友圈评论
                    $comment = Db::table('jingo_user_circlecomment a')->field(['a.commentID', 'a.commentText', 'a.commentTime', 'b.userPhoto', 'b.userName'])->join('jingo_users b', 'b.userId = a.commentID', 'LEFT')->where('a.circleID', $info[$k]['circleID'])->order('a.commentTime', 'DESC')->select();
                    $info[$k]['comment'] = [];
                    if ($comment) {
                        foreach ($comment as $val) {
                            $info[$k]['comment'][] = $val;
                        }
                    }
                }*/
            }
            return json(array('result' => 'success', 'value' => $info2));
        }else {
            return json(array('0'));
        }
    }

}