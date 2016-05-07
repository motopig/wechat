<?php
use Ecdo\Tower\Migration\TowerMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 卡券核销员表
 * 
 * @package 
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class Verification extends TowerMigration
{
    /**
     * 获取表名
     * 
     * @return string
     * @see \Ecdo\Tower\Migration\TowerMigration::getTable()
     */
    public function getTable()
    {
        return 'verification';
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
            $table->text('openid'); // 核销员
            $table->text('location_id_list'); // 适用门店(门店ID:1,2,3;all:全部;不指定门店:null;)
            $table->text('info')->nullable(); // 核销员信息
            $table->integer('status')->default(0); // 审核状态(0:待审核,1:启用,2:禁用)
            $table->timestamps();
        };
        
        return $closure;
    }
}