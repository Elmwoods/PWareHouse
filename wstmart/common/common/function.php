<?php
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
 */

use think\Db;


const WST_ADDON_PATH = './addons/';

/**
 * 生成验证码
 */
function WSTVerify($conf=['useCurve'  =>  false,'useNoise'  =>  false]){//添加 $conf=['useCurve'  =>  false,'useNoise'  =>  false]
    $Verify = new \verify\Verify($conf); //添加 $con
    $Verify->length   = 4;
    $Verify->entry();
}
/**
 * 核对验证码
 */
function WSTVerifyCheck($code){
	$verify = new \verify\Verify();
	return $verify->check($code);
}
/**
 * 生成数据返回值
 */
function WSTReturn($msg,$status = -1,$data = []){
	$rs = ['status'=>$status,'msg'=>$msg];
	if(!empty($data))$rs['data'] = $data;
	return $rs;
}

/**
 * 检测字符串不否包含
 * @param $srcword 被检测的字符串
 * @param $filterWords 禁用使用的字符串列表
 * @return boolean true-检测到,false-未检测到
 */
function WSTCheckFilterWords($srcword,$filterWords){
	$flag = true;
	if($filterWords!=""){
		$filterWords = str_replace("，",",",$filterWords);
		$words = explode(",",$filterWords);
		for($i=0;$i<count($words);$i++){
			if(strpos($srcword,$words[$i]) !== false){
				$flag = false;
				break;
			}
		}
	}
	return $flag;
}

/**
 * 获取指定的全局配置
 */
function WSTConf($key,$v = ''){
	if(is_null($v)){
		if(array_key_exists('WSTMARTCONF',$GLOBALS) && array_key_exists($key,$GLOBALS['WSTMARTCONF'])){
		    unset($GLOBALS['WSTMARTCONF'][$key]);
		}
	}else if($v === ''){
		if(array_key_exists('WSTMARTCONF',$GLOBALS)){
			$conf = $GLOBALS['WSTMARTCONF'];
			$ks = explode(".",$key);
			for($i=0,$k=count($ks);$i<$k;$i++){
				if(array_key_exists($ks[$i],$conf)){
					$conf = $conf[$ks[$i]];
				}else{
					return null;
				}
			}
			return $conf;
		}
	}else{
		return $GLOBALS['WSTMARTCONF'][$key] = $v;
	}
	return null;
}

//php获取中文字符拼音首字母
function WSTGetFirstCharter($str){
	if(empty($str)){
		return '';
	}
	$fchar=ord($str{0});
	if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0});
	$s1=iconv('UTF-8','gb2312',$str);
	$s2=iconv('gb2312','UTF-8',$s1);
	$s=$s2==$str?$s1:$str;
	if(empty($s{1})){
		return '';
	}
	$asc=ord($s{0})*256+ord($s{1})-65536;
	if($asc>=-20319 && $asc<=-20284) return 'A';
	if($asc>=-20283 && $asc<=-19776) return 'B';
	if($asc>=-19775 && $asc<=-19219) return 'C';
	if($asc>=-19218 && $asc<=-18711) return 'D';
	if($asc>=-18710 && $asc<=-18527) return 'E';
	if($asc>=-18526 && $asc<=-18240) return 'F';
	if($asc>=-18239 && $asc<=-17923) return 'G';
	if($asc>=-17922 && $asc<=-17418) return 'H';
	if($asc>=-17417 && $asc<=-16475) return 'J';
	if($asc>=-16474 && $asc<=-16213) return 'K';
	if($asc>=-16212 && $asc<=-15641) return 'L';
	if($asc>=-15640 && $asc<=-15166) return 'M';
	if($asc>=-15165 && $asc<=-14923) return 'N';
	if($asc>=-14922 && $asc<=-14915) return 'O';
	if($asc>=-14914 && $asc<=-14631) return 'P';
	if($asc>=-14630 && $asc<=-14150) return 'Q';
	if($asc>=-14149 && $asc<=-14091) return 'R';
	if($asc>=-14090 && $asc<=-13319) return 'S';
	if($asc>=-13318 && $asc<=-12839) return 'T';
	if($asc>=-12838 && $asc<=-12557) return 'W';
	if($asc>=-12556 && $asc<=-11848) return 'X';
	if($asc>=-11847 && $asc<=-11056) return 'Y';
	if($asc>=-11055 && $asc<=-10247) return 'Z';
	return null;
}

/**
 * 设置当前页面对象
 * @param int 0-用户  1-商家
 */
function WSTLoginTarget($target = 0){
	$WST_USER = session('WST_USER');
	$WST_USER['loginTarget'] = $target;
	session('WST_USER',$WST_USER);
}
/**
 * 邮件发送函数
 * @param string to      要发送的邮箱地址
 * @param string subject 邮件标题
 * @param string content 邮件内容
 * @return array
 */
function WSTSendMail($to, $subject, $content) {
	$mail = new \phpmailer\phpmailer();
    // 装配邮件服务器
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host = WSTConf("CONF.mailSmtp");
    $mail->SMTPAuth = WSTConf("CONF.mailAuth");
    $mail->Username = WSTConf("CONF.mailUserName");
    $mail->Password = WSTConf("CONF.mailPassword");
    $mail->CharSet = 'utf-8';
    // 装配邮件头信息
    $mail->From = WSTConf("CONF.mailAddress");
    $mail->AddAddress($to);
    $mail->FromName = WSTConf("CONF.mailSendTitle");
    $mail->IsHTML(true);
    // 装配邮件正文信息
    $mail->Subject = $subject;
    $mail->Body = $content;
    // 发送邮件
    $rs =array();
    if (!$mail->Send()) {
    	$rs['status'] = 0;
    	$rs['msg'] = $mail->ErrorInfo;
        return $rs;
    } else {
    	$rs['status'] = 1;
        return $rs;
    }
}

/**
 * 获取系统配置数据
 */
