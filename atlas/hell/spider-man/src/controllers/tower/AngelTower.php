<?php
namespace Ecdo\EcdoSpiderMan;

use Ecdo\EcdoSpiderMan\AngelCommon;
use App\Models\Tower as TowerModel;
use Illuminate\Support\Facades\View;
use Ollieread\Multiauth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Ecdo\Universe\Atlas\StarNest;
use Ecdo\Tower\Atlas\TowerStarManager;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoSpiderMan\AngelDashboardUtils;
use Ecdo\EcdoSpiderMan\AngelAccountUtils;
use Ecdo\EcdoHulk\WechatDashboardUtils;
use Ecdo\Universe\TowerDB;


class AngelTower extends AngelCommon{
    
    public $notTowerRoute = ['tower/setPlan'];
    
    function setPlan($data){
        
    }
}