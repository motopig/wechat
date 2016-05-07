<?php
namespace Ecdo\EcdoSpiderMan;

use Ecdo\EcdoSpiderMan\AngelCommon;
use App\Models\Tower;
use Illuminate\Support\Facades\View;
use Ollieread\Multiauth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Ecdo\Universe\Atlas\StarNest;
use Ecdo\Tower\Atlas\TowerStarManager;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoSpiderMan\AngelDashboardUtils;
use Ecdo\EcdoSpiderMan\AngelAccountUtils;
use Ecdo\EcdoHulk\WechatDashboardUtils;
use Ecdo\Universe\TowerDB;

/**
 * 商家控制台
 *
 * @category yunke
 * @package atlas\hell\spider-man\src\controllers\dashboard
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class AngelDashboard extends AngelCommon
{
    public function __construct()
    {
        // guid白名单
        $this->notTowerRoute = ['crTower', 'chTower', 'dashboard'];

        parent::__construct();
        $this->user = Auth::angel()->get();

        $this->adu = new AngelDashboardUtils();
        $this->sideMenu(array('m_shop','m_shop_file','m_shop_auth'));
    }
    
    // 首页
    public function index()
    {
        // 清除店铺guid 菜单 权限等缓存
        \Ecdo\Universe\TowerUtils::forgetTower();
        
        // 获取商家店铺信息
        $tower = $this->adu->getManyByUserId($this->user->id);

        $metas = array(
            'title' => '一点云客 | 移动智能营销管理中心',
            'keyword' => '云客,微信,营销,微商城,摇一摇,微信Wi-Fi',
            'description' => '一点云客提供基于微信的移动智能营销服务，包括摇一摇，微信wifi，微信签到，微信调查等多种营销服务。',
        );
        
        $angel_account_utils = new AngelAccountUtils();
        $angel_info = $angel_account_utils->getAngelInfo(Auth::angel()->get()->id);

        return View::make('EcdoSpiderMan::dashboard/index')->with(compact('tower','metas','angel_info'));
    }

    // 创建店铺
    public function crTower()
    {
        $tower = $this->adu->getManyByUserId($this->user->id);
        $territory = \Session::get(Auth::angel()->get()->encrypt_id . '_territory');
        $territory_info = \Session::get(Auth::angel()->get()->encrypt_id . '_territory_info');
        $res = true;
        $msg = '';

        // 个人用户限制 (非认证只能创建1个店铺，已认证可创建3个云号，无店员创建权限)
        if ($territory->property == 'personal') {
            if (isset($territory_info->validator)) {
                if ($territory_info->validator == 'false' && count($tower) >= 1) {
                   $res = false;
                } elseif (count($tower) == 3) {
                    $res = false;
                }
            } elseif (count($tower) >= 1) {
                $res = false;  
            }

            if (! $res) {
                if (empty($territory_info) || $territory_info->validator == 'false') {
                    $msg = '免费个人用户最多只能拥有1个云号';
                } else {
                    $msg = '个人用户最多只能拥有3个云号';
                }
            }
        }

        // 企业用户限制 (非认证只能创建3个店铺，已认证无店铺创建限制，有店员创建权限)
        if ($territory->property == 'enterprise') {
            if (isset($territory_info->validator)) {
                if ($territory_info->validator == 'false' && count($tower) >= 3) {
                   $res = false;  
                }
            } elseif (count($tower) >= 3) {
                $res = false;
            }

            if (! $res) {
                $msg = '免费企业用户最多只能拥有3个云号';
            }
        }

        if (! $res) {
            return Redirect::to('angel')->with('error', $msg);
        }

        $business = \Config::get('EcdoSpiderMan::setting')['business'];
        
        return View::make('EcdoSpiderMan::dashboard/cr_tower')->with(compact('business'));
    }

    // 创建店铺处理
    public function crTowerDis()
    {
        // 表单验证规则
        $rules = array(
            'name' => 'Required|min:2|unique:tower,name',
            // 'byname' => 'Required|unique:tower,byname'
        );
        
        if (! Input::get('business')) {
            $rules['business'] = 'Required';
        } elseif (Input::get('business') == 'other' && ! Input::get('business_other')) {
            $rules['business_other'] = 'Required';
        }

        // 验证表单信息
        $validator = Validator::make(Input::all(), $rules);
        
        // 验证不通过
        if (! $validator->passes()) {
            return Redirect::to('angel/crTower')->withInput(Input::all())->withErrors($validator->getMessageBag());
        }

        if ($this->adu->createTower(Input::all(), $this->user)) {
            // 新建的店铺安装基础应用包
            $tower = Tower::Where('name', Input::get('name'))->first(['encrypt_id']);
            $towerGuid = $tower->encrypt_id;

            \Queue::push('Ecdo\Tower\Queue\StarJob@instBase', $towerGuid);
            
            return Redirect::to('angel')->with('success', '云号创建成功');
        } else {
            return Redirect::to('angel/crTower')->with('error', '云号创建失败');
        }
    }
    
    public function weixin(){
        
        return View::make('EcdoSpiderMan::dashboard/weixin');
    }

    public function sylar(){

        if($guid = TowerUtils::fetchTowerGuid()){
            $Des = new \App\Lib\Des(\Config::get('des')['key']);
            $login_b2c = json_decode(TowerUtils::fetchTowerLoginInfo());

            $uname =  $Des->encrypt($login_b2c->username);
            $password =  $Des->encrypt($login_b2c->password);
            $url = 'http://' . $guid . '.' . \Config::get('connectb2c')['login_url'] ;

            return View::make('EcdoSpiderMan::dashboard/sylar')->with(compact('url', 'uname', 'password'));
        }
    }

    // 店铺控制台
    public function dashboard()
    {
        $noconfig = false;
        $wdu_data = '';
        $guid = '';
        // if (WechatDashboardUtils::getWechatCount() == 0 && substr(\URL::current(), -7) != 'setting') {
        // 	$guid = TowerUtils::fetchTowerGuid();
        //     $noconfig = true;
        // }else{
        //     $wdu = new WechatDashboardUtils;
        //     $wdu_data = $wdu->getWechat();
        // }

        $wdu = new WechatDashboardUtils;
        $dt = $wdu->getWechat();
        if (empty($dt[1]) || $dt[1]['disabled'] == 'true') {
            $guid = TowerUtils::fetchTowerGuid();
            $noconfig = true;
        }
        
        $guid = TowerUtils::fetchTowerGuid();
        $adu = new AngelDashboardUtils();
        $tower = $adu->getTowerByGuid($guid);
        
        if($tower->business=='other'){
            $tower->business='其它';
        }
        
        $business = \Config::get('EcdoSpiderMan::setting')['business'];
        
        if(array_key_exists($tower->business,$business)){
            $tower->business = $business[$tower->business];
        }

        $wsu = new \Ecdo\EcdoHulk\WechatShakearoundUtils();
        $shakearoundCount = $wsu->getShakearoundCount();
        
        $this->sideMenu(array('m_yunke','m_help'));
        return View::make('EcdoSpiderMan::dashboard/dashboard')->with(compact('noconfig','wdu_data','guid','tower','business', 'shakearoundCount'));
    }

    /**
     * 切换店铺，转向店铺页面
     * @param string $tower            
     */
    public function chTower($tower)
    {
        // 调用功能类存储店铺guid，并存储店铺菜单
        if (! empty($tower)) {
            //检查云号数据库是否创建
            if($this->adu->checkTowerDatabase($tower) == 'true'){
                $info = '云号信息初始化中，请稍后。。。';
                return View::make('EcdoSpiderMan::dashboard/warning')->with(compact('info'));
            }
            \Ecdo\Universe\TowerUtils::storeTowerGuid($tower);
            \Ecdo\Universe\TowerUtils::storeTower();
        }

        return Redirect::to('angel/dashboard');
    }
    
    //error页面
    public function errorPage(){
        return View::make('EcdoSpiderMan::dashboard/errorpage');
    }

    // 云号配置
    public function towerConfig()
    {
        $tu = new TowerUtils();
        $adu = new AngelDashboardUtils();
        $tower = $adu->getTowerByGuid($tu->fetchTowerGuid());
        $business = \Config::get('EcdoSpiderMan::setting')['business'];

        return View::make('EcdoSpiderMan::dashboard/tower_config')->with(compact('tower', 'business'));
    }

    // 云号配置处理
    public function towerConfigDis()
    {
        $adu = new AngelDashboardUtils();
        $arr = $adu->towerConfig(Input::all());
        
        // if ($arr['errcode'] == 'success') {
//             $arr['url'] = action('\Ecdo\EcdoSpiderMan\AngelDashboard@dashboard');
//         }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }
}
