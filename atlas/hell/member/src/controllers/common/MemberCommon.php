<?php
namespace Ecdo\EcdoMember;

use Ecdo\EcdoSpiderMan\AngelCommon;
//use Ecdo\EcdoHulk\WechatDashboardUtils;
use Illuminate\Support\Facades\View;
use Ecdo\Universe\TowerUtils;

/**
 * 商家控制器公用类
 * 
 * @category yunke
 * @package atlas\hell\member\src\controllers\common
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class MemberCommon extends AngelCommon
{
    public function __construct()
    {
        parent::__construct();
        $this->sideMenu(array('m_member'));
    }
}
