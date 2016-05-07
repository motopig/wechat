<?php
namespace Ecdo\EcdoBatMan;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Ecdo\Universe\TowerUtils;
use App\Lib\RouteCommon;
use Ecdo\EcdoHulk\WechatGraphicsUtils;
use Ecdo\EcdoSpiderMan\SiteCommon;
use Ecdo\EcdoBatMan\EntityShopUtils;

/**
 * 门店前台控制器
 * 
 * @category yunke
 * @package atlas\hell\bat-man\src\controllers\site
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class EntityShopSite extends SiteCommon
{
    public function __construct()
    {
        parent::__construct();

        $this->esu = new EntityShopUtils();
    }

    // 附近门店列表展示
    // 31.066314/121.40714
    public function entityList($guid, $latitude, $longitude)
    {
        $list = $this->esu->nearbyEntityShop($guid, $latitude, $longitude);

        return View::make('EcdoBatMan::site/nearby/entitylist')->with(compact('list'));
    }

    // 附近门店详情展示
    // 95530
    public function entityDetail($guid, $sid)
    {
        $detail = $this->esu->nearbyEntityShopOne($guid, $sid);
        $baidu = \Config::get('gravity.baidu.url.map') . '?ak=' . \Config::get('key.baidu.ak') . '&v=1.0';

        return View::make('EcdoBatMan::site/nearby/entitydetail')->with(compact('detail', 'baidu'));
    }
}
