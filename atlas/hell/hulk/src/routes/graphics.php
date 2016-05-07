<?php

/**
 * 商家后台微信普通图文素材路由
 * 
 * @category yunke
 * @package atlas\hell\hulk\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 普通图文首页
Route::get('/graphics', '\Ecdo\EcdoHulk\WechatGraphics@index');
// 创建普通单图文
Route::get('/graphics/crSingel', '\Ecdo\EcdoHulk\WechatGraphics@crSingel');
// 创建普通多图文
Route::get('/graphics/crMany', '\Ecdo\EcdoHulk\WechatGraphics@crMany');
// 查看普通图文
Route::get('/graphics/shGraphics', '\Ecdo\EcdoHulk\WechatGraphics@shGraphics');
// 编辑普通图文
Route::get('/graphics/upGraphics', '\Ecdo\EcdoHulk\WechatGraphics@upGraphics');
// 创建或编辑普通单图文处理
Route::post('/graphics/crupGraphicDis', '\Ecdo\EcdoHulk\WechatGraphics@crupGraphicDis');
// 创建或编辑普通多图文处理
Route::post('/graphics/crupGraphicsDis', '\Ecdo\EcdoHulk\WechatGraphics@crupGraphicsDis');
// 删除普通图文
Route::get('/graphics/deGraphics', '\Ecdo\EcdoHulk\WechatGraphics@deGraphics');
// 搜索普通图文
Route::get('/graphics/seGraphics', '\Ecdo\EcdoHulk\WechatGraphics@seGraphics');
// 筛选普通图文
Route::get('/graphics/fiGraphics', '\Ecdo\EcdoHulk\WechatGraphics@fiGraphics');
// 筛选普通图文处理
Route::any('/graphics/fiGraphicsDis', '\Ecdo\EcdoHulk\WechatGraphics@fiGraphicsDis');
// 获取普通图文图片数据
Route::any('/graphics/graphicsImageUrl', '\Ecdo\EcdoHulk\WechatGraphics@graphicsImageUrl');
