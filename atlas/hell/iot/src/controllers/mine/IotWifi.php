<?php
/**
* wifi管理
* description
* package atlas/hell/iot/src/controllers/mine/IotWifi.php
* date 2015-06-23 09:21:42
* author Hello <hello@no>
* @copyright ECDO. All Rights Reserved.
*/
 

namespace Ecdo\EcdoIot;

use Ecdo\EcdoIot\IotCommon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class IotWifi extends IotCommon
{
    public function __construct()
    {
        parent::__construct();
    }
    
    //Wifi首页
    public function index()
    {
        return View::make('EcdoIot::wifi/index');
    }
    
}
