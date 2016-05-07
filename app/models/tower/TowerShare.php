<?php
namespace App\Models;

/**
 * 平台公用配置模型
 *
 * @category yunke
 * @package app\models\tower
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class TowerShare extends \Eloquent
{

    protected $table = 'tower_share';

    protected $connection = 'mysql';

    protected $guarded = array();
}
