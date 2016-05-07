<?php
namespace Ecdo\Tower;

/**
 * 店铺菜单管理器
 * 
 * @package Ecdo\Tower
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class TowerMenuManager
{
    /**
     * 获取菜单数据，详细数据
     * 
     * @return array
     */
    public function getTowerMenu()
    {
        $tm = new TowerMenu();
        $tm->generate();
        $menus = $tm->fetchGroup();
        
        return $menus;
    }
    
    /**
     * 获取用户可用菜单
     * 
     * @return array
     */
    public function getUserMenu()
    {
        return $this->getTowerMenu();
    }
}
