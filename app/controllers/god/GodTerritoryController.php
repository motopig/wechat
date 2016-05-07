<?php
namespace App\Controllers;

use App\Lib\GodTerritorySelect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

/**
 * 企业控制台
 *
 * @category yunke
 * @package app\controllers\god
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class GodTerritoryController extends GodController
{

    public function __construct()
    {
        parent::__construct();
        
        $this->gts = new GodTerritorySelect();
    }
    
    // 首页
    public function index()
    {
        $territory = $this->gts->getTerritoryAll();
        
        return View::make('god/territory/index')->with(compact('territory'));
    }
}