function WSTConfig(){
	$rs = cache('WST_CONF');
	if(!$rs){
		$rv = Db::name('sys_configs')->field('fieldCode,fieldValue')->select();
		$rs = [];
		foreach ($rv as $v){
			$rs[$v['fieldCode']] = $v['fieldValue'];
		}
		//获取风格
        $styles = Db::name('styles')->where(['isUse'=>1])->field('styleSys,stylePath,id')->select();
        if(!empty($styles)){
	        foreach ($styles as $key => $v) {
		        $rs['wst'.$v['styleSys'].'Style'] = $v['stylePath'];
		        $rs['wst'.$v['styleSys'].'StyleId'] = $v['id'];
	        }
        }
		//获取上传文件目录配置
		$data = Db::name('datas')->where('catId',3)->column('dataVal');
		foreach ($data as $key => $v){
			$data[$key] = str_replace('_','',$v);
		}
		$rs['wstUploads'] = $data;
		if($rs['mallLicense']=='')$rs['mallSlogan'] = $rs['mallSlogan']."  ".base64_decode('UG93ZXJlZCBCeSBXU1RNYXJ0');
		cache('WST_CONF',$rs,31536000);
	}
	return $rs;
} 

/**
 * 判断手机号格式是否正确
 */
function WSTIsPhone($phoneNo){
	$reg = "/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/";
	$rs = \think\Validate::regex($phoneNo,$reg);
	return $rs;
}

/**
 * 检测登录账号是否可用
 * @param $key 要检测的内容
 */
function WSTCheckLoginKey($val,$userId = 0){
    if($val=='')return WSTReturn("登录账号不能为空");
    if(!WSTCheckFilterWords($val,WSTConf("CONF.registerLimitWords"))){
    	return WSTReturn("登录账号包含非法字符");
    }
    $dbo = Db::name('users')->where(["loginName|userEmail|userPhone"=>['=',$val],'dataFlag'=>1]);
    if($userId>0){
    	$dbo->where("userId", "<>", $userId);
    }
    $rs = $dbo->count();
    if($rs==0){
    	return WSTReturn("该登录账号可用",1);
    }
    return WSTReturn("对不起，登录账号已存在");
}

/**
 * 生成随机数账号
 */
function WSTRandomLoginName($loginName){
	$chars = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
    //简单的派字母
    foreach ($chars as $key =>$c){
    	$crs = WSTCheckLoginKey($loginName."_".$c);
    	if($crs['status']==1)return $loginName."_".$c;
    }
    //随机派三位数值
    for($i=0;$i<1000;$i++){
    	$crs = $this->WSTCheckLoginKey($loginName."_".$i);
    	if($crs['status']==1)return $loginName."_".$i;
    }
    return '';
}

/**
 * 删除一维数组里的多个key
 */
function WSTUnset(&$data,$keys){
    if($keys!='' && is_array($data)){
        $key = explode(',',$keys);
        foreach ($key as $v)unset($data[$v]);
    }
}
/**
 * 只允许一维数组里的某些key通过
 */
function WSTAllow(&$data,$keys){
    if($keys!='' && is_array($data)){
        $key = explode(',',$keys);
        foreach ($data as $vkeys =>$v)if(!in_array($vkeys,$key))unset($data[$vkeys]);
    }
}

/**
 * 字符串替换
 * @param string $str     要替换的字符串
 * @param string $repStr  即将被替换的字符串
 * @param int $start      要替换的起始位置,从0开始
 * @param string $splilt  遇到这个指定的字符串就停止替换
 */
function WSTStrReplace($str,$repStr,$start,$splilt = ''){
	$newStr = substr(utf8_encode($str),0,$start);
	$breakNum = -1;
	for ($i=$start;$i<strlen($str);$i++){
		$char = substr($str,$i,1);
		if($char==$splilt){
			$breakNum = $i;
			break;
		}
		$newStr.=$repStr;
	}
	if($splilt!='' && $breakNum>-1){
		for ($i=$breakNum;$i<strlen($str);$i++){
			$char = substr($str,$i,1);
			$newStr.=$char;
		}
	}
	return $newStr;
}

/**
 * 获取指定商品分类的子分类列表
 */
function WSTGoodsCats($parentId = 0,$isFloor = -1){
	$dbo = Db::name('goods_cats')->where(['dataFlag'=>1, 'isShow' => 1,'parentId'=>$parentId]);
	if($isFloor!=-1)$dbo->where('isFloor',$isFloor);
	return $dbo->field("catName,catId")->order('catSort asc')->select();
}


/**
 * 上传图片
 * 需要生成缩略图： isThumb=1
 * 需要加水印：isWatermark=1
 * pc版缩略图： width height
 * 手机版原图：mWidth mHeight
 * 缩略图：mTWidth mTHeight
 * 判断图片来源：fromType 0：商家/用户   1：平台管理员
 */
