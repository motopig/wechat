<?php
namespace Ecdo\EcdoIronMan;

use Ecdo\EcdoSpiderMan\AngelCommon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoIronMan\IronManCommon;
use Ecdo\EcdoIronMan\verificationUtils;

/**
 * 核销员
 *
 * @category yunke
 * @package atlas\hell\iron-man\src\controllers\carduse
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class Verifications extends IronManCommon
{
    public function __construct()
    {
        parent::__construct();

        $this->vu = new verificationUtils();
    }

    // 核销员列表
    public function index()
    {
    	$verification = $this->vu->getVerificationPage();
        $status = $this->vu->getStatus();

    	return View::make('EcdoIronMan::verification/index')->with(compact('verification', 'status'));
    }

    // 核销员搜索
    public function verificationSearch()
    {
        $search = Input::get('search');
        $verification = $this->vu->getVerificationSearchPage($search);

        return View::make('EcdoIronMan::verification/index')->with(compact('verification', 'search'));
    }

    // 核销员筛选
    public function verificationFilter()
    {
        $status = $this->vu->getStatus();

        return View::make('EcdoIronMan::verification/filter')->with(compact('verification', 'status'));
    }

    // 核销员筛选处理
    public function verificationFilterDis()
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
        $verification = $this->vu->getVerificationFilterPage($filter);

        return View::make('EcdoIronMan::verification/index')->with(compact('verification', 'filter'));
    }
    
    // 核销员创建
    public function verificationCreate()
    {
        return View::make('EcdoIronMan::verification/create')->with(compact('verification'));
    }

    // 核销员创建处理
    public function verificationCreateDis()
    {
        $arr = $this->vu->verificationCreateDis(Input::all());
        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoIronMan\Verifications@index');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 核销员编辑
    public function verificationUpdate()
    {
        $verification = $this->vu->getOneVerification(Input::get('id'));
        $status = $this->vu->getStatus();

        return View::make('EcdoIronMan::verification/update')->with(compact('verification', 'status'));
    }

    // 核销员编辑处理
    public function verificationUpdateDis()
    {
        $arr = $this->vu->verificationUpdateDis(Input::all());
        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoIronMan\Verifications@index');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 核销员删除
    public function verificationDelete()
    {
        $arr = $this->vu->verificationDelete(Input::all());
        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoIronMan\Verifications@index');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }
}
