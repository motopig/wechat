<?php
namespace App\Lib;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Ecdo\Universe\TowerUtils;
use Ecdo\Universe\TowerDB;

/**
 * 微信开放平台 - 第三方公众平台
 * 
 * @category yunke
 * @package app\lib\wormhole\wechat
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class EcstoreOpen extends Controller
{
    public function __construct()
    {
        $this->tu = new TowerUtils();
    }

    // ecstore接口
    public function index()
    {
        $res = ['errcode' => 'success', 'errmsg' => '', 'data' => ''];
        $data = Input::all();

        if (empty($data)) {
            $res['errcode'] = 'error';
            $res['errmsg'] = '未接收到数据!';

            return $res;
        } else {
            if (! empty($data['guid'])) {
                $tu = new TowerUtils();
                $tu->storeTowerGuid($data['guid']);

                switch ($data['type']) {
                    case 'coupons':
                        $res = self::coupons($res, $data);
                        break;
                    default:
                        $res['errcode'] = 'error';
                        $res['errmsg'] = '缺少接口类型!';
                        break;
                }
            } else {
                $res['errcode'] = 'error';
                $res['errmsg'] = '缺少云号guid!';
            }

            return $res;
        }
    }

    // 卡券信息同步
    public function coupons($res, $data = [])
    {
        if (empty($data)) {
            $res['errcode'] = 'error';
            $res['errmsg'] = '缺少接口数据!';
        } else {
            DB::beginTransaction();

            switch ($data['action']) {
                case 'new':
                case 'edit':
                    if ($data['action'] == 'new') {
                        $res['errmsg'] = '创建卡券成功!';
                    } else {
                        $res['errmsg'] = '编辑卡券成功!';
                    }
                    
                    if ($id = \Ecdo\EcdoIronMan\Coupons::where('card_id', $data['cpns_id'])->pluck('id')) {
                        $c = \Ecdo\EcdoIronMan\Coupons::find($id);
                    } else {
                        $c = new \Ecdo\EcdoIronMan\Coupons();
                    }

                    $c->card_id = $data['cpns_id'];
                    $c->type = 2;
                    $c->coupons_type = 'DISCOUNT';
                    $c->logo_url = $data['guid'];
                    $c->brand_name = $data['guid'];
                    $c->color = '#55BD47';
                    $c->title = $data['cpns_name'];
                    $c->notice = $data['cpns_name'];
                    $c->begin_at = date('Y-m-d H:i');
                    $c->end_at = date('Y-m-d H:i');
                    $c->code_type = 'CODE_TYPE_TEXT';
                    $c->quantity = 10000;
                    $c->inventory = 0;
                    $c->default_detail = $data['cpns_name'];
                    $c->description = $data['cpns_name'];
                    $c->status = 1;

                    if (! $c->save()) {
                        $res['errcode'] = 'error';
                        $res['errmsg'] = '保存卡券数据失败!';
                    }

                    if ($res['errcode'] == 'success') {
                        DB::commit();
                    } else {
                        DB::rollBack();
                    }

                    break;
                case 'delete':
                    $res['errmsg'] = '删除卡券成功!';
                    $cpns_id = json_decode($data['cpns_id']);

                    foreach ($cpns_id as $k => $v) {
                        if ($id = \Ecdo\EcdoIronMan\Coupons::where('card_id', $v)->pluck('id')) {
                            if (\Ecdo\EcdoIronMan\CouponsInfo::where('coupons_id', $id)->count() > 0) {
                                if (! \Ecdo\EcdoIronMan\CouponsInfo::where('coupons_id', $id)->delete()) {
                                    $res['errcode'] = 'error';
                                    $res['errmsg'] = '卡券领取记录删除失败!';
                                    break;
                                }
                            }

                            if ($res['errcode'] == 'success') {
                                if (! \Ecdo\EcdoIronMan\Coupons::where('id', $id)->delete()) {
                                    $res['errcode'] = 'error';
                                    $res['errmsg'] = '卡券数据删除失败!';
                                    break;
                                }
                            }
                        }
                    }

                    if ($res['errcode'] == 'success') {
                        DB::commit();
                    } else {
                        DB::rollBack();
                    }
                    
                    break;
            }
        }

        return $res;
    }
}
