<?php
namespace wstmart\home\controller;
use wstmart\admin\validate\express;
use wstmart\home\model\GoodsAttr;
use wstmart\home\model\GoodsCsv;
use wstmart\home\model\Imports as M;
use wstmart\home\model\ShopsId;

/**
 * ============================================================================
 * WSTMart多用户商城
 * 版权所有 2016-2066 广州商淘信息科技有限公司，并保留所有权利。
 * 官网地址:http://www.wstmart.net
 * 交流社区:http://bbs.shangtaosoft.com
 * 联系QQ:153289970
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！未经本公司授权您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 默认控制器
 */
class Imports extends Base{
	/**
	 * 数据导入首页
	 */
	public function index(){
		return $this->fetch('shops/import');
	}

	public function importCSV() {
        if (isset($_POST['submit'])) { //导入CSV
            $filename = $_FILES['file']['tmp_name'];
            if (empty ($filename)) {
                echo '请选择要导入的CSV文件！';
                exit;
            }
            /*用于CSV文件的导入*/
            /*$content = preg_split('/\n/', unicodeToUtf8(file_get_contents($filename)));      //对内容进行中文转码,并按换行符分离每一行数据
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
            $goodsCsv = new GoodsAttr();
            $result = array();
            foreach($splitArr as $item){
                $result[] = $goodsCsv->allowField(true)->data($item)->isUpdate(false)->save();      //循环写入数据库
            }
            if ($result) {
                echo 'success';
            }else {
                echo 'error';
            }
            exit();*/


            /*用于好商城数据导入*/
            /*$content = preg_split('/[\,]|\n/', unicodeToUtf8(file_get_contents($filename)));      //对内容进行中文转码,并按制表符和换行符进行分离
            echo '<pre />';
            //print_r($content);
            $fieldNum = 46;             //数据表字段数
            $fieldArr = array();        //数据表字段数组
            for ($i = 0;$i < $fieldNum; $i++) {
                $fieldArr[$i] = $content[$i];       //组合数据表字段
            }

            $lineArr[] = array();
            $n = 0;
            $i = $fieldNum;
            $j = 0;
            while ($i < count($content) - 1) {
                if ($fieldArr[$j] == 'gallery') {       //当字段为gallery时执行转换函数
                    $lineArr[$n][$fieldArr[$j]] = convert($content[$i]);
                }elseif($fieldArr[$j] == 'goodsDesc') {     //当字段为goodsDesc时替换""为"并去除两端的"
                    $lineArr[$n][$fieldArr[$j]] = str_replace('""', '"', trim($content[$i], '"'));
                }else {     //当字段为其他时去除两端的"
                    $lineArr[$n][$fieldArr[$j]] = trim($content[$i], '"');
                }
                $i++;
                $j++;
                if ($j >= $fieldNum) {
                    $j = 0;
                    $n++;
                }
            }
            print_r($lineArr);
            exit();*/


            /*$line = preg_split('/\n/', unicodeToUtf8(file_get_contents($filename)));
            $field = preg_split('/\t/', $line[1]);
            $fieldNum = count($field);
            $content = preg_split('/\t|[\n]/', unicodeToUtf8(file_get_contents($filename)));      //对内容进行中文转码,并按制表符和换行符进行分离
            $fieldArr = array();        //数据表字段数组

            for ($i = $fieldNum;$i < ($fieldNum * 2); $i++) {
                $fieldArr[$i] = $content[$i];       //组合数据表字段
            }
            $content = preg_split('/\t|[\n]/', unicodeToUtf8(file_get_contents($filename)));      //对内容进行中文转码,并按制表符和换行符进行分离*/


            if (substr($_FILES['file']['name'], -3) == 'csv') {
                $line = preg_split('/\n/', unicodeToUtf8(file_get_contents($filename)));
                $line = array_slice($line, 1, -1);
                $field = preg_split('/\t/', $line[0]);
                $fieldNum = count($field);
                $line = implode("\t", $line);
                $content = preg_split('/[\t]/', $line);
                $fieldArr = array();        //数据表字段数组
            }elseif (substr($_FILES['file']['name'], -3) == 'xml') {
                $simple = simplexml_load_file($filename);
                $arr = $this -> std_class_object_to_array($simple -> content);
                $fieldNum = count($arr['r-0']);
                $fieldArr = $arr['r-0'];
                $content = [];
                foreach ($arr as $v) {
                    foreach ($v as $v1) {
                        $content[] = is_array($v1) ? '' : $v1;
                    }
                }
            }else {
                $this -> error('上传文件类型只能为CSV或XML');
            }


            /*echo '<pre />';
            print_r($content);
            exit();*/
            for ($i = 0;$i < $fieldNum; $i++) {
                $fieldArr[$i] = $content[$i];       //组合数据表字段
            }

            /*从4行开始,将每行分离出来组成二维数组*/
            $lineArr[] = array();
            $n = 0;
            $i = $fieldNum * 2;
            $j = 0;

            /*将每行数据组成一个二维数组*/
            $shops = new ShopsId();
            while ($i < count($content) - 1) {
                $no = substr(time(), -5).mt_rand(10000,99999);
                $currentTime = date('Y-m-d H:i:s');
                //组合数据库goods表需要的数组
                switch ($fieldArr[$j]) {
                    case 'picture':
                        if (preg_match('/\,/', convert($content[$i]))) {        //判断是否有多张图片
                            $splitArr = explode(',', convert($content[$i]));
                            $lineArr[$n]['goodsImg'] = $splitArr[0];
                            $lineArr[$n]['gallery'] = '';
                            for ($k = 1; $k < count($splitArr); $k++) {
                                $lineArr[$n]['gallery'] .= $splitArr[$k].',';
                            }
                            $lineArr[$n]['gallery'] = trim($lineArr[$n]['gallery'], ',');
                        }else {
                            $lineArr[$n]['goodsImg'] = convert($content[$i]);
                            $lineArr[$n]['gallery'] = '';
                        }
                        break;
                    case 'description':
                        //$lineArr[$n]['goodsDesc'] = htmlspecialchars(trim($content[$i], '"'));
                        $content[$i] = str_replace('""', '"', trim($content[$i], '"'));
                        $lineArr[$n]['goodsDesc'] = str_replace('alt="', 'alt=""', $content[$i]);
                        //$lineArr[$n]['goodsDesc'] = htmlspecialchars($content[$i]);
                        break;
                    case 'title':
                        $lineArr[$n]['goodsName'] = trim($content[$i], '"');
                        break;
                    case 'price':
                        $lineArr[$n]['costPrice'] = trim($content[$i], '"');
                        break;
                    case 'marketPrice':
                        $lineArr[$n]['marketPrice'] = trim($content[$i], '"');
                        break;
                    case 'shopPrice':
                        $lineArr[$n]['shopPrice'] = trim($content[$i], '"');
                        break;
                    case 'num':
                        $lineArr[$n]['goodsStock'] = trim($content[$i], '"');
                        $lineArr[$n]['warnStock'] = floor($content[$i] * 0.05);
                        break;
                    case 'inputValues':
                        $lineArr[$n]['instoreNo'] = trim($content[$i], '"');
                        break;
                    case 'goodsUnit':
                        $lineArr[$n]['goodsUnit'] = trim($content[$i], '"');
                        break;
                    case 'list_time':
                        $lineArr[$n]['saleTime'] = $currentTime;
                        $lineArr[$n]['createTime'] = $currentTime;
                        $lineArr[$n]['productNo'] = $no;
                        $lineArr[$n]['goodsSn'] = $no;
                        break;
                    case 'location_state':
                        $lineArr[$n]['locationState'] = trim($content[$i], '"');
                        break;
                    case 'location_city':
                        $lineArr[$n]['locationCity'] = trim($content[$i], '"');
                        break;
                    case 'valid_thru':
                        $lineArr[$n]['validTime'] = trim($content[$i], '"');
                        break;
                    case 'adminNo':
                        $lineArr[$n]['adminNo'] = trim($content[$i], '"');
                        break;
                    case 'skuProps':
                        $lineArr[$n]['skuProps'] = trim($content[$i], '"');
                        break;
                    case 'goodsCatIdPath':
                        $lineArr[$n]['goodsCatIdPath'] = trim($content[$i], '"');
                        break;
                    case 'goodsCatId':
                        $lineArr[$n]['goodsCatId'] = trim($content[$i], '"');
                        break;
                    case 'isSpec':
                        $lineArr[$n]['isSpec'] = trim($content[$i], '"');
                        break;
                    case 'supplier':
                        $lineArr[$n]['supplier'] = trim($content[$i], '"');
                        break;
                    case 'shopId':
                        $res = $shops -> where('shopName', trim($content[$i], '"')) -> find();
                        $lineArr[$n]['shopId'] = $res['shopId'];
                        break;
                    case 'shopCatId1':
                        $lineArr[$n]['shopCatId1'] = trim($content[$i], '"');
                        break;
                    case 'shopCatId2':
                        $lineArr[$n]['shopCatId2'] = trim($content[$i], '"');
                        break;
                }
                $i++;
                $j++;
                if ($j >= $fieldNum) {
                    $j = 0;
                    $n++;
                }
            }
            /*echo '<pre />';
            print_r($lineArr);
            exit();*/
            if (count($lineArr) == 0) {
                echo '没有任何数据！';
                exit;
            }

            $goodsCsv = new GoodsCsv();
            $result = array();
            foreach($lineArr as $data){
                $result[] = $goodsCsv->allowField(true)->data($data)->isUpdate(false)->save();      //循环写入数据库
            }
            if ($result) {
                $this -> success('上传成功!', 'Imports/index', '', 1);
            }else {
                echo '导入失败';
            }
        }
    }

    public function show() {
        $goodsCsv = new GoodsCsv();
        $result = $goodsCsv::column('picture');
        $arr = array();
        foreach ($result as $v) {
            $arr[] = convert($v);
        }
        $this -> assign('url', $arr);
        return $this -> fetch('shops/output');
    }

    /**
     * 上传商品数据
     */
    public function importGoods(){
    	$rs = WSTUploadFile();
		if(json_decode($rs)->status==1){
			$m = new M();
    	    $rss = $m->importGoods($rs);
    	    return $rss;
		}
    	return $rs;
    }

    public function std_class_object_to_array($stdclassobject)
    {
        $_array = is_object($stdclassobject) ? get_object_vars($stdclassobject) : $stdclassobject;
        $array = [];
        foreach ($_array as $key => $value) {
            $value = (is_array($value) || is_object($value)) ? $this -> std_class_object_to_array($value) : $value;
            $array[$key] = $value;
        }

        return $array;
    }

    public function test() {

    }

}
