<?php

/**
 * 商家后台会员路由配置
 * 
 * @category yunke
 * @package atlas\hell\member\src\routes
 * @author hello<hello@no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 商家后台微信模块
Route::group(array(
    'before' => ['angel.auth', 'tower.verify'],
    'prefix' => 'angel/member'
), function ()
{
	// 会员模块管理页面
	include 'dashboard.php';
	// 会员信息
	include 'info.php';
    
});

// 商家前台会员模块
Route::group(array(
    'prefix' => '{tower}'
), function ()
{
    include 'site.php';
});
