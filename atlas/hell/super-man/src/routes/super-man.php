<?php
/**
 * 商家后台店铺(铺面)路由配置
 * 
 * @category yunke
 * @package atlas\hell\super-man\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 商家后台店铺(铺面)模块
Route::group(array(
    'before' => ['angel.auth', 'tower.verify'],
    'prefix' => 'angel/store'
), function ()
{
	// 图片路由配置
	include 'image.php';

	// 语音路由配置
	include 'voice.php';

	// 视频路由配置
	include 'video.php';
});