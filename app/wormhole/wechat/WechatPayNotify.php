<?php
namespace App\Wormhole;

use Ecdo\EcdoSpiderMan\SiteCommon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Lib\RouteCommon;
use Illuminate\Support\Facades\DB;
use Ecdo\EcdoSpiderMan\Models\AngelOrderBill as AngelOrderBillModel;

require_once app_path()."/lib/wormhole/wechat/pay/WxPay.Api.php";
require_once app_path()."/lib/wormhole/wechat/pay/WxPay.Notify.php";
class WxPayNotifi extends \WxPayNotify{
    
	//模式二 查询订单
	public function Queryorder($transaction_id)
	{
		$input = new \WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = \WxPayApi::orderQuery($input);
        
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//模式二 重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
        if($data['result_code']=='FAIL'){
            if(array_key_exists('err_code',$data) && array_key_exists('err_code_des',$data)){
    			$msg = $data['err_code'].$data['err_code_des'];
    			return false;
            }
        }
        
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
        
        //根据拿到的数据进行设置订单状态和支付单状态
        $wechat_pay_notify = new WechatPayNotify();
        $wechat_pay_notify->saveWechatPay($data);
        
		return true;
	}
    
    
}


class WechatPayNotify extends \Controller{
    
    //模式二-callback
    function nativePayCallback(){
        $this->WxPayNotifi = new WxPayNotifi();
        $this->WxPayNotifi->Handle(true);
    }
    
    
    function saveWechatPay($data){
        
        //1_orderID,2_billID
        $out_trade_no = explode('_',$data['out_trade_no']);
        $order_id = $out_trade_no[0];
        $bill_id = $out_trade_no[1];
        
        //微信支付存储
        $wechat_payment = array();
        $wechat_payment['angel_order_id'] = $order_id;
        $wechat_payment['appid'] = $data['appid'];
        $wechat_payment['mch_id'] = $data['mch_id'];
        if(array_key_exists('device_info',$data)){
            $wechat_payment['device_info'] = $data['device_info'];
        }
        $wechat_payment['openid'] = $data['openid'];
        $wechat_payment['is_subscribe'] = $data['is_subscribe'];
        $wechat_payment['trade_type'] = $data['trade_type'];
        $wechat_payment['bank_type'] = $data['bank_type'];
        $wechat_payment['total_fee'] = $data['total_fee'];
        if(array_key_exists('fee_type',$data)){
            $wechat_payment['fee_type'] = $data['fee_type'];
        }
        $wechat_payment['cash_fee'] = $data['cash_fee'];
        if(array_key_exists('cash_fee_type',$data)){
            $wechat_payment['cash_fee_type'] = $data['cash_fee_type'];
        }
        if(array_key_exists('coupon_fee',$data)){
            $wechat_payment['coupon_fee'] = $data['coupon_fee'];
        }
        if(array_key_exists('coupon_count',$data)){
            $wechat_payment['coupon_count'] = $data['coupon_count'];
        }
        $wechat_payment['coupon_batch_id'] = 0;
        
        foreach($data as $k=>$v){
            if(substr($k,0,9)=='coupon_id_'){
                $wechat_payment['coupon_id'] = $v;
            }
            if(substr($k,0,10)=='coupon_fee_'){
                $wechat_payment['coupon_fee'] = $v;
            }
        }
        $wechat_payment['transaction_id'] = $data['transaction_id'];
        $wechat_payment['time_end'] = date('Y-m-d H:i:s',strtotime($data['time_end']));
        $wechat_payment['created_at'] = date('Y-m-d H:i:s',time());
        
        
        $order_payment = array(
            'order_id'=>$order_id,
            'order_status'=>'paid',
            'bill_id'=>$bill_id,
            'pay_at'=>$wechat_payment['time_end'],
            'bank_type'=>$data['bank_type'],
            'pay_method_id'=>$data['transaction_id'],
            'pay_status'=>'SUCCESS'
        );
        $order_payment['pay_coupon'] = 0;
        if(array_key_exists('coupon_fee',$data)){
            $order_payment['pay_coupon'] = $data['coupon_fee'];
        }
        
        $order_bill_model = new AngelOrderBillModel();
        $order_bill_model->updateWechatPay($wechat_payment);
        
        $order_obj = new Ecdo\EcdoSpiderMan\AngelOrder();
        $order_obj->payComplete($order_payment);
    }
}
