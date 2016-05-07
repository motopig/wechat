<?php
use Ecdo\Tower\Migration\TowerMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 卡券券面表
 * 
 * @package 
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class Coupons extends TowerMigration
{
    /**
     * 获取表名
     * 
     * @return string
     * @see \Ecdo\Tower\Migration\TowerMigration::getTable()
     */
    public function getTable()
    {
        return 'coupons';
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
            $table->integer('type')->default(0); // 卡券适用类型(0:云号,1:微信)
            $table->string('card_id')->nullable(); // 卡券编号
            // 卡券类型(通用券:GENERALCOUPON,团购券:GROUPON,折扣券:DISCOUNT,
            // 礼品券:GIFT,代金券:CASH,会员卡:MEMBERCARD,景点门票:SCENICTICKET,
            // 电影票:MOVIE_TICKET,飞机票:BOARDINGPASS,红包:LUCKYMONEY,会议门票:MEETINGTICKET)
            $table->string('coupons_type');
            $table->text('logo_url'); // 商家Logo
            $table->string('brand_name'); // 商家名称(长度:12)
            $table->text('color'); // 卡券颜色
            $table->string('title'); // 卡券标题(长度:12)
            $table->string('sub_title')->nullable(); // 卡券副标题(长度:18)
            $table->string('notice'); // 使用说明(长度:12;一句话描述,展示在首页)
            $table->string('begin_at'); // 开始时间(格式:2015.1.1-2015.1.10)
            $table->string('end_at'); // 结束时间(格式:2015.1.1-2015.1.10)
            $table->string('code_type'); // 销券方式(仅卡券号:CODE_TYPE_TEXT,二维码:CODE_TYPE_QRCODE,条形码:CODE_TYPE_BARCODE)
            $table->string('coupons_setting')->nullable(); // 优惠设置(适用于:折扣券(打折),代金券(减免))
            $table->integer('quantity'); // 库存数量
            $table->integer('inventory'); // 已领取库存数量
            $table->integer('use_limit')->default(1); // 领券限制
            $table->enum('can_share', array('true', 'false'))->default('true'); // 是否可分享(true:可以,false:不可以)
            $table->enum('can_give_friend', array('true', 'false'))->default('true'); // 是否可转赠(true:可以,false:不可以)
            $table->text('default_detail'); // 优惠详情(长度1000)
            $table->text('description'); // 使用须知(长度1000)
            $table->string('service_phone')->nullable(); // 客服电话
            $table->text('location_id_list')->nullable(); // 适用门店(门店ID:1,2,3;all:全部;null:无适用门店)
            $table->string('custom_url_name')->nullable(); // 商户自定义入口名称(长度:5)
            $table->string('custom_url_sub_title')->nullable(); // 商户自定义入口右侧tips(长度:6)
            $table->text('custom_url')->nullable(); // 商户自定义入口跳转外链的地址链接
            $table->string('promotion_url_name')->nullable(); // 营销场景的自定义入口(长度:5)
            $table->string('promotion_url_sub_title')->nullable(); //营销场景自定义入口右侧tips(长度:6)
            $table->text('promotion_url')->nullable(); // 营销场景自定义入口跳转外链的地址链接
            // 卡券状态(0:审核中,1:可投放,2:审核失败)
            $table->integer('status')->default(0);
            $table->text('qrcode')->nullable(); // 卡券二维码
            $table->timestamps();
        };
        
        return $closure;
    }
}