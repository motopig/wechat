<?php namespace Ecdo\Model\Tower;

use Ecdo\Universe\TowerModel;

/**
 * 店铺用户角色模型类
 * 
 * @package Ecdo\Model\Tower
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class TowerAngelRole extends TowerModel
{
    protected $table = 'tower_angel_role';
    protected $guarded = [];
    protected $primaryKey = 'angel_id';
    
    /**
     * 获取店铺人员
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function angel()
    {
        return $this->hasOne('Angel', 'id', 'angel_id');
    }
}