function WSTUploadPic($fromType=0){
	$fileKey = key($_FILES);
	$dir = Input('param.dir');
	if($dir=='')return json_encode(['msg'=>'没有指定文件目录！','status'=>-1]);
	$dirs = WSTConf("CONF.wstUploads");
   	if(!in_array($dir, $dirs)){
   		return json_encode(['msg'=>'非法文件目录！','status'=>-1]);
   	}
   	// 上传文件
    $file = request()->file($fileKey);
    if($file===null){
    	return json_encode(['msg'=>'上传文件不存在或超过服务器限制','status'=>-1]);
    }
    $validate = new \think\Validate([
	    ['fileMime','fileMime:image/png,image/gif,image/jpeg,image/x-ms-bmp','只允许上传jpg,gif,png,bmp类型的文件'],
	    ['fileExt','fileExt:jpg,jpeg,gif,png,bmp','只允许上传后缀为jpg,gif,png,bmp的文件'],
	    ['fileSize','fileSize:2097152','文件大小超出限制'],//最大2M
	]);
	$data = ['fileMime'  => $file,
	    	 'fileSize' => $file,
	         'fileExt'=> $file
	        ];
	if (!$validate->check($data)) {
	    return json_encode(['msg'=>$validate->getError(),'status'=>-1]);
	}
    $info = $file->rule('uniqid')->move(ROOT_PATH.'/upload/'.$dir."/".date('Y-m'));
    if($info){
    	$filePath = $info->getPathname();
    	$filePath = str_replace(ROOT_PATH,'',$filePath);
    	$filePath = str_replace('\\','/',$filePath);
    	$name = $info->getFilename();
    	$filePath = str_replace($name,'',$filePath);
    	//原图路径
    	$imageSrc = trim($filePath.$name,'/');
    	//图片记录
    	WSTRecordImages($imageSrc, (int)$fromType);
    	//打开原图
    	$image = \image\Image::open($imageSrc);
    	//缩略图路径 手机版原图路径 手机版缩略图路径
    	$thumbSrc = $mSrc = $mThumb = null;
    	//手机版原图宽高
    	$mWidth = min($image->width(),(int)input('mWidth',700));
		$mHeight = min($image->height(),(int)input('mHeight',700));
		//手机版缩略图宽高
		$mTWidth = min($image->width(),(int)input('mTWidth',250));
		$mTHeight = min($image->height(),(int)input('mTHeight',250));

    	/****************************** 生成缩略图 *********************************/
    	$isThumb = (int)input('isThumb');
    	if($isThumb==1){
    		//缩略图路径
    		$thumbSrc = str_replace('.', '_thumb.', $imageSrc);
    		$image->thumb((int)input('width',min(300,$image->width())), (int)input('height',min(300,$image->height())),2)->save($thumbSrc,$image->type(),90);
    		//是否需要生成移动版的缩略图
    		$suffix = WSTConf("CONF.wstMobileImgSuffix");
    		if(!empty($suffix)){
    			$image = \image\Image::open($imageSrc);
    			$mSrc = str_replace('.',"$suffix.",$imageSrc);
    			$mThumb = str_replace('.', '_thumb.',$mSrc);
    			$image->thumb($mWidth, $mHeight)->save($mSrc,$image->type(),90);
    			$image->thumb($mTWidth, $mTHeight, 2)->save($mThumb,$image->type(),90);
    		}


    	}
    	/***************************** 添加水印 ***********************************/
    	$isWatermark=(int)input('isWatermark');
    	if($isWatermark==1 && (int)WSTConf('CONF.watermarkPosition')!==0){
	    	//取出水印配置
	    	$wmWord = WSTConf('CONF.watermarkWord');//文字
	    	$wmFile = trim(WSTConf('CONF.watermarkFile'),'/');//水印文件
	    	$wmPosition = (int)WSTConf('CONF.watermarkPosition');//水印位置
	    	$wmSize = ((int)WSTConf('CONF.watermarkSize')!=0)?WSTConf('CONF.watermarkSize'):'20';//大小
	    	$wmColor = (WSTConf('CONF.watermarkColor')!='')?WSTConf('CONF.watermarkColor'):'#000000';//颜色必须是16进制的
	    	$wmOpacity = ((int)WSTConf('CONF.watermarkOpacity')!=0)?WSTConf('CONF.watermarkOpacity'):'100';//水印透明度
	    	//是否有自定义字体文件
	    	$customTtf = $_SERVER['DOCUMENT_ROOT'].WSTConf('CONF.watermarkTtf');
	    	$ttf = is_file($customTtf)?$customTtf:EXTEND_PATH.'/verify/verify/ttfs/3.ttf';
	        $image = \image\Image::open($imageSrc);
	    	if(!empty($wmWord)){//当设置了文字水印 就一定会执行文字水印,不管是否设置了文件水印
		    	
	    		//执行文字水印
	    		$image->text($wmWord, $ttf, $wmSize, $wmColor, $wmPosition)->save($imageSrc);
	    		if($thumbSrc!==null){
	    			$image->thumb((int)input('width',min(300,$image->width())), (int)input('height',min(300,$image->height())),2)->save($thumbSrc,$image->type(),90);
	    		}
	    		//如果有生成手机版原图
	    		if(!empty($mSrc)){
	    			$image = \image\Image::open($imageSrc);
	    			$image->thumb($mWidth, $mHeight)->save($mSrc,$image->type(),90);
	    			$image->thumb($mTWidth, $mTHeight, 2)->save($mThumb,$image->type(),90);
	    		}
	    	}elseif(!empty($wmFile)){//设置了文件水印,并且没有设置文字水印
	    		//执行图片水印
	    		$image->water($wmFile, $wmPosition, $wmOpacity)->save($imageSrc);
	    		if($thumbSrc!==null){
	    			$image->thumb((int)input('width',min(300,$image->width())), (int)input('height',min(300,$image->height())),2)->save($thumbSrc,$image->type(),90);
	    		}
	    		//如果有生成手机版原图
	    		if($mSrc!==null){
	    			$image = \image\Image::open($imageSrc);
	    			$image->thumb($mWidth, $mHeight)->save($mSrc,$image->type(),90);
	    			$image->thumb($mTWidth, $mTHeight,2)->save($mThumb,$image->type(),90);
	    		}
	    	}
    	}
    	//判断是否有生成缩略图
    	$thumbSrc = ($thumbSrc==null)?$info->getFilename():str_replace('.','_thumb.', $info->getFilename());
		$filePath = ltrim($filePath,'/');
		// 用户头像上传宽高限制
		$isCut = (int)input('isCut');
		if($isCut){
			$imgSrc = $filePath.$info->getFilename();
			$image = \image\Image::open($imgSrc);
			$size = $image->size();//原图宽高
			$w = $size[0];
			$h = $size[1];
			$rate = $w/$h;
			if($w>$h && $w>500){
				$newH = 500/$rate;
				$image->thumb(500, $newH)->save($imgSrc,$image->type(),90);
			}elseif($h>$w && $h>500){
				$newW = 500*$rate;
				$image->thumb($newW, 500)->save($imgSrc,$image->type(),90);
			}
		}
        return json_encode(['status'=>1,'savePath'=>$filePath,'name'=>$info->getFilename(),'thumb'=>$thumbSrc]);
    }else{
        //上传失败获取错误信息
        return $file->getError();
    }    
}
/**
 * 上传文件
 */
