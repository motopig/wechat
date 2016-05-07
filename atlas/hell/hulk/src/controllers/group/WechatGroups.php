<?php
namespace Ecdo\EcdoHulk;

use Ecdo\EcdoHulk\WechatCommon;
use Ecdo\EcdoHulk\WechatGroupUtils;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Ecdo\Universe\TowerUtils;

/**
 * 商家微信组别
 *
 * @category yunke
 * @package atlas\hell\hulk\src\controllers\group
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatGroups extends WechatCommon
{
    public function __construct()
    {
        parent::__construct();

        $this->wgu = new WechatGroupUtils();
    }
    
    // 组别列表
    public function index()
    {
        $group = $this->wgu->getGroupPage();
    	
    	return View::make('EcdoHulk::group/index')->with(compact('group'));
    }

    // 查看组别
    public function shGroup()
    {
        $group = $this->wgu->getOneGroup(Input::get('group_id'));

        return View::make('EcdoHulk::group/show')->with(compact('group'));
    }

    // 搜索组别
    public function seGroup()
    {
        $search = Input::get('search');
        $group = $this->wgu->getSearchGroupPage($search);

        return View::make('EcdoHulk::group/index')->with(compact('group', 'search'));
    }

    // 筛选组别
    public function fiGroup()
    {
        return View::make('EcdoHulk::group/filter');
    }

    // 筛选组别处理
    public function fiGroupDis()
    {
        // 判断是否已经筛选进分页
        if (Input::get('filter')) {
            // 删除空元素
            $data = Input::get('filter');
            $data = array_filter($data);
        } else {
            // 删除csrf_token及空元素
            $data = Input::All();
            unset($data['csrf_token']);
            $data = array_filter($data);
        }

        $filter = $data;
        $group = $this->wgu->getFilterGroupPage($filter);
        
        return View::make('EcdoHulk::group/index')->with(compact('group', 'filter'));
    }

    // 创建组别
    public function crGroup()
    {
        return View::make('EcdoHulk::group/create');
    }

    // 编辑组别
    public function upGroup()
    {
        $group = $this->wgu->getOneGroup(Input::get('group_id'));

        return View::make('EcdoHulk::group/update')->with(compact('group'));
    }

    // 创建组别处理
    public function crupGroupDis()
    {
        // 表单验证规则
        $rules = 'Required|min:1|max:30';
        if (Input::get('id')) {
            if (Input::get('o_name') != Input::get('name')) {
                $rules .= '|unique:'.TowerUtils::fetchTowerGuid().'_wechat_group,name';
            }
        } else {
            $rules .= '|unique:'.TowerUtils::fetchTowerGuid().'_wechat_group,name';
        }

        // validator-ajax表单验证
        if ($rules) {
            $this->response_rules['name'] = $rules;
            $this->ajaxValidator(Input::all());
        }

        // validator-ajax验证通过
        if ($this->response_type == 'success') {
            if (! $this->wgu->crupGroup(Input::all())) {
                $this->response_type = 'error';
                if (Input::get('id')) {
                    $msg = '编辑组别失败!';
                } else {
                    $msg = '创建组别失败!';
                }

                $this->response_msg = $msg;
            }
        }
        
        // ajax返回请求
        $this->end('\Ecdo\EcdoHulk\WechatGroups@index');
    }

    // 删除组别
    public function deGroup()
    {
        if ($this->wgu->deleteGroup(Input::get('group_id'))) {
            return Redirect::to('angel/wechat/group')->with('success', '删除组别成功!');
        } else {
            return Redirect::to('angel/wechat/group')->with('error', '删除组别失败!');
        }
    }

    // 批量删除组别
    public function drGroup()
    {
        if ($this->wgu->dropGroup(Input::get('id'))) {
            return Redirect::to('angel/wechat/group')->with('success', '批量删除组别成功!');
        } else {
            return Redirect::to('angel/wechat/group')->with('error', '批量删除组别失败!');
        }
    }

    // 导入组别
    public function imGroup()
    {
        return View::make('EcdoHulk::group/import');
    }

    // 导入组别处理
    public function imGroupDis()
    {
        // 文件导入验证
        if (! Input::hasFile('file')) {
            return Redirect::to('angel/wechat/group')->with('error', '请先上传文件!');
        } else {
            $res = $this->fileValidator(Input::file('file'), 'csv');
            if ($res['errcode'] == 'error') {
                return Redirect::to('angel/wechat/group')->with('error', $res['msg']);
            }

            // csv获取导入文件并打开文件流
            $file_data = $_FILES['file'];
            $handle = fopen($file_data['tmp_name'], 'r');
            $result = $this->csvImport($handle);
            $res = $this->wgu->importGroup($result, $this->rows);

            // 关闭文件流
            fclose($handle);

            if ($res['errcode'] == 'error') {
                return Redirect::to('angel/wechat/group')->with('error', $res['msg']);
            } else {
                return Redirect::to('angel/wechat/group')->with('success', '导入组别成功!');
            }
        }
    }

    // 导出组别
    public function exGroup()
    {
        // 导出头信息及标题
        $title = $this->wgu->getExportType();
        $this->ExcelHead($title);

        // 获取内容并导出
        $data = $this->wgu->exportGroup(Input::get('id'));
        foreach ($data as $k => $v) {
            $this->ExcelBody($v);
        }
    }
}
