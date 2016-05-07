<?php

/**
 * 第三方api百度地址匹配
*
* @category yunke
* @package app\config\wormhole
* @author no<no>
* @copyright © no. All rights reserved.
*/
return array(
    
    // 百度开放云平台
    'url' => array(
        // 百度地图请求地址 (ak：百度开放平台开发者ID)
        'map' => 'http://api.map.baidu.com/components',

        // 查询天气预报
        'getWeather' => 'http://api.map.baidu.com/telematics/v3/weather'
    )
);
