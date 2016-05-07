<?php
namespace Ecdo\Universe;

/**
 * 店铺模型基类
 * 
 * @package Ecdo\Universe
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class TowerModel extends \Eloquent
{
    
    /**
     * 店铺唯一识别
     * 
     * @var string
     */
    protected $towerGuid;
    
    /**
     * 原始表名
     *
     * @var string
     */
    protected $origTable;
    
    /**
     * 调用父类方法，并设置店铺Eloquent表关联
     * 
     * @param array $attributes
     */
    public function __construct(array $attributes = array())
    {
        if (empty($this->origTable)) {
            $this->origTable = $this->table;
        }
        $this->getConnection();
        $this->fetchTowerGuid();
        parent::__construct($attributes);
    }
    
    /**
     * 从缓存中获取店铺唯一识别
     */
    protected function fetchTowerGuid()
    {
        $towerGuid = TowerUtils::fetchTowerGuid();

        if (! empty($towerGuid)) {
            $this->towerGuid = $towerGuid;
            $this->setTowerTable();
        }
    }
    
    /**
     * 设置店铺唯一识别
     * 
     * @param string $towerGuid
     * @return object
     */
    public function setTowerGuid($towerGuid = '')
    {
        if (empty($towerGuid)) {
            $this->towerGuid = TowerUtils::fetchTowerGuid();
        } else {
            $this->towerGuid = $towerGuid;
        }
        
        if (! empty($this->towerGuid)) {
            $this->setTowerTable();
        }
        
        return $this;
    }
    
    /**
     * 设置店铺表名
     */
    protected function setTowerTable()
    {
        TowerUtils::setTowerGuid($this->towerGuid);
        $table = TowerUtils::genTowerTable($this->origTable);
        $this->setTable($table);
    }
    
    /**
     * 进行判断店铺唯一识别是否存在，再进行返回表名
     * 
     * @return boolean|string
     * @see \Illuminate\Database\Eloquent\Model::getTable()
     */
    public function getTable()
    {
        if (empty($this->towerGuid)) {
            return false;
        } else {
            return parent::getTable();
        }
    }


    //切换店铺数据库连接
    public function getConnection()
    {
        \Ecdo\Universe\TowerDB::useConnTower();

        return static::resolveConnection(\Config::get('database.default'));
    }
}