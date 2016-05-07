<?php
namespace App\Models;

/**
 * 微信授权公众号模型
 *
 * @category yunke
 * @package app\models\tower
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class TowerWechat extends \Eloquent
{

    protected $table = 'tower_wechat';

    protected $connection = 'mysql';

    protected $guarded = array();
}
