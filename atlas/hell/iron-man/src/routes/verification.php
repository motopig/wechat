<?php

/**
 * 核销员
 * 
 * @category yunke
 * @package atlas\hell\iron-man\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 核销员列表
Route::get('/verification', '\Ecdo\EcdoIronMan\Verifications@index');
// 核销员搜索
Route::get('/verificationSearch', '\Ecdo\EcdoIronMan\Verifications@verificationSearch');
// 核销员筛选
Route::get('/verificationFilter', '\Ecdo\EcdoIronMan\Verifications@verificationFilter');
// 核销员筛选处理
Route::post('/verificationFilterDis', '\Ecdo\EcdoIronMan\Verifications@verificationFilterDis');
// 创建核销员
Route::get('/verificationCreate', '\Ecdo\EcdoIronMan\Verifications@verificationCreate');
// 创建核销员处理
Route::post('/verificationCreateDis', '\Ecdo\EcdoIronMan\Verifications@verificationCreateDis');
// 编辑核销员
Route::get('/verificationUpdate', '\Ecdo\EcdoIronMan\Verifications@verificationUpdate');
// 编辑核销员处理
Route::post('/verificationUpdateDis', '\Ecdo\EcdoIronMan\Verifications@verificationUpdateDis');
// 删除核销员
Route::any('/verificationDelete', '\Ecdo\EcdoIronMan\Verifications@verificationDelete');
