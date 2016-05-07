<?php
/**
* 会员资料管理
* description
* package atlas/hell/member/src/controllers/info/MemberInfo.php
* date 2015-05-28 16:43:12
* author Hello <hello@no>
* @copyright ECDO. All Rights Reserved.
*/
 

namespace Ecdo\EcdoMember;

use Ecdo\EcdoMember\MemberCommon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class MemberInfo extends MemberCommon
{
    public function __construct()
    {
        parent::__construct();
    }
    
    // 会员信息首页
    public function index()
    {
        return View::make('EcdoMember::info/index');
    }
    
    public function create()
    {
        return View::make('Member::info/index');
    }
    
    public function edit()
    {
        return View::make('Member::info/index');
    }
    
    public function profile()
    {
        return View::make('Member::info/index');
    }
    
    public function setting(){
        
    }
    
    public function extend(){
        
    }
    
    public function merge(){
        
    }
}
