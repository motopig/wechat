<?php
namespace Ecdo\EcdoHulk;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Ecdo\Universe\TowerUtils;
use App\Lib\RouteCommon;
use Ecdo\EcdoHulk\WechatGraphicsUtils;
use Ecdo\EcdoSpiderMan\SiteCommon;

/**
 * 微信前台控制器
 * 
 * @category yunke
 * @package atlas\hell\hulk\src\controllers\site
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatSite extends SiteCommon
{
    public function __construct()
    {
        parent::__construct();
    }

    // 微信图文内容展示
    public function graphics($guid, $id)
    {
        $wgu = new WechatGraphicsUtils();
        $graphics = $wgu->getOneGraphic($id);

        return View::make('EcdoHulk::site/graphics/graphics')->with(compact('graphics'));
    }
}
