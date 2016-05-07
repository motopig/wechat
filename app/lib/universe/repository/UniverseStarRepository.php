<?php namespace Ecdo\Universe\Repository;

use Ecdo\Model\Universe\UniverseStar;
use Ecdo\Universe\UniverseRepository;

/**
 * 平台应用数据表类
 * 
 * @package Ecdo\Universe\Atlas
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class UniverseStarRepository extends UniverseRepository
{
    
    /**
     * 创建平台应用表
     */
    public function createRepository()
    {
        $schema = $this->getConnection()->getSchemaBuilder();
        
        $schema->create($this->table, function($table)
        {
            // 表字段
            $table->increments('id');
            $table->string('star');
            $table->string('title');
            $table->text('desc');
            $table->string('author');
            $table->string('company', 1000);
            $table->decimal('price', 8, 2)->default(0);
            $table->enum('type', ['base', 'option'])->default('base');
            $table->enum('on_sale', ['Y', 'N'])->default('N');
            $table->string('conflict', 1000);
            $table->string('depend', 1000);
            $table->timestamps();
            
            // 表索引
            $table->index('star');
            $table->index('type');
            $table->index('on_sale');
        });
    }

    /**
     * 获取数据表名
     * 
     * @return string
     */
    public function fetchTable()
    {
        return UniverseStar::getModel()->getTable();
    }
}
