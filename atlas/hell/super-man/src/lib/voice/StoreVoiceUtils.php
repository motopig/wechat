<?php
namespace Ecdo\EcdoSuperMan;

use Ecdo\EcdoSuperMan\StoreVoice;
use Illuminate\Support\Facades\DB;
use Ecdo\Universe\TowerUtils;

/**
 * 店铺语音
 * 
 * @category yunke
 * @package atlas\hell\super-man\src\lib\voice
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class StoreVoiceUtils
{
	public function __construct()
    {
        // 分页
        $this->page = \Config::get('EcdoSpiderMan::setting')['page'];
    }

    // 获取语音列表
    public function getVoicePage()
    {
        $dt = StoreVoice::orderBy('updated_at', 'desc')->paginate($this->page);

        return $dt;
    }

    // 获取语音
    public function getOneVoice($id)
    {
    	$dt = StoreVoice::where('id', $id)->first();
        
        return $dt;
    }

    // 搜索语音
    public function getSearchVoicePage($search)
    {
    	$dt = StoreVoice::where('name', 'like', '%'.trim($search).'%')->paginate($this->page);

        return $dt;
    }

    // 创建语音
    public function createVoice($file)
    {
    	$sv = new StoreVoice();

    	$sv->url = $file;

    	if ($sv->save()) {
    		return true;
    	} else {
    		return false;
    	}
    }

    // 编辑语音
    public function updateVoice($data)
    {
    	$sv = StoreVoice::find($data['id']);

    	$sv->name = $data['name'];

    	if ($sv->save()) {
    		return true;
    	} else {
    		return false;
    	}
    }

    // 删除语音
    public function deleteVoice($id)
    {
    	$res = true;
        DB::beginTransaction();

        if ($media_id = WechatMedia::where('f_id', $id)->where('media_type', 'video')->pluck('media_id')) {
            $data = ['action' => 'del', 'media_id' => $media_id, 'type' => 'video'];

            $wa = new WechatAction();
            $result = $wa->media($data);

            if ($result['errcode'] == 'error') {
                $res = false;
            }
        }

        if ($res) {
            if (! $url = StoreVoice::where('id', $id)->pluck('url')) {
                $res = false;
            } else {
                $sv = StoreVoice::find($id);
                if (! $sv->delete()) {
                    $res = false;
                } else {
                    if (is_dir($url)) {
                        unlink($url);
                    }
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

    // 批量删除语音
    public function dropVoice($id)
    {	
    	$path = array();
    	$data = explode(',', $id);
    	foreach ($data as $k => $v) {
    		$path[$k] = StoreVoice::where('id', $v)->pluck('url');
    	}

    	if (! StoreVoice::whereIn('id', $data)->delete()) {
    		return false;
    	} else {
    		if (count($path) > 0) {
    			foreach ($path as $k => $v) {
    				if (is_dir($v)) {
	    				unlink($v);
	    			}
    			}
    		}

    		unset($path);
    		return true;
    	}
    }
}