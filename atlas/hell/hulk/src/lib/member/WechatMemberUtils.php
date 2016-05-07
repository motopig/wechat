<?php
namespace Ecdo\EcdoHulk;

use Ecdo\EcdoHulk\WechatMember;
use Ecdo\EcdoHulk\WechatMemberInfo;
use Illuminate\Support\Facades\DB;
use Ecdo\Universe\TowerUtils;
use App\Lib\Concern;
use App\Wormhole\WechatAction;
use Queue;

/**
 * 微信会员
 * 
 * @category yunke
 * @package atlas\hell\hulk\src\lib\member
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatMemberUtils
{
    public function __construct()
    {
        // 分页
        $this->page = \Config::get('EcdoSpiderMan::setting')['page'];
        $this->guid = TowerUtils::fetchTowerGuid();
    }

	// 获取会员
    public function getMemberPage()
    {
        $dt = DB::table($this->guid . '_wechat_member')->join($this->guid . '_wechat_member_info', $this->guid . '_wechat_member.id', '=', $this->guid . '_wechat_member_info.wechat_member_id')
        ->select($this->guid . '_wechat_member_info.*', $this->guid . '_wechat_member.open_id', $this->guid . '_wechat_member.concern', $this->guid . '_wechat_member.updated_at as concern_time')
        ->orderBy($this->guid . '_wechat_member.updated_at', 'desc')->paginate($this->page);

        // 识别会员组别绑定
        if (count($dt) > 0) {
            foreach ($dt as $k => $v) {
                $dt[$k]->group_name = DB::table($this->guid . '_wechat_group')->join($this->guid . '_wechat_member_group', $this->guid . '_wechat_group.id', '=', $this->guid . '_wechat_member_group.wechat_group_id')
                ->where($this->guid . '_wechat_member_group.wechat_member_id', $v->wechat_member_id)->pluck($this->guid . '_wechat_group.name');
            }
        }
        
        return $dt;
    }

    // 查看会员
    public function getOneMember($id)
    {
        $dt = DB::table($this->guid . '_wechat_member')->join($this->guid . '_wechat_member_info', $this->guid . '_wechat_member.id', '=', $this->guid . '_wechat_member_info.wechat_member_id')
        ->where($this->guid . '_wechat_member.id', $id)->select($this->guid . '_wechat_member_info.*', $this->guid . '_wechat_member.open_id', $this->guid . '_wechat_member.concern', $this->guid . '_wechat_member.updated_at as concern_time')
        ->first();

        if ($dt) {
            $dt->group_name = DB::table($this->guid . '_wechat_group')->join($this->guid . '_wechat_member_group', $this->guid . '_wechat_group.id', '=', $this->guid . '_wechat_member_group.wechat_group_id')
            ->where($this->guid . '_wechat_member_group.wechat_member_id', $dt->wechat_member_id)->pluck($this->guid . '_wechat_group.name');
        }

        return $dt;
    }

    // 根据openid查询会员
    public function getOneMemberByOpenID($open_id)
    {
        $dt = DB::table($this->guid . '_wechat_member')->join($this->guid . '_wechat_member_info', $this->guid . '_wechat_member.id', '=', $this->guid . '_wechat_member_info.wechat_member_id')
            ->where($this->guid . '_wechat_member.open_id', $open_id)->select($this->guid . '_wechat_member_info.*', $this->guid . '_wechat_member.open_id', $this->guid . '_wechat_member.concern', $this->guid . '_wechat_member.updated_at as concern_time')
            ->first();

        return $dt;
    }

    // 搜索会员
    public function getSearchMemberPage($search)
    {
        $dt = DB::table($this->guid . '_wechat_member')->join($this->guid . '_wechat_member_info', $this->guid . '_wechat_member.id', '=', $this->guid . '_wechat_member_info.wechat_member_id')->where($this->guid . '_wechat_member_info.name', 'like', '%'.trim($search).'%')
        ->select($this->guid . '_wechat_member_info.*', $this->guid . '_wechat_member.open_id', $this->guid . '_wechat_member.concern', $this->guid . '_wechat_member.updated_at as concern_time')
        ->paginate($this->page);

        // 识别会员组别绑定
        if (count($dt) > 0) {
            foreach ($dt as $k => $v) {
                $dt[$k]->group_name = DB::table($this->guid . '_wechat_group')->join($this->guid . '_wechat_member_group', $this->guid . '_wechat_group.id', '=', $this->guid . '_wechat_member_group.wechat_group_id')
                ->where($this->guid . '_wechat_member_group.wechat_member_id', $v->wechat_member_id)->pluck($this->guid . '_wechat_group.name');
            }
        }
        
        return $dt;
    }

    // 获取组别
    public function getGroup()
    {
        $dt = WechatGroup::get();
        if (count($dt) > 0) {
            $dt = $dt->toArray();
        }

        return $dt;
    }

    // 筛选会员
    public function getFilterMemberPage($filter)
    {
        $dt = DB::table($this->guid . '_wechat_member')->join($this->guid . '_wechat_member_info', $this->guid . '_wechat_member.id', '=', $this->guid . '_wechat_member_info.wechat_member_id');
        if (count($filter) > 0) {
            foreach ($filter as $k => $v) {
                if ($k == 'concern') {
                    $dt = $dt->where($this->guid . '_wechat_member.concern', $v);
                } elseif ($k == 'name'){
                    $dt = $dt->where($this->guid . '_wechat_member_info.name', 'like', '%'.trim($v).'%');
                } elseif ($k == 'gender'){
                    $dt = $dt->where($this->guid . '_wechat_member_info.gender', $v);
                }
            }
        }

        $dt = $dt->select($this->guid . '_wechat_member_info.*', $this->guid . '_wechat_member.open_id', $this->guid . '_wechat_member.concern', $this->guid . '_wechat_member.updated_at as concern_time')
        ->paginate($this->page);

        // 识别会员组别绑定
        if (count($dt) > 0) {
            $group_id = false;
            if (count($filter) > 0 && key($filter) == 'group_id') {
                $group_id = $filter['group_id'];
            }

            foreach ($dt as $k => $v) {
                $group_name = DB::table($this->guid . '_wechat_group')->join($this->guid . '_wechat_member_group', $this->guid . '_wechat_group.id', '=', $this->guid . '_wechat_member_group.wechat_group_id')
                ->where($this->guid . '_wechat_member_group.wechat_member_id', $v->wechat_member_id);

                // 筛选组别获取组别ID
                if ($group_id) {
                    $dt[$k]->group_name = $group_name->pluck($this->guid . '_wechat_group.name');
                    $dt[$k]->group_id = WechatGroup::where('name', $dt[$k]->group_name)->pluck('id');
                } else {
                    $dt[$k]->group_name = $group_name->pluck($this->guid . '_wechat_group.name');
                }
            }

            // 筛选组别处理
            if ($group_id) {
                foreach ($dt as $k => $v) {
                    if ($v->group_id != $group_id) {
                        unset($dt[$k]);
                    }
                }
            }
        }

        return $dt;
    }

    // 订阅事件处理
    public function concern($data = [])
    {
        if (! empty($data)) {
            if (! $id = WechatMember::where('open_id', $data['fromUser'])->pluck('id')) {
                $wm = new WechatMember();
            } else {
                $wm = WechatMember::find($id);
            }

            $wm->open_id = $data['fromUser'];
            if ($data['event'] == 'unsubscribe') {
                $wm->concern = 'unfollow';
            } else {
                $wm->concern = 'follow';
            }

            if ($wm->save() && $data['event'] == 'subscribe') {
                $data['wechat_member_id'] = $wm->id;
                // Queue::push('App\Lib\Concern@send', $data); // 订阅会员个人信息队列
                self::concernInfo($data);
            }
        }
    }

    // 其他动作触发订阅事件处理
    public function concernOther($data = [])
    {
        if (! empty($data)) {
            if (! $wmid = WechatMember::where('open_id', $data['fromUser'])->pluck('id')) {
                $wm = new WechatMember();
                $wm->open_id = $data['fromUser'];
                $wm->concern = 'follow';
                if ($wm->save()) {
                    $wmid = $wm->id;
                }
            }

            $data['wechat_member_id'] = $wmid;
            // Queue::push('App\Lib\Concern@send', $data);
            self::concernInfo($data);
        }
    }

    // 订阅用户信息同步
    public function concernInfo($data = [])
    {
        if (! empty($data)) {
            $arr = ['type' => 'getUserInfo', 'action' => [], 'parameter' => ['key' => 'getUserInfo', 
            'value' => ['access_token' => '', 'openid' => $data['fromUser']]]];
            $wa = new WechatAction();
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

                $unionid = isset($result['data']['unionid']) ? $result['data']['unionid'] : '';
                if (! empty($unionid)) {
                    $wmi->unionid = $unionid;
                }

                $wmi->save();
            }
        }
    }
}
