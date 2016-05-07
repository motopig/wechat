<?php namespace Ecdo\Tower;

use App\Controllers\BaseController;
use Ecdo\Model\Tower\TowerRole;
use Ecdo\Universe\TowerUtils;

/**
 * 店铺角色控制器
 * 
 * @package Ecdo\Tower
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class RoleController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->sideMenu(array('m_shop','m_shop_file','m_shop_auth'));
    }
    
    /**
     * 列表页面
     * 
     * @return string
     */
    public function index()
    {
        // 分布
        $page['perPage'] = TowerUtils::getPerPage();
        $roles = TowerRole::paginate($page['perPage']);
        $page['curPage'] = $roles ? $roles->getCurrentPage() : 0;
        $page['ttlPage'] = $roles ? $roles->getLastPage() : 0;
        $page['links'] = $roles ? $roles->links() : '';
        
        $roles = $roles ? $roles->toArray()['data'] : [];
        
        // 根据路由判断拥有权限的按钮
        $tpm = new TowerPermissionManager();
        $chkPerm['add'] = $tpm->chkUserOwnPermByPath('angel/role/add');
        $chkPerm['edit'] = $tpm->chkUserOwnPermByPath('angel/role/edit');
        $chkPerm['del'] = $tpm->chkUserOwnPermByPath('angel/role/del');
        
        return \View::make('tower.role.index', compact('roles', 'page', 'chkPerm'));
    }
    
    /**
     * 创建角色页面
     * 
     * @return string
     */
    public function add()
    {
        $tpm = new TowerPermissionManager();
        $perms = $tpm->getTowerPermission();
        $i = 0;
        
        return \View::make('tower.role.add', compact('perms', 'i'));
    }
    
    /**
     * 创建角色，ajax方式
     * 
     * @return string
     */
    public function doAdd()
    {
        $role['title'] = \Input::get('title');
        
        // 检验权限存在
        if (!(\Input::get('perms'))) {
            $msg = '至少选择一项权限';
            return $msg;
        }
        
        $role['permissions'] = implode(',', \Input::get('perms'));
        $role['desc'] = \Input::get('desc');
        
        // 获取表名
        $table = TowerRole::getModel()->getTable();
        
        // 检验角色名称
        $msg = [
            'required' => '角色名称必须输入',
            'unique' => '角色名称已重复'
        ];
        $validator = \Validator::make(['title' => $role['title']], ['title' => 'required|unique:' . $table], $msg);
        if ($validator->fails()) {
            $msg = $validator->messages()->toArray();
            return current($msg['title']);
        }
        
        // 保存角色
        $objRole = new TowerRole($role);
        $rs = $objRole->save();
        
        if ($rs) {
            return 'success';
        } else {
            return 'failed';
        }
    }
    
    /**
     * 查看角色明细
     * 
     * @return string
     */
    public function detail()
    {
        $id = \Input::get('rid');
        $tpm = new TowerPermissionManager();
        $allPerms = $tpm->getTowerPermission();
        $role = TowerRole::find($id, ['id', 'title', 'permissions', 'desc'])->toArray();
        $tmpPerms = explode(',', $role['permissions']);
        
        // 设置已选权限
        $perms = [];
        foreach ($allPerms as $gid => $rows) {
            foreach ($rows['perms'] as $pid => $row) {
                if (in_array($row['id'], $tmpPerms)) {
                    if (empty($perms[$gid])) {
                        $perms[$gid] = $rows;
                        $perms[$gid]['perms'] = [];
                    }
                    
                    $perms[$gid]['perms'][$pid] = $row;
                }
            }
        }
        
        $i = 0;
        
        return \View::make('tower.role.detail', compact('role', 'perms', 'i'));
    }
    
    /**
     * 显示编辑角色
     * 
     * @return string
     */
    public function edit()
    {
        $id = \Input::get('rid');
        $tpm = new TowerPermissionManager();
        $perms = $tpm->getTowerPermission();
        $role = TowerRole::find($id, ['id', 'title', 'permissions', 'desc'])->toArray();
        $tmpPerms = explode(',', $role['permissions']);
        
        // 设置已选权限
        foreach ($perms as $gid => $rows) {
            foreach ($rows['perms'] as $pid => $row) {
                if (in_array($row['id'], $tmpPerms)) {
                    $perms[$gid]['perms'][$pid]['checked'] = true;
                }
            }
        }
        
        $i = 0;
        
        return \View::make('tower.role.edit', compact('role', 'perms', 'i'));
    }
    
    /**
     * 编辑角色，ajax方式
     * 
     * @return string
     */
    public function doEdit()
    {
        $id = \Input::get('rid');
        
        // id必要
        if (empty($id)) {
            return '数据非法';
        }
        
        // 检验角色存在
        $role = TowerRole::find($id);
        if (empty($role)) {
            return '角色不存在';
        }
        
        $role->title = \Input::get('title');
        
        // 检验权限存在
        if (!(\Input::get('perms'))) {
            $msg = '至少选择一项权限';
            return $msg;
        }
        
        $role->permissions = implode(',', \Input::get('perms'));
        $role->desc = \Input::get('desc');
        
        // 获取表名
        $table = TowerRole::getModel()->getTable();
        
        // 检验角色名称
        $msg = [
            'required' => '角色名称必须输入',
            'unique' => '角色名称已重复'
        ];
        $validator = \Validator::make(['title' => $role->title], ['title' => 'required|unique:' . $table . ',title,' . $id], $msg);
        if ($validator->fails()) {
            $msg = $validator->messages()->toArray();
            return current($msg['title']);
        }
        
        // 保存角色
        $rs = $role->save();
        
        if ($rs) {
            return 'success';
        } else {
            return 'failed';
        }
    }
    
    /**
     * 删除角色，ajax方式
     * 成功输出success，失败输出failed
     * 
     * @return string
     */
    public function del()
    {
        $id = \Input::get('rid');
        if (empty($id)) {
            return 'error';
        }
        
        $rs = TowerRole::destroy($id);
        if ($rs) {
            return 'success';
        } else {
            return 'failed';
        }
    }
}
