<?php
use Ecdo\Tower\Migration\TowerMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 门店微信关联表
 * 
 * @package 
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class EntityShopWechat extends TowerMigration
{
    /**
     * 获取表名
     * 
     * @return string
     * @see \Ecdo\Tower\Migration\TowerMigration::getTable()
     */
    public function getTable()
    {
        return 'entity_shop_wechat';
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
            $table->string('poi_id')->nullable(); // 微信门店唯一ID
            // 审核状态 - 0:未同步,1:审核中,2:审核成功,3:审核失败
            $table->integer('status')->default(0);
            $table->string('msg')->nullable(); // 审核失败理由
            $table->timestamps();
        };
        
        return $closure;
    }
}