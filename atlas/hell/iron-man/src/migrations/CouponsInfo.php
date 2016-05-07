<?php
use Ecdo\Tower\Migration\TowerMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 卡券信息表
 * 
 * @package 
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class CouponsInfo extends TowerMigration
{
    /**
     * 获取表名
     * 
     * @return string
     * @see \Ecdo\Tower\Migration\TowerMigration::getTable()
     */
    public function getTable()
    {
        return 'coupons_info';
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
            $table->integer('coupons_id'); // 卡券券面ID
            $table->string('card_id')->nullable(); // 卡券唯一ID
            $table->string('code')->nullable(); // 卡券code
            $table->text('openid'); // openid
            // 卡券状态(0:领取,1:核销,2:删除)
            $table->integer('status')->default(0);
            $table->timestamps();
        };
        
        return $closure;
    }
}