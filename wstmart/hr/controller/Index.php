<?php
namespace wstmart\hr\controller;
use think\Controller;
use think\Db;
use wstmart\hr\model\Phone;

/**
 * Created by PhpStorm.
 * User: Lynn
 * Date: 2017/6/22
 * Time: 13:45
 */
class Index extends Controller {
    public function index() {
        $param = request() -> param();
        if (!empty($param)) {
            if (isset($param['name']) && $param['name'] != '') {
                $arr = Db::table('jingo_phone_book') -> field(['name', 'phone']) -> where('name', 'like', '%'.$param['name'].'%') -> whereOr('short', 'like', $param['name'].'%') -> select();
                $this -> assign('phone', $arr);
            }
        }
        return view('default/index');
    }

    public function upload() {
        return view('default/upload');
    }

    public function import() {
        $filename = $_FILES['csv']['tmp_name'];
        if (empty ($filename)) {
            echo '请选择要导入的CSV文件！';
            exit;
        }
        /*用于CSV文件的导入*/
        $content = preg_split('/\n/', unicodeToUtf8(file_get_contents($filename)));      //对内容进行中文转码,并按换行符分离每一行数据
        array_splice($content, -1);     //去除最后一个空数组
        $fieldArr = preg_split('/[\,]/', $content[0]);      //对第一行按逗号分割,获取数据表字段
        $fieldNum = count($fieldArr);   //获取数据表字段数
        $splitArr = array();            //初始化分割数组
        foreach (array_splice($content, 1) as $k => $v) {   //去除第一行的数据
            $splitArr[] = preg_split('/[\,]/', $v);         //将每一行按逗号分割获取单元格的数据
        }
        for ($i=0; $i < count($splitArr); $i++) {           //循环获取二维数组的值
            for ($j=0; $j < $fieldNum; $j++) {
                $splitArr[$i][$fieldArr[$j]] = $splitArr[$i][$j];       //将字段键名写进关联数组
            }
            array_splice($splitArr[$i], 0, $fieldNum);      //去除索引键名
        }
        $goodsCsv = new Phone();
        $result = array();
        foreach($splitArr as $item){
            $result[] = $goodsCsv->allowField(true)->data($item)->isUpdate(false)->save();      //循环写入数据库
        }
        if ($result) {
            echo 'success';
        }else {
            echo 'error';
        }
        exit();
    }

    public function add() {
        $param = request()->param();
        if (!empty($param)) {
            $data = [];
            foreach ($param['info'] as $v) {
                $v = array_filter($v);
                if (count($v) != 3) {
                    $v = [];
                }
                if ($v) {
                    $data[] = $v;
                }
            }
            if (Db::table('jingo_phone_book') -> insertAll($data)) {
                $this->success('添加成功',url('hr/index/add'), '', 1);
            }else {
                $this->success('添加失败',url('hr/index/add'), '', 1);
            }
        }
        return view('default/submit');
    }

    public function show() {
        if (session('code') == '都可以') {
            $arr = Db::table('jingo_phone_book') -> order('id', 'ASC') -> paginate(10);
            $this -> assign('list', $arr);
            return view('default/show');
        }else {
            $this->success('非法操作',url('hr/index/index'), '', 1);
        }
    }

    public function update() {
        if (session('code') == '都可以') {
            if (!empty($_POST)) {
                $pre = $_POST['pre'];
                $id = $_POST['id'];
                unset($_POST['pre']);
                unset($_POST['id']);
                if (Db::table('jingo_phone_book') -> where('id', $id) -> update($_POST)) {
                    $this->success('修改成功',$pre, '', 1);
                }else {
                    $this->error('修改失败,请重试');
                }
            }
            if (!empty($_GET['id'])) {
                $arr = Db::table('jingo_phone_book') -> where('id', $_GET['id']) -> find();
                $pre = $_SERVER['HTTP_REFERER'];
                $this->assign('pre', $pre);
                $this->assign('detail', $arr);
                return view('default/update');
            }
        }else {
            $this->success('非法操作',url('hr/index/index'), '', 1);
        }

    }

    public function delete() {
        if (session('code') == '都可以') {
            if (!empty($_GET['id'])) {
                $pre = $_SERVER['HTTP_REFERER'];
                if (Db::table('jingo_phone_book') -> delete(['id'=>$_GET['id']])) {
                    $this->success('删除成功',$pre, '', 1);
                }else {
                    $this->error('删除失败,请重试');
                }
            }
        }else {
            $this->success('非法操作',url('hr/index/index'), '', 1);
        }

    }

    public function check() {
        return view('default/check');
    }

    public function turn() {
        if (!empty($_POST['code'])) {
            if ($_POST['code'] == '都可以') {
                session('code', $_POST['code']);
                $this->redirect(url('hr/index/show'));
            }else {
                session('code', $_POST['code']);
                $this->error('口令有误,请询问萌萌哒管理员后重试!', url('hr/index/check'), '', 3);
            }
        }
    }

    public function search() {
        if (session('code') == '都可以') {
            $param = request() -> param();
            if (isset($param['keyword']) && $param['keyword'] != '') {
                session('keyword', $param['keyword']);
            }
            $arr = Db::table('jingo_phone_book') -> where('name', 'like', '%'.session('keyword').'%') -> whereOr('short', 'like', session('keyword').'%') -> order('id', 'ASC') -> paginate(10);
            $this -> assign('phone', $arr);
            return view('default/search');
        }else {
            $this->success('非法操作',url('hr/index/index'), '', 1);
        }
    }

    public function test() {
        Db::table('jingo_user_test') -> insert(['num'=>1]);
    }
}