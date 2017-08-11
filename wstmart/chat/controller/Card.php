<?php
/**
 * Created by PhpStorm.
 * User: Me
 * Date: 2017/7/28
 * Time: 10:22
 */

namespace wstmart\chat\controller;


use think\Db;
use think\Validate;

class Card
{
    public $request;


    public function __construct()
    {
        $this->request = request();
    }

    //新增名片
    public function add() {
        $param = $this->request->param();
        if (!empty($param)) {
            $rules = [
                'name' => 'require|max:10',
                'email' => 'email',
                'company' => 'require',
                'job' => 'require',
                'address' => 'require',
            ];
            $msg = [
                'name.require' => '姓名不可为空',
                'email.require' => '邮箱格式不正确',
                'company.require' => '公司不可为空',
                'job.require' => '职务不可为空',
                'address.require' => '地址不可为空',
            ];
            $validate = new Validate($rules, $msg);
            if ($validate -> check($param)) {
                $char = new First();
                $param['short'] = $char -> getFirstchar($param['name']);
                var_dump($param);
                if (Db::table('jingo_user_cards') -> insert($param)) {
                    return json(['result'=>'success', 'msg'=>'电子名片创建成功']);
                }else {
                    return json(['result'=>'error', 'msg'=>'网络又开小差了']);
                }
            }else {
                return json(['result'=>'error', 'msg'=>$validate->getError()]);
            }
        }else {
            return json(['result'=>'error', 'msg'=>'请传参']);
        }
    }

    //获取名片信息
    public function info($userId) {
        $param = $this->request->param();
        if (!empty($param)) {
            $data = Db::table('jingo_user_cards') -> field(['id', 'userId', 'name', 'phone', 'email', 'company', 'job', 'address', 'account', 'vcr', 'hidden']) -> where('userId', $param['userId']) -> find();
            if ($data) {
                if ($data['hidden']) {
                    $hidden = explode(',', $data['hidden']);
                    foreach ($hidden as $k => $v) {
                        switch ($v) {
                            case '1' :
                                unset($data['phone']);
                                break;
                            case '2' :
                                unset($data['email']);
                                break;
                            case '3' :
                                unset($data['company']);
                                break;
                            case '4' :
                                unset($data['job']);
                                break;
                            case '5' :
                                unset($data['address']);
                                break;
                            case '6' :
                                unset($data['account']);
                                break;
                            case '7' :
                                unset($data['vcr']);
                                break;
                        }
                    }
                    unset($data['hidden']);
                }
                return json(['result'=>'success', 'value'=>$data]);
            }else {
                return json(['result'=>'error', 'msg'=>'网络又开小差了']);
            }
        }else {
            return json(['result'=>'error', 'msg'=>'请传参']);
        }
    }

    //编辑名片
    public function edit() {
        $param = $this->request->param();
        if (!empty($param)) {
            $rules = [
                'name' => 'require|max:10',
                'email' => 'email',
                'company' => 'require',
                'job' => 'require',
                'address' => 'require',
            ];
            $msg = [
                'name.require' => '姓名不可为空',
                'email' => '邮箱格式不正确',
                'company.require' => '公司不可为空',
                'job.require' => '职务不可为空',
                'address.require' => '地址不可为空',
            ];
            $validate = new Validate($rules, $msg);
            if ($validate -> check($param)) {
                if (Db::table('jingo_user_cards') -> where('id', $param['id']) -> update($param)) {
                    return json(['result'=>'success', 'msg'=>'电子名片修改成功']);
                }else {
                    return json(['result'=>'error', 'msg'=>'网络又开小差了']);
                }
            }else {
                return json(['result'=>'error', 'msg'=>$validate->getError()]);
            }
        }else {
            return json(['result'=>'error', 'msg'=>'请传参']);
        }
    }

    //名片收藏夹
    public function cardBook() {
        $param = $this->request->param();
        if (!empty($param)) {
            $arr = Db::table('jingo_users') -> field(['cardBook']) -> find($param['userId']);
            if ($arr['cardBook']) {
                $card = explode(',', $arr['cardBook']);
                $data = Db::table('jingo_user_cards') -> where('id', 'in', $card) -> select();
                if ($data) {
                    return json(['result'=>'success', 'value'=>$data]);
                }else {
                    return json(['result'=>'error', 'msg'=>'网络又开小差了']);
                }
            }else {
                return json(['result'=>'error', 'value'=>[]]);
            }
        }else {
            return json(['result'=>'error', 'msg'=>'请传参']);
        }
    }

    //名片搜索
    public function search() {
        $param = $this->request->param();
        if (!empty($param)) {
            $field = ['a.cardBook', 'b.name', 'b.phone', 'b.email', 'b.company', 'b.job', 'b.account', 'b.address', 'b.vcr'];
            $selfInfo = Db::table('jingo_users a') -> join('jingo_user_cards b', 'a.userId = b.userId', 'LEFT') -> field($field) -> find($param['userId']);
            if ($selfInfo) {
                if ($selfInfo['cardBook']) {
                    $range = explode(',', $selfInfo['cardBook']);
                    $selfCardID = Db::table('jingo_user_cards') -> field('id') -> where('userId', $param['userId']) -> find();
                    $range = array_diff($range, [$selfCardID['id']]);
                    if ($range) {
                        session('keyword', $param['keyword']);
                        $data = Db::table('jingo_user_cards a')
                            -> where('id', 'in', $range)
                            -> where(function ($query) {
                                $query -> where('name', 'like', '%'.session('keyword').'%') -> whereOr('short', 'like', session('keyword').'%');
                            }) -> select();
                    }else {
                        $data = [];
                    }

                }else {
                    $data = [];
                }
            }else {
                $selfInfo = [];
                $data = [];
            }
            return json(['result'=>'success', 'value'=>$data, 'info'=>$selfInfo]);
        }else {
            return json(['result'=>'error', 'msg'=>'请传参']);
        }
    }

    //收藏
    public function favorite($userId, $targetID) {
        $param = $this->request->param();
        if (!empty($param)) {
            $info = Db::table('jingo_users') -> field('cardBook') -> find($param['userId']);
            if ($info['cardBook']) {
                $info['cardBook'] = $info['cardBook'].$param['targetID'];
            }else {
                $info['cardBook'] = $param['targetID'];
            }
            if (Db::table('jingo_users') -> where('userId', $param['userId']) -> update(['cardBook'=>$param['cardBook']])) {
                return json(['result'=>'success', 'msg'=>'收藏名片成功']);
            }else {
                return json(['result'=>'error', 'msg'=>'网络又开小差了,请稍后重试']);
            }
        }else {
            return json(['result'=>'error', 'msg'=>'请传参']);
        }
    }

    //隐藏项
    public function hidden($userId, $hidden) {
        $param = $this->request->param();
        if (!empty($param)) {
            if (Db::table('jingo_user_cards') -> where('userId', $param['userId']) -> update(['hidden'=>$param['hidden']])) {
                return json(['result'=>'success', 'msg'=>'隐藏设置成功']);
            }else {
                return json(['result'=>'error', 'msg'=>'网络又开小差了,请稍后重试']);
            }
        }else {
            return json(['result'=>'error', 'msg'=>'请传参']);
        }
    }
}