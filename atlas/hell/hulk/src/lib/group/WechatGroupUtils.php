<?php
namespace Ecdo\EcdoHulk;

use Ecdo\EcdoHulk\WechatGroup;
use Illuminate\Support\Facades\DB;
use Ecdo\Universe\TowerUtils;
use App\Wormhole\WechatAction;

/**
 * 微信组别
 * 
 * @category yunke
 * @package atlas\hell\hulk\src\lib\group
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatGroupUtils
{
    public function __construct()
    {
        // 分页
        $this->page = \Config::get('EcdoSpiderMan::setting')['page'];
    }

    // 导出标题
    public function getExportType()
    {
        $arr = array(
            0 => '组别名称',
            1 => '微信组ID',
            2 => '组内人数'
        );
        
        return $arr;
    }

	// 获取组别
    public function getGroupPage()
    {
        $dt = WechatGroup::orderBy('updated_at', 'desc')->paginate($this->page);

        return $dt;
    }

    // 获取唯一组别
    public function getOneGroup($group_id)
    {
        $dt = WechatGroup::where('id', $group_id)->first();
        
        return $dt;
    }

    // 搜索组别
    public function getSearchGroupPage($search)
    {
        $dt = WechatGroup::where('name', 'like', '%'.trim($search).'%')->paginate($this->page);

        return $dt;
    }

    // 筛选组别
    public function getFilterGroupPage($filter)
    {
        $dt = WechatGroup::orderBy('created_at', 'asc');
        if (count($filter) > 0) {
            foreach ($filter as $k => $v) {
                if ($k == 'name') {
                    $dt = $dt->where($k, 'like', '%'.trim($v).'%');
                } elseif ($k == 'wechat_group_id'){
                    $dt = $dt->where($k, trim($v));
                }
            }
        }

        $dt = $dt->paginate($this->page);

        return $dt;
    }

    // 创建编辑组别
    public function crupGroup($data)
    {
    	$res = true;
        DB::beginTransaction();

        if (! empty($data['id'])) {
            $result = self::groupWechatAction($data, 'updateGroup');

        	$wg = WechatGroup::find($data['id']);
        } else {
            $result = self::groupWechatAction($data, 'createGroup');

        	$wg = new WechatGroup();
        }

        if ($result == '' || $result['errcode'] == 'error') {
            $res = false;
        } else {
            $wg->name = $data['name'];
            if (empty($data['id'])) {
                $wg->wechat_group_id = $result['data']['group']['id'];
            }

            if (! $wg->save()) {
                $res = false;
            }
        }
        
        if ($res) {
            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 删除组别
    public function deleteGroup($group_id)
    {
    	$res = true;
        DB::beginTransaction();

        // $result = self::groupWechatAction($group_id, 'deleteGroup');
        $result = true; // 因微信分组删除接口本身问题，暂时屏蔽

        if ($result == '' || $result['errcode'] == 'error') {
            $res = false;
        } else {
            if (! $id = WechatGroup::where('id', $group_id)->pluck('id')) {
                $res = false;
            } else {
                $wg = WechatGroup::find($id);
                if (! $wg->delete()) {
                    $res = false;
                }
            }
        }

    	if ($res) {
            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 分组微信接口
    public function groupWechatAction($data, $type)
    {
        switch ($type) {
            case 'createGroup':
                $arr = ['type' => 'createGroup', 'action' => ['name' => $data['name']], 
                'parameter' => ['key' => 'createGroup', 'value' => ['access_token' => '']]];
                break;
            case 'updateGroup':
                $id = WechatGroup::where('id', $data['id'])->pluck('wechat_group_id');
                $arr = ['type' => 'updateGroup', 'action' => ['id' => $id, 'name' => $data['name']], 
                'parameter' => ['key' => 'updateGroup', 'value' => ['access_token' => '']]];
                break;
            case 'deleteGroup':
                $id = WechatGroup::where('id', $data)->pluck('wechat_group_id');
                $arr = ['type' => 'deleteGroup', 'action' => ['id' => $id], 
                'parameter' => ['key' => 'deleteGroup', 'value' => ['access_token' => '']]];
                break;
        }

        $wa = new WechatAction();
        $result = $wa->send($arr);

        return $result;
    }

    // 批量删除组别
    public function dropGroup($id)
    {	
    	$data = explode(',', $id);
    	if (! WechatGroup::whereIn('id', $data)->delete()) {
    		return false;
    	} else {
    		return true;
    	}
    }

    // 导入组别
    public function importGroup($data, $rows)
    {
        $res = array(
            'errcode' => 'success',
            'msg' => ''
        );

        if (count($data) == 0) {
            $res['errcode'] = 'error';
            $res['msg'] = '上传文件内容为空!';
        } elseif (count($data) > $rows) {
            $res['errcode'] = 'error';
            $res['msg'] = '上传文件内容数据不能超过1000行!';
        }

        if ($res['errcode'] == 'success') {
            DB::beginTransaction();

            $status = true;
            foreach ($data as $k => $v) {
                // 组别名称验证
                if (! $v[0] || WechatGroup::where('name', $v[0])->pluck('id')) {
                    $num = $k + 1; // 数组下标＋1
                    $status = false;
                    $res['errcode'] = 'error';
                    $res['msg'] = '第' . $num . '行中，组别不能为空且不能重复!';
                    break;
                }
            }

            if ($status) {
                foreach ($data as $k => $v) {
                    $wg = new WechatGroup();
                    $wg->name = $v[0];
                    $wg->encrypt_id = sha1($v[0] . time());

                    if (! $wg->save()) {
                        $res['errcode'] = 'error';
                        $res['msg'] = '导入组别保存数据失败!';
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

        return $res;
    }

    // 导出组别
    public function exportGroup($id)
    {
        $data = explode(',', $id);
        foreach ($data as $k => $v) {
            $wg = WechatGroup::where('id', $v)->first();

            // 组合内容数据
            $arr[] = array(
                0 => $wg['name'],
                1 => $wg['wechat_group_id'],
                2 => $wg['count']
            );
        }

        return $arr;
    }
}
