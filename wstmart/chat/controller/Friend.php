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
use wstmart\chat\model\Vote;

class Friend extends Controller
{
    public $request;
    private $RongCloud;


    public function __construct()
    {
        $this->request = request();
        $this->RongCloud = new Rongyun();
    }

    //获取朋友列表
    public function friends($userId)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            //联接朋友表和用户总表查询好友信息
            $user_friend = db('user_friends a');
            $arr1 = $user_friend->join('users b', 'a.friendID = b.userId', 'LEFT')->field(['a.friendID', 'a.nickName', 'b.userName', 'b.userPhoto', 'b.loginName', 'b.userPhone', 'a.isDisturb', 'a.isTop', 'a.circleEnable', 'a.isCheck', 'a.isBlackList'])->where('a.userId', $param['userId'])->where(['a.isAgreed' => 1, 'a.isDelete' => 0])->select();
            for ($i = 0,$count = count($arr1); $i < $count; $i++) {
                $arr1[$i]['state'] = 0;     //0表示朋友
            }
            //联接群表和群用户表查询群信息
            $user_group = db('user_groups a');
            $arr2 = $user_group->field(['a.groupID', 'a.name', 'a.icon', 'b.groupName', 'b.isSave', 'b.isDisturb', 'b.isTop'])->join('user_groupmembers b', 'a.groupID = b.groupID', 'LEFT')->where('b.userId', $param['userId'])->select();
            for ($i = 0,$count = count($arr2); $i < $count; $i++) {
                $arr2[$i]['state'] = 1;     //1表示群
            }
            //系统机器人
            $arr3[] = ['friendID' => 1, 'userName' => '小V', 'userPhoto' => 'upload/userPhotos/userVV.png', 'state' => 2];       //2表示系统机器人
            //客服人员
            $id = mb_substr($param['userId'], 0, -2).'00';
            $info = Db::table('jingo_users') -> field(['userName', 'userPhoto', 'userSex', 'userPhone']) -> where('userId', $id) -> find();
            if ($info) {
                $info['friendID'] = $id;
                $info['state'] = 3;         //3表示客服
                $arr3[] = $info;
            }
            //合并朋友表和群表
            $arr = array_merge($arr1, $arr2, $arr3);
            return json(array('result' => 'success', 'value' => $arr));
        } else {
            return json(array('result' => 'error', 'value' => '请传参'));
        }
    }

    //新的朋友
    public function askList($userId)
    {
        $param = $this->request->param();
        $info = Db::table('jingo_users') -> field(['isRec', 'userPhone']) -> where('userId', $param['userId']) -> find();
        $time = $param['time'];         //上次请求的时间
        //$time = time();
        if (!empty($param)) {
            //联表查询自己的好友请求列表
            $data = Db::table('jingo_user_friends a')
                ->join('users b', 'a.friendID = b.userId', 'LEFT')
                ->field(['a.friendID', 'a.nickName', 'a.isAgreed', 'a.time', 'b.userPhoto', 'b.userPhone', 'b.userName'])
                ->where('a.userId', intval($param['userId']))
                ->order('time', 'DESC')
                ->select();
            $isNew = [];
            if ($data) {
                $friends = [];      //朋友的手机号
                foreach ($data as $k => $v) {
                    if (strtotime($v['time']) > $time) {        //判断自己添加的朋友是否有新纪录
                        $isNew[] = 1;
                    } else {
                        $isNew[] = 0;
                    }
                    if ($v['isAgreed'] == 1) {
                        $data[$k]['state'] = 1;
                    } else {
                        $data[$k]['state'] = 0;
                    }
                    $friends[] = $v['userPhone'];
                }
            }else {
                $friends = [];
            }

            //推荐通讯录开启的情况
            if ($info['isRec']) {
                $json = str_replace('&quot;', '"', $param['list']);

                //$json = '[{"phone":"+8613588181629","name":"房东"},{"phone":"+8613861224941","name":"娜娜"},{"phone":"+8613866701633","name":"吴萍"},{"phone":"+8615556907479","name":"余淑芳"},{"phone":"057185200000","name":"易信专线"},{"phone":"057185202000","name":"易信专线"},{"phone":"057185203000","name":"易信专线"},{"phone":"057185204000","name":"易信专线"},{"phone":"057185205000","name":"易信专线"},{"phone":"057185206000","name":"易信专线"},{"phone":"057185207000","name":"易信专线"},{"phone":"057185208000","name":"易信专线"},{"phone":"057185209000","name":"易信专线"},{"phone":"13003643666","name":"易信专线"},{"phone":"13024279666","name":"易信专线"},{"phone":"13024295666","name":"易信专线"},{"phone":"13053044930","name":"夏方"},{"phone":"13063445270","name":"刘銮"},{"phone":"13067748666","name":"易信专线"},{"phone":"13067784666","name":"易信专线"},{"phone":"13135512622","name":"陈周"},{"phone":"13205645796","name":"刘杨"},{"phone":"13216204582","name":"国强小姐姐"},{"phone":"13340881990099","name":"考生号"},{"phone":"13485860693","name":"吴萍"},{"phone":"13559864555","name":"郭老师"},{"phone":"13566062020","name":"13566062020"},{"phone":"13757152817","name":"魏大"},{"phone":"13777369952","name":"魏"},{"phone":"13855657418","name":"慕老师"},{"phone":"13865672086","name":"顾秀云"},{"phone":"13866088753","name":"舅舅"},{"phone":"13866734646","name":"魏老师"},{"phone":"13956531994","name":"老爸"},{"phone":"13966950251","name":"程影"},{"phone":"14790537084","name":"吴蕾"},{"phone":"14790695354","name":"姨婆"},{"phone":"15024469315","name":"李玲"},{"phone":"15056050305","name":"罗丹"},{"phone":"15056637200","name":"倪赵凤"},{"phone":"15056974664","name":"方文丽"},{"phone":"15056977663","name":"汤小妹"},{"phone":"15088165192","name":"李涛老婆"},{"phone":"15155141549","name":"黄存玖"},{"phone":"15155641591","name":"张春阳"},{"phone":"15155684362","name":"刘韵"},{"phone":"15156038610","name":"强化凯"},{"phone":"15178639017","name":"杨寒"},{"phone":"15212250029","name":"雅萍(芜湖)"},{"phone":"15399611910","name":"班主任"},{"phone":"15605637208","name":"王凌云"},{"phone":"15755162221","name":"魏国强"},{"phone":"15855553513","name":"杨华芳"},{"phone":"17185714157","name":"打不死的小强"},{"phone":"17367084036","name":"林浩"},{"phone":"17505714941","name":"崔金磊"},{"phone":"18006294129","name":"灵芝姐"},{"phone":"18119614339","name":"潘刘芳"},{"phone":"18133685485","name":"胡婷（合肥）"},{"phone":"18223471019","name":"陶勇"},{"phone":"18225706183","name":"朱黎"},{"phone":"18255647698","name":"陈登"},{"phone":"18256018803","name":"赵宝婷"},{"phone":"18256707973","name":"国强二姐"},{"phone":"18297713265","name":"大胡"},{"phone":"18355128273","name":"胡传飞"},{"phone":"18355185720","name":"宋子夜"},{"phone":"18355440483","name":"大凤(淮南)"},{"phone":"18356085011","name":"马永萍"},{"phone":"18375327137","name":"倪燕(芜湖)"},{"phone":"18662583412","name":"杨"},{"phone":"18714896870","name":"都宝珠"},{"phone":"18715109521","name":"陈琴（合肥）"},{"phone":"18715109601","name":"欧雪萌"},{"phone":"18715401097","name":"雅萍"},{"phone":"18715401331","name":"胡春"},{"phone":"18726192269","name":"大姨"},{"phone":"18726192433","name":"老妈"},{"phone":"18755170926","name":"宋娜娜"},{"phone":"18755194276","name":"胡耽"},{"phone":"18756075700","name":"大胡(合肥)"},{"phone":"18756911819","name":"孙小琴"},{"phone":"18756933586","name":"珠(合肥)"},{"phone":"18757141666","name":"易信专线"},{"phone":"18757142666","name":"易信专线"},{"phone":"18757143666","name":"易信专线"},{"phone":"18757144666","name":"易信专线"},{"phone":"18856036081","name":"杨守乐"},{"phone":"18860459029","name":"吴蕾(六安)"},{"phone":"18949836260","name":"189 4983 6260"},{"phone":"4009668668","name":"易信专线"},{"phone":"6089955","name":"家"}]';
                //$json = '[{"name":"陶勇","phone":"18223471019"},{"name":"陶勇","phone":"13750684163"},{"name":"崔金磊","phone":"15757113254"}]';
            }else {
                $json = '[]';
            }
            $isPhone = [];         //通讯录中已加过好友的数组
            if (isset($json)) {
                $json = json_decode($json);
                $phone = [];        //通讯录的手机号
                foreach ($json as $v) {
                    if (str_replace(' ', '', $v -> phone) == $info['userPhone']) {
                        continue;
                    }
                    $phone[] = str_replace(' ', '', $v -> phone);
                }

                if (isset($friends) && is_array($friends)) {
                    foreach ($phone as $k => $v) {
                        $isPhone[] = $v;
                        if (in_array($v, $friends)) {
                            unset($phone[$k]);      //去除已是好友的通讯录手机
                        }
                    }
                }


                if ($phone) {       //判断手机号集合不为空时执行查询
                    //查询通讯录信息
                    $arr = Db::table('jingo_users')->field(['userId', 'userName', 'userPhone', 'userPhoto', 'createTime'])->where('userPhone', 'in', $phone)->select();
                    if ($arr) {
                        foreach ($arr as $k => $v) {
                            if (strtotime($v['createTime']) > $time) {      //判断是否有新注册的通讯录好友
                                $isNew[] = 1;
                            } else {
                                $isNew[] = 0;
                            }
                            $arr[$k]['state'] = 2;
                        }
                    }else {
                        $arr = [];
                    }
                }else {
                    $arr = [];
                }
                $data = array_merge($arr, $data);
            }


            //请求自己为好友的情况
            $arr2 = Db::table('jingo_user_friends a')
                ->join('users b', 'a.userId = b.userId', 'LEFT')
                ->field(['a.userId', 'a.isAgreed', 'a.time', 'b.userPhoto', 'b.userPhone', 'b.userName'])
                ->where('a.friendID', $param['userId'])
                ->where('a.isAgreed', 0)
                ->order('time', 'DESC')
                ->select();
            if ($arr2) {
                foreach ($arr2 as $k => $v) {
                    if (strtotime($v['time']) > $time) {        //判断添加自己为好友的是否有新纪录
                        $isNew[] = 1;
                    } else {
                        $isNew[] = 0;
                    }

                    $arr2[$k]['state'] = 3;
                    foreach ($data as $key => $val) {
                        if ($val['state'] == 2 && $val['userId'] == $v['userId']) {
                            unset($data[$key]);
                        }
                    }
                }

                $data = array_merge($data, $arr2);

            }

            if ($data && $isPhone) {
                foreach ($data as $k => $v) {
                    if (in_array($v['userPhone'], $isPhone)) {
                        foreach ($json as $value) {
                            if ($v['userPhone'] == str_replace(' ', '', $value -> phone)) {
                                $data[$k]['trueName'] = $value -> name;
                            }
                        }
                        $data[$k]['isPhoneBook'] = 1;
                    }else {
                        $data[$k]['isPhoneBook'] = 0;
                    }
                }
            }

            //判断是否有新朋友
            if (in_array(1, $isNew)) {
                $new = 1;
            } else {
                $new = 0;
            }
            //将结果进行排序
            $sort = [];
            $unique = [];
            foreach ($data as $key => $item) {
                foreach ($item as $k1 => $v1) {
                    if ($k1 == 'time' || $k1 == 'createTime') {
                        $sort[$key] = strtotime($v1);
                    }
                }
                //去除数组中重复的数组(复杂算法,待优化)
                if ($key > 1) {
                    if (@$item['friendID'] != @$unique[($key - 1)]['friendID'] || @$item['userId'] != @$unique[($key - 1)]['userId']) {
                        if (!isset($item['isPhoneBook'])) {
                            $item['isPhoneBook'] = 0;
                        }
                        $unique[$key] = $item;
                    }else {
                        unset($sort[$key]);
                    }
                }else {
                    if (!isset($item['isPhoneBook'])) {
                        $item['isPhoneBook'] = 0;
                    }
                    $unique[$key] = $item;
                }
            }

            array_multisort($sort, SORT_DESC, $unique);
            return json(array('result' => 'success', 'isNew' => $new, 'value' => $unique));        //返回好友请求列表
        } else {
            return json(array('result' => 'error', 'value' => '请传参'));
        }
    }

    //好友同意请求
    public function agree($userId, $friendID, $nickName)
    {
        $param = $this->request->param();
        /*$param['content'] = str_replace('&quot;', '"', $param['content']);*/
        if (!empty($param)) {
            if (Db::table('jingo_user_friends') -> field('userId') -> where(['userId'=>$param['friendID'], 'friendID'=>$param['userId'], 'isAgreed'=>0]) -> find()) {
                Db::startTrans();
                DB::table('jingo_user_friends')->where('friendID', $param['userId'])->where('userId', $param['friendID'])->update(['isAgreed' => 1]);        //更新好友请求值为1(0未同意1同意)
                $arr = Db::table('jingo_users')->field(['userName', 'userPhoto'])->where('userId', $param['friendID'])->find();
                $arr['nickName'] = $param['nickName'];
                $arr['friendID'] = $param['friendID'];
                if (empty($param['nickName'])) {            //如果没有设置备注名,则将对方的昵称设置为备注名
                    $param['nickName'] = $arr['userName'];
                }
                $data = [
                    'userId' => $param['userId'],
                    'friendID' => $param['friendID'],
                    'nickName' => $param['nickName'],
                    'isAgreed' => 1,
                ];
                //$info = $this->RongCloud->publishPrivates($param['userId'], $param['friendID'], 'RC:InfoNtf', '{"message":"你们已经是好友了  可以聊天了"}', '', '', 1, 1, 1, 1, 1);
                if (Db::table('jingo_user_friends')->insert($data)) {         //好友表添加对方好友的相关信息
                    Db::commit();
                    return json(array('result' => 'success', 'value' => $arr));
                } else {
                    Db::rollback();
                    return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
                }
            }else {
                return json(array('result' => 'success1', 'msg' => '您已添加对方为好友'));
            }

        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }


    //获取朋友信息
    public function friendInfo($userId, $friendID)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            $fields = array('a.userId', 'a.userName', 'a.signature', 'a.userPhoto', 'a.userSex', 'a.userNation', 'a.userProvince', 'a.userCity', 'b.nickName', 'b.isDisturb', 'b.isTop', 'b.circleEnable', 'c.picturePath');
            $data = Db::table('jingo_users a')->join('user_friends b', 'b.friendID = a.userId', 'LEFT')->join('user_album c', 'c.userId = a.userId', 'LEFT')->field($fields)->where('b.userId', $param['userId'])->where('b.friendID', $param['friendID'])->select();
            $values[] = array();        //初始化朋友信息组合
            $picArr = array();          //朋友相册组合
            for ($i = 0; $i < count($data); $i++) {
                if ($i == 0) {            //将第一个数组的朋友信息赋值,后续数组的朋友信息相同,不重复赋值
                    $values = $data[$i];
                    $picArr[] = $data[$i]['picturePath'];
                } else {
                    $picArr[] = $data[$i]['picturePath'];
                }
            }
            $values['picturePath'] = $picArr;       //将相册加入朋友信息组合
            if ($values) {
                return json(array('result' => 'success', 'value' => $values));
            } else {
                return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //添加朋友
    public function add($userId, $friendID, $nickName, $content, $objectName)
    {
        $param = $this->request->param();
        $param['content'] = str_replace('&quot;', '"', $param['content']);
        $param['content'] = str_replace('\\', '', $param['content']);
        $arr = Db::table('jingo_users')->field(['userName', 'isValidate'])->where('userId', $param['friendID'])->find();
        $selfName = Db::table('jingo_users')->field('userName')->where('userId', $param['userId'])->find();
        if (!empty($param)) {      //判断是否post发送userId
            /*if (empty($param['nickName'])) {        //判断没有传备注名时将该用户昵称作为备注名
                $param['nickName'] = $arr['userName'];
            }*/
            $user_friend = db('user_friends');
            $data = array(
                'friendID' => $param['friendID'],
                'userId' => $param['userId'],
                'nickName' => $param['nickName'],
            );
            $friendArr = $user_friend->field(['id', 'isBlackList', 'isDelete', 'isAgreed'])->where('friendID', $param['friendID'])->where('userId', $param['userId'])->find();
            //判断对方已经添加你为好友的情况
            if ($user_friend -> where(['userId'=>$param['friendID'], 'friendID'=>$param['userId'], 'isAgreed'=>0]) -> find()) {
                return $this -> agree($param['userId'], $param['friendID'], $param['nickName']);
            }
            if ($friendArr) {
                if ($friendArr['isAgreed'] == 0) {      //判断如果还不是好友
                    if ($arr['isValidate'] == 1) {
                        return json(array('result' => 'success', 'msg' => '您已申请过了,请耐心等待她同意'));
                    }else {         //如果对方不需要验证好友请求,则直接加为好友
                        $user_friend -> startTrans();
                        $user_friend -> where('id', $friendArr['id']) -> update(['isAgreed'=>1]);
                        $user_friend -> insert(['userId'=>$param['friendID'], 'friendID'=>$param['userId'], 'nickName'=>$selfName['userName'], 'isAgreed'=>1]);
                        $info = $this->RongCloud->PublishSystems(1, $param['friendID'], $param['objectName'], $param['content']);
                        if ((json_decode($info)->code) == 200) {
                            $user_friend->commit();
                            return json(array('result' => 'success', 'msg' => '好友请求已发送'));
                        } else {
                            $user_friend->rollback();
                            return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
                        }
                    }
                } else {
                    if ($friendArr['isBlackList'] == 0 && $friendArr['isDelete'] == 0) {        //既不在黑名单又没删除的情况
                        return json(array('result' => 'success', 'msg' => '她已在您的好友列表中'));
                    } elseif ($friendArr['isBlackList'] == 1 && $friendArr['isDelete'] == 0) {   //在黑名单但没有删除的情况
                        $user_friend->startTrans();
                        $user_friend->where('id', $friendArr['id'])->update(['isBlackList' => 0]);
                        $info = $this->RongCloud->removeBlacklists($param['userId'], $param['friendID']);
                        if (json_decode($info)->code == 200) {        //判断第三方接入是否成功
                            $user_friend->commit();
                            return json(array('result' => 'success', 'msg' => '已从黑名单中移除'));
                        } else {
                            $user_friend->rollback();
                            return json(array('result' => 'error', 'msg' => '未能从黑名单中移除,请稍候重试'));
                        }
                    } elseif ($friendArr['isBlackList'] == 1 && $friendArr['isDelete'] == 1) {   //删除好友的情况
                        $user_friend->startTrans();
                        $user_friend->where('id', $friendArr['id'])->update(['isBlackList' => 0, 'isDelete' => 0]);
                        $info = $this->RongCloud->removeBlacklists($param['userId'], $param['friendID']);
                        if (json_decode($info)->code == 200) {        //判断第三方接入是否成功
                            $user_friend->commit();
                            return json(array('result' => 'success', 'value' => 1, 'msg' => '已悄悄将好友加回'));
                        } else {
                            $user_friend->rollback();
                            return json(array('result' => 'error', 'msg' => '未能加回好友,请稍候重试'));
                        }
                    } else {
                        return json(array('result' => 'error', 'msg' => '未知情况'));
                    }
                }
            } else {       //如果查不到该好友则添加好友
                if ($arr['isValidate'] == 1) {
                    $info = $this->RongCloud->PublishSystems(1, $param['friendID'], $param['objectName'], $param['content'], '系统通知', '', 1, 1);
                    $user_friend -> startTrans();
                    if ((json_decode($info)->code) == 200 && $user_friend->insert($data)) {
                        $user_friend -> commit();
                        return json(array('result' => 'success', 'msg' => '好友请求已发送'));
                    } else {
                        $user_friend -> rollback();
                        return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
                    }
                }else {     //如果对方不需要验证好友请求,则直接加为好友
                    $user_friend -> startTrans();
                    $data['isAgreed'] = 1;
                    $user_friend->insert($data);
                    $user_friend -> insert(['userId'=>$param['friendID'], 'friendID'=>$param['userId'], 'nickName'=>$selfName['userName'], 'isAgreed'=>1]);
                    $info = $this->RongCloud->PublishSystems(1, $param['friendID'], $param['objectName'], $param['content']);
                    if ((json_decode($info)->code) == 200) {
                        $user_friend->commit();
                        return json(array('result' => 'success', 'value' => 1, 'msg' => '好友请求已发送'));
                    } else {
                        $user_friend->rollback();
                        return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
                    }
                }
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }


    //拉黑好友
    public function deFriend($userId, $friendID)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            $user_friend = db('user_friends');
            $info = $this->RongCloud->addBlacklists($param['userId'], $param['friendID']);
            $user_friend -> startTrans();
            $user_friend->where('userId', $param['userId'])->where('friendID', $param['friendID'])->update(['isBlackList' => 1]);
            if (json_decode($info)->code == 200) {
                $user_friend -> commit();
                return json(array('result' => 'success', 'msg' => '您已将好友关进小黑屋'));
            } else {
                $user_friend -> rollback();
                return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //删除好友
    public function delete($userId, $friendID)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            $user_friend = db('user_friends');

            //判断在对方将你删除的情况下
            if ($user_friend -> field('userId') -> where(['userId'=>$param['friendID'], 'friendID'=>$param['userId'], 'isDelete'=>1]) -> find()) {
                $user_friend -> startTrans();
                $user_friend ->where(['userId'=>$param['userId'], 'friendID'=>$param['friendID']])-> delete();
                $user_friend ->where(['userId'=>$param['friendID'], 'friendID'=>$param['userId']])-> delete();
                $info = $this->RongCloud->removeBlacklists($param['userId'], $param['friendID']);
                $info1 = $this->RongCloud->removeBlacklists($param['friendID'], $param['userId']);
                if (json_decode($info)->code == 200 && json_decode($info1)->code == 200) {
                    $user_friend->commit();
                    return json(array('result' => 'success', 'msg' => '好友三两个足矣'));
                } else {
                    $user_friend->rollback();
                    return json(array('result' => 'error', 'msg' => '好险,差点就注孤生了'));
                }
            }else {
                //将该好友软删除
                $user_friend -> startTrans();
                if ($user_friend->where('userId', $param['userId'])->where('friendID', $param['friendID'])->update(['isDelete' => 1, 'isBlackList' => 1])) {
                    $info = $this->RongCloud->addBlacklists($param['userId'], $param['friendID']);
                    if (json_decode($info)->code == 200) {
                        $user_friend->commit();
                        return json(array('result' => 'success', 'msg' => '好友三两个足矣'));
                    } else {
                        $user_friend->rollback();
                        return json(array('result' => 'error', 'msg' => '好险,差点就注孤生了'));
                    }
                } else {
                    return json(array('result' => 'error', 'msg' => '好险,差点就注孤生了'));
                }
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传递自己和朋友的ID'));
        }
    }

    //保存最近联系人列表
    public function addLastList()
    {         //该接口暂时不做测试(需和前端沟通)
        $param = $this->request->param();
        if (!empty($param)) {
            Db::startTrans();
            Db::table('jingo_user_contacts')->where('userId', $param['userId'])->delete();      //删除之前联系人
            if (Db::table('jingo_user_contacts')->insertAll($param)) {            //新增联系人列表
                Db::commit();
                return json(array('result' => 'success', 'msg' => '保存最近联系人成功'));
            } else {
                Db::rollback();
                return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //最近联系人列表
    public function lastList($userId)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            $data = Db::table('jingo_user_contacts')->field(['contactID', 'name', 'photo', 'type'])->where('userId', $param['userId'])->order('id', 'ASC')->select();      //查询联系人列表
            if ($data) {
                return json(array('result' => 'success', 'value' => $data));
            } else {
                return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //朋友设置(用于设置昵称和置顶等)
    public function setting($userId, $friendID)
    {
        $param = $this->request->param();
        $user_friend = new Friends();           //实例化用户朋友表
        if (!empty($param)) {
            Db::startTrans();
            if ($user_friend->allowField(true)->isUpdate(true)->save($param, ['userId' => $param['userId'], 'friendID' => $param['friendID']])) {         //更新朋友设置
                if (isset($param['isBlackList'])) {
                    if ($param['isBlackList'] == 1) {
                        $info = $this->RongCloud->addBlacklists($param['userId'], $param['friendID']);
                    }elseif ($param['isBlackList'] == 0) {
                        $info = $this->RongCloud->removeBlacklists($param['userId'], $param['friendID']);
                    }
                    if (json_decode($info)->code == 200) {
                        Db::commit();
                        return json(array('result' => 'success', 'msg' => '设置已修改'));
                    } else {
                        Db::rollback();
                        return json(array('result' => 'error', 'msg' => '设置修改失败'));
                    }
                }else {
                    Db::commit();
                    return json(array('result' => 'success', 'msg' => '设置已修改'));
                }
            } else {
                Db::rollback();
                return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //陌生人设置
    public function setStranger($userId, $age, $distance)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            if (Db::table('jingo_users')->where('userId', $param['userId'])->update(['setAge' => $param['age'], 'setDistance' => $param['distance']])) {
                return json(array('result' => 'success', 'msg' => '已更改陌生人设置'));
            } else {
                return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //陌生人列表
    public function stranger($userId)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            //查询本人的位置信息和陌生人设置
            $arr = Db::table('jingo_users')->field(['longitude', 'latitude', 'setAge', 'setDistance'])->where('userId', $param['userId'])->find();
            if ($arr) {
                $distance = $arr['setDistance'];
                $age = explode(',', $arr['setAge']);
                //查询朋友ID
                $arr2 = Db::table('jingo_user_friends')->field('friendID')->where('userId', $param['userId'])->where(function ($query) {
                    $query->where('isAgreed', 1)->whereor('isBlackList', 1);
                })->select();
                session('userId',$param['userId']);
                //查询点赞过的人ID
                $arr3 = Db::table('jingo_user_vote')->field('userId')->where(function ($query) {
                    $query->where('strangerID', session('userId'))->where('isCancel', 0);
                })->select();
                session('userId', null);
                $friends = [];      //朋友ID数组
                if ($arr2 || $arr3) {
                    foreach ($arr2 as $v) {
                        $friends[] = $v['friendID'];
                    }
                    foreach ($arr3 as $v) {
                        $friends[] = $v['userId'];
                    }
                    $friends[] = $param['userId'];      //将自己加入朋友数组,以避免查询到自己
                }
                $x = $distance * 1000 * 9 * 0.000001 / 2;        //经度
                $y = $distance * 1000 * 9 * 0.000001 / cos($arr['latitude']) / 2;      //纬度
                //$ageRange = [$age[0], $age[1]];                             //年龄范围
                $xRange = [$arr['longitude'] - $x, $arr['longitude'] + $x];     //经度范围
                $yRange = [$arr['latitude'] - $y, $arr['latitude'] + $y];       //纬度范围
                $fields = ['userId', 'userPhoto', 'userSex', 'userAge', 'userName', 'longitude', 'latitude'];
                if ($friends) {
                    $data = Db::table('jingo_users')
                        ->field($fields)
                        ->where('longitude', 'between', $xRange)
                        //->where("longitude between {$xRange[0]} and {$xRange[1]}")
                        //->where("latitude between {$yRange[0]} and {$yRange[1]}")
                        ->where('latitude', 'between', $yRange)
                        ->where("userAge between {$age[0]} and {$age[1]}")
                        ->where('userId', 'not in', $friends)
                        ->select();
                } else {
                    $data = Db::table('jingo_users')
                        ->field($fields)
                        ->where('longitude', 'between', $xRange)
                        ->where('latitude', 'between', $yRange)
                        ->where("userAge between {$age[0]} and {$age[1]}")
                        ->where('userId', 'neq', $param['userId'])
                        ->select();
                }
                return json(array('result' => 'success', 'value' => $data));
            } else {
                return json(array('result' => 'error', 'msg' => '请先开启定位并更新位置'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //陌生人列表(注册人数少时使用)
    public function strangerList($userId)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            //查询本人的位置信息和陌生人设置
            $arr = Db::table('jingo_users')->field(['longitude', 'latitude', 'setAge', 'setDistance'])->where('userId', $param['userId'])->find();
            if ($arr) {
                //查询朋友ID
                $arr2 = Db::table('jingo_user_friends')->field('friendID')->where('userId', $param['userId'])->where(function ($query) {
                    $query->where('isAgreed', 1)->whereor('isBlackList', 1);
                })->select();
                session('userId',$param['userId']);
                //查询点赞过的人ID
                $arr3 = Db::table('jingo_user_vote')->field('userId')->where(function ($query) {
                    $query->where('strangerID', session('userId'))->where('isCancel', 0);
                })->select();
                session('userId', null);
                $friends = [];      //朋友ID数组
                if ($arr2 || $arr3) {
                    foreach ($arr2 as $v) {
                        $friends[] = $v['friendID'];
                    }
                    foreach ($arr3 as $v) {
                        $friends[] = $v['userId'];
                    }
                    $friends[] = $param['userId'];      //将自己加入朋友数组,以避免查询到自己
                }
                $friends[] = '1';

                //去除客服人员(笨办法,需优化)
                $filter = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];
                foreach ($filter as $v) {
                    $friends[] = $v.'00';
                }
                $fields = ['userId', 'userPhoto', 'userSex', 'userAge', 'userName', 'longitude', 'latitude'];
                if ($friends) {
                    $data = Db::table('jingo_users')
                        ->field($fields)
                        ->where('longitude', 'neq', '')
                        ->where('userId', 'not in', $friends)
                        ->paginate(10);
                } else {
                    $data = Db::table('jingo_users')
                        ->field($fields)
                        ->where('longitude', 'neq', '')
                        ->where('userId', 'neq', $param['userId'])
                        ->paginate(10);
                }
                $data = $data->toArray()['Rows'];
                return json(array('result' => 'success', 'value' => $data));
            } else {
                return json(array('result' => 'error', 'msg' => '请先开启定位并更新位置'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //陌生人点赞
    public function vote($userId, $friendID, $nickName, $content, $objectName)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            $vote = new Vote();
            //判断对方有没有加我为好友
            if (Db::table('jingo_user_friends')->where(['userId' => $param['friendID'], 'friendID' => $param['userId']])->find()) {
                $vote->save(['userId' => $param['friendID'], 'strangerID' => $param['userId'], 'isAgreed' => 0]);
                //$vote->save(['userId' => $param['userId'], 'strangerID' => $param['friendID'], 'isAgreed' => 1]);
                return $this->agree($param['userId'], $param['friendID'], $param['nickName'], $param['content'], $param['objectName']);
            } else {
                if ($vote -> where(['userId' => $param['friendID'], 'strangerID' => $param['userId'], 'isCancel'=>0]) -> find()) {
                    return json(array('result' => 'error', 'msg' => '您已赞了对方'));
                }
                $vote->save(['userId' => $param['friendID'], 'strangerID' => $param['userId'], 'isCancel'=>0]);
                return $this->add($param['userId'], $param['friendID'], $param['nickName'], $param['content'], $param['objectName']);
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }


    
    //取消赞
    public function cancel($userId, $strangerID)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            if (Db::table('jingo_user_vote')->where(['userId' => $param['strangerID'], 'strangerID' => $param['userId']])->update(['isCancel'=>1])) {
                return json(array('result' => 'success', 'msg' => '已取消点赞'));
            } else {
                return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍后重试'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //喜欢我的
    public function likeMe($userId)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            $fields = [
                'b.userId',
                'b.userPhone',
                'b.userName',
                'b.userPhoto',
                'a.isAgreed',
            ];
            $data = Db::table('jingo_user_vote a')->join('jingo_users b', 'a.strangerID = b.userId')->field($fields)->where(['a.userId'=>$param['userId'], 'isCancel'=>0])->order('time', 'DESC')->select();
            return json(array('result' => 'success', 'value' => $data));
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    //我喜欢的
    public function iLike($userId)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            $fields = [
                'userId',
                'userPhone',
                'userName',
                'userPhoto',
            ];
            $arr = Db::table('jingo_user_vote')->field(['userId', 'isAgreed'])->where('strangerID', $param['userId'])->where('isCancel', 0)->select();
            if ($arr) {
                $arr2 = [];
                foreach ($arr as $v) {      //组合数据
                    $arr2['userId'][] = $v['userId'];
                    $arr2['isAgreed'][] = $v['isAgreed'];
                }
                $arr3 = [];
                foreach ($arr2['userId'] as $k => $v) {
                    $arr3[$v] = $k;         //给数组排序
                }
                $data = Db::table('jingo_users')->field($fields)->where('userId', 'in', $arr2['userId'])->select();       //查询我喜欢的人的信息
                for ($i = 0; $i < count($data); $i++) {
                    $data[$i]['isAgreed'] = $arr2['isAgreed'][$arr3[$data[$i]['userId']]];
                }
            }else {
                $data = [];
            }
            return json(array('result' => 'success', 'value' => $data));
        } else {
            return json(array('result' => 'error', 'msg' => '请传参'));
        }
    }

    public function test() {
        $param = $this->request -> param();
        if (Api::checkApi($param['userId'], $param['token']) == 'false') exit();
        echo 'suc';
    }


}