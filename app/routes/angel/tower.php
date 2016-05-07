<?php
Route::group([
    'before' => [
        'angel.auth'
    ],
    'prefix' => 'angel',
    'namespace' => '\Ecdo\Tower'
], function () {
    // 获取菜单
    Route::get('menu', 'TowerController@getMenus');
});
Route::group([
    'before' => [
        'angel.auth',
        'tower.verify'
    ],
    'prefix' => 'angel',
    'namespace' => '\Ecdo\Tower'
], function () {
    
    
    // 店铺角色
    Route::get('role', 'RoleController@index');
    Route::get('role/add', 'RoleController@add');

    // 用户角色
    Route::get('role/users', 'RoleUserController@index');

    // 需要进行csrf过滤
    Route::group([
        'before' => 'csrf',
        'prefix' => 'role'
    ], function() {
        // 角色添加修改
        Route::post('doAdd', 'RoleController@doAdd');
        Route::get('del', 'RoleController@del');
        Route::get('detail', 'RoleController@detail');
        Route::get('edit', 'RoleController@edit');
        Route::post('doEdit', 'RoleController@doEdit');

        // 用户角色编辑
        Route::get('users/edit', 'RoleUserController@edit');
        Route::post('users/doEdit', 'RoleUserController@doEdit');
    });
});

/**
 * 店铺应用中心路由
 */
// 需要先登录，并且选择店铺
Route::group([
    'before' => [
        'angel.auth',
        'tower.verify'
    ],
    'prefix' => 'angel/appCenter',
    'namespace' => '\Ecdo\Tower'
], function () {
    Route::get('', 'AppCenterController@index');
    
    Route::get('install', [
        'before' => 'csrf',
        'uses' => 'AppCenterController@install'
    ]);

    Route::any('replace', 'AppCenterController@replace');
});
