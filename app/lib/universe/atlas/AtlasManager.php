<?php namespace Ecdo\Universe\Atlas;

use Ecdo\Universe\Atlas\StarNest;
use Ecdo\Model\Universe\UniverseStar;
use Ecdo\Universe\Repository\UniverseStarRepository;

class AtlasManager
{
    /**
     * 获取所有应用包
     *
     * @return array
     */
    public function getAllStars()
    {
        // 检查平台应用包数据表是否存在并创建
        $usr = new UniverseStarRepository('mysql');
        if (! $usr->repositoryExists()) {
            $usr->createRepository();
        }
        
        $sn = new StarNest();
        $tmp = $sn->retrieveStars();
        $dbStars = UniverseStar::all()->toArray();
        $stars = [];
        foreach ((array)$dbStars as $row) {
            $stars[$row['star']] = $row;
        }
    
        $diff = array_diff_key($tmp, $stars);
        foreach ((array)$diff as $row) {
            $stars[$row['star']] = $row;
        }
        
        $fs = app('files');
        foreach ((array)$stars as $key => $star) {
            $imgUrl = asset('atlas/' . $star['star'] . '/images/icon.png');
            if ($fs->exists($imgUrl)) {
                $stars[$key]['icon'] = $imgUrl;
            }
        }
        
        return $stars;
    }
    
    /**
     * 获取指定应用包信息
     * 
     * @param string $star
     * @return array
     */
    public function getStar($star)
    {
        $starInfo = UniverseStar::where('star', $star)->get()->toArray();
        if (empty($starInfo)) {
            $sn = new StarNest();
            $starInfo = $sn->getStarInfo($star);
        } else {
            $starInfo = current($starInfo);
        }
        
        return $starInfo;
    }
    
    /**
     * 更新应用包缓存，用于店铺访问
     */
    public function updateCache()
    {
        $tmp = UniverseStar::all()->toArray();
        $stars = [];
        foreach ((array)$tmp as $row) {
            $stars[$row['star']] = $row;
        }
        
        $sn = new StarNest();
        if (! empty($stars)) {
            $sn->storeGroup($stars);
        }
    }
}