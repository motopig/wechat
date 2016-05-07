<?php
namespace App\Controllers;

use App\Lib\GodUserSelect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Ollieread\Multiauth;
use Illuminate\Support\Facades\Auth;

/**
 * 平台登入验证
 *
 * @category yunke
 * @package app\controllers\god
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class GodAccountController extends GodController
{
    // 不检查权限方法
    protected $whitelist = array(
        'getLogin',
        'postLogin'
    );

    public function __construct()
    {
        parent::__construct();
        
        $this->gus = new GodUserSelect();
    }
    
    // 登录
    public function getLogin()
    {
        return View::make('god/account/login');
    }
    
    // 登录处理
    public function postLogin()
    {
        // 表单验证规则
        $rules = array(
            'email' => 'Required|Email',
            'password' => 'Required'
        );
        
        // 验证表单信息
        $validator = Validator::make(Input::all(), $rules);
        
        // 验证不通过
        if (! $validator->passes()) {
            return Redirect::to('god/login')->withInput(Input::all())->withErrors($validator->getMessageBag());
        }
        
        if ($this->gus->getOneByEmailPass(Input::get('email'), Input::get('password'))) {
            return Redirect::to('god')->with('success', '登录成功!');
        } else {
            return Redirect::to('god/login')->with('error', '登录失败, 邮箱或密码无效!');
        }
    }
    
    // 登出处理
    public function getLogout()
    {
        // 清除登录信息
        Auth::god()->logout();
        
        return Redirect::to('god/login')->with('success', '退出成功!');
    }
}
