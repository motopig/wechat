<?php
namespace Ecdo\EcdoSpiderMan;

use Ecdo\EcdoSpiderMan\AngelCommon;
use Ecdo\EcdoSpiderMan\AngelAccountUtils;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Ollieread\Multiauth;
use Illuminate\Support\Facades\Auth;
use App\Lib\SendEmail;
use Queue;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoSpiderMan\AngelValidator;
use Ecdo\EcdoSpiderMan\AngelDashboardUtils;
use Ecdo\EcdoSpiderMan\Models\AngelOrder as AngelOrderModel;
use Ecdo\EcdoSpiderMan\Models\AngelOrderInfo as AngelOrderInfoModel;
use Ecdo\EcdoSpiderMan\Models\AngelOrderBill as AngelOrderBillModel;
use Ecdo\EcdoSpiderMan\Models\AngelOrderPayWechat as AngelOrderPayWechatModel;

use App\Models\Tower as TowerModel;
use App\Wormhole\WechatPay;

class AngelOrderBill extends AngelCommon{
    
    public function __construct(){
        parent::__construct();
        $this->user = Auth::angel()->get();
        $this->adu = new AngelDashboardUtils();
        
        $this->sideMenu(array('m_account','m_order'));
        $this->model = new AngelOrderBillModel();
    }
    
    //更新订单
    public function updateOrder($data){
        if(!array_key_exists('order_id',$data)){
            return false;
        }
        
        AngelOrderModel::where('id',$data['order_id'])->update(array(
            'status'=>$data['order_status'],
            'updated_at'=>date('Y-m-d H:i:s',time())
        ));
        
        return true;
    }
    
    //更新账单
    public function updateBill($data){
        if(!array_key_exists('bill_id',$data)){
            return false;
        }
        $bill = AngelOrderBillModel::find($data['bill_id'])->toArray();
        if(!$bill){
            return false;
        }
        AngelOrderModel::where('id',$data['bill_id'])->update(array(
            'pay_status'=>$data['pay_status'],
            'pay_method_id'=>$data['pay_method_id'],
            'bank_type'=>$data['bank_type'],
            'pay_coupon'=>$data['pay_coupon'],
            'pay_at'=>date('Y-m-d H:i:s',time())
        ));
        
        return true;
    }
    
    //更新微信支付状态
    public function updateWechatPay($data){
        $pay_wechat_model = new AngelOrderPayWechat();
        
        foreach($data as $k=>$v){
            $pay_wechat_model->$k = $v;
        }
        $id = $pay_wechat_model->save();
        if($id){
            \DB::commit();
            return true;
        }else{
            \DB::rollBack();
            return false;
        }
    }
}