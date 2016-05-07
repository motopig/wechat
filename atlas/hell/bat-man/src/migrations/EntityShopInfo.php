<?php
use Ecdo\Tower\Migration\TowerMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 门店服务信息表
 * 
 * @package 
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class EntityShopInfo extends TowerMigration
{
    /**
     * 获取表名
     * 
     * @return string
     * @see \Ecdo\Tower\Migration\TowerMigration::getTable()
     */
    public function getTable()
    {
        return 'entity_shop_info';
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
            $table->integer('entity_shop_id')->index(); // 门店ID
            $table->text('store_image_id')->nullable(); // 店铺图片ID (逗号分割)
            // 微信门店信息体
            $table->string('telephone'); // 电话
            $table->text('photo_list')->nullable(); // 图片列表 (逗号分割)
            $table->string('open_time')->nullable(); // 营业时间
            $table->string('avg_price')->nullable(); // 人均价格
            $table->text('recommend')->nullable(); // 推荐
            $table->text('special')->nullable(); // 特使服务
            $table->text('desc')->nullable(); // 简介
            $table->string('signature')->nullable(); // 门店签名
            $table->timestamps();
        };
        
        return $closure;
    }
}