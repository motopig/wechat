<?php

/**
 * 总路由配置
 * 
 * @category yunke
 * @package app
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 平台首页
Route::get('/', '\App\Controllers\HomeController@showWelcome');
Route::get('feature', '\App\Controllers\HomeController@showFeature');
Route::get('price', '\App\Controllers\HomeController@showPrice');
Route::get('help', '\App\Controllers\HomeController@showHelp');
Route::get('help/{view}', '\App\Controllers\HomeController@showHelp');

// 微信平台接口路由
include 'routes/wormhole/wechat.php';

// ecstore接口路由
include 'routes/wormhole/ecstore.php';

//工具入口
Route::get('/qrcode/{encoded_url?}', '\App\Controllers\ToolController@qrCode')->where('encoded_url', '.*');

// 平台登录
Route::get('god/login', '\App\Controllers\GodAccountController@getLogin');
Route::post('god/login', '\App\Controllers\GodAccountController@postLogin');

// 平台模块
Route::group(array(
    'before' => 'god.auth',
    'prefix' => 'god'
), function ()
{
    // 基础路由
    include 'routes/god/god.php';
    // 企业路由
    include 'routes/god/territory.php';
});

// 商户平台店铺应用中心
include 'routes/angel/tower.php';


// 错误页面
App::error(function($exception, $code)
{
    $pathInfo = Request::getPathInfo();
    $pathInfos = explode('/',$pathInfo);
    $metas = array(
        'title' => '一点云客 | 移动智能营销平台',
        'keyword'=>'云客,微信摇一摇,智能硬件,微信POS,微信wifi,微信路由器',
        'description'=>'一点云客提供基于微信的移动智能营销服务，包括摇一摇，微信wifi，微信签到，微信调查等多种营销服务。'
    );
    if($code){
        $metas['title'] = $code.' | '.$metas['title'];
    }
    $angel = '';
    if($pathInfos[1]=='angel'){
        $angel = 'angel';
    }
    
    if($angel && Auth::angel()->check()){
        return Redirect::to('angel/errorpage');
    }else{
        switch ($code)
        {
            case 403:
                return Response::view('errors.403', array(), 403)->with(compact('metas'));
            case 404:
                return Response::view('errors.404', array('metas'=>$metas), 404);

            case 500:
                return Response::view('errors.500', array('metas'=>$metas), 500);

            default:
                return Response::view('errors.default', array('metas'=>$metas), $code);
        }
    }
    
});
