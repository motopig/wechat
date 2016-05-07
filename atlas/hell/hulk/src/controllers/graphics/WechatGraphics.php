<?php
namespace Ecdo\EcdoHulk;

use Ecdo\EcdoHulk\WechatCommon;
use Ecdo\EcdoHulk\WechatGraphicsUtils;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

/**
 * 商家微信普通图文
 *
 * @category yunke
 * @package atlas\hell\hulk\src\controllers\graphics
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatGraphics extends WechatCommon
{
    public function __construct()
    {
        parent::__construct();

        $this->wgu = new WechatGraphicsUtils();
    }
    
    // 普通图文列表
    public function index()
    { 
        $graphics = $this->wgu->getGraphicsPage();
        
        return View::make('EcdoHulk::graphics/index')->with(compact('graphics'));
    }

    // 创建普通单图文
    public function crSingel()
    {
        return View::make('EcdoHulk::graphics/create_singel');
    }

    // 创建普通多图文
    public function crMany()
    {
        return View::make('EcdoHulk::graphics/create_many');
    }

    // 查看普通图文
    public function shGraphics()
    {
        $graphics = $this->wgu->getOneGraphics(Input::get('graphics_id'));

        return View::make('EcdoHulk::graphics/show')->with(compact('graphics'));
    }

    // 编辑普通图文
    public function upGraphics()
    {
        $graphics = $this->wgu->getOneGraphics(Input::get('graphics_id'));

        if (count($graphics['item']) > 0) {
            return View::make('EcdoHulk::graphics/update_many')->with(compact('graphics'));
        } else {
            return View::make('EcdoHulk::graphics/update_singel')->with(compact('graphics'));
        }
    }

    // 搜索普通图文
    public function seGraphics()
    {
        $search = Input::get('search');
        $graphics = $this->wgu->getSearchGraphicsPage($search);

        return View::make('EcdoHulk::graphics/index')->with(compact('graphics', 'search'));
    }

    // 筛选普通图文
    public function fiGraphics()
    {
        return View::make('EcdoHulk::graphics/filter');
    }

    // 筛选普通图文处理
    public function fiGraphicsDis()
    {
        // 判断是否已经筛选进分页
        if (Input::get('filter')) {
            // 删除空元素
            $data = Input::get('filter');
            $data = array_filter($data);
        } else {
            // 删除csrf_token csrf_guid 空元素
            $data = Input::All();
            unset($data['csrf_token']);
            unset($data['csrf_guid']);
            $data = array_filter($data);
        }

        $filter = $data;
        $graphics = $this->wgu->getFilterGraphicsPage($filter);

        return View::make('EcdoHulk::graphics/index')->with(compact('graphics', 'filter'));
    }

    // 创建或编辑普通单图文
    public function crupGraphicDis()
    {
        // 表单验证规则
        $rules = array(
            'title' => 'Required|max:64'
        );

        $rules['image_url'] = 'Required';

        if (Input::get('author')) {
             $rules['author'] = 'Required|max:8';
        }

        if (Input::get('digest')) {
            $rules['digest'] = 'Required|max:120';
        }

        if (Input::get('content_source_url')) {
            $rules['content_source_url'] = 'Required|url';
        }
        
        // 验证表单信息
        $validator = Validator::make(Input::all(), $rules);
        
        // 验证不通过
        if (! $validator->passes()) {
            return Redirect::to('angel/wechat/graphics')->with('error', '缺少或不符合表单必填参数!');
        }

        $rs = $this->wgu->crupGraphic(Input::all());

        return Redirect::to('angel/wechat/graphics')->with($rs['err'], $rs['msg']);
    }

    // 创建或编辑普通多图文
    public function crupGraphicsDis()
    {
        $data = Input::all();
        unset($data['csrf_token']);
        unset($data['csrf_guid']);
        foreach ($data as $k => $v) {
            if ($k != 'f_id') {
                $data[$k] = explode(',', $v);
            }
        }

        if (count($data['title']) == 0 || count($data['content']) == 0 || count($data['image_url']) == 0) {
            return Redirect::to('angel/wechat/graphics')->with('error', '标题、封面图片、文本内容不能为空!');
        } elseif (! empty($data['f_id'])) {
            foreach ($data['u_id'] as $k => $v) {
                if ($v == '') {
                    $data['u_id'][$k] = 0;
                }
            }
        }

        $res = $this->wgu->crupGraphics($data);
        if ($res['errcode'] == 'success') {
            $res['url'] = action('\Ecdo\EcdoHulk\WechatGraphics@index');
        }

        exit(json_encode($res, JSON_UNESCAPED_UNICODE));
    }

    // 创建或编辑普通图文上传素材
    public function uploadFile($file)
    {
        $res = ['errcode' => 'success'];
        if ($file) {
            $res = $this->fileValidator($file, 'image');
        } else {
            $res['file'] = '';
        }

        return $res;
    }

    // 删除普通图文
    public function deGraphics()
    {
        if ($this->wgu->deleteGraphics(Input::get('graphics_id'))) {
            return Redirect::to('angel/wechat/graphics')->with('success', '图文已删除');
        } else {
            return Redirect::to('angel/wechat/graphics')->with('error', '图文删除失败');
        }
    }

    // 获取普通图文图片地址
    public function graphicsImageUrl()
    {
        $siu = new \Ecdo\EcdoSuperMan\StoreImageUtils();
        $image = $siu->getOneImage(Input::get('id'));

        $arr = ['store_image_id' => $image->id, 'image' => asset($image->url)];
        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }
}
