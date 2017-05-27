<?php

use think\Hook;
use think\Config;
use think\Loader;
use think\Db;

// 插件目录
define('ADDON_PATH', ROOT_PATH . 'addons' . DS);
// 定义路由
\think\Route::any('addon/:route', "\\think\\addons\\AddonsController@execute");


// 如果插件目录不存在则创建
if (!is_dir(ADDON_PATH)) {
    @mkdir(ADDON_PATH, 0777, true);
}

// 注册类的根命名空间
\think\Loader::addNamespace('addons', ADDON_PATH);

// 闭包初始化行为
Hook::add('app_begin', function () {

	$hooks = cache('hooks');

	if(empty($hooks)){
		$addons = Db::name('hooks')->where("addons != ''")->column('name,addons');
		if(!empty($addons)){
			foreach ($addons as $key => $values) {
				if($values){
					$map['status'] = 1;
					$names = explode(',',$values);
					$map['name'] = array('IN',$names);
					$data = model('common/addons')->where($map)->column('addonId,name');
		
					if($data){
						$data = array_intersect($names, $data);
					
						$addons[$key] = array_filter(array_map('get_addon_class', $data));
						\think\Hook::add($key, $addons[$key]);
					}
				}
			}
			array_filter($addons);
			cache('hooks', $addons);
		}
		
	}else{
		foreach ($hooks as $key => $values) {
			if(is_array($hooks[$key])){
				\think\Hook::add($key, $hooks[$key]);
			}
		}
	}

    
});


/**
 * 处理插件钩子
 * @param string $hook 钩子名称
 * @param mixed $params 传入参数
 * @return void
 */
function hook($hook, $params = []){
    \think\Hook::listen($hook, $params);
}

/**
 * 获取插件类的类名
 * @param $name 插件名
 * @param string $type 返回命名空间类型
 * @param string $class 当前类名
 * @return string
 */
function get_addon_class($name, $type = 'hook', $class = null){
    $name = \think\Loader::parseName($name);
    // 处理多级控制器情况
    if (!is_null($class) && strpos($class, '.')) {
        $class = explode('.', $class);
        foreach ($class as $key => $cls) {
            $class[$key] = \think\Loader::parseName($cls, 1);
        }
        $class = implode('\\', $class);
    } else {
        $class = \think\Loader::parseName(is_null($class) ? $name : $class, 1);
    }
    switch ($type) {
        case 'controller':
            $namespace = "\\addons\\" . $name . "\\controller\\" . $class;
            break;
        default:
            $namespace = "\\addons\\" . $name . "\\" . $class;
    }

    return class_exists($namespace) ? $namespace : '';
}

/**
 * 获取插件类的配置文件数组
 * @param string $name 插件名
 * @return array
 */
function get_addon_config($name){
    $class = get_addon_class($name);
    if (class_exists($class)) {
        $addon = new $class();
        return $addon->getConfig();
    } else {
        return [];
    }
}

/**
 * 插件显示内容里生成访问插件的url
 * @param $url
 * @param array $param
 * @return bool|string
 * @param bool|string $suffix 生成的URL后缀
 * @param bool|string $domain 域名
 */
function addon_url($url, $param = [], $suffix = true, $domain = false){
    $url = parse_url($url);
    $case = config('url_convert');
    $addons = $case ? Loader::parseName($url['scheme']) : $url['scheme'];
    $controller = $case ? Loader::parseName($url['host']) : $url['host'];
    $action = trim($case ? strtolower($url['path']) : $url['path'], '/');

    /* 解析URL带的参数 */
    if (isset($url['query'])) {
        parse_str($url['query'], $query);
        $param = array_merge($query, $param);
    }

    // 生成插件链接新规则
    $actions = "{$addons}-{$controller}-{$action}";

    return url("addon/{$actions}", $param, $suffix, $domain);
}

/**
 * 安装插件执行sql
 */
function installSql($name){
	$sqlfile = WSTRootPath()."/addons/".$name."/install.sql";
	if(file_exists($sqlfile)){
		$sql = file_get_contents($sqlfile);
		excuteSql($sql,config('database')["prefix"]);
	}
	
}

/**
 * 卸载插件执行sql
 */
function uninstallSql($name){
	$sqlfile = WSTRootPath()."/addons/".$name."/uninstall.sql";
	if(file_exists($sqlfile)){
		$sql = file_get_contents($sqlfile);
		excuteSql($sql,config('database')["prefix"]);
	}
}

/**
 * 执行sql
 * @param string $db_prefix
 */
function excuteSql($sql,$db_prefix=''){
	if(!isset($sql) || empty($sql)) return;
	$sql = str_replace("\r", "\n", str_replace(' `wst_', ' `'.$db_prefix, $sql));
	$ret = array();
	$num = 0;
	foreach(explode(";\n", trim($sql)) as $query) {
		$ret[$num] = '';
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$ret[$num] .= (isset($query[0]) && $query[0] == '#') || (isset($query[1]) && isset($query[1]) && $query[0].$query[1] == '--') ? '' : $query;
		}
		$num++;
	}
	unset($sql);
	foreach($ret as $query){
		$query = trim($query);
		if($query) {
			if(strtoupper(substr($query, 0, 12)) == 'CREATE TABLE'){
				$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $query));
				$query = preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $query)." ENGINE=InnoDB DEFAULT CHARSET=utf8";
			}
			Db::execute($query);
		}
	}
}