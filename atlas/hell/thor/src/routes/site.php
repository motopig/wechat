<?php

// 抽奖页(含网页授权)
Route::get('/wheel/{sid}', '\Ecdo\EcdoThor\Wheel@lucky');
// 抽奖结果处理
Route::any('/wheelResult', '\Ecdo\EcdoThor\Wheel@wheelResult');
