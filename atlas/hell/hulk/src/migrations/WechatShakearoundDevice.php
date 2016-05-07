<?php
use Ecdo\Tower\Migration\TowerMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 微信摇一摇设备表
 * 
 * @package 
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class WechatShakearoundDevice extends TowerMigration
{
    /**
     * 获取表名
     * 
     * @return string
     * @see \Ecdo\Tower\Migration\TowerMigration::getTable()
     */
    public function getTable()
    {
        return 'wechat_shakearound_device';
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
            $table->string('model')->nullable(); // 设备型号
            $table->string('apply_id')->nullable(); // 申请的批次ID
            $table->string('device_id')->nullable(); // 设备编号
            // UUID、major、minor，三个信息需填写完整，若填了设备编号，则可不填此信息。
            $table->string('uuid')->nullable();
            $table->string('major')->nullable();
            $table->string('minor')->nullable();
            // 激活状态，0：未激活，1：已激活（但不活跃），2：活跃
            $table->integer('status')->default(0);
            $table->string('sid')->nullable(); // 门店唯一ID
            // Poi_id 的说明改为：设备关联的门店ID，关联门店后，在门店1KM的范围内有优先摇出信息的机会。
            $table->string('poi_id')->nullable();
            $table->string('comment')->nullable(); // 设备的备注信息，不超过15个汉字或30个英文字母。
            $table->timestamps();
        };
        
        return $closure;
    }
}