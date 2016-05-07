<?php namespace Ecdo\Tower\Migration;

/**
 * 店铺的表migration
 * 
 * @package Ecdo\Tower\Migration
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
abstract class TowerMigration
{
    /**
     * 获取表名
     * 
     * @return string
     */
    abstract public function getTable();
    
    /**
     * 返回表结构
     * 
     * @return Closure
     */
    abstract public function getTableSchema();
    
}