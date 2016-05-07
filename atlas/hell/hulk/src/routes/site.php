<?php

/**
 * 微信前台路由
 * 
 * @category yunke
 * @package atlas\hell\hulk\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 图文预览
Route::any('/graphics/{id}', '\Ecdo\EcdoHulk\WechatSite@graphics');
