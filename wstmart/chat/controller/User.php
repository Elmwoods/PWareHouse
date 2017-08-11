<?php
/**
 * Created by PhpStorm.
 * User: Lynn
 * Date: 2017/5/31
 * Time: 9:56
 */

namespace wstmart\chat\controller;


use function PHPSTORM_META\elementType;
use think\Controller;
use think\Db;
use think\Validate;
use wstmart\chat\model\Users;

class User extends Controller {
    public $request;
    private $api;

    public function __construct()
    {
        $this->request = request();
        $this->api = new Api();
    }

    //更换手机号
    public function changeMobile($mobile, $code, $userId, $token_id, $token)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            $api = new Api();
            $info = $api -> checkToken($param['token_id'], $param['token']);
            if ($info == 'success') {
                $arr = Db::table('jingo_user_code')->where(['mobile' => $param['mobile'], 'code' => $param['code']])->find();       //获取短信验证码相关信息
                $expire = time() - 30 * 60;       //验证码失效时间点
                if ($arr) {     //如果验证码信息存在
                    if ($expire < $arr['reg_time']) {       //并且验证码不超过30分钟
                        $boolen = Db::table('jingo_users') -> field('loginName') -> where(['userPhone' => $param['mobile']]) -> find();
                        if (!$boolen) {
                            if (Db::table('jingo_users')->update(['userPhone' => $param['mobile'], 'userId' => $param['userId']])) {
                                return json(['result' => 'success', 'msg' => '成功更换手机号']);
                            } else {
                                return json(['result' => 'error', 'msg' => '更换手机号失败,请稍候重试']);
                            }
                        }else {
                            return json(['result' => 'error', 'msg' => '手机号已被使用,请确认手机号唯一性']);
                        }
                    } else {
                        Db::table('jingo_user_code')->where('reg_time', '<', $expire)->delete();        //删除过期验证码
                        return json(['result' => 'error', 'msg' => '您的验证码已过期']);
                    }
                } else {
                    return json(['result' => 'error', 'msg' => '您输入的验证码有误']);
                }
            }elseif ($info == 'invalid') {
                return json(['result' => 'error', 'msg' => '您的密钥已过期']);
            }else {
                return json(['result' => 'error', 'msg' => '您的密钥有误']);
            }
        } else {
            return json(['result' => 'error', 'msg' => '请传参']);
        }
    }

    //设置VVID
    public function setVVID($userId, $vvid)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            $arr = Db::table('jingo_users')->field(['loginName', 'isSeted'])->where('userId', $param['userId'])->find();
            if ($arr['isSeted'] == 0) {
                $validate = new Validate([
                    'vvid' => 'require|max:20|/^[a-zA-Z0-9_]+$/',
                ]);       //验证用户名(必填,不大于20字符)
                $data = [
                    'vvid' => trim($param['vvid']),
                ];
                if ($validate->check($data)) {
                    if (Db::table('jingo_users')->where('userId', $param['userId'])->update(['loginName' => $param['vvid'], 'isSeted' => 1])) {
                        return json(['result' => 'success', 'msg' => 'VVID设置成功']);
                    } else {
                        return json(['result' => 'error', 'msg' => '网络又开小差了,请稍后重试']);
                    }
                }
            } else {
                return json(['result' => 'error', 'msg' => '尊敬的客户您好!VVID只能设置一次']);
            }
        } else {
            return json(['result' => 'error', 'msg' => '请传参']);
        }
    }

    //设置密码(用于初始化密码)
    public function setPass($userId, $password) {
        $param = $this->request->param();
        if (!empty($param)) {
            $data['loginSecret'] = mt_rand(1000, 9999);
            $data['loginPwd'] = md5($param['password'].$data['loginSecret']);
            if (Db::table('jingo_users') -> where('userId', $param['userId']) -> update($data)) {
                return json(['result' => 'success', 'msg' => '密码设置成功']);
            }else {
                return json(['result' => 'error', 'msg' => '网络又开小差了,请稍后重试']);
            }
        }else {
            return json(['result' => 'error', 'msg' => '请传参']);
        }
    }

    //修改密码
    public function changePass($mobile, $code, $userId, $password)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            $arr = Db::table('jingo_user_code')->where(['mobile' => $param['mobile'], 'code' => $param['code']])->find();       //获取短信验证码相关信息
            $expire = time() - 30 * 60;       //验证码失效时间点
            if ($arr) {     //如果验证码信息存在
                if ($expire < $arr['reg_time']) {       //并且验证码不超过30分钟
                    $loginSecret = mt_rand(1000,9999);
                    $boolen = Db::table('jingo_users')->where(['userPhone' => $param['mobile'], 'userId' => $param['userId']])->update(['loginPwd' => md5($param['password'] . $loginSecret), 'loginSecret' => $loginSecret]);       //更新密码
                    if ($boolen) {
                        return urldecode(json_encode(['result' => 'success', 'msg' => urlencode('密码修改成功')]));
                    } else {
                        return urldecode(json_encode(['result' => 'error', 'msg' => urlencode('网络又开小差了,请稍候重试')]));
                    }
                } else {
                    Db::table('jingo_user_code')->where('reg_time', '<', $expire)->delete();        //删除过期验证码
                    return json(['result' => 'error', 'msg' => '您的验证码已过期']);
                }
            } else {
                return json(['result' => 'error', 'msg' => '您输入的验证码有误']);
            }
        } else {
            return json(['result' => 'error', 'msg' => '请传参']);
        }
    }

    //生成个人二维码
    public function setQRcode($userId)
    {
        include_once "phpqrcode.php";
        $param = $this->request->param();
        if (!$param) {
            exit();
        }
        $data = Db::table('jingo_users') -> field(['userName', 'qrcode']) -> where('userId', $param['userId']) -> find();
        $serialize = json_encode([              //二维码信息
            'userId' => $param['userId'],
            'nickName' => $data['userName']
        ]);
        $rand = mt_rand(10000000, 99999999);
        $time = time();
        $name = md5($rand) . md5($time);          //生成二维码名
        $date = date("Ymd", time());
        if (!file_exists(ROOT_PATH . 'upload/qrcode/personal/' . $date)) {
            mkdir(ROOT_PATH . 'upload/qrcode/personal/' . $date);
        }

        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 2;//生成图片大小
        $path = $date . '/' . $name . '.png';
        $map = [
            'userId' => $param['userId']
        ];
        QRcode::png($serialize, ROOT_PATH . 'upload/qrcode/personal/' . $date . '/' . $name . '.png', $errorCorrectionLevel, $matrixPointSize, 2);

        $arr = Db::table('jingo_users')->field('userPhoto')->where('userId', $param['userId'])->find();
        if ($arr) {
            if ($arr['userPhoto'] != '') {
                $logo = ROOT_PATH . $arr['userPhoto'];//准备好的logo图片
            }else {
                $logo = '';
            }
            $QR = ROOT_PATH . 'upload/qrcode/personal/' . $date . '/' . $name . '.png';//已经生成的原始二维码图
            $QR = imagecreatefromstring(file_get_contents($QR));
            if ($logo != '') {
                //$QR = imagecreatefromstring(file_get_contents($QR));
                $logo = imagecreatefromstring(file_get_contents($logo));
                $QR_width = imagesx($QR);//二维码图片宽度
                $QR_height = imagesy($QR);//二维码图片高度
                $logo_width = imagesx($logo);//logo图片宽度
                $logo_height = imagesy($logo);//logo图片高度
                $logo_qr_width = $QR_width / 5;
                $scale = $logo_width / $logo_qr_width;
                $logo_qr_height = $logo_height / $scale;
                $from_width = ($QR_width - $logo_qr_width) / 2;

                //重新组合图片并调整大小
                imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
            }

            //输出图片
            imagepng($QR, ROOT_PATH . 'upload/qrcode/personal/' . $date . '/' . $name . '.png');
        }

        if (file_exists(ROOT_PATH . 'upload/qrcode/personal/' . $date . '/' . $name . '.png')) {
            if (Db::table('jingo_users')->where($map)->update(['qrcode' => $path])) {
                if (file_exists(ROOT_PATH . 'upload/qrcode/personal/'.$data['qrcode'])) {
                    unlink(ROOT_PATH . 'upload/qrcode/personal/'.$data['qrcode']);
                }
                return json(array('result' => 'success', 'value' => ['qrcode'=>$path]));
            } else {
                unlink(ROOT_PATH . 'upload/qrcode/personal/' . $date . '/' . $name . '.png');     //如果更新个人二维码路径失败则删除二维码(linux下注意权限问题)
                return json(array('result' => 'error', 'msg' => '二维码图片路径保存失败,请稍候重试'));
            }
        } else {
            return json(array('result' => 'error', 'msg' => '二维码图片失效,请重新生成'));
        }
    }

    //搜索陌生人
    public function search($userId, $keyword)
    {
        $param = $this->request->param();
        if (!empty($param)) {
            $map = [            //搜索条件
                'loginName' => $param['keyword'],
                'userPhone' => $param['keyword'],
            ];
            //查询朋友ID
            $arr = Db::table('jingo_user_friends')->field('friendID')->where('userId', $param['userId']) -> where('isAgreed', 1) -> where('isDelete', 0) ->select();
            //查询特殊情况(不是好友并且加入黑名单)
            $arr3 = Db::table('jingo_user_friends')->field('friendID')->where('userId', $param['userId']) -> where('isAgreed', 0) -> where('isBlackList', 1) ->select();
            $arr = array_merge($arr, $arr3);
            if ($arr) {
                $friends = [];
                foreach ($arr as $v) {
                    $friends[] = $v['friendID'];
                }
            }
            $fields = [
                'userId',
                'userPhoto',
                'userName',
                'loginName',
                'userSex',
                'brithday',
                'signature',
                'userPhone',
                'userNation',
                'userProvince',
                'userCity'
            ];
            //查询关键词返回的用户信息
            $arr2 = Db::table('jingo_users')->field($fields)->whereOr($map)->find();
            if (substr($arr2['userId'], -2) == '00') {
                return json(array('result' => 'error', 'msg' => '搜索结果无'));
            }
            if (isset($friends)) {      //如果有好友列表
                if (!empty($arr2) && !in_array($arr2['userId'], $friends)) {
                    $arr2['birthday'] = $arr2['brithday'];
                    unset($arr2['brithday']);
                    return json(array('result' => 'success', 'value' => $arr2));
                } else {
                    return json(array('result' => 'error', 'msg' => '搜索结果无'));
                }
            }else {
                if ($arr2) {
                    $arr2['birthday'] = $arr2['brithday'];
                    unset($arr2['brithday']);
                    return json(array('result' => 'success', 'value' => $arr2));
                }else {
                    return json(array('result' => 'error', 'msg' => '搜索结果无'));
                }
            }
        } else {
            return json(['result' => 'error', 'msg' => '请传参']);
        }
    }

    //名片
    public function card($userId, $targetID) {
        $param = $this->request->param();
        if (!empty($param)) {
            //字段列表
            $fields = [
                'a.userId',
                'a.userName',
                'a.loginName',
                'a.signature',
                'a.userSex',
                'a.brithday',
                'a.userAge',
                'a.userPhoto',
                'a.userNation',
                'a.userProvince',
                'a.userCity',
                'b.pic',
                'c.nickName',
            ];
            //查询是否存在好友请求
            $arr = Db::table('jingo_user_friends') -> field(['isAgreed', 'isDelete']) -> where(['userId'=>$param['userId'], 'friendID'=>$param['targetID']]) -> find();
            //判断对方是否添加我为好友
            if ($a = Db::table('jingo_user_friends') -> field(['isAgreed']) -> where(['userId' => $param['targetID'], 'friendID'=>$param['userId']]) -> find()) {
                if ($a['isAgreed'] == 1) {
                    //判断是否已删除好友
                    if (Db::table('jingo_user_friends') -> where(['userId' => $param['userId'], 'friendID'=>$param['targetID'], 'isDelete'=>1]) -> find()) {
                        $data = Db::table('jingo_users') -> field(['userId', 'userName', 'loginName', 'userPhoto', 'signature', 'userSex', 'brithday', 'userAge', 'userNation', 'userProvince', 'userCity', 'setStranger']) -> where('userId', $param['targetID']) -> find();
                        //查询朋友圈图片及个人信息start
                        if ($data['setStranger'] == 1) {
                            $pic = [];
                            $pic['pic'] = '';
                            $arr1 = Db::table('jingo_users a') -> join('jingo_user_circle b', 'b.fromUserId = a.userId', 'LEFT') -> join('jingo_user_friends c','c.friendID = a.userId', 'LEFT') -> field($fields) -> where('a.userId', $param['targetID']) -> where('c.userId', $param['userId']) -> select();
                            if ($arr1) {
                                for($i=0;$i < count($arr1);$i++) {
                                    if (!empty($arr1[$i]['pic'])) {
                                        $pic['pic'] .= $arr1[$i]['pic'].',';
                                    }else {
                                        $pic['pic'] .= '';
                                    }
                                }
                                $pic['pic'] = trim($pic['pic'], ',');
                                unset($arr1[0]['pic']);
                                $data =array_merge($pic,$arr1[0]);
                            }else {
                                $data['pic'] = '';
                            }
                            $data['setStranger'] = 1;
                        }else {
                            $data['setStranger'] = 0;
                        }
                        //查询朋友圈图片及个人信息end
                        $data['state'] = 0;
                        return json(array('result' => 'success', 'value' => $data));
                        exit();
                    }
                }else {
                    $data = Db::table('jingo_users') -> field(['userId', 'userName', 'loginName', 'signature', 'userSex', 'userPhoto', 'brithday', 'userAge', 'userNation', 'userProvince', 'userCity']) -> where('userId', $param['targetID']) -> find();
                    $data['state'] = 3;
                    return json(array('result' => 'success', 'value' => $data));
                    exit();
                }
            }
            $data = [];
            $data['pic'] = '';
            if ($arr) {             //如果存在好友请求
                if ($arr['isAgreed'] == 1 && $arr['isDelete'] == 0) {         //如果好友同意
                    $arr1 = Db::table('jingo_users a') -> join('jingo_user_circle b', 'b.fromUserId = a.userId', 'LEFT') -> join('jingo_user_friends c','c.friendID = a.userId', 'LEFT') -> field($fields) -> where('a.userId', $param['targetID']) -> where('c.userId', $param['userId']) -> select();
                    if ($arr1) {
                        for($i=0;$i < count($arr1);$i++) {
                            if (!empty($arr1[$i]['pic'])) {
                                $data['pic'] .= $arr1[$i]['pic'].',';
                            }else {
                                $data['pic'] .= '';
                            }
                        }
                        $data['pic'] = trim($data['pic'], ',');
                        unset($arr1[0]['pic']);
                        $data =array_merge($data,$arr1[0]);
                    }else {
                        $data['pic'] = '';
                    }

                    $data['state'] = 1;         //状态1表示可以聊天了
                }/*elseif ($arr['isAgreed'] == 1 && $arr['isDelete'] == 1) {
                    $data = Db::table('jingo_users') -> field(['userId', 'userName', 'loginName', 'signature', 'userSex', 'brithday', 'userNation', 'userProvince', 'userCity']) -> where('userId', $param['targetID']) -> find();
                    $data['state'] = 0;         //如果好友未同意返回状态0(表示陌生人)
                }*/ else {
                    $data = Db::table('jingo_users') -> field(['userId', 'userName', 'loginName', 'userPhoto', 'signature', 'userSex', 'brithday', 'userAge', 'userNation', 'userProvince', 'userCity']) -> where('userId', $param['targetID']) -> find();
                    $data['state'] = 2;         //如果好友未同意返回状态2(表示已添加好友,但对方未同意)
                }
            }else {                             //如果不存在好友请求
                //目标的个人信息
                $data = Db::table('jingo_users') -> field(['userId', 'userName', 'loginName', 'userPhoto', 'signature', 'userSex', 'brithday', 'userAge', 'userNation', 'userProvince', 'userCity', 'setStranger']) -> where('userId', $param['targetID']) -> find();
                if ($data['userId'] == $param['userId']) {      //如果好友请求者是自己,则返回状态1
                    $pic = [];
                    $pic['pic'] = '';
                    $arr1 = Db::table('jingo_users a') -> join('jingo_user_circle b', 'b.fromUserId = a.userId', 'LEFT') -> field(['a.userId', 'a.userName', 'a.loginName', 'a.userPhoto', 'a.signature', 'a.userSex', 'a.brithday', 'a.userAge', 'a.userNation', 'a.userProvince', 'a.userCity', 'b.pic']) -> where('a.userId', $param['userId']) -> select();
                    if (!empty($arr1)) {
                        for($i=0;$i < count($arr1);$i++) {
                            if (!empty($arr1[$i]['pic'])) {
                                $pic['pic'] .= $arr1[$i]['pic'].',';
                            }else {
                                $pic['pic'] .= '';
                            }
                        }
                        $pic['pic'] = trim($pic['pic'], ',');
                        unset($arr1[0]['pic']);
                        $data =array_merge($pic,$arr1[0]);
                    }else {
                        $data['pic'] = '';
                    }
                    $data['state'] = 1;
                }else {
                    //查询朋友圈图片及个人信息start
                    if ($data['setStranger'] == 1) {
                        $pic = [];
                        $pic['pic'] = '';
                        //查询朋友圈相关信息
                        $arr1 = Db::table('jingo_users a') -> join('jingo_user_circle b', 'b.fromUserId = a.userId', 'LEFT') -> field(['a.userId', 'a.userName', 'a.loginName', 'a.userPhoto', 'a.signature', 'a.userSex', 'a.brithday', 'a.userAge', 'a.userNation', 'a.userProvince', 'a.userCity', 'b.pic']) -> where('a.userId', $param['targetID']) -> select();
                        if (!empty($arr1)) {
                            for($i=0;$i < count($arr1);$i++) {
                                if (!empty($arr1[$i]['pic'])) {
                                    $pic['pic'] .= $arr1[$i]['pic'].',';
                                }else {
                                    $pic['pic'] .= '';
                                }
                            }
                            $pic['pic'] = trim($pic['pic'], ',');
                            unset($arr1[0]['pic']);
                            $data =array_merge($pic,$arr1[0]);
                        }else {
                            $data['pic'] = '';
                        }
                        $data['setStranger'] = 1;
                    }else {
                        $data['setStranger'] = 0;
                    }
                    //查询朋友圈图片及个人信息end
                    $data['state'] = 0;
                }
            }
            if ($data) {
                return json(array('result' => 'success', 'value' => $data));
            }else {
                return json(array('result' => 'error', 'msg' => '获取朋友信息失败,请稍候重试'));
            }
        }else{
            return json(['result' => 'error', 'msg' => '请传参']);
        }
    }

    //更新定位信息
    public function gps($userId, $longitude, $latitude) {
        $param = $this->request->param();
        if (!empty($param)) {
            if (Db::table('jingo_users') -> where('userId', $param['userId']) -> update(['longitude'=>$param['longitude'], 'latitude'=>$param['latitude']])) {
                return json(array('result' => 'success', 'msg' => '定位更新成功'));
            }else {
                return json(array('result' => 'error', 'msg' => '定位更新失败'));
            }
        }else {
            return json(['result' => 'error', 'msg' => '请传参']);
        }
    }

    //个人设置
    public function setting($userId) {
        $param = $this->request->param();
        $users = new Users();
        if (!empty($param)) {
            if (isset($param['upload'])) {
                //获取上传图片
                $file = request()->file('userPhoto');
                // 移动到框架应用根目录/public/uploads/ 目录下
                if (!empty($file)) {
                    if (!file_exists(ROOT_PATH . 'upload/userPhotos/')) {
                        mkdir(ROOT_PATH . 'upload/userPhotos/');
                    }
                    // 移动到框架应用根目录/public/uploads/目录下
                    $info = $file->validate(['size' => 5 * 1024 * 1024, 'ext' => 'jpg,png,gif'])->rule('uniqid')->move(ROOT_PATH . 'upload/userPhotos/');
                    if ($info) {
                        $path = 'upload/userPhotos/'.$info->getFilename();
                    } else {
                        // 上传失败获取错误信息
                        //echo $file->getError();
                        return json(array('result' => 'error', 'msg' => $file->getError()));
                    }
                } else {
                    return json(array('result' => 'error', 'msg' => '上传图片失败'));
                }
            }
            if (isset($path)) {
                $param['userPhoto'] = $path;
                $pre = $users -> field('userPhoto') -> find($param['userId']);
                $str = substr($pre['userPhoto'], -13, 9);        //截取图片路径中的图片名
            }
            $users -> startTrans();

            if ($users -> allowField(true) -> isUpdate(true) -> save($param, ['userId'=>$param['userId']])) {
                if (isset($str) && $str != 'userPhoto' && isset($path) && $pre['userPhoto'] !='' && file_exists(ROOT_PATH.$pre['userPhoto'])) {
                    unlink($pre['userPhoto']);
                }
                if (isset($path)) {
                    Db::startTrans();
                    if (Db::table('jingo_user_groupmembers') -> where('userId', $param['userId']) -> update(['groupPhoto'=>$path])) {
                        Db::commit();
                    }else {
                        Db::rollback();
                    }
                }
                $users -> commit();
                if (isset($path)) {
                    return json(array('result' => 'success', 'msg' => '已更改设置', 'path'=>$path));
                }else {
                    return json(array('result' => 'success', 'msg' => '已更改设置'));
                }
            }else {
                $users -> rollback();
                return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍后重试'));
            }
        }else {
            return json(['result' => 'error', 'msg' => '请传参']);
        }
    }

    //设置聊天背景
    public function setBackground() {
        $param = $this->request->param();
        $users = new Users();
        if (!empty($param)) {
            //获取上传图片
            $file = request()->file('userBG');
            // 移动到框架应用根目录/public/uploads/ 目录下
            if (!empty($file)) {
                if (!file_exists(ROOT_PATH . 'upload/userBackgrounds/')) {
                    mkdir(ROOT_PATH . 'upload/userBackgrounds/');
                }
                // 移动到框架应用根目录/public/uploads/目录下
                $info = $file->validate(['size' => 5 * 1024 * 1024, 'ext' => 'jpg,png,gif,jpeg'])->rule('uniqid')->move(ROOT_PATH . 'upload/userBackgrounds/');
                if ($info) {
                    $path = 'upload/userBackgrounds/'.$info->getFilename();
                } else {
                    // 上传失败获取错误信息
                    //echo $file->getError();
                    return json(array('result' => 'error', 'msg' => $file->getError()));
                }
            } else {
                return json(array('result' => 'error', 'msg' => '上传图片失败'));
            }

            //获取当前聊天背景路径
            if (isset($path)) {
                $param['userBG'] = $path;
                $pre = $users -> field('userPhoto') -> find($param['userId']);
            }

            //修改聊天背景
            $users->startTrans();
            if ($users -> allowField(true) -> isUpdate(true) -> save($param, ['userId'=>$param['userId']])) {
                if (isset($path) && $pre['userPhoto'] !='' && file_exists(ROOT_PATH.$pre['userPhoto'])) {
                    unlink($pre['userPhoto']);
                }
                $users -> commit();
                if (isset($path)) {
                    return json(array('result' => 'success', 'msg' => '已更改设置', 'path'=>$path));
                }else {
                    return json(array('result' => 'success', 'msg' => '已更改设置'));
                }
            }else {
                $users -> rollback();
                return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍后重试'));
            }
        }else {
            return json(['result' => 'error', 'msg' => '请传参']);
        }
    }

    //不让他看我的朋友圈
    public function hiddenMeList($userId) {
        $param = $this->request->param();
        if (!empty($param)) {
            $data = Db::table('jingo_user_friends a') -> field(['b.userId', 'b.userPhoto']) -> join('jingo_users b', 'a.friendID = b.userId', 'LEFT') -> where(['a.userId'=>$param['userId'], 'isCheck'=>1]) -> order('b.userId', 'ASC') -> select();
            return json(array('result' => 'success', 'value' => $data));
        }else {
            return json(['result' => 'error', 'msg' => '请传参']);
        }
    }

    //不看他的朋友圈
    public function ignoreList() {
        $param = $this->request->param();
        if (!empty($param)) {
            $data = Db::table('jingo_user_friends a') -> field(['b.userId', 'b.userPhoto']) -> join('jingo_users b', 'a.friendID = b.userId', 'LEFT') -> where(['a.userId'=>$param['userId'], 'circleEnable'=>1]) -> order('b.userId', 'ASC') -> select();
            return json(array('result' => 'success', 'value' => $data));
        }else {
            return json(['result' => 'error', 'msg' => '请传参']);
        }
    }

    //通讯录好友
    public function phoneBook($userId) {
        $param = $this->request->param();
        $param['list'] = str_replace('&quot;', '"', $param['list']);
        if (!empty($param)) {
            $data = Db::table('jingo_user_friends a')
                ->join('users b', 'a.friendID = b.userId', 'LEFT')
                ->field(['a.friendID', 'a.nickName', 'a.isAgreed', 'a.time', 'b.userPhoto', 'b.userPhone', 'b.userName'])
                ->where('a.userId', $param['userId'])
                ->where('isAgreed', 1)
                ->order('time', 'DESC')
                ->select();
            if ($data) {
                $friends = [];
                foreach ($data as $v) {
                    $friends[] = $v['userPhone'];
                }
            }
            $info = Db::table('jingo_users') -> field(['isRec', 'userPhone']) -> where('userId', $param['userId']) -> find();
            if ($info['isRec']) {
                //$json = '[{"name":"林浩","phone":"16461684896"}]';
                $json = $param['list'];
                if (isset($json)) {
                    $json = json_decode($json);
                    $phone = [];        //通讯录的手机号
                    foreach ($json as $v) {
                        if (str_replace(' ', '', $v -> phone) == $info['userPhone']) {
                            continue;
                        }
                        $phone[] = str_replace(' ', '', $v -> phone);
                    }
                    if ($phone) {       //判断手机号集合不为空时执行查询
                        //查询通讯录好友信息
                        $arr = Db::table('jingo_users')->field(['userId', 'userPhoto', 'userPhone', 'createTime'])->where('userPhone', 'in', $phone)->order('createTime', 'DESC')->select();
                        if ($arr) {
                            foreach ($arr as $k => $v) {
                                foreach ($json as $key => $value) {
                                    if ($v['userPhone'] == str_replace(' ', '', $value -> phone)) {
                                        $arr[$k]['trueName'] = $value -> name;
                                    }
                                }
                                if (isset($friends)) {
                                    if (in_array($v['userPhone'], $friends)) {
                                        $arr[$k]['state'] = 1;
                                    }else {
                                        $arr[$k]['state'] = 2;
                                    }
                                }else {
                                    $arr[$k]['state'] = 2;
                                }
                            }
                        }else {
                            $arr = [];
                        }
                    }else {
                        $arr = [];
                    }
                    return json(array('result' => 'success', 'value' => $arr));
                }
            }else {
                return json(['result' => 'error', 'msg' => '请先开启推荐通讯录朋友功能']);
            }
        }else {
            return json(['result' => 'error', 'msg' => '请传参']);
        }
    }

    //不看他的朋友圈(设置)
    public function ignore($userId, $friendID) {
        $param = $this->request->param();
        if (!empty($param)) {
            if ($param['friendID'] != '') {
                $arr = explode(',', $param['friendID']);
                if ($arr) {
                    Db::startTrans();
                    Db::table('jingo_user_friends') -> where('userId', $param['userId']) -> update(['circleEnable'=>0]);
                    if (Db::table('jingo_user_friends') -> where('userId', $param['userId']) -> where('friendID', 'in', $arr) -> update(['circleEnable'=>1])) {
                        Db::commit();
                        return json(array('result' => 'success', 'msg' => '设置成功'));
                    }else {
                        Db::rollback();
                        return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
                    }
                }
            }else {
                Db::startTrans();
                if (Db::table('jingo_user_friends') -> where('userId', $param['userId']) -> update(['circleEnable'=>0])) {
                    Db::commit();
                    return json(array('result' => 'success', 'msg' => '设置成功'));
                }else {
                    Db::rollback();
                    return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
                }
            }
        }else {
            return json(['result' => 'error', 'msg' => '请传参']);
        }
    }

    //不让他看我的朋友圈(设置)
    public function hiddenMe($userId, $friendID) {
        $param = $this->request->param();
        if (!empty($param)) {
            if ($param['friendID'] != '') {
                $arr = explode(',', $param['friendID']);
                if ($arr) {
                    Db::startTrans();
                    Db::table('jingo_user_friends') -> where('userId', $param['userId']) -> update(['isCheck'=>0]);
                    if (Db::table('jingo_user_friends') -> where('userId', $param['userId']) -> where('friendID', 'in', $arr) -> update(['isCheck'=>1])) {
                        Db::commit();
                        return json(array('result' => 'success', 'msg' => '设置成功'));
                    }else {
                        Db::rollback();
                        return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
                    }
                }
            }else {
                Db::startTrans();
                if (Db::table('jingo_user_friends') -> where('userId', $param['userId']) -> update(['isCheck'=>0])) {
                    Db::commit();
                    return json(array('result' => 'success', 'msg' => '设置成功'));
                }else {
                    Db::rollback();
                    return json(array('result' => 'error', 'msg' => '网络又开小差了,请稍候重试'));
                }
            }
        }else {
            return json(['result' => 'error', 'msg' => '请传参']);
        }
    }


}