<?php

/**
 * 商家后台微信会员路由
 * 
 * @category yunke
 * @package atlas\hell\hulk\src\routes
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 会员首页
Route::get('/member', '\Ecdo\EcdoHulk\WechatMembers@index');
// 查看会员
Route::get('/member/shMember', '\Ecdo\EcdoHulk\WechatMembers@shMember');
// 搜索会员
Route::get('/member/seMember', '\Ecdo\EcdoHulk\WechatMembers@seMember');
// 筛选会员
Route::get('/member/fiMember', '\Ecdo\EcdoHulk\WechatMembers@fiMember');
// 筛选会员处理
Route::any('/member/fiMemberDis', '\Ecdo\EcdoHulk\WechatMembers@fiMemberDis');
