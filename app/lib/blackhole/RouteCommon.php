<?php
namespace App\Lib;

use Session;
use App\Models\Tower;
use Ecdo\EcdoHulk\Wechat;
use Ecdo\Universe\TowerUtils;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Ecdo\Universe\TowerDB;

/**
 * 商家路由匹配公用调用类
 *
 * @category yunke
 * @package app\lib\blackhole
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class RouteCommon
{
    // 获取商家路由配置信息
    public function route()
    {
        $data = [];
        $tu = new TowerUtils();
        $tower = Session::get('guid');

        if (str_contains(URL::current(), '/angel') && ! str_contains(URL::current(), '/angel/chTower')) { // 商家后台配置
            $data['tower'] = $tu->fetchTowerGuid();
        } elseif (str_contains(URL::current(), '/wormhole')) { // 商家微信配置
            $path = explode('/', explode('/wormhole/wechat', URL::current())[0]);
            $towerGuid = end($path);
            $tu->storeTowerGuid($towerGuid);

            if ($dt = Tower::where('encrypt_id', $towerGuid)->first()) {
                $data['tower'] = $towerGuid;
            }
        } elseif (str_contains(URL::current(), '/openx') && str_contains(URL::current(), '/callback')) {
            $appid = explode('/callback', explode('/openx/', URL::current())[1])[0];
            $towerGuid = \App\Models\TowerWechat::where('appid', $appid)->where('disabled', 'false')->pluck('guid');
            
            if (! empty($towerGuid)) {
                $data['tower'] = $towerGuid;
            }
        } elseif (str_contains(URL::current(), '/oauth2/platform')) {
            $towerGuid = explode('platform/', URL::current())[1];
            $tu->storeTowerGuid($towerGuid);
            TowerDB::useConnTower();

            if (! empty($towerGuid)) {
                $data['tower'] = $towerGuid;
            }
        } else { // 商家前台配置
            $path = explode(URL::to('/'), URL::current());

            if (! empty($path[1])) {
                $towerGuid = explode('/', $path[1])[1];
                $tu->storeTowerGuid($towerGuid);
                TowerDB::useConnTower();

                if (Session::get('guid') == $towerGuid) {
                    $data['tower'] = $tower;
                } else {
                    if ($dt = Tower::where('encrypt_id', $towerGuid)->first()) {
                        Session::put('guid', $towerGuid);
                        $data['tower'] = $towerGuid;

                        if (! Session::get('tower_name')) {
                            Session::put('tower_name', $dt->name);
                        }
                    }
                }
            }
        }

        // 微信接口配置信息
        $dt = [];
        if (array_key_exists('tower', $data)) {
            if (\App\Models\TowerWechat::where('guid', $data['tower'])
                ->where('disabled', 'false')->pluck('id')) {
                $dt = [
                    'appid' => Config::get('key.wechat.appid'),
                    'appsecret' => Config::get('key.wechat.appsecret'),
                    'token' => Config::get('key.wechat.token'),
                    'encodingAesKey' => Config::get('key.wechat.encodingAesKey'),
                    'open' => true
                ];
            } else{
                TowerDB::useConnTower();
                if ($dt = DB::table($data['tower'] . '_wechat')->where('disabled', 'false')->first()) {
                    $dt = (array)$dt;
                    $dt['open'] = false;
                }
            }

            $data['wechat'] = $dt;
        }

        return $data;
    }
}
