<?php
namespace Ecdo\EcdoIronMan;

use Ecdo\EcdoSpiderMan\AngelCommon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoIronMan\CouponsUtils;
use Ecdo\EcdoIronMan\IronManCommon;

/**
 * 卡券app
 *
 * @category yunke
 * @package atlas\hell\iron-man\src\controllers\coupons
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class Coupones extends IronManCommon
{
    public function __construct()
    {
        parent::__construct();

        $this->cu = new CouponsUtils();
    }

    // 卡券基础设置
    public function conponSetting()
    {
        $setting = $this->cu->couponSetting();

        return View::make('EcdoIronMan::coupons/setting')->with(compact('setting')); 
    }

    // 卡券基础设置处理
    public function conponSettingDis()
    {
        $arr = $this->cu->couponSettingDis(Input::all());
        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoIronMan\Coupones@conponSetting');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }
    
    // 卡券列表
    public function index()
    {
    	$type = $this->cu->getType();
    	$coupons_type = $this->cu->getCouponsType()['type'];
    	$coupons_type_notice = $this->cu->getCouponsType()['notice'];
        $setting = $this->cu->couponSetting();
        $coupons = $this->cu->getCouponsPage();
        $delivery_type = $this->cu->getDelivery()['type'];
        $delivery_notice = $this->cu->getDelivery()['notice'];

    	return View::make('EcdoIronMan::coupons/index')
        ->with(compact('type', 'coupons_type', 'coupons_type_notice', 
        'setting', 'coupons', 'delivery_type', 'delivery_notice'));
    }

    // 卡券搜索
    public function couponSearch()
    {
        $search = Input::get('search');
        $type = $this->cu->getType();
        $coupons_type = $this->cu->getCouponsType()['type'];
        $coupons_type_notice = $this->cu->getCouponsType()['notice'];
        $setting = $this->cu->couponSetting();
        $coupons = $this->cu->getCouponSearchPage($search);

        return View::make('EcdoIronMan::coupons/index')
        ->with(compact('type', 'coupons_type', 'coupons_type_notice', 'setting', 'coupons', 'search'));
    }

    // 卡券筛选
    public function couponsFilter()
    {
        $coupons_type = $this->cu->getCouponsType()['type'];
        $type = $this->cu->getType();
        $status = $this->cu->getStatus();

        return View::make('EcdoIronMan::coupons/filter')
        ->with(compact('type', 'coupons_type', 'status'));
    }

    // 卡券筛选处理
    public function couponsFilterDis()
    {
        // 判断是否已经筛选进分页
        if (Input::get('filter')) {
            // 删除空元素
            $data = Input::get('filter');
            $data = array_filter($data, function($i) {
                if ($i != '') {
                    return true;
                }
            });
        } else {
            // 删除csrf_token csrf_guid 空元素
            $data = Input::All();
            unset($data['csrf_token']);
            unset($data['csrf_guid']);
            $data = array_filter($data, function($i) {
                if ($i != '') {
                    return true;
                }
            });
        }

        $filter = $data;
        $type = $this->cu->getType();
        $coupons_type = $this->cu->getCouponsType()['type'];
        $coupons_type_notice = $this->cu->getCouponsType()['notice'];
        $setting = $this->cu->couponSetting();
        $coupons = $this->cu->getCouponFilterPage($filter);

        return View::make('EcdoIronMan::coupons/index')
        ->with(compact('type', 'coupons_type', 'coupons_type_notice', 'setting', 'coupons', 'filter'));
    }

    // 卡券定义
    public function couponsType()
    {
        $arr = ['errcode' => 'success', 'url' => action('\Ecdo\EcdoIronMan\Coupones@createCoupons', 
            ['type' => Input::get('type') . '_' . Input::get('coupons_type')])];

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 卡券适用门店
    public function conponStore()
    {
        $arr = [];
        $id = explode(',', Input::get('id'));
        $store_id = explode(',', Input::get('store_id'));

        // 获取是否有新增的差集
        $plus = array_diff($id, $store_id);
        if (! empty($plus)) {
            $arr = $this->cu->getAllEntityShop($plus);
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 投放卡券
    public function couponsDelivery()
    {
        $arr = $this->cu->couponsDelivery(Input::all());

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 创建卡券
    public function createCoupons($type)
    {
        $data = explode('_', $type);
        $type = ['key' => $data[0], 'value' => $this->cu->getType()[$data[0]]];
        $coupons_type = ['key' => $data[1], 'value' => $this->cu->getCouponsType()['type'][$data[1]]];
        $setting = $this->cu->couponSetting();
        $color = json_encode($this->cu->getCouponsColor(), JSON_UNESCAPED_UNICODE);
        $content = json_encode($this->cu->getCouponsContent($coupons_type['key']), JSON_UNESCAPED_UNICODE);

        return View::make('EcdoIronMan::coupons/create')
        ->with(compact('type', 'coupons_type', 'color', 'setting', 'content'));
    }

    // 创建卡券处理
    public function createCouponsDis()
    {
        $arr = $this->cu->couponsCreate(Input::all());
        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoIronMan\Coupones@index');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 编辑卡券
    public function updateCoupons($id, $type)
    {
        $data = explode('_', $type);
        $type = ['key' => $data[0], 'value' => $this->cu->getType()[$data[0]]];
        $coupons_type = ['key' => $data[1], 'value' => $this->cu->getCouponsType()['type'][$data[1]]];
        $setting = $this->cu->couponSetting();
        $color = json_encode($this->cu->getCouponsColor(), JSON_UNESCAPED_UNICODE);
        $content = json_encode($this->cu->getCouponsContent($coupons_type['key']), JSON_UNESCAPED_UNICODE);
        $coupons = $this->cu->getOneCoupons($id);

        return View::make('EcdoIronMan::coupons/update')
        ->with(compact('type', 'coupons_type', 'color', 'setting', 'content', 'coupons'));
    }

    // 编辑卡券处理
    public function updateCouponsDis()
    {
        $arr = $this->cu->couponsUpdate(Input::all());
        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoIronMan\Coupones@index');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 删除卡券
    public function couponsDelete()
    {
        $arr = $this->cu->couponsDelete(Input::all());
        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoIronMan\Coupones@index');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }
}
