<?php
namespace Ecdo\EcdoBatMan;

use Ecdo\EcdoSpiderMan\AngelCommon;

/**
 * BatMan公用类
 * 
 * @category yunke
 * @package atlas\hell\hulk\src\controllers\common
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class BatManCommon extends AngelCommon
{
    public function __construct()
    {
        parent::__construct();

        $this->sideMenu(array('m_market','m_thor'));
    }
}
