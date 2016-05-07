<?php

/**
 * 商家路由配置
 * 
 * @category yunke
 * @package atlas\hell\spider-man\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 商家注册获取验证码 (ajax)
Route::get('angel/code_validator', '\Ecdo\EcdoSpiderMan\AngelAccount@codeValidator');

// 商家注册
Route::get('angel/register', '\Ecdo\EcdoSpiderMan\AngelAccount@getRegister');
Route::post('angel/register', '\Ecdo\EcdoSpiderMan\AngelAccount@postRegister');

// 商家注册邮件验证激活
Route::get('angel/mailsent', '\Ecdo\EcdoSpiderMan\AngelAccount@getMailSent');

// 注册邮件验证创建账户
Route::get('angel/email_validator', '\Ecdo\EcdoSpiderMan\AngelAccount@getEmailValidator');
Route::post('angel/email_validator', '\Ecdo\EcdoSpiderMan\AngelAccount@postEmailValidator');

// 忘记密码邮件验证激活
Route::get('angel/resetpwd', '\Ecdo\EcdoSpiderMan\AngelAccount@getResetPwd');
// 忘记密码邮件验证激活处理
Route::post('angel/resetpwd', '\Ecdo\EcdoSpiderMan\AngelAccount@postResetPwd');

// 忘记密码创建新密码
Route::get('angel/resetpwdset', '\Ecdo\EcdoSpiderMan\AngelAccount@getResetPwdSet');
// 忘记密码创建新密码处理
Route::post('angel/resetpwdset', '\Ecdo\EcdoSpiderMan\AngelAccount@postResetPwdSet');

// 商家登录
Route::get('angel/login', '\Ecdo\EcdoSpiderMan\AngelAccount@getLogin');
Route::post('angel/login', '\Ecdo\EcdoSpiderMan\AngelAccount@postLogin');

// 商家登出
Route::get('angel/logout', '\Ecdo\EcdoSpiderMan\AngelAccount@getLogout');

//错误页面
Route::get('angel/errorpage', '\Ecdo\EcdoSpiderMan\AngelDashboard@errorPage');

// 商家后台模块
Route::group(array(
    'before' => 'angel.auth',
    'prefix' => 'angel'
), function (){
	// 后台控制台路由配置
	include 'dashboard.php';
	// 个人资料设置
	include 'account.php';
	//公用模态路由
    include 'modal.php';
    
    //商店
    include 'route_shop.php';
	//订单路由
    include 'route_order.php';
});
