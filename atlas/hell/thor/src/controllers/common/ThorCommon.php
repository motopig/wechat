<?php
namespace Ecdo\EcdoThor;

use Ecdo\EcdoSpiderMan\AngelCommon;

/**
 * Thor公用类
 * 
 * @category yunke
 * @package atlas\hell\thor\src\controllers\common
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class ThorCommon extends AngelCommon
{
    public function __construct()
    {
        parent::__construct();

        $this->sideMenu(array('m_market','m_thor'));
    }
}
