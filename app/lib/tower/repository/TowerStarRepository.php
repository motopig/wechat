<?php namespace Ecdo\Tower\Repository;

use Ecdo\Universe\UniverseRepository;
use Ecdo\Model\Tower\TowerStar;

/**
 * @package Ecdo\Tower\Repository
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class TowerStarRepository extends UniverseRepository
{
    /**
     * 商铺唯一识别
     * 
     * @var string
     */
    protected $towerGuid;
    
    /**
     * 构造方法，初始化店铺guid
     * 
     * @param string $towerGuid
     */
    public function __construct($towerGuid)
    {
        $this->towerGuid = $towerGuid;
        $name = 'tower_conn';
        parent::__construct($name);
    }

    /**
     * 创建店铺应用表
     * 
     * @see \Ecdo\Universe\UniverseRepository::createRepository()
     */
    public function createRepository()
    {
        $schema = $this->getConnection()->getSchemaBuilder();

        $schema->create($this->table, function($table)
        {
            $table->increments('id');
            $table->string('star');
            $table->timestamp('expire');
            $table->enum('enabled', ['Y', 'N'])->default('Y');
            $table->timestamps();
            
            $table->index('star');
            $table->index('enabled');
        });
    }
    
    /**
     * 获取模型对应的表名
     * 
     * @return string
     * @see \Ecdo\Universe\UniverseRepository::fetchTable()
     */
    public function fetchTable()
    {
        return TowerStar::getModel()->getTable();
    }
}