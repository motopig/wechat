<?php
namespace Ecdo\EcdoHulk;

use Ecdo\EcdoSpiderMan\AngelCommon;
use Ecdo\EcdoHulk\WechatDashboardUtils;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Ecdo\Universe\TowerUtils;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

/**
 * 商家控制器公用类
 * 
 * @category yunke
 * @package atlas\hell\hulk\src\controllers\common
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatCommon extends AngelCommon
{
    public function __construct()
    {
        parent::__construct();

        if (substr(\URL::current(), -7) != 'setting' && empty(WechatDashboardUtils::getWechatCount()[1])) {
        	$guid = TowerUtils::fetchTowerGuid();
            $noconfig = true;

            // 更新接管方预授权码pre_auth_code
            $wcb = new \App\Lib\WechatCallBack([
                'appid' => Config::get('key.wechat.appid'),
                'appsecret' => Config::get('key.wechat.appsecret'),
                'token' => Config::get('key.wechat.token'),
                'encodingAesKey' => Config::get('key.wechat.encodingAesKey'),
                'guid' => $guid,
                'open' => true
            ]);
            
            $wcb->apiCreatePreauthcode();
            $url = Config::get('gravity.wechat.url')['componentLoginPage'] . 
            '?component_appid=' . Config::get('key.wechat.appid') . 
            '&pre_auth_code=' . Cache::get('pre_auth_code') . '&redirect_uri=' . \URL::to('/') . 
            '/openx/wx_oauth_dis?guid=' . $guid;

            exit(View::make('EcdoHulk::config/config')->with(compact('guid','noconfig', 'url')));
        }
        $this->sideMenu(array('m_wechat','m_wechat_market','m_wechat_setting'));
    }
}
