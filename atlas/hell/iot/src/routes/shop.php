<?php

//商店首页
Route::get('/shop', '\Ecdo\EcdoIot\IotShop@index');

//商店商品列表
Route::get('/shop/gallery', '\Ecdo\EcdoIot\IotGallery@index');
