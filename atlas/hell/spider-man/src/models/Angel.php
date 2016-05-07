<?php
namespace Ecdo\EcdoSpiderMan;

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

/**
 * 商家账户
 * 
 * @category yunke
 * @package atlas\hell\spider-man\src\models
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

class Angel extends \Eloquent implements UserInterface, RemindableInterface
{
    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'angel';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'password',
        'remember_token'
    );
}
