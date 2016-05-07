<?php

// 会员模块设置
Route::get('/setting', '\Ecdo\EcdoMember\MemberInfo@setting');

// 首页
Route::get('/', '\Ecdo\EcdoMember\MemberInfo@index');
