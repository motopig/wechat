<?php namespace Ecdo\Tower\Atlas;

use Ecdo\Universe\TowerUtils;
use Ecdo\Tower\TowerMenu;
use Ecdo\Tower\TowerPermission;
use Ecdo\Model\Tower\TowerStar;
use Ecdo\Tower\Repository\TowerStarRepository;
use Ecdo\Tower\Migration\TowerMigrationManager;
use Ecdo\Universe\Cache\JsonCache;
use Ecdo\Universe\Atlas\StarNest;

/**
 * 店铺应用包管理类
 * 
 * @package Ecdo\Angel\Star
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class TowerStarManager
{
    
    /**
     * @var string
     */
    protected $towerGuid = '';
    
    /**
     * @var object
     */
    protected $cache = null;
    
    /**
     * 构造初始化
     * 
     * @param string $towerGuid
     */
    public function __construct($towerGuid)
    {
        $this->towerGuid = $towerGuid;
        $this->cache = new JsonCache();
    }
    
    // /**
    //  * 安装应用
    //  * 
    //  * @param sting $star
    //  * @return bool
    //  */
    // public function installStar($star)
    // {
    //     $tsr = new TowerStarRepository($this->towerGuid);
    //     if (! $tsr->repositoryExists()) {
    //         $tsr->createRepository();
    //     }
        
    //     $sn = new StarNest();
    //     $starInfo = $sn->getStarInfo($star);
    //     if (empty($starInfo)) {
    //         return false;
    //     }

    //     $tmp = TowerStar::where('star', $star)->get(['id'])->toArray();
    //     if (! empty($tmp['id'])) {
    //         return false;
    //     }
    //     //存储当前应用
    //     TowerUtils::storeTowerStars();

    //     $expire = '2038-01-01 00:00:00';
    //     $arr = ['star' => $star, 'expire' => $expire, 'enabled' => 'Y'];
        
    //     $mdlStar = new TowerStar($arr);
    //     $rs = $mdlStar->save();
    //     if ($rs) {
    //         $tmm = new TowerMigrationManager($this->towerGuid);
    //         $tmm->migrateStar($star);
            
    //         // 更新缓存
    //         $this->refreshCache();
    //     }
        
    //     return $rs;
    // }

    // 安装应用 - no
    public function installStar($star)
    {
        $rs = true;

        $tsr = new TowerStarRepository($this->towerGuid);
        if (! $tsr->repositoryExists()) {
            $tsr->createRepository();
        }
        
        $sn = new StarNest();
        $starInfo = $sn->getStarInfo($star);
        if (empty($starInfo)) {
            return false;
        }

        //存储当前应用
        TowerUtils::storeTowerStars();

        $tmp = TowerStar::where('star', $star)->get(['id'])->toArray();
        if (empty($tmp[0]['id'])) {
            $expire = '2038-01-01 00:00:00';
            $arr = ['star' => $star, 'expire' => $expire, 'enabled' => 'Y'];
            
            $mdlStar = new TowerStar($arr);
            $rs = $mdlStar->save();
        }

        if ($rs) {
            $tmm = new TowerMigrationManager($this->towerGuid);
            $tmm->migrateStar($star);
            
            // 更新缓存
            $this->refreshCache();
        }
        
        return $rs;
    }
    
    /**
     * 获取所有已安装的应用
     * 
     * @return array
     */
    public function getAllStars()
    {
        $tsr = new TowerStarRepository($this->towerGuid);
        if (! $tsr->repositoryExists()) {
            $tsr->createRepository();
        }
        
        $stars = [];
        $stars = $this->fetch();
        
        if (empty($stars)) {
            $this->refreshCache();
            $stars = $this->fetch();
        }
        
        return $stars;
    }
    
    /**
     * 获取已启用的应用
     * 
     * @return array
     */
    public function getEnabledStars()
    {
        $stars = $this->getAllStars();
        $arr = [];
        foreach ((array) $stars as $row) {
            if ($row['enabled'] === 'Y') {
                $arr[$row['star']] = $row;
            }
        }
        
        return $arr;
    }
    
    /**
     * 启用指定应用
     * 
     * @param string $star
     * @return bool
     */
    public function enableStar($star)
    {
        $row = TowerStar::where('star', $star)->get('star, enabled');
        $row->enabled = 'Y';
        $rs = $row->save();
        
        if ($rs) {
            $this->refreshCache();
        }
        
        return $rs;
    }
    
    /**
     * 关闭应用
     * 
     * @param string $star
     * @return bool
     */
    public function disableStar($star)
    {
        $row = TowerStar::where('star', $star)->get('star, enabled');
        $row->enabled = 'N';
        $rs = $row->save();
        
        if ($rs) {
            $this->refreshCache();
        }
        
        return $rs;
    }
    
    /**
     * 获取TowerStar模型对象 
     * 
     * @return obj
     */
    protected function getTowerStar()
    {
        return TowerStar::setTowerGuid($this->towerGuid);
    }
    
    /**
     * 刷新缓存
     */
    public function refreshCache()
    {
        $tmp = TowerStar::all()->toArray();
        $stars = [];
        foreach ((array)$tmp as $row) {
            $stars[$row['star']] = $row;
        }
        
        // 更新店铺权限与菜单缓存
        $tp = new TowerPermission();
        $tp->generate();
        $tm = new TowerMenu();
        $tm->generate();
        
        $this->store($stars);
    }
    
    /**
     * 将应用包的信息存入缓存
     * 
     * @param array $stars
     */
    protected function store($stars)
    {
        $this->cache->forever($this->getCacheKey(), $stars);
    }
    
    /**
     * 从缓存中获取应用包的信息
     * 
     * @return array
     */
    protected function fetch()
    {
        return $this->cache->fetch($this->getCacheKey());
    }
    
    /**
     * 设置店铺的guid
     * 
     * @param string $towerGuid
     */
    public function setTowerGuid($towerGuid)
    {
        $this->towerGuid = $towerGuid;
    }
    
    /**
     * 获取缓存的key值
     * 
     * @return string
     */
    protected function getCacheKey()
    {
        $key = 'tower.cache.atlas.' . $this->towerGuid;
        return $key;
    }
}