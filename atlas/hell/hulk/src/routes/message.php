<?php

/**
 * 微信用户消息路由
 * 
 * @category yunke
 * @package atlas\hell\hulk\src\routes
 * @author Dev<Dev@no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 用户消息首页
Route::get('/message', '\Ecdo\EcdoHulk\WechatMessages@index');
// 用户消息类型查看
Route::get('/message/cat', '\Ecdo\EcdoHulk\WechatMessages@cat');
// 获取某个用户的对话消息
Route::get('/message/getMemberMessage', '\Ecdo\EcdoHulk\WechatMessages@getMemberMessage');
// 创建用户消息
Route::post('/message/replay', '\Ecdo\EcdoHulk\WechatMessages@replay');
// 检查用户新消息
Route::get('/message/checkNewMessage', '\Ecdo\EcdoHulk\WechatMessages@checkNewMessage');
//更多消息查看
Route::get('/message/more', '\Ecdo\EcdoHulk\WechatMessages@more');
//搜索用户昵称
Route::get('/message/seMessage', '\Ecdo\EcdoHulk\WechatMessages@seMessage');

