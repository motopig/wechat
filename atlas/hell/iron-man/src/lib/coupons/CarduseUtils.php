<?php
namespace Ecdo\EcdoIronMan;

use Illuminate\Support\Facades\DB;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoIronMan\Coupons;
use Ecdo\EcdoIronMan\CouponsInfo;
use Ecdo\EcdoIronMan\Verification;
use Ecdo\EcdoIronMan\Carduse;
use Ecdo\EcdoBatMan\EntityShop;
use App\Wormhole\WechatAction;

/**
 * 卡券app
 * 
 * @category yunke
 * @package atlas\hell\iron-man\src\lib\carduse
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class CarduseUtils
{
    public function __construct()
    {
        // 分页
        $this->page = \Config::get('EcdoSpiderMan::setting')['page'];
    }

    // 核销状态
    public function getStatus()
    {
        $arr = array(
            0 => '未使用',
            1 => '已核销',
            2 => '已删除'
        );
        
        return $arr;
    }

    // 核销方式
    public function getType()
    {
        $arr = array(
            0 => '扫码核销',
            1 => '网页核销'
        );
        
        return $arr;
    }

    // 获取核销券列表
    public function getCardusePage()
    {
        $res = CouponsInfo::orderBy('updated_at', 'desc')->paginate($this->page);
        if (! empty($res)) {
            foreach ($res as $k => $v) {
                $res[$k]->coupons = Coupons::where('id', $v->coupons_id)->first();
                $res[$k]->carduse = Carduse::where('code', $v->code)->first();
                if (! empty($res[$k]->carduse)) {
                	$res[$k]->verification = Verification::where('openid', $res[$k]->carduse['openid'])->first();
                    if (! empty($res[$k]->verification)) {
                        $res[$k]->verification->info = unserialize($res[$k]->verification->info);
                    }
                }

                if (! empty($v->openid)) {
                    $wmu = new \Ecdo\EcdoHulk\WechatMemberUtils();
                    $res[$k]->wechat = $wmu->getOneMemberByOpenID($v->openid);
                }
            }
        }

        return $res;
    }

    // 获取核销券搜索列表
    public function getCarduseSearchPage($search)
    {
    	$res = CouponsInfo::where('code', 'like', '%'.trim($search).'%')
    	->orderBy('updated_at', 'desc')->paginate($this->page);
        if (! empty($res)) {
            foreach ($res as $k => $v) {
                $res[$k]->coupons = Coupons::where('id', $v->coupons_id)->first();
                $res[$k]->carduse = Carduse::where('coupons_id', $v->coupons_id)->first();
                if (! empty($res[$k]->carduse)) {
                	$res[$k]->verification = Verification::where('openid', $res[$k]->carduse['openid'])->first();
                    if (! empty($res[$k]->verification)) {
                        $res[$k]->verification->info = unserialize($res[$k]->verification->info);
                    }
                }

                if (! empty($v->openid)) {
                    $wmu = new \Ecdo\EcdoHulk\WechatMemberUtils();
                    $res[$k]->wechat = $wmu->getOneMemberByOpenID($v->openid);
                }
            }
        }

        return $res;
    }

    // 获取核销券筛选列表
    public function getCarduseFilterPage($filter)
    {
    	$res = CouponsInfo::orderBy('updated_at', 'desc');
    	if (count($filter) > 0) {
    		foreach ($filter as $k => $v) {
    			switch ($k) {
    				case 'status':
    					$res = $res->where($k, trim($v));
    					unset($filter[$k]);
    					break;
    				case 'code':
    					$res = $res->where($k, 'like', '%'.trim($v).'%');
    					unset($filter[$k]);
    					break;
    			}
    		}
    	}

    	$res = $res->paginate($this->page);
    	if (! empty($res)) {
            foreach ($res as $k => $v) {
                $coupons = Coupons::where('id', $v->coupons_id);
                if (count($filter) > 0) {
                	foreach ($filter as $ks => $vs) {
                		switch ($ks) {
		    				case 'type':
		    					$coupons = $coupons->where($ks, trim($vs));
		    					break;
		    				case 'coupons_type':
		    					$coupons = $coupons->where($ks, trim($vs));
		    					break;
		    				case 'title':
		    					$coupons = $coupons->where($ks, trim($vs));
		    					break;
		    			}
                	}
                }

                $coupons = $coupons->first();
                if (! empty($coupons)) {
                	$res[$k]->coupons = $coupons;
                	$res[$k]->carduse = Carduse::where('coupons_id', $v->coupons_id)->first();
	                if (! empty($res[$k]->carduse)) {
	                	$res[$k]->verification = Verification::where('openid', $res[$k]->carduse['openid'])->first();
	                }

                    if (! empty($v->openid)) {
                        $wmu = new \Ecdo\EcdoHulk\WechatMemberUtils();
                        $res[$k]->wechat = $wmu->getOneMemberByOpenID($v->openid);
                    }
                } else {
                	unset($res[$k]);
                }
            }
        }

        return $res;
    }

    // 卡券核销记录
    public function carduseLog($openid)
    {
        $res = Carduse::where('openid', $openid)->orderBy('updated_at', 'desc')->paginate($this->page);
        if (! empty($res)) {
            foreach ($res as $k => $v) {
                $res[$k]->coupons = Coupons::where('id', $v->coupons_id)->first();
                $res[$k]->info = CouponsInfo::where('code', $v->code)->first();

                $cu = new \Ecdo\EcdoIronMan\CouponsUtils();
                $res[$k]->coupons['favourable'] = $cu->getCouponsContent($res[$k]->coupons['coupons_type'])['title'];
                
                $wmu = new \Ecdo\EcdoHulk\WechatMemberUtils();
                $res[$k]->wechat = $wmu->getOneMemberByOpenID($res[$k]->info['openid']);
            }
        }

        return $res;
    }

    // 卡券核销搜索记录
    public function carduseLogSearch($openid, $search)
    {
        $res = Carduse::where('openid', $openid)->where('code', 'like', '%'.trim($search).'%')
        ->orderBy('updated_at', 'desc')->paginate($this->page);
        if (! empty($res)) {
            foreach ($res as $k => $v) {
                $res[$k]->coupons = Coupons::where('id', $v->coupons_id)->first();
                $res[$k]->info = CouponsInfo::where('code', $v->code)->first();
                
                $cu = new \Ecdo\EcdoIronMan\CouponsUtils();
                $res[$k]->coupons['favourable'] = $cu->getCouponsContent($res[$k]->coupons['coupons_type'])['title'];

                $wmu = new \Ecdo\EcdoHulk\WechatMemberUtils();
                $res[$k]->wechat = $wmu->getOneMemberByOpenID($res[$k]->info['openid']);
            }
        }
        
        return $res;
    }

    // 卡券核销
    public function carduseVerification($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => '卡券核销成功!', 'data' => ''];
        DB::beginTransaction();

        if (! $info = CouponsInfo::where('id', $data['id'])
            ->orWhere('code', $data['id'])->where('status', 0)->first()) {
            $res['errcode'] = 'error';
            $res['errmsg'] = '卡券已核销或已删除!';
        } else {
            $coupons = Coupons::where('id', $info->coupons_id)->first();
            if ($coupons->end_at <= date('Y-m-d H:i:s', time())) {
                $res['errcode'] = 'error';
                $res['errmsg'] = '卡券已过期，无法核销!'; 
            }

            // 判断是否有权限核销卡券
            if (! empty($data['openid'])) {
                $location_id_list = Verification::where('openid', $data['openid'])->pluck('location_id_list');

                switch ($location_id_list) {
                    case 'null':
                        break;
                    case 'all':
                        if ($coupons->location_id_list == 'null') {
                            $res['errcode'] = 'error';
                            $res['errmsg'] = '卡券未指定门店，您无权核销!'; 
                        }

                        break;
                    default:
                        if ($coupons->location_id_list == 'null') {
                            $res['errcode'] = 'error';
                            $res['errmsg'] = '卡券未指定门店，您无权核销!'; 
                        } elseif ($coupons->location_id_list != 'all') {
                            $v_store_id = explode(',', $location_id_list);
                            $c_store_id = explode(',', $coupons->location_id_list);
                            $intersection = array_intersect($v_store_id, $c_store_id);

                            if (empty($intersection)) {
                                $res['errcode'] = 'error';
                                $res['errmsg'] = '卡券不适用您所在的门店，您无权核销!';  
                            }
                        }

                        break;
                }
            }

            // 微信核销同步接口
            if ($res['errcode'] == 'success' && $coupons->type == 1) {
                $response = self::carduseVerificationWechat('cardCode', ['code' => $info->code]);
                if ($response['errcode'] == 'error') {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = $response['errmsg'];
                }
            }

            if ($res['errcode'] == 'success') {
                $ci = CouponsInfo::find($info->id);
                $ci->status = 1;

                if (! $ci->save()) {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = '卡券核销状态变更失败!'; 
                }
            }

            if ($res['errcode'] == 'success') {
                $c = new Carduse();
                $c->coupons_id = $info->coupons_id;
                $c->card_id = $info->card_id;
                $c->code = $info->code;
                $c->type = $data['type'];

                if (! empty($data['openid'])) {
                    $c->openid = $data['openid'];
                }

                if (! $c->save()) {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = '卡券记录保存失败!'; 
                }
            }
        }

        if ($res['errcode'] == 'success') {
            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 微信卡券核销接口
    public function carduseVerificationWechat($type, $data)
    {
        switch ($type) {
            case 'cardCode':
                $arr = ['type' => $type, 'action' => ['code' => $data['code']], 
                'parameter' => ['key' => $type, 'value' => ['access_token' => '']]];
                break;
        }

        $wa = new WechatAction();
        $result = $wa->send($arr);

        return $result;
    }
}
