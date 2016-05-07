<?php
use Ecdo\Tower\Migration\TowerMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 卡券核销记录表
 * 
 * @package 
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class Carduse extends TowerMigration
{
    /**
     * 获取表名
     * 
     * @return string
     * @see \Ecdo\Tower\Migration\TowerMigration::getTable()
     */
    public function getTable()
    {
        return 'carduse';
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
            $table->integer('type')->default(0); // 核销方式(0:手机核销,1:网页核销)
            $table->integer('coupons_id'); // 卡券ID
            $table->string('card_id'); // 卡券编号
            $table->string('code'); // 卡券券号
            $table->string('location_id_list')->nullable(); // 适用门店
            $table->text('openid')->nullable(); // 核销操作人
            $table->text('info')->nullable(); // 卡券信息
            $table->string('price')->nullable(); // 实收金额
            $table->timestamps();
        };
        
        return $closure;
    }
}