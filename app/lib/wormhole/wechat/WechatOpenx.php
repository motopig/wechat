<?php
namespace App\Lib;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Lib\WechatCallBack;
use App\Wormhole\WechatAction;
use Ecdo\Universe\TowerUtils;
use Ecdo\Universe\TowerDB;

/**
 * 微信开放平台 - 第三方公众平台
 * 
 * @category yunke
 * @package app\lib\wormhole\wechat
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatOpenx extends Controller
{
    // 接管方AppId
    public $appid;
    // 接管方AppSecret
    public $appsecret;
    // 接管方Token
    public $token;
    // 接管方消息加解密Key
    public $encodingAesKey;
    // 云号Tower Guid
    public $guid;
    // 微信公用配置类对象
    public $wcb;
    
    public function __construct()
    {
        $this->appid = Config::get('key.wechat.appid');
        $this->appsecret = Config::get('key.wechat.appsecret');
        $this->token = Config::get('key.wechat.token');
        $this->encodingAesKey = Config::get('key.wechat.encodingAesKey');
        $this->wcb = new \App\Lib\WechatCallBack([
            'appid' => $this->appid,
            'appsecret' => $this->appsecret,
            'token' => $this->token,
            'encodingAesKey' => $this->encodingAesKey,
            'guid' => $this->guid,
            'open' => true
        ]);
    }

    // 授权事件接收(托管方ticket及授权方取消授权)
    public function wxOauthCancel()
    {
        $postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : file_get_contents("php://input");
        if (! empty($postStr) && Input::get('encrypt_type') && Input::get('encrypt_type') == 'aes') {
            libxml_disable_entity_loader(true);
            $postStr = simplexml_load_string($this->wcb->decryptMsg($postStr, Input::all()), 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->wcb->componentOauthCancel($postStr);
        }
    }

    // 发起授权页(测试体验)
    public function wxOauth()
    {
        // 更新接管方预授权码pre_auth_code
        $this->wcb->apiCreatePreauthcode();
        $url = Config::get('gravity.wechat.url')['componentLoginPage'] . '?component_appid=' . $this->appid . 
        '&pre_auth_code=' . Cache::get('pre_auth_code') . '&redirect_uri=' . URL::to('/') . 
        '/openx/wx_oauth_dis?guid=' . Input::get('guid');
        
        return View::make('site/wxOauth')->with(compact('url'));
    }

    // 授权回调处理
    public function wxOauthDis()
    {
        // 获取授权方信息
        $res = $this->wcb->authorizationInfo(Input::all());
        
        return View::make('site/wxOauth')->with(compact('res'));
    }

    // 公众号消息与事件接收
    public function wxCallback($appid)
    {
        // 全网发布后，参数appid微信动态请求变更为授权appid
        $postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : file_get_contents("php://input");
        if (! empty($postStr)) {
            libxml_disable_entity_loader(true);
            $postStr = $this->wcb->decryptMsg($postStr, Input::all());
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $data = $this->wcb->postObjList($postObj);
            
            if (! $data) {
                exit(0);
            } elseif ($this->guid = \App\Models\TowerWechat::where('appid', $appid)
                ->orWhere('original', $data['toUser'])->where('disabled', 'false')->pluck('guid')) {
                $tu = new TowerUtils();
                $tu->storeTowerGuid($this->guid);
                $this->wcb->guid = $this->guid;

                // 微信消息排序处理机制原则
                $wa = new WechatAction(['guid' => $this->guid, 'wcb' => $this->wcb]);
                $wa->msg($data);
            }

            return '';
        }
    }

    // 获取OPENID
    public function oauth2OpenId()
    {
        $data = Input::all();

        if (strpos($data['state'], '@@@') !== false) {
            $guid = explode('@@@', $data['state'])[1];
            $tu = new TowerUtils();
            $tu->storeTowerGuid($guid);
            TowerDB::useConnTower();
            if ($dt = DB::table($guid . '_wechat')->where('disabled', 'false')->first()) {
                $dt = (array) $dt;
                $this->wcb = new \App\Lib\WechatCallBack([
                    'appid' => $dt['appid'],
                    'appsecret' => $dt['appsecret'],
                    'token' => $dt['token'],
                    'encodingAesKey' => $dt['encodingAesKey'],
                    'guid' => $guid,
                    'open' => false
                ]);
            }

            $data['state'] = explode('@@@', $data['state'])[0];
        }

        $oauthInfo = $this->wcb->oauth2Component($data);
        $url = $oauthInfo['state'] . '&appid=' . $oauthInfo['appid'] . 
        '&guid='. $oauthInfo['guid'] . '&openid=' . $oauthInfo['openid'];

        // 跳转第三方平台赋予会员信息
        if (! empty($oauthInfo['guid'])) {
            $tu = new TowerUtils();
            $tu->storeTowerGuid($oauthInfo['guid']);
            TowerDB::useConnTower();
            $wmbu = new \Ecdo\EcdoHulk\WechatMemberUtils();
            $dt = $wmbu->getOneMemberByOpenID($oauthInfo['openid']);
            if (! empty($dt)) {
                $url .= '&name=' . $dt->name . '&gender=' . $dt->gender . '&head=' . urlencode($dt->head);
            }
        }
        
        header('Location: ' . $url);
        exit(0);
    }

    // 第三方平台微信网页授权登录
    public function platform($state)
    {
        $data = Input::all();

        if ($data['state'] && $data['state'] == $state) {
            $dt = $this->wcb->oauth2Component($data);

            // 储存用户信息
            $tu = new TowerUtils();
            $tu->storeTowerGuid($dt['guid']);
            TowerDB::useConnTower();

            $wmbu = new \Ecdo\EcdoHulk\WechatMemberUtils();
            $wmbu->concernOther(['fromUser' => $dt['openid']]);

            // 暂时只供PINN.IM专用 - no
            $code = \Crypt::encrypt($dt['guid'] . $dt['openid']);
            $url = 'http://www.pinn.im/entry/wechatlogin/?openid=' . $dt['openid'] . '&guid=' . $dt['guid'] 
            . '&yunke_token=a55942cdcfa6f52b89320b1c3c8d104e&code=' . $code;

            header('Location: ' . $url);
            exit(0);
        }
    }

    // 提供给第三方平台微信用户信息
    public function platformUser()
    {
        $data = Input::all();
        $res = ['errcode' => 'success', 'errmsg' => '', 'data' => ''];

        if (! $data['guid'] && ! $data['openid']) {
            $res['errcode'] = 'error';
            $res['errmsg'] = '缺少接口必要参数!';
        } elseif (\Crypt::decrypt($data['code']) != $data['guid'] . $data['openid']) {
            $res['errcode'] = 'error';
            $res['errmsg'] = '接口验证失败!';
        }

        if ($res['errcode'] == 'success') {
            $tu = new TowerUtils();
            $tu->storeTowerGuid($data['guid']);
            TowerDB::useConnTower();

            $wmbu = new \Ecdo\EcdoHulk\WechatMemberUtils();
            $dt = $wmbu->getOneMemberByOpenID($data['openid']);
            if (! empty($dt)) {
                $res['data'] = (array) $dt;
            } else {
                $res['errcode'] = 'error';
                $res['errmsg'] = '未查到微信会员信息!';
            }
        }

        return $res;
    }
}
