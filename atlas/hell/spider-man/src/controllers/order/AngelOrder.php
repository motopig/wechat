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
use App\Models\Tower as TowerModel;
use App\Wormhole\WechatPay;

class AngelOrder extends AngelCommon{
    
    
    public $notTowerRoute = ['order/shop'];
    
    public function __construct(){
        parent::__construct();
        $this->user = Auth::angel()->get();
        $this->adu = new AngelDashboardUtils();
        
        $this->sideMenu(array('m_account','m_order'));
        
        $this->model = new AngelOrderModel();
        $this->page_num = \Config::get('EcdoSpiderMan::setting')['page'];
        
    }
    
    //显示订购的统计信息
    public function index(){
        $orders = array();
        //搜索订单列表，并显示
        $order_setting = \Config::get('EcdoSpiderMan::setting')['order_setting'];
        
        $order_count = \DB::table('angel_order')->count();
        
        $angel_order_model = new AngelOrderModel();
        $orders = AngelOrderModel::with('angelOrderInfo')->where('angel_id','=',$this->user->id)->orderBy('created_at','desc')->paginate($this->page_num);
        
        $orderss = $orders->toArray();
        
        $orders_data = $orderss['data'];
        
        foreach($orders_data as $key=>$order){
            if((time()-strtotime($order['created_at'])) > 86400){
                AngelOrderModel::where('id',$order['id'])->update(['status'=>'cancel']);
                $order['status'] = 'cancel';
            }
            
            if(!empty($order['angel_order_info'])){
                foreach($order['angel_order_info'] as $k=>$info){
                    if($info['tower_id']>0){
                        $tower = TowerModel::find($info['tower_id'])->first();
                        $info['content'] = preg_replace('/tower_name/',$tower->name,$info['content']);
                    }
                    $order['angel_order_info'][$k]=$info;
                }
                $order['content'] = $order['angel_order_info'][0]['content'];
                if(array_key_exists($order['status'],$order_setting['status'])){
                    $order['status_name'] = $order_setting['status'][$order['status']];
                }else{
                    $order['status_name'] = $order['status'];
                }
                $orders_data[$key] = $order;
            }
        }
        
        return View::make('EcdoSpiderMan::order/index')->with(compact('orders','orders_data','order_count'));
    }
    
    
    public function detail($order_id=null){
        $order = array();
        $order_infos = array();
        $order_setting = \Config::get('EcdoSpiderMan::setting')['order_setting'];
        
        if($order_id && (int)$order_id>0){
            $order = AngelOrderModel::find($order_id)->toArray();
            if((time()-strtotime($order['created_at'])) > 86400){
                AngelOrderModel::where('id',$order_id)->update(['status'=>'cancel']);
                $order['status'] = 'cancel';
            }
            
            $order_bills = AngelOrderBillModel::where('angel_order_id','=',$order_id)->first();
            
            if($order && $order['angel_id']==$this->user->id){
                
                if(array_key_exists($order['status'],$order_setting['status'])){
                    $order['status_name'] = $order_setting['status'][$order['status']];
                }else{
                    $order['status_name'] = $order['status'];
                }
                
                if($order['pay_method']=='wechat'){
                    $order['pay_method_name'] = "微信扫码支付";
                }elseif($order['pay_method']=='wechat'){
                    $order['pay_method_name'] = "支付宝";
                }
                
                $order_infos = AngelOrderInfoModel::where('angel_order_id','=',$order_id)->get()->toArray();
                
                foreach($order_infos as $key=>$info){
                    if($info['tower_id']>0){
                        $tower = TowerModel::find($info['tower_id'])->first();
                        $info['content'] = preg_replace('/tower_name/',$tower->name,$info['content']);
                    }
                    
                    $order_infos[$key] = $info;
                }
                
            }else{
                $order = array();
            }
        }
        return View::make('EcdoSpiderMan::order/detail')->with(array('order'=>$order,'order_infos'=>$order_infos));
    }
     
    //账单充值
    public function charge(){
        $cash = 300;
        return View::make('EcdoSpiderMan::order/charge')->with(compact('cash'));
    }
    
