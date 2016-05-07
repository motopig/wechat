<?php
namespace App\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use Ecdo\Universe\TowerUtils;

/**
 * 系统控制器
 * 
 * @category yunke
 * @package app
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class BaseController extends Controller
{
    // 不检查权限控制器方法
    protected $whitelist = array();
    
    public $routes;
    
    // __construct 构造方法
    public function __construct()
    {
        // csrf 处理
        $this->beforeFilter('csrf', array(
            'on' => 'post'
        ));
        $this->routePath();
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (! is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }
    
    public function routePath(){
        $route_path = Route::current()->getPath();
        \Session::put('route_path', $route_path);
        \View::share('route_path',$route_path);
    }
    
    public function sideMenu($display=array()){
        $menus = TowerUtils::fetchTowerMenus();
        
        if($menus){
            $new_menus = array();
            if(is_array($display) && !empty($display)){
                \Session::put('side_menu',$display);
                foreach($display as $menu){
                    if(array_key_exists($menu,$menus['group'])){
                        $new_menus['group'][$menu] = $menus['group'][$menu];
                    }
                    if(array_key_exists($menu,$menus['menu'])){
                        $new_menus['menu'][$menu] = $menus['menu'][$menu];
                    }
                }
            }else{
                $new_menus = $menus;
            }
            \View::share('side_menu',$new_menus);
        }
    }
}
