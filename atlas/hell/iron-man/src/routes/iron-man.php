<?php

/**
 * 卡券路由配置
 * 
 * @category yunke
 * @package atlas\hell\iron-man\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 商家后台微信模块
Route::group(array(
    'before' => ['angel.auth', 'tower.verify'],
    'prefix' => 'angel'
), function ()
{
	// 卡券app
	include 'coupons.php';
	// 卡券核销
	include 'carduse.php';
	// 核销员
	include 'verification.php';
});

// 商家前台卡券模块
Route::group(array(
    'prefix' => '{tower}'
), function ()
{
    include 'site.php';
});