function WSTUploadFile(){
	$fileKey = key($_FILES);
	$dir = Input('post.dir');
	if($dir=='')return json_encode(['msg'=>'没有指定文件目录！','status'=>-1]);
	$dirs = WSTConf("CONF.wstUploads");
   	if(!in_array($dir, $dirs)){
   		return json_encode(['msg'=>'非法文件目录！','status'=>-1]);
   	}
   	//上传文件
    $file = request()->file($fileKey);
    if($file===null){
    	return json_encode(['msg'=>'上传文件不存在或超过服务器限制','status'=>-1]);
    }
    $validate = new \think\Validate([
	    ['fileExt','fileExt:xls,xlsx,xlsm,csv','只允许上传后缀为xls,xlsx,xlsm,csv的文件']
	]);
	$data = ['fileExt'=> $file];
	if (!$validate->check($data)) {
	    return json_encode(['msg'=>$validate->getError(),'status'=>-1]);
	}
    $info = $file->rule('uniqid')->move(ROOT_PATH.'/upload/'.$dir."/".date('Y-m'));
    //保存路径
    $filePath = $info->getPathname();
	$filePath = str_replace(ROOT_PATH,'',$filePath);
	$filePath = str_replace('\\','/',$filePath);
	$name = $info->getFilename();
	$filePath = str_replace($name,'',$filePath);
	if($info){
		return json_encode(['status'=>1,'name'=>$info->getFilename(),'route'=>$filePath]);
	}else{
		//上传失败获取错误信息
		return $file->getError();
	}
}
/**
 * 生成默认商品编号/货号
 */
function WSTGoodsNo($pref = ''){
	return $pref.(round(microtime(true),4)*10000).mt_rand(0,9);
}
/**
 * 获取订单统一流水号
 */
function WSTOrderQnique(){
	return (round(microtime(true),4)*10000).mt_rand(1000,9999);
}


/**
* 图片管理
* @param $imgPath    图片路径
* @param $fromType   0：用户/商家 1：平台管理员
* 
*/
function WSTRecordImages($imgPath, $fromType){
	$data = [];
	$data['imgPath'] = $imgPath;
	if(file_exists($imgPath)){
		$data['imgSize'] = filesize($imgPath); //返回字节数 imgsize/1024 k  	imgsize/1024/1024 m
	}
	//获取表名
	$table = explode('/',$imgPath);
	$data['fromTable'] = $table[1];
	$data['fromType'] = (int)$fromType; 
	//根据类型判断所有者
	$data['ownId'] = ((int)$fromType==0)?(int)session('WST_USER.userId'):(int)session('WST_STAFF.staffId');
	$data['isUse'] = 0; //默认不使用
	$data['createTime'] = date('Y-m-d H:i:s');

	//保存记录
	Db::name('images')->insert($data);

}
/**
* 启用图片
* @param $fromType 0：  用户/商家 1：平台管理员
* @param $dataId        来源记录id
* @param $imgPath       图片路径,要处理多张图片时请传入一位数组,或用","连接图片路径
* @param $fromTable     该记录来自哪张表
* @param $imgFieldName  表中的图片字段名称
*/
function WSTUseImages($fromType, $dataId, $imgPath, $fromTable='', $imgFieldName=''){
	if(empty($imgPath))return;

	$image['fromType'] = (int)$fromType;
	//根据类型判断所有者
	$image['ownId'] = ((int)$fromType==0)?(int)session('WST_USER.userId'):(int)session('WST_STAFF.staffId');
	$image['dataId'] = (int)$dataId;

	$image['isUse'] = 1;//标记为启用
	if($fromTable!=''){
		$tmp = ['',''];
		if(strpos($fromTable,'-')!==false){
			$tmp = explode('-',$fromTable);
			$fromTable = str_replace('-'.$tmp[1],'',$fromTable);
		}
		$image['fromTable'] = str_replace('_','',$fromTable.$tmp[1]);
	}

	$imgPath = is_array($imgPath)?$imgPath:explode(',',$imgPath);//转数组


	//用于与旧图比较
	$newImage = $imgPath;

	// 不为空说明执行修改
	if($imgFieldName!=''){
		//要操作的表名  $fromTable;
		// 获取`$fromTable`表的主键
		$prefix = config('database.prefix');
		$tableName = $prefix.$fromTable;
		$pk = Db::getTableInfo("$tableName", 'pk');
		// 取出旧图
		$oldImgPath = model("$fromTable")->where("$pk",$dataId)->value("$imgFieldName"); 
		// 转数组
		$oldImgPath = explode(',', $oldImgPath);

		// 1.要设置为启用的文件
		$newImage = array_diff($imgPath, $oldImgPath);
		// 2.要标记为删除的文件
		$oldImgPath = array_diff($oldImgPath, $imgPath);
		//旧图数组跟新图数组相同则不需要继续执行
		if($newImage!=$oldImgPath)WSTUnuseImage($oldImgPath);
	}
	if(!empty($newImage)){
		Db::name('images')->where(['imgPath'=>['in',$newImage]])->update($image);
	}
}

/**
* 编辑器图片记录
* @param $fromType 0：  用户/商家 1：平台管理员
* @param $dataId        来源记录id
* @param $oldDesc       旧商品描述
* @param $newDesc       新商品描述
* @param $fromTable     该记录来自哪张表
*/
function WSTEditorImageRocord($fromTable, $dataId, $oldDesc, $newDesc){
		//编辑器里的图片
		$rule = '/src="\/(upload.*?)"/';
	    // 获取旧的src数组
	    preg_match_all($rule,$oldDesc,$images);
	    $oldImgPath = $images[1];

	    preg_match_all($rule,$newDesc,$images);  
	    // 获取新的src数组
	    $imgPath = $images[1];
		// 1.要设置为启用的文件
		$newImage = array_diff($imgPath, $oldImgPath);
		// 2.要标记为删除的文件
		$oldImgPath = array_diff($oldImgPath, $imgPath);
		//旧图数组跟新图数组相同则不需要继续执行
		if($newImage!=$oldImgPath){
			//标记新图启用
			WSTUseImages($fromTable, $dataId, $newImage);
			//标记旧图删除
			WSTUnuseImage($oldImgPath);
		}
}

/**
* 标记删除图片
*/
function WSTUnuseImage($fromTable, $field = '' , $dataId = 0){
	if($fromTable=='')return;
	$imgPath = $fromTable;
	if($field!=''){
		$prefix = config('database.prefix');
		$tableName = $prefix.$fromTable;
		$pk = Db::getTableInfo("$tableName", 'pk');
		// 取出旧图
		$imgPath = model("$fromTable")->where("$pk",$dataId)->value("$field");
	}
	if(!empty($imgPath)){
		$imgPath = is_array($imgPath)?$imgPath:explode(',',$imgPath);//转数组
		Db::name('images')->where(['imgPath'=>['in',$imgPath]])->setField('isUse',0);
	}
}
/**
 * 获取系统根目录
 */
