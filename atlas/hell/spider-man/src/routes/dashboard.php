<?php

/**
 * 商家后台控制台路由
 * 
 * @category yunke
 * @package atlas\hell\spider-man\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 首页
Route::get('/', '\Ecdo\EcdoSpiderMan\AngelDashboard@index');

// 创建店铺
Route::get('/crTower', '\Ecdo\EcdoSpiderMan\AngelDashboard@crTower');
// 创建店铺处理
Route::post('/crTower', '\Ecdo\EcdoSpiderMan\AngelDashboard@crTowerDis');

// 了解公众号
Route::get('/weixin', '\Ecdo\EcdoSpiderMan\AngelDashboard@weixin');

//登陆微商城
Route::get('/sylar', '\Ecdo\EcdoSpiderMan\AngelDashboard@sylar');

// 切换店铺
Route::get('chTower/{tower}', '\Ecdo\EcdoSpiderMan\AngelDashboard@chTower');

Route::group(array(
    'before' => 'tower.verify'
), function ()
{
    // 店铺控制台
    Route::get('dashboard', '\Ecdo\EcdoSpiderMan\AngelDashboard@dashboard');
    // 云号配置
    Route::get('towerConfig', '\Ecdo\EcdoSpiderMan\AngelDashboard@towerConfig');
    // 云号配置处理
    Route::post('towerConfigDis', '\Ecdo\EcdoSpiderMan\AngelDashboard@towerConfigDis');
});