<?php
use Ecdo\Tower\Migration\TowerMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 微信自动回复表
 * 
 * @package 
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class WechatAutoReply extends TowerMigration
{
    /**
     * 获取表名
     * 
     * @return string
     * @see \Ecdo\Tower\Migration\TowerMigration::getTable()
     */
    public function getTable()
    {
        return 'wechat_auto_reply';
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
            $table->string('name');
            $table->integer('type')->default(0); // 回复类型 - 0:文本,1:微信图文,2:高级图文,3:图片,4:语音,5:视频
            $table->text('content')->nullable(); // 回复内容
            $table->enum('concern', array('0', '1'))->default('0'); // 关注后自动回复 - 0:否,1:是
            $table->enum('disabled', array('true', 'false'))->default('false');
            $table->timestamps();
        };
        
        return $closure;
    }
}