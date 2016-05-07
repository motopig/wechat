<?php
namespace Ecdo\EcdoHulk;

use Session;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoHulk\WechatCommon;
use Ecdo\EcdoHulk\WechatMessageUtils;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


/**
 * 微信用户消息
 *
 * @category yunke
 * @package atlas\hell\hulk\src\controllers\message
 * @author Dev<dev@no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

class WechatMessages  extends WechatCommon{

    public function __construct()
    {
        parent::__construct();

        $this->wmu = new WechatMessageUtils();
        $this->page = \Config::get('EcdoSpiderMan::setting')['page'];
    }

    // 用户消息列表
    public function index()
    {
        $messages = $this->wmu->getMessagePage();
        $subInfo = [];

        $list = Session::get('newMessagex'.TowerUtils::fetchTowerGuid());
        $subInfo = json_decode($list, true);

        $subInfo['curTime'] = time();
        //查询未接待消息
//        if(!$ignoreNum = Session::get('ignoreNum'.TowerUtils::fetchTowerGuid())){
            $imessages = $this->wmu->getCatMessage('ignore');
            $ignoreNum = count($imessages);
            Session::put('ignoreNum'.TowerUtils::fetchTowerGuid(), $ignoreNum);
//        }

        Session::forget('newMessagex'.TowerUtils::fetchTowerGuid());

        return View::make('EcdoHulk::message/index')->with(compact('messages', 'subInfo', 'ignoreNum'));
    }

    //按照消息分类查看消息
    public function cat(){

        if (Input::get('filter')) {
            // 删除空元素
            $tmp = Input::get('filter');
            $cat = $tmp['cat'];
        }else{
            $cat = Input::get('cat');//0=>所有消息 1=>未接待 2=>备注 3=>风险客户 4=>自动触发回复
        }

        $messages = $this->wmu->getCatMessage($cat);
        $filter = [ 'cat' => $cat ];
        if($cat != 'ignore'){
            if(!$ignoreNum = Session::get('ignoreNum'.TowerUtils::fetchTowerGuid())){
                $imessages = $this->wmu->getCatMessage('ignore');
                $ignoreNum = count($imessages);
            }
        }else{
            $ignoreNum = count($messages);
        }

        Session::put('ignoreNum'.TowerUtils::fetchTowerGuid() , $ignoreNum);

        return View::make('EcdoHulk::message/index')->with(compact('messages', 'filter', 'cat', 'ignoreNum'));
    }

    //获取用户的消息列表
    public function getMemberMessage(){
        $memberMessage = $this->wmu->getMemberMessage(Input::get('member_id'), 0 ,Input::get('cat'));
        echo json_encode(array('data' => $memberMessage));
    }

    //发送消息给用户
    public function replay(){
        $data = Input::get();
        if($this->wmu->saveMessage($data)){
            echo json_encode(array('status'=>'sucess'));exit;
        }else{
            echo json_encode(array('status'=>'faile'));exit;
        }
    }

    //查看是否有新消息
    public function checkNewMessage(){
        $time = Input::get('curTime');
        $list = $this->wmu->checkNewMessage($time);
        if(!empty($list) && $list){
            Session::put('newMessagex'.TowerUtils::fetchTowerGuid() , json_encode($list));
            Session::save();
            $list['nums'] = 1;
        }else{
            $list['nums'] = 0;
        }


        echo json_encode($list);exit;
    }

    //更多信息查看
    public function more(){
        $page = Input::get('page');
        $member_id = Input::get('member_id');
        $cat = Input::get('cat');
        $messages['data'] = $this->wmu->getMemberMessage($member_id, $page, $cat);

        echo json_encode($messages);exit;
    }

    //搜索用户昵称
    public function seMessage(){
        $search = Input::get('search');
        $messages = $this->wmu->getSearchMessagePage($search);

        return View::make('EcdoHulk::message/index')->with(compact('messages', 'search'));
    }

}