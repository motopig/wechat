<?php

/**
 * 幸运大抽奖配置
 * 
 * @category yunke
 * @package atlas\hell\thor\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 抽奖活动列表
Route::get('/luckdraw', '\Ecdo\EcdoThor\LuckDraws@index');
// 抽奖活动搜索
Route::get('/luckdrawSearch', '\Ecdo\EcdoThor\LuckDraws@luckdrawSearch');
// 创建抽奖活动
Route::get('/luckdrawCreate', '\Ecdo\EcdoThor\LuckDraws@luckdrawCreate');
// 创建抽奖活动处理
Route::post('/luckdrawCreateDis', '\Ecdo\EcdoThor\LuckDraws@luckdrawCreateDis');
// 编辑抽奖活动
Route::get('/luckdrawUpdate', '\Ecdo\EcdoThor\LuckDraws@luckdrawUpdate');
// 编辑抽奖活动处理
Route::post('/luckdrawUpdateDis', '\Ecdo\EcdoThor\LuckDraws@luckdrawUpdateDis');
// 删除抽奖活动
Route::any('/luckdrawDelete', '\Ecdo\EcdoThor\LuckDraws@luckdrawDelete');
