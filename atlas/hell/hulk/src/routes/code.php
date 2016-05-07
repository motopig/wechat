<?php

/**
 * 微信二维码路由
 * 
 * @category yunke
 * @package atlas\hell\hulk\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 二维码首页
Route::get('/code', '\Ecdo\EcdoHulk\WechatCodes@index');
// 搜索二维码
Route::get('/code/seCode', '\Ecdo\EcdoHulk\WechatCodes@seCode');
// 创建二维码
Route::get('/code/crCode', '\Ecdo\EcdoHulk\WechatCodes@crCode');
// 创建二维码处理
Route::post('/code/crCodeDis', '\Ecdo\EcdoHulk\WechatCodes@crCodeDis');
// 编辑二维码
Route::get('/code/upCode', '\Ecdo\EcdoHulk\WechatCodes@upCode');
// 编辑二维码处理
Route::post('/code/upCodeDis', '\Ecdo\EcdoHulk\WechatCodes@upCodeDis');
// 删除二维码
Route::get('/code/deCode', '\Ecdo\EcdoHulk\WechatCodes@deCode');
// 二维码用途模版数据
Route::any('/code/uses', '\Ecdo\EcdoHulk\WechatCodes@uses');
