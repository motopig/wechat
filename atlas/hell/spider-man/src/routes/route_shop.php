<?php
//商店
Route::get('/shop', '\Ecdo\EcdoSpiderMan\AngelShop@index');

//商店
Route::get('/shop/index/', '\Ecdo\EcdoSpiderMan\AngelShop@index');

//购物车
Route::get('/shop/cart', '\Ecdo\EcdoSpiderMan\AngelShop@cart');

//加入购物车
Route::post('/shop/cart/add', '\Ecdo\EcdoSpiderMan\AngelShop@addCart');

//从购物车删除
Route::post('/shop/cart/delete', '\Ecdo\EcdoSpiderMan\AngelShop@deleteFromCart');

//清空购物车
Route::post('/shop/cart/empty', '\Ecdo\EcdoSpiderMan\AngelShop@emptyCart');


//购买套餐
Route::get('/shop/plan', '\Ecdo\EcdoSpiderMan\AngelShop@buyPlan');
//购买硬件
Route::get('/shop/iot', '\Ecdo\EcdoSpiderMan\AngelShop@buyIot');