<?php

namespace think\addons;

use think\Request;
use think\Config;
use think\Loader;

/**
 * 插件基类控制器
 * Class Controller
 * @package think\addons
 */
class Controller extends \think\Controller
{
    // 当前插件操作
    protected $addon = null;
    protected $controller = null;
    protected $action = null;
    // 当前template
    protected $template;
    // 模板配置信息
    protected $config = [
        'type' => 'Think',
        'view_path' => '',
        'view_suffix' => 'html',
        'strip_space' => true,
        'view_depr' => DS,
        'tpl_begin' => '{',
        'tpl_end' => '}',
        'taglib_begin' => '{',
        'taglib_end' => '}',
    ];

    /**
     * 架构函数
     * @param Request $request Request对象
     * @access public
     */
    public function __construct(Request $request = null)
    {
        // 生成request对象
        $this->request = is_null($request) ? Request::instance() : $request;
        // 初始化配置信息
        $this->config = Config::get('template') ?: $this->config;
        // 处理路由参数
        $route = $this->request->param('route', '');
        $param = explode('-', $route);
        // 是否自动转换控制器和操作名
        $convert = \think\Config::get('url_convert');
        // 格式化路由的插件位置
        $this->action = $convert ? strtolower(array_pop($param)) : array_pop($param);
        $this->controller = $convert ? strtolower(array_pop($param)) : array_pop($param);
        $this->addon = $convert ? strtolower(array_pop($param)) : array_pop($param);
        $base = new \think\addons\BaseModel();
        $status = $base->getAddonStatus(ucfirst($this->addon));
        if(isset($status) && $status!=1){
            $request = request();
            if($request->isMobile()){
                if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
                    header("Location:".url('wechat/error/message'));
                }else{
                    header("Location:".url('mobile/error/message'));
                }
            }else{
            	header("Location:".url('home/error/message'));
            }
        	exit();
        }
        // 生成view_path
        $view_path = $this->config['view_path'] ?: 'view';

        // 重置配置
        Config::set('template.view_path', ADDON_PATH . $this->addon . DS . $view_path . DS);
        $this->checkPrivileges();
        parent::__construct($request);
        $this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
    }
	
    public function checkPrivileges(){
    	$urls = model('home/HomeMenus')->getMenusUrl();
    	$request = request();
    	$visit = strtolower($request->path());
    	if(isset($urls[$visit])){
    		$menuType = (int)$urls[$visit];
    		$userType = -1;
    		if((int)session('WST_USER.userId')>0)$userType = 0;
    		if((int)session('WST_USER.shopId')>0)$userType = 1;
    		//未登录不允许访问受保护的资源
    		if($userType==-1){
    			if($request->isAjax()){
    				echo json_encode(['status'=>-999,'msg'=>'对不起，您还没有登录，请先登录']);
    			}else{
    				header("Location:".url('home/users/login'));
    			}
    			exit();
    		}
    		//已登录但不是商家 则不允许访问受保护的商家资源
    		if($userType==0 && $menuType==1){
    			if($request->isAjax()){
    				echo json_encode(['status'=>-999,'msg'=>'对不起，您不是商家，请先申请为商家再访问']);
    			}else{
    				header("Location:".url('home/shops/login'));
    			}
    			exit();
    		}
    	}
    }
    
    public function checkAdminPrivileges(){
    	
    	$STAFF = session('WST_STAFF');
    	$request = request();
    	if(empty($STAFF)){
    		if($request->isAjax()){
    			echo json_encode(['status'=>-999,'msg'=>'对不起，您还没有登录，请先登录']);
    		}else{
    			header("Location:".url('admin/index/login'));
    		}
    		exit();
    	}else{
	    	$urls = WSTVisitPrivilege();
	    	$privileges = session('WST_STAFF.privileges');
	        $visit = $request->path();
	        if(!$privileges || (array_key_exists($visit,$urls) && !in_array($urls[$visit]['code'],$privileges))){
	        	if($request->isAjax()){
	        		echo json_encode(['status'=>-998,'msg'=>'对不起，您没有操作权限，请与管理员联系']);
	        	}else{
	        		header("Content-type: text/html; charset=utf-8");
	        	    echo "对不起，您没有操作权限，请与管理员联系";
	        	}
	        	exit();
	        }
    	}
    }
    

    /**
     * 加载模板输出
     * @access protected
     * @param string $template 模板文件名
     * @param array $vars 模板输出变量
     * @param array $replace 模板替换
     * @param array $config 模板参数
     * @return mixed
     */
    public function fetch($template = '', $vars = [], $replace = [], $config = []){
        $controller = Loader::parseName($this->controller);
        if ('think' == strtolower($this->config['type']) && $controller && 0 !== strpos($template, '/')) {
            $depr = $this->config['view_depr'];
            $template = str_replace(['/', ':'], $depr, $template);
            if ('' == $template) {
                // 如果模板文件名为空 按照默认规则定位
                $template = str_replace('.', DS, $controller) . $depr . $this->action;
            } elseif (false === strpos($template, $depr)) {
                $template = str_replace('.', DS, $controller) . $depr . $template;
            }
        }
        $replace['__STYLE__'] = str_replace('/index.php','',\think\Request::instance()->root()).'/wstmart/home/view/'.WSTConf('CONF.wsthomeStyle');
        $replace['__ADMIN__'] = str_replace('/index.php','',\think\Request::instance()->root()).'/wstmart/admin/view';
        $replace['__WECHAT__'] = str_replace('/index.php','',\think\Request::instance()->root()).'/wstmart/wechat/view/'.WSTConf('CONF.wstwechatStyle');;
        $replace['__MOBILE__'] = str_replace('/index.php','',\think\Request::instance()->root()).'/wstmart/mobile/view/default';
        return \Think\Controller::fetch($template, $vars, $replace, $config);
    }
    
    /**
     * 模板变量赋值
     * @access protected
     * @param mixed $name  要显示的模板变量
     * @param mixed $value 变量的值
     * @return void
     */
    public function assign($name, $value = ''){
    	$this->view->assign($name, $value);
    }
    
}
