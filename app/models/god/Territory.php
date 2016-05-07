<?php
namespace App\Models;

/**
 * 企业模型
 *
 * @category yunke
 * @package app\models\angel
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class Territory extends \Eloquent
{

    protected $table = 'territory';

    protected $guarded = array();

    /**
     * 企业与店铺关系
     *
     * @return hasMany
     */
    public function tower()
    {
        return $this->hasMany('Ecdo\EcdoSpiderMan\Tower', 'territory_id');
    }
}
