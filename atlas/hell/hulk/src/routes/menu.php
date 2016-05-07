<?php
/**
 * 商家后台微信路由配置
 *
 * @category yunke
 * @package atlas\hell\hulk\src\routes
 * @author Ecdo<dev@no>
 * @copyright © ECDO, Inc. All rights reserved.
 *
 */

Route::get('/menu', '\Ecdo\EcdoHulk\WechatMenus@index');

Route::post('/menu/toEdit', '\Ecdo\EcdoHulk\WechatMenus@toEdit');

Route::get('/menu/getInfo', '\Ecdo\EcdoHulk\WechatMenus@getInfo');