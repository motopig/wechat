<?php
namespace Ecdo\Universe\Cache;

/**
 * Cache行为标准
 * 
 * @package Ecdo\Universe\Cache
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
interface CacheBehavior
{
    /**
     * 存储缓存
     * 
     * @param string $key
     * @param mixed $val
     * @param int $min
     * @return void
     */
    public function store($key, $val, $min = 60);
    
    /**
     * 永久存储缓存
     * 
     * @param string $key
     * @param mixed $val
     * @return void
     */
    public function forever($key, $val);
    
    /**
     * 获取指定缓存值
     * 
     * @param string $key
     * @return mixed
     */
    public function fetch($key);
    
    /**
     * 移除指定缓存值
     * 
     * @param string $key
     * @return void
     */
    public function forget($key);
}