<?php namespace Ecdo\Tower;

use Ecdo\Universe\BaseMenu;
use Ecdo\Universe\TowerUtils;
use Ecdo\Atlas\AtlasUtils;

/**
 * 商户店铺菜单类
 * 
 * @package Ecdo\Tower
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class TowerMenu extends BaseMenu
{
    /**
     * 店铺唯一识别
     * 
     * @var string
     */
    protected $towerGuid = '';
    
    /**
     * 菜单组缓存键
     *
     * @var string
     */
    protected $groupKey = 'tower.cache.menu.group';
    
    /**
     * 菜单树缓存键
     *
     * @var string
     */
    protected $treeKey = 'tower.cache.menu.tree';
    
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
        $this->path = AtlasUtils::getAtlasPath();
    }
    
    /**
     * 根据传递的star返回路径匹配模式
     *
     * @param string $star
     * @return string
     */
    protected function getStarPathPattern($star)
    {
        if ($star === '*/*') {
            $star = '*/*';
        } else {
            list($vendor, $package) = explode('/', $star);
            $star = snake_case($vendor, '-') . '/' . snake_case($package, '-');
        }
        $pattern = $this->path . '/' . $star . '/src/routes' . $this->pattern;
    
        return $pattern;
    }
    
    /**
     * 获取已启用star
     *
     * @return array
     */
    protected function getEnabledStars()
    {
        return ['*/*'];
    }
    
    /**
     * 获取店铺的唯一识别，如果不存在，从缓存中读取
     * 
     * @return string
     */
    protected function getTowerGuid()
    {
        if (empty($this->towerGuid)) {
            $this->towerGuid = TowerUtils::fetchTowerGuid();
        }
        
        return $this->towerGuid;
    }
    
    /**
     * 获取店铺对应的group key
     *
     * @return string
     * @see \Ecdo\Universe\UniverseMenu::getGroupKey()
     */
    protected function getGroupKey()
    {
        return $this->groupKey . '.' . $this->getTowerGuid();
    }
    
    /**
     * 获取店铺对应的tree key
     *
     * @return string
     * @see \Ecdo\Universe\UniverseMenu::getTreeKey()
     */
    protected function getTreeKey()
    {
        return $this->treeKey . '.' . $this->getTowerGuid();
    }
    
    /**
     * 获取路径下所有菜单文件中的菜单
     *
     * @return array
     */
    public function scan()
    {
        $stars = $this->getEnabledStars();
        $files = [];
        foreach ($stars as $star) {
            $path = $this->getStarPathPattern($star);
            $files = array_merge($files, $this->fs->glob($path));
        }

        $menu = [];
        if (! empty($files)) {
            foreach ($files as $file) {
                $menu = array_merge($menu, $this->getFromFile($file)['menu']);
            }
        }
    
        return ['menu' => $menu];
    }
}