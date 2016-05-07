<?php

//我的硬件
Route::get('/mine', '\Ecdo\EcdoIot\IotMine@index');

//我的分享
Route::get('/mine/share', '\Ecdo\EcdoIot\IotMine@share');
