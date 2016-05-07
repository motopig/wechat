<?php

/**
 * 平台路由配置
 * 
 * @category yunke
 * @package app/routes/god
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 首页
Route::get('/', '\App\Controllers\GodDashboardController@index');

// 登出
Route::get('logout', '\App\Controllers\GodAccountController@getLogout');

// 编辑账户
Route::get('account', '\App\Controllers\GodAccountController@getAccount');
// 编辑账户处理
Route::post('account', '\App\Controllers\GodAccountController@postAccount');

// 应用中心
Route::get('appCenter', '\Ecdo\God\AppCenterController@index');
Route::group([
    'before' => 'csrf',
    'namespace' => '\Ecdo\God'
], function () {
    Route::get('appCenter/show', 'AppCenterController@show');
    Route::post('appCenter/edit', 'AppCenterController@edit');
});
