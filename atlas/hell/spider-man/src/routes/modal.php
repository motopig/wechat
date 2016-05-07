<?php

/**
 * 公用模态路由器
 * 
 * @category yunke
 * @package atlas\hell\spider-man\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

Route::group(array(
    'before' => 'tower.verify'
), function ()
{
    // 模态框
    Route::any('modal/{type}', '\Ecdo\EcdoSpiderMan\AngelCommon@modal');
    // 模态框预览
	Route::post('modalPreview', '\Ecdo\EcdoSpiderMan\AngelCommon@modalPreview');
});
