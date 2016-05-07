<?php

/**
 * 微信平台接口路由配置
 * 
 * @category yunke
 * @package app/routes/wormhole
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 微信消息接收入口(any：同时接收get与post数据)
Route::any('{tower}/wormhole/wechat', '\App\Wormhole\WechatAction@index');

// 开放平台
// 发起授权页的体验
Route::any('openx/wx_oauth', '\App\Lib\WechatOpenx@wxOauth');
// 授权事件接收
Route::any('openx/wx_oauth_cancel', '\App\Lib\WechatOpenx@wxOauthCancel');
// 公众号消息与事件接收
Route::any('openx/{' . \Config::get('key.wechat.appid') . '}/callback', '\App\Lib\WechatOpenx@wxCallback');
// Route::any('openx/{wx570bc396a51b8ff8}/callback', '\App\Lib\WechatOpenx@wxCallback'); // 测试
// 授权处理
Route::any('openx/wx_oauth_dis', '\App\Lib\WechatOpenx@wxOauthDis');
// oauth2获取openid
Route::any('oauth2/oauth2OpenId', '\App\Lib\WechatOpenx@oauth2OpenId');

Route::get('/openx/wechat_pay/notify/','\App\Wormhole\WechatPayNotify@nativePayCallback');

// 第三方平台微信网页授权登录
Route::any('oauth2/platform/{guid}', '\App\Lib\WechatOpenx@platform');
// 提供给第三方平台微信用户信息
Route::any('oauth2/platformUser', '\App\Lib\WechatOpenx@platformUser');
