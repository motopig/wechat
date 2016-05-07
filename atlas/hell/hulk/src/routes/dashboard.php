<?php

/**
 * 商家后台微信控制台路由
 * 
 * @category yunke
 * @package atlas\hell\hulk\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 微信配置
Route::get('/config', '\Ecdo\EcdoHulk\WechatDashboard@config');
// 微信配置处理
Route::post('/setting', '\Ecdo\EcdoHulk\WechatDashboard@configDis');
// 首页
Route::get('/', '\Ecdo\EcdoHulk\WechatDashboard@index');
