<?php
/**
* 智能硬件管理
* 智能硬件管理的应用
* package atlas/hell/iot/src/controllers/common/IotCommon.php/test
* date 2015-06-22 18:43:56
* author Hello <hello@no>
* @copyright ECDO. All Rights Reserved.
*/
 

namespace Ecdo\EcdoIot;

use Ecdo\EcdoSpiderMan\AngelCommon;
use Illuminate\Support\Facades\View;
use Ecdo\Universe\TowerUtils;
 

class IotCommon extends AngelCommon
{
    public function __construct()
    {
        parent::__construct();
        $this->sideMenu(array('m_iot'));
    }
}
