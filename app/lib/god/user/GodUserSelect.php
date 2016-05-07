<?php
namespace App\Lib;

use App\Models\God;
use Ollieread\Multiauth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * 平台用户数据获取类
 *
 * @category yunke
 * @package app\lib\god\user
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class GodUserSelect
{
    // 根据邮箱和密码验证用户登入有效性
    public function getOneByEmailPass($email, $password)
    {
        if (Auth::god()->attempt(array(
            'email' => $email,
            'password' => $password
        ))) {
            return God::where('email', $email)->where('disabled', 'false')->pluck('id');
        }
    }
}
