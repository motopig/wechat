<?php
namespace Ecdo\EcdoBatMan;

use Ecdo\EcdoBatMan\EntityShop;
use Ecdo\EcdoBatMan\EntityShopInfo;
use Ecdo\EcdoBatMan\EntityShopWechat;
use Ecdo\EcdoSpiderMan\TowerShare;
use Illuminate\Support\Facades\DB;
use Ecdo\Universe\TowerUtils;
use App\Wormhole\WechatAction;
use Ecdo\EcdoSuperMan\StoreImage;

/**
 * 微信组别
 * 
 * @category yunke
 * @package atlas\hell\bat-man\src\lib\entityshop
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class EntityShopUtils
{
    public function __construct()
    {
        // 分页
        $this->page = \Config::get('EcdoSpiderMan::setting')['page'];
    }

    // 获取所有门店基本信息
    public function getEntityShopFoundation()
    {
        $dt = EntityShop::all();

        return $dt;
    }

	// 获取门店列表
    public function getEntityShopPage()
    {
        $dt = EntityShop::orderBy('updated_at', 'desc')->paginate($this->page);
        foreach ($dt as $k => $v) {
            $dt[$k]->item = EntityShopInfo::where('entity_shop_id', $v->id)->first();
            $dt[$k]->status = EntityShopWechat::where('sid', $v->sid)->pluck('status');
        }
        
        return $dt;
    }

    // 获取单个门店列表
    public function getEntityShopOne($id)
    {
        $dt = EntityShop::where('id', $id)->first();
        if ($dt) {
            $dt->item = EntityShopInfo::where('entity_shop_id', $dt->id)->first();
            $dt->status = EntityShopWechat::where('sid', $dt->sid)->pluck('status');
            $dt->categories = explode(',', $dt->categories);

            if (! empty($dt->item) && ! empty($dt->item->store_image_id)) {
                $image_id = explode(',', $dt->item->store_image_id);
                $store_image = [];

                foreach ($image_id as $k => $v) {
                    $store_image[$k]['store_image_id'] = $v;
                    $store_image[$k]['store_image_url'] = asset(StoreImage::where('id', $v)->pluck('url'));
                }

                $dt->store_image = $store_image;
                unset($image_id);
                unset($store_image);
            }
        }

        return $dt;
    }

    // 搜索门店
    public function getSearchEntityShopPage($search)
    {
        $dt = EntityShop::where('business_name', 'like', '%'.trim($search).'%')
        ->orderBy('updated_at', 'desc')->paginate($this->page);

        foreach ($dt as $k => $v) {
            $dt[$k]->item = EntityShopInfo::where('entity_shop_id', $v->id)->first();
            $dt[$k]->status = EntityShopWechat::where('sid', $v->sid)->pluck('status');
        }

        return $dt;
    }

    // 筛选门店
    public function getFilterEntityShopPage($filter)
    {
        $dt = EntityShop::orderBy('created_at', 'asc');
        if (count($filter) > 0) {
            foreach ($filter as $k => $v) {
                switch ($k) {
                    case 'sid':
                        $dt = $dt->where($k, 'like', '%'.trim($v).'%');
                        break;
                    case 'business_name':
                        $dt = $dt->where($k, 'like', '%'.trim($v).'%');
                        break;
                    case 'province':
                        $dt = $dt->where($k, trim($v));
                        break;
                    case 'city':
                        $dt = $dt->where($k, trim($v));
                        break;
                    case 'district':
                        $dt = $dt->where($k, trim($v));
                        break;
                    case 'address':
                        $dt = $dt->where($k, 'like', '%'.trim($v).'%');
                        break;
                }
            }
        }

        $dt = $dt->paginate($this->page);
        foreach ($dt as $k => $v) {
            $dt[$k]->item = EntityShopInfo::where('entity_shop_id', $v->id)->first();
            $dt[$k]->status = EntityShopWechat::where('sid', $v->sid)->pluck('status');
        }

        return $dt;
    }

    // 随机生成1-8位不重复字符串
    public function genKey()
    {
        $re = '';
        $num = rand(1, 8);
        $s = '1234567890';

        do {
            while (strlen($re) < 8) {
                $re .= $s[rand(0, strlen($s) - 1)];
            }

            $sid = EntityShop::where('sid', $re)->pluck('sid');
        } while ($sid);

        return $re;
    }

    // 门店创建处理
    public function createEntityShop($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => '', 'data' => ''];

        DB::beginTransaction();
        $es = new EntityShop();

        $es->sid = self::genKey();
        $es->business_name = $data['business_name'];
        $es->branch_name = $data['branch_name'];
        $es->categories = $data['categories'] . ',' . $data['sub'];
        $es->province = $data['province'];
        $es->city = $data['city'];
        $es->district = $data['district'];
        $es->address = $data['address'];
        $es->latitude = $data['latitude'];
        $es->longitude = $data['longitude'];

        if (! $es->save()) {
            $res['errcode'] = 'error';
            $res['errmsg'] = '配置门店基本信息失败!';
        }

        if ($res['errcode'] == 'success') {
            $esi = new EntityShopInfo();

            if (! empty($data['store_image_id'])) {
                $data['store_image_id'] = rtrim($data['store_image_id'], ',');
            }

            $esi->entity_shop_id = $es->id;
            $esi->store_image_id = $data['store_image_id'];
            $esi->telephone = $data['telephone'];
            $esi->avg_price = $data['avg_price'];
            $esi->open_time = $data['open_time'];
            $esi->recommend = $data['recommend'];
            $esi->special = $data['special'];
            $esi->desc = $data['desc'];
            $esi->signature = $data['signature'];

            if (! $esi->save()) {
                $res['errcode'] = 'error';
                $res['errmsg'] = '配置门店服务信息失败!';
            } else {
                $esw = new EntityShopWechat();
                $esw->sid = $es->sid;

                if (! $esw->save()) {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = '门店与微信关系建立失败!';
                }
            }
        }

        if ($res['errcode'] == 'success') {
            $res['errmsg'] = '创建门店成功!';

            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 编辑门店
    public function updateEntityShop($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => '', 'data' => ''];

        DB::beginTransaction();
        $es = EntityShop::find($data['id']);

        $es->business_name = $data['business_name'];
        $es->branch_name = $data['branch_name'];
        $es->categories = $data['categories'] . ',' . $data['sub'];
        $es->province = $data['province'];
        $es->city = $data['city'];
        $es->district = $data['district'];
        $es->address = $data['address'];
        $es->latitude = $data['latitude'];
        $es->longitude = $data['longitude'];

        if (! $es->save()) {
            $res['errcode'] = 'error';
            $res['errmsg'] = '配置门店基本信息失败!';
        }

        if ($res['errcode'] == 'success') {
            $esi_id = EntityShopInfo::where('entity_shop_id', $data['id'])->pluck('id');
            $esi = EntityShopInfo::find($esi_id);

            if (! empty($data['store_image_id'])) {
                $data['store_image_id'] = rtrim($data['store_image_id'], ',');
            }

            $esi->store_image_id = $data['store_image_id'];
            $esi->telephone = $data['telephone'];
            $esi->avg_price = $data['avg_price'];
            $esi->open_time = $data['open_time'];
            $esi->recommend = $data['recommend'];
            $esi->special = $data['special'];
            $esi->desc = $data['desc'];
            $esi->signature = $data['signature'];

            if (! $esi->save()) {
                $res['errcode'] = 'error';
                $res['errmsg'] = '配置门店服务信息失败!';
            }
        }

        if ($res['errcode'] == 'success') {
            $res['errmsg'] = '编辑门店成功!';

            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 删除门店
    public function deleteEntityShop($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => '删除门店成功!', 'data' => ''];

        if ($id = EntityShop::where('sid', $data['sid'])->pluck('id')) {
            DB::beginTransaction();

            if ($poi_id = EntityShopWechat::where('sid', $data['sid'])->pluck('poi_id')) {
                $response = self::couponsWechatAction('delPoi', ['poi_id' => $poi_id]);
                if ($response['errcode'] == 'error') {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = $response['errmsg'];
                }
            }

            if ($res['errcode'] == 'success') {
                if (! EntityShopWechat::where('sid', $data['sid'])->delete()) {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = '门店与微信关系删除失败!';
                }

                if ($res['errcode'] == 'success') {
                    if (! EntityShopInfo::where('entity_shop_id', $id)->delete()) {
                        $res['errcode'] = 'error';
                        $res['errmsg'] = '门店服务信息删除失败!';
                    }
                }

                if ($res['errcode'] == 'success') {
                    if (! EntityShop::where('sid', $data['sid'])->delete()) {
                        $res['errcode'] = 'error';
                        $res['errmsg'] = '门店基本信息删除失败!';
                    }
                }
            }

            if ($res['errcode'] == 'success') {
                DB::commit();
            } else {
                DB::rollBack();
            }
        }

        return $res;
    }

    // 同步微信审核门店
    public function wechatEntityShop($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => '同步门店至微信审核成功!', 'data' => ''];
        $esw = EntityShopWechat::where('sid', $data['sid'])->first();
        $store_image_id = [];
        $photo_list = [];

        if (! empty($esw)) {
            $store = EntityShop::where('sid', $data['sid'])->first()->toArray();
            $store['info'] = EntityShopInfo::where('entity_shop_id', $store['id'])->first()->toArray();
            if (! empty($store['info']['store_image_id'])) {
                $store_image_id = explode(',', $store['info']['store_image_id']);
            }

            if (empty($store_image_id)) {
                $res['errcode'] = 'error';
                $res['errmsg'] = '微信规定：至少上传一张门店图片！';
            } else {
                foreach ($store_image_id as $k => $v) {
                    $path = StoreImage::where('id', $v)->pluck('url');
                    $name = substr($path, strrpos($path, '/') + 1);
                    $buffer = '@' . base_path() . '/public/' . $path . ';filename=' . $name;
                    $response = self::storeWechatAction('uploadImg', ['buffer' => $buffer]);

                    if ($response['errcode'] == 'success') {
                        $photo_list[$k] = ['photo_url' => $response['data']['url']];
                    } else {
                        $res['errcode'] = 'error';
                        $res['errmsg'] = $response['errmsg'];
                        break;
                    }
                }

                if ($res['errcode'] == 'success') {
                    $store['photo_list'] = $photo_list;
                    $store['wechat'] = $esw->toArray();
                    $business = self::wechatStoreBaseInfo($store);
                    if ($store['wechat']['status'] == 2) {
                        $type = 'updatePoi';
                    } else {
                        $type = 'addPoi';
                    }

                    $response = self::storeWechatAction($type, ['business' => $business]);
                    if ($response['errcode'] != 'success') {
                        $res['errcode'] = 'error';
                        $res['errmsg'] = $response['errmsg'];
                    }
                }

                if ($res['errcode'] == 'success' && 
                    ($store['wechat']['status'] == 0 || $store['wechat']['status'] == 3)) {
                    $eswi = EntityShopWechat::find($store['wechat']['id']);
                    $eswi->status = 1;

                    if (! $eswi->save()) {
                        $res['errcode'] = 'error';
                        $res['errmsg'] = '保存门店同步微信数据失败！';
                    }
                }
            }
        } else {
            $res['errcode'] = 'error';
            $res['errmsg'] = '不存在的门店sid！';
        }

        return $res;
    }

    // 微信同步门店基本信息
    private function wechatStoreBaseInfo($data)
    {
        $res = [];

        switch ($data['wechat']['status']) {
            case 0:
            case 3:
                $category = \Config::get('EcdoSpiderMan::setting')['category'];
                $categories = explode(',', $data['categories']);

                $res['base_info'] = [
                    'sid' => $data['sid'], // 门店sid
                    'business_name' => $data['business_name'], // 门店名称
                    'branch_name' => ! empty($data['branch_name']) ? $data['branch_name'] : '', // 分店名称
                    'province' => $data['province'], // 门店所在省份
                    'city' => $data['city'], // 门店所在城市
                    'district' => $data['district'], // 门店所在地区
                    'address' => $data['address'], // 门店所在详细街道地址（不要填写省市信息）
                    'categories' => [$category['main'][$categories[0]] . ',' 
                    . $category['sub'][$categories[0]][$categories[1]]], // 门店类型
                    'offset_type' => (int) 1, // 坐标类型，1 为火星坐标（目前只能选1）
                    'longitude' => ! empty($data['longitude']) ? $data['longitude'] : '', // 门店所在地理位置的经度
                    'latitude' => ! empty($data['latitude']) ? $data['latitude'] : '', // 门店所在地理位置的纬度
                    'photo_list' => $data['photo_list'], // 图片列表
                    'telephone' => $data['info']['telephone'], // 门店电话
                    'special' => ! empty($data['info']['special']) ? $data['info']['special'] : '', // 特色服务
                    'open_time' => ! empty($data['info']['open_time']) ? $data['info']['open_time'] : '', // 营业时间
                    'avg_price' => ! empty($data['info']['avg_price']) ? $data['info']['avg_price'] : '', // 人均价格
                    'introduction' => ! empty($data['info']['desc']) ? $data['info']['desc'] : '', // 商户简介
                    'recommend' => ! empty($data['info']['recommend']) ? $data['info']['recommend'] : '' // 推荐品
                ];

                break;
            case 2:
                $res['base_info'] = [
                    'poi_id' => $data['poi_id'], // 门店poi_id
                    'telephone' => $data['telephone'], // 门店电话
                    'photo_list' => $data['photo_list'], // 图片列表
                    'special' => ! empty($data['info']['special']) ? $data['info']['special'] : '', // 特色服务
                    'open_time' => ! empty($data['info']['open_time']) ? $data['info']['open_time'] : '', // 营业时间
                    'avg_price' => ! empty($data['info']['avg_price']) ? $data['info']['avg_price'] : '', // 人均价格
                    'introduction' => ! empty($data['info']['desc']) ? $data['info']['desc'] : '', // 商户简介
                    'recommend' => ! empty($data['info']['recommend']) ? $data['info']['recommend'] : '' // 推荐品
                ];

                break;
        }

        return $res;
    }

    // 微信门店接口
    public function storeWechatAction($type, $data)
    {
        switch ($type) {
            case 'uploadImg':
                $arr = ['type' => $type, 'action' => ['buffer' => $data['buffer']], 
                'parameter' => ['key' => $type, 'value' => ['access_token' => '']]];
                break;
            case 'addPoi':
            case 'updatePoi':
                $arr = ['type' => $type, 'action' => ['business' => $data['business']], 
                'parameter' => ['key' => $type, 'value' => ['access_token' => '']]];
                break;
            case 'delPoi':
                $arr = ['type' => $type, 'action' => ['poi_id' => $data['poi_id']], 
                'parameter' => ['key' => $type, 'value' => ['access_token' => '']]];
                break;
        }

        $wa = new WechatAction();
        $result = $wa->send($arr);

        return $result;
    }

    // 监控微信门店审核事件
    public function wechatStoreMonitor($data)
    {
        error_log(var_export($data,1),3,dirname(__FILE__).'/weixin1.log');
        if ($id = EntityShopWechat::where('sid', $data['UniqId'])->pluck('id')) {
            $esw = EntityShopWechat::find($id);

            if ($data['Result'] == 'succ') {
                $esw->status = 2;
                $esw->poi_id = $data['PoiId'];
            } else {
                $esw->status = 3;
            }

            $esw->save();
        }
    }

    // 附近门店配置
    public function nearbyEntityShopConfig()
    {
        $dt = TowerShare::where('type', 0)->first();
        if ($dt) {
            $dt->content = unserialize($dt->content);
            if (! empty($dt->content['store_image_id'])) {
                $dt->img_url = StoreImage::where('id', $dt->content['store_image_id'])->pluck('url');
            }
        }

        return $dt;
    }

    // 附近门店配置
    public function nearbyEntityShopConfigDis($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => '附近门店配置成功!'];
        $content = $data;
        unset($content['csrf_token']);
        unset($content['csrf_guid']);
        unset($content['id']);
        $data['content'] = serialize($content);

        if (! empty($data['id'])) {
            $ts = TowerShare::find($data['id']);
        } else {
            $ts = new TowerShare();
        }

        $ts->type = 0;
        $ts->content = $data['content'];

        if (! $ts->save()) {
            $res['errcode'] = 'error';
            $res['errmsg'] = '附近门店配置失败!';
        }

        return $res;
    }

    // 获取前3个附近门店数组
    public function arrThree($data, $num)
    {
        foreach ($data as $k => $v) {
            if ($k > $num) {
                unset($data[$k]);
            }
        }

        return $data;
    }

    // 附近门店经纬度计算
    function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $EARTH_RADIUS = 6378.137;

        $lat1 = $this->getRadian($lat1);
        $lat2 = $this->getRadian($lat2);

        $a = $lat1 - $lat2;
        $b = $this->getRadian($lng1) - $this->getRadian($lng2);

        $v = 2 * asin(sqrt(pow(sin($a/2),2) + cos($lat1) * cos($lat2) * pow(sin($b/2),2)));
        $v = round($EARTH_RADIUS * $v * 10000) / 10000;

        return $v;
    }

    // 附近门店经纬度公式
    function getRadian($d)
    {
        return $d * 3.1415926535898 / 180.0;
    }

    // 附近门店图文数据
    public function nearbyEntityShopGraphics($data)
    {
        $res = [];
        $shop = self::nearbyEntityShop($data['guid'], $data['location_x'], $data['location_y']);

        if (! empty($shop)) {
            // 获取指定条目的附近门店图文 (下标从0开始计算)
            $shop['shop'] = $this->arrThree($shop['shop'], $shop['nearby_sum'] - 1);

            // 附近门店图文处理
            $articles = [];
            $articles[0]['title'] = '更多您附近的门店>>';
            $articles[0]['url'] = $shop['nearby_url'];
            $articles[0]['picurl'] = $shop['nearby_img_url'];

            foreach ($shop['shop'] as $k => $v) {
                $articles[$k + 1]['title'] = $v['business_name'] . "\n" . $v['km'];
                $articles[$k + 1]['url'] = $v['url'];
                $articles[$k + 1]['picurl'] = $v['img_url'];
            }

            // $item = '';
            // $tpl = self::graphicsXml();
            // foreach ($articles as $k => $v) {
            //     $item .= sprintf($tpl, $v['title'], $v['url'], $v['picurl']);
            // }

            // $res['data'] = ['passive' => true, 'type' => 'sendMsgNews', 
            // 'action' => ['touser' => $data['fromUser'], 'fromuser' => $data['toUser'], 
            // 'articleCount' => count($articles), 'articles' => $item]];

            $res['data'] = ['type' => 'sendMsgNews', 
            'action' => ['touser' => $data['fromUser'], 'articles' => $articles], 
            'parameter' => ['key' => 'sendMsg','value' => ['access_token' => '']]];
        }

        return $res;
    }

    // 获取附近门店信息
    public function nearbyEntityShop($guid, $latitude, $longitude)
    {
        $data = [];
        $nearby = self::nearbyEntityShopConfig();

        if (! empty($nearby) && $nearby->content['disabled'] == 'false') {
            $shop = self::getEntityShopFoundation();

            if (count($shop) > 0) {
                $shop = $shop->toArray();
                $data['nearby_url'] = action('\Ecdo\EcdoBatMan\EntityShopSite@entityList', [$guid, $latitude, $longitude]);

                if (empty($nearby->content['store_image_id'])) {
                    $data['nearby_img_url'] = asset('store.jpg');
                } else {
                    $data['nearby_img_url'] = asset($nearby->img_url);
                }

                if (empty($nearby->content['sum'])) {
                    $data['nearby_sum'] = 3;
                } else {
                    $data['nearby_sum'] = $nearby->content['sum'];
                }

                if (empty($nearby->content['num'])) {
                    $data['nearby_num'] = 10;
                } else {
                    $data['nearby_num'] = $nearby->content['num'];
                }

                if (empty($nearby->content['km'])) {
                    $data['nearby_km'] = 20;
                } else {
                    $data['nearby_km'] = $nearby->content['km'];
                }

                // 获取经纬度所计算出的距离
                foreach ($shop as $k => $v) {
                    if ($store_image_id = EntityShopInfo::where('entity_shop_id', $v['id'])->pluck('store_image_id')) {
                        $store_image_id = explode(',', $store_image_id);
                        $shop[$k]['img_url'] = asset(StoreImage::where('id', $store_image_id[0])->pluck('url'));
                        unset($store_image_id);
                    } else {
                        $shop[$k]['img_url'] = asset('store.jpg');
                    }

                    $km = self::getDistance($latitude, $longitude, $v['latitude'], $v['longitude']);
                    if ($km <= $data['nearby_km']) {
                        $shop[$k]['order'] = $km;
                        $shop[$k]['url'] = action('\Ecdo\EcdoBatMan\EntityShopSite@entityDetail', [$guid, $v['sid']]);

                        if ($km < 1) {
                            $shop[$k]['km'] = ceil($km * 1000) . "米";
                        } else {
                            $shop[$k]['km'] = sprintf("%.2f", $km) . "公里";
                        }
                    } else {
                        unset($shop[$k]);
                    }
                }

                if (! empty($shop)) {
                    $shop = self::multi_array_sort($shop, 'order');
                }

                $data['shop'] = $shop;
            }
        }

        return $data;
    }

    // 获取单个附近门店信息
    public function nearbyEntityShopOne($guid, $sid)
    {
        $arr = [];
        
        if ($dt = EntityShop::where('sid', $sid)->first()) {
            $dt->item = EntityShopInfo::where('entity_shop_id', $dt->id)->first();
            $dt->status = EntityShopWechat::where('sid', $dt->sid)->pluck('status');
            $dt->categories = explode(',', $dt->categories);

            if (! empty($dt->item) && ! empty($dt->item->store_image_id)) {
                $image_id = explode(',', $dt->item->store_image_id);
                $store_image = [];

                foreach ($image_id as $k => $v) {
                    $store_image[$k]['store_image_id'] = $v;
                    $store_image[$k]['store_image_url'] = asset(StoreImage::where('id', $v)->pluck('url'));
                }

                $dt->store_image = $store_image;
                unset($image_id);
                unset($store_image);
            } else {
                $dt->store_image = [
                    0 => ['store_image_url' => asset('store.jpg')]
                ];
            }

            $arr = $dt;
        }

        return $arr;
    }

    // 获取图文模版
    private function graphicsXml()
    {
        return '<item>
                <Title>%s</Title>
                <Url>%s</Url>
                <PicUrl>%s</PicUrl>
                <Description></Description>
                </item>';
    }

    // 二维数组按元素排序
    public function multi_array_sort($multi_array, $sort_key, $sort = SORT_ASC)
    { 
        if (is_array($multi_array)) { 
            foreach ($multi_array as $row_array) { 
                if (is_array($row_array)) {
                    $key_array[] = $row_array[$sort_key]; 
                } else {
                    return false; 
                }
            }
        } else {
            return false; 
        }

        array_multisort($key_array, $sort, $multi_array); 
        return $multi_array; 
    }
}
