<?php
namespace App\Controllers;

use Illuminate\Support\Facades\View;

/**
 * 平台首页
 * 
 * @category yunke
 * @package app\controllers
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class HomeController extends BaseController
{

    public function showWelcome()
    {
        $page_title = "一点云客 - 移动智能营销平台";
        $page_keyword = "云客,微信摇一摇,智能硬件,微信POS,微信wifi,微信路由器";
        $page_description = "一点云客提供基于微信的移动智能营销服务，包括摇一摇，微信wifi，微信签到，微信调查等多种营销服务。";
        return View::make('site/hello')->with(compact('page_title','page_keyword','page_description'));
    }
    
    public function showFeature(){
        $page_title = "功能介绍 - 一点云客|移动智能营销平台";
        $page_keyword = "云客,微信摇一摇,智能硬件,微信POS,微信wifi,微信路由器";
        $page_description = "一点云客提供基于微信的移动智能营销服务，包括摇一摇，微信wifi，微信签到，微信调查等多种营销服务。";
        
        
        return View::make('site/feature')->with(compact('data','page_title','page_keyword','page_description'));
    }
    
    public function showPrice(){
        $page_title = "产品价格 - 一点云客|移动智能营销平台";
        $page_keyword = "云客,微信摇一摇,智能硬件,微信POS,微信wifi,微信路由器";
        $page_description = "一点云客提供基于微信的移动智能营销服务，包括摇一摇，微信wifi，微信签到，微信调查等多种营销服务。";
        
        
        return View::make('site/price')->with(compact('data','page_title','page_keyword','page_description'));
    }
    
    public function showHelp($view=''){
        $page_title = "帮助手册 - 一点云客|移动智能营销平台";
        $page_keyword = "云客,微信摇一摇,智能硬件,微信POS,微信wifi,微信路由器";
        $page_description = "一点云客提供基于微信的移动智能营销服务，包括摇一摇，微信wifi，微信签到，微信调查等多种营销服务。";
        
        
        
        if(empty($view)){
            return View::make('site/help')->with(compact('data','page_title','page_keyword','page_description','view'));
        }else{
            return View::make('site/help'.$view)->with(compact('data','page_title','page_keyword','page_description','view'));
        }
        
    }
}
