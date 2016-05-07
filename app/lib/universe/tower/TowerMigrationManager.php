<?php namespace Ecdo\Tower\Migration;

use Closure;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Ecdo\Universe\TowerUtils;
use Ecdo\Atlas\AtlasUtils;

/**
 * 店铺Migration的管理类
 * 
 * @package Ecdo\Tower\Migration
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class TowerMigrationManager
{
    /**
     * @var \Illuminate\Database\ConnectionResolverInterface
     */
    protected $resolver = null;
    
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $fs = null;
    
    /**
     * @var string
     */
    protected $connection = null;
    
    /**
     * @var string
     */
    protected $atlasPath = null;
    
    /**
     * @var string
     */
    protected $pattern = 'src/migrations/*.php';
    
    /**
     * @var sting
     */
    protected $towerGuid = '';
    
    /**
     * 初始构造
     */
    public function __construct($towerGuid)
    {
        $this->resolver = app('db');
        $this->fs = app('files');
        $this->atlasPath = AtlasUtils::getAtlasPath();
        
        $this->setTowerGuid($towerGuid);
    }
    
    /**
     * 执行表结构生成
     * 
     * @param string $star
     */
    public function migrateStar($star)
    {
        $files = $this->scan($star);
        
        foreach ((array) $files as $file) {
            $this->fs->requireOnce($file);
            $arr = explode('/', substr($file, 0, -4));
            $cls = array_pop($arr);
            $cls = studly_case($cls);
            
            if (class_exists($cls)) {
                // 需要实现getTable和getTableSchema方法
                $obj = new $cls;
                $table = $this->getTowerTable($obj->getTable());

                $closure = $obj->getTableSchema();
                if ($this->tableExists($table)) {
                    $this->changeTable($table, $closure);
                } else {
                    $this->createTable($table, $closure);
                }
            }
        }
    }
    
    /**
     * 设置店铺唯一识别
     * 
     * @param string $towerGuid
     */
    public function setTowerGuid($towerGuid)
    {
        $this->towerGuid = $towerGuid;
    }
    
    /**
     * 获取店铺表名
     * 
     * @param string $table
     * @return string
     */
    protected function getTowerTable($table)
    {
        TowerUtils::setTowerGuid($this->towerGuid);
        return TowerUtils::genTowerTable($table);
    }
    
    /**
     * 判断表是否存在
     * 
     * @param string $table
     */
    protected function tableExists($table)
    {
        return $this->getConnection()->getSchemaBuilder()->hasTable($table);
    }
    
    /**
     * 创建数据表
     * 
     * @param string $table
     * @param Closure $closure
     */
    protected function createTable($table, Closure $closure)
    {
        $this->getConnection()->getSchemaBuilder()->create($table, $closure);
    }
    
    /**
     * 修改数据表
     * 
     * @param string $table
     * @param Closure $closure
     */
    protected function changeTable($table, Closure $closure)
    {
        //$this->getConnection()->getSchemaBuilder()->table($table, $closure);
    }
    
    /**
     * 获取migration文件路径
     * 
     * @param string $star
     * @return array
     */
    protected function scan($star)
    {
        $path = $this->atlasPath . '/' . $star . '/' . $this->pattern;
        $files = $this->fs->glob($path);
        
        return $files;
    }
    
    /**
     * 获取数据库连接对象
     */
    protected function getConnection()
    {
        return $this->resolver->connection($this->connection);
    }
    
    /**
     * 设置数据库连接
     * 
     * @param string $name
     */
    public function setConnection($name)
    {
        if (! empty($name))
        {
            $this->resolver->setDefaultConnection($name);
        }
        
        $this->connection = $name;
    }
}