<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => APP_PATH.'purse' . DS . 'view' . DS . 'default' . DS . 'skip.html',
    'dispatch_error_tmpl'    => APP_PATH.'purse' . DS . 'view' . DS . 'default' . DS . 'skip.html',
    //分页配置
    'paginate'               => [
        'type'      => 'bootstrap',
        'var_page'  => 'p',
        'list_rows' => 15,
    ],
];
