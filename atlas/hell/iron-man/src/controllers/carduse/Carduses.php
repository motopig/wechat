<?php
namespace Ecdo\EcdoIronMan;

use Ecdo\EcdoSpiderMan\AngelCommon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoIronMan\IronManCommon;
use Ecdo\EcdoIronMan\CarduseUtils;
use Ecdo\EcdoIronMan\CouponsUtils;

/**
 * 卡券核销
 *
 * @category yunke
 * @package atlas\hell\iron-man\src\controllers\carduse
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class Carduses extends IronManCommon
{
    public function __construct()
    {
        parent::__construct();

        $this->c = new CouponsUtils();
        $this->cu = new CarduseUtils();
    }
    
    // 核销券列表
    public function index()
    {	
    	$type = $this->c->getType();
    	$coupons_type = $this->c->getCouponsType()['type'];
    	$status = $this->cu->getStatus();
    	$carduseType = $this->cu->getType();
    	$carduse = $this->cu->getCardusePage();

    	return View::make('EcdoIronMan::carduse/index')
    	->with(compact('carduse', 'status', 'carduseType', 'type', 'coupons_type'));
    }

    // 核销券搜索
    public function carduseSearch()
    {
    	$search = Input::get('search');
    	$type = $this->c->getType();
    	$coupons_type = $this->c->getCouponsType()['type'];
    	$status = $this->cu->getStatus();
    	$carduseType = $this->cu->getType();
        $carduse = $this->cu->getCarduseSearchPage($search);

        return View::make('EcdoIronMan::carduse/index')
    	->with(compact('carduse', 'status', 'carduseType', 'type', 'coupons_type', 'search'));
    }

    // 核销券筛选
    public function carduseFilter()
    {
    	$type = $this->c->getType();
    	$coupons_type = $this->c->getCouponsType()['type'];
    	$status = $this->cu->getStatus();
    	$carduseType = $this->cu->getType();

        return View::make('EcdoIronMan::carduse/filter')
        ->with(compact('status', 'carduseType', 'type', 'coupons_type'));
    }

    // 核销券筛选处理
    public function carduseFilterDis()
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
        $type = $this->c->getType();
    	$coupons_type = $this->c->getCouponsType()['type'];
    	$status = $this->cu->getStatus();
    	$carduseType = $this->cu->getType();
        $carduse = $this->cu->getCarduseFilterPage($filter);

        return View::make('EcdoIronMan::carduse/index')
    	->with(compact('carduse', 'status', 'carduseType', 'type', 'coupons_type', 'filter'));
    }

    // 卡券核销
    public function carduseVerification()
    {
    	$arr = $this->cu->carduseVerification(Input::all());
        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoIronMan\Carduses@index');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }
}
