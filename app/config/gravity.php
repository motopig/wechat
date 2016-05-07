<?php

/**
* config目录文件引入方式
*
* @category yunke
* @package app\config
* @author no<no>
* @copyright © no. All rights reserved.
*/
return array(
	'wechat' => include('wormhole/wechat.php'),
	'alipay' => include('wormhole/alipay.php'),
	'baidu' => include('wormhole/baidu.php'),
	'wallet' => include('wormhole/wallet.php')
);
