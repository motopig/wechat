<?php
namespace App\Controllers;

use Illuminate\Support\Facades\View;

/**
 * 平台控制台
 * 
 * @category yunke
 * @package app\controllers\god
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class GodDashboardController extends GodController
{

    public function __construct()
    {
        parent::__construct();
    }
    
    // 首页
    public function index()
    {
        return View::make('god/dashboard/index');
    }
}
