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
 * 店铺视频
 *
 * @category yunke
 * @package atlas\hell\super-man\src\controllers\video
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class StoreVideos extends AngelCommon
{
    public function __construct()
    {
        parent::__construct();

        $this->svu = new StoreVideoUtils();
        
        \View::share('menu_route_path','angel/store/image');
        $this->sideMenu(array('m_shop','m_shop_file','m_shop_auth'));
    }

    // 视频列表
    public function index()
    {
    	$video = $this->svu->getVideoPage();
        
        return View::make('EcdoSuperMan::video/index')->with(compact('video'));
    }

    // 搜索视频
    public function seVideo()
    {
        $search = Input::get('search');
        $video = $this->svu->getSearchVideoPage($search);

        return View::make('EcdoSuperMan::video/index')->with(compact('video', 'search'));
    }

    // 创建视频
    public function crVideo()
    {
        return View::make('EcdoSuperMan::video/create');
    }

    // 创建视频处理
    public function crVideoDis()
    {
        // 文件导入验证
        if (! Input::hasFile('file')) {
            return Redirect::to('angel/store/video')->with('error', '请先上传视频!');
        } else {
            $res = $this->fileValidator(Input::file('file'), 'video');

            if ($res['errcode'] == 'error') {
                return Redirect::to('angel/store/video')->with('error', $res['msg']);
            }

            if ($this->svu->createVideo($res['file'])) {
                return Redirect::to('angel/store/video')->with('success', '创建视频成功!');
            } else {
                return Redirect::to('angel/store/video')->with('error', '创建视频失败!');
            }
        }
    }

    // 编辑视频
    public function upVideo()
    {
        $video = $this->svu->getOneVoice(Input::get('id'));

        return View::make('EcdoSuperMan::video/update')->with(compact('video'));
    }

    // 编辑视频处理
    public function upVideoDis()
    {
        // validator-ajax表单验证
        if (Input::get('o_name') != Input::get('name')) {
            // 表单验证规则
            $rules = 'Required|min:1|max:30|unique:'.TowerUtils::fetchTowerGuid().'_store_video,name';
            $this->response_rules['name'] = $rules;
            $this->ajaxValidator(Input::all());
        }

        // validator-ajax验证通过
        if ($this->response_type == 'success') {
            if (! $this->svu->updateVideo(Input::all())) {
                $this->response_type = 'error';
                $this->response_msg = '编辑视频失败!';
            }
        }
        
        // ajax返回请求
        $this->end('\Ecdo\EcdoSuperMan\StoreVideos@index');
    }

    // 删除视频
    public function deVideo()
    {
        if ($this->svu->deleteVideo(Input::get('id'))) {
            return Redirect::to('angel/store/video')->with('success', '删除视频成功!');
        } else {
            return Redirect::to('angel/store/video')->with('error', '删除视频失败!');
        }
    }

    // 批量删除视频
    public function drVideo()
    {
        if ($this->svu->dropVideo(Input::get('id'))) {
            return Redirect::to('angel/store/video')->with('success', '批量删除视频成功!');
        } else {
            return Redirect::to('angel/store/video')->with('error', '批量删除视频失败!');
        }
    }
}
