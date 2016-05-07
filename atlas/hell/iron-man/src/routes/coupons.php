<?php

/**
 * 卡券app
 * 
 * @category yunke
 * @package atlas\hell\iron-man\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 卡券基础设置
Route::get('/coupons/setting', '\Ecdo\EcdoIronMan\Coupones@conponSetting');
// 卡券基础设置处理
Route::post('/coupons/settingDis', '\Ecdo\EcdoIronMan\Coupones@conponSettingDis');
// 卡券列表
Route::get('/coupons', '\Ecdo\EcdoIronMan\Coupones@index');
// 卡券搜索
Route::get('/couponSearch', '\Ecdo\EcdoIronMan\Coupones@couponSearch');
// 卡券筛选
Route::get('/couponsFilter', '\Ecdo\EcdoIronMan\Coupones@couponsFilter');
// 卡券筛选处理
Route::post('/couponsFilterDis', '\Ecdo\EcdoIronMan\Coupones@couponsFilterDis');
// 卡券定义
Route::post('/couponsType', '\Ecdo\EcdoIronMan\Coupones@couponsType');
// 卡券适用门店
Route::any('/coupons/store', '\Ecdo\EcdoIronMan\Coupones@conponStore');
// 创建卡券
Route::get('/createCoupons/{type}', '\Ecdo\EcdoIronMan\Coupones@createCoupons');
// 创建卡券处理
Route::post('/createCouponsDis', '\Ecdo\EcdoIronMan\Coupones@createCouponsDis');
// 编辑卡券
Route::get('/updateCoupons/{id}/{type}', '\Ecdo\EcdoIronMan\Coupones@updateCoupons');
// 编辑卡券处理
Route::post('/updateCouponsDis', '\Ecdo\EcdoIronMan\Coupones@updateCouponsDis');
// 删除卡券
Route::any('/couponsDelete', '\Ecdo\EcdoIronMan\Coupones@couponsDelete');
// 投放卡券
Route::any('/couponsDelivery', '\Ecdo\EcdoIronMan\Coupones@couponsDelivery');
