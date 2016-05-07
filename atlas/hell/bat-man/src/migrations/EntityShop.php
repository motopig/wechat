<?php
use Ecdo\Tower\Migration\TowerMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 门店基本信息表
 * 
 * @package 
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class EntityShop extends TowerMigration
{
    /**
     * 获取表名
     * 
     * @return string
     * @see \Ecdo\Tower\Migration\TowerMigration::getTable()
     */
    public function getTable()
    {
        return 'entity_shop';
    }
    
    /**
     * 获取表结构
     * 
     * @return Closure
     * @see \Ecdo\Tower\Migration\TowerMigration::getTableSchema()
     */
    public function getTableSchema()
    {
        $closure = function(Blueprint $table) {
            $table->increments('id');
            $table->string('sid')->unique(); // 门店唯一ID
            $table->string('business_name'); // 门店名
            $table->string('branch_name')->nullable(); // 分店名
            $table->string('province'); // 省份
            $table->string('city'); // 城市
            $table->string('district'); // 地区
            $table->string('address'); // 地址
            $table->text('latitude')->nullable(); // 纬度(2位)
            $table->text('longitude')->nullable(); // 经度(3位)
            $table->text('categories'); // 门店类目 (逗号分割, 其中第一个为主类目)
            $table->enum('disabled', array('true', 'false'))->default('false');
            $table->timestamps();
        };
        
        return $closure;
    }
}