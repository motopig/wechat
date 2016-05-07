<?php
namespace Ecdo\EcdoHulk;

use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoHulk\WechatCommon;
use Ecdo\EcdoHulk\WechatDashboardUtils;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

/**
 * 商家微信控制台
 *
 * @category yunke
 * @package atlas\hell\hulk\src\controllers\dashboard
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatDashboard extends WechatCommon
{
    public function __construct()
    {
        parent::__construct();

        $this->wdu = new WechatDashboardUtils();
        $this->guid = TowerUtils::fetchTowerGuid();
    }
    
    // 首页
    public function index()
    {
        $concernCount = json_encode($this->wdu->getConcernCount());

        return View::make('EcdoHulk::dashboard/index')->with(compact('concernCount'));
    }

    // 微信配置
    public function config()
    {
        $this->sideMenu(array('m_shop','m_shop_file','m_shop_auth'));
        $guid = $this->guid;
        $wechat = $this->wdu->getWechat()[0];
        $open = $this->wdu->getWechat()[1];
        $noconfig = true;

        // 更新接管方预授权码pre_auth_code
        $wcb = new \App\Lib\WechatCallBack([
            'appid' => Config::get('key.wechat.appid'),
            'appsecret' => Config::get('key.wechat.appsecret'),
            'token' => Config::get('key.wechat.token'),
            'encodingAesKey' => Config::get('key.wechat.encodingAesKey'),
            'guid' => $this->guid,
            'open' => true
        ]);

        // 更新接管方预授权码pre_auth_code
        $wcb->apiCreatePreauthcode();
        $url = Config::get('gravity.wechat.url')['componentLoginPage'] . 
        '?component_appid=' . Config::get('key.wechat.appid') . 
        '&pre_auth_code=' . Cache::get('pre_auth_code') . '&redirect_uri=' . \URL::to('/') . 
        '/openx/wx_oauth_dis?guid=' . $this->guid;

    	return View::make('EcdoHulk::config/up_wecaht')->with(compact('wechat', 'open', 'noconfig', 'guid', 'url'));
    }

    // 微信配置处理
    public function configDis()
    {
    	// 表单验证规则
        $rules = array(
            'appid' => 'Required',
            'appsecret' => 'Required',
            'token' => 'Required',
            'encodingAesKey' => 'Required'
        );
        
        // 验证表单信息
        $validator = Validator::make(Input::all(), $rules);
        
        // 验证不通过
        if (! $validator->passes()) {
        	if (Input::get('id')) {
        		$url = 'angel/wechat/config';
        	} else {
        		$url = 'angel/wechat';
        	}

            return Redirect::to($url)->withInput(Input::all())->withErrors($validator->getMessageBag());
        } elseif (! empty($this->wdu->getWechat()[1]) && $this->wdu->getWechat()[1]['disabled'] == 'false') {
            return Redirect::to('angel/dashboard')->with('error', '微信公众号已授权，无法进行手动配置！');
        }

        if ($this->wdu->settingWechat(Input::all())) {
            return Redirect::to('angel/wechat/config')->with('success', '微信配置保存成功');
        } else {
            return Redirect::to('angel/dashboard')->with('error', '微信配置失败');
        }
    }
}
