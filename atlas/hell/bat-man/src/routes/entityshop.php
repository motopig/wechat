<?php

/**
 * 门店基本配置
 * 
 * @category yunke
 * @package atlas\hell\bat-man\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 门店首页
Route::get('/entityshop', '\Ecdo\EcdoBatMan\EntityShops@index');
// 门店搜索
Route::get('/entityshop/seEntityShop', '\Ecdo\EcdoBatMan\EntityShops@seEntityShop');
// 门店筛选
Route::get('/entityshop/fiEntityShop', '\Ecdo\EcdoBatMan\EntityShops@fiEntityShop');
// 门店筛选处理
Route::post('/entityshop/fiEntityShopDis', '\Ecdo\EcdoBatMan\EntityShops@fiEntityShopDis');
// 创建门店
Route::get('/entityshop/crEntityShop', '\Ecdo\EcdoBatMan\EntityShops@crEntityShop');
// 创建门店处理
Route::post('/entityshop/crEntityShopDis', '\Ecdo\EcdoBatMan\EntityShops@crEntityShopDis');
// 编辑门店
Route::get('/entityshop/upEntityShop', '\Ecdo\EcdoBatMan\EntityShops@upEntityShop');
// 编辑门店处理
Route::post('/entityshop/upEntityShopDis', '\Ecdo\EcdoBatMan\EntityShops@upEntityShopDis');
// 删除门店
Route::any('/entityshop/deEntityShop', '\Ecdo\EcdoBatMan\EntityShops@deEntityShop');
// 附近门店配置
Route::get('/nearbyentityshop', '\Ecdo\EcdoBatMan\EntityShops@nearbyEntityShop');
// 附近门店配置处理
Route::post('/nearbyentityshopDis', '\Ecdo\EcdoBatMan\EntityShops@nearbyentityshopDis');
// 同步门店至微信审核
Route::any('/wechatEntityShop', '\Ecdo\EcdoBatMan\EntityShops@wechatEntityShop');
