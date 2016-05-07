<?php namespace Ecdo\Tower;

use Ecdo\Universe\TowerUtils;
/**
 * 店铺控制器
 * 
 * @package Ecdo\Tower
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class TowerController extends \Controller
{
    /**
     * 获取菜单，供ajax请求调用
     * 返回JSON
     * 
     * @return string
     */
    public function getMenus()
    {
        $menus = TowerUtils::fetchTowerMenus();
        
        if($menus){
            $display_menu = \Session::get('side_menu');
            $new_menus = array();
            
            if(is_array($display_menu) && !empty($display_menu)){
                foreach($display_menu as $menu){
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
            
            $str = json_encode($new_menus, JSON_UNESCAPED_UNICODE);
            return $str;
        }
    }
}
