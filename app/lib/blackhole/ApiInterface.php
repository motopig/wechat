<?php
namespace App\Lib;

/**
 * API公用接口
 *
 * @category yunke
 * @package app\lib\blackhole
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
interface ApiInterface
{
    // 获取各类接口地址汇总
    public function getApiUri();
    
    // 获取各类接口错误信息汇总
    public function getErrcode();
    
    // 获取各类接口AccessToken
    public function getAccessToken();
    
    // 各类接口验证方法
    public function valid();
    
    // 各类接口消息接收方法
    public function init();
}
