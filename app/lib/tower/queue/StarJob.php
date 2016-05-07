<?php namespace Ecdo\Tower\Queue;

use Ecdo\Universe\Atlas\StarNest;
use Ecdo\Tower\Atlas\TowerStarManager;
use Ecdo\Tower\Migration\TowerMigrationManager;
use Ecdo\Universe\TowerUtils;

/**
 * 应用包相关队列任务
 * 
 * @package Ecdo\Tower\Queue
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class StarJob
{
    /**
     * 安装基础应用包方法
     * 
     * @param object $job
     * @param string $towerGuid
     */
    public function instBase($job, $towerGuid)
    {
        $sn = new StarNest();
        $baseStars = $sn->getBaseStars();
        $tsm = new TowerStarManager($towerGuid);

        //设置店铺GUID
        TowerUtils::storeTowerGuid($towerGuid);
        //设置店铺数据库连接cache
        TowerUtils::setTowerConn();

        foreach ((array)$baseStars as $star) {
            $tsm->installStar($star['star']);
        }

        $id = \App\Models\Tower::where('encrypt_id', $towerGuid)->pluck('id');
        $tinfo = \App\Models\Tower::find($id);
        $tinfo->disabled = 'false';
        $tinfo->save();

        $job->delete();
    }
    
    /**
     * 生成应用的数据表
     * 
     * @param object $job
     * @param array $data
     */
    public function starMigrate($job, $data)
    {
        $towerGuid = $data['towerGuid'];
        $star = $data['star'];
        $tmm = new TowerMigrationManager($towerGuid);
        $tmm->migrateStar($star);
        
        $job->delete();
    }
}