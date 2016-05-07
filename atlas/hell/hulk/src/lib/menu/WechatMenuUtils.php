<?php


namespace Ecdo\EcdoHulk;

use Ecdo\EcdoSuperMan\StoreImage;
use Ecdo\EcdoSuperMan\StoreVoice;
use Ecdo\EcdoSuperMan\StoreVideo;
use Ecdo\EcdoHulk\WechatMemberUtils;
use Ecdo\EcdoHulk\WechatMenu;
use Illuminate\Support\Facades\DB;
use Ecdo\Universe\TowerUtils;
use App\Wormhole\WechatAction;

/**
 * 微信微信菜单公共类
 *
 * @category yunke
 * @package atlas\hell\hulk\src\lib\group
 * @author Dev<dev@no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatMenuUtils
{
    public function __construct()
    {
        $this->guid = TowerUtils::fetchTowerGuid();
    }

    public function pitStation($data){

        $wa = new \App\Wormhole\WechatAction();
        //调用微信接口发送信息给用户

        if(!array_key_exists('eventKey', $data)){
            return;
        }
        $menuInfo = $this->getMenuByKey($data['eventKey']);
        if(count($menuInfo) == 0){
            return;
        }

        switch ($menuInfo->actionType){

            case 'text' :
                //调用微信接口发送信息给用户
                //emoji 替换
                $data['content'] = preg_replace_callback(
                    '/<img[^>]*data="([^"]*)">/',
                    function ($matches)
                    {
                        if(is_array($matches)){
                            $b = explode('"', $matches[0]);
                            return $b[3];
                        }
                    },
                    $menuInfo->actionContent);
                $arr = [
                    'type' => 'sendMsgText',
                    'action' => [
                        'touser' => $data['fromUser'],
                        'content' => $data['content']
                    ],
                    'parameter' => [ // 发送地址 (被动推送调用时可不填)
                        'key' => 'sendMsg', // 接口地址
                        'value' => [
                            'access_token' => '' // 接口参数
                        ]
                    ],
                ];

                $result = $wa->send($arr);
                break;
            case 'link' :
                $arr = [
                    'type' => 'sendMsgText',
                    'action' => [
                        'touser' => $data['fromUser'],
                        'content' => $menuInfo->actionContent
                    ],
                    'parameter' => [ // 发送地址 (被动推送调用时可不填)
                        'key' => 'sendMsg', // 接口地址
                        'value' => [
                            'access_token' => '' // 接口参数
                        ]
                    ],
                ];

                $result = $wa->send($arr);
                break;
            case 'video' :
                $arr = $wa->media(['action' => 'add', 'type' => 'video', 'id' => trim($menuInfo->messageId)]);
                if (! empty($arr['data'])) {
                    $send = [
                        'type' => 'sendMsgVideo',
                        'action' => [
                            'touser' => $data['fromUser'],
                            'media_id' => $arr['data'],
                            'title' => '',
                            'description' => ''
                        ],
                        'parameter' => [ // 发送地址 (被动推送调用时可不填)
                            'key' => 'sendMsg', // 接口地址
                            'value' => [
                                'access_token' => '' // 接口参数
                            ]
                        ],
                    ];

                    $result = $wa->send($send);
                }

                break;
            case 'voice' :
                $arr = $wa->media(['action' => 'add', 'type' => 'voice', 'id' => trim($menuInfo->messageId)]);
                $send = [
                    'type' => 'sendMsgVoice',
                    'action' => [
                        'touser' => $data['fromUser'],
                        'media_id' => $arr['data']
                    ],
                    'parameter' => [ // 发送地址 (被动推送调用时可不填)
                        'key' => 'sendMsg', // 接口地址
                        'value' => [
                            'access_token' => '' // 接口参数
                        ]
                    ],
                ];
                $result = $wa->send($send);
                break;
            case 'graphics' :
                $wgu = new \Ecdo\EcdoHulk\WechatGraphicsUtils();
                $arr = $wgu->graphicsTpl(['id' => trim($menuInfo->messageId), 'guid' => $this->guid, 'type' => '']);
                $send = [
                    'type' => 'sendMsgNews',
                    'action' => [
                        'touser' => $data['fromUser'],
                        'articles' => $arr['articles']
                    ],
                    'parameter' => [ // 发送地址 (被动推送调用时可不填)
                        'key' => 'sendMsg', // 接口地址
                        'value' => [
                            'access_token' => '' // 接口参数
                        ]
                    ],
                ];
                $result = $wa->send($send);
                break;
            case 'image' :
                $arr = $wa->media(['action' => 'add', 'type' => 'image', 'id' => trim($menuInfo->messageId)]);
                $send = [
                    'type' => 'sendMsgImage',
                    'action' => [
                        'touser' => $data['fromUser'],
                        'media_id' => $arr['data']
                    ],
                    'parameter' => [ // 发送地址 (被动推送调用时可不填)
                        'key' => 'sendMsg', // 接口地址
                        'value' => [
                            'access_token' => '' // 接口参数
                        ]
                    ],
                ];

                $result = $wa->send($send);
                break;
        }

        return $result;
    }

    //根据key 返回具体某个菜单
    public function getMenuByKey($key){
        $tmpMenu = WechatMenu::all();

        $menus = array();

        if(count($tmpMenu) > 0) {
            $menus = json_decode($tmpMenu[0]->menu);
        }

        foreach($menus as $k => $v){

            if($v->key == $key){
                return $v;
            }

            if(isset($v->subMenuItems)){
                foreach($v->subMenuItems as $ck => $cv){
                    if($cv->key == $key){
                        return $cv;
                    }
                }
            }
        }
        return [];
    }
}
