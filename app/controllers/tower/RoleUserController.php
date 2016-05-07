<?php namespace Ecdo\Tower;

use App\Controllers\BaseController;
use Ecdo\Model\Tower\TowerAngelRole;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoSpiderMan\AngelTowerGrade;
use Ecdo\EcdoSpiderMan\Angel;
use Ecdo\EcdoSpiderMan\AngelInfo;
use Ecdo\Model\Tower\TowerRole;
use Ecdo\Model\Tower\Ecdo\Model\Tower;

/**
 * 店铺用户角色关系控制器类
 * 
 * @package package_name
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class RoleUserController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->sideMenu(array('m_shop','m_shop_file','m_shop_auth'));
    }
    
    /**
     * 用户角色关系列表
     * 
     * @return string
     */
    public function index()
    {
        $towerId = TowerUtils::getTowerId();
        $curUser = TowerUtils::getCurTowerUser();
        $page['perPage'] = TowerUtils::getPerPage();
        // 获取普通管理员
        $tmpUserIds = AngelTowerGrade::where('tower_id', '=', $towerId)->where('grade', '=', 'admin')->get(['angel_id'])->toArray();
        $userIds = [];
        foreach ((array)$tmpUserIds as $row) {
            if ($row['angel_id'] !== $curUser->id) {
                $userIds[] = $row['angel_id'];
            }
        }
        
        $roleUsers = [];
        $users = [];
        if (! empty($userIds)) {
            // 获取账号
            $roleUsers = Angel::whereIn('id', $userIds)->paginate($page['perPage'], ['id', 'email']);
            $tmp = $roleUsers->toArray()['data'];
            $users = [];
            foreach ((array)$tmp as $row) {
                $users[$row['id']] = $row;
            }
            
            // 获取昵称
            $tmp = AngelInfo::whereIn('angel_id', $userIds)->get(['angel_id', 'name'])->toArray();
            foreach ((array)$tmp as $row) {
                $users[$row['angel_id']]['name'] = $row['name'];
            }
            
            // 获取角色
            $tmp = TowerRole::get(['id', 'title'])->toArray();
            $roles = [];
            foreach ((array)$tmp as $row) {
                $roles[$row['id']] = $row['title'];
            }
            
            // 获取用户角色
            $tmp = TowerAngelRole::whereIn('angel_id', $userIds)->get(['angel_id', 'role_ids', 'updated_at'])->toArray();
            foreach((array)$tmp as $row) {
                $roleIds = explode(',', $row['role_ids']);
                $tmpRoles = [];
                foreach ($roleIds as $key => $val) {
                    if (! empty($roles[$val])) {
                        $tmpRoles[] = $roles[$val];
                    }
                }
                
                $users[$row['angel_id']]['roles'] = implode(',', $tmpRoles);
                $users[$row['angel_id']]['updated_at'] = $row['updated_at'];
            }
        }
        
        $page['curPage'] = $roleUsers ? $roleUsers->getCurrentPage() : 0;
        $page['ttlPage'] = $roleUsers ? $roleUsers->getLastPage() : 0;
        $page['links'] = $roleUsers ? $roleUsers->links() : '';
        
        // 根据路由判断拥有权限的按钮
        $tpm = new TowerPermissionManager();
        $chkPerm['add'] = $tpm->chkUserOwnPermByPath('angel/role/add');
        $chkPerm['edit'] = $tpm->chkUserOwnPermByPath('angel/role/edit');
        $chkPerm['del'] = $tpm->chkUserOwnPermByPath('angel/role/del');
        
        return \View::make('tower.role.user.index', compact('users', 'page','chkPerm'));
    }
    
    /**
     * 用户角色关系编辑页面
     * ajax方式
     * 
     * @return string
     */
    public function edit()
    {
        $id = \Input::get('id');

        $user['id'] = $id;
        // 获取用户账号
        $tmp = Angel::where('id', '=', $id)->first(['id', 'email']);
        $user['email'] = $tmp->email;
        
        // 获取用户昵称
//         $tmp = AngelInfo::where('angel_id', '=', $id)->first(['angel_id', 'name']);
//         $user['name'] = $tmp ? $tmp->name : '';
        
        // 获取角色
        $tmp = TowerRole::get(['id', 'title', 'desc'])->toArray();
        $roles = [];
        foreach ((array)$tmp as $row) {
            $roles[$row['id']] = $row;
        }
        
        // 获取用户角色
        $tmp = TowerAngelRole::where('angel_id', $id)->first(['angel_id', 'role_ids']);
        if (! empty($tmp)) {
            $roleIds = explode(',', $tmp->role_ids);
            
            foreach ($roleIds as $row) {
                if (! empty($roles[$row])) {
                    $roles[$row]['checked'] = true;
                }
            }
        }
        
        return \View::make('tower.role.user.edit', compact('user', 'roles'));
    }
    
    /**
     * 用户角色编辑处理
     * ajax方式
     * 返回success 成功，error 错误, failed 失败
     * 
     * @return string
     */
    public function doEdit()
    {
        $uid = \Input::get('uid');
        // 检查非当前用户
        $curUser = TowerUtils::getCurTowerUser();
        if ($uid === $curUser->id) {
            return 'error';
        }
        
        // 检查用户是否属于当前店铺
        $towerId = TowerUtils::getTowerId();
        $tmp = AngelTowerGrade::where('angel_id', '=', $uid)->where('tower_id', '=', $towerId)->where('grade', '=', 'admin')->first(['id']);
        if (empty($tmp)) {
            return 'error';
        }
        
        // 保存用户角色
        $roleIds = \Input::get('roles');
        $roleIds = implode(',', $roleIds);
        $userRoles = TowerAngelRole::find($uid);
        if (empty($userRoles)) {
            $userRoles = new TowerAngelRole(['angel_id' => $uid, 'role_ids' => $roleIds]);
        } else {
            $userRoles->role_ids = $roleIds;
        }
        
        $rs = $userRoles->save();
        if ($rs) {
            return 'success';
        } else {
            return 'failed';
        }
    }
}