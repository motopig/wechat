<?php
/**
* 我的硬件
* description
* package atlas/hell/iot/src/controllers/shop/IotShop.php
* date 2015-06-22 19:07:43
* author Hello <hello@no>
* @copyright ECDO. All Rights Reserved.
*/

namespace Ecdo\EcdoIot;

use Ecdo\EcdoIot\IotCommon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class IotMine extends IotCommon
{
    public function __construct()
    {
        parent::__construct();
    }
    
    //商店首页
    public function index()
    {
        return View::make('EcdoIot::mine/index');
    }
    
}
