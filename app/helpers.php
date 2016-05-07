<?php
/**
* helpers for yunke
* description
* package app/helpers.php
* date 2015-07-08 00:34:22
* author Hello <hello@no>
* @copyright ECDO. All Rights Reserved.
*/
 
function money($amount, $symbol = '￥'){
    return $symbol . money_format('%i', $amount);
}

// 概率算法
function chanceAlgorithm($data = [])
{
	$res = '';

	if (! empty($data)) {
		// 概率数组的总概率精度
	    $sum = array_sum($data); 
	    
	    // 概率数组循环
	    foreach ($data as $k => $v) {
	        $rand = mt_rand(1, $sum);
	        if ($rand <= $v) {
	            $res = $k;
	            break;
	        } else {
	            $sum -= $v;
	        }
	    }

	    unset($data);
	}

    return $res;
}

// 模拟POST
function curlPost($url, $data = [])
{
    if (! function_exists('curl_init')) {
        return '';
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $data = curl_exec($ch);

    if (! $data) {
        error_log(curl_error($ch));
    }
    curl_close($ch);
    
    return $data;
}
