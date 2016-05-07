<?php
use Ecdo\Tower\Migration\TowerMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 微信配置表
 * 
 * @package 
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class Wechat extends TowerMigration
{
    /**
     * 获取表名
     * 
     * @return string
     * @see \Ecdo\Tower\Migration\TowerMigration::getTable()
     */
    public function getTable()
    {
        return 'wechat';
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
            $table->string('appid')->unique(); // 应用ID
            $table->string('appsecret')->unique(); // 应用密钥
            $table->string('url')->unique(); // 服务器地址
            $table->string('token'); // 令牌
            $table->string('encodingAesKey'); // 消息加解密密钥
            $table->string('name')->nullable(); // 名称
            $table->string('micro_signal')->nullable(); // 微信号
            $table->enum('disabled', array('true', 'false'))->default('false');
            $table->timestamps();
        };
        
        return $closure;
    }
}