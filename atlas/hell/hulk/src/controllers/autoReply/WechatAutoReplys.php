<?php
namespace Ecdo\EcdoHulk;

use Ecdo\EcdoHulk\WechatCommon;
use Ecdo\EcdoHulk\WechatAutoReplyUtils;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

/**
 * 微信自动回复
 *
 * @category yunke
 * @package atlas\hell\hulk\src\controllers\autoReply
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatAutoReplys extends WechatCommon
{
    public function __construct()
    {
        parent::__construct();

        $this->wau = new WechatAutoReplyUtils();
    }
    
    // 自动回复列表
    public function index()
    {
        $autoreply = $this->wau->getAutoReplyPage();

        return View::make('EcdoHulk::autoReply/index')->with(compact('autoreply'));
    }

    // 搜索自动回复
    public function seAutoReply()
    {
        $search = Input::get('search');
        $autoreply = $this->wau->getAutoReplySearchPage($search);

        return View::make('EcdoHulk::autoReply/index')->with(compact('autoreply', 'search'));
    }

    // 创建自动回复
    public function crAutoReply()
    {
        return View::make('EcdoHulk::autoReply/create');
    }

    // 创建自动回复处理
    public function crAutoReplyDis()
    {
        // 去除重复的关键字
        $keyword = array_unique(explode(',', Input::get('keyword')));

        // 判断关键字是否已经存在表中
        $arr = $this->wau->autoReplykeywordValidate($keyword);
        if ($arr['err'] == 'error') {
            exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
        }

        $arr = $this->wau->autoReplyCreate(Input::all(), $keyword);
        if ($arr['err'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoHulk\WechatAutoReplys@index');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 编辑自动回复
    public function upAutoReply()
    {
        $autoreply = $this->wau->getAutoReplyOnePage(Input::get('id'));

        return View::make('EcdoHulk::autoReply/update')->with(compact('autoreply'));
    }

    // 编辑自动回复处理
    public function upAutoReplyDis()
    {
        // 去除重复的关键字
        $keyword = array_unique(explode(',', Input::get('keyword')));

        // 判断关键字是否已经存在表中
        $res = $this->wau->autoReplykeywordValidateUpdate(Input::get('id'), $keyword);
        if ($res['err'] == 'error') {
            exit(json_encode($res, JSON_UNESCAPED_UNICODE));
        }

        $arr = $this->wau->autoReplyUpdate(Input::all(), $res['keyword']);
        if ($arr['err'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoHulk\WechatAutoReplys@index');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 删除自动回复
    public function deAutoReply()
    {
        if ($this->wau->deleteAutoReply(Input::get('id'))) {
            return Redirect::to('angel/wechat/autoReply')->with('success', '删除自动回复成功!');
        } else {
            return Redirect::to('angel/wechat/autoReply')->with('error', '删除自动回复失败!');
        }
    }

    // 关注自动回复设置
    public function concernAutoReply()
    {
        $arr = $this->wau->concernAutoReplyDis(Input::all());
        
        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 关键字匹配设置
    public function matchingAutoReply()
    {
        $arr = $this->wau->matchingAutoReplyDis(Input::all());
        
        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }
}
