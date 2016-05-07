<?php namespace Ecdo\Model\Universe;

/**
 * 平台应用包模型类
 * 
 * @package Ecdo\Model\Universe
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class UniverseStar extends \Eloquent
{
    /**
     * 表名
     * 
     * @var string
     */
    protected $table = 'universe_star';
    
    protected $connection = 'mysql';
    
    /**
     * @var string
     */
    protected $guarded = ['id'];
    
}
