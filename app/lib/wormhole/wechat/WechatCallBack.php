<?php
namespace App\Lib;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Ecdo\Universe\TowerUtils;

/**
 * 微信公用配置类
 * 
 * @category yunke
 * @package app\lib\wormhole\wechat
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatCallBack implements ApiInterface
{
    // 接口地址
    public $url;
    // 错误信息
    public $errcode;
    // AppId
    public $appid;
    // AppSecret
    public $appsecret;
    // TOKEN
    public $token;
    // EncodingAesKey
    public $encodingAesKey;
    // AccessToken
    public $accessToken;
    // jsapi_ticket
    public $jsApiTicket;
    // jsapi_ticket_card
    public $jsApiTicketCard;
    // 托管机制
    public $open;
    // Tower Guid
    public $guid;

    public function __construct($obj)
    {
        $this->url = self::getApiUri();
        $this->errcode = self::getErrcode();
        $this->appid = $obj['appid'];
        $this->appsecret = $obj['appsecret'];
        $this->token = $obj['token'];
        $this->encodingAesKey = $obj['encodingAesKey'];
        $this->open = $obj['open'];
        $this->guid = $obj['guid'];

        if (Input::get('signature') && Input::get('echostr') 
            && Input::get('timestamp') && Input::get('nonce')) {
            // 微信开发者验证
            self::checkSignature();
            exit(0);
        }
    }

    public function valid() {}
    
    // 微信接口地址汇总
    public function getApiUri()
    {
        return Config::get('gravity.wechat.url');
    }
    
    // 微信接口错误信息汇总
    public function getErrcode()
    {
        return Config::get('gravity.wechat.errcode');
    }

    // 微信消息接收处理
    public function getMsg()
    {
        return self::init();
    }

    // 微信系统及用户消息解密
    public function decryptMsg($postStr, $parameter)
    {
        $xml_tree = new \DOMDocument();
        $xml_tree->loadXML($postStr);
        $array_e = $xml_tree->getElementsByTagName('Encrypt');
        $encrypt = $array_e->item(0)->nodeValue;
        $from_xml = sprintf($postStr, $encrypt);
        
        $msg = '';
        $pc = new \WXBizMsgCrypt($this->token, $this->encodingAesKey, $this->appid);
        $errCode = $pc->decryptMsg($parameter['msg_signature'], $parameter['timestamp'], $parameter['nonce'], $from_xml, $msg);
        if ($errCode == 0) {
            return $postStr = $msg;
        } else {
            exit($this->errcode[$errCode]);
        }
    }

    // 微信被动回复消息加密
    public function encryptMsg($msg, $parameter)
    {
        $encryptMsg = '';
        $pc = new \WXBizMsgCrypt($this->token, $this->encodingAesKey, $this->appid);
        $errCode = $pc->encryptMsg($msg, $parameter['timestamp'], $parameter['nonce'], $encryptMsg);
        if ($errCode == 0) {
            return $encryptMsg;
        } else {
            exit($this->errcode[$errCode]);
        }
    }

    // 微信消息接收方法
    public function init()
    {
        $postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : file_get_contents("php://input");
        if (! empty($postStr)) {
            libxml_disable_entity_loader(true);
            
            // 微信消息解密
            if (Input::get('encrypt_type') && Input::get('encrypt_type') == 'aes') {
                $postStr = self::decryptMsg($postStr, Input::all());
            }

            // 微信消息接收处理
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $postObjArr = self::postObjList($postObj);

            return $postObjArr;
        } else {
            return false;
        }
    }

    // 微信消息推送执行
    public function postMsg($data = array(), $passive = '')
    {
        // 被动消息
        if (! empty($passive)) {
            // 微信消息加密
            if (Input::get('encrypt_type') && Input::get('encrypt_type') == 'aes') {
                return self::encryptMsg(self::sendPassiveXmlTpl($data['type'], $data['action']), Input::all());
            } else {
                return self::sendPassiveXmlTpl($data['type'], $data['action']);
            }
        } else {
            $upload = false;
            $download = false;
            $json = self::sendJsonData($data['type'], $data['action']);

            switch ($data['parameter']['key']) {
                case 'addMaterial':
                case 'shakearoundMaterialAdd':
                case 'uploadImg':
                    $upload = true;
                    break;
                case 'getQrcode':
                case 'downloadMedia':
                    $download = true;
                    break;
                default:
                    break;
            }

            if ($upload) {
                return self::curlUpload(self::getParameterUrl($data['parameter']), $json);
            } elseif ($download) {
                return self::curlDownload(self::getParameterUrl($data['parameter']), $json);
            } else {
                return self::curlPost(self::getParameterUrl($data['parameter']), $json);
            }
        }

        // 不执行任何动作
        return '-2';
    }

    // 授权事件接收(托管方ticket及授权方取消授权)
    public function componentOauthCancel($str = '')
    {
       if (! empty($str)) {
            $str = (array) $str;
            if (! empty($str['InfoType'])) {
                switch ($str['InfoType']) {
                    case 'unauthorized':
                        if ($id = \App\Models\TowerWechat::where('appid', $str['AuthorizerAppid'])->pluck('id')) {
                            $tw = \App\Models\TowerWechat::find($id);
                            $tw->disabled = 'true';
                            $tw->save();
                        }

                        break;
                    case 'component_verify_ticket':
                        if ($id = \App\Models\TowerShare::where('type', 0)->pluck('id')) {
                            $ts = \App\Models\TowerShare::find($id); 
                        } else {
                            $ts = new \App\Models\TowerShare();
                        }

                        $ts->content = (string) $str['ComponentVerifyTicket'];
                        $ts->save();
                        break;
                }
            }
        }
    }

    // 更新接管方预授权码pre_auth_code
    public function apiCreatePreauthcode()
    {
        self::componentAccessToken();
        if (! Cache::has('pre_auth_code')) {
            $data = [
                'type' => 'apiCreatePreauthcode',
                'action' => ['component_appid' => $this->appid],
                'parameter' => [
                    'key' => 'apiCreatePreauthcode',
                    'value' => [
                        'component_access_token' => Cache::get('component_access_token')
                    ]
                ]
            ];

            $res = (array) json_decode(self::postMsg($data));
            if (! empty($res['pre_auth_code'])) {
                Cache::put('pre_auth_code', $res['pre_auth_code'], ((int) $res['expires_in'] / 60));
            }
        }
    }

    // 授权处理回调
    public function authorizationInfo($parameter)
    {
        self::componentAccessToken();
        $res = ['errcode' => 'success', 'errmsg' => '微信公众号授权成功!', 'data' => ''];
        if (! Cache::has('query_auth_code') || Cache::get('query_auth_code') != $parameter['auth_code']) {
            Cache::put('query_auth_code', $parameter['auth_code'], ((int) $parameter['expires_in'] / 60));
        }

        $data = [
            'type' => 'componentApiQueryAuth',
            'action' => ['component_appid' => $this->appid, 'authorization_code' => Cache::get('query_auth_code')],
            'parameter' => [
                'key' => 'componentApiQueryAuth',
                'value' => [
                    'component_access_token' => Cache::get('component_access_token')
                ]
            ]
        ];

        $dt = (array) json_decode(self::postMsg($data));
        if (! empty($dt['authorization_info'])) {
            $res['data'] = (array) $dt['authorization_info'];
        } else {
            $res['errcode'] = 'error';
            $res['errmsg'] = '获取授权方授权信息失败!';
        }

        if ($res['errcode'] == 'success') {
            if ($twd = \App\Models\TowerWechat::where('guid', $parameter['guid'])->first()) {
                if ($twd->appid != $res['data']['authorizer_appid']) {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = '每个云号只能绑定一个微信公众号；如需绑定其他公众号，请创建新的云号!';
                } else {
                    $tw = \App\Models\TowerWechat::find($twd->id);
                }
            } else {
                $tw = new \App\Models\TowerWechat();
                $tw->guid = $parameter['guid'];
            }

            if ($res['errcode'] == 'success') {
                // $arr = self::getAuthorizerInfo($res['data']['authorizer_appid']);
                // if (! empty($arr)) {
                //     $tw->guid = $parameter['guid'];
                //     $tw->appid = $res['data']['authorizer_appid'];
                //     $tw->authorizer_refresh_token = $res['data']['authorizer_refresh_token'];
                //     $tw->original = $arr['authorizer_info']['user_name'];
                //     $tw->info = serialize($arr);
                //     $tw->func_info = serialize($res['data']['func_info']);

                //     if (! $tw->save()) {
                //         $res['errcode'] = 'error';
                //         $res['errmsg'] = '保存授权方数据失败!';
                //     }
                // } else {
                //     $res['errcode'] = 'error';
                //     $res['errmsg'] = '获取授权方账户信息失败!';
                // }
                
                $tw->appid = $res['data']['authorizer_appid'];
                $tw->authorizer_refresh_token = $res['data']['authorizer_refresh_token'];
                $tw->func_info = serialize($res['data']['func_info']);
                $tw->disabled = 'false';

                if (! $tw->save()) {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = '保存授权方数据失败!';
                }
            }
        }

        return $res;
    }

    // 获取授权方的账户信息
    public function getAuthorizerInfo($authorizer_appid)
    {
        $res = [];
        $data = [
            'type' => 'apiGetAuthorizerInfo',
            'action' => ['component_appid' => $this->appid, 'authorizer_appid' => $authorizer_appid],
            'parameter' => [
                'key' => 'apiGetAuthorizerInfo',
                'value' => [
                    'component_access_token' => Cache::get('component_access_token')
                ]
            ]
        ];

        $dt = (array) json_decode(self::postMsg($data));
        if (! empty($dt['authorizer_info'])) {
            $res = $dt;
        }

        return $res;
    }

    // 更新接管方令牌component_access_token
    public function componentAccessToken($type = false)
    {
        if (! Cache::has('component_access_token') || $type = true) {
            $data = [
                'type' => 'componentAccessToken',
                'action' => [
                    'component_appid' => $this->appid,
                    'component_appsecret' => $this->appsecret,
                    'component_verify_ticket' => \App\Models\TowerShare::where('type', 0)->pluck('content')
                ],
                'parameter' => [
                    'key' => 'componentAccessToken',
                    'value' => []
                ]
            ];

            $res = (array) json_decode(self::postMsg($data));
            if (! empty($res['component_access_token'])) {
                Cache::put('component_access_token', $res['component_access_token'], ((int) $res['expires_in'] / 60));
            }
        }
    }

    // 获取刷新授权方authorizer_access_token
    public function authorizerAccessToken()
    {
        self::componentAccessToken();
        $twd = \App\Models\TowerWechat::where('guid', $this->guid)->first();

        $data = [
            'type' => 'authorizerRefreshToken',
            'action' => [
                'component_appid' => $this->appid, 
                'authorizer_appid' => $twd->appid,
                'authorizer_refresh_token' => $twd->authorizer_refresh_token
            ],
            'parameter' => [
                'key' => 'authorizerRefreshToken',
                'value' => [
                    'component_access_token' => Cache::get('component_access_token')
                ]
            ]
        ];

        $res = (array) json_decode(self::postMsg($data));
        if (! empty($res['authorizer_access_token'])) {
            Cache::put($this->guid . '_access_token', $res['authorizer_access_token'], ((int) $res['expires_in'] / 60));
            $tw = \App\Models\TowerWechat::find($twd->id);
            $tw->authorizer_refresh_token = $res['authorizer_refresh_token'];
            $tw->save();
        }
    }

    // 获取开发者access_token
    public function advancedAccessToken()
    {
        $data = [
            'key' => 'getAccessToken',
            'value' => [
                'appid' => $this->appid,
                'secret' => $this->appsecret,
                'grant_type' => 'client_credential'
            ]
        ];

        $sendUrl = self::getParameterUrl($data);
        $req = self::curlPost($sendUrl);
        $res = (array) json_decode($req);
        if (! empty($res['access_token'])) {
           Cache::put($this->guid . '_access_token', $res['access_token'], ((int) $res['expires_in'] / 60));
        }
    }

    // 获取accessToken
    public function getAccessToken()
    {
        if (! Cache::has($this->guid . '_access_token')) {
            if ($this->open) {
                self::authorizerAccessToken();
            } else {
                self::advancedAccessToken();
            }
        }

        $this->accessToken = Cache::get($this->guid . '_access_token');
    }

    // 获取jsapi_ticket及卡券api_ticket
    public function getJsApiTicket()
    {
        if (! Cache::has($this->guid . '_js_api_ticket')) {
            self::getAccessToken();
            $data = [
                'key' => 'getJsApiTicket',
                'value' => [
                    'access_token' => Cache::get($this->guid . '_access_token'),
                    'type' => 'jsapi'
                ]
            ];

            $sendUrl = self::getParameterUrl($data);
            $req = self::curlPost($sendUrl);
            $res = (array) json_decode($req);
            if (! empty($res['ticket'])) {
               Cache::put($this->guid . '_js_api_ticket', $res['ticket'], ((int) $res['expires_in'] / 60));
            }
        }

        if (! Cache::has($this->guid . '_js_api_ticket_card')) {
            self::getAccessToken();
            $data = [
                'key' => 'getJsApiTicket',
                'value' => [
                    'access_token' => Cache::get($this->guid . '_access_token'),
                    'type' => 'wx_card'
                ]
            ];

            $sendUrl = self::getParameterUrl($data);
            $req = self::curlPost($sendUrl);
            $res = (array) json_decode($req);
            if (! empty($res['ticket'])) {
               Cache::put($this->guid . '_js_api_ticket_card', $res['ticket'], ((int) $res['expires_in'] / 60));
            }
        }

        $this->jsApiTicket = Cache::get($this->guid . '_js_api_ticket');
        $this->jsApiTicketCard = Cache::get($this->guid . '_js_api_ticket_card');
    }

    // 网页授权获取用户信息请求code
    public function oauth2Authorize($data)
    {
        $res = [];

        if ($this->open) {
            $res = self::oauth2AuthorizeComponent($data);
        } else {
            $res = self::oauth2AuthorizeAdvanced($data);
        }

        return $res;
    }

    // 托管机制网页授权获取用户信息请求code
    public function oauth2AuthorizeComponent($data = '')
    {
        $res = [];

        if (! empty($data)) {
             $data = [
                'key' => 'oauth2Authorize',
                'value' => [
                    'appid' => $data['appid'],
                    'redirect_uri' => $data['redirect_uri'],
                    'response_type' => 'code',
                    'scope' => 'snsapi_userinfo',
                    'state' => $data['state'],
                    'component_appid' => $this->appid,
                ]
            ];

            $res = self::getParameterUrl($data);
        }

        return $res;
    }

    // 开发者机制网页授权获取用户信息请求code
    public function oauth2AuthorizeAdvanced($data = '')
    {
        $res = [];

        if (! empty($data)) {
             $data = [
                'key' => 'oauth2Authorize',
                'value' => [
                    'appid' => $this->appid,
                    'redirect_uri' => $data['redirect_uri'],
                    'response_type' => 'code',
                    'scope' => 'snsapi_userinfo',
                    'state' => $data['state'] . '@@@' . $this->guid
                ]
            ];

            $res = self::getParameterUrl($data) . '#wechat_redirect';
        }

        return $res;
    }

    // 网页授权获取用户信息通过code换取access_token及openid
    public function oauth2Component($data)
    {
        $res = [];

        if (! empty($data['appid'])) {
            $res = self::oauth2ComponentAccessToken($data);
        } else {
            $res = self::oauth2AccessToken($data);
        }

        return $res;
    }
    
    // 托管机制网页授权获取用户信息通过code换取access_token及openid
    public function oauth2ComponentAccessToken($data = '')
    {
        $res = [];

        if (! empty($data) && $guid = \App\Models\TowerWechat::where('appid', $data['appid'])->pluck('guid')) {
            self::componentAccessToken();

            $arr = [
                'type' => 'oauth2Component',
                'action' => [],
                'parameter' => [
                    'key' => 'oauth2Component',
                    'value' => [
                        'appid' => $data['appid'],
                        'code' => $data['code'],
                        'grant_type' => 'authorization_code',
                        'component_appid' => $this->appid,
                        'component_access_token' => Cache::get('component_access_token')
                    ]
                ]
            ];

            $dt = (array) json_decode(self::postMsg($arr));
            if (! empty($dt['openid'])) {
                $res = [
                    'guid' => $guid,
                    'appid' => $data['appid'],
                    'openid' => $dt['openid'],
                    'state' => $data['state'],
                    'access_token' => $dt['access_token'],
                    'refresh_token' => $dt['refresh_token'],
                    'unionid' => ! empty($dt['unionid']) ? $dt['unionid'] : ''
                ];
            }
        }

        return $res;
    }

    // 开发者机制网页授权获取用户信息通过code换取access_token及openid
    public function oauth2AccessToken($data = '')
    {
        $res = [];
        if (! empty($data)) {
            $arr = [
                'type' => 'getAccessTokenOauth2',
                'action' => [],
                'parameter' => [
                    'key' => 'getAccessTokenOauth2',
                    'value' => [
                        'appid' => $this->appid,
                        'secret' => $this->appsecret,
                        'code' => $data['code'],
                        'grant_type' => 'authorization_code'
                    ]
                ]
            ];

            $dt = (array) json_decode(self::postMsg($arr));
            if (! empty($dt['openid'])) {
                $res = [
                    'guid' => $this->guid,
                    'appid' => $this->appid,
                    'openid' => $dt['openid'],
                    'state' => $data['state'],
                    'access_token' => $dt['access_token'],
                    'refresh_token' => $dt['refresh_token'],
                    'unionid' => ! empty($dt['unionid']) ? $dt['unionid'] : ''
                ];
            }
        }

        return $res;
    }

    // 判断结果状态
    public function checkIsSuc($res)
    {
        $result = ['errcode' => 'success', 'data' => '', 'errmsg' => ''];
        if (is_string($res)) {
            $res = json_decode($res, true);
        }

        if (isset($res['errcode']) && $res['errcode'] != 0) {
            $errcode = $res['errcode'];
            $result['errcode'] = 'error';
            $result['errmsg'] = $this->errcode[$errcode];
        } else {
            $result['data'] = $res;
        }

        return $result;
    }
    
    // 封装AppId access_token Cache Key
    public function getAppIdCacheKey($appid, $openid = '', $oauth2 = '')
    {
        if (! empty($oauth2) && ! empty($openid)) {
            if ($oauth2 == 'oauth2') {
                return $openid . '@' . $appid . '_wechat_access_token_oauth2';
            } elseif ($oauth2 == 'refresh') {
                return $openid . '@' . $appid . '_wechat_refresh_access_token_oauth2';
            }
        } else {
            return $appid . '_wechat_access_token';
        }
    }
    
    // 验证微信发起请求
    public function checkSignature()
    {
        if (! $this->token) {
            return false;
        }
        
        $token = $this->token;
        $signature = Input::get('signature');
        $timestamp = Input::get('timestamp');
        $nonce = Input::get('nonce');
        
        $tmpArr = array(
            $token,
            $timestamp,
            $nonce
        );
        
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        
        if ($tmpStr == $signature) {
            if (Input::get('echostr')) {
                echo Input::get('echostr');
            }
            
            return true;
        } else {
            return false;
        }
    }
    
    // 微信接收消息数据转数组
    public function postObjList($postObj)
    {
        // 消息基本信息
        $result = array(
            'fromUser' => (string) htmlspecialchars($postObj->FromUserName),
            'toUser' => (string) htmlspecialchars($postObj->ToUserName),
            'createTime' => (int) $postObj->CreateTime,
            'created_at' => date('Y-m-d H:i:s', (int) $postObj->CreateTime),
            'msgType' => (string) strtolower((string) $postObj->MsgType)
        );
        
        // 消息ID
        if (property_exists($postObj, 'MsgId')) {
            $result['msgId'] = (int) $postObj->MsgId;
        }
        
        // 消息类型
        switch ($result['msgType']) {
            case 'text': // 文本
                $result['content'] = (string) $postObj->Content; // Content 消息内容
                break;
            case 'location': // 地理位置
                $result['location_x'] = (float) $postObj->Location_X; // 地理位置纬度
                $result['location_y'] = (float) $postObj->Location_Y; // 地理位置经度
                $result['scale'] = (float) $postObj->Scale; // 地图缩放大小
                $result['label'] = (string) $postObj->Label; // 地理位置信息
                break;
            case 'image': // 图片
                $result['picUrl'] = (string) $postObj->PicUrl; // 图片链接，可用HTTP GET获取
                $result['mediaId'] = (string) $postObj->MediaId; // 图片消息媒体id，可调用多媒体文件下载接口拉取数据。
                break;
            case 'video': // 视频
                $result['mediaId'] = (string) $postObj->MediaId; // 图片消息媒体id，可调用多媒体文件下载接口拉取数据。
                $result['thumbMediaId'] = (string) $postObj->ThumbMediaId; // 视频消息缩略图的媒体id，可调用多媒体文件下载接口拉取数据。
                break;
            case 'link': // 链接
                $result['title'] = (string) $postObj->Title;
                $result['description'] = (string) $postObj->Description;
                $result['url'] = (string) $postObj->Url;
                break;
            case 'voice': // 语音
                $result['mediaId'] = (string) $postObj->MediaId;
                $result['format'] = (string) $postObj->Format;
                if (property_exists($postObj, 'Recognition')) {
                    $result['recognition'] = (string) $postObj->Recognition;
                }
                
                break;
            case 'event': // 事件
                $result['event'] = strtolower((string) $postObj->Event);
                switch ($result['event']) {
                    case 'subscribe': // 订阅
                        if (property_exists($postObj, 'EventKey')) { // 扫带参数二维码
                            $result['eventKey'] = (string) $postObj->EventKey;
                            $result['ticket'] = (string) $postObj->Ticket;
                        }

                        break;
                    case 'unsubscribe': // 取消订阅
                        break;
                    case 'scan': // 扫带参数二维码
                        if (property_exists($postObj, 'EventKey')) {
                            $result['eventKey'] = (string) $postObj->EventKey;
                            $result['ticket'] = (string) $postObj->Ticket;
                        }

                        break;
                    case 'location': // 地理位置
                        $result['latitude'] = (string) $postObj->Latitude;
                        $result['longitude'] = (string) $postObj->Longitude;
                        $result['precision'] = (string) $postObj->Precision;
                        break;
                    case 'view': // 链接
                        if (property_exists($postObj, 'EventKey')) {
                            $result['eventKey'] = (string) $postObj->EventKey;
                        }

                        break;
                    case 'click': // 点击
                        $result['eventKey'] = (string) $postObj->EventKey;
                        break;
                    case 'location_select': // 弹出地理位置选择器
                    case 'scancode_push': // 扫码推事件
                    case 'scancode_waitmsg': // 扫码推事件且弹出“消息接收中”提示框
                    case 'pic_sysphoto': // 弹出系统拍照发图
                    case 'pic_photo_or_album': // 弹出拍照或者相册发图
                    case 'pic_weixin': // 弹出微信相册发图器
                        break;
                    case 'card_pass_check': // 卡券通过审核
                    case 'card_not_pass_check': // 卡券未通过审核
                    case 'user_consume_card': // 卡券核销事件
                    case 'user_get_card': // 用户领取卡券
                    case 'user_del_card': // 用户删除卡券
                        if (property_exists($postObj, 'CardId')) {
                            $result['CardId'] = (string) $postObj->CardId;

                            if (property_exists($postObj, 'UserCardCode')) {
                                $result['UserCardCode'] = (string) $postObj->UserCardCode;

                                if (property_exists($postObj, 'IsGiveByFriend')) {
                                    $result['IsGiveByFriend'] = (string) $postObj->IsGiveByFriend;
                                } elseif (property_exists($postObj, 'FriendUserName')) {
                                    $result['FriendUserName'] = (string) $postObj->FriendUserName;
                                } elseif (property_exists($postObj, 'OldUserCardCode')) {
                                    $result['OldUserCardCode'] = (string) $postObj->OldUserCardCode;
                                }
                            }
                        }

                        break;
                    case 'poi_check_notify': // 门店审核事件
                        $result['UniqId'] = (string) $postObj->UniqId;
                        $result['Result'] = (string) $postObj->Result;
                        if (property_exists($postObj, 'PoiId')) {
                            $result['PoiId'] = (string) $postObj->PoiId;
                        }
                        
                        break;
                }

                break;
        }
        
        return $result;
    }
    
    // 被动推送模版
    public function sendPassiveXmlTpl($type, $data = array())
    {
        switch ($type) {
            case 'sendMsgText': // 文本消息
                $tpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[text]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        </xml>";
                $res = sprintf($tpl, $data['touser'], $data['fromuser'], time(), $data['content']);
                break;
            case 'sendMsgImage': // 图片消息
                $tpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[image]]></MsgType>
                        <Image>
                        <MediaId><![CDATA[%s]]></MediaId>
                        </Image>
                        </xml>";
                $res = sprintf($tpl, $data['touser'], $data['fromuser'], time(), $data['media_id']);
                break;
            case 'sendMsgVoice': // 语音消息
                $tpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[voice]]></MsgType>
                        <Voice>
                        <MediaId><![CDATA[%s]]></MediaId>
                        </Voice>
                        </xml>";
                $res = sprintf($tpl, $data['touser'], $data['fromuser'], time(), $data['media_id']);
                break;
            case 'sendMsgVideo': // 视频消息
                $tpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[video]]></MsgType>
                        <Video>
                        <MediaId><![CDATA[%s]]></MediaId>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                        </Video>
                        </xml>";
                $res = sprintf($tpl, $data['touser'], $data['fromuser'], time(), $data['media_id'], $data['title'], $data['description']);
                break;
            case 'sendMsgMusic': // 音乐消息
                $tpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[music]]></MsgType>
                        <Music>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                        <MusicUrl><![CDATA[%s]]></MusicUrl>
                        <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                        <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
                        </Music>
                        </xml>";
                $res = sprintf($tpl, $data['touser'], $data['fromuser'], time(), $data['title'], $data['description'], $data['musicUrl'], $data['HqMusicUrl'], $data['thumbMediaId']);
                break;
            case 'sendMsgNews': // 图文消息
                $tpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[news]]></MsgType>
                        <ArticleCount>%s</ArticleCount>
                        <Articles>%s</Articles>
                        </xml>";
                $res = sprintf($tpl, $data['touser'], $data['fromuser'], time(), $data['articleCount'], $data['articles']);
                break;
            case 'transferCustomerService': // 消息转发到多客服
                $tpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[transfer_customer_service]]></MsgType>
                        </xml>";
                $res = sprintf($tpl, $data['touser'], $data['fromuser'], time());
                break;
            case 'transferCustomerServiceKfAccount': // 消息转发到指定客服
                $tpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[transfer_customer_service]]></MsgType>
                        <TransInfo>
                           <KfAccount>![CDATA[%s]]</KfAccount>
                        </TransInfo>
                        </xml>";
                $res = sprintf($tpl, $data['touser'], $data['fromuser'], time(), $data['kfAccount']);
                break;
            default: // 无任何推送通知
                $res = '-2';
                break;
        }

        return $res;
    }

    // 主动推送模版
    public function sendJsonData($type, $data = array())
    {
        // 推送类型选择
        $sendJson = array(
            
            // 文本消息
            'sendMsgText' => array(
                'touser' => ! empty($data['touser']) ? $data['touser'] : '',
                'msgtype' => 'text',
                'text' => array(
                    'content' => ! empty($data['content']) ? $data['content'] : ''
                )
            ),
            
            // 图片消息
            'sendMsgImage' => array(
                'touser' => ! empty($data['touser']) ? $data['touser'] : '',
                'msgtype' => 'image',
                'image' => array(
                    'media_id' => ! empty($data['media_id']) ? $data['media_id'] : ''
                )
            ),
            
            // 语音消息
            'sendMsgVoice' => array(
                'touser' => ! empty($data['touser']) ? $data['touser'] : '',
                'msgtype' => 'voice',
                'voice' => array(
                    'media_id' => ! empty($data['media_id']) ? $data['media_id'] : ''
                )
            ),
            
            // 视频消息
            'sendMsgVideo' => array(
                'touser' => ! empty($data['touser']) ? $data['touser'] : '',
                'msgtype' => 'video',
                'video' => array(
                    'media_id' => ! empty($data['media_id']) ? $data['media_id'] : '',
                    'title' => ! empty($data['title']) ? $data['title'] : '',
                    'description' => ! empty($data['description']) ? $data['description'] : ''
                )
            ),
            
            // 音乐消息
            'sendMsgMusic' => array(
                'touser' => ! empty($data['touser']) ? $data['touser'] : '',
                'msgtype' => 'music',
                'music' => array(
                    'title' => ! empty($data['title']) ? $data['title'] : '',
                    'description' => ! empty($data['description']) ? $data['description'] : '',
                    'musicurl' => ! empty($data['musicurl']) ? $data['musicurl'] : '',
                    'hqmusicurl' => ! empty($data['hqmusicurl']) ? $data['hqmusicurl'] : '',
                    'thumb_media_id' => ! empty($data['thumb_media_id']) ? $data['thumb_media_id'] : ''
                )
            ),
            
            // 图文消息
            'sendMsgNews' => array(
                'touser' => ! empty($data['touser']) ? $data['touser'] : '',
                'msgtype' => 'news',
                'news' => array(
                    'articles' => ! empty($data['articles']) ? $data['articles'] : ''
                )
            ),
            
            // 创建菜单
            'createMenu' => array(
                'button' => ! empty($data['button']) ? $data['button'] : ''
            ),
            
            // 创建分组
            'createGroup' => array(
                'group' => array(
                    'name' => ! empty($data['name']) ? $data['name'] : ''
                )
            ),
            
            // 修改分组
            'updateGroup' => array(
                'group' => array(
                    'id' => ! empty($data['id']) ? $data['id'] : '',
                    'name' => ! empty($data['name']) ? $data['name'] : ''
                )
            ),
            
            // 移动用户所在分组
            'moveGroup' => array(
                'openid' => ! empty($data['openid']) ? $data['openid'] : '',
                'to_groupid' => ! empty($data['to_groupid']) ? $data['to_groupid'] : ''
            ),
            
            // 查询用户所在分组
            'getGroupId' => array(
                'openid' => ! empty($data['openid']) ? $data['openid'] : ''
            ),

            // 删除分组
            'deleteGroup' => array(
                'group' => array(
                    'id' => ! empty($data['id']) ? $data['id'] : ''
                )
            ),
            
            // 设置用户备注名
            'userRemark' => array(
                'openid' => ! empty($data['openid']) ? $data['openid'] : '',
                'remark' => ! empty($data['remark']) ? $data['remark'] : ''
            ),
            
            // 创建永久二维码ticket
            'createQrcode' => array(
                // QR_LIMIT_SCENE:永久;QR_LIMIT_STR_SCENE为永久的字符串参数值;QR_SCENE:临时,expire_seconds:604800(7天)
                'action_name' => 'QR_LIMIT_STR_SCENE',
                'action_info' => array(
                    'scene' => array(
                        // 'scene_id' => ! empty($data['scene_id']) ? $data['scene_id'] : '',
                        'scene_str' => ! empty($data['scene_str']) ? $data['scene_str'] : ''
                    )
                )
            ),
            
            // 添加客服账号 (密码为明文的32位加密MD5值)
            'addKfAccount' => array(
                'kf_account' => ! empty($data['kf_account']) ? $data['kf_account'] : '', // 格式: 账号前缀@公众号微信号 (账号前缀最多10个字符)
                'nickname' => ! empty($data['nickname']) ? $data['nickname'] : '', // 最长6个汉字或12个英文字符
                'password' => ! empty($data['password']) ? $data['password'] : ''
            ),
            
            // 设置客服信息
            'updateKfAccount' => array(
                'kf_account' => ! empty($data['kf_account']) ? $data['kf_account'] : '',
                'nickname' => ! empty($data['nickname']) ? $data['nickname'] : '',
                'password' => ! empty($data['password']) ? $data['password'] : ''
            ),
            
            // 上传客服头像 (jpg: 640*640{最佳})
            'uploadHeadImgKfAccount' => array(
                'kf_account' => ! empty($data['kf_account']) ? $data['kf_account'] : '',
                'media' => ! empty($data['media']) ? $data['media'] : ''
            ),

            // 新增永久图文素材
            'addNews' => array(
                'articles' => ! empty($data['articles']) ? $data['articles'] : ''
            ),

            // 新增其他类型永久素材
            // 分别有图片（image）、语音（voice）、视频（video）和缩略图（thumb）
            'addMaterial' => array(
                'type' => ! empty($data['type']) ? $data['type'] : '',
                'media' => ! empty($data['media']) ? $data['media'] : ''
            ),

            // 删除永久图文素材
            'delMaterial' => array(
                'media_id' => ! empty($data['media_id']) ? $data['media_id'] : ''
            ),

            //下载用户素材
            'downloadMedia' => array(
                'media_id' => ! empty($data['media_id']) ? $data['media_id'] : ''
            ),

            // // 预览群发消息接口 (订阅号与服务号认证后均可用)
            // 'massPreview' => ! empty($data) ? self::massPreviewData($data) : '',
            
            // // 根据分组进行群发 (订阅号与服务号认证后均可用)
            // 'massAll' => ! empty($data) ? self::massAllData($data) : '',
            
            // // 根据OpenID列表群发 (订阅号不可用, 服务号认证后可用)
            // 'massOpenId' => ! empty($data) ? self::massOpenIdData($data) : '',
            
            // // 删除群发 (订阅号与服务号认证后均可用)
            // 'deleteMass' => array(
            //     'msg_id' => ! empty($data['msg_id']) ? $data['msg_id'] : ''
            // ),
            
            // 查询群发消息发送状态 (订阅号与服务号认证后均可用)
            'getMass' => array(
                'msg_id' => ! empty($data['msg_id']) ? $data['msg_id'] : ''
            ),

            // 获取用户增减数据 (最大时间跨度: 7天)
            'getUserSummary' => array(
                'begin_date' => ! empty($data['begin_date']) ? $data['begin_date'] : '',
                'end_date' => ! empty($data['end_date']) ? $data['end_date'] : ''
            ),
            
            // 获取累计用户数据 (最大时间跨度: 7天)
            'getUserCumulate' => array(
                'begin_date' => ! empty($data['begin_date']) ? $data['begin_date'] : '',
                'end_date' => ! empty($data['end_date']) ? $data['end_date'] : ''
            ),
            
            // 获取图文群发每日数据 (最大时间跨度: 1天)
            'getArticleSummary' => array(
                'begin_date' => ! empty($data['begin_date']) ? $data['begin_date'] : '',
                'end_date' => ! empty($data['end_date']) ? $data['end_date'] : ''
            ),
            
            // 获取图文群发总数据 (最大时间跨度: 1天)
            'getArticleTotal' => array(
                'begin_date' => ! empty($data['begin_date']) ? $data['begin_date'] : '',
                'end_date' => ! empty($data['end_date']) ? $data['end_date'] : ''
            ),
            
            // 获取图文统计数据 (最大时间跨度: 3天)
            'getUserRead' => array(
                'begin_date' => ! empty($data['begin_date']) ? $data['begin_date'] : '',
                'end_date' => ! empty($data['end_date']) ? $data['end_date'] : ''
            ),
            
            // 获取图文统计分时数据 (最大时间跨度: 1天)
            'getUserReadHour' => array(
                'begin_date' => ! empty($data['begin_date']) ? $data['begin_date'] : '',
                'end_date' => ! empty($data['end_date']) ? $data['end_date'] : ''
            ),
            
            // 获取图文分享转发数据 (最大时间跨度: 7天)
            'getUserShare' => array(
                'begin_date' => ! empty($data['begin_date']) ? $data['begin_date'] : '',
                'end_date' => ! empty($data['end_date']) ? $data['end_date'] : ''
            ),
            
            // 获取图文分享转发分时数据 (最大时间跨度: 1天)
            'getUserShareHour' => array(
                'begin_date' => ! empty($data['begin_date']) ? $data['begin_date'] : '',
                'end_date' => ! empty($data['end_date']) ? $data['end_date'] : ''
            ),
            
            // 获取消息发送概况数据 (最大时间跨度: 7天)
            'getUpStreamMsg' => array(
                'begin_date' => ! empty($data['begin_date']) ? $data['begin_date'] : '',
                'end_date' => ! empty($data['end_date']) ? $data['end_date'] : ''
            ),
            
            // 获取消息分送分时数据 (最大时间跨度: 1天)
            'getUpStreamMsgHour' => array(
                'begin_date' => ! empty($data['begin_date']) ? $data['begin_date'] : '',
                'end_date' => ! empty($data['end_date']) ? $data['end_date'] : ''
            ),
            
            // 获取消息发送周数据 (最大时间跨度: 30天)
            'getUpStreamMsgWeek' => array(
                'begin_date' => ! empty($data['begin_date']) ? $data['begin_date'] : '',
                'end_date' => ! empty($data['end_date']) ? $data['end_date'] : ''
            ),
            
            // 获取消息发送月数据 (最大时间跨度: 30天)
            'getUpStreamMsgMonth' => array(
                'begin_date' => ! empty($data['begin_date']) ? $data['begin_date'] : '',
                'end_date' => ! empty($data['end_date']) ? $data['end_date'] : ''
            ),
            
            // 获取消息发送分布数据 (最大时间跨度: 15天)
            'getUpStreamMsgDist' => array(
                'begin_date' => ! empty($data['begin_date']) ? $data['begin_date'] : '',
                'end_date' => ! empty($data['end_date']) ? $data['end_date'] : ''
            ),
            
            // 获取消息发送分布周数据 (最大时间跨度: 30天)
            'getUpStreamMsgDistWeek' => array(
                'begin_date' => ! empty($data['begin_date']) ? $data['begin_date'] : '',
                'end_date' => ! empty($data['end_date']) ? $data['end_date'] : ''
            ),
            
            // 获取消息发送分布月数据 (最大时间跨度: 30天)
            'getUpStreamMsgDistMonth' => array(
                'begin_date' => ! empty($data['begin_date']) ? $data['begin_date'] : '',
                'end_date' => ! empty($data['end_date']) ? $data['end_date'] : ''
            ),
            
            // 获取接口分析数据 (最大时间跨度: 30天)
            'getInterFaceSummary' => array(
                'begin_date' => ! empty($data['begin_date']) ? $data['begin_date'] : '',
                'end_date' => ! empty($data['end_date']) ? $data['end_date'] : ''
            ),
            
            // 获取接口分析分时数据 (最大时间跨度: 1天)
            'getInterFaceSummaryHour' => array(
                'begin_date' => ! empty($data['begin_date']) ? $data['begin_date'] : '',
                'end_date' => ! empty($data['end_date']) ? $data['end_date'] : ''
            ),

            // 申请设备ID
            'shakearoundDeviceApplyid' => array(
                'quantity' => ! empty($data['quantity']) ? $data['quantity'] : '',
                'apply_reason' => ! empty($data['apply_reason']) ? $data['apply_reason'] : '',
                'comment' => ! empty($data['comment']) ? $data['comment'] : ''
            ),

            // 编辑设备信息
            'shakearoundDeviceUpdate' => array(
                'comment' => ! empty($data['comment']) ? $data['comment'] : '',
                'device_identifier' => array(
                    'device_id' => ! empty($data['device_id']) ? $data['device_id'] : ''
                )
            ),

            // 配置设备与门店的关联关系
            'shakearoundDeviceBindlocation' => array(
                'poi_id' => ! empty($data['poi_id']) ? $data['poi_id'] : '',
                'device_identifier' => array(
                    'device_id' => ! empty($data['device_id']) ? $data['device_id'] : ''
                )
            ),

            // 配置设备与页面的关联关系
            'shakearoundDeviceBindpage' => array(
                'page_ids' => ! empty($data['poi_id']) ? $data['page_ids'] : '',
                'bind' => ! empty($data['bind']) ? $data['bind'] : '',
                'append' => ! empty($data['append']) ? $data['append'] : '',
                'device_identifier' => array(
                    'device_id' => ! empty($data['device_id']) ? $data['device_id'] : ''
                )
            ),

            // 查询设备列表
            'shakearoundDeviceSearch' => array(
                'begin' => isset($data['begin']) ? $data['begin'] : '',
                'count' => ! empty($data['count']) ? $data['count'] : ''
            ),

            // 根据批次ID查询设备列表
            'shakearoundDeviceApply' => array(
                'begin' => isset($data['begin']) ? $data['begin'] : '',
                'count' => ! empty($data['count']) ? $data['count'] : ''
            ),

            // 上传摇一摇图片素材
            'shakearoundMaterialAdd' => array(
                'media' => ! empty($data['media']) ? $data['media'] : ''
            ),

            // 添加摇一摇页面
            'shakearoundPageAdd' => array(
                'title' => ! empty($data['title']) ? $data['title'] : '',
                'description' => ! empty($data['description']) ? $data['description'] : '',
                'page_url' => ! empty($data['page_url']) ? $data['page_url'] : '',
                'icon_url' => ! empty($data['icon_url']) ? $data['icon_url'] : '',
                'comment' => ! empty($data['comment']) ? $data['comment'] : ''
            ),

            // 编辑摇一摇页面
            'shakearoundPageUpdate' => array(
                'page_id' => ! empty($data['page_id']) ? $data['page_id'] : '',
                'title' => ! empty($data['title']) ? $data['title'] : '',
                'description' => ! empty($data['description']) ? $data['description'] : '',
                'page_url' => ! empty($data['page_url']) ? $data['page_url'] : '',
                'icon_url' => ! empty($data['icon_url']) ? $data['icon_url'] : '',
                'comment' => ! empty($data['comment']) ? $data['comment'] : ''
            ),

            // 删除摇一摇页面
            'shakearoundPageDelete' => array(
                'page_ids' => ! empty($data['page_ids']) ? $data['page_ids'] : ''
            ),

            // 编辑设备信息
            'shakearoundDeviceUpdate' => array(
                'comment' => ! empty($data['comment']) ? $data['comment'] : '',
                'device_identifier' => array(
                    'device_id' => ! empty($data['device_id']) ? $data['device_id'] : ''
                )
            ),

            // 配置设备与门店的关联关系
            'shakearoundDeviceBindlocation' => array(
                'poi_id' => ! empty($data['poi_id']) ? $data['poi_id'] : '',
                'device_identifier' => array(
                    'device_id' => ! empty($data['device_id']) ? $data['device_id'] : ''
                )
            ),

            // 配置设备与页面的关联关系
            'shakearoundDeviceBindpage' => array(
                'page_ids' => ! empty($data['page_ids']) ? $data['page_ids'] : '',
                'bind' => isset($data['bind']) ? $data['bind'] : '',
                'append' => isset($data['append']) ? $data['append'] : '',
                'device_identifier' => array(
                    'device_id' => ! empty($data['device_id']) ? $data['device_id'] : ''
                )
            ),

            // 微信开放平台
            // 获取接管方component_access_token
            'componentAccessToken' => array(
                'component_appid' => isset($data['component_appid']) ? $data['component_appid'] : '',
                'component_appsecret' => isset($data['component_appsecret']) ? $data['component_appsecret'] : '',
                'component_verify_ticket' => isset($data['component_verify_ticket']) ? $data['component_verify_ticket'] : ''
            ),

            // 更新接管方预授权码pre_auth_code
            'apiCreatePreauthcode' => array(
                'component_appid' => isset($data['component_appid']) ? $data['component_appid'] : ''
            ),

            // 授权处理回调
            'componentApiQueryAuth' => array(
                'component_appid' => isset($data['component_appid']) ? $data['component_appid'] : '',
                'authorization_code' => isset($data['authorization_code']) ? $data['authorization_code'] : ''
            ),

            // 获取刷新令牌authorizer_refresh_token
            'authorizerRefreshToken' => array(
                'component_appid' => isset($data['component_appid']) ? $data['component_appid'] : '',
                'authorizer_appid' => isset($data['authorizer_appid']) ? $data['authorizer_appid'] : '',
                'authorizer_refresh_token' => isset($data['authorizer_refresh_token']) ? $data['authorizer_refresh_token'] : ''
            ),

            // 获取授权方的账户信息
            'apiGetAuthorizerInfo' => array(
                'component_appid' => isset($data['component_appid']) ? $data['component_appid'] : '',
                'authorization_code' => isset($data['authorization_code']) ? $data['authorization_code'] : ''
            ),

            // 卡券接口
            // 上传LOGO
            'uploadImg' => array(
                'buffer' => ! empty($data['buffer']) ? $data['buffer'] : ''
            ),

            // 创建卡券
            'cardCreate' => array(
                'card' => ! empty($data['card']) ? $data['card'] : ''
            ),

            // 删除卡券
            'cardDelete' => array(
                'card_id' => ! empty($data['card_id']) ? $data['card_id'] : ''
            ),

            // 更改卡券信息
            'cardUpdate' => ! empty($data['card']) ? $data['card'] : '',

            // 修改库存
            'modifyStock' => ! empty($data['card']) ? $data['card'] : '',

            // 卡券二维码
            'cardQrcode' => array(
                'action_name' => 'QR_CARD',
                'action_info' => array(
                    'card' => array(
                        'card_id' => ! empty($data['card_id']) ? $data['card_id'] : ''
                    )
                )
            ),

            // 卡券核销
            'cardCode' => array(
                'code' => ! empty($data['code']) ? $data['code'] : ''
            ),

            // 创建门店
            'addPoi' => array(
                'business' => ! empty($data['business']) ? $data['business'] : ''
            ),

            // 修改门店服务信息
            'updatePoi' => array(
                'business' => ! empty($data['business']) ? $data['business'] : ''
            ),

            // 删除门店
            'delPoi' => ! empty($data['poi_id']) ? $data['poi_id'] : ''
        );

        if (empty($type) || empty($data) || empty($sendJson[$type])) {
            return '-2';
        } else {
            $upload = false;
            $download = false;

            switch ($type) {
                case 'addMaterial':
                case 'shakearoundMaterialAdd':
                case 'uploadImg':
                    $upload = true;
                    break;
                case 'shakearoundDeviceApplyid':
                    if (! empty($data['poi_id'])) {
                        $sendJson[$type]['poi_id'] = $data['poi_id'];
                    }

                    break;
                case 'downloadMedia':
                    $download = true;
                    break;
                default:
                    break;
            }

            if ($upload) {
                return $sendJson[$type];
            } elseif ($download){
                return $sendJson[$type];
            } else {
                return json_encode($sendJson[$type], JSON_UNESCAPED_UNICODE);
            }
        }
    }
    
    // 预览群发消息接口数据匹配
    public function massPreviewData($data)
    {
        // 推送类型选择
        $sendJson = array(
            
            // 图文消息
            'mpnews' => array(
                'touser' => ! empty($data['touser']) ? $data['touser'] : '',
                'mpnews' => array(
                    'media_id' => ! empty($data['media_id']) ? $data['media_id'] : ''
                ),
                
                'msgtype' => 'mpnews'
            ),
            
            // 文本消息
            'text' => array(
                'touser' => ! empty($data['touser']) ? $data['touser'] : '',
                'text' => array(
                    'content' => ! empty($data['content']) ? $data['content'] : ''
                ),
                
                'msgtype' => 'text'
            ),
            
            // 语音消息
            'voice' => array(
                'touser' => ! empty($data['touser']) ? $data['touser'] : '',
                'voice' => array(
                    'media_id' => ! empty($data['media_id']) ? $data['media_id'] : ''
                ),
                
                'msgtype' => 'voice'
            ),
            
            // 图片消息
            'image' => array(
                'touser' => ! empty($data['touser']) ? $data['touser'] : '',
                'image' => array(
                    'media_id' => ! empty($data['media_id']) ? $data['media_id'] : ''
                ),
                
                'msgtype' => 'image'
            ),
            
            // 视频消息
            'mpvideo' => array(
                'touser' => ! empty($data['touser']) ? $data['touser'] : '',
                'mpvideo' => array(
                    'media_id' => ! empty($data['media_id']) ? $data['media_id'] : ''
                ),
                
                'msgtype' => 'mpvideo'
            )
        );
        
        if (empty($sendJson[$data['msgtype']])) {
            return '';
        } else {
            return json_encode($sendJson[$data['msgtype']], JSON_UNESCAPED_UNICODE);
        }
    }
    
    // 分组群发数据匹配
    public function massAllData($data)
    {
        // 推送类型选择
        $sendJson = array(
            
            // 图文消息
            'mpnews' => array(
                'filter' => array(
                    'is_to_all' => ! empty($data['is_to_all']) ? $data['is_to_all'] : '',
                    'group_id' => ! empty($data['group_id']) ? $data['group_id'] : ''
                ),
                
                'mpnews' => array(
                    'media_id' => ! empty($data['media_id']) ? $data['media_id'] : ''
                ),
                
                'msgtype' => 'mpnews',
                'title' => ! empty($data['title']) ? $data['title'] : '',
                'description' => ! empty($data['description']) ? $data['description'] : '',
                'thumb_media_id' => ! empty($data['thumb_media_id']) ? $data['thumb_media_id'] : ''
            ),
            
            // 文本消息
            'text' => array(
                'filter' => array(
                    'is_to_all' => ! empty($data['is_to_all']) ? $data['is_to_all'] : '',
                    'group_id' => ! empty($data['group_id']) ? $data['group_id'] : ''
                ),
                
                'text' => array(
                    'content' => ! empty($data['content']) ? $data['content'] : ''
                ),
                
                'msgtype' => 'text'
            ),
            
            // 语音消息
            'voice' => array(
                'filter' => array(
                    'is_to_all' => ! empty($data['is_to_all']) ? $data['is_to_all'] : '',
                    'group_id' => ! empty($data['group_id']) ? $data['group_id'] : ''
                ),
                
                'voice' => array(
                    'media_id' => ! empty($data['media_id']) ? $data['media_id'] : ''
                ),
                
                'msgtype' => 'voice'
            ),
            
            // 图片消息
            'image' => array(
                'filter' => array(
                    'is_to_all' => ! empty($data['is_to_all']) ? $data['is_to_all'] : '',
                    'group_id' => ! empty($data['group_id']) ? $data['group_id'] : ''
                ),
                
                'image' => array(
                    'media_id' => ! empty($data['media_id']) ? $data['media_id'] : ''
                ),
                
                'msgtype' => 'image'
            ),
            
            // 视频消息
            'mpvideo' => array(
                'filter' => array(
                    'is_to_all' => ! empty($data['is_to_all']) ? $data['is_to_all'] : '',
                    'group_id' => ! empty($data['group_id']) ? $data['group_id'] : ''
                ),
                
                'mpvideo' => array(
                    'media_id' => ! empty($data['media_id']) ? $data['media_id'] : ''
                ),
                
                'msgtype' => 'mpvideo'
            )
        );
        
        if (empty($sendJson[$data['msgtype']])) {
            return '';
        } else {
            return json_encode($sendJson[$data['msgtype']], JSON_UNESCAPED_UNICODE);
        }
    }
    
    // OpenID列表群发数据匹配
    public function massOpenIdData($data)
    {
        // 推送类型选择
        $sendJson = array(
            
            // 图文消息
            'mpnews' => array(
                'touser' => ! empty($data['touser']) ? $data['touser'] : '',
                'mpnews' => array(
                    'media_id' => ! empty($data['media_id']) ? $data['media_id'] : ''
                ),
                
                'msgtype' => 'mpnews',
                'title' => ! empty($data['title']) ? $data['title'] : '',
                'description' => ! empty($data['description']) ? $data['description'] : '',
                'thumb_media_id' => ! empty($data['thumb_media_id']) ? $data['thumb_media_id'] : ''
            ),
            
            // 文本消息
            'text' => array(
                'touser' => ! empty($data['touser']) ? $data['touser'] : '',
                'text' => array(
                    'content' => ! empty($data['content']) ? $data['content'] : ''
                ),
                
                'msgtype' => 'text'
            ),
            
            // 语音消息
            'voice' => array(
                'touser' => ! empty($data['touser']) ? $data['touser'] : '',
                'voice' => array(
                    'media_id' => ! empty($data['media_id']) ? $data['media_id'] : ''
                ),
                
                'msgtype' => 'voice'
            ),
            
            // 图片消息
            'image' => array(
                'touser' => ! empty($data['touser']) ? $data['touser'] : '',
                'image' => array(
                    'media_id' => ! empty($data['media_id']) ? $data['media_id'] : ''
                ),
                
                'msgtype' => 'image'
            ),
            
            // 视频消息
            'mpvideo' => array(
                'touser' => ! empty($data['touser']) ? $data['touser'] : '',
                'mpvideo' => array(
                    'media_id' => ! empty($data['media_id']) ? $data['media_id'] : ''
                ),
                
                'msgtype' => 'mpvideo'
            )
        );
        
        if (empty($sendJson[$data['msgtype']])) {
            return '';
        } else {
            return json_encode($sendJson[$data['msgtype']], JSON_UNESCAPED_UNICODE);
        }
    }
    
    // 不带参数地址匹配
    public function getUrl($data)
    {
        return $this->url[$data['key']];
    }
    
    // 带参数地址匹配
    public function getParameterUrl($data)
    {
        $url = $this->url[$data['key']];
        $arr = $data['value'];

        if (isset($arr['access_token'])) {
            self::getAccessToken();
            $arr['access_token'] = $this->accessToken;
        }
        
        if (str_contains($url, '?')) {
            $parameter = '&' . http_build_query($arr);
        } else {
            $parameter = '?' . http_build_query($arr);
        }

        return $url . $parameter;
    }

    // jsapi数据整合
    public function getJsApiData($arr)
    {
        // 推送类型选择
        $sendJson = [
            // 获取网络状态接口
            'getNetworkType' => [
                'method' => 'getNetworkType'
            ],

            // 隐藏右上角菜单接口
            'hideOptionMenu' => [
                'method' => 'hideOptionMenu'
            ],

            // 显示右上角菜单接口
            'showOptionMenu' => [
                'method' => 'showOptionMenu'
            ],

            // 关闭当前网页窗口接口
            'closeWindow' => [
                'method' => 'closeWindow'
            ],

            // 分享给朋友
            'onMenuShareAppMessage' => [
                'method' => 'onMenuShareAppMessage',
                'title' => ! empty($arr['data']['title']) ? $arr['data']['title'] : '', // 分享标题
                'desc' => ! empty($arr['data']['desc']) ? $arr['data']['desc'] : '', // 分享描述
                'link' => ! empty($arr['data']['link']) ? $arr['data']['link'] : '', // 分享链接
                'imgUrl' => ! empty($arr['data']['imgUrl']) ? $arr['data']['imgUrl'] : '', // 分享图标
                // 分享类型：music、video或link，不填默认为link
                'type' => ! empty($arr['data']['type']) ? $arr['data']['type'] : 'link',
                // 如果type是music或video，则要提供数据链接，默认为空
                'dataUrl' => ! empty($arr['data']['dataUrl']) ? $arr['data']['dataUrl'] : '',

            ],

            // 分享到朋友圈
            'onMenuShareTimeline' => [
                'method' => 'onMenuShareTimeline',
                'title' => ! empty($arr['data']['title']) ? $arr['data']['title'] : '',
                'link' => ! empty($arr['data']['link']) ? $arr['data']['link'] : '',
                'imgUrl' => ! empty($arr['data']['imgUrl']) ? $arr['data']['imgUrl'] : ''
            ],

            // 分享到QQ
            'onMenuShareQQ' => [
                'method' => 'onMenuShareQQ',
                'title' => ! empty($arr['data']['title']) ? $arr['data']['title'] : '',
                'desc' => ! empty($arr['data']['desc']) ? $arr['data']['desc'] : '',
                'link' => ! empty($arr['data']['link']) ? $arr['data']['link'] : '',
                'imgUrl' => ! empty($arr['data']['imgUrl']) ? $arr['data']['imgUrl'] : ''
            ],

            // 分享到QQ空间
            'onMenuShareQZone' => [
                'method' => 'onMenuShareQZone',
                'title' => ! empty($arr['data']['title']) ? $arr['data']['title'] : '',
                'desc' => ! empty($arr['data']['desc']) ? $arr['data']['desc'] : '',
                'link' => ! empty($arr['data']['link']) ? $arr['data']['link'] : '',
                'imgUrl' => ! empty($arr['data']['imgUrl']) ? $arr['data']['imgUrl'] : ''
            ],

            // 分享到腾讯微博
            'onMenuShareWeibo' => [
                'method' => 'onMenuShareWeibo',
                'title' => ! empty($arr['data']['title']) ? $arr['data']['title'] : '',
                'desc' => ! empty($arr['data']['desc']) ? $arr['data']['desc'] : '',
                'link' => ! empty($arr['data']['link']) ? $arr['data']['link'] : '',
                'imgUrl' => ! empty($arr['data']['imgUrl']) ? $arr['data']['imgUrl'] : ''
            ],

            // 使用微信内置地图查看位置接口
            'openLocation' => [
                'method' => 'openLocation',
                // 纬度，浮点数，范围为90 ~ -90
                'latitude' => ! empty($arr['data']['latitude']) ? $arr['data']['latitude'] : 0,
                // 经度，浮点数，范围为180 ~ -180
                'longitude' => ! empty($arr['data']['longitude']) ? $arr['data']['longitude'] : 0,
                'name' => ! empty($arr['data']['name']) ? $arr['data']['name'] : '', // 位置名
                'address' => ! empty($arr['data']['address']) ? $arr['data']['address'] : '', // 地址详情说明
                // 地图缩放级别,整形值,范围从1~28。默认为最大
                'scale' => ! empty($arr['data']['scale']) ? $arr['data']['scale'] : 1,
                // 在查看位置界面底部显示的超链接,可点击跳转
                'infoUrl' => ! empty($arr['data']['infoUrl']) ? $arr['data']['infoUrl'] : ''
            ],

            // 获取地理位置接口
            'getLocation' => [
                'method' => 'getLocation',
                'type' => 'wgs84' // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
            ],

            // 调起微信扫一扫接口
            'scanQRCode' => [
                'method' => 'scanQRCode',
                // 默认为0，扫描结果由微信处理，1则直接返回扫描结果
                'needResult' => ! empty($arr['data']['needResult']) ? $arr['data']['needResult'] : 1,
                'scanType' => ['qrCode', 'barCode'], // 可以指定扫二维码还是一维码，默认二者都有
            ],

            // 批量添加卡券接口
            'addCard' => [
                'method' => 'addCard',
                'cardId' => ! empty($arr['data']['cardId']) ? $arr['data']['cardId'] : '', // 卡券card_id
                'cardExt' => self::getSignPackageCard($arr) // 微信卡券扩展字段
            ]
        ];

        $res = [
            'config' => self::getSignPackage($arr),
            'data' => $sendJson[$arr['method']]
        ];

        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    // jsapi验证签名
    public function getSignPackage($data)
    {
        $this->guid = $data['guid'];
        self::getJsApiTicket();

        $js_api_ticket = $this->jsApiTicket; // js_api_ticket
        $nonceStr = self::createNonceStr(); // 随机字符串
        $timestamp = time(); // 时间戳
        $url = ! empty($data['url']) ? $data['url'] : \URL::current(); // 当前页面地址

        return [
            'appId' => \App\Models\TowerWechat::where('guid', $this->guid)->pluck('appid'),
            'timestamp'  => $timestamp,
            'nonceStr' => $nonceStr,
            // 这里参数的顺序要按照key值ASCII码升序排序
            'signature' => sha1("jsapi_ticket=$js_api_ticket&noncestr=$nonceStr&timestamp=$timestamp&url=$url")
        ];
    }

    // jscard验证签名
    public function getSignPackageCard($data)
    {
        self::getJsApiTicket();

        $card_id = ! empty($data['data']['cardId']) ? $data['data']['cardId'] : ''; // 卡券card_id
        $signature = time() . $this->jsApiTicketCard . $card_id;
        
        return [
            'timestamp'  => time(),
            'signature' => sha1($signature)
        ];
    }

    // 随机字符串
    private function createNonceStr($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';

        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }

        return $str;
    }
    
    // 模拟GET
    public function curlGet($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        
        if (! curl_exec($ch)) {
            error_log(curl_error($ch));
            $data = '';
        } else {
            $data = curl_multi_getcontent($ch);
        }
        curl_close($ch);
        
        return $data;
    }
    
    // 模拟POST
    public function curlPost($url, $data = '')
    {
        if (! function_exists('curl_init')) {
            return '';
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $data = curl_exec($ch);

        if (! $data) {
            error_log(curl_error($ch));
        }
        curl_close($ch);
        
        return $data;
    }

    // 模拟下载
    public function curlDownload($url, $data = '')
    {
        $url .= "&media_id=".$data['media_id'];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0); //只取body头
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        $httpinfo = curl_getinfo($ch);
        curl_close($ch);
        $mediaAll = array_merge(array('header' => $httpinfo), array('body' => $package));

        return $mediaAll;
    }
    
    // 模拟上传
    public function curlUpload($url, $data = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (! empty($data)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        if (! $data) {
            error_log(curl_error($ch));
        }
        curl_close($ch);

        return $data;
    }
}
