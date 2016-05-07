<?php namespace Ecdo\God;

use Ecdo\Universe\BaseMenu;

/**
 * 平台菜单类
 * 
 * @package Ecdo\God
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class GodMenu extends BaseMenu
{

    /**
     * 菜单组缓存键
     *
     * @var string
     */
    protected $groupKey = 'god.cache.menu.group';
    
    /**
     * 菜单树缓存键
     *
     * @var string
     */
    protected $treeKey = 'god.cache.menu.tree';
    
    /**
     * 文件路径匹配模式
     *
     * @var string
     */
    protected $pattern = '/menu*.json';
    
    /**
     * 设置平台菜单文件所在路径
     */
    public function __construct()
    {
        parent::__construct();
        $this->path = app_path() . '/routes';
    }
}
