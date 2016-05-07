<?php

/**
 * 店铺图片路由配置
 * 
 * @category yunke
 * @package atlas\hell\super-man\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 图片首页
Route::get('/image', '\Ecdo\EcdoSuperMan\StoreImages@index');
// 创建图片
Route::get('/image/crImage', '\Ecdo\EcdoSuperMan\StoreImages@crImage');
// 创建图片处理
Route::post('/image/crImageDis', '\Ecdo\EcdoSuperMan\StoreImages@crImageDis');
// 编辑图片
Route::get('/image/upImage', '\Ecdo\EcdoSuperMan\StoreImages@upImage');
// 编辑图片处理
Route::post('/image/upImageDis', '\Ecdo\EcdoSuperMan\StoreImages@upImageDis');
// 删除图片
Route::get('/image/deImage', '\Ecdo\EcdoSuperMan\StoreImages@deImage');
// 批量删除图片
Route::get('/image/drImage', '\Ecdo\EcdoSuperMan\StoreImages@drImage');
// 搜索图片
Route::get('/image/seImage', '\Ecdo\EcdoSuperMan\StoreImages@seImage');
