<?php

/**
 * 会员前台路由
 * 
 * @category yunke
 * @package atlas\hell\member\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 会员中心
Route::any('/member/center', '\Ecdo\EcdoMember\MemberSite@center');
// 会员详情
Route::any('/member/info/{openid?}', '\Ecdo\EcdoMember\MemberSite@info');
// 卡券列表
Route::any('/member/card/{openid?}', '\Ecdo\EcdoMember\MemberSite@card');
