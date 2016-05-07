<?php namespace Ecdo\Tower;

use Ecdo\Universe\TowerDB;
use Ecdo\Universe\TowerUtils;
/**
 * @package Ecdo\Tower
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
abstract class TowerRepository
{
    
    /**
     * 数据库执行器
     *
     * @var object
     */
    protected $resolver;
    
    /**
     * 数据库连接
     *
     * @var string
     */
    protected $connection;
    
    /**
     * 数据表名
     * 
     * @var string
     */
    protected $table;
    
    /**
     * 商铺唯一识别
     * 
     * @var string
     */
    protected $towerGuid;

    /**
     * 构造方法初始化
     */
    public function __construct($towerGuid)
    {
        $this->towerGuid = $towerGuid;
        $this->resolver = app('db');
        TowerUtils::setTowerGuid($towerGuid);
        $this->table = TowerUtils::genTowerTable($this->fetchTable());
    }
    
    /**
     * 创建数据表方法
     */
    abstract public function createRepository();
    
    /**
     * 获取数据表名方法
     * 
     * @return string
     */
    abstract public function fetchTable();
    
    /**
     * 判断平台应用表是否存在
     *
     * @return bool
     */
    public function repositoryExists()
    {
        $schema = $this->getConnection()->getSchemaBuilder();
    
        return $schema->hasTable($this->table);
    }
    
    /**
     * Get a query builder for the migration table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function table()
    {
        return $this->getConnection()->table($this->table);
    }
    
    /**
     * Get the connection resolver instance.
     *
     * @return \Illuminate\Database\ConnectionResolverInterface
     */
    public function getConnectionResolver()
    {
        return $this->resolver;
    }
    
    /**
     * Resolve the database connection instance.
     *
     * @return \Illuminate\Database\Connection
     */
    public function getConnection()
    {
        return $this->resolver->connection($this->connection);
    }
    
    /**
     * Set the information source to gather data.
     *
     * @param  string  $name
     * @return void
     */
    public function setSource($name)
    {
        $this->connection = $name;
    }
}