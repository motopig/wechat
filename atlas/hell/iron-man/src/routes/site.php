<?php

/**
 * 卡券前台路由
 * 
 * @category yunke
 * @package atlas\hell\iron-man\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 卡券核销登录校验
Route::any('/card/verification/{data}/{config?}', '\Ecdo\EcdoIronMan\CouponsSite@verification');
// 卡券核销员注册
Route::any('/card/verificationDis', '\Ecdo\EcdoIronMan\CouponsSite@verificationDis');
// 卡券核销记录
Route::any('/card/carduseLog', '\Ecdo\EcdoIronMan\CouponsSite@carduseLog');
// 卡券核销搜索记录
Route::any('/card/carduseLogSearch', '\Ecdo\EcdoIronMan\CouponsSite@carduseLogSearch');
// 卡券核销
Route::any('/card/carduse', '\Ecdo\EcdoIronMan\CouponsSite@carduse');
// 卡券核销处理
Route::any('/card/carduseDis', '\Ecdo\EcdoIronMan\CouponsSite@carduseDis');
// 卡券微信js扫描
Route::any('/card/wxjsQrcode', '\Ecdo\EcdoIronMan\CouponsSite@wxjsQrcode');
// 卡券列表
Route::any('/card/codePage', '\Ecdo\EcdoIronMan\CouponsSite@codePage');
// 卡券券面
Route::any('/card/codeInfo/{code}/{action}', '\Ecdo\EcdoIronMan\CouponsSite@codeInfo');
// 生成卡券二维码或条形码图片
Route::get('/card/codeImage/{code_type}/{code}', '\Ecdo\EcdoIronMan\CouponsSite@codeImage');
// 卡券详情
Route::any('/card/codeContent/{data}', '\Ecdo\EcdoIronMan\CouponsSite@codeContent');
// 领取卡券
Route::any('/card/codeReceive/{id}/{oauth2?}', '\Ecdo\EcdoIronMan\CouponsSite@codeReceive');
// 领取卡券处理
Route::any('/card/codeReceiveDis', '\Ecdo\EcdoIronMan\CouponsSite@codeReceiveDis');
