<?php namespace Ecdo\Tower;

use Ecdo\Universe\BasePermission;
use Ecdo\Universe\TowerUtils;
use Ecdo\Atlas\AtlasUtils;

/**
 * 商户权限类
 * 
 * @package Ecdo\Tower
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class TowerPermission extends BasePermission
{
    /**
     * 店铺唯一识别
     *
     * @var string
     */
    protected $towerGuid = '';
    
    /**
     * 权限组缓存键
     *
     * @var string
     */
    protected $groupKey = 'tower.cache.permission.group';
    
    /**
     * 权限树缓存键
     *
     * @var string
     */
    protected $treeKey = 'tower.cache.permission.tree';
    
    /**
     * 文件路径匹配模式
     *
     * @var string
     */
    protected $pattern = '/permission*.json';
    
    /**
     * 设置平台权限文件所在路径
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
    protected function getStarPathPattern($star = '')
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
     * @see \Ecdo\Universe\UniversePermission::getGroupKey()
     */
    protected function getGroupKey()
    {
        return $this->groupKey . '.' . $this->getTowerGuid();
    }
    
    /**
     * 获取店铺对应的tree key
     * 
     * @return string
     * @see \Ecdo\Universe\UniversePermission::getTreeKey()
     */
    protected function getTreeKey()
    {
        return $this->treeKey . '.' . $this->getTowerGuid();
    }
    
    /**
     * 获取路径下所有权限文件中的权限
     *
     * @return array
     */
    public function scan()
    {
        $stars = $this->getEnabledStars();
        $files = [];
        foreach ($stars as $star) {
            $path = $this->getStarPathPattern($star);
            $files = array_merge($this->fs->glob($path));
        }
        
        $permission = [];
        if (! empty($files)) {
            foreach ($files as $file) {
                $permission[] = $this->getFromFile($file);
            }
    
            $permission = $this->format($permission);
        }
    
        return $permission;
    }
}
