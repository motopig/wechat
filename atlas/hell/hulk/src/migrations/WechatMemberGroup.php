<?php
use Ecdo\Tower\Migration\TowerMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 微信会员组别绑定表
 * 
 * @package 
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class WechatMemberGroup extends TowerMigration
{
    /**
     * 获取表名
     * 
     * @return string
     * @see \Ecdo\Tower\Migration\TowerMigration::getTable()
     */
    public function getTable()
    {
        return 'wechat_member_group';
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
            $table->integer('wechat_group_id')->index();
            $table->timestamps();
        };
        
        return $closure;
    }
}