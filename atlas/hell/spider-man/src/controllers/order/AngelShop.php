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

class AngelShop extends AngelCommon{
    
    public $notTowerRoute = ['shop/index'];
    public $inTower = true;
    
    public function __construct(){
        parent::__construct();
        $this->user = Auth::angel()->get();
        $this->adu = new AngelDashboardUtils();
        
        $this->page_num = \Config::get('EcdoSpiderMan::setting')['page'];
    }
    
    //商店首页
    public function index(){
        
        return View::make('EcdoSpiderMan::shop/index')->with(array(
            'shop_menu'=>true
        ));
    }
    
    //加入购物车
    public function addCart(){
        
        return View::make('EcdoSpiderMan::shop/cart/add')->with(array('carts'=>$carts));
    }
    
    //购物车
    public function cart(){
        $carts = array();
        return View::make('EcdoSpiderMan::shop/cart/index')->with(array('carts'=>$carts));
    }
    
    //购买套餐
    public function buyPlan(){
        //套餐列表
        $prices = \Config::get('EcdoSpiderMan::setting')['price'];
        
        foreach($prices as $key=>$price){
            if($price['space']){
                $price['space'] = floor($price['space']/(1024*1024));
                $prices[$key]['space'] = $price['space'];
            }
        }
        
        $cur_tower_guid = \Ecdo\Universe\TowerUtils::getTowerGuid();
        
        //云号列表
        $towers = array();
        $cur_tower = '';
        if($cur_tower_guid){
            $cur_tower = $this->adu->getTowerByGuid($cur_tower_guid);
        }
        if(!$cur_tower){
            $towers = $this->adu->getManyByUserId($this->user->id);
        }
        
        //#TODO:已经购买套餐的云号只能进行有效期内升级，不能直接续费
        
        if(!$towers && !$cur_tower){
            return Redirect::to('angel/crTower');
        }
        
        return View::make('EcdoSpiderMan::shop/buyplan')->with(array(
            'plan_menu'=>true,
            'prices'=>$prices,
            'towers'=>$towers,
            'cur_tower'=>$cur_tower
        ));
    }
    
    //购买硬件
    public function buyIot(){
        
    }
    
    //购买应用
    public function buyApp(){
        
    }
    
    //活动专题
    public function promotion($page='index'){
        
    }
}