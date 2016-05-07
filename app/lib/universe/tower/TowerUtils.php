<?php
namespace Ecdo\Universe;

use Ecdo\Universe\TowerDB;

/**
 * 店铺工具类
 *
 * @package Ecdo\Universe
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class TowerUtils
{

    /**
     * 文件hash算法，默认md5
     *
     * @var string
     */
    private static $fileHashArgo = 'md5_file';
    
    /**
     * hash算法，默认md5
     * 
     * @var string
     */
    private static $hashArgo = 'md5';

    /**
     * 店铺唯一识别
     *
     * @var string
     */
    private static $towerGuid = '';


    /**
     * 根据传递的字符串生成店铺的guid
     *
     * @param string $str            
     * @return string
     */
    public static function genTowerGuid($str)
    {
        $guid = self::genGuid($str, 8);
        self::storeTowerGuid($guid);
        return $guid;
    }

    /**
     * 根据传递的字符串与指定的长度生成Guid
     *
     * @param string $str            
     * @param number $len            
     * @return string
     */
    public static function genGuid($str = '', $len = 8)
    {
        if (empty($str)) {
            $str = uniqid();
        }
        
        // 生成hash值
        $guid = self::genHash($str);
        if (strlen($guid) < $len) {
            $len = strlen($guid);
        }
        
        $guid = substr($guid, 0, $len);
        return $guid;
    }

    /**
     * 生成指定文件夹下文件的hash值，返回格式:
     * [file => hash]
     *
     * @param string $dir            
     * @return array
     */
    public static function genDirHash($dir)
    {
        // 确保目录路径最后是'/'
        if (substr($dir, -1, 1) !== '/') {
            $dir .= '/';
        }
        
        // 遍历目录中的文件进行hash计算
        $files = glob($dir . '*');
        $hashs = array();
        if (! empty($files)) {
            foreach ($files as $file) {
                $hash = self::genFileHash($file);
                $path = str_replace($dir, '', $file);
                $hashs[$path] = $hash;
            }
        }
        
        return $hashs;
    }

    /**
     * 生成指定文件的hash值
     *
     * @param string $file            
     * @return string
     */
    public static function genFileHash($file)
    {
        if (file_exists($file)) {
            $argo = self::$fileHashArgo;
            return $argo($file);
        } else {
            return false;
        }
    }

    /**
     * 设置文件hash算法，默认md5
     *
     * @param string $argo            
     */
    public static function setFileHashArgo($argo)
    {
        $argo = strtolower($argo);
        if ($argo === 'sha1') {
            self::$fileHashArgo = 'sha1_file';
        } else {
            self::$fileHashArgo = 'md5_file';
        }
    }
    
    /**
     * 根据传递的字符串生成hash值
     * 
     * @param string $str
     */
    public static function genHash($str)
    {
        $argo = self::$hashArgo;
        return $argo($str);
    }
    
    /**
     * 设置hash算法，默认md5
     * 
     * @param string $argo
     */
    public static function setHashArgo($argo)
    {
        $argo = strtolower($argo);
        if ($argo === 'sha1') {
            self::$hashArgo = 'sha1';
        } else {
            self::$hashArgo = 'md5';
        }
    }
    
    /**
     * 存储店铺唯一识别到存储区
     *
     * @param string $towerGuid
     */
    public static function storeTowerGuid($towerGuid)
    {
        self::setTowerGuid($towerGuid);
        \Session::put('tower_guid', $towerGuid);
    }
    
    /**
     * 移除缓存中的店铺唯一识别
     */
    public static function forgetTowerGuid()
    {
        \Session::forget('tower_guid');
        self::setTowerGuid('');
    }
    
    /**
     * 从存储区中获取店铺唯一识别
     *
     * @return string
     */
    public static function fetchTowerGuid()
    {
        $guid = \Session::get('tower_guid');

        $rtn = '';
        if (! empty($guid)) {
            $rtn = $guid;
            self::setTowerGuid($rtn);
        }
    
        return $rtn;
    }
    
    /**
     * 设置店铺唯一识别
     *
     * @param string $towerGuid
     */
    public static function setTowerGuid($towerGuid)
    {
        self::$towerGuid = $towerGuid;
    }
    
    /**
     * 获取店铺唯一识别
     *
     * @return string
     */
    public static function getTowerGuid()
    {
        return self::$towerGuid;
    }
    
    /**
     * 获取店铺id
     * 
     * @return int
     */
    public static function getTowerId()
    {
        TowerDB::useConnUniverse();
        $towerGuid = self::fetchTowerGuid();
        $tower = \App\Models\Tower::where('encrypt_id', $towerGuid)->first(['id']);
        if (empty($tower)) {
            return 0;
        }
        $towerId = $tower['id'];
        
        return $towerId;
    }
    
    /**
     * 生成店铺表名
     *
     * @param string $table
     * @return string
     */
    public static function genTowerTable($table)
    {
        $towerTable = self::getTowerGuid() . '_' . $table;
        return $towerTable;
    }
    
    /**
     * 获取店铺菜单，存入缓存
     */
    public static function storeTowerMenus()
    {
        $tmm = new \Ecdo\Tower\TowerMenuManager();
        $menus = $tmm->getUserMenu();

        \Session::put('tower_menus', $menus);
    }
    
    /**
     * 从缓存中取出店铺菜单
     * 
     * @return array
     */
    public static function fetchTowerMenus()
    {
        $tower_menus = \Session::get('tower_menus');
        if(!$tower_menus){
            self::storeTowerMenus();
            $tower_menus = \Session::get('tower_menus');
        }
        return $tower_menus;
    }
    
    /**
     * 清除缓存中的菜单
     */
    public static function forgetTowerMenus()
    {
        \Session::forget('tower_menus');
    }
    
    /**
     * 获取店铺可用应用存入缓存
     */
    public static function storeTowerStars()
    {
        $stars = \Ecdo\Model\Tower\TowerStar::where('enabled', '=', 'Y')->lists('star');

        \Session::put('tower_stars', $stars);
    }
    
    /**
     * 从缓存中获取店铺可用应用
     * 
     * @return array
     */
    public static function fetchTowerStars()
    {
        return \Session::get('tower_stars');
    }
    
    /**
     * 清除店铺可用应用的缓存
     */
    public static function forgetTowerStars()
    {
        \Session::forget('tower_stars');
    }
    
    /**
     * 获取分页数
     * 
     * @return int
     */
    public static function getPerPage()
    {
        return \Config::get('EcdoSpiderMan::setting')['page'] ?: 10;
    }
    
    /**
     * 获取当前登录店铺管理的用户
     * 
     * @return object
     */
    public static function getCurTowerUser()
    {
        return \Auth::angel()->get();
    }
    
    /**
     * 将当前用户的权限存入session
     */
    public static function storeCurTowerUserPerm()
    {
        // 获取用户id
        $userId = 0;
        $user = self::getCurTowerUser();
        if (! empty($user)) {
            $userId = $user->id;
        }

        // 获取店铺id
        $towerId = self::getTowerId();
        
        // 获取用户在店铺中的级别
        $data = \Ecdo\EcdoSpiderMan\AngelTowerGrade::where('tower_id', '=', $towerId)->where('angel_id', '=', $userId)->first(['grade']);
        if (! empty($data) && $data->grade === 'root') {
            \Session::put('tower_user_root', true);
            \Session::put('tower_perms', '');
        } else {
            \Session::put('tower_user_root', false);
            $tpm = new \Ecdo\Tower\TowerPermissionManager();
            $perms = $tpm->getCurUserPermission();
            \Session::put('tower_perms', $perms);
        }
    }
    
    /**
     * 获取当前店铺用户是否是root
     * 
     * @return boolean
     */
    public static function fetchCurTowerUserRoot()
    {
        return \Session::get('tower_user_root');
    }
    
    /**
     * 获取当前用户的权限
     * 
     * @return array
     */
    public static function fetchCurTowerUserPerm()
    {
        return \Session::get('tower_perms');
    }
    
    /**
     * 清除缓存中的当前用户权限
     */
    public static function forgetCurTowerUserPerm()
    {
        \Session::forget('tower_user_root');
        \Session::forget('tower_perms');
    }

    public static function setTowerConn(){

        $connects = \DB::table('tower')->where('encrypt_id', self::$towerGuid)->pluck('connections');

        \Cache::put( 'Tower_' . self::$towerGuid . '_conn', $connects , 60);
    }

    /**
     * 将店铺用户的登录信息存入缓存
     */
    public static function storeTower()
    {
        self::setTowerConn();
        self::storeTowerStars();
        self::storeCurTowerUserPerm();
        self::storeTowerMenus();
    }
    
    /**
     * 清除用户的登录信息
     */
    public static function forgetTower()
    {
        self::forgetTowerGuid();
        self::forgetTowerMenus();
        self::forgetCurTowerUserPerm();
        self::forgetTowerStars();
    }

    /**
     * 获取云号对应微商城登陆信息
     */
    public static function fetchTowerLoginInfo(){
        return \DB::table('tower')->where('encrypt_id', self::$towerGuid)->pluck('login_b2c');
    }
}
