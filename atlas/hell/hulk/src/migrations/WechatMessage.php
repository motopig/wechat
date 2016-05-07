<?php

use Ecdo\Tower\Migration\TowerMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 微信用户消息表
 *
 * @package
 * @author Dev<Dev@no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class WechatMessage extends TowerMigration
{
    /**
     * 获取表名
     *
     * @return string
     * @see \Ecdo\Tower\Migration\TowerMigration::getTable()
     */
    public function getTable()
    {
        return 'wechat_message';
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
            $table->integer('member_id')->index();
            $table->integer('op_id');
            $table->integer('mold')->default(0);//对应消息类型 0=>消息 1=>事件
            //0: 0=>text 1=>image 2=>voice 3=>video 4=>shortvideo 5=>location 6=>link 7=>graphics
            //1: 0=>subscribe 1=>unsubscribe 2=>scan 3=>location 4=>click 5=>view
            $table->integer('type')->default(0);
            $table->integer('cat')->default(0);//对应消息分类 0=>所有消息 1=>未接待 2=>备注 3=>风险客户 4=>自动触发回复
            $table->text('content');
            $table->integer('create_time');
            $table->enum('disabled', array('true', 'false'))->default('false');
            $table->timestamps();
        };

        return $closure;
    }
}