function WSTRootPath(){
	return dirname(dirname(dirname(dirname(__File__))));
}
/**
 * 切换图片
 * @param $imgurl 图片路径
 * @param $imgType 图片类型    0:PC版大图   1:PC版缩略图       2:移动版大图    3:移动版缩略图
 * 图片规则  
 * PC版版大图 :201635459344.jpg
 * PC版版缩略图 :201635459344_thumb.jpg
 * 移动版大图 :201635459344_m.jpg
 * 移动版缩略图 :201635459344_m_thumb.jpg
 */
function WSTImg($imgurl,$imgType = 1){
	$m = WSTConf('CONF.wstMobileImgSuffix');
	$imgurl = str_replace($m.'.','.',$imgurl);
	$imgurl = str_replace($m.'_thumb.','.',$imgurl);
	$imgurl = str_replace('_thumb.','.',$imgurl);
	$img = '';
	switch ($imgType){
		case 0:$img =  $imgurl;break;
		case 1:$img =  str_replace('.','_thumb.',$imgurl);break;
		case 2:$img =  str_replace('.',$m.'.',$imgurl);break;
		case 3:$img =  str_replace('.',$m.'_thumb.',$imgurl);break;
	}
	return ((file_exists(WSTRootPath()."/".$img))?$img:$imgurl);
}

/**
 * 根据送货城市获取运费
 * @param $cityId 送货城市Id
 * @param @shopIds 店铺ID
 */
function WSTOrderFreight($shopId,$cityId){
	$goodsFreight = ['total'=>0,'shops'=>[]];
	$rs = Db::name('shops')->alias('s')->join('__SHOP_FREIGHTS__ sf','s.shopId=sf.shopId and sf.areaId2='.$cityId,'left')
	     ->where(['s.shopId'=>$shopId])->field('s.freight,sf.freightId,sf.freight freight2')->find();
	if(empty($rs))return 0;
	if((int)$rs['freight']<0)$rs['freight'] = 0;
    if((int)$rs['freight2']<0)$rs['freight2'] = 0;
    return (int)((int)$rs['freightId']>0)?$rs['freight2']:$rs['freight'];
}
/**
 * 生成订单号
 */
function WSTOrderNo(){
    $orderId = Db::name('orderids')->insertGetId(['rnd'=>time()]);
	return $orderId.(fmod($orderId,7));
}
/**
 * 高精度数字相加
 * @param $num
 * @param number $i 保留小数位
 */
function WSTBCMoney($num1,$num2,$i=2){
	$num = bcadd($num1, $num2, $i);
	return (float)$num;
}
/**
 * 获取支付方式
 */
function WSTLangPayType($v){
	switch($v){
		case 0:return '货到付款';
		case 1:return '在线支付';
	}
}
/**
 * 收货方式
 */
function WSTLangDeliverType($v){
	switch ($v) {
		case 1:return "自提";
		case 0:return "送货上门";
	}
}
/**
 * 订单状态
 */
function WSTLangOrderStatus($v){
	switch($v){
		case -3:return '用户拒收';
		case -2:return '待支付';
		case -1:return '已取消';
		case 0:return '待发货';
		case 1:return '待收货';
		case 2:return '已收货';
	}
}
/**
 * 积分来源
 */
function WSTLangScore($v){
    switch($v){
		case 1:return '商品订单';
		case 2:return '评价订单';
		case 4:return '退款订单';
		case 10001:return '管理员';
		default:return '其他';
	}
}
/**
 * 资金来源
 */
function WSTLangMoneySrc($v){
    switch($v){
		case 1:return '商品订单';
		case 2:return '订单结算';
		case 3:return '提现申请';
		case 4:return '钱包充值';
		default:return '其他';
	}
}
/**
 * 投诉状态
 */
function WSTLangComplainStatus($v){
    switch($v){
		case 0:return '等待处理';
		case 1:return '等待应诉人应诉';
		case 2:return '应诉人已应诉';
		case 3:return '等待仲裁';
		case 4:return '已仲裁';
	}
}
/**
 * 支付来源
 */
function WSTLangPayFrom($pkey){
    $paySrc = cache('WST_PAY_SRC');
    if(!$paySrc){
        $paySrc = Db::name('payments')->where(['enabled'=>1])->order('payOrder asc')->select();
        cache('WST_PAY_SRC',$paySrc,31622400);
    }
    foreach($paySrc as $v){
       if($pkey==$v['payCode'])return $v['payName'];
    }
    return '';
}

/**
 * 插件状态
 */
function WSTLangAddonStatus($v){
	switch($v){
		case 0:return '未安装';
		case 1:return '启用';
		case 2:return '禁用';
	}
}

/**
 * 获取业务数据内容
 */
function WSTDatas($catId,$id = 0){
	$data = cache('WST_DATAS');
	if(!$data){
		$rs = Db::name('datas')->order('catId asc,dataSort asc,id asc')->select();
		$data = [];
		foreach ($rs as $key =>$v){
			$data[$v['catId']][$v['dataVal']] = $v;
		}
		cache('WST_DATAS',$data,378432000);
	}
	
	if(isset($data[$catId])){
		if($id==0)return $data[$catId];
		return isset($data[$catId][$id])?$data[$catId][$id]:'';
	}
	return [];
}
/**
 * 获取消息模板
 */
function WSTMsgTemplates($tplCode){
    $data = cache('WST_MSG_TEMPLATES');
	if(!$data){
		$rs = Db::name('template_msgs')->order('id asc')->select();
		$data = [];
		foreach ($rs as $key =>$v){
			if($v['tplType']==3 && (int)WSTConf('CONF.wxenabled')==1){
				$ps = Db::name('wx_template_params')->where('parentId',$v['id'])->select();
				$v['params'] = $ps;
			}
			if($v['tplContent']==''){
                $data[$v['tplCode']] = $v;
			}else{
				$v['content'] = htmlspecialchars_decode($v['tplContent']);
				$v['tplContent'] = strip_tags(htmlspecialchars_decode($v['tplContent']));
				$data[$v['tplCode']] = $v;
			}
		}
		cache('WST_MSG_TEMPLATES',$data,378432000);
	}
	return (isset($data[$tplCode]))?$data[$tplCode]:null;
}
/**
 * 发送微信消息
 */
