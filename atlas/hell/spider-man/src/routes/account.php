<?php

/**
 * 商家个人资料设置
 * 
 * @category yunke
 * @package atlas\hell\spider-man\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 个人资料修改
Route::get('/upAccount', '\Ecdo\EcdoSpiderMan\AngelAccount@upAccount');
// 个人资料处理
Route::post('/upAccount', '\Ecdo\EcdoSpiderMan\AngelAccount@upAccountDis');
// 个人资料头像上传
Route::post('/accountUpload', '\Ecdo\EcdoSpiderMan\AngelAccount@accountUpload');


Route::get('/account/edit', '\Ecdo\EcdoSpiderMan\AngelAccount@editAccount');
Route::post('/account/save', '\Ecdo\EcdoSpiderMan\AngelAccount@saveAccount');