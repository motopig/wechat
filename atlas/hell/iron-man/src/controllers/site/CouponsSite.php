<?php
namespace Ecdo\EcdoIronMan;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Ecdo\EcdoSpiderMan\SiteCommon;
use Ecdo\EcdoIronMan\CouponsUtils;
use Ecdo\EcdoIronMan\Verification;
use Ecdo\EcdoIronMan\CarduseUtils;

/**
 * 卡券前台控制器
 * 
 * @category yunke
 * @package atlas\hell\iron-man\src\controllers\site
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class CouponsSite extends SiteCommon
{
    public function __construct()
    {
        parent::__construct();

        $this->guid = parent::getSessionGuid();
        $this->openid = parent::getSessionOpenid();
        $this->vu = new verificationUtils();
        $this->cu = new CarduseUtils();
        $this->c = new CouponsUtils();
    }

    // 卡券核销登录校验
    public function verification($guid, $data, $config = '')
    {
        $data = explode('@@@', \Crypt::decrypt($data));
        $code_id = $data[0];
        $openid = $data[1];

        // 判断是否同一个用户登录校验
        if (empty($config)) {
            $wcb = new \App\Lib\WechatCallBack([
                'appid' => \Config::get('key.wechat.appid'),
                'appsecret' => \Config::get('key.wechat.appsecret'),
                'token' => \Config::get('key.wechat.token'),
                'encodingAesKey' => \Config::get('key.wechat.encodingAesKey'),
                'guid' => $guid,
                'open' => true
            ]);

            $result = $wcb->oauth2Component(Input::all());
            if (! isset($result['openid']) || $result['openid'] != $openid) {
                exit('<h1>openid Calibration error!</h1>');
            } else {
                parent::setSessionOpenid($result['openid']);
            }
        }

        $request = [
            'openid' => $openid,
            'guid' => $guid,
            'code_id' => $code_id
        ];

        $verification = $this->vu->getOneVerification($request['openid']);
        if (empty($verification)) {
            $request['action'] = 'register';

            if (! $this->vu->getCodeValidate($code_id)) {
                exit('<h1>code_id Invalid!</h1>');
            }
        } else {
            switch ($verification['status']) {
                case 0:
                case 2:
                    $request['action'] = 'verify';
                    $request['verification'] = $verification;
                    break;
                case 1:
                    $request['action'] = 'index';
                    $request['verification'] = $verification;
                    break;
            }
        }

        return View::make('EcdoIronMan::site/verification/' . $request['action'])->with(compact('request'));
    }

    // 卡券核销员注册
    public function verificationDis()
    {
        $data = Input::all();
        $arr = $this->vu->createVerification($data);

        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoIronMan\CouponsSite@verification', 
            [$data['csrf_guid'], \Crypt::encrypt($data['code_id'] . '@@@' . $data['openid']), true]);
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 卡券核销记录
    public function carduseLog()
    {
        parent::isSessionOpenid();

        $type = $this->c->getType();
        $coupons_type = $this->c->getCouponsType()['type'];
        $status = $this->cu->getStatus();
        $carduse = $this->cu->carduseLog($this->openid);
        $verification = $this->vu->getOneVerification($this->openid);
        $data = $arr = \Crypt::encrypt($this->guid . '@@@' . $this->openid);

        return View::make('EcdoIronMan::site/carduse/log')
        ->with(compact('carduse', 'verification', 'type', 'coupons_type', 'status', 'data'));
    }

    // 卡券核销搜索记录
    public function carduseLogSearch()
    {
        parent::isSessionOpenid();

        $search = Input::get('search');
        $type = $this->c->getType();
        $coupons_type = $this->c->getCouponsType()['type'];
        $status = $this->cu->getStatus();
        $carduse = $this->cu->carduseLogSearch($this->openid, $search);
        $verification = $this->vu->getOneVerification($this->openid);

        return View::make('EcdoIronMan::site/carduse/log')
        ->with(compact('carduse', 'search', 'verification', 'type', 'coupons_type', 'status'));
    }

    // 卡券核销
    public function carduse()
    {
        parent::isSessionOpenid();

        $verification = $this->vu->getOneVerification($this->openid);
        $data = $arr = \Crypt::encrypt($this->guid . '@@@' . $this->openid);

        return View::make('EcdoIronMan::site/carduse/index')->with(compact('verification', 'data'));
    }

    // 卡券核销处理
    public function carduseDis()
    {
        parent::isSessionOpenid();

        $data = Input::all();
        $arr = $this->cu->carduseVerification($data);
        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoIronMan\CouponsSite@codeInfo', 
            [$data['csrf_guid'], $data['id'], 'carduse']);
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 卡券列表
    public function codePage($guid)
    {
        parent::isSessionOpenid();
        
        $type = $this->c->getType();
        $coupons_type = $this->c->getCouponsType()['type'];
        $code_type = $this->c->getCodeStatus();
        $info = $this->c->getCouponsInfoOpenid($this->openid);

        return View::make('EcdoIronMan::site/coupons/page')->with(compact('info', 'type', 'coupons_type', 'code_type'));
    }

    // 卡券券面
    public function codeInfo($guid, $code, $action)
    {
        parent::isSessionOpenid();

        $type = $this->c->getType();
        $coupons_type = $this->c->getCouponsType()['type'];
        $setting = $this->c->couponSetting();
        $info = $this->c->getCouponsInfo($code);
        $coupons = $this->c->getOneCoupons($info->card_id);
        $status = $this->cu->getStatus();
        $register = action('\Ecdo\EcdoIronMan\CouponsSite@' . $action, [$guid]);
        $data = $guid . '@@@' . $coupons->id . '@@@' . $code . '@@@' . $action;

        return View::make('EcdoIronMan::site/carduse/code')
        ->with(compact('code', 'info', 'type', 'coupons_type', 'setting', 'status', 'coupons', 
        'register', 'action', 'data'));
    }

    // 生成卡券二维码或条形码图片
    public function codeImage($guid, $code_type, $code)
    {
        $tc = new \App\Controllers\ToolController();

        if ($code_type == 'CODE_TYPE_QRCODE') {
            return $tc->qrCode($code, 12);
        } elseif ($code_type == 'CODE_TYPE_BARCODE') {
            return $tc->barCode($code);
        }
    }

    // 卡券详情
    public function codeContent($guid, $data)
    {
        parent::isSessionOpenid();

        $data = explode('@@@', $data);
        $coupons_type = $this->c->getCouponsType()['type'];
        $coupons = $this->c->getOneCoupons($data[1]);
        $code = $data[2];
        $action = $data[3];

        return View::make('EcdoIronMan::site/carduse/content')->with(compact('coupons', 'coupons_type', 'code', 'action'));
    }

    // 卡券微信js扫描
    public function wxjsQrcode()
    {
        $wcb = new \App\Lib\WechatCallBack([
            'appid' => \Config::get('key.wechat.appid'),
            'appsecret' => \Config::get('key.wechat.appsecret'),
            'token' => \Config::get('key.wechat.token'),
            'encodingAesKey' => \Config::get('key.wechat.encodingAesKey'),
            'guid' => Input::get('csrf_guid'),
            'open' => true
        ]);

        $data = [
            'guid' => Input::get('csrf_guid'), // 必填
            'method' => Input::get('action'), // 必填
            'url' => Input::get('url'), // 必填
            'data' => [] // 选填
        ];

        $res = $wcb->getJsApiData($data);
        exit($res);
    }

    // 领取卡券
    public function codeReceive($guid, $id, $oauth2 = '')
    {
        if (! empty($oauth2)) {
            $wcb = new \App\Lib\WechatCallBack([
                'appid' => \Config::get('key.wechat.appid'),
                'appsecret' => \Config::get('key.wechat.appsecret'),
                'token' => \Config::get('key.wechat.token'),
                'encodingAesKey' => \Config::get('key.wechat.encodingAesKey'),
                'guid' => $guid,
                'open' => true
            ]);

            $result = $wcb->oauth2Component(Input::all());
            if (! isset($result['openid'])) {
                exit('<h1>openid Calibration error!</h1>');
            } else {
                parent::setSessionOpenid($result['openid']);
            }
        }

        $setting = $this->c->couponSetting();
        $type = $this->c->getType();
        $coupons_type = $this->c->getCouponsType()['type'];
        $coupons = $this->c->getOneCoupons($id);

        return View::make('EcdoIronMan::site/carduse/receive')
        ->with(compact('coupons', 'type','coupons_type', 'setting'));
    }

    // 领取卡券处理
    public function codeReceiveDis()
    {
        $wo = new \App\Lib\WechatOpenx();
        $wo->wcb->guid = Input::get('csrf_guid');
        
        $data = [
            'guid' => Input::get('csrf_guid'),
            'method' => Input::get('action'),
            'url' => Input::get('url'),
            'data' => ['cardId' => Input::get('card_id')]
        ];

        $res = $wo->wcb->getJsApiData($data);
        exit($res);
    }
}