    //生成订单
    public function orderCreate(){
        
        $order = $this->planOrderCreate();
        
        $order_id = $this->model->saveOrder($order);
        
        if($order_id){
            $errors = array(
                'errcode'=>'success',
                'errmsg'=>'订单创建成功',
                'url'=>'/angel/order/pay/'.$order_id
            );
        }else{
            $errors = array(
                'errcode'=>'error',
                'errmsg'=>'订单创建失败,请刷新页面重试',
                'url'=>''
            );
        }
        
        exit(json_encode($errors, JSON_UNESCAPED_UNICODE));
        
    }
    
    public function planOrderCreate(){
        $prices = \Config::get('EcdoSpiderMan::setting')['price'];
        
        $towers = Input::get('tower');
        $plan = Input::get('plan');
        $plan_time = Input::get('plan_time');
        $pay_method = Input::get('pay');
        
        if(count($towers)<1){
            $errors = array(
                'errcode'=>'error',
                'errmsg'=>'请先选择云号进行购买',
                'url'=>''
            );
            exit(json_encode($errors, JSON_UNESCAPED_UNICODE));
        }
        
        if(empty($pay_method)){
            $errors = array(
                'errcode'=>'error',
                'errmsg'=>'请选择支付方式',
                'url'=>''
            );
            exit(json_encode($errors, JSON_UNESCAPED_UNICODE));
        }
        
        if(empty($plan)){
            $errors = array(
                'errcode'=>'error',
                'errmsg'=>'请选择套餐版本',
                'url'=>''
            );
            exit(json_encode($errors, JSON_UNESCAPED_UNICODE));
        }
        
        if(empty($plan_time)){
            $errors = array(
                'errcode'=>'error',
                'errmsg'=>'请选择套餐期限',
                'url'=>''
            );
            exit(json_encode($errors, JSON_UNESCAPED_UNICODE));
        }
        
        $plan_name = '通用版';
        if($plan=='ent'){
            $plan_name = '企业版';
            $plan_price = 299;
        }
        if($plan=='pro'){
            $plan_name = '旗舰版';
            $plan_price = 999;
        }
        
        //转化成 人民币分
        if($plan_time<12){
            $order_count = $pay_count = (count($towers)*$plan_time*$plan_price)*100;
        }elseif($plan_time==12){
            $order_count = $pay_count = (count($towers)*10*$plan_price+9)*100;
        }
        
        $order_data = array();
        $order_data = array(
            'angel_id'=>$this->user->id,
            'status'=>'ready',
            'order_count'=>$order_count,
            'pay_count'=>$order_count,
            'order_type'=>'plan',
            'pay_method'=>$pay_method
        );
        
        $order_info_data = array();
        
        foreach($towers as $tower_id){
            $tower = TowerModel::where('id','=',$tower_id)->first(['id','name']);
            
            if($tower){
                $order_info_data[] = array(
                    'order_type'=>'plan',
                    'type_id'=>0,
                    'content'=>'云号[tower_name] - '.$plan_name.' - '.$plan_time.'个月',
                    'count_number'=>1,
                    'tower_id'=>$tower->id
                );
            }
        }
        
        return array('order'=>$order_data,'order_info'=>$order_info_data);
    }
    
