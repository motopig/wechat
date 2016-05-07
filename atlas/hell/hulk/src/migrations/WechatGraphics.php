<?php
use Ecdo\Tower\Migration\TowerMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 微信普通图文表
 * 
 * @package 
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class WechatGraphics extends TowerMigration
{
    /**
     * 获取表名
     * 
     * @return string
     * @see \Ecdo\Tower\Migration\TowerMigration::getTable()
     */
    public function getTable()
    {
        return 'wechat_graphics';
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
            $table->integer('f_id')->default(0)->index(); // 多图文的父级图文ID，0默认为单图文
            $table->enum('type', array('', '1', '2'))->default(''); // 1:单图文, 2:多图文
            $table->string('title'); // 标题
            $table->integer('store_image_id')->default(0); // 图片ID
            $table->string('thumb_media_id')->nullable(); // 图文消息的封面图片素材id（必须是永久mediaID）
            $table->string('author')->nullable(); // 作者
            $table->text('digest')->nullable(); // 图文消息的摘要，仅有单图文消息才有摘要，多图文此处为空
            $table->enum('show_cover_pic', array('0', '1'))->default('0'); // 是否显示封面，0为false，即不显示，1为true，即显示
            $table->text('content'); // 图文消息的具体内容，支持HTML标签，必须少于2万字符，小于1M，且此处会去除JS
            $table->text('content_source_url')->nullable(); // 图文消息的原文地址，即点击“阅读原文”后的URL
            $table->timestamps();
        };
        
        return $closure;
    }
}