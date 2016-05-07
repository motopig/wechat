<?php namespace Ecdo\God;

use Ecdo\Universe\BasePermission;

/**
 * 平台权限类
 * 
 * @package Ecdo\God
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class GodPermission extends BasePermission
{
    
    /**
     * 权限组缓存键
     *
     * @var string
     */
    protected $groupKey = 'god.cache.permission.group';
    
    /**
     * 权限树缓存键
     *
     * @var string
     */
    protected $treeKey = 'god.cache.permission.tree';
    
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
        $this->setPath(app_path() . '/routes');
    }
}
