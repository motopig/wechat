<?php
namespace Ecdo\EcdoSpiderMan;

use Ecdo\EcdoSpiderMan\AngelCommon;
use Ecdo\EcdoSpiderMan\AngelAccountUtils;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Ollieread\Multiauth;
use Illuminate\Support\Facades\Auth;
use App\Lib\SendEmail;
use Queue;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoSpiderMan\AngelValidator;

/**
 * 商家登入验证
 *
 * @category yunke
 * @package atlas\hell\spider-man\src\controllers\account
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class AngelAccount extends AngelCommon
{
    // 不检查权限方法
    protected $whitelist = array(
        'getRegister',
        'posttRegister',
        'codeValidator',
        'getMailSent',
        'getEmailValidator',
        'postEmailValidator',
        'getResetPwd',
        'postResetPwd',
        'getResetPwdSet',
        'postResetPwdSet',
        'getLogin',
        'postLogin'
    );

    public function __construct()
    {
        parent::__construct();
        $this->aau = new AngelAccountUtils();
        
        $this->sideMenu(array('m_account','m_order'));
    }
    
    // 商家注册
    public function getRegister()
    {
        if(Auth::angel()->check()){
            return Redirect::to('angel');
        }
        $metas = array();
        $metas = array(
            'title' => '用户注册 | 一点云客 | 移动智能营销平台',
            'keyword'=>'云客,微信摇一摇,智能硬件,微信POS,微信wifi,微信路由器',
            'description'=>'一点云客提供基于微信的移动智能营销服务，包括摇一摇，微信wifi，微信签到，微信调查等多种营销服务。'
        );
        
        return View::make('EcdoSpiderMan::account/register')->with(compact('metas'));
    }

    // 商家注册获取验证码
    public function codeValidator()
    {
        if (\Session::get('identifying_code')) {
            \Session::forget('identifying_code');
        }

        $aac = new \Ecdo\EcdoSpiderMan\AngelAccountCode();
        $aac->doimg();

        \Session::put('identifying_code', $aac->getCode());
    }

    // 商家注册处理
    public function postRegister()
    {
        // 表单验证规则
        $rules = array(
            'email' => 'Required|Email|unique:angel,email',
            'code' => 'Required|min:4'
        );
        
        $nice_names = array(
            'email'=>'邮箱',
            'code'=>'验证码'
        );
        
        // 验证表单信息
        $validator = Validator::make(Input::all(), $rules);
        $validator->setAttributeNames($nice_names); 
        
        // 验证不通过
        if (! $validator->passes()) {
            return Redirect::to('angel/register')->withInput(Input::all())->withErrors($validator->getMessageBag());
        }

        // 不区分大小写判断验证码输入是否正确
        if (! \Session::get('identifying_code') || trim(Input::get('code')) != \Session::get('identifying_code')) {
            return Redirect::to('angel/register')->withInput(Input::all())->withErrors(array('code'=>'验证码输入错误'));
        }
        
        //验证是否已经提交申请
        \Session::forget('identifying_code');
        $exist_email = AngelValidator::where('email',Input::get('email'))->orderBy('id','desc')->first(['email','created_at','encrypt_id']);
        $update = 1;
        if(!empty($exist_email)){
            //超过24小时就需要重新授权
            $created_at_time = strtotime($exist_email->created_at);
            if(((time()-$created_at_time)/3600)>24){
                //删除validator
                AngelValidator::where('email',Input::get('email'))->delete();
                $update = 2;
            }else{
                //更新时间
                $updated_at = date('Y-m-d H:i:s',time());
                $encrypt_id = $exist_email->encrypt_id;
                $update = 0;
            }
        }
        
        if($update>0){
            if (! $encrypt_id = $this->aau->createAngelValidator(Input::get('email'),Input::get('refer'))) {
                return Redirect::to('angel/register')->withInput(Input::all())->with('error', '账户注册失败');
            }
        }else{
            /**
            if (! $encrypt_id = $this->aau->createAngelValidator(Input::get('email'),Input::get('refer'),$updated_at)) {
                return Redirect::to('angel/register')->withInput(Input::all())->with('error', '账户注册失败');
            }
            */
        }
        
        // 发送邮件执行
        Queue::push('App\Lib\SendEmail@send', [
            'type' => 'register',
            'encrypt_id' => $encrypt_id,
            'to_email' => Input::get('email')
        ]);

        echo $this->getMailsent(Input::get('email'), $encrypt_id);
    }

    // // 随机验证码
    // public function getCodeStr() 
    // { 
    //     $len = 5; // 五位数
    //     $chars_array = array( 
    //         '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 
    //         'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 
    //         'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 
    //         'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 
    //         'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 
    //         'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 
    //         'Y', 'Z'
    //     );

    //     $charsLen = count($chars_array) - 1; 
    //     $outputstr = ''; 
    //     for ($i = 0; $i < $len; $i++) { 
    //         $outputstr .= $chars_array[mt_rand(0, $charsLen)]; 
    //     }

    //     return $outputstr; 
    // }

    // 商家注册邮件验证激活
    public function getMailSent($email = '', $encrypt_id = '')
    {
        $uri = '';
        if (! empty($email)) {
            $str = explode('@', $email);
            $uri = 'mail.' . $str[1];
            return View::make('EcdoSpiderMan::account/mailsent')->with(compact('uri', 'email', 'encrypt_id'));
        }
    }
    
    public function getMailResent(){
        //#TODO:重新发送邮件
    }

    // 注册邮件验证创建账户
    public function getEmailValidator()
    {
        // 查询注册邮件验证是否有效
        $res = $this->aau->createAngelValidatorStatus(Input::get('sign'));
        if ($res['suc'] == 'success') {
            $sign = Input::get('sign');
            $email = $res['email'];
            $refer = $res['refer'];

            return View::make('EcdoSpiderMan::account/validator')->with(compact('sign', 'email','refer'));
        } else {
            return Redirect::to('angel/register')->with('error', $res['msg']);
        }
    }

    // 注册邮件验证创建账户处理
    public function postEmailValidator()
    {
        // 表单验证规则
        $rules = array(
            'password' => 'Required|min:4'
        );
        $nice_names = array(
            'password'=>'密码'
        );
        // 验证表单信息
        $validator = Validator::make(Input::all(), $rules);
        $validator->setAttributeNames($nice_names); 
        
        // 验证不通过
        if (! $validator->passes()) {
            return Redirect::to('angel/email_validator?sign='.Input::get('sign'))->withInput(Input::all())->withErrors($validator->getMessageBag());
        }

        if ($id = $this->aau->createAngelRoot(Input::all())) {
            // 注册邮箱后手动登录
            Auth::angel()->login(Angel::find($id));

            return Redirect::to('angel')->with('success', '注册成功');
        } else {
            return Redirect::to('angel/register')->with('error', '注册失败');
        }
    }

    // 忘记密码
    public function getResetPwd()
    {
        return View::make('EcdoSpiderMan::account/resetpwd');
    }

    // 忘记密码处理
    public function postResetPwd()
    {
        // 表单验证规则
        $rules = array(
            'email' => 'Required|Email',
            'code' => 'Required|min:4'
        );
        
        // 验证表单信息
        $validator = Validator::make(Input::all(), $rules);
        
        // 验证不通过
        if (! $validator->passes()) {
            return Redirect::to('angel/resetpwd')->withInput(Input::all())->withErrors($validator->getMessageBag());
        }
        
        // 不区分大小写判断验证码输入是否正确
        if (! \Session::get('identifying_code') || trim(Input::get('code')) != \Session::get('identifying_code')) {
            return Redirect::to('angel/resetpwd')->with('error', '验证码输入错误!');
        }

        // 根据邮箱查询账号是否存在
        \Session::forget('identifying_code');
        if (! $encrypt_id = $this->aau->getEmailByEncryptId(Input::get('email'))) {
            return Redirect::to('angel/resetpwd')->with('error', '邮箱账号不存在!');
        }

        // 发送邮件执行
        Queue::push('App\Lib\SendEmail@send', [
            'type' => 'reset',
            'encrypt_id' => $encrypt_id,
            'to_email' => Input::get('email')
        ]);

        return Redirect::to('angel/login')->with('success', '密码修改验证邮件已发送, 请注意查收!');
    }

    // 忘记密码创建新密码
    public function getResetPwdSet()
    {
        // 根据ID查询账号是否存在
        $encrypt_id = Input::get('sign');
        if (! $email = $this->aau->getEncryptIdByEmail($encrypt_id)) {
            return Redirect::to('angel/login')->with('error', '邮箱不存在或验证邮件已失效!');
        }

        return View::make('EcdoSpiderMan::account/resetpwdset')->with(compact('email', 'encrypt_id'));
    }

    // 忘记密码创建新密码处理
    public function postResetPwdSet()
    {
        // 表单验证规则
        $rules = array(
            'password' => 'Required|min:4'
        );
        
        // 验证表单信息
        $validator = Validator::make(Input::all(), $rules);
        
        // 验证不通过
        if (! $validator->passes()) {
            return Redirect::to('angel/resetpwdset?sign='.Input::get('sign'))->withInput(Input::all())->withErrors($validator->getMessageBag());
        }

        // 根据ID查询账号是否存在
        $encrypt_id = Input::get('sign');
        if (! $email = $this->aau->getEncryptIdByEmail($encrypt_id)) {
            return Redirect::to('angel/login')->with('error', '邮箱不存在或验证邮件已失效!');
        }

        // 修改商家密码
        if ($this->aau->editAngelPwd($email, Input::get('password'), $encrypt_id)) {
            return Redirect::to('angel/login')->with('success', '修改密码成功!');
        } else {
            return Redirect::to('angel/login')->with('error', '修改密码失败!');
        }
    }

    // 商家登录
    public function getLogin()
    {
        if(Auth::angel()->check()){
            return Redirect::to('angel');
        }
        
        $metas = array();
        $metas = array(
            'title' => '用户登录 | 一点云客 | 移动智能营销平台',
            'keyword'=>'云客,微信摇一摇,智能硬件,微信POS,微信wifi,微信路由器',
            'description'=>'一点云客提供基于微信的移动智能营销服务，包括摇一摇，微信wifi，微信签到，微信调查等多种营销服务。'
        );
        return View::make('EcdoSpiderMan::account/login')->with(compact('metas'));
    }
    
    // 商家登录处理
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
            return Redirect::to('angel/login')->withInput(Input::all())->withErrors($validator->getMessageBag());
        }
        
        if ($this->aau->getOneByEmailPass(Input::get('email'), Input::get('password'))) {
            return Redirect::to('angel')->with('success', '登录成功!');
        } else {
            return Redirect::to('angel/login')->withInput(Input::all())->with('error', '登录失败,帐号或密码无效');
        }
    }
    
    // 商家登出处理
    public function getLogout()
    {
        // 清除登录信息
        Auth::angel()->logout();
        TowerUtils::forgetTower();
        
        return Redirect::to('angel/login')->with('success', '退出成功!');
    }

    // 个人资料修改
    public function upAccount()
    {
        $angel_info = $this->aau->getAngelInfo(Auth::angel()->get()->id);
        
        return View::make('EcdoSpiderMan::account/up_account')->with(compact('angel_info'));
    }
    
    public function editAccount(){
        $angel_info = $this->aau->getAngelInfo(Auth::angel()->get()->id);
        return View::make('EcdoSpiderMan::account/edit_account')->with(compact('angel_info'));
    }
    
    // 个人资料修改处理
    public function saveAccount(){
        // 表单验证规则
        if (Input::get('password')) {
            $this->response_rules['password'] = 'Required|min:6';
        }

        // validator-ajax表单验证
        $this->ajaxValidator(Input::all());

        // validator-ajax验证通过
        if ($this->response_type == 'success') {
            // 修改个人信息
            if (! $this->aau->updateAccount(Input::all(), Auth::angel()->get()->id)) {
                return Redirect::to('angel/account/edit')->with('error','修改资料失败,请稍后再试');
            }
        }
        
        return Redirect::to('angel/account/edit')->with('success','资料修改成功');
    }

    // 个人资料修改处理
    public function upAccountDis(){
        // 表单验证规则
        if (Input::get('password')) {
            $this->response_rules['password'] = 'Required|min:4';
        }

        // validator-ajax表单验证
        $this->ajaxValidator(Input::all());

        // validator-ajax验证通过
        if ($this->response_type == 'success') {
            // 修改个人信息
            if (! $this->aau->updateAccount(Input::all(), Auth::angel()->get()->id)) {
                $this->response_type = 'error';
                $this->response_msg = '修改个人信息失败!';
            }
        }
        
        // ajax返回请求
        $this->end('\Ecdo\EcdoSpiderMan\AngelDashboard@dashboard');
    }

    // 个人资料模态框头像上传
    public function accountUpload(){
        $arr = [];
        $res = $this->fileValidator(Input::file('file'), 'head');

        if ($res['errcode'] == 'error') {
            $arr['response_type'] = $res['errcode'];
            $arr['response_msg'] = $res['msg'];
        } else {
            $this->aau->updateAccountHead($res['file'], Auth::angel()->get()->id);
        }

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }
}
