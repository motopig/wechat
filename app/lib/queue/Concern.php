<?php

namespace App\Lib;

use Ecdo\EcdoHulk\WechatMemberInfo;
use Illuminate\Support\Facades\DB;
use Ecdo\Universe\TowerUtils;

/**
 * 订阅会员个人信息队列
 *
 * @category yunke
 * @package app\lib\queue
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class Concern
{
	public function send($job, $data)
	{
        $arr = ['type' => 'getUserInfo', 'action' => [], 'parameter' => ['key' => 'getUserInfo', 
        'value' => ['access_token' => '', 'openid' => $data['fromUser']]]];
        $wa = new \App\Wormhole\WechatAction();
        $result = $wa->send($arr);
        
		if ($result['errcode'] == 'success') {
        	if (! $id = WechatMemberInfo::where('wechat_member_id', $data['wechat_member_id'])->pluck('id')) {
            	$wmi = new WechatMemberInfo();
            } else {
            	$wmi = WechatMemberInfo::find($id);
            }

            $wmi->wechat_member_id = $data['wechat_member_id'];
            $wmi->name = ! empty($result['data']['nickname']) ? $result['data']['nickname'] : '';
            $wmi->head = ! empty($result['data']['headimgurl']) ? $result['data']['headimgurl'] : '';
            $wmi->city = ! empty($result['data']['city']) ? $result['data']['city'] : '';
            $wmi->province = ! empty($result['data']['province']) ? $result['data']['province'] : '';
            $wmi->country = ! empty($result['data']['country']) ? $result['data']['country'] : '';
            $wmi->gender = 'unknown';

            $sex = ! empty($result['data']['sex']) ? $result['data']['sex'] : '';
            if (! empty($sex)) {
            	if ($sex == 1) {
            		$wmi->gender = 'male';
            	} else if ($sex == 2) {
            		$wmi->gender = 'female';
            	}
            }

            $wmi->save();
        }

        $job->delete();
	}
}
