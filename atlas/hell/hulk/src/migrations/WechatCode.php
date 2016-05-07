<?php
use Ecdo\Tower\Migration\TowerMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 微信二维码表
 * 
 * @package 
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class WechatCode extends TowerMigration
{
    /**
     * 获取表名
     * 
     * @return string
     * @see \Ecdo\Tower\Migration\TowerMigration::getTable()
     */
    public function getTable()
    {
        return 'wechat_code';
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
            $table->string('name')->nullable();
            $table->text('logo')->nullable(); // 二维码图片解析后地址
            $table->integer('use')->default(0); // 用途 - 0:消息,1:核销
            // 动作类型
            // 用途0, 0:文本、1:微信图文、2:高级图文、3:图片、4:语音、5:视频;
            // 用途1, 0:核销注册次数(content, quantity:可注册次数、inventory:已注册次数)
            $table->integer('type')->default(0);
            $table->text('content')->nullable(); // 动作内容
            $table->string('scene_str')->nullable(); // 场景值id(字符串形式:长度限制为1到64)
            $table->integer('scene_id')->default(0); // 场景值id(支持数字1-100000)
            $table->text('action_info')->nullable(); // 二维码详细信息
            $table->text('ticket')->nullable(); // 二维码ticket
            $table->text('url')->nullable(); // 二维码图片解析后地址
            $table->enum('disabled', array('true', 'false'))->default('false');
            $table->timestamps();
        };
        
        return $closure;
    }
}