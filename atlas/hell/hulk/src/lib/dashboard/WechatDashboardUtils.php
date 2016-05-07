<?php
namespace Ecdo\EcdoHulk;

use Ecdo\EcdoHulk\Wechat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoHulk\WechatMember;

/**
 * 商家用户数据获取类
 * 
 * @category yunke
 * @package atlas\hell\hulk\src\lib\dashboard
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatDashboardUtils
{
	// 判断是否配置微信
    public static function getWechatCount()
    {
        $tower = TowerUtils::fetchTowerGuid();
        $dt[0] = Wechat::count();
        $dt[1] = \App\Models\TowerWechat::where('guid', $tower)->pluck('id');

        return $dt;
    }

    // 获取微信配置信息
    public function getWechat()
    {
        $tower = TowerUtils::fetchTowerGuid();
    	$dt[0] = Wechat::first();
    	if (! empty($dt[0])) {
    		$dt[0] = $dt[0]->toArray();
    	}

        $dt[1] = \App\Models\TowerWechat::where('guid', $tower)->first();
        if (! empty($dt[1])) {
            $dt[1] = $dt[1]->toArray();
        }

    	return $dt;
    }

    // 微信配置处理
    public function settingWechat($data)
    {
    	if (isset($data['id'])) {
    		$wechat = Wechat::find($data['id']);
    	} else {
    		$wechat = new Wechat();
    	}

    	// $wechat->encrypt_id = sha1($data['appid'] . md5(time()));
    	$wechat->url = $data['url'];
    	$wechat->appid = $data['appid'];
    	$wechat->appsecret = $data['appsecret'];
    	$wechat->token = $data['token'];
    	$wechat->encodingAesKey = $data['encodingAesKey'];

    	if ($wechat->save()) {
            $tower = TowerUtils::fetchTowerGuid();
            if (Cache::has($tower . '_wechat')) {
                Cache::forget($tower . '_wechat');

                $dt = DB::table($tower . '_wechat')->first();
                if (! empty($dt)) {
                    Cache::put($tower . '_wechat', $dt, 60);
                }
            }

            return true;
        } else {
            return fasle;
        }
    }

    // 微信关注统计
    public function getConcernCount()
    {
        $arr = [];
        $arr['date'] = self::getDate();

        foreach ($arr['date'] as $k => $v) {
            $begin = $v . ' 00:00:00';
            $end = $v . ' 23:59:59';

            // 新增关注数
            $arr['follow'][$k] = WechatMember::where('concern', 'follow')
            ->where('created_at', '>=', $begin)->where('created_at', '<=', $end)->count();

            // 取消关注数
            $arr['unfollow'][$k] = WechatMember::where('concern', 'unfollow')
            ->where('created_at', '>=', $begin)->where('created_at', '<=', $end)->count();

            // 净增关注数
            $arr['net_growth'][$k] = $arr['follow'][$k] - $arr['unfollow'][$k];
        }

        return $arr;
    }

    // 初始化上一周日期结构
    public function getDate()
    {
        $week = date('w');
        $arr = [
            date('Y-m-d', strtotime('+' . 1 - $week - 7 . 'days')),
            date('Y-m-d', strtotime('+' . 2 - $week - 7 . 'days')),
            date('Y-m-d', strtotime('+' . 3 - $week - 7 . 'days')),
            date('Y-m-d', strtotime('+' . 4 - $week - 7 . 'days')),
            date('Y-m-d', strtotime('+' . 5 - $week - 7 . 'days')),
            date('Y-m-d', strtotime('+' . 6 - $week - 7 . 'days')),
            date('Y-m-d', strtotime('+' . 7 - $week - 7 . 'days')),
        ];

        return $arr;
    }
}
