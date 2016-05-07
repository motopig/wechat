<?php
namespace Ecdo\EcdoIronMan;

use Illuminate\Support\Facades\DB;
use Ecdo\Universe\TowerUtils;
use App\Wormhole\WechatAction;
use Ecdo\EcdoIronMan\Coupons;
use Ecdo\EcdoIronMan\CouponsInfo;
use Ecdo\EcdoSpiderMan\TowerShare;
use Ecdo\EcdoSuperMan\StoreImage;
use Ecdo\EcdoBatMan\EntityShop;
use Ecdo\EcdoBatMan\EntityShopWechat;

/**
 * 卡券app
 * 
 * @category yunke
 * @package atlas\hell\iron-man\src\lib\coupons
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class CouponsUtils
{
    public function __construct()
    {
        // 分页
        $this->page = \Config::get('EcdoSpiderMan::setting')['page'];
    }

    // 卡券类别
    public function getType()
    {
    	$arr = array(
            0 => '云号',
            1 => '微信',
            2 => 'ecstore'
        );
        
        return $arr;
    }

    // 卡券类型
    public function getCouponsType()
    {
    	$arr = array(
    		'type' => array(
    			'DISCOUNT' => '折扣券',
	            'CASH' => '代金券',
	            'GIFT' => '礼品券',
	            'GROUPON' => '团购券',
	            'GENERALCOUPON' => '优惠券'
    		),

    		'notice' => array(
    			'DISCOUNT' => '(可为用户提供消费折扣)',
	            'CASH' => '(可为用户提供抵扣现金服务; 可设置成为"满*元, 减*元")',
	            'GIFT' => '(可为用户提供消费送赠品服务)',
	            'GROUPON' => '(可为用户提供团购套餐服务)',
	            'GENERALCOUPON' => '(即"通用券", 建议当以上四种无法满足需求时采用)'
    		)
        );
        
        return $arr;
    }

    // 卡券状态
    public function getStatus()
    {
        $arr = array(
            0 => '审核中',
            1 => '审核通过',
            2 => '审核未通过'
        );
        
        return $arr;
    }

    // 券码状态
    public function getCodeStatus()
    {
        $arr = array(
            0 => '未使用',
            1 => '已使用',
            2 => '已删除'
        );
        
        return $arr;
    }

    // 卡券颜色
    public function getCouponsColor()
    {
        $arr = array(
            ['name' => 'Color010', 'value' => '#55bd47'],
            ['name' => 'Color020', 'value' => '#10ad61'],
            ['name' => 'Color030', 'value' => '#35a4de'],
            ['name' => 'Color040', 'value' => '#3d78da'],
            ['name' => 'Color050', 'value' => '#9058cb'],
            ['name' => 'Color060', 'value' => '#de9c33'],
            ['name' => 'Color070', 'value' => '#ebac16'],
            ['name' => 'Color080', 'value' => '#f9861f'],
            ['name' => 'Color081', 'value' => '#f08500'],
            ['name' => 'Color090', 'value' => '#e75735'],
            ['name' => 'Color100', 'value' => '#d54036'],
            ['name' => 'Color101', 'value' => '#cf3e36']
        );

        return $arr;
    }

    // 卡券优惠内容
    public function getCouponsContent($type)
    {
        $arr = [];
        switch ($type) {
            case 'DISCOUNT':
                $arr = ['type' => 'DISCOUNT', 'title' => '折扣额度'];
                break;
            case 'CASH':
                $arr = ['type' => 'CASH', 'title' => '减免金额'];
                break;
            default :
                $arr = ['type' => '', 'title' => '优惠详情'];
                break;
        }
        
        return $arr;
    }

    // 投放方式
    public function getDelivery()
    {
        $arr = array(
            'type' => array(
                0 => '下载二维码'
            ),

            'notice' => array(
                0 => '(下载卡券二维码，通过打印张贴或其他渠道发放)'
            )
        );
        
        return $arr;
    }

    // 随机生成1-12位不重复字符串
    public function genKey()
    {
        $re = '';
        $num = rand(1, 12);
        $s = '1234567890';

        do {
            while (strlen($re) < 12) {
                $re .= $s[rand(0, strlen($s) - 1)];
            }

            $card_id = Coupons::where('card_id', $re)->pluck('card_id');
        } while ($card_id);

        return $re;
    }

    // 微信卡券接口
    public function couponsWechatAction($type, $data)
    {
        switch ($type) {
            case 'uploadImg':
                $arr = ['type' => $type, 'action' => ['buffer' => $data['buffer']], 
                'parameter' => ['key' => $type, 'value' => ['access_token' => '']]];
                break;
            case 'cardCreate':
            case 'cardUpdate':
                switch ($data['coupons_type']) {
                    case 'DISCOUNT':
                        $typeTitle = 'discount';
                        $card_type = 'DISCOUNT';
                        break;
                    case 'CASH':
                        $typeTitle = 'cash';
                        $card_type = 'CASH';
                        break;
                    case 'GIFT':
                        $typeTitle = 'gift';
                        $card_type = 'GIFT';
                        break;
                    case 'GROUPON':
                        $typeTitle = 'groupon';
                        $card_type = 'GROUPON';
                        break;
                    case 'GENERALCOUPON':
                        $typeTitle = 'general_coupon';
                        $card_type = 'GENERAL_COUPON';
                        break;
                }

                if (! empty($data['card_id'])) {
                    $card['card_id'] = $data['card_id'];
                    $card['card_type'] = $card_type;
                    $card[$typeTitle]['base_info'] = [
                        'date_info' => $data['date_info'],
                        'location_id_list' => ! empty($data['location_id_list']) ? $data['location_id_list'] : ''
                    ];
                } else {
                    $card['card_type'] = $card_type;
                    $card[$typeTitle] = self::wechatBaseInfoCreate($data);
                }

                $arr = ['type' => $type, 'action' => ['card' => $card], 
                'parameter' => ['key' => $type, 'value' => ['access_token' => '']]];
                break;
            case 'cardDelete':
                $arr = ['type' => $type, 'action' => ['card_id' => $data['card_id']], 
                'parameter' => ['key' => $type, 'value' => ['access_token' => '']]];
                break;
            case 'modifyStock':
                $card = [
                    'card_id' => $data['card_id'],
                    $data['frezz_key'] => (int) $data['frezz_value']
                ];

                $arr = ['type' => $type, 'action' => ['card' => $card], 
                'parameter' => ['key' => $type, 'value' => ['access_token' => '']]];
                break;
            case 'cardQrcode':
                $arr = ['type' => $type, 'action' => ['card_id' => $data['card_id']], 
                'parameter' => ['key' => $type, 'value' => ['access_token' => '']]];
                break;
        }

        $wa = new WechatAction();
        $result = $wa->send($arr);

        return $result;
    }

    // 微信创建卡券基本信息
    private function wechatBaseInfoCreate($data)
    {
        $res = [
            'base_info' => [
                'logo_url' => $data['url'], // 商户logo
                'brand_name' => $data['brand_name'], // 商户名字
                'code_type' => $data['code_type'], // code码展示类型
                'title' => $data['title'], // 券名
                'sub_title' => ! empty($data['sub_title']) ? $data['sub_title'] : '', // 券名的副标题
                'notice' => $data['notice'], // 使用提醒
                'service_phone' => ! empty($data['service_phone']) ? $data['service_phone'] : '', // 客服电话
                'description' => $data['description'], // 使用说明
                'sku' => ['quantity' => $data['quantity']], // 卡券库存的数量
                'get_limit' => $data['use_limit'], // 每人最大领取次数
                'date_info' => [
                    'type' => (int) 1, // 使用时间的类型 (1:固定日期区间,2:固定时长(自领取后按天算))
                    'begin_timestamp' => strtotime($data['begin_at']), // 起用时间
                    'end_timestamp' => strtotime($data['end_at']) // 结束时间
                ]
            ]
        ];

        $res['base_info']['can_share'] = $data['can_share'] == 'true' ? true : false; // 是否可分享
        $res['base_info']['can_give_friend'] = $data['can_give_friend'] == 'true' ? true : false; // 是否可转赠
        foreach (self::getCouponsColor() as $k => $v) {
            if ($data['color'] == $v['value']) {
                $res['base_info']['color'] = $v['name']; // 券颜色
                break;
            }
        }

        switch ($data['location_id_list']) {
            case 'store':
                $data['store_id'] = explode(',', $data['store_id']);
                if ($poi_id = EntityShopWechat::whereIn('sid', $data['store_id'])
                    ->whereNotNull('poi_id')->lists('poi_id')) {
                    $res['base_info']['location_id_list'] = $poi_id;
                }

                break;
            case 'all':
                if ($poi_id = EntityShopWechat::whereNotNull('poi_id')->lists('poi_id')) {
                    $res['base_info']['location_id_list'] = $poi_id;
                }

                break;
        }

        switch ($data['coupons_type']) {
            case 'DISCOUNT':
                $res['discount'] = $data['coupons_setting']; // 折扣额度
                break;
            case 'CASH':
                $res['reduce_cost'] = $data['coupons_setting']; // 减免金额
                break;
            case 'GIFT':
                $res['gift'] = $data['default_detail']; // 礼品详情
                break;
            case 'GROUPON':
                $res['deal_detail'] = $data['default_detail']; // 团购详情
                break;
            case 'GENERALCOUPON':
                $res['default_detail'] = $data['default_detail']; // 优惠券详情
                break;
        }

        return $res;
    }

    // 微信编辑卡券基本信息
    private function wechatBaseInfoUpdate($res, $data, $obj)
    {
        if ($data['end_at'] < $obj->end_at) {
            $res['errcode'] = 'error';
            $res['errmsg'] = '微信卡券规定：有效期时间修改仅支持有效区间的扩大！';
        } else {
            // 修改时间及变更门店
            $location_id_list = [];
            $date_info = [
                'type' => (int) 1,
                'begin_timestamp' => strtotime($obj->begin_at),
                'end_timestamp' => strtotime($data['end_at'])
            ];

            switch ($data['location_id_list']) {
                case 'store':
                    $data['store_id'] = explode(',', $data['store_id']);
                    if ($poi_id = EntityShopWechat::whereIn('sid', $data['store_id'])
                        ->whereNotNull('poi_id')->lists('poi_id')) {
                        $location_id_list = $poi_id;
                    }

                    break;
                case 'all':
                    if ($poi_id = EntityShopWechat::whereNotNull('poi_id')->lists('poi_id')) {
                        $location_id_list = $poi_id;
                    }

                    break;
            }

            $response = self::couponsWechatAction('cardUpdate', ['card_id' => $obj->card_id, 
            'coupons_type' => $obj->coupons_type, 'date_info' => $date_info, 'location_id_list' => $location_id_list]);
            if ($response['errcode'] != 'success') {
                $res['errcode'] = 'error';
                $res['errmsg'] = $response['errmsg'];
            }
        }

        return $res;
    }

    // 卡券基础设置
    public function couponSetting()
    {
        $dt = TowerShare::where('type', 1)->first();
        if ($dt) {
            $dt->content = unserialize($dt->content);

            if (! empty($dt->content['logo'])) {
                $dt->img_url = StoreImage::where('id', $dt->content['logo'])->pluck('url');
            }
        }

        return $dt;
    }

    // 卡券基础设置处理
    public function couponSettingDis($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => '卡券基础设置成功!'];

        $path = StoreImage::where('id', $data['logo'])->pluck('url');
        $name = substr($path, strrpos($path, '/') + 1);
        $buffer = '@' . base_path() . '/public/' . $path . ';filename=' . $name;
        $response = self::couponsWechatAction('uploadImg', ['buffer' => $buffer]);
        if ($response['errcode'] == 'success') {
            $data['url'] = $response['data']['url'];
        } else {
            $res['errcode'] = 'error';
            $res['errmsg'] = $response['errmsg'];
        }

        if ($res['errcode'] == 'success') {
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

            $ts->type = 1;
            $ts->content = $data['content'];

            if (! $ts->save()) {
                $res['errcode'] = 'error';
                $res['errmsg'] = '卡券基础设置失败!';
            }
        }

        return $res;
    }

    // 获取卡券列表
    public function getCouponsPage()
    {
        $dt = Coupons::orderBy('updated_at', 'desc')->paginate($this->page);
        if (! empty($dt)) {
            foreach ($dt as $k => $v) {
                if ($v->end_at <= date('Y-m-d H:i:s', time())) {
                    $dt[$k]->time = true;
                }
            }
        }

        return $dt;
    }

    // 卡券搜素列表
    public function getCouponSearchPage($search)
    {
        $dt = Coupons::where('title', 'like', '%'.trim($search).'%')
        ->orderBy('updated_at', 'desc')->paginate($this->page);
        if (! empty($dt)) {
            foreach ($dt as $k => $v) {
                if ($v->end_at <= date('Y-m-d H:i:s', time())) {
                    $dt[$k]->time = true;
                }
            }
        }

        return $dt;
    }

    // 卡券筛选列表
    public function getCouponFilterPage($filter)
    {
        $dt = Coupons::orderBy('updated_at', 'desc');
        if (count($filter) > 0) {
            foreach ($filter as $k => $v) {
                switch ($k) {
                    case 'type':
                        $dt = $dt->where($k, trim($v));
                        break;
                    case 'coupons_type':
                        $dt = $dt->where($k, trim($v));
                        break;
                    case 'status':
                        $dt = $dt->where($k, trim($v));
                        break;
                    case 'card_id':
                        $dt = $dt->where($k, 'like', '%'.trim($v).'%');
                        break;
                    case 'title':
                        $dt = $dt->where($k, 'like', '%'.trim($v).'%');
                        break;
                }
            }
        }

        $dt = $dt->paginate($this->page);
        if (! empty($dt)) {
            foreach ($dt as $k => $v) {
                if ($v->end_at <= date('Y-m-d H:i:s', time())) {
                    $dt[$k]->time = true;
                }
            }
        }
        
        return $dt;
    }

    // 获取单张卡券
    public function getOneCoupons($id)
    {
        $dt = Coupons::where('id', $id)->orWhere('card_id', $id)->first();
        $dt->favourable = self::getCouponsContent($dt->coupons_type)['title'];

        switch ($dt->location_id_list) {
            case 'all':
                $dt->store_count = EntityShop::count();
                break;
            case 'null':
                $dt->store_count = 0;
                break;
            default:
                $store_id = explode(',', $dt->location_id_list);
                $dt->store_count = EntityShop::whereIn('id', $store_id)->count();
                $dt->store = EntityShop::whereIn('id', $store_id)->get();
                break;
        }

        return $dt;
    }

    // 根据code获取卡券详情
    public function getCouponsInfo($code)
    {
        return CouponsInfo::where('code', $code)->first();
    }

    // 根据openid获取卡券详情
    public function getCouponsInfoOpenid($openid)
    {
        $dt = CouponsInfo::where('openid', $openid)->where('status', '<', 2)->orderBy('updated_at', 'desc')->get();
        if (! empty($dt)) {
            foreach ($dt as $k => $v) {
                $dt[$k]->coupons = Coupons::where('id', $v->coupons_id)->first();
                if ($dt[$k]->coupons['end_at'] <= date('Y-m-d H:i:s', time())) {
                    $dt[$k]->time = true;
                }

                // 暂时过滤ecstore优惠券
                if ($dt[$k]->coupons['type'] == 2) {
                    unset($dt[$k]);
                }
            }
        }
        
        return $dt;
    }
    
    // 获取多家门店
    public function getAllEntityShop($id)
    {
        return \Ecdo\EcdoBatMan\EntityShop::whereIN('id', $id)->get()->toArray();
    }

    // 获取卡券标题
    public function getOneCouponsTitle($id)
    {
        return Coupons::where('id', $id)->pluck('title');
    }

    // 获取有效卡券
    public function getEffectiveCoupons()
    {
        $dt = [];

        // if ($dt = Coupons::where('status', 1)->get()) {
        // 暂时只能是ecstore卡券 - no
        if ($dt = Coupons::where('status', 1)->where('type', 2)->get()) {
            $dt = $dt->toArray();
        }

        return $dt;
    }

    // 卡券投放
    public function couponsDelivery($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => '', 'data' => ''];

        switch ($data['delivery']) {
            case 0:
                $coupons = Coupons::where('id', $data['id'])->first();
                if (! empty($coupons->qrcode)) {
                    $res['url'] = $coupons->qrcode;
                } else {
                    $response = self::couponsWechatAction('cardQrcode', ['card_id' => $coupons->card_id]);
                    if ($response['errcode'] == 'success') {
                        $c = Coupons::find($coupons->id);
                        $c->qrcode = \Config::get('gravity.wechat.url')['getQrcode'] . '?ticket=' 
                        . UrlEncode($response['data']['ticket']);
                        if (! $c->save()) {
                            $res['errcode'] = 'error';
                            $res['errmsg'] = '保存二维码链接失败!';
                        }
                    } else {
                        $res['errcode'] = 'error';
                        $res['errmsg'] = $response['errmsg'];
                    }
                }

                break;
        }

        return $res;
    }

    // 创建卡券
    public function couponsCreate($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => '卡券创建成功！', 'data' => ''];
        DB::beginTransaction();

        if ($data['type'] == 1) {
            $response = self::couponsWechatAction('cardCreate', $data);
            if ($response['errcode'] == 'success') {
                $wechat_card_id = $response['data']['card_id'];
            } else {
                $res['errcode'] = 'error';
                $res['errmsg'] = $response['errmsg'];
            }
        }

        if ($res['errcode'] == 'success') {
            $c = new Coupons();

            $c->type = $data['type'];
            $c->coupons_type = $data['coupons_type'];
            $c->logo_url = $data['logo_url'];
            $c->brand_name = $data['brand_name'];
            $c->color = $data['color'];
            $c->title = $data['title'];
            $c->sub_title = $data['sub_title'];
            $c->notice = $data['notice'];
            $c->begin_at = $data['begin_at'];
            $c->end_at = $data['end_at'];
            $c->code_type = $data['code_type'];
            $c->quantity = $data['quantity'];
            $c->can_share = $data['can_share'];
            $c->can_give_friend = $data['can_give_friend'];
            $c->default_detail = $data['default_detail'];
            $c->description = $data['description'];
            $c->service_phone = $data['service_phone'];

            switch ($data['coupons_type']) {
                case 'DISCOUNT':
                    $c->coupons_setting = $data['coupons_setting'];
                    break;
                case 'CASH':
                    $c->coupons_setting = $data['coupons_setting'];
                    break;
            }

            switch ($data['location_id_list']) {
                case 'store':
                    $c->location_id_list = $data['store_id'];
                    break;
                case 'all':
                    $c->location_id_list = 'all';
                    break;
                case 'null':
                    $c->location_id_list = 'null';
                    break;
            }

            if ($data['use_limit']) {
                $c->use_limit = $data['use_limit'];
            }

            if ($data['type'] == 0) {
                $c->card_id = self::genKey();
                $c->status = 1;
            } elseif ($data['type'] == 1) {
                $c->card_id = $wechat_card_id;
                $c->status = 0;
            }

            if (! $c->save()) {
                $res['errcode'] = 'error';
                $res['errmsg'] = '卡券创建失败！';
            }
        }

        if ($res['errcode'] == 'success') {
            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 编辑卡券
    public function couponsUpdate($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => '卡券编辑成功！', 'data' => ''];
        
        $c = Coupons::find($data['id']);
        if ($c->type == 1) {
            $frezz_key = '';
            if ($data['quantity'] > $c->quantity) {
                $frezz_key = 'increase_stock_value';
                $frezz_value = $data['quantity'] - $c->quantity;
            } elseif ($data['quantity'] < $c->quantity) {
                $frezz_key = 'reduce_stock_value';
                $frezz_value = $c->quantity - $data['quantity'];
            }

            // 修改库存
            if (! empty($frezz_key)) {
                $response = self::couponsWechatAction('modifyStock', ['card_id' => $c->card_id, 
                'frezz_key' => $frezz_key, 'frezz_value' => $frezz_value]);
                if ($response['errcode'] != 'success') {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = $response['errmsg'];
                } else {
                    $c->quantity = $data['quantity'];
                    if (! $c->save()) {
                        $res['errcode'] = 'error';
                        $res['errmsg'] = '变更卡券库存数量失败！';
                    }
                }
            }

            if ($res['errcode'] == 'success') {
                $res = self::wechatBaseInfoUpdate($res, $data, $c);
            }
        }

        if ($res['errcode'] == 'success') {
            DB::beginTransaction();

            $c->logo_url = $data['logo_url'];
            $c->brand_name = $data['brand_name'];
            $c->quantity = $data['quantity'];
            $c->end_at = $data['end_at'];
            
            switch ($data['location_id_list']) {
                case 'store':
                    $c->location_id_list = $data['store_id'];
                    break;
                case 'all':
                    $c->location_id_list = 'all';
                    break;
                case 'null':
                    $c->location_id_list = 'null';
                    break;
            }

            if (! $c->save()) {
                $res['errcode'] = 'error';
                $res['errmsg'] = '卡券编辑失败！';
            }

            if ($res['errcode'] == 'success') {
                DB::commit();
            } else {
                DB::rollBack();
            }
        }

        return $res;
    }

    // 删除卡券
    public function couponsDelete($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => '卡券删除成功!', 'data' => ''];
        
        if ($card = Coupons::where('id', $data['id'])->first()) {
            DB::beginTransaction();

            if ($card->type == 1) {
                $response = self::couponsWechatAction('cardDelete', ['card_id' => $card->card_id]);
                if ($response['errcode'] == 'error') {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = $response['errmsg'];
                }
            }
            
            if ($res['errcode'] == 'success') {
                if (CouponsInfo::where('coupons_id', $card->id)->count() > 0) {
                    if (! CouponsInfo::where('coupons_id', $card->id)->delete()) {
                        $res['errcode'] = 'error';
                        $res['errmsg'] = '卡券领取记录删除失败!';
                    }
                }

                if ($res['errcode'] == 'success') {
                    if (! Coupons::where('id', $data['id'])->delete()) {
                        $res['errcode'] = 'error';
                        $res['errmsg'] = '卡券删除失败!';
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

    // 监控微信卡券通知
    public function wechatCardMonitor($data)
    {
        if ($id = Coupons::where('card_id', $data['CardId'])->pluck('id')) {
            switch ($data['event']) {
                case 'card_pass_check': // 卡券通过审核
                    $c = Coupons::find($id);
                    $c->status = 1;
                    $c->save();
                    break;
                case 'card_not_pass_check': // 卡券未通过审核
                    $c = Coupons::find($id);
                    $c->status = 2;
                    $c->save();
                    break;
                case 'user_consume_card': // 卡券核销事件
                    if ($cid = CouponsInfo::where('coupons_id', $id)
                        ->where('card_id', $data['CardId'])
                        ->where('code', $data['UserCardCode'])
                        ->where('openid', $data['fromUser'])->pluck('id')) {
                        $ci = CouponsInfo::find($cid);
                        $ci->status = 1;
                        $ci->save();
                    }

                    break;
                case 'user_get_card': // 用户领取卡券
                    // 是否为转增
                    if ($data['IsGiveByFriend'] == 1 && 
                        $del_id = CouponsInfo::where('coupons_id', $id)
                        ->where('card_id', $data['CardId'])
                        ->where('code', $data['OldUserCardCode'])
                        ->where('openid', $data['FriendUserName'])->pluck('id')) {
                        CouponsInfo::where('id', $del_id)->delete();
                    }

                    $ci = new CouponsInfo();
                    $ci->coupons_id = $id;
                    $ci->card_id = $data['CardId'];
                    $ci->code = $data['UserCardCode'];
                    $ci->openid = $data['fromUser'];
                    $ci->save();

                    $c = Coupons::find($id);
                    $c->inventory = (int) $c->inventory + 1;
                    $c->save();
                    break;
                case 'user_del_card': // 用户删除卡券
                    if ($cid = CouponsInfo::where('coupons_id', $id)
                        ->where('card_id', $data['CardId'])
                        ->where('code', $data['UserCardCode'])
                        ->where('openid', $data['fromUser'])->pluck('id')) {
                        $ci = CouponsInfo::find($cid);
                        $ci->status = 2;
                        $ci->save();
                    }

                    break;
            }
        }
    }
}
