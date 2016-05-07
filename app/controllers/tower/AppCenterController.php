<?php namespace Ecdo\Tower;

use Session;
use App\Controllers\BaseController;
use Ecdo\Tower\Atlas\TowerStarManager;
use Ecdo\Universe\TowerUtils;
use Ecdo\Universe\Atlas\StarNest;

/**
 * 店铺应用中心控制器类
 * 
 * @package Ecdo\Tower
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class AppCenterController extends BaseController
{
    
    public function __construct(){
        parent::__construct();
        $this->sideMenu(array('m_appCenter'));
    }

    /**
     * 应用中心列表页
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 获取店铺所有应用
        $towerGuid = TowerUtils::fetchTowerGuid();
        $sn = new StarNest();
        $allStars = $sn->getAllStars();
        $tsm = new TowerStarManager($towerGuid);
        $instStars = $tsm->getAllStars();

        // 应用分类
        $baseStars = [];
        $optStars = [];
        foreach ($allStars as $star => $row) {
            if (! empty($instStars[$star])) {
                $row = array_merge($row, $instStars[$star]);
                $row['inst_at'] = $instStars[$star]['created_at'];
                $row['expire'] = $instStars[$star]['expire'];
                $allStars[$star] = $row;
                $tmp = $row;
                $tmp['id'] = $instStars[$star]['id'];
                $instStars[$star] = $tmp;
            }
            
            if ($row['type'] === 'base') {
                $baseStars[$star] = $row;
            } else {
                $optStars[$star] = $row;
            }
        }

        // 检查按钮权限
        $tpm = new TowerPermissionManager();
        $chkPerm['inst'] = $tpm->chkUserOwnPermByPath('angel/appCenter/install');
        
        return \View::make('tower/appcenter/index', compact('allStars', 'instStars', 'baseStars', 'optStars', 'chkPerm'));
    }
    
    /**
     * 安装应用
     * 
     * @return string|\Illuminate\Http\Response
     */
    public function install()
    {
        $star = \Input::get('star');
        $towerGuid = TowerUtils::fetchTowerGuid();
        $tsm = new TowerStarManager($towerGuid);
        
        $rs = $tsm->installStar($star);
        
        if ($rs) {
            return 'success';
        } else {
            return 'failed';
        }
    }

    // 刷新应用 - no
    public function replace()
    {
        $res = ['errcode' => 'success', 'errmsg' => '应用更新完成!', 
        'url' => action('\Ecdo\Tower\AppCenterController@index')];

        $towerGuid = TowerUtils::fetchTowerGuid();
        $tsm = new TowerStarManager($towerGuid);
        $tsm->installStar(\Input::get('star'));

        exit(json_encode($res, JSON_UNESCAPED_UNICODE));
    }
}