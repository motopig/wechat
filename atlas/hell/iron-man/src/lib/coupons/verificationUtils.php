<?php
namespace Ecdo\EcdoIronMan;

use Illuminate\Support\Facades\DB;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoIronMan\Verification;
use Ecdo\EcdoBatMan\EntityShop;

/**
 * 卡券app
 * 
 * @category yunke
 * @package atlas\hell\iron-man\src\lib\coupons
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class verificationUtils
{
    public function __construct()
    {
        // 分页
        $this->page = \Config::get('EcdoSpiderMan::setting')['page'];
    }

    // 核销员状态
    public function getStatus()
    {
        $arr = array(
            0 => '审核中',
            1 => '启用',
            2 => '禁用'
        );
        
        return $arr;
    }

    // 获取核销员列表
    public function getVerificationPage()
    {
        $res = Verification::orderBy('updated_at', 'desc')->paginate($this->page);
        if (! empty($res)) {
            $wmu = new \Ecdo\EcdoHulk\WechatMemberUtils();
            $wcu = new \Ecdo\EcdoHulk\WechatCodeUtils();

            foreach ($res as $k => $v) {
                $res[$k]->info = unserialize($v->info);
                $res[$k]->wechat = $wmu->getOneMemberByOpenID($v->openid);

                switch ($v->location_id_list) {
                    case 'all':
                        $res[$k]->store_count = '全部';
                        break;
                    case 'null':
                        $res[$k]->store_count = '未指定';
                        break;
                    default:
                        $store_id = explode(',', $v->location_id_list);
                        $res[$k]->store_count = EntityShop::whereIn('id', $store_id)->count() . '家';
                        break;
                }
            }
        }

        return $res;
    }

    // 获取核销员搜索列表
    public function getVerificationSearchPage($search)
    {
        $res = Verification::where('info', 'like', '%'.trim($search).'%')
        ->orderBy('updated_at', 'desc')->paginate($this->page);
        if (! empty($res)) {
            $wmu = new \Ecdo\EcdoHulk\WechatMemberUtils();
            $wcu = new \Ecdo\EcdoHulk\WechatCodeUtils();

            foreach ($res as $k => $v) {
                $res[$k]->info = unserialize($v->info);
                $res[$k]->wechat = $wmu->getOneMemberByOpenID($v->openid);

                switch ($v->location_id_list) {
                    case 'all':
                        $res[$k]->store_count = '全部';
                        break;
                    case 'null':
                        $res[$k]->store_count = 0;
                        break;
                    default:
                        $store_id = explode(',', $v->location_id_list);
                        $res[$k]->store_count = EntityShop::whereIn('id', $store_id)->count() . '家';
                        break;
                }
            }
        }

        return $res;
    }

    // 获取核销员筛选列表
    public function getVerificationFilterPage($filter)
    {
        $res = Verification::orderBy('updated_at', 'desc');
        if (count($filter) > 0) {
            foreach ($filter as $k => $v) {
                switch ($k) {
                    case 'status':
                        $res = $res->where($k, trim($v));
                        break;
                    case 'name':
                        $res = $res->where('info', 'like', '%'.trim($v).'%');
                        break;
                    case 'mobile':
                        $res = $res->where('info', 'like', '%'.trim($v).'%');
                        break;
                }
            }
        }

        $res = $res->paginate($this->page);
        if (! empty($res)) {
            $wmu = new \Ecdo\EcdoHulk\WechatMemberUtils();
            $wcu = new \Ecdo\EcdoHulk\WechatCodeUtils();

            foreach ($res as $k => $v) {
                $res[$k]->info = unserialize($v->info);
                $res[$k]->wechat = $wmu->getOneMemberByOpenID($v->openid);

                switch ($v->location_id_list) {
                    case 'all':
                        $res[$k]->store_count = '全部';
                        break;
                    case 'null':
                        $res[$k]->store_count = '未指定';
                        break;
                    default:
                        $store_id = explode(',', $v->location_id_list);
                        $res[$k]->store_count = EntityShop::whereIn('id', $store_id)->count() . '家';
                        break;
                }
            }
        }

        return $res;
    }

    // 获取单个核销员信息
    public function getOneVerification($id)
    {
        $res = Verification::where('openid', $id)->orWhere('id', $id)->first();
        if (! empty($res)) {
            $wmu = new \Ecdo\EcdoHulk\WechatMemberUtils();
            $wcu = new \Ecdo\EcdoHulk\WechatCodeUtils();

            $res->info = unserialize($res->info);
            $res->wechat = $wmu->getOneMemberByOpenID($res['openid']);

            switch ($res->location_id_list) {
                case 'all':
                    $res->store_count = '全部';
                    break;
                case 'null':
                    $res->store_count = '未指定';
                    break;
                default:
                    $store_id = explode(',', $res->location_id_list);
                    $res->store_count = EntityShop::whereIn('id', $store_id)->count();
                    $res->store = EntityShop::whereIn('id', $store_id)->get();
                    break;
            }
        }

        return $res;
    }

    // 判断核销二维码有效性
    public function getCodeValidate($code_id)
    {
        $wc = \Ecdo\EcdoHulk\WechatCode::find($code_id);
        $verification = unserialize($wc->content);

        if ((int) $verification['quantity'] > (int) $verification['inventory']) {
            return true;
        } else {
            return false;
        }
    }

    // 核销员注册
    public function createVerification($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => '核销员注册成功!'];
        DB::beginTransaction();

        if ($id = Verification::where('openid', $data['openid'])->pluck('id')) {
            $res['errcode'] = 'error';
            $res['errmsg'] = '核销员已注册!';
        }

        if ($res['errcode'] == 'success') {
            $wc = \Ecdo\EcdoHulk\WechatCode::find($data['code_id']);

            $verification = unserialize($wc->content);
            $quantity = ! empty($verification['quantity']) ? $verification['quantity'] : 20;
            $inventory = ! empty($verification['inventory']) ? (int) $verification['inventory'] + 1 : 1;
            $wc->content = serialize(['quantity' => $quantity, 'inventory' => $inventory]);

            if (! $wc->save()) {
                $res['errcode'] = 'error';
                $res['errmsg'] = '核销校验码保存失败!';
            }
        }

        if ($res['errcode'] == 'success') {
            $v = new Verification();

            $v->openid = $data['openid'];
            $v->location_id_list = 'null';
            $v->info = serialize(['name' => $data['name'], 'mobile' => $data['mobile']]);

            if (! $v->save()) {
                $res['errcode'] = 'error';
                $res['errmsg'] = '核销员注册失败!';
            }
        }

        if ($res['errcode'] == 'success') {
            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 核销员创建
    public function verificationCreateDis($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => '核销员创建成功!', 'data' => ''];
        DB::beginTransaction();

        if (! $id = \Ecdo\EcdoHulk\WechatMember::where('open_id', $data['openid'])->pluck('id')) {
            $res['errcode'] = 'error';
            $res['errmsg'] = '不存在的openid!';
        } elseif ($id = Verification::where('openid', $data['openid'])->pluck('id')) {
            $res['errcode'] = 'error';
            $res['errmsg'] = '核销员已存在!';
        }

        if ($res['errcode'] == 'success') {
            $v = new Verification();
            $v->openid = $data['openid'];
            $v->location_id_list = 'null';
            $v->info = serialize(['name' => $data['name'], 'mobile' => $data['mobile']]);

            if (! $v->save()) {
                $res['errcode'] = 'error';
                $res['errmsg'] = '核销员创建失败!';
            }
        }

        if ($res['errcode'] == 'success') {
            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 核销员编辑
    public function verificationUpdateDis($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => '核销员编辑成功!', 'data' => ''];
        DB::beginTransaction();

        $v = Verification::find($data['id']);

        $v->info = serialize(['name' => $data['name'], 'mobile' => $data['mobile']]);
        $v->status = $data['status'];
        switch ($data['location_id_list']) {
            case 'store':
                $v->location_id_list = $data['store_id'];
                break;
            case 'all':
                $v->location_id_list = 'all';
                break;
            case 'null':
                $v->location_id_list = 'null';
                break;
        }
            
        if (! $v->save()) {
            $res['errcode'] = 'error';
            $res['errmsg'] = '核销员编辑失败!';
        }

        if ($res['errcode'] == 'success') {
            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 核销员删除
    public function verificationDelete($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => '核销员删除成功!', 'data' => ''];
        
        if ($id = Verification::where('id', $data['id'])->pluck('id')) {
            DB::beginTransaction();

            if (! Verification::where('id', $data['id'])->delete()) {
                $res['errcode'] = 'error';
                $res['errmsg'] = '核销员删除失败!';
            }

            if ($res['errcode'] == 'success') {
                DB::commit();
            } else {
                DB::rollBack();
            }
        }

        return $res;
    }
}
