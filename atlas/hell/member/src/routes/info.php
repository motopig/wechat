<?php

/**
 * 会员列表相关路由器
 * 
 * @category yunke
 * @package atlas\hell\member\src\routes
 * @author Hello<hello@no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 会员列表
Route::get('/index', '\Ecdo\EcdoMember\MemberInfo@index');
// 创建会员
Route::get('/create', '\Ecdo\EcdoMember\MemberInfo@create');
// 编辑会员
Route::get('/edit', '\Ecdo\EcdoMember\MemberInfo@edit');

// 查看会员资料
Route::get('/profile', '\Ecdo\EcdoMember\MemberInfo@profile');

// 查看会员扩展资料
Route::get('/extend', '\Ecdo\EcdoMember\MemberInfo@extend');
// 会员关联资料
Route::get('/relate', '\Ecdo\EcdoMember\MemberInfo@relate');
// 会员资料合并
Route::get('/merge', '\Ecdo\EcdoMember\MemberInfo@merge');
