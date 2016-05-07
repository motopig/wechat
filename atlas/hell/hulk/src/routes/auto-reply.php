<?php

/**
 * 微信自动回复路由
 * 
 * @category yunke
 * @package atlas\hell\hulk\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 自动回复首页
Route::get('/autoReply', '\Ecdo\EcdoHulk\WechatAutoReplys@index');
// 搜索自动回复
Route::get('/autoReply/seAutoReply', '\Ecdo\EcdoHulk\WechatAutoReplys@seAutoReply');
// 创建自动回复
Route::get('/autoReply/crAutoReply', '\Ecdo\EcdoHulk\WechatAutoReplys@crAutoReply');
// 创建自动回复处理
Route::post('/autoReply/crAutoReplyDis', '\Ecdo\EcdoHulk\WechatAutoReplys@crAutoReplyDis');
// 编辑自动回复
Route::get('/autoReply/upAutoReply', '\Ecdo\EcdoHulk\WechatAutoReplys@upAutoReply');
// 编辑自动回复处理
Route::post('/autoReply/upAutoReplyDis', '\Ecdo\EcdoHulk\WechatAutoReplys@upAutoReplyDis');
// 删除自动回复
Route::get('/autoReply/deAutoReply', '\Ecdo\EcdoHulk\WechatAutoReplys@deAutoReply');
// 关注自动回复设置
Route::any('/autoReply/concernAutoReply', '\Ecdo\EcdoHulk\WechatAutoReplys@concernAutoReply');
// 关键字匹配设置
Route::any('/autoReply/matchingAutoReply', '\Ecdo\EcdoHulk\WechatAutoReplys@matchingAutoReply');
