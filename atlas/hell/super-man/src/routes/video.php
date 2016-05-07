<?php

/**
 * 店铺视频路由配置
 * 
 * @category yunke
 * @package atlas\hell\super-man\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 视频首页
Route::get('/video', '\Ecdo\EcdoSuperMan\StoreVideos@index');
// 创建视频
Route::get('/video/crVideo', '\Ecdo\EcdoSuperMan\StoreVideos@crVideo');
// 创建视频处理
Route::post('/video/crVideoDis', '\Ecdo\EcdoSuperMan\StoreVideos@crVideoDis');
// 编辑视频
Route::get('/video/upVideo', '\Ecdo\EcdoSuperMan\StoreVideos@upVideo');
// 编辑视频处理
Route::post('/video/upVideoDis', '\Ecdo\EcdoSuperMan\StoreVideos@upVideoDis');
// 删除视频
Route::get('/video/deVideo', '\Ecdo\EcdoSuperMan\StoreVideos@deVideo');
// 批量删除视频
Route::get('/video/drVideo', '\Ecdo\EcdoSuperMan\StoreVideos@drVideo');
// 搜索视频
Route::get('/video/seVideo', '\Ecdo\EcdoSuperMan\StoreVideos@seVideo');