function WSTWxMessage($params){
    $tpl = WSTMsgTemplates($params['CODE']);
	if($tpl && file_exists('wstmart'.DS.'wechat'.DS.'behavior'.DS.'InitWechatMessges.php')){
		\think\Hook::exec('wstmart\\wechat\\behavior\\InitWechatMessges','run',$params);
	}
}
/**
 * 截取字符串
 */
function WSTMSubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = false){
	$newStr = '';
	if (function_exists ( "mb_substr" )) {
		if ($suffix){
			$newStr = mb_substr ( $str, $start, $length, $charset )."...";
		}else{
			$newStr = mb_substr ( $str, $start, $length, $charset );
		}
	} elseif (function_exists ( 'iconv_substr' )) {
		if ($suffix){
			$newStr = iconv_substr ( $str, $start, $length, $charset )."...";
		}else{
			$newStr = iconv_substr ( $str, $start, $length, $charset );
		}
	}
	if($newStr==''){
	$re ['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re ['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re ['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re ['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all ( $re [$charset], $str, $match );
	$slice = join ( "", array_slice ( $match [0], $start, $length ) );
	if ($suffix)
		$newStr = $slice;
	}
	return $newStr;
}
function WSTScore($score,$users,$type = 5,$len = 0,$total = 1){
	if((int)$score==0)return $type;
	switch($type){
		case 5:return round($score/$total/$users,0);
		case 10:return round($score/$total*2/$users,$len);
		case 100:return round($score/$total*2/$users,$len);
	}
}
function WSTShopEncrypt($shopId){
	return md5(base64_encode("wstmart".date("Y-m-d").$shopId));
}
/**
 * 根据子分类循环获取其父级分类
 */
function WSTGoodsCatPath($catId, $data = []){
    if($catId==0)return $data;
    $data[] = $catId;
	$parentId = Db::name('goods_cats')->where('catId',$catId)->value('parentId');
	if($parentId==0){
		krsort($data);
		return $data;
	}else{
		return WSTGoodsCatPath($parentId, $data);
	}
}
/**
 * 提供原生分页处理
 */
function WSTPager($total,$rs,$page,$size = 0){
	$pageSize = ($size>0)?$size:config('paginate.list_rows');
	$totalPage = ($total%$pageSize==0)?($total/$pageSize):(intval($total/$pageSize)+1);
	return ['Total'=>$total,'PerPage'=>$pageSize,'CurrentPage'=>$page,'TotalPage'=>$totalPage,'Rows'=>$rs];
}


/**
* 编辑器上传图片
*/
function WSTEditUpload($fromType){
    //PHP上传失败
    if (!empty($_FILES['imgFile']['error'])) {
        switch($_FILES['imgFile']['error']){
            case '1':
                $error = '超过php.ini允许的大小。';
                break;
            case '2':
                $error = '超过表单允许的大小。';
                break;
            case '3':
                $error = '图片只有部分被上传。';
                break;
            case '4':
                $error = '请选择图片。';
                break;
            case '6':
                $error = '找不到临时目录。';
                break;
            case '7':
                $error = '写文件到硬盘出错。';
                break;
            case '8':
                $error = 'File upload stopped by extension。';
                break;
            case '999':
            default:
                $error = '未知错误。';
        }
        return WSTReturn(1,$error);
    }

    $fileKey = key($_FILES);
	$dir = 'image'; // 编辑器上传图片目录
	$dirs = WSTConf("CONF.wstUploads");
   	if(!in_array($dir, $dirs)){
   		return json_encode(['error'=>1,'message'=>'非法文件目录！']);
   	}
   	// 上传文件
    $file = request()->file($fileKey);
    if($file===null){
    	return json_encode(["error"=>1,"message"=>'上传文件不存在或超过服务器限制']);
    }
    $validate = new \think\Validate([
	    ['fileMime','fileMime:image/png,image/gif,image/jpeg,image/x-ms-bmp','只允许上传jpg,gif,png,bmp类型的文件'],
	    ['fileExt','fileExt:jpg,jpeg,gif,png,bmp','只允许上传后缀为jpg,gif,png,bmp的文件'],
	    ['fileSize','fileSize:2097152','文件大小超出限制'],//最大2M
	]);
	$data = ['fileMime'  => $file,
	    	 'fileSize' => $file,
	         'fileExt'=> $file
	        ];
	if (!$validate->check($data)) {
	    return json_encode(['message'=>$validate->getError(),'error'=>1]);
	}
    $info = $file->rule('uniqid')->move(ROOT_PATH.'/upload/'.$dir."/".date('Y-m'));
    if($info){
    	$filePath = $info->getPathname();
    	$filePath = str_replace(ROOT_PATH,'',$filePath);
    	$filePath = str_replace('\\','/',$filePath);
    	$name = $info->getFilename();
    	$imageSrc = trim($filePath,'/');
    	//图片记录
    	WSTRecordImages($imageSrc, (int)$fromType);
    	return json_encode(array('error' => 0, 'url' => $filePath));
	}
}
/**
 * 转义单引号
 */
function WSTHtmlspecialchars($v){
	return htmlspecialchars($v,ENT_QUOTES);
}

/**
* 发送商城消息
* @param int 	$to 接受者d
* @param string $content 内容
* @param array  $msgJson 存放json数据
*/
function WSTSendMsg($to,$content,$msgJson=[],$msgType = 1){
	$message = [];
	$message['msgType'] = $msgType;
	$message['sendUserId'] = 1;
	$message['createTime'] = date('Y-m-d H:i:s');
	$message['msgStatus'] = 0;
	$message['dataFlag'] = 1;

	$message['receiveUserId'] = $to;
	$message['msgContent'] = $content;
	$message['msgJson'] = json_encode($msgJson);
	Db::name('messages')->insert($message);

}

/**
 * 获取分类的佣金
 */
function WSTGoodsCommissionRate($goodsCatId){
	$cats = Db::name('goods_cats')->where('catId',$goodsCatId)->field('parentId,commissionRate')->find();
	if(empty($cats)){
		return 0;
	}else{
		if((float)$cats['commissionRate']>=0)return (float)$cats['commissionRate'];
		return WSTGoodsCommissionRate($cats['parentId']);
	}
}

function WSTFormatIn($split,$str){
	$strdatas = explode($split,$str);
	$data = array();
	for($i=0;$i<count($strdatas);$i++){
		$data[] = (int)$strdatas[$i];
	}
	$data = array_unique($data);
	return implode($split,$data);
}

function WSTRandStr($len = 6){
    $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $tmp = '';
    for($i=0;$i<$len;$i++){
        $tmp.=$str[rand(0,35)];
    }
    return $tmp;
}
/**
 * 金额兑积分
 */
function WSTMoneyGiftScore($money){
    $moneyToScore = (float)WSTConf('CONF.moneyToScore');
    return intval($money*$moneyToScore);
}
/**
 * 积分兑金额
 * $isBack=true则$score实际上传入金额，通过金额反推需要兑换的积分
 */
function WSTScoreToMoney($score,$isBack = false){
    $scoreToMoney = (int)WSTConf('CONF.scoreToMoney');
    if($scoreToMoney<=0)return 0;
    if($isBack){
        return intval(strval($score*$scoreToMoney));
    }else{
    	return round($score/$scoreToMoney,2);
    }
}
/**
 * 头像处理
 */
function WSTUserPhoto($userPhoto=''){
	if(substr($userPhoto,0,4)!='http' && $userPhoto){
		$userPhoto  = '__ROOT__/'.$userPhoto;
	}else if(!$userPhoto){
		$userPhoto  = '__ROOT__/'.WSTConf('CONF.userLogo');
	}
	return $userPhoto;
}

function WSTClearHookCache(){
	WSTConf('listenUrl',null);
	$STAFF = session('WST_STAFF');
	if(!empty($STAFF)){
	    //获取角色权限
	 	$STAFF['privileges'] = Db::name('privileges')->where(['dataFlag'=>1])->column('privilegeCode');
	 	$STAFF['menuIds'] = Db::name('menus')->where('dataFlag',1)->column('menuId');
	 	session('WST_STAFF',$STAFF);
	}
	WSTConf('protectedUrl',null);
	cache('WST_HOME_MENUS',null);
	cache('WST_PRO_MENUS',null);
	cache('WST_MOBILE_BTN',null);
	cache('hooks',null);
}
/**
 * 获取移动端首页按钮
 */
function WSTMobileBtns($src){
    $data = cache('WST_MOBILE_BTN');
    if(!$data){
        $rs = Db::name('mobile_btns')->order('btnSort asc')->select();
        $data = [];
        foreach ($rs as $key => $v) {
        	$data[$v['btnSrc']][] = $v;
        }
        cache('WST_MOBILE_BTN',$data,31536000);
    }
    return $data[$src];
}

/**
 * 获取星期几
 */
function WSTgetWeek($date){
	//强制转换日期格式
	$date_str=date('Y-m-d',strtotime($date));
	$number_wk=date("w",strtotime($date));
	$weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
	return $weekArr[$number_wk];
}
/**
 * 获取路由规则
 */
function WSTRoute(){
	$data = cache('WST_ROUTES');
	if(!$data){
		$routes = \think\Route::rules('*');
		$data = [];
		foreach ($routes as $key => $v) {
			if($v['rule']=='addon/:route')continue;
			$data[$v['route']] = $v['rule'];
		}
		cache('WST_ROUTES',$data,31536000);
	}
	return json_encode($data);
}

/**
 * 获取项目根路径
 */
function WSTRoot(){
	$url  = request()->root(true);
	$data = explode("/index.php",$url);
	return $data[0];
}

/**
 * URL 64位加密处理
 * @param string $data 字符串内容
 * @param boolean $isEncode true:编码  false:解码
 */
function WSTBase64url($data,$isEncode = true) { 
  return ($isEncode)?rtrim(strtr(base64_encode($data), '+/', '-_'), '='):base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
}

//随机红包函数
function redPacket($total, $num) {
    $min        =   0.01;       //红包最小值
    $redPacket  =   [];         //初始化红包数组
    for ($i=1;$i<$num;$i++)
    {
        $safe_total     =       ($total-($num-$i)*$min)/($num-$i);          //随机数安全上限
        $money          =       mt_rand($min*100,$safe_total*100)/100;      //红包值
        $total          -=      $money;
        $redPacket[$i]  =       sprintf('%.2f', $money);
    }
    $redPacket[$num]    =       sprintf('%.2f', $total);
    return $redPacket;
}

//数据分页
function page($data, $page, $num) {
    return array_slice($data, ($page-1)*$num, $num);
}

//获取token函数
function getToken($member_id, $member_name, $member_avatar) {
    srand((double)microtime()*1000000);

    $appKey = 'n19jmcy5nejo9';
    $appSecret = 'ql8rxPItynllG'; // 开发者平台分配的 App Secret。

    $nonce = rand(); // 获取随机数。
    $timestamp = time(); // 获取时间戳。

    $signature = sha1($appSecret.$nonce.$timestamp);

    $url = 'http://api.cn.ronghub.com/user/getToken.json';

    $postData = 'userId=' . $member_id . '&name=' . $member_name . '&portraitUri=' . $member_avatar;

    $httpHeader = array(

        'App-Key:' . $appKey,   //平台分配

        'Nonce:' . $nonce,        //随机数

        'Timestamp:' . $timestamp,    //时间戳

        'Signature:' . $signature,         //签名

        'Content-Type: application/x-www-form-urlencoded',

    );

//创建http header

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_POST, 1);

    if ($postData != '') {

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    } else {

        showMsg(0, '缺少相应参数');

    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_HEADER, false);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);

    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);


    $result = curl_exec($ch);
    /*print_r($result);
    exit();*/
    $token = json_decode($result) -> token;
    curl_close($ch);
    return $token;
}

