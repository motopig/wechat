<?php
namespace Ecdo\Universe\Cache;

use Ecdo\Universe\Cache\CacheBehavior;

/**
 * Json方式Cache
 * 
 * @package Ecdo\Universe\Cache
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class JsonCache implements CacheBehavior
{
    
    /**
     * 数据进行编码
     * 
     * @param mixed $data
     * @return string
     */
    protected function encode($data)
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * 数据进行解码
     * 
     * @param string $data
     * @return mixed
     */
    protected function decode($data)
    {
        return json_decode($data, true);
    }
    
    /**
     * 存储缓存，默认存储60分钟
     *
     * @param string $key
     * @param mixed $val
     * @param int $min
     * @return void
     */
    public function store($key, $val, $min = 60)
    {
        $val = $this->encode($val);
        \Cache::put($key, $val, $min);
    }
    
    /**
     * 永久存储缓存
     *
     * @param string $key
     * @param mixed $val
     * @return void
    */
    public function forever($key, $val)
    {
        $val = $this->encode($val);
        \Cache::forever($key, $val);
    }
    
    /**
     * 获取指定缓存值
     *
     * @param string $key
     * @return mixed
    */
    public function fetch($key)
    {
        $data = \Cache::get($key);
        if (! empty($data)) {
           $data = $this->decode($data);
        }
        
        return $data;
    }
    
    /**
     * 移除指定缓存值
     *
     * @param string $key
     * @return void
    */
    public function forget($key)
    {
        \Cache::forget($key);
    }
}