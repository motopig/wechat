<?php
namespace App\Wormhole;

use Ecdo\EcdoSpiderMan\SiteCommon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Lib\RouteCommon;
use Illuminate\Support\Facades\DB;

/**
* 微信支付
* description
* package app/wormhole/wechat/WchatPay.php
* date 2015-07-07 16:25:02
* author Hello <hello@no>
* @copyright ECDO. All Rights Reserved.
*/
 

class WechatPay extends SiteCommon{
    
    function __construct(){
        parent::__construct();
        require_once app_path()."/lib/wormhole/wechat/pay/WxPay.Api.php";
    }
    
    //模式一    
    function nativeAPay(){
        //#TODO 模式一和模式二区别除了二维码的有效期，模式一更复杂
    }    
    
    //模式二
    function nativePay($data,&$msg){
        
        $body = $data['body'];
        $attach = '';
        if(array_key_exists('attach',$data)){
            $attach = $data['attach'];
        }
        
        $detail = $data['detail'];
        $out_trade_no = $data['out_trade_no'];
        $fee_type = 'CNY';
        $total_fee = $data['total_fee'];//分
        
        $goods_tag = 'yunke';
        if(array_key_exists('goods_tag',$data)){
            $goods_tag = $data['goods_tag'];
        }
        
        $product_id = $data['product_id'];
        $openid = '';//native状态下不用传
        
        $notify_url = \URL::to('/openx/wechat_pay/notify/');
        $trade_type = 'NATIVE';
        $time_start = date("YmdHis");
        $time_expire = date("YmdHis", time() + 600);
        
        $input = new \WxPayUnifiedOrder();
        $input->SetBody($body);
        $input->SetAttach($attach);
        $input->SetDetail($detail);
        $input->SetOut_trade_no($out_trade_no);
        $input->SetTotal_fee($total_fee);
        $input->SetTime_start($time_start);
        $input->SetTime_expire($time_expire);
        $input->SetGoods_tag($goods_tag);
        $input->SetNotify_url($notify_url);
        $input->SetTrade_type($trade_type);
        $input->SetProduct_id($product_id);
        
        $result = $this->GetPayUrl($input);
        
        if($result['return_code']=='FAIL'){
            $msg = $result['return_msg'];
            return false;
        }
        
        if($result['return_code']=='SUCCESS'){
            
            if($result['result_code']=='FAIL'){
                $msg = $result['err_code'].$result['err_code_des'];
                return false;
            }else{
                //get code_url
                if(array_key_exists('code_url',$result)){
                    \Session::put('order_'.$product_id.'_url',$result['code_url'].'_'.time());
                }
                return $result;
            }
            
        }
        
    }
    
    
	/**
	 * 生成扫描支付URL,模式一
	 * @param BizPayUrlInput $bizUrlInfo
	 */
	public function GetPrePayUrl($productId){
		$biz = new WxPayBizPayUrl();
		$biz->SetProduct_id($productId);
		$values = \WxpayApi::bizpayurl($biz);
		$url = "weixin://wxpay/bizpayurl?" . $this->ToUrlParams($values);
		return $url;
	}
	
	/**
	 * 
	 * 参数数组转换为url参数
	 * @param array $urlObj
	 */
	private function ToUrlParams($urlObj){
		$buff = "";
		foreach ($urlObj as $k => $v)
		{
			$buff .= $k . "=" . $v . "&";
		}
		
		$buff = trim($buff, "&");
		return $buff;
	}
	
	/**
	 * 
	 * 生成直接支付url，支付url有效期为2小时,模式二
	 * @param UnifiedOrderInput $input
	 */
	public function GetPayUrl($input){
		if($input->GetTrade_type() == "NATIVE"){
			$result = \WxPayApi::unifiedOrder($input);
			return $result;
		}
	}
    
    
}