function name_rand() {
    //生成随机用户名
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $length = 8;
    $mobile_name = '';
    for ($i = 0; $i < $length; $i++) {
        $mobile_name .= $chars[mt_rand(0, strlen($chars) - 1)];     //随机生成的新用户名(昵称)
    }
    return $mobile_name;
}

function rsaDecrypt($data) {
    $private_key = file_get_contents(APP_PATH."chat/controller/rsa_key/rsa_private_key.pem");
//     $private_key = '-----BEGIN RSA PRIVATE KEY-----
// MIIEpQIBAAKCAQEA+CpLs/07K7Ent0iWpJ0AH49+4FhX6FvhC/7xGPgWN3XdPpJ7
// dLQLr8caRSlxV6aZGzky5iKk8brH0RdvepJ6xkkfh64mvwhDyeNtQRt1XqA+2pBP
// lDURz576Q631a/jLqG02eQA5HMGp5VnBCju8Q5NkuDwXrU+zfQbfYoV0d5tlNJVR
// ZxdoavtSS81jHZQzAWu3qKH3ZOlst8FybZPa/lZ8dEMut9lzco1LtE3ej9FK/a9o
// ChMUd2K34vK/+uqhzofGgNuqUFZZQnsqlr3IGDAy77JtmRieXy8gDQvKvn6C8QXn
// qWkEesfNonlnvXZnBveIE3biNolS6M779J1I2wIDAQABAoIBAFCZRA8IDsCo/9Iq
// NCrwhsq5SybH6n0f4h6IRXurj2Oh1yJsZsZHd4g2bRTLWfFyvUU3NY3C8LlFxYHm
// T6GKk2907lWdvR3MLGAUijZcGamK9/76Ya4r8kXxRyzWAeB4ZISmO8vew63QREKl
// /Ok673EvHMYtVi9GtG/F3KVd3K6/WMrnVoEoYwros0i/XqRRmLc6Y8Pq5KoUPsB2
// iwYYzO9FWWPRTqXIX2j8XOWDBs+YAH8uNbhFxwcQ0cfJsUjRUnruMvNK8l7DxTe7
// zMwzWomkwEAveoPOOiCiGto+HRLpdmyRImrOCyTzPzSsqx4oPxEOpn6rxHnGJXPR
// DruCuFECgYEA/ne8zRrHC9kLZNxlC0PTjk4jmpEaqdf+Vt36JnGmCZa/fo1ryx5J
// As+pozVnd4Wjaxbn7zcr3X+zHC5YUzatFwW5hVoSJrq9Y39E12PecXaCtgv0uigg
// etp5F65bUeFuRYw0tu0XK6/SxlzxBBtRBuSOAKuMU7ViA4CEEV4RDAkCgYEA+ajX
// xzlJwZhsSXNFwpifQVt7Q/8Rt7D3DfPKNq0XrCSz7vY9aR6JiLXlI5Jx+Y9ubHxd
// mOggE3Df1t9LVTL0ub9tpBsV/MAWjHtioIKG7eLizlCaWCzTdNks9t21o9k+jX60
// gW1a87vh3fumoNFuyYN2p/Kip++FJ/Vdu36brsMCgYEAwppBSb2LsTTYMNcXcwj4
// 1eBN/Bux/2eOO1JgxlutdbHSWm6+m8RaY4r6GTVzHgWWVwRadSeFJUxOZmPO6jxr
// HKslGKUsMnAm+9tNg0b9y1mtVplIG8EqLam8Z27QqYqzsP4rJiWkzaUVlNto+hIF
// 4jaRnlOS6kVRYoo69akxcjkCgYEAo1dihcfnh7Av+QZgN+cfGuW9iklR0DsKCKje
// PlOVT11cFBYrK6F9HmLTnihsRXkgJ/5eRnJoug95g93oLb/++aBTHbFS+2C54APC
// DX4YxX9SIOX/dX0uhB6rGoEIHEFAedbSRnPDs//S0SeaZ1FfCJzofw1yeiltly0j
// vE4jkX0CgYEAr7EmawNoEjiqqBf/Ry5WEm1grSqhuXxERgGu1rfKJrfpd+Jbvt/c
// ziga4iXje4bDfIidLyuQgibplIrt4goZW1dJrjfkWqwUQ1RMM/pkPb8FN9vXHRRx
// EMRR1l8bbXecCjjGnpgjvtqRkJW9WuFCXvFQhpZpXymCZN7h0nZ3m/Q=
// -----END RSA PRIVATE KEY-----';

/*    $public_key = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA+CpLs/07K7Ent0iWpJ0A
H49+4FhX6FvhC/7xGPgWN3XdPpJ7dLQLr8caRSlxV6aZGzky5iKk8brH0RdvepJ6
xkkfh64mvwhDyeNtQRt1XqA+2pBPlDURz576Q631a/jLqG02eQA5HMGp5VnBCju8
Q5NkuDwXrU+zfQbfYoV0d5tlNJVRZxdoavtSS81jHZQzAWu3qKH3ZOlst8FybZPa
/lZ8dEMut9lzco1LtE3ej9FK/a9oChMUd2K34vK/+uqhzofGgNuqUFZZQnsqlr3I
GDAy77JtmRieXy8gDQvKvn6C8QXnqWkEesfNonlnvXZnBveIE3biNolS6M779J1I
2wIDAQAB
-----END PUBLIC KEY-----';*/

//echo $private_key;
    $pi_key =  openssl_pkey_get_private($private_key);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
//    $pu_key = openssl_pkey_get_public($public_key);//这个函数可用来判断公钥是否是可用的
//        print_r($pi_key);echo "\n";
//        print_r($pu_key);echo "\n";


    $encrypted = "";
    $decrypted = "";


//        echo "public key encrypt:\n";
//        openssl_public_encrypt('1234567',$encrypted,$pu_key);//公钥加密
//        $encrypted = base64_encode($encrypted);
//        echo $encrypted,"\n";

//        echo "private key decrypt:\n";
    openssl_private_decrypt(base64_decode($data),$decrypted,$pi_key);//私钥解密
    return $decrypted;
//        echo $decrypted,"\n";
}


//把用户输入的文本转义（主要针对特殊符号和emoji表情）
function textEncode($str){
    if(!is_string($str)) return $str;
    if(!$str || $str=='undefined') return '';
    $text = json_encode($str); //暴露出unicode
    $text = preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i", function($str){
        return addslashes($str[0]);
    },$text); //将emoji的unicode留下,其他不变
    return json_decode($text);
}

//解码上面的转义
function textDecode($str){
    $text = json_encode($str); //暴露出unicode
    $text = preg_replace_callback('/\\\\\\\\/i', function($str){
        return '\\';
    }, $text); //将两条斜杠变成一条,其他不变
    return json_decode($text);
}