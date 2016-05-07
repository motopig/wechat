<?php
namespace Ecdo\EcdoSpiderMan\Models;

/**
* 微信支付状态回调
* description
* package atlas/hell/spider-man/src/models/AngelOrderPayWechat.php
* date 2015-07-03 11:32:23
* author Hello <hello@no>
* @copyright ECDO. All Rights Reserved.
*/
 

class AngelOrderPayWechat extends \Eloquent{

    protected $table = 'angel_order_pay_wechat';
    
    public $timestamps = false;
    
    protected $guarded = array();
    
    
    public function __construct(array $attributes = array()){
        parent::__construct($attributes);
    }
    
    public function angelOrder(){
        return $this->belongsTo('Ecdo\EcdoSpiderMan\Models\AngelOrder');
    }
}
