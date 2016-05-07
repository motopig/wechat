<?php
use Ecdo\Tower\Migration\TowerMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 抽奖活动记录表
 * 
 * @package 
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class LuckDrawLog extends TowerMigration
{
    /**
     * 获取表名
     * 
     * @return string
     * @see \Ecdo\Tower\Migration\TowerMigration::getTable()
     */
    public function getTable()
    {
        return 'luck_draw_log';
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
            $table->integer('luck_draw_id'); // 抽奖活动ID
            // 奖品类型(null:未中奖,0:卡券,1:积分)
            $table->string('type')->nullable();
            $table->string('content')->nullable(); // 奖品内容
            $table->text('openid'); // 抽奖会员openid
            $table->timestamps();
        };
        
        return $closure;
    }
}