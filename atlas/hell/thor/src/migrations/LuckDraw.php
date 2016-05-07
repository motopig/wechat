<?php
use Ecdo\Tower\Migration\TowerMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 抽奖活动设置表
 * 
 * @package 
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class LuckDraw extends TowerMigration
{
    /**
     * 获取表名
     * 
     * @return string
     * @see \Ecdo\Tower\Migration\TowerMigration::getTable()
     */
    public function getTable()
    {
        return 'luck_draw';
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
            $table->string('name'); // 抽奖活动名称
            $table->string('begin_at'); // 开始时间
            $table->string('end_at'); // 结束时间
            $table->integer('nums')->default(1); // 会员参与次数
            $table->string('description')->nullable(); // 抽奖活动说明
            $table->integer('not_chance')->default(50); // 未中奖概率
            $table->string('not_message')->nullable(); // 未中奖说明
            $table->integer('template_id')->default(0); // 抽奖活动模版
            $table->string('grade_id')->nullable(); // 可参与会员等级
            $table->string('point')->nullable(); // 会员消耗积分
            $table->enum('disabled', array('true', 'false'))->default('false'); // 是否启用(false:启用;true:禁用)
            $table->timestamps();
        };
        
        return $closure;
    }
}