<?php

/**
 * 幸运大抽奖路由配置
 * 
 * @category yunke
 * @package atlas\hell\thor\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

Route::group(array(
    'before' => ['angel.auth', 'tower.verify'],
    'prefix' => 'angel'
), function ()
{
	include 'luckdraw.php';
});

// 前台模块
Route::group(array(
    'prefix' => '{tower}'
), function ()
{
    include 'site.php';
});

