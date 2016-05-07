<?php
namespace Ecdo\EcdoBatMan;

use Ecdo\EcdoBatMan\BatManCommon;
use Ecdo\EcdoSpiderMan\AngelCommon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoBatMan\EntityShopUtils;

/**
 * 门店基本配置
 *
 * @category yunke
 * @package atlas\hell\bat-man\src\controllers\entityshop
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class EntityShops extends BatManCommon
{
    public function __construct()
    {
        parent::__construct();

        $this->esu = new EntityShopUtils();
    }
    
    // 获取门店
    public function index()
    {
    	$entityshop = $this->esu->getEntityShopPage();

        return View::make('EcdoBatMan::entityshop/index')->with(compact('entityshop'));
    }

    // 搜索门店
    public function seEntityShop()
    {
    	$search = Input::get('search');
        $entityshop = $this->esu->getSearchEntityShopPage($search);

        return View::make('EcdoBatMan::entityshop/index')->with(compact('entityshop', 'search'));
    }

    // 筛选门店
    public function fiEntityShop()
    {
        return View::make('EcdoBatMan::entityshop/filter');
    }

    // 筛选门店处理
    public function fiEntityShopDis()
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
        $entityshop = $this->esu->getFilterEntityShopPage($filter);

        return View::make('EcdoBatMan::entityshop/index')->with(compact('entityshop', 'filter'));
    }

    // 附近门店配置
    public function nearbyEntityShop()
    {
        $nearby = $this->esu->nearbyEntityShopConfig();
        
        return View::make('EcdoBatMan::entityshop/nearby')->with(compact('nearby'));
    }

    // 附近门店配置处理
    public function nearbyEntityShopDis()
    {
        $arr = $this->esu->nearbyEntityShopConfigDis(Input::all());
        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoBatMan\EntityShops@nearbyEntityShop');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 创建门店
    public function crEntityShop()
    {
        $category = \Config::get('EcdoSpiderMan::setting')['category'];
        $categoryjson = json_encode($category);
        
        return View::make('EcdoBatMan::entityshop/create')->with(compact('category', 'categoryjson'));
    }

    // 创建门店处理
    public function crEntityShopDis()
    {
        $arr = $this->esu->createEntityShop(Input::all());

        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoBatMan\EntityShops@index');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 编辑门店
    public function upEntityShop()
    {
        $category = \Config::get('EcdoSpiderMan::setting')['category'];
        $categoryjson = json_encode($category);
        $shop = $this->esu->getEntityShopOne(Input::get('id'));
        
        return View::make('EcdoBatMan::entityshop/update')->with(compact('shop', 'category', 'categoryjson'));
    }

    // 编辑门店处理
    public function upEntityShopDis()
    {
        $arr = $this->esu->updateEntityShop(Input::all());

        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoBatMan\EntityShops@index');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 删除门店
    public function deEntityShop()
    {
        $arr = $this->esu->deleteEntityShop(Input::all());

        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoBatMan\EntityShops@index');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 同步门店至微信审核
    public function wechatEntityShop()
    {
        $arr = $this->esu->wechatEntityShop(Input::all());

        if ($arr['errcode'] == 'success') {
            $arr['url'] = action('\Ecdo\EcdoBatMan\EntityShops@index');
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }
}
