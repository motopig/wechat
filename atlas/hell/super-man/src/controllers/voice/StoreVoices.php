<?php
namespace Ecdo\EcdoSuperMan;

use Ecdo\EcdoSpiderMan\AngelCommon;
use Ecdo\EcdoSuperMan\StoreImageUtils;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Ecdo\Universe\TowerUtils;

/**
 * 店铺语音
 *
 * @category yunke
 * @package atlas\hell\super-man\src\controllers\voice
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class StoreVoices extends AngelCommon
{
    public function __construct()
    {
        parent::__construct();

        $this->svu = new StoreVoiceUtils();
        \View::share('menu_route_path','angel/store/image');
        $this->sideMenu(array('m_shop','m_shop_file','m_shop_auth'));
    }

    // 语音列表
    public function index()
    {
    	$voice = $this->svu->getVoicePage();
        
        return View::make('EcdoSuperMan::voice/index')->with(compact('voice'));
    }

    // 搜索语音
    public function seVoice()
    {
        $search = Input::get('search');
        $voice = $this->svu->getSearchVoicePage($search);

        return View::make('EcdoSuperMan::voice/index')->with(compact('voice', 'search'));
    }

    // 创建语音
    public function crVoice()
    {
        return View::make('EcdoSuperMan::voice/create');
    }

    // 创建语音处理
    public function crVoiceDis()
    {
        // 文件导入验证
        if (! Input::hasFile('file')) {
            return Redirect::to('angel/store/voice')->with('error', '请先上传语音!');
        } else {
            $res = $this->fileValidator(Input::file('file'), 'voice');

            if ($res['errcode'] == 'error') {
                return Redirect::to('angel/store/voice')->with('error', $res['msg']);
            }

            if ($this->svu->createVoice($res['file'])) {
                return Redirect::to('angel/store/voice')->with('success', '创建语音成功!');
            } else {
                return Redirect::to('angel/store/voice')->with('error', '创建语音失败!');
            }
        }
    }

    // 编辑语音
    public function upVoice()
    {
        $voice = $this->svu->getOneVoice(Input::get('id'));

        return View::make('EcdoSuperMan::voice/update')->with(compact('voice'));
    }

    // 编辑语音处理
    public function upVoiceDis()
    {
        // validator-ajax表单验证
        if (Input::get('o_name') != Input::get('name')) {
            // 表单验证规则
            $rules = 'Required|min:1|max:30|unique:'.TowerUtils::fetchTowerGuid().'_store_voice,name';
            $this->response_rules['name'] = $rules;
            $this->ajaxValidator(Input::all());
        }

        // validator-ajax验证通过
        if ($this->response_type == 'success') {
            if (! $this->svu->updateVoice(Input::all())) {
                $this->response_type = 'error';
                $this->response_msg = '编辑语音失败!';
            }
        }
        
        // ajax返回请求
        $this->end('\Ecdo\EcdoSuperMan\StoreVoices@index');
    }

    // 删除语音
    public function deVoice()
    {
        if ($this->svu->deleteVoice(Input::get('id'))) {
            return Redirect::to('angel/store/voice')->with('success', '删除语音成功!');
        } else {
            return Redirect::to('angel/store/voice')->with('error', '删除语音失败!');
        }
    }

    // 批量删除语音
    public function drVoice()
    {
        if ($this->svu->dropVoice(Input::get('id'))) {
            return Redirect::to('angel/store/voice')->with('success', '批量删除语音成功!');
        } else {
            return Redirect::to('angel/store/voice')->with('error', '批量删除语音失败!');
        }
    }
}
