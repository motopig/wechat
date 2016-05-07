<?php

/**
 * 路由过滤配置
 *
 * @category yunke
 * @package app
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

/*
 * |--------------------------------------------------------------------------
 * | Application & Route Filters
 * |--------------------------------------------------------------------------
 * |
 * | Below you will find the "before" and "after" events for the application
 * | which may be used to do any work before or after a request into your
 * | application. Here you may also register your custom route filters.
 * |
 */
App::before(function ($request)
{
    //
});

App::after(function ($request, $response)
{
    //
});

/*
 * |--------------------------------------------------------------------------
 * | Authentication Filters
 * |--------------------------------------------------------------------------
 * |
 * | The following filters are used to verify that the user of the current
 * | session is logged into this application. The "basic" filter easily
 * | integrates HTTP Basic authentication for quick, simple checking.
 * |
 */

// Route::filter('auth', function()
// {
// if (Auth::guest())
// {
// if (Request::ajax())
// {
// return Response::make('Unauthorized', 401);
// }
// else
// {
// return Redirect::guest('login');
// }
// }
// });

// Route::filter('auth.basic', function()
// {
// return Auth::basic();
// });

/*
 * |--------------------------------------------------------------------------
 * | Guest Filter
 * |--------------------------------------------------------------------------
 * |
 * | The "guest" filter is the counterpart of the authentication filters as
 * | it simply checks that the current user is not logged in. A redirect
 * | response will be issued if they are, which you may freely change.
 * |
 */

// Route::filter('guest', function()
// {
// if (Auth::check()) return Redirect::to('/');
// });

/*
 * |--------------------------------------------------------------------------
 * | CSRF Protection Filter
 * |--------------------------------------------------------------------------
 * |
 * | The CSRF filter is responsible for protecting your application against
 * | cross-site request forgery attacks. If this special token in a user
 * | session does not match the one given in this request, we'll bail.
 * |
 */

// 平台权限验证
Route::filter('god.auth', function ()
{
    if (! Auth::god()->check()) {
        return Redirect::guest('god/login');
    }
});

// 商家权限验证
Route::filter('angel.auth', function ()
{
    if (! Auth::angel()->check()) {
        return Redirect::guest('angel/login');
    }
});

// token验证
Route::filter('csrf', function ()
{
    if (Session::token() !== Input::get('csrf_token')) {
        return Redirect::guest('angel/login')->with('error','请重新登录');
    }
});

// 店铺识别 & 权限控制
Route::filter('tower.verify', function()
{
    // 店铺识别，诺未选店铺，则转至选择店铺页面
    $towerId = Ecdo\Universe\TowerUtils::fetchTowerGuid();
    if (empty($towerId)) {
        return Redirect::to('angel');
    } else {
        Ecdo\Universe\TowerUtils::storeTowerGuid($towerId);
    }
    
    // 获取当前路由，判断用户权限
    $path = Route::current()->getPath();
    $tpm = new Ecdo\Tower\TowerPermissionManager();
    if (! $tpm->chkUserOwnPermByPath($path)) {
        return Response::make('Unauthorized', 401);
    }
});
