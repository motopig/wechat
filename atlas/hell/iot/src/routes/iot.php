<?php
/**
* 智能硬件路由
* description
* package atlas/hell/iot/src/routes/iot.php
* date 2015-06-22 18:46:16
* author Hello <hello@no>
* @copyright ECDO. All Rights Reserved.
*/
 

// 商家后台智能硬件模块
Route::group(array(
    'before' => ['angel.auth'],
    'prefix' => 'angel/iot'
), function (){

    //商店首页
    Route::get('/', '\Ecdo\EcdoIot\IotShop@index');

});


// 商家后台智能硬件模块
Route::group(array(
    'before' => ['angel.auth', 'tower.verify'],
    'prefix' => 'angel/iot'
), function ()
{
    
    Route::get('/wifi', '\Ecdo\EcdoIot\IotWifi@index');
    
	// 硬件商店
	include 'shop.php';
	// 我的硬件
	include 'mine.php';
    
});
