<?php

/**
 * ecstore接口路由配置
 * 
 * @category yunke
 * @package app/routes/wormhole
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// ecstore消息接收入口
Route::any('ecstore/index', '\App\Lib\EcstoreOpen@index');
