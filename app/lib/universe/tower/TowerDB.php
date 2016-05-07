<?php
namespace Ecdo\Universe;

/**
 * 店铺数据库工具类
 *
 * @package Ecdo\Universe
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class TowerDB
{

    /**
     * 默认数据库链接名称
     *
     * @var string
     */
    private static $universeConn = 'mysql';

    /**
     * 店铺数据库链接名称
     *
     * @var string
     */
    private static $towerConn = 'tower_conn';

    /**
     * 切换平台数据库链接
     */
    public static function useConnUniverse()
    {
        \Config::set('database.default', self::$universeConn);
    }

    /**
     * 切换店铺数据库链接
     */
    public static function useConnTower()
    {
        self::confTowerConn();
        \Config::set('database.default', self::$towerConn);
    }

    /**
     * 将店铺数据库配置信息添加到数据库配置中
     */
    private static function confTowerConn()
    {
        $towerGuid = TowerUtils::getTowerGuid();

        $connConf = self::getConnTowerConf($towerGuid);

        $defConf = \Config::get('database.connections.' . self::$universeConn);
        $defConf['database'] = $connConf['database'];
        if (! empty($connConf['host']) && $defConf['host'] != $connConf['host']) {
            $defConf['host'] = $connConf['host'];
        }
        
        if (! empty($connConf['username']) && $defConf['username'] != $connConf['username']) {
            $defConf['username'] = $connConf['username'];
        }
        
        if (! empty($connConf['password']) && $defConf['password'] != $connConf['password']) {
            $defConf['password'] = $connConf['password'];
        }

        \Config::set('database.connections.' . self::$towerConn, $defConf);
    }

    /**
     * 调用平台数据库连接
     * 
     * @return \Illuminate\Database\Connection
     */
    public static function dbConnUniverse()
    {
        return \DB::connection(self::$universeConn);
    }

    /**
     * 调用店铺数据库连接
     * 
     * @return \Illuminate\Database\Connection
     */
    public static function dbConnTower()
    {
        self::confTowerConn();
        return \DB::connection(self::$towerConn);
    }

    /**
     * 获取店铺链接配置信息
     *
     * @param string $towerGuid            
     * @throws Exception
     * @return array
     */
    public static function getConnTowerConf($towerGuid)
    {
        $key = 'Tower_' . $towerGuid . '_conn';
        $connConf = unserialize(\Cache::get($key));

        if (empty($connConf) && $towerGuid) {
            self::useConnUniverse();
            $rs = \App\Models\Tower::where('encrypt_id', '=', $towerGuid)->first([
                'connections'
            ]);
            if (empty($rs)) {
                throw new \Exception('店铺数据库配置信息异常');
            }
            $connConf = unserialize($rs['connections']);
        }
        
        return $connConf;
    }
}