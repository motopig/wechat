<?php
namespace Ecdo\EcdoThor;

use Ecdo\EcdoSpiderMan\AngelCommon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Lib\WechatCallBack;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoThor\ThorCommon;
use Ecdo\EcdoThor\LuckDrawUtils;
use Ecdo\EcdoSpiderMan\SiteCommon;

class Wheel extends SiteCommon
{
    public function __construct()
    {
        parent::__construct();
        $this->ldu = new LuckDrawUtils();
    }

    // 抽奖页(含网页授权)
    public function lucky($guid, $sid)
    {
    	$data = [];

    	// 初始化微信接口
    	$wcb = new \App\Lib\WechatCallBack([
            'appid' => \Config::get('key.wechat.appid'),
            'appsecret' => \Config::get('key.wechat.appsecret'),
            'token' => \Config::get('key.wechat.token'),
            'encodingAesKey' => \Config::get('key.wechat.encodingAesKey'),
            'guid' => $guid,
            'open' => true
        ]);

        // 获取openid及奖品信息
        $result = $wcb->oauth2Component(Input::all());
        if (! empty($result)) {
        	$data = ['guid' => $guid, 'openid' => $result['openid'], 'id' => $sid];
            $data['info'] = LuckDraw::where('id', $sid)->first()->toArray();
        }

        return View::make('EcdoThor::site/wheel/lucky')->with(compact('data'));
    }

    // 抽奖结果处理
    public function wheelResult()
    {
        $prize = $this->ldu->prizeLuckDraw(['guid' => Input::get('guid'), 'openid' => Input::get('openid'), 'id' => Input::get('id')]);

        exit(json_encode($prize, JSON_UNESCAPED_UNICODE));
    }
}
