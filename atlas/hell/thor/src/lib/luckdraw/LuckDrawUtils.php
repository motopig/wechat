<?php
namespace Ecdo\EcdoThor;

use Illuminate\Support\Facades\DB;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoThor\LuckDraw;
use Ecdo\EcdoThor\LuckDrawPrize;
use Ecdo\EcdoThor\LuckDrawLog;
use Ecdo\EcdoIronMan\Coupons;
use Ecdo\EcdoIronMan\CouponsInfo;

/**
 * 幸运大抽奖
 * 
 * @category yunke
 * @package atlas\hell\thor\src\lib\luckdraw
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class LuckDrawUtils
{
    public function __construct()
    {
        // 分页
        $this->page = \Config::get('EcdoSpiderMan::setting')['page'];
    }

    // 奖品类型
    public function getType()
    {
    	$arr = array(
            ['key' => 0, 'value' => '卡券']
        );
        
        return $arr;
    }

    // 获取奖品列表
    public function getLuckDrawPage()
    {
        $dt = LuckDraw::orderBy('updated_at', 'desc')->paginate($this->page);
        foreach ($dt as $k => $v) {
            $dt[$k]->item = LuckDrawPrize::where('luck_draw_id', $v->id)->get();
            $dt[$k]->prize = self::getPrize($dt[$k]->item);
        }
        
        return $dt;
    }

    // 搜索奖品列表
    public function getLuckDrawSearchPage($search)
    {
        $dt = LuckDraw::where('name', 'like', '%'.trim($search).'%')
        ->orderBy('updated_at', 'desc')->paginate($this->page);
        foreach ($dt as $k => $v) {
            $dt[$k]->item = LuckDrawPrize::where('luck_draw_id', $v->id)->get();
            $dt[$k]->prize = self::getPrize($dt[$k]->item);
        }
        
        return $dt;
    }

    // 获取奖品参数
    public function getPrize($data)
    {
        $dt = '';

        if (! empty($data)) {
            foreach ($data as $k => $v) {
                switch ($v->type) {
                    case 0:
                        $cu = new \Ecdo\EcdoIronMan\CouponsUtils();
                        $dt .= self::getType()[0]['value'] . ': ' . 
                        $cu->getOneCouponsTitle($v->content) . '<br />';
                        break;
                }
            }
        }

        return $dt;
    }

    // 获取单个抽奖活动
    public function getLuckDrawOne($id)
    {
        $dt = LuckDraw::where('id', $id)->first()->toArray();
        $dt['prize'] = LuckDrawPrize::where('luck_draw_id', $dt['id'])->get()->toArray();

        return $dt;
    }

    // 创建奖品处理
    public function luckdrawCreate($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => '', 'data' => ''];

        DB::beginTransaction();
        $ld = new LuckDraw();

        $ld->name = $data['name'];
        $ld->begin_at = $data['begin_at'];
        $ld->end_at = $data['end_at'];
        $ld->description = $data['description'];
        $ld->disabled = $data['disabled'];
        if (! empty($data['nums'])) {
            $ld->nums = $data['nums'];
        }

        if (! empty($data['not_chance'])) {
            $ld->not_chance = $data['not_chance'];
        }

        if (! empty($data['not_message'])) {
            $ld->not_message = $data['not_message'];
        } else {
            $ld->not_message = '哎呀，真可惜擦身而过！';
        }

        if (! $ld->save()) {
            $res['errcode'] = 'error';
            $res['errmsg'] = '幸运大抽奖基本信息创建失败!';
        } else {
            $luck_draw_id = $ld->id;
        }

        if ($res['errcode'] == 'success') {
            for ($i = 0; $i < count($data['type']); $i++) {
                $ldp = new LuckDrawPrize();

                $ldp->luck_draw_id = $luck_draw_id;
                $ldp->type = $data['type'][$i];
                $ldp->content = $data['content'][$i];
                if (! empty($data['chance'][$i])) {
                    $ldp->chance = $data['chance'][$i];
                }

                if (! empty($data['quantity'][$i])) {
                    $ldp->quantity = $data['quantity'][$i];
                }

                if (! $ldp->save()) {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = '幸运大抽奖奖品信息创建失败!';
                    break;
                }
            }
        }

        if ($res['errcode'] == 'success') {
            $res['errmsg'] = '幸运大抽奖创建成功!';

            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 编辑抽奖活动
    public function luckdrawUpdate($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => '', 'data' => ''];
        DB::beginTransaction();

        // 获取当前已配置的奖品ID
        $prize = LuckDrawPrize::where('luck_draw_id', $data['id'])->lists('id');
        // 获取是否有删除的差集
        $minus = array_diff($prize, $data['pid']);
        // 获取是否有新增的差集
        $plus = array_diff($data['pid'], $prize);
        // 获取是否有需要编辑的交集
        $equal = array_intersect($prize, $data['pid']);

        if (! empty($minus)) {
            if (! LuckDrawPrize::where('luck_draw_id', $data['id'])->whereIn('id', $minus)->delete()) {
                $res['errcode'] = 'error';
                $res['errmsg'] = '幸运大抽奖奖品删除失败!';
            }
        }

        if ($res['errcode'] == 'success' && ! empty($plus)) {
            foreach ($plus as $k => $v) {
                $ldp = new LuckDrawPrize();

                $ldp->luck_draw_id = $data['id'];
                $ldp->type = $data['type'][$k];
                $ldp->content = $data['content'][$k];
                if (! empty($data['chance'][$k])) {
                    $ldp->chance = $data['chance'][$k];
                }

                if (! empty($data['quantity'][$k])) {
                    $ldp->quantity = $data['quantity'][$k];
                }

                if (! $ldp->save()) {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = '幸运大抽奖奖品信息创建失败!';
                    break;
                }
            }
        }

        if ($res['errcode'] == 'success' && ! empty($equal)) {
            foreach ($equal as $k => $v) {
                $ldp = LuckDrawPrize::find($v);
                
                $ldp->luck_draw_id = $data['id'];
                $ldp->type = $data['type'][$k];
                $ldp->content = $data['content'][$k];
                if (! empty($data['chance'][$k])) {
                    $ldp->chance = $data['chance'][$k];
                }

                if (! empty($data['quantity'][$k])) {
                    $ldp->quantity = $data['quantity'][$k];
                }

                if (! $ldp->save()) {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = '幸运大抽奖奖品信息更新失败!';
                    break;
                }
            }
        }

        if ($res['errcode'] == 'success') {
            $ld = LuckDraw::find($data['id']);

            $ld->name = $data['name'];
            $ld->begin_at = $data['begin_at'];
            $ld->end_at = $data['end_at'];
            $ld->description = $data['description'];
            $ld->disabled = $data['disabled'];
            if (! empty($data['nums'])) {
                $ld->nums = $data['nums'];
            }

            if (! empty($data['not_chance'])) {
                $ld->not_chance = $data['not_chance'];
            }

            if (! empty($data['not_message'])) {
                $ld->not_message = $data['not_message'];
            } else {
                $ld->not_message = '哎呀，真可惜擦身而过！';
            }

            if (! $ld->save()) {
                $res['errcode'] = 'error';
                $res['errmsg'] = '幸运大抽奖基本信息创建失败!';
            }
        }

        if ($res['errcode'] == 'success') {
            $res['errmsg'] = '幸运大抽奖编辑成功!';

            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 删除抽奖活动
    public function luckdrawDelete($data)
    {
        $res = ['errcode' => 'success', 'errmsg' => '幸运大抽奖删除成功!', 'data' => ''];

        if ($id = LuckDraw::where('id', $data['id'])->pluck('id')) {
            DB::beginTransaction();

            if (! LuckDrawPrize::where('luck_draw_id', $id)->delete()) {
                $res['errcode'] = 'error';
                $res['errmsg'] = '幸运大抽奖奖品删除失败!';
            }

            if ($res['errcode'] == 'success') {
                if (! LuckDraw::where('id', $data['id'])->delete()) {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = '幸运大抽奖删除失败!';
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

    // 根据openid获取奖品
    public function prizeLuckDraw($arr = [])
    {
        $res = ['errcode' => 'success', 'errmsg' => '', 'data' => ''];

        if (empty($arr)) {
            $res['errcode'] = 'error';
            $res['errmsg'] = '系统错误，缺少活动相关参数！';
        } else {
            if ($luckdraw = LuckDraw::where('id', $arr['id'])->first()) {
                $luckdraw = $luckdraw->toArray();

                if ($luckdraw['disabled'] == 'true' || $luckdraw['end_at'] <= date('Y-m-d H:i:s')) {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = '别着急，活动还未开始！';
                } elseif (LuckDrawLog::where('luck_draw_id', $arr['id'])->where('openid', $arr['openid'])->count() 
                    >= $luckdraw['nums']) {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = '您的抽奖次数已用完！';
                }
            } else {
                $res['errcode'] = 'error';
                $res['errmsg'] = '活动不存在！';
            }
        }

        if ($res['errcode'] == 'success') {
            // 抽奖概率键值匹配
            $roles = LuckDrawPrize::where('luck_draw_id', $arr['id'])->lists('chance', 'id');
            $roles[0] = $luckdraw['not_chance']; // 未抽到任何奖品
            $prize = [];
            
            $dt = chanceAlgorithm($roles);
            if ($dt > 0) {
                $prize = LuckDrawPrize::where('id', $dt)->first()->toArray();
                if ($prize['quantity'] == $prize['inventory']) {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = '奖品已领完，下次要快点！'; // 奖品领完不计抽奖次数
                } else {
                    $coupons = Coupons::where('id', $prize['content'])->first();
                    $prize['card_id'] = $coupons->card_id;
                    $prize['title'] = $coupons->title;
                }
            }
        }

        if ($res['errcode'] == 'success') {
            $ldl = new LuckDrawLog();
            $ldl->luck_draw_id = $luckdraw['id'];
            $ldl->openid = $arr['openid'];
            if (! empty($prize)) {
                $ldl->type = $prize['type'];
                $ldl->content = $prize['card_id'] . '@@@' . $prize['title'];
            }

            if (! $ldl->save()) {
                $res['errcode'] = 'error';
                $res['errmsg'] = '会员活动日志记录失败！';
            } else {
                if (empty($prize)) {
                    $res['errmsg'] = $luckdraw['not_message'];
                } else {
                    switch ($prize['type']) {
                        case 0:
                            $data = ['prize_id' => $prize['id'], 'card_id' => $prize['card_id'], 
                            'guid' => $arr['guid'], 'openid' => $arr['openid']];
                            $res = self::couponsPrizeAction($res, $data);
                            break;
                    }

                    $res['data'] = [
                        'luckdraw' => $luckdraw,
                        'prize' => $prize,
                        'openid' => $arr['openid'],
                        'guid' => $arr['guid'],
                        'url' => \Config::get('connectb2c.url')['head'] . $data['guid'] . 
                        \Config::get('connectb2c.url')['coupon'] . '?cpns_id=' . trim($prize['card_id']) . 
                        '&openid=' . trim($arr['openid']) . '&memc_code=' . trim($res['data']['code'])
                    ];
                }
            }
        }

        return $res;
    }

    // 抽中卡券奖品处理
    // 需要数据:openid,card_id,奖品prize_id,活动luckdraw_id
    public function couponsPrizeAction($res, $data = [])
    {
        DB::beginTransaction();

        // 暂时请求EC获取卡券code
        $url = \Config::get('connectb2c.url')['head'] . $data['guid'] . \Config::get('connectb2c.url')['genCoupon'];
        $code = json_decode(curlPost($url, ['cpns_id' => $data['card_id'], 'openid' => $data['openid']]))[0];
        if (empty($code)) {
            $res['errcode'] = 'error';
            $res['errmsg'] = '未获取到卡券券号！';
        }

        if ($res['errcode'] == 'success') {
            $coupons_id = Coupons::where('card_id', $data['card_id'])->pluck('id');
            $c = Coupons::find($coupons_id);
            $c->inventory += 1;
            if (! $c->save()) {
                $res['errcode'] = 'error';
                $res['errmsg'] = '更新卡券领取数量失败！';
            }
        }

        if ($res['errcode'] == 'success') {
            $ci = new CouponsInfo();
            $ci->coupons_id = $coupons_id;
            $ci->card_id = $data['card_id'];
            $ci->code = $code;
            $ci->openid = $data['openid'];

            if (! $ci->save()) {
                $res['errcode'] = 'error';
                $res['errmsg'] = '保存会员领取卡券数据失败！';
            }
        }

        if ($res['errcode'] == 'success') {
            $ldp = LuckDrawPrize::find($data['prize_id']);
            $ldp->inventory += 1;

            if (! $ldp->save()) {
                $res['errcode'] = 'error';
                $res['errmsg'] = '更新奖品领取数量失败！';
            }
        }

        if ($res['errcode'] == 'success') {
            $res['data']['code'] = $code;

            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }
}
