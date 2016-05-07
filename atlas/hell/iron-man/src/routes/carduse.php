<?php

/**
 * 卡券核销
 * 
 * @category yunke
 * @package atlas\hell\iron-man\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 核销券列表
Route::get('/carduse', '\Ecdo\EcdoIronMan\Carduses@index');
// 核销券搜索
Route::get('/carduseSearch', '\Ecdo\EcdoIronMan\Carduses@carduseSearch');
// 核销券筛选
Route::get('/carduseFilter', '\Ecdo\EcdoIronMan\Carduses@carduseFilter');
// 核销券筛选处理
Route::post('/carduseFilterDis', '\Ecdo\EcdoIronMan\Carduses@carduseFilterDis');
// 卡券核销
Route::any('/carduseVerification', '\Ecdo\EcdoIronMan\Carduses@carduseVerification');
