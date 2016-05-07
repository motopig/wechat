<?php
use Ecdo\Tower\Migration\TowerMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 微信关联媒体表
 * 
 * @package 
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class WechatMedia extends TowerMigration
{
    /**
     * 获取表名
     * 
     * @return string
     * @see \Ecdo\Tower\Migration\TowerMigration::getTable()
     */
    public function getTable()
    {
        return 'wechat_media';
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
            $table->string('media_id')->index(); // 微信返回的媒体ID
            // 媒体状态-temporary:临时,permanent:永久
            $table->enum('media_status', array('temporary', 'permanent'))->default('permanent');
            $table->enum('media_type', array('', 'image', 'thumb', 'voice', 'video', 'graphics', 'material'))
            ->default(''); // 类型-image:图片,thumb:缩略图,voice:语音,video:视频,graphics:普通图文,material:高级图文
            $table->integer('f_id')->default(0); // 对应类型的ID (比如图文ID)
            $table->timestamps();
        };
        
        return $closure;
    }
}