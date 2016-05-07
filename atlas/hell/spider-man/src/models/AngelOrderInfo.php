<?php
namespace Ecdo\EcdoSpiderMan\Models;

/**
* 订单管理
* description
* package atlas/hell/spider-man/src/models/AngelOrderInfo.php
* date 2015-07-03 11:32:23
* author Hello <hello@no>
* @copyright ECDO. All Rights Reserved.
*/
 

class AngelOrderInfo extends \Eloquent{

    protected $table = 'angel_order_info';

    protected $guarded = array();
    
    
    public function __construct(array $attributes = array()){
        parent::__construct($attributes);
    }
    
    public function angelOrder(){
        return $this->belongsTo('Ecdo\EcdoSpiderMan\Models\AngelOrder');
    }
}
