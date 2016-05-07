<?php

/**
 * 微信摇一摇路由
 * 
 * @category yunke
 * @package atlas\hell\hulk\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 设备列表
Route::get('/shakearound/device', '\Ecdo\EcdoHulk\WechatShakearounds@device');
// 查看设备
Route::get('/shakearound/shDevice', '\Ecdo\EcdoHulk\WechatShakearounds@shDevice');
// 搜索设备
Route::get('/shakearound/seDevice', '\Ecdo\EcdoHulk\WechatShakearounds@seDevice');
// 刷新设备
Route::post('/shakearound/deviceReload', '\Ecdo\EcdoHulk\WechatShakearounds@deviceReload');
// 创建设备
Route::get('/shakearound/device/create', '\Ecdo\EcdoHulk\WechatShakearounds@deviceCreate');
// 创建设备处理
Route::post('/shakearound/deviceCreateDis', '\Ecdo\EcdoHulk\WechatShakearounds@deviceCreateDis');
// 设备修改绑定页面
Route::get('/shakearound/device/update', '\Ecdo\EcdoHulk\WechatShakearounds@deviceUpdate');
// 设备修改绑定页面处理
Route::post('/shakearound/deviceUpdateDis', '\Ecdo\EcdoHulk\WechatShakearounds@deviceUpdateDis');
// 页面列表
Route::get('/shakearound/page', '\Ecdo\EcdoHulk\WechatShakearounds@page');
// 搜索页面
Route::get('/shakearound/sePage', '\Ecdo\EcdoHulk\WechatShakearounds@sePage');
// 创建页面
Route::get('/shakearound/page/create', '\Ecdo\EcdoHulk\WechatShakearounds@pageCreate');
// 创建页面处理
Route::post('/shakearound/pageCreateDis', '\Ecdo\EcdoHulk\WechatShakearounds@pageCreateDis');
// 编辑页面
Route::get('/shakearound/page/update', '\Ecdo\EcdoHulk\WechatShakearounds@pageUpdate');
// 编辑页面处理
Route::post('/shakearound/pageUpdateDis', '\Ecdo\EcdoHulk\WechatShakearounds@pageUpdateDis');
// 删除页面
Route::get('/shakearound/page/delete', '\Ecdo\EcdoHulk\WechatShakearounds@pageDelete');
