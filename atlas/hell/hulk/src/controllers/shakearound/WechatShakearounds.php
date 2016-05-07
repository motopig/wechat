<?php
namespace Ecdo\EcdoHulk;

use Ecdo\EcdoHulk\WechatCommon;
use Ecdo\EcdoHulk\WechatShakearoundUtils;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

/**
 * 摇一摇
 *
 * @category yunke
 * @package atlas\hell\hulk\src\controllers\code
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatShakearounds extends WechatCommon
{
    public function __construct()
    {
        parent::__construct();

        $this->wsu = new WechatShakearoundUtils();
    }
    
    // 设备列表
    public function device()
    {
        $device = $this->wsu->shakearoundDevicePage();

        return View::make('EcdoHulk::shakearound/device/index')->with(compact('device'));
    }

    // 查看设备
    public function shDevice()
    {
        $device = $this->wsu->getOneDevice(Input::get('id'));

        return View::make('EcdoHulk::shakearound/device/show')->with(compact('device'));
    }

    // 搜索设备
    public function seDevice()
    {
        $search = Input::get('search');
        $device = $this->wsu->shakearoundDeviceSearchPage($search);

        return View::make('EcdoHulk::shakearound/device/index')->with(compact('device', 'search'));
    }

    // 刷新设备
    public function deviceReload()
    {
        $arr = $this->wsu->deviceReload();

        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoHulk\WechatShakearounds@device');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 创建设备
    public function deviceCreate()
    {
        $esu = new \Ecdo\EcdoBatMan\EntityShopUtils();
        $entityshop = $esu->getEntityShopFoundation();
        
        return View::make('EcdoHulk::shakearound/device/create')->with(compact('entityshop'));
    }

    // 创建设备处理
    public function deviceCreateDis()
    {
        $arr = $this->wsu->deviceCreate(Input::all());
        if ($arr['errmsg'] == '远端服务不可用') {
            $arr['errcode'] = 'log';
            $arr['errmsg'] = '微信远端服务不可用；设备已申请至微信服务器，稍候可刷新获取设备信息！';
        }

        if ($arr['errcode'] != 'error') {
            $arr['url'] = action('\Ecdo\EcdoHulk\WechatShakearounds@device');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 页面列表
    public function page()
    {
        $page = $this->wsu->shakearoundPage();

        return View::make('EcdoHulk::shakearound/page/index')->with(compact('page'));
    }

    // 搜索页面
    public function sePage()
    {
        $search = Input::get('search');
        $page = $this->wsu->shakearoundSearchPage($search);

        return View::make('EcdoHulk::shakearound/page/index')->with(compact('page', 'search'));
    }

    // 创建页面
    public function pageCreate()
    {
        $type = $this->wsu->getPageType()['key'];
        $content = $this->wsu->getPageType()['value'];
        
        return View::make('EcdoHulk::shakearound/page/create')->with(compact('type', 'content'));
    }

    // 创建页面处理
    public function pageCreateDis()
    {
        $arr = $this->wsu->pageCreate(Input::all());

        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoHulk\WechatShakearounds@page');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 编辑页面
    public function pageUpdate()
    {
        $type = $this->wsu->getPageType()['key'];
        $content = $this->wsu->getPageType()['value'];
        $page = $this->wsu->getOnePage(Input::get('id'));
        
        return View::make('EcdoHulk::shakearound/page/update')->with(compact('page', 'type', 'content'));
    }

    // 编辑页面处理
    public function pageUpdateDis()
    {
        $arr = $this->wsu->pageUpdate(Input::all());

        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoHulk\WechatShakearounds@page');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 删除页面
    public function pageDelete()
    {
        $res = $this->wsu->pageDelete(Input::get('id'));

        return Redirect::to('angel/wechat/shakearound/page')->with($res['errcode'], $res['errmsg']);
    }

    // 设备修改绑定页面
    public function deviceUpdate()
    {
        $device = $this->wsu->getOneDevice(Input::get('id'));
        $device_bind_page = $this->wsu->getDeviceBindPage($device['device_id']);
        $page = $this->wsu->getPageAll();
        $json_page = json_encode($page);

        $esu = new \Ecdo\EcdoBatMan\EntityShopUtils();
        $entityshop = $esu->getEntityShopFoundation();

        return View::make('EcdoHulk::shakearound/device/update')
        ->with(compact('device', 'device_bind_page', 'page', 'json_page', 'entityshop'));
    }

    // 设备修改绑定页面处理
    public function deviceUpdateDis()
    {
        $res = $this->wsu->deviceBindPage(Input::all());

        return Redirect::to('angel/wechat/shakearound/device')->with($res['errcode'], $res['errmsg']);
    }
}
