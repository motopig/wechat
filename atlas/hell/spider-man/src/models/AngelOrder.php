<?php
namespace Ecdo\EcdoSpiderMan\Models;

use Ecdo\EcdoSpiderMan\Models\AngelOrderInfo as AngelOrderInfoModel;
use Illuminate\Support\Facades\DB;

/**
* 订单管理
* description
* package atlas/hell/spider-man/src/models/AngelOrder.php
* date 2015-07-03 11:32:23
* author Hello <hello@no>
* @copyright ECDO. All Rights Reserved.
*/
 

class AngelOrder extends \Eloquent{

    protected $table = 'angel_order';

    protected $guarded = array();
    
    
    public function __construct(array $attributes = array()){
        
        parent::__construct($attributes);
        
    }
    
    public function angelOrderInfo(){
        return $this->hasMany('Ecdo\EcdoSpiderMan\Models\AngelOrderInfo');
    }
    
    public function angelOrderBill(){
        return $this->hasMany('Ecdo\EcdoSpiderMan\Models\AngelOrderBill');
    }
    
    function saveOrder($order){
        if(array_key_exists('order',$order) && array_key_exists('order_info',$order)){
            
            $order_data = $order['order'];
            $order_info_data = $order['order_info'];
            
            $order_ex = mt_rand(1,9);
            
            if($order_data['order_type']=='plan'){
                $order_ex = 2;
            }elseif($order_data['order_type']=='iot'){
                $order_ex = 3;
            }
            
            $order_data['id'] = $order_ex.time().$order_data['angel_id'];
            
            foreach($order_data as $key=>$value){
                $this->$key = $value;
            }
            
            $res = $this->save();
            
            if ($res) {
                $order_id = $this->id;
                $this->infoModel = new AngelOrderInfoModel();
                
                $order_infos = array();
                foreach($order_info_data as $order_info){
                    $order_infos[] = new AngelOrderInfoModel($order_info);
                }
                $rs = $this->find($order_id)->angelOrderInfo()->saveMany($order_infos);
                if($rs){
                    DB::commit();
                    return $order_id;
                }else{
                    DB::rollBack();
                    return $rs;
                }
            } else {
                DB::rollBack();
                return $res;
            }
            
        }
        
        return array('errcode'=>'erorr','errmsg'=>'订单创建失败,请重试');
    }
}
