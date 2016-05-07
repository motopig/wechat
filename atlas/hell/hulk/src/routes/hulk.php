<?php

/**
 * 商家后台微信路由配置
 * 
 * @category yunke
 * @package atlas\hell\hulk\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 商家后台微信模块
Route::group(array(
    'before' => ['angel.auth', 'tower.verify'],
    'prefix' => 'angel/wechat'
), function ()
{
	// 微信控制台路由配置
	include 'dashboard.php';

	// 微信普通图文素材路由配置
	include 'graphics.php';

	// 微信高级图文素材路由配置
	include 'material.php';

	// 微信会员路由配置
	include 'member.php';

	// 微信组别路由配置
	include 'group.php';

    //微信菜单路由配置
    include 'menu.php';

    //微信自动回复路由配置
    include 'auto-reply.php';

    //微信二维码路由配置
    include 'code.php';

    // 微信摇一摇配置
    include 'shakearound.php';

    //微信用户消息路由
    include 'message.php';
});

// 商家前台微信模块
Route::group(array(
    'prefix' => '{tower}'
), function ()
{
    include 'site.php';
});
