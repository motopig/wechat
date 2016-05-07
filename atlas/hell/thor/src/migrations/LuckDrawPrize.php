<?php
use Ecdo\Tower\Migration\TowerMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 抽奖活动奖品表
 * 
 * @package 
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class LuckDrawPrize extends TowerMigration
{
    /**
     * 获取表名
     * 
     * @return string
     * @see \Ecdo\Tower\Migration\TowerMigration::getTable()
     */
    public function getTable()
    {
        return 'luck_draw_prize';
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
            // 奖品类型(0:卡券,1:积分)
            $table->integer('type')->default(0);
            $table->string('content'); // 奖品内容
            $table->integer('chance')->default(0); // 中奖概率
            $table->integer('quantity')->default(1); // 奖品数量
            $table->integer('inventory')->default(0); // 已领取奖品数量
            $table->integer('luck_draw_image_id')->default(0); // 奖品图片
            $table->timestamps();
        };
        
        return $closure;
    }
}