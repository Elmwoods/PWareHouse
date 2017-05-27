<?php
namespace addons\webchinese\controller;

use think\addons\Controller;
use addons\webchinese\model\Webchinese as M;

class Alidayu extends Controller{
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
    
}