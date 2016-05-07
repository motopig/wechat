<?php
namespace Ecdo\EcdoIronMan;

use Ecdo\EcdoSpiderMan\AngelCommon;

/**
 * IronMan公用类
 * 
 * @category yunke
 * @package atlas\hell\iron-man\src\controllers\common
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class IronManCommon extends AngelCommon
{
    public function __construct()
    {
        parent::__construct();

        $this->sideMenu(array('m_market','m_thor'));
    }
}
