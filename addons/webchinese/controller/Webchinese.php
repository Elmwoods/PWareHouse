<?php
namespace addons\webchinese\controller;

use think\addons\Controller;
use addons\webchinese\model\Webchinese as M;

class Webchinese extends Controller{
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
    
}