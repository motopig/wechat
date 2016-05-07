<?php

/**
 * 店铺语音路由配置
 * 
 * @category yunke
 * @package atlas\hell\super-man\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 语音首页
Route::get('/voice', '\Ecdo\EcdoSuperMan\StoreVoices@index');
// 创建语音
Route::get('/voice/crVoice', '\Ecdo\EcdoSuperMan\StoreVoices@crVoice');
// 创建语音处理
Route::post('/voice/crVoiceDis', '\Ecdo\EcdoSuperMan\StoreVoices@crVoiceDis');
// 编辑语音
Route::get('/voice/upVoice', '\Ecdo\EcdoSuperMan\StoreVoices@upVoice');
// 编辑语音处理
Route::post('/voice/upVoiceDis', '\Ecdo\EcdoSuperMan\StoreVoices@upVoiceDis');
// 删除语音
Route::get('/voice/deVoice', '\Ecdo\EcdoSuperMan\StoreVoices@deVoice');
// 批量删除语音
Route::get('/voice/drVoice', '\Ecdo\EcdoSuperMan\StoreVoices@drVoice');
// 搜索语音
Route::get('/voice/seVoice', '\Ecdo\EcdoSuperMan\StoreVoices@seVoice');