<?php
//账单中心
Route::get('/order', '\Ecdo\EcdoSpiderMan\AngelOrder@index');

//账单中心
Route::get('/order/index/', '\Ecdo\EcdoSpiderMan\AngelOrder@index');

//订单详情
Route::get('/order/detail/{order_id?}', '\Ecdo\EcdoSpiderMan\AngelOrder@detail');

//账户充值
Route::get('/order/charge', '\Ecdo\EcdoSpiderMan\AngelOrder@charge');

//生成订单
Route::post('/order/create', '\Ecdo\EcdoSpiderMan\AngelOrder@orderCreate');

//支付
Route::get('/order/pay/{order_id?}', '\Ecdo\EcdoSpiderMan\AngelOrder@pay')->where('id', '[0-9]+');

Route::get('/order/payqrcode/{url?}', '\Ecdo\EcdoSpiderMan\AngelOrder@payQrcode')->where('url', '.*');
Route::get('/order/payqrcodehtml/{url?}', '\Ecdo\EcdoSpiderMan\AngelOrder@payQrcodeHtml')->where('url', '.*');

//发起结算
Route::post('/order/checkout', '\Ecdo\EcdoSpiderMan\AngelOrder@checkout');

//订单列表
Route::get('/order/list', '\Ecdo\EcdoSpiderMan\AngelOrder@list');