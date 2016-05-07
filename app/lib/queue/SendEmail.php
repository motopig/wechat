<?php
namespace App\Lib;

use Illuminate\Support\Facades\Mail;

/**
 * 邮件队列
 *
 * @category yunke
 * @package app\lib\queue
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class SendEmail
{
	public function send($job, $data)
	{
		if ($data['type'] == 'reset') {
            $url = action('\Ecdo\EcdoSpiderMan\AngelAccount@getResetPwdSet') . '?sign=' . $data['encrypt_id'];
            $title = '【一点云客】商户密码更改 请确认完成修改';
            $view = 'EcdoSpiderMan::account.reset_email';
        } elseif($data['type'] == 'register') {
            $url = action('\Ecdo\EcdoSpiderMan\AngelAccount@getEmailValidator') . '?sign=' . $data['encrypt_id'];
            $title = '【一点云客】邮件注册商户帐号 请确认并完成注册';
            $view = 'EcdoSpiderMan::account.register_email';
        }

        $d = array(
            'email' => $data['to_email'], // 商家账号
            'url' => $url  // 验证地址
        );

        $u = array(
            'from_email' => 'no-reply@yunke.im', // 发件人邮箱
            'to_email' => trim($data['to_email']), // 收信人邮箱
            'title' => $title // 标题
        );

        Mail::send($view, $d, function ($m) use ($u) {
            $m->from($u['from_email']);
            $m->to($u['to_email']);
            $m->subject($u['title']);
        });

        $job->delete();
	}
}
