<?php
namespace Ecdo\EcdoHulk;

use Ecdo\EcdoHulk\WechatCommon;
use Ecdo\EcdoHulk\WechatMenu;
use Ecdo\EcdoHulk\WechatMenuUtils;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoSuperMan\StoreImageUtils;
use Ecdo\EcdoSuperMan\StoreVideoUtils;
use Ecdo\EcdoSuperMan\StoreVoiceUtils;
use App\Wormhole\WechatAction;


/**
 * 商家微信菜单
 *
 * @category yunke
 * @package atlas\hell\hulk\src\controllers\group
 * @author Ecdo<dev@no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatMenus extends WechatCommon
{


    public function __construct()
    {
        parent::__construct();
        //click 事件类型
        $this->click = ['text','image','video','voice','graphics'];
        //view 事件类型
        $this->view = ['material','link','ecshop','member'];
        //微商城页面类型
        $this->shopType = ['lp' => 0, 'member' => 1];//0首页//1会员中心

        $this->guid = TowerUtils::getTowerGuid();
    }


    // 菜单列表
    public function index()
    {
        $tmpmenu = WechatMenu::all();

        $id = 0;
        $menus = array();
        $tmp = array();
        if(count($tmpmenu) > 0) {

            $menus = json_decode($tmpmenu[0]->menu);
            $id = $tmpmenu[0]->id;

            foreach($menus as $k => $v){

                $tmp['name'] = $v->name;
                $tmp['actionContent'] = !empty($v->actionContent) ? $v->actionContent : '';
                $tmp['actionType'] = $v->actionType;
                $tmp['messageId'] = $v->messageId;
                $tmp['key'] = $v->key;
                $menus[$k]->subMenuItems = !empty($menus[$k]->subMenuItems) ? $menus[$k]->subMenuItems : [];
                $menus[$k]->actionContent = !empty($v->actionContent) ? $v->actionContent : '';
                $menus[$k]->pinfo = $tmp;
            }
        }

        return View::make('EcdoHulk::menu/index')->with(compact('menus','id'));
    }

    public function gen_key($num = 8){
        $re = '';
        $s = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        while(strlen($re)<$num) {
            $re .= $s[rand(0, strlen($s)-1)]; //从$s中随机产生一个字符
        }
        return $re;
    }

    //编辑，保存，删除 菜单动作
    public function toEdit()
    {
        if(!is_array($_POST['mjson']) && !empty($_POST['mjson'])){
            echo json_encode(array());exit;
        }
        $wa = new WechatAction();
        $mjson = $_POST['mjson'];

        $sync = array();
        $tmp = array();
        $subtmp = array();
        foreach($mjson as $k => &$v){

            $mjson[$k] = json_decode($v);

            //如果是同步菜单
            if($_POST['sync'] == 'true'){
                $tmpsync = get_object_vars($mjson[$k]);

                if(array_key_exists('pinfo', $tmpsync)){//是主菜单
                    if($tmpsync['messageId']){//是主菜单 且下面没有子菜单
                        $tmp['name'] = $tmpsync['name'];
                        if(in_array($tmpsync['actionType'], $this->click)){
                            $tmp['type'] = 'click';
                            $tmp['key'] = $tmpsync['key'];
                        }elseif(in_array($tmpsync['actionType'], $this->view)){
                            $tmp['type'] = 'view';
                            if($tmpsync['actionType'] == 'ecshop'){
                                $oauthUrl = action('\App\Lib\WechatOpenx@oauth2OpenId');
                                $type = 'http://' . $this->guid . '.' . \Config::get('connectb2c')['api_url'] . $tmpsync['actionContent'];
                                $tmp['url'] = $wa->oauth2Authorize($oauthUrl,$type);
                            }elseif($tmpsync['actionType'] == 'member'){
                                $oauthUrl = action('\Ecdo\EcdoMember\MemberSite@center', [$this->guid]);
                                $tmp['url'] = $wa->oauth2Authorize($oauthUrl);
                            }else{
                                $tmp['url'] = $tmpsync['actionContent'];
                            }
                        }
                    }else{
                        $tmp['name'] = $tmpsync['name'];
                    }
                }

                if(array_key_exists('subMenuItems', $tmpsync) && !empty($tmpsync['subMenuItems'])){//有子菜单
                    foreach($tmpsync['subMenuItems'] as $ik => &$items){

                        $tmpsync['subMenuItems'][$ik] = get_object_vars($tmpsync['subMenuItems'][$ik]);

                        $subtmp['name'] = $tmpsync['subMenuItems'][$ik]['name'];

                        if(in_array($tmpsync['subMenuItems'][$ik]['actionType'], $this->click)){
                            $subtmp['type'] = 'click';
                            $subtmp['key'] = $tmpsync['subMenuItems'][$ik]['key'];
                        }elseif(in_array($tmpsync['subMenuItems'][$ik]['actionType'], $this->view)){
                            $subtmp['type'] = 'view';
                            if($tmpsync['subMenuItems'][$ik]['actionType'] == 'ecshop'){

                                $oauthUrl = action('\App\Lib\WechatOpenx@oauth2OpenId');
                                $type = 'http://' . $this->guid . '.' . \Config::get('connectb2c')['api_url'] . $tmpsync['subMenuItems'][$ik]['actionContent'];
                                $subtmp['url'] = $wa->oauth2Authorize($oauthUrl,$type);
                            }elseif($tmpsync['subMenuItems'][$ik]['actionType'] == 'member'){
                                $oauthUrl = action('\Ecdo\EcdoMember\MemberSite@center', [$this->guid]);
                                $subtmp['url'] = $wa->oauth2Authorize($oauthUrl);
                            }else{
                                $subtmp['url'] = $tmpsync['subMenuItems'][$ik]['actionContent'];
                            }
                        }elseif(!$tmpsync['subMenuItems'][$ik]['actionType']){
                            echo  json_encode(array('error'=>'请将菜单配置完成后同步微信'));exit;
                        }
                        $tmp['sub_button'][] = $subtmp;
                        unset($subtmp);
                    }
                }else{
                    if(!array_key_exists('type', $tmp)){
                        echo  json_encode(array('error'=>'请将菜单配置完成后同步微信'));exit;
                    }
                }
                unset($tmpsync);
                $sync[] = $tmp;
                unset($tmp);
            }else{

                //如果是保存菜单 存在子菜单
                if(isset($mjson[$k]->subMenuItems) && is_array($mjson[$k]->subMenuItems)){
                    foreach($mjson[$k]->subMenuItems as $key => &$value){
                        if(!$value){
                            unset($mjson[$k]->subMenuItems[$key]);
                            continue;
                        }
                        if(empty($value->key)){
                            $value->key = $this->gen_key();
                        }
                    }
                }
                //第一次保存菜单 生成唯一 key
                if(empty($mjson[$k]->key)){
                    $mjson[$k]->key = $this->gen_key();
                }
            }

        }

        if($_POST['sync'] == 'true'){//发布
            $arr = [
                        'type' => 'createMenu',
                        'action' => [
                                'button' => $sync
                        ],
                        'parameter' => [ // 发送地址 (被动推送调用时可不填)
                            'key' => 'createMenu', // 接口地址
                            'value' => [
                                'access_token' => '' // 接口参数
                            ]
                        ],
                    ];

            $result = $wa->send($arr);
            if($result['errcode'] == 'success'){
                echo json_encode(array('success'=>'true'));exit;
            }else{
                echo json_encode(array('error'=>$result['errmsg']));exit;
            }
        }else{
            //保存前取出id
            $tmpmenu = WechatMenu::all();
            if(count($tmpmenu) > 0){
                $wm = WechatMenu::find($tmpmenu[0]->id);
            }else{
                $wm = new WechatMenu();
            }

            $wm->menu = json_encode($mjson,JSON_UNESCAPED_UNICODE);

            if($wm->save()){
                echo json_encode(array('success'=>'true'));
            }else{
                echo json_encode(array());
            }
        }
    }

    public function getInfo(){
        $type = Input::get('type');
        $id   = Input::get('id');
        $return = array();
        switch($type) {
            case 'image':
                $imageUtiles = new StoreImageUtils();
                $data = $imageUtiles->getOneImage($id);
                $return['id'] = $data->id;
                $return['url'] = $data->url;
                $return['name'] = $data->name;
                break;
            case 'voice':
                $voiceUtiles = new StoreVoiceUtils();
                $return = $voiceUtiles->getOneVoice($id);
                break;
            case 'video':
                $videoUtiles = new StoreVideoUtils();
                $return = $videoUtiles->getOneVideo($id);
                break;
            case 'graphics':
                $graphicsUtiles = new WechatGraphicsUtils();
                $data = $graphicsUtiles->getOneGraphics($id);

                $return['id'] = $data['id'];
                $return['title'] = $data['title'];
                $return['img_url'] = $data['img_url'];
                if($data['item']){
                    foreach($data['item'] as $k => $v){
                        $items['id'] = $v->id;
                        $items['title'] = $v->title;
                        $items['img_url'] = $v->img_url;
                        $return['items'][] = $items;
                    }
                }

                break;
            case 'material':
//                $imageUtiles = new StoreMaterialUtils();
//                $data = $imageUtiles->getOneImage();
                break;
        }

        echo json_encode($return);
    }


}
