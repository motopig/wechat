<?php

/**
 * 商家后台微信高级图文素材路由
 * 
 * @category yunke
 * @package atlas\hell\hulk\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 高级图文首页
Route::get('/material', '\Ecdo\EcdoHulk\WechatMaterial@index');
// 创建高级单图文
Route::get('/material/crSingel', '\Ecdo\EcdoHulk\WechatMaterial@crSingel');
// 创建高级多图文
Route::get('/material/crMany', '\Ecdo\EcdoHulk\WechatMaterial@crMany');
