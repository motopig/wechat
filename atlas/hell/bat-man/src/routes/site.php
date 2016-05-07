<?php

/**
 * 门店前台路由
 * 
 * @category yunke
 * @package atlas\hell\bat-man\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 附近门店列表
Route::any('/entityList/{latitude}/{longitude}', '\Ecdo\EcdoBatMan\EntityShopSite@entityList');
// 附近详情
Route::any('/entityDetail/{sid}', '\Ecdo\EcdoBatMan\EntityShopSite@entityDetail');
