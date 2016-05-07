<?php
namespace Ecdo\EcdoSuperMan;

use Ecdo\EcdoSuperMan\StoreVideo;
use Illuminate\Support\Facades\DB;
use Ecdo\Universe\TowerUtils;

/**
 * 店铺视频
 * 
 * @category yunke
 * @package atlas\hell\super-man\src\lib\voice
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class StoreVideoUtils
{
	public function __construct()
    {
        // 分页
        $this->page = \Config::get('EcdoSpiderMan::setting')['page'];
    }

    // 获取视频列表
    public function getVideoPage()
    {
        $dt = StoreVideo::orderBy('updated_at', 'desc')->paginate($this->page);

        return $dt;
    }

    // 获取视频
    public function getOneVideo($id)
    {
    	$dt = StoreVideo::where('id', $id)->first();
        
        return $dt;
    }

    // 搜索视频
    public function getSearchVideoPage($search)
    {
    	$dt = StoreVideo::where('name', 'like', '%'.trim($search).'%')->paginate($this->page);

        return $dt;
    }

    // 创建视频
    public function createVideo($file)
    {
    	$sv = new StoreVideo();

    	$sv->url = $file;

    	if ($sv->save()) {
    		return true;
    	} else {
    		return false;
    	}
    }

    // 编辑视频
    public function updateVideo($data)
    {
    	$sv = StoreVideo::find($data['id']);

    	$sv->name = $data['name'];

    	if ($sv->save()) {
    		return true;
    	} else {
    		return false;
    	}
    }

    // 删除视频
    public function deleteVideo($id)
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
            if (! $url = StoreVideo::where('id', $id)->pluck('url')) {
                $res = false;
            } else {
                $sv = StoreVideo::find($id);
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

    // 批量删除视频
    public function dropVideo($id)
    {	
    	$path = array();
    	$data = explode(',', $id);
    	foreach ($data as $k => $v) {
    		$path[$k] = StoreVideo::where('id', $v)->pluck('url');
    	}

    	if (! StoreVideo::whereIn('id', $data)->delete()) {
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