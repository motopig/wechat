<?php
namespace Ecdo\EcdoHulk;

use Ecdo\EcdoHulk\WechatCommon;
use Ecdo\EcdoHulk\WechatCodeUtils;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

/**
 * 微信自动回复
 *
 * @category yunke
 * @package atlas\hell\hulk\src\controllers\code
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatCodes extends WechatCommon
{
    public function __construct()
    {
        parent::__construct();

        $this->wcu = new WechatCodeUtils();
    }
    
    // 二维码列表
    public function index()
    {
        $use = $this->wcu->getUse();
        $code = $this->wcu->getCodePage();
        
        return View::make('EcdoHulk::code/index')->with(compact('code', 'use'));
    }

    // 搜索二维码
    public function seCode()
    {
        $search = Input::get('search');

        $use = $this->wcu->getUse();
        $code = $this->wcu->getCodeSearchPage($search);

        return View::make('EcdoHulk::code/index')->with(compact('code', 'use', 'search'));
    }

    // 二维码用途模版数据
    public function uses()
    {
        $arr = $this->wcu->getUseTpl(Input::all());

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 创建二维码
    public function crCode()
    {
        $use = $this->wcu->getUse();

        return View::make('EcdoHulk::code/create')->with(compact('use'));
    }

    // 创建二维码处理
    public function crCodeDis()
    {
        $arr = $this->wcu->codeCreate(Input::all());

        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoHulk\WechatCodes@index');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 编辑二维码
    public function upCode()
    {
        $use = $this->wcu->getUse();
        $code = $this->wcu->getOneCode(Input::get('id'));

        return View::make('EcdoHulk::code/update')->with(compact('use', 'code'));
    }

    // 编辑二维码处理
    public function upCodeDis()
    {
        $arr = $this->wcu->codeUpdate(Input::all());

        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoHulk\WechatCodes@index');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 删除二维码
    public function deCode()
    {
        if ($this->wcu->deleteCode(Input::get('id'))) {
            return Redirect::to('angel/wechat/code')->with('success', '删除二维码成功!');
        } else {
            return Redirect::to('angel/wechat/code')->with('error', '删除二维码失败!');
        }
    }
}
