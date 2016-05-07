<?php

/**
 * 门店路由配置
 * 
 * @category yunke
 * @package atlas\hell\bat-man\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 商家后台微信模块
Route::group(array(
    'before' => ['angel.auth', 'tower.verify'],
    'prefix' => 'angel'
), function ()
{
	// 门店基本配置
	include 'entityshop.php';
});

// 商家前台门店模块
Route::group(array(
    'prefix' => '{tower}'
), function ()
{
    include 'site.php';
});
