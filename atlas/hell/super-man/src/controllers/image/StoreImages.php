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
 * 店铺图片
 *
 * @category yunke
 * @package atlas\hell\super-man\src\controllers\image
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class StoreImages extends AngelCommon
{
    public function __construct()
    {
        parent::__construct();

        $this->siu = new StoreImageUtils();
        $this->sideMenu(array('m_shop','m_shop_file','m_shop_auth'));
    }

    // 图片列表
    public function index()
    {
    	$image = $this->siu->getImagePage();
        
        return View::make('EcdoSuperMan::image/index')->with(compact('image'));
    }

    // 搜索图片
    public function seImage()
    {
        $search = Input::get('search');
        $image = $this->siu->getSearchImagePage($search);

        return View::make('EcdoSuperMan::image/index')->with(compact('image', 'search'));
    }

    // 创建图片
    public function crImage()
    {
        return View::make('EcdoSuperMan::image/create');
    }

    // 创建图片处理
    public function crImageDis()
    {
        // 文件导入验证
        if (! Input::hasFile('file')) {
            return Redirect::to('angel/store/image')->with('error', '请先上传图片!');
        } else {
            $res = $this->fileValidator(Input::file('file'), 'image');

            if ($res['errcode'] == 'error') {
                return Redirect::to('angel/store/image')->with('error', $res['msg']);
            }

            if ($this->siu->createImage($res['file'])) {
                return Redirect::to('angel/store/image')->with('success', '创建图片成功!');
            } else {
                return Redirect::to('angel/store/image')->with('error', '创建图片失败!');
            }
        }
    }

    // 编辑图片
    public function upImage()
    {
        $image = $this->siu->getOneImage(Input::get('id'));

        return View::make('EcdoSuperMan::image/update')->with(compact('image'));
    }

    // 编辑图片处理
    public function upImageDis()
    {
        // validator-ajax表单验证
        if (Input::get('o_name') != Input::get('name')) {
            // 表单验证规则
            $rules = 'Required|min:1|max:30|unique:'.TowerUtils::fetchTowerGuid().'_store_image,name';
            $this->response_rules['name'] = $rules;
            $this->ajaxValidator(Input::all());
        }

        // validator-ajax验证通过
        if ($this->response_type == 'success') {
            if (! $this->siu->updateImage(Input::all())) {
                $this->response_type = 'error';
                $this->response_msg = '编辑图片失败!';
            }
        }
        
        // ajax返回请求
        $this->end('\Ecdo\EcdoSuperMan\StoreImages@index');
    }

    // 删除图片
    public function deImage()
    {
        if ($this->siu->deleteImage(Input::get('id'))) {
            return Redirect::to('angel/store/image')->with('success', '删除图片成功!');
        } else {
            return Redirect::to('angel/store/image')->with('error', '删除图片失败!');
        }
    }

    // 批量删除图片
    public function drImage()
    {
        if ($this->siu->dropImage(Input::get('id'))) {
            return Redirect::to('angel/store/image')->with('success', '批量删除图片成功!');
        } else {
            return Redirect::to('angel/store/image')->with('error', '批量删除图片失败!');
        }
    }
}
