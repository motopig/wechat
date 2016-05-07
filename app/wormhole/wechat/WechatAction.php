<?php
namespace App\Wormhole;

use Ecdo\EcdoSpiderMan\SiteCommon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Lib\WechatCallBack;
use App\Lib\RouteCommon;
use Illuminate\Support\Facades\DB;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoHulk\WechatMediaUtils;
use Ecdo\EcdoHulk\WechatAutoReplyUtils;
use Ecdo\EcdoHulk\WechatCodeUtils;
use Ecdo\EcdoBatMan\EntityShopUtils;

/**
 * 微信接口执行类
 *
 * @category yunke
 * @package app\api\wechat
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatAction extends SiteCommon
{
    // 商家平台唯一识别
    public $guid;
    // 微信公用配置类对象
    public $wcb;

    public function __construct($open = [])
    {
        if (! empty($open)) {
            $this->guid = $open['guid'];
            $this->wcb = $open['wcb'];
        } else {
            parent::__construct();
            $this->guid = $this->params['tower'];
            $this->wcb = new WechatCallBack([
                'appid' => $this->params['wechat']['appid'],
                'appsecret' => $this->params['wechat']['appsecret'],
                'token' => $this->params['wechat']['token'],
                'encodingAesKey' => $this->params['wechat']['encodingAesKey'],
                'open' => $this->params['wechat']['open'],
                'guid' => $this->guid
            ]);
        }
    }
    
    // 微信消息接收处理
    public function index()
    {
        if (! $data = $this->wcb->getMsg()) {
            exit(0);
        }

        self::msg($data);
        return '';
    }

    // 消息体方法
    public function msg($data, $object = '')
    {
        if (! empty($data)) {
            // 微信消息排序处理机制原则
            self::concern($data);
            self::location($data);
            self::autoReply($data);
            self::scan($data);
            self::event($data);
            self::message($data);
            self::menu($data);
        }
    }

    // 订阅事件处理
    public function concern($data = [])
    {
        if (! empty($data)) {
            $wmu = new \Ecdo\EcdoHulk\WechatMemberUtils();

            switch ($data['msgType']) {
                case 'event':
                    switch ($data['event']) {
                        case 'subscribe':
                            $wmu->concern($data);
                            break;
                        case 'unsubscribe':
                            $wmu->concern($data);
                        default:
                            $wmu->concernOther($data);
                            break;
                    }
                    
                    break;
            }
        }
    }

    // 消息存储处理
    public function message($data = [])
    {
        if (! empty($data)) {
            $wmeu = new \Ecdo\EcdoHulk\WechatMessageUtils();
            $wmeu->pitStation($data);
        }
    }

    // 自动回复处理
    public function autoReply($data = [])
    {
        if (! empty($data)) {
            $dt = false;

            switch ($data['msgType']) {
                case 'text':
                    $dt = true;
                    break;
                case 'event':
                    switch ($data['event']) {
                        case 'subscribe':
                            $dt = true;
                            break;
                    }

                    break;
            }

            if ($dt) {
                $data['guid'] = $this->guid;
                $waru = new WechatAutoReplyUtils();
                $res = $waru->autoReplySend($data);

                if (! empty($res['data'])) {
                    self::send($res['data']);
                }
            }
        }
    }

    // 扫二维码事件处理
    public function scan($data = [])
    {
        if (! empty($data) && ! empty($data['ticket'])) {
            $data['guid'] = $this->guid;
            $wcu = new WechatCodeUtils();
            $res = $wcu->codeSend($data);

            if (! empty($res['data'])) {
                self::send($res['data']);
            }
        }
    }

    // 上报地理位置操作
    public function location($data = [])
    {
        if (! empty($data)) {
            $dt = false;

            switch ($data['msgType']) {
                case 'location':
                    $data['latitude'] = $data['location_x'];
                    $data['longitude'] = $data['location_y'];
                    $dt = true;
                    break;
            }

            if ($dt) {
                $data['guid'] = $this->guid;
                $esu = new EntityShopUtils();
                $res = $esu->nearbyEntityShopGraphics($data);

                if (! empty($res['data'])) {
                    self::send($res['data']);
                }
            }
        }
    }

    // 监控微信事件通知
    public function event($data = [])
    {
        if (! empty($data)) {
            if (! empty($data['CardId'])) {
                $cu = new \Ecdo\EcdoIronMan\CouponsUtils();
                $cu->wechatCardMonitor($data);
            } elseif (! empty($data['UniqId'])) {
                $esu = new \Ecdo\EcdoBatMan\EntityShopUtils();
                $esu->wechatStoreMonitor($data);
            }
        }
    }

    // 菜单事件处理
    public function menu($data = [])
    {
        if (! empty($data)) {
            $wmu = new \Ecdo\EcdoHulk\WechatMenuUtils();
            $wmu->pitStation($data);
        }
    }

    // 微信消息推送处理
    public function send($data = [])
    {
        if (! empty($data['type'])) {
            if (! empty($data['passive'])) {
                $passive = $data['passive'];
            } else {
                $passive = '';
            }
            
            $data['guid'] = $this->guid;
            $res = $this->wcb->postMsg($data, $passive);
            if (! empty($data['passive'])) {
                echo $res;
            }
        } else {
            $res = json_encode(array(
                'errcode' => '-2'
            ));
        }
        
        if (empty($data['passive'])) {
            return $this->wcb->checkIsSuc($res);
        }
    }

    // 微信media操作
    public function media($data = [])
    {
        $res = ['errcode' => 'success', 'msg' => '', 'data' => ''];

        if (empty($data)) {
            $res['errcode'] = 'error';
            $res['errmsg'] = '获取media_id失败!';
        } else {
            $wmu = new WechatMediaUtils();

            switch ($data['action']) {
                case 'add':
                    if ($data['type'] == 'graphics') {
                        $data['guid'] = $this->guid;
                        $res = $wmu->addNewMedia($res, $data);
                    } else {
                        $res = $wmu->addMedia($res, $data);
                    }

                    break;
                case 'del':
                    $res = $wmu->delMaterial($res, $data);
                    break;
                default:
                    $res['errcode'] = 'error';
                    $res['errmsg'] = 'media动作类型不存在!';
                    break;
            }
        }

        return $res;
    }

    // 网页授权获取用户信息请求code
    public function oauth2Authorize($redirect_uri, $state = '')
    {
        $appid = '';
        
        if ($dt = \App\Models\TowerWechat::where('guid', $this->guid)->where('disabled', 'false')->pluck('appid')) {
            $appid = $dt;
        }
        
        $data = ['redirect_uri' => $redirect_uri, 'state' => $state, 'appid' => $appid];
        
        return $this->wcb->oauth2Authorize($data);
    }
}