    //订单支付
    public function pay($order_id=null){
        
        $order = array();
        $order_infos = array();
        $pay_result = array();
        $errmsg = '';
        
        if($order_id && (int)$order_id>0){
            $order = AngelOrderModel::find($order_id)->toArray();
            
            if((time()-strtotime($order['created_at'])) > 86400){
                return Redirect::to('angel/order/detail/'.$order['id'])->with('error','订单已超过24小时自动作废，请重新下单');
            }
            
            //只要存在 bill && NOTPAY 状态就表示订单还能支付
            $order_bills = AngelOrderBillModel::where('angel_order_id','=',$order_id)->where('pay_status','<>','NOTPAY')->first();
            
            if($order && $order['angel_id']==$this->user->id && $order['status']=='ready' && !$order_bills){
                
                $order_infos = AngelOrderInfoModel::where('angel_order_id','=',$order_id)->get()->toArray();
                
                $pay_detail = '';
                $comma = '';
                foreach($order_infos as $key=>$info){
                    if($info['tower_id']>0){
                        $tower = TowerModel::find($info['tower_id'])->first();
                        $info['content'] = preg_replace('/tower_name/',$tower->name,$info['content']);
                        $pay_detail .= $comma.$info['content'];
                        $comma = " | ";
                    }
                    $order_infos[$key] = $info;
                }
                
                $body = "";
                if($order['order_type']=='plan'){
                    $body .= "云号套餐";
                }else{
                    $body .= $order_infos[0]['content'];
                }
                
                $order_bill_model = new AngelOrderBillModel();
                
                $bill = array(
                    'angel_order_id'=>$order['id'],
                    'angel_id'=>$order['angel_id'],
                    'pay_count'=>$order['pay_count'],
                    'pay_method'=>$order['pay_method'],
                    'pay_status'=>'NOTPAY',
                    'created_at'=>date('Y-m-d H:i:s',time()),
                );
                
                if($order['pay_method']=='wechat'){
                    $order['pay_method_name'] = "微信扫码支付";
                    
                    //判断code_url是否在2小时的有效期内
                    if($order_codes = \Session::get('order_'.$order['id'].'_url')){
                        $order_codes = explode('_',$order_codes);
                        if(!array_key_exists('1',$order_codes)){
                            $order_codes[1] = 0;
                        }
                        //2小时内，只需要一个code_url
                        if(time()-$order_codes[1]<7200){
                            $pay_result = array('code_url'=>$order_codes[0]);
                            $pay_result['code_url_encode'] = rawurlencode($pay_result['code_url']);
                        }else{
                            //删除session order_[order_id]_url
                            \Session::forget('order_'.$order['id'].'_url');
                        }
                    }else{
                        //新订单,或code_url超过2小时
                        //out_trade_no 不能重复
                        //微信扫码支付，直接生成bill
                        
                        foreach($bill as $k=>$v){
                            $order_bill_model->$k = $v;
                        }
                        
                        $bill_id = $order_bill_model->save();
                        
                        $out_trade_no = $order['id'].'_'.$bill_id.'_'.time();//bill id = 时间戳
                        
                        $data = array(
                            'body'=>$body,
                            'detail'=>$pay_detail,
                            'out_trade_no'=>$out_trade_no,
                            'total_fee'=>$order['order_count'],
                            'product_id'=>$order['id']
                        );
                        $pay_method = new WechatPay();
                        $pay_result = $pay_method->nativePay($data,$errmsg);
                        
                        if($pay_result){
                            if(array_key_exists('code_url',$pay_result)){
                                $pay_result['code_url_encode'] = rawurlencode($pay_result['code_url']);
                            }
                        }
                    }
                    
                }
                
            }else{
                $order = array();
            }
            
        }
        
        return View::make('EcdoSpiderMan::order/payment')->with(array('order'=>$order,'order_infos'=>$order_infos,'pay_result'=>$pay_result,'errmsg'=>$errmsg));
    }
    
    
    //微信支付
    public function payQrcodeHtml($url=null){
        
        $url = rawurlencode($url);
        echo "<div class='text-center'>";
        echo "<span class='clear text-success'>微信扫码，扫一扫立即支付</span>";
        echo "<img src='".\URL::to('/angel/order/payqrcode/'.$url)."'>";
        
        echo "</div>";
    }
    
    //微信支付
    public function payQrcode($url=null){
        $url = rawurlencode($url);
        return with(new \App\Controllers\ToolController)->qrCode($url,12);
    }
    
    
    public function payComplete($data){
        if(!$data['order_id']){
            return false;
        }
        $order = AngelOrderModel::find($data['order_id'])->toArray();
        
        if(!$order){
            return false;
        }
        
        $order_bill_model = new AngelOrderBillModel();
        $order_update = $order_bill_model->updateOrder($data);
        $bill_update = $order_bill_model->updateBill($data);
        
        //套餐的话需要更新套餐记录的
        if($order_update && $order['order_type']=='plan'){
            
        }
    }
    
    //订单支付
    public function checkout(){
        
    }
    
    //显示订单列表
    public function OrderList(){
        
    }
    
}