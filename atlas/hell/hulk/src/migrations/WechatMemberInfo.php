<?php
use Ecdo\Tower\Migration\TowerMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 微信会员信息表
 * 
 * @package 
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class WechatMemberInfo extends TowerMigration
{
    /**
     * 获取表名
     * 
     * @return string
     * @see \Ecdo\Tower\Migration\TowerMigration::getTable()
     */
    public function getTable()
    {
        return 'wechat_member_info';
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
            $table->integer('wechat_member_id')->index();
            $table->string('name')->nullable();
            $table->text('head')->nullable();
            $table->string('country')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->enum('gender', array('male', 'female', 'unknown'))->default('unknown');
            $table->string('birthday')->nullable();
            $table->text('brief')->nullable();
            $table->text('unionid')->nullable();
            $table->timestamps();
        };
        
        return $closure;
    }
}