<?php

/**
 * 商家后台微信组别管理路由
 * 
 * @category yunke
 * @package atlas\hell\hulk\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 组别首页
Route::get('/group', '\Ecdo\EcdoHulk\WechatGroups@index');
// 创建组别
Route::get('/group/crGroup', '\Ecdo\EcdoHulk\WechatGroups@crGroup');
// 编辑组别
Route::get('/group/upGroup', '\Ecdo\EcdoHulk\WechatGroups@upGroup');
// 创建或编辑组别处理
Route::post('/group/crupGroupDis', '\Ecdo\EcdoHulk\WechatGroups@crupGroupDis');
// 删除组别
Route::get('/group/deGroup', '\Ecdo\EcdoHulk\WechatGroups@deGroup');
// 批量删除组别
Route::get('/group/drGroup', '\Ecdo\EcdoHulk\WechatGroups@drGroup');
// 查看组别
Route::get('/group/shGroup', '\Ecdo\EcdoHulk\WechatGroups@shGroup');
// 搜索组别
Route::get('/group/seGroup', '\Ecdo\EcdoHulk\WechatGroups@seGroup');
// 筛选组别
Route::get('/group/fiGroup', '\Ecdo\EcdoHulk\WechatGroups@fiGroup');
// 筛选组别处理
Route::any('/group/fiGroupDis', '\Ecdo\EcdoHulk\WechatGroups@fiGroupDis');
// 导入组别
Route::get('/group/imGroup', '\Ecdo\EcdoHulk\WechatGroups@imGroup');
// 导入组别处理
Route::post('/group/imGroupDis', '\Ecdo\EcdoHulk\WechatGroups@imGroupDis');
// 导出组别
Route::get('/group/exGroup', '\Ecdo\EcdoHulk\WechatGroups@exGroup');
