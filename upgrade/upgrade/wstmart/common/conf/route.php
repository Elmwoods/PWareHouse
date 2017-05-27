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
\think\Route::rule([
    //PC版优化
    'category-<cat>'=>'home/goods/lists',        //商品分类
    'search'=>'home/goods/search',               //商品搜索
    'goods-<id>'=>'home/goods/detail',           //商品
    'brands'=>'home/brands/index',               //品牌
    'street'=>'home/shops/shopstreet',           //品牌
    'service-<id>'=>'home/helpcenter/view',      //帮助中心
    'service'=>'home/helpcenter/view',           //帮助中心
    'news-<id>'=>'home/news/view',               //新闻
    'news'=>'home/news/view',                    //新闻
    'newscats-<catId>'=>'home/news/nlist',      //新闻
    'newscats'=>'home/news/nlist',               //新闻
    'login'=>'home/users/login',                 //用户登录页
    'register'=>'home/users/regist',             //用户登录页
    'forget'=>'home/users/forgetpass',           //找回密码
    'forget-step1'=>'home/users/forgetPasst',    //找回密码
    'forget-success'=>'home/users/forgetPassf',  //找回密码
    'forget-step3'=>'home/users/resetPass',      //找回密码
    'shop-login'=>'home/shops/login',            //商家登录
    'selfshop'=>'home/shops/selfshop',           //自营店铺
    'shop-<shopId>'=>'home/shops/home',           //店铺主页
    'shop-protocol'=>'home/shopapplys/protocol'
]);