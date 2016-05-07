<?php
use Ecdo\Tower\Migration\TowerMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 微信摇一摇页面表
 * 
 * @package 
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class WechatShakearoundPage extends TowerMigration
{
    /**
     * 获取表名
     * 
     * @return string
     * @see \Ecdo\Tower\Migration\TowerMigration::getTable()
     */
    public function getTable()
    {
        return 'wechat_shakearound_page';
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
            $table->string('title'); // 在摇一摇页面展示的主标题,不超过6个字
            $table->string('description'); // 在摇一摇页面展示的副标题,不超过7个字
            // 在摇一摇页面展示的图片(图标);图片需先上传至微信侧服务器,用“素材管理-上传图片素材”接口上传图片,返回的图片URL再配置在此处
            $table->integer('shakearound_material_id')->default(0);
            // 摇一摇类型(0:自定义链接,1:关注,2:卡券,3:微信图文,4:文章,5:幸运大抽奖)
            $table->integer('type')->default(0)->index();
            $table->text('content')->nullable(); // 活动类型
            $table->text('page_url'); // 根据类型设置对应跳转链接
            $table->string('page_id')->nullable(); // 摇一摇周边页面唯一ID(微信返回)
            $table->string('comment')->nullable(); // 页面的备注信息,不超过15个字
            $table->timestamps();
        };
        
        return $closure;
    }
}