<?php namespace Ecdo\Tower;

use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoSpiderMan\AngelTowerGrade;
use Ecdo\Model\Tower\TowerAngelRole;
use Ecdo\Model\Tower\TowerRole;
/**
 * 店铺权限管理类
 * 
 * @package Ecdo\Tower
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class TowerPermissionManager
{
    
    /**
     * 获取店铺权限明细列表
     * 
     * @return array
     */
    public function getTowerPermission()
    {
        $tp = new TowerPermission();
        $tmp = $tp->fetchGroup();
        $perms = [];
        foreach ($tmp['group'] as $key => $row) {
            if (! empty($tmp['permission'][$key])) {
                $row['perms'] = $tmp['permission'][$key];
                $perms[$key] = $row;
            }
        }
        
        return $perms;
    }
    
    /**
     * 获取当前用户权限
     * 
     * @return array
     */
    public function getCurUserPermission()
    {
        // 获取用户所有角色
        $user = TowerUtils::getCurTowerUser();
        $userId = $user->id;
        
        // 根据角色获取所有权限ID
        $userPerms = [];
        $roleIds = TowerAngelRole::where('angel_id', '=', $userId)->first(['role_ids']);
        if (! empty($roleIds)) {
            // 获取角色所有权限ID
            $perms = TowerRole::whereIn('id', explode(',', $roleIds->role_ids))->get(['permissions'])->toArray();
            foreach ($perms as $row) {
                $userPerms = array_merge($userPerms, explode(',', $row['permissions']));
            }
            
            // 取唯一权限
            $userPerms = array_unique($userPerms);
        }
        
        return $userPerms; 
    }
    
    /**
     * 检查用户拥有指定权限
     * 
     * @param string $permId
     * @return boolean
     */
    public function chkUserOwnPerm($permId)
    {
        // 获取店铺所有权限列表
        $tp = new TowerPermission();
        $towerPerms = $tp->fetchTree();
        
        // 如果店铺没有该权限，则返回无权
        if (empty($towerPerms[$permId])) {
            return true;
        }
        
        // root用户则所有权限
        if (TowerUtils::fetchCurTowerUserRoot()) {
            return true;
        }
        
        // 获取用户在店铺中的权限
        $userPerms = TowerUtils::fetchCurTowerUserPerm();
        
        // 判断拥有权限
        if (! empty($userPerms) && in_array($permId, $userPerms)) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 根据路由检查用户是否拥有权限
     * 
     * @param string $path
     * @return boolean
     */
    public function chkUserOwnPermByPath($path)
    {
        // 根据路由获取权限id
        $permId = $this->getPermIdByPath($path);
        
        // 根据权限id进行检查
        if ($permId) {
            return $this->chkUserOwnPerm($permId);
        } else {
            return true;
        }
    }
    
    /**
     * 通过路由获取权限id
     * 
     * @param string $path
     * @return string
     */
    public function getPermIdByPath($path)
    {
        // 获取路由对应权限
        $perms = $this->getTowerPathPerms();
        
        // 返回权限id或false
        if (empty($perms[$path])) {
            return false;
        } else {
            return $perms[$path];
        }
    }
    
    /**
     * 将店铺权限组合以路由为键的数组
     * 
     * @return array
     */
    public function getTowerPathPerms()
    {
        $tp = new TowerPermission();
        // 获取权限与路径数组
        $data = $tp->fetchTree();
        
        // 重新组合数组
        $perms = [];
        foreach ((array)$data as $permId => $rows) {
            foreach ((array)$rows as $row) {
                $perms[$row] = $permId;
            }
        }
        
        return $perms;
    }
}
