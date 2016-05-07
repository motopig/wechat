<?php
namespace Ecdo\EcdoHulk;

use Ecdo\EcdoHulk\WechatShakearoundDevice;
use Ecdo\EcdoHulk\WechatShakearoundPage;
use Ecdo\EcdoHulk\WechatShakearoundMaterial;
use Ecdo\EcdoHulk\WechatShakearoundPageBind;
use Ecdo\EcdoBatMan\EntityShop;
use Ecdo\EcdoBatMan\EntityShopWechat;
use Ecdo\EcdoSuperMan\StoreImage;
use Illuminate\Support\Facades\DB;
use Ecdo\Universe\TowerUtils;
use App\Wormhole\WechatAction;

/**
 * 摇一摇
 * 
 * @category yunke
 * @package atlas\hell\hulk\src\lib\code
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatShakearoundUtils
{
    public function __construct()
    {
        // 分页
        $this->page = \Config::get('EcdoSpiderMan::setting')['page'];
    }

    // 页面类型
    public static function getPageType()
    {
        // 摇一摇类型(0:自定义链接,1:关注,2:卡券,3:微信图文,4:文章,5:幸运大抽奖)
        $arr = array(
            'key' => array(
                0 => '自定义链接',
                1 => '公众号关注',
                2 => '卡券',
                5 => '幸运大抽奖'
            ),

            'value' => array(
                2 => \Ecdo\EcdoIronMan\Coupons::where('status', 1)->where('type', 1)->get(), // 暂时只能是微信卡券
                5 => \Ecdo\EcdoThor\LuckDraw::all()
            ),
        );

        return $arr;
    }

    // 设备列表
    public function shakearoundDevicePage()
    {
        $dt = WechatShakearoundDevice::orderBy('updated_at', 'desc')->paginate($this->page);
        foreach ($dt as $k => $v) {
            $dt[$k]->business_name = EntityShop::where('sid', $v->sid)->pluck('business_name');
            $dt[$k]->bind_count = WechatShakearoundPageBind::where('device_id', $v->device_id)->count();
        }
        
        return $dt;
    }

    // 页面列表
    public function shakearoundPage()
    {
        $type = self::getPageType()['key'];

        $dt = WechatShakearoundPage::orderBy('updated_at', 'desc')->paginate($this->page);
        foreach ($dt as $k => $v) {
            $dt[$k]->type_val = $type[$v->type];
            $dt[$k]->icon_url = WechatShakearoundMaterial::where('id', $v->shakearound_material_id)
            ->pluck('icon_url');
        }
        
        return $dt;
    }

    // 获取单个设备信息
    public function getOneDevice($id)
    {
        $dt = WechatShakearoundDevice::where('id', $id)->first();
        if ($dt) {
            $dt->business_name = EntityShop::where('sid', $dt->sid)->pluck('business_name');
        }
        
        return $dt;
    }

    // 搜索设备
    public function shakearoundDeviceSearchPage($search)
    {
        $dt = WechatShakearoundDevice::where('device_id', 'like', '%'.trim($search).'%')->orderBy('updated_at', 'desc')->paginate($this->page);
        foreach ($dt as $k => $v) {
            $dt[$k]->business_name = EntityShop::where('sid', $v->sid)->pluck('business_name');
        }
        
        return $dt;
    }

    // 获取单个页面
    public function getOnePage($id)
    {
        $dt = WechatShakearoundPage::where('id', $id)->first();
        if ($dt) {
            $dt['store_image_id'] = WechatShakearoundMaterial::where('id', $dt['shakearound_material_id'])->pluck('store_image_id');
            $dt['img_url'] = StoreImage::where('id', $dt['store_image_id'])->pluck('url');
        }

        return $dt;
    }

    // 搜索页面
    public function shakearoundSearchPage($search)
    {
        $dt = WechatShakearoundPage::where('title', 'like', '%'.trim($search).'%')->orderBy('updated_at', 'desc')->paginate($this->page);
        
        return $dt;
    }

    // 获取所有页面
    public function getPageAll()
    {
        $rs = WechatShakearoundPage::all();
        foreach ($rs as $k => $v) {
            $rs[$k]->icon_url = WechatShakearoundMaterial::where('id', $v->shakearound_material_id)
            ->pluck('icon_url');
        }

        return $rs;
    }

    // 获取设备绑定页面
    public function getDeviceBindPage($device_id)
    {
        $rs = WechatShakearoundPageBind::where('device_id', $device_id)->get();
        foreach ($rs as $k => $v) {
            $rs[$k]->page_item = WechatShakearoundPage::where('page_id', $v->page_id)->first();
            $material = WechatShakearoundMaterial::where('id', $v->page_item['shakearound_material_id'])->first();
            $rs[$k]->page_item['store_image_id'] = $material['store_image_id'];
            $rs[$k]->page_item['img_url'] = StoreImage::where('id', $material['store_image_id'])->pluck('url');
            $rs[$k]->page_item['icon_url'] = $material['icon_url'];
        }

        return $rs;
    }

    // 刷新设备
    public function deviceReload()
    {
        $data = ['begin' => 0, 'count' => 50];
        $res = self::shakearoundWechatAction($data, 'shakearoundDeviceSearch');

        if ($res['errcode'] == 'success') {
            $devices = $res['data']['data']['devices'];
            DB::beginTransaction();

            // 获取是否有删除的差集
            $device = WechatShakearoundDevice::lists('device_id');
            foreach ($devices as $k => $v) {
                $arr[] = $v['device_id'];
            }

            $minus = array_diff($device, $arr);
            if (! empty($minus)) {
                if (! WechatShakearoundDevice::whereIn('device_id', $minus)->delete() || 
                    ! WechatShakearoundPageBind::whereIn('device_id', $minus)->delete()) {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = '同步更新已删除设备失败!';
                }
            }

            if ($res['errcode'] == 'success') {
                foreach ($devices as $k => $v) {
                    if ($id = WechatShakearoundDevice::where('device_id', $v['device_id'])->pluck('id')) {
                        $wsd = WechatShakearoundDevice::find($id);
                    } else {
                        $wsd = new WechatShakearoundDevice();
                    }

                    $wsd->device_id = $v['device_id'];
                    $wsd->uuid = $v['uuid'];
                    $wsd->major = $v['major'];
                    $wsd->minor = $v['minor'];
                    $wsd->comment = $v['comment'];
                    $wsd->status = $v['status'];
                    // 暂时不管微信门店poi_id的处理机制
                    // if ($v['poi_id'] > 0) {
                    //     $wsd->poi_id = $v['poi_id'];
                    //     $wsd->sid = EntityShopWechat::where('poi_id', $v['poi_id'])->pluck('sid');
                    // }

                    if (! $wsd->save()) {
                        $res['errcode'] = 'error';
                        $res['errmsg'] = '更新设备失败!';

                        break;
                    }
                }
            }
        }

        if ($res['errcode'] == 'success') {
            $res['errmsg'] = '更新设备成功!';

            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 创建设备
    public function deviceCreate($data)
    {
    	$res = ['errcode' => 'success', 'errmsg' => ''];
        if (! empty($data['sid'])) {
            // 暂时不管微信门店poi_id的处理机制
    		// $data['poi_id'] = intval($data['poi_id']);
    	}

        $res = self::shakearoundWechatAction($data, 'shakearoundDeviceApplyid');
        // if ($res['errcode'] == 'success') {
        // 	DB::beginTransaction();
        // 	$device_identifiers = $res['data']['data']['device_identifiers'];
        //     $wsd->apply_id = $res['data']['data']['apply_id'];

        // 	foreach ($device_identifiers as $k => $v) {
        // 		$wsd = new WechatShakearoundDevice();

        // 		$wsd->device_id = $v['device_id'];
        // 		$wsd->uuid = $v['uuid'];
        // 		$wsd->major = $v['major'];
        // 		$wsd->minor = $v['minor'];
        // 		$wsd->sid = $data['sid'];
        // 		// $wsd->poi_id = $data['poi_id'];
        // 		$wsd->comment = $data['comment'];
        // 		if (! $wsd->save()) {
        // 			$res['errcode'] = 'error';
        //         	$res['errmsg'] = '申请设备失败!';

        //         	break;
        // 		}
        // 	}
        // }

        if ($res['errcode'] == 'success') {
            $res['errmsg'] = '申请设备成功, 请刷新同步设备信息!';

            // DB::commit();
        } else {
            // DB::rollBack();
            $res['errcode'] = 'error';
            $res['errmsg'] = '申请设备失败!';
        }

    	return $res;
    }

    // 创建页面
    public function pageCreate($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => ''];
        
        $res = self::materialCreate($res, $data['store_image_id']);
        if ($res['errcode'] == 'success') {
            DB::beginTransaction();
            $wap = new WechatAction();

            $data['icon_url'] = $res['data']['icon_url'];
            $data['shakearound_material_id'] = $res['data']['shakearound_material_id'];
            switch ($data['type']) {
                case 2:
                    $data['page_url'] = action('\Ecdo\EcdoIronMan\CouponsSite@codeReceive', 
                    [TowerUtils::getTowerGuid(), $data['content']]);
                    break;
                case 5:
                    $registerUrl = action('\Ecdo\EcdoThor\Wheel@lucky', [TowerUtils::getTowerGuid(), $data['content']]);
                    $data['page_url'] = $wap->oauth2Authorize($registerUrl);
                    break;
            }

            $res = self::shakearoundWechatAction($data, 'shakearoundPageAdd');
            if ($res['errcode'] == 'success') {
                $wsp = new WechatShakearoundPage();

                $wsp->title = $data['title'];
                $wsp->description = $data['description'];
                $wsp->shakearound_material_id = $data['shakearound_material_id'];
                $wsp->type = $data['type'];
                $wsp->page_url = $data['page_url'];
                $wsp->page_id = $res['data']['data']['page_id'];
                $wsp->comment = $data['comment'];
                switch ($data['type']) {
                    case 2:
                    case 5:
                        $wsp->content = $data['content'];
                        break;
                    default:
                        $wsp->content = '';
                        break;
                }
                
                if (! $wsp->save()) {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = '创建摇一摇页面失败!';
                }
            }
        }

        if ($res['errcode'] == 'success') {
            $res['errmsg'] = '创建摇一摇页面成功!';

            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 编辑页面
    public function pageUpdate($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => ''];
        
        $res = self::materialCreate($res, $data['store_image_id']);
        if ($res['errcode'] == 'success') {
            DB::beginTransaction();
            $wap = new WechatAction();

            $data['icon_url'] = $res['data']['icon_url'];
            $data['shakearound_material_id'] = $res['data']['shakearound_material_id'];
            switch ($data['type']) {
                case 2:
                    $data['page_url'] = action('\Ecdo\EcdoIronMan\CouponsSite@codeReceive', 
                    [TowerUtils::getTowerGuid(), $data['content']]);
                    break;
                case 5:
                    $registerUrl = action('\Ecdo\EcdoThor\Wheel@lucky', [TowerUtils::getTowerGuid(), $data['content']]);
                    $data['page_url'] = $wap->oauth2Authorize($registerUrl);
                    break;
            }

            $res = self::shakearoundWechatAction($data, 'shakearoundPageUpdate');
            if ($res['errcode'] == 'success') {
                $wsp = WechatShakearoundPage::find($data['id']);

                $wsp->title = $data['title'];
                $wsp->description = $data['description'];
                $wsp->shakearound_material_id = $data['shakearound_material_id'];
                $wsp->type = $data['type'];
                $wsp->page_url = $data['page_url'];
                $wsp->comment = $data['comment'];
                switch ($data['type']) {
                    case 5:
                        $wsp->content = $data['content'];
                        break;
                    default:
                        $wsp->content = '';
                        break;
                }

                if (! $wsp->save()) {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = '编辑摇一摇页面失败!';
                }
            }
        }

        if ($res['errcode'] == 'success') {
            $res['errmsg'] = '编辑摇一摇页面成功!';

            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 删除页面
    public function pageDelete($id)
    {
        $res = ['errcode' => 'success', 'errmsg' => ''];

        $dt = WechatShakearoundPage::where('id', $id)->first();
        if ($dt) {
            if (WechatShakearoundPageBind::where('page_id', $dt->page_id)->pluck('id')) {
                $res['errcode'] = 'error';
                $res['errmsg'] = '页面已绑定相关设备，无法删除!';
            } else {
                $data['page_id'] = $dt->page_id;
                $res = self::shakearoundWechatAction($data, 'shakearoundPageDelete');

                if ($res['errcode'] == 'success') {
                    if (! WechatShakearoundPage::where('id', $dt->id)->delete()) {
                        $res['errcode'] = 'error';
                        $res['errmsg'] = '删除摇一摇页面失败!';
                    } else {
                        $res['errmsg'] = '删除摇一摇页面成功!';
                    }
                }
            }
        }

        return $res;
    }

    // 设备绑定页面
    public function deviceBindPage($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => ''];
        $data['id'] = WechatShakearoundDevice::where('device_id', $data['device_id'])->pluck('id');
        DB::beginTransaction();

        // 判断是否需要配置设备备注信息
        if (! empty($data['comment'])) {
            $res = self::shakearoundWechatAction($data, 'shakearoundDeviceUpdate');

            if ($res['errcode'] == 'success') {
                $wsd = WechatShakearoundDevice::find($data['id']);

                $wsd->model = $data['model'];
                $wsd->comment = $data['comment'];

                if (! $wsd->save()) {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = '摇一摇设备配置备注或型号失败!';

                    DB::rollBack();
                } else {
                    DB::commit();
                }
            }
        }

        // 判断是否需要关联设备门店
        if ($res['errcode'] == 'success' && ! empty($data['sid'])) {
            // 暂时不管微信门店poi_id的处理机制
            // if ($poi_id = EntityShopWechat::where('sid', $data['sid'])->pulck('poi_id')) {
            //     $data['poi_id'] = $poi_id;
            //     $res = self::shakearoundWechatAction($data, 'shakearoundDeviceBindlocation');
            // } else {
            //     $poi_id = '';
            // }

            if ($res['errcode'] == 'success') {
                $wsd = WechatShakearoundDevice::find($data['id']);
                $wsd->sid = $data['sid'];
                // $wsd->poi_id = $poi_id;

                if (! $wsd->save()) {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = '摇一摇设备关联门店失败!';

                    DB::rollBack();
                } else {
                    DB::commit();
                }
            }
        }

        // 设备绑定页面解绑
        $pages = WechatShakearoundPageBind::where('device_id', $data['device_id'])->lists('page_id');
        if ($res['errcode'] == 'success') {
            if (count($pages) > 0) {
                // 获取是否有删除的差集
                $minus = [];
                if (! isset($data['page_id'])) {
                    $minus = $pages;
                } else {
                    $minus = array_diff($pages, $data['page_id']);
                }

                if (count($minus) > 0) {
                    foreach ($minus as $k => $v) {
                        $minus[$k] = intval($v);
                    }

                    $minus = array_merge($minus);
                    $arr = ['type' => 'shakearoundDeviceBindpage', 'action' => ['device_id' => intval($data['device_id']), 
                    'page_ids' => $minus, 'bind' => 0, 'append' => 0], 
                    'parameter' => ['key' => 'shakearoundDeviceBindpage', 'value' => ['access_token' => '']]];
                    $res = self::shakearoundWechatAction($arr, 'shakearoundDeviceBindpage');

                    if ($res['errcode'] == 'success') {
                        if (! WechatShakearoundPageBind::where('device_id', $data['device_id'])
                            ->whereIn('page_id', $minus)->delete()) {
                            $res['errcode'] = 'error';
                            $res['errmsg'] = '摇一摇设备解绑页面失败!';

                            DB::rollBack();
                        } else {
                            DB::commit();
                        }
                    }
                }
            }
        }

        // 判断是否新增绑定页面
        if ($res['errcode'] == 'success' && isset($data['page_id'])) {
            // 获取是否有新增的差集
            $plus = [];
            if (count($pages) > 0) {
                $plus = array_diff($data['page_id'], $pages);
            } else {
                $plus = $data['page_id'];
            }

            if (count($plus) > 0) {
                foreach ($plus as $k => $v) {
                    $plus[$k] = intval($v);
                }

                $plus = array_merge($plus);
                $arr = ['type' => 'shakearoundDeviceBindpage', 'action' => ['device_id' => intval($data['device_id']), 
                'page_ids' => $plus, 'bind' => 1, 'append' => 1], 
                'parameter' => ['key' => 'shakearoundDeviceBindpage', 'value' => ['access_token' => '']]];
                $res = self::shakearoundWechatAction($arr, 'shakearoundDeviceBindpage');

                if ($res['errcode'] == 'success') {
                    $res = true;

                    foreach ($plus as $k => $v) {
                        $wspb = new WechatShakearoundPageBind();

                        $wspb->device_id = $data['device_id'];
                        $wspb->page_id = $v;

                        if (! $wspb->save()) {
                            $res = false; 
                            break;
                        }
                    }

                    if (! $res) {
                        $res['errcode'] = 'error';
                        $res['errmsg'] = '摇一摇设备绑定页面失败!';

                        DB::rollBack();
                    } else {
                        DB::commit();
                    }
                }
            }
        }

        // if ($res['errcode'] == 'success') {
        //     $res = self::shakearoundWechatAction($data, 'shakearoundDeviceOne');

        //     if ($res['errcode'] == 'success') {
        //         $devices = $res['data']['data']['devices'][0];
                
        //         $wsd = WechatShakearoundDevice::find($id);
        //         $wsd->status = $devices['status'];

        //         if (! $wsd->save()) {
        //             $res['errcode'] = 'error';
        //             $res['errmsg'] = '激活摇一摇设备失败!';

        //             DB::rollBack();
        //         } else {
        //             DB::commit();
        //         }
        //     }
        // }

        if ($res['errcode'] == 'success') {
            $res['errmsg'] = '配置摇一摇设备成功!';
        }

        return $res;
    }

    // 摇一摇微信接口
    public function shakearoundWechatAction($data, $type)
    {
        switch ($type) {
            case 'shakearoundDeviceApplyid': // 申请设备ID
                $arr = ['type' => $type, 'action' => ['quantity' => intval($data['quantity']), 
                'apply_reason' => $data['apply_reason'], 'comment' => $data['comment']], 
                'parameter' => ['key' => $type, 'value' => ['access_token' => '']]];
                break;
            case 'shakearoundDeviceSearch': // 查询设备列表
                $arr = ['type' => $type, 'action' => ['begin' => $data['begin'], 'count' => $data['count']], 
                'parameter' => ['key' => $type, 'value' => ['access_token' => '']]];
                break;
            case 'shakearoundDeviceApply': // 根据批次ID查询设备列表
                $arr = ['type' => $type, 'action' => ['apply_id' => intval($data['apply_id']), 
                'begin' => $data['begin'], 'count' => $data['count']], 
                'parameter' => ['key' => $type, 'value' => ['access_token' => '']]];
                break;
            case 'shakearoundPageAdd': // 创建页面
                $arr = ['type' => $type, 'action' => ['title' => $data['title'], 'description' => $data['description'], 
                'page_url' => $data['page_url'], 'comment' => $data['comment'], 'icon_url' => $data['icon_url']], 
                'parameter' => ['key' => $type, 'value' => ['access_token' => '']]];
                break;
            case 'shakearoundPageUpdate': // 编辑页面
                $arr = ['type' => $type, 'action' => ['page_id' => intval($data['page_id']), 'title' => $data['title'], 
                'description' => $data['description'], 'page_url' => $data['page_url'], 'comment' => $data['comment'], 
                'icon_url' => $data['icon_url']], 'parameter' => ['key' => $type, 'value' => ['access_token' => '']]];
                break;
            case 'shakearoundPageDelete': // 删除页面
                $arr = ['type' => $type, 'action' => ['page_ids' => [intval($data['page_id'])]], 
                'parameter' => ['key' => $type, 'value' => ['access_token' => '']]];
                break;
            case 'shakearoundMaterialAdd': // 上传素材
                $arr = ['type' => $type, 'action' => ['media' => $data['media']], 
                'parameter' => ['key' => $type, 'value' => ['access_token' => '']]];
                break;
            case 'shakearoundDeviceUpdate': // 编辑设备备注信息
                $arr = ['type' => $type, 'action' => ['device_id' => intval($data['device_id']), 'comment' => $data['comment']], 
                'parameter' => ['key' => $type, 'value' => ['access_token' => '']]];
                break;
            case 'shakearoundDeviceBindlocation': // 配置设备与门店的关联关系
                $arr = ['type' => $type, 'action' => ['device_id' => intval($data['device_id']), 'poi_id' => intval($data['poi_id'])], 
                'parameter' => ['key' => $type, 'value' => ['access_token' => '']]];
                break;
            case 'shakearoundDeviceBindpage': // 配置设备与页面的关联关系
                $arr = $data;
                break;
        }

        $wa = new WechatAction();
        $result = $wa->send($arr);

        return $result;
    }

    // 上传素材
    public function materialCreate($res, $store_image_id)
    {
        if ($material = WechatShakearoundMaterial::where('store_image_id', $store_image_id)->first()) {
            $res['data']['shakearound_material_id'] = $material->id;
            $res['data']['icon_url'] = $material->icon_url;
        } else {
            $path = StoreImage::where('id', $store_image_id)->pluck('url');
            $name = substr($path, strrpos($path, '/') + 1);
            $media = '@' . base_path() . '/public/' . $path . ';filename=' . $name;
            $data['media'] = $media;
            $response = self::shakearoundWechatAction($data, 'shakearoundMaterialAdd');

            if ($response['errcode'] == 'success') {
                DB::beginTransaction();
                $wsm = new WechatShakearoundMaterial();
                $wsm->store_image_id = $store_image_id;
                $wsm->icon_url = $response['data']['data']['pic_url'];

                if (! $wsm->save()) {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = '创建摇一摇图片素材失败!';
                } else {
                    $res['data']['shakearound_material_id'] = $wsm->id;
                    $res['data']['icon_url'] = $wsm->icon_url;
                }
            } else {
                $res['errcode'] = 'error';
                $res['errmsg'] = $response['errmsg'];
            }

            if ($res['errcode'] == 'success') {
                DB::commit();
            } else {
                DB::rollBack();
            }
        }

        return $res;
    }

    // 摇一摇统计数据
    public function getShakearoundCount()
    {
        $arr = [];

        // 设备总数
        $arr['count'] = WechatShakearoundDevice::count();
        // 已激活设备
        $arr['1_count'] = WechatShakearoundDevice::where('status', '>', 0)->count();
        // 未激活设备
        $arr['0_count'] = WechatShakearoundDevice::where('status', 0)->count();

        return $arr;
    }
}
