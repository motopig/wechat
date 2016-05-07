<?php
namespace Ecdo\EcdoSuperMan;

use Ecdo\EcdoSuperMan\StoreImage;
use Illuminate\Support\Facades\DB;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoHulk\WechatMedia;
use App\Wormhole\WechatAction;

/**
 * 店铺图片
 * 
 * @category yunke
 * @package atlas\hell\super-man\src\lib\image
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class StoreImageUtils
{
	public function __construct()
    {
        // 分页
        $this->page = \Config::get('EcdoSpiderMan::setting')['page'];
    }

    // 获取图片列表
    public function getImagePage()
    {
        $dt = StoreImage::orderBy('updated_at', 'desc')->paginate($this->page);

        return $dt;
    }

    // 获取图片
    public function getOneImage($id)
    {
    	$dt = StoreImage::where('id', $id)->first();
        
        return $dt;
    }

    // 搜索图片
    public function getSearchImagePage($search)
    {
    	$dt = StoreImage::where('name', 'like', '%'.trim($search).'%')->paginate($this->page);

        return $dt;
    }

    // 创建图片
    public function createImage($file)
    {
    	$si = new StoreImage();

    	$si->url = $file;

    	if ($si->save()) {
    		return true;
    	} else {
    		return false;
    	}
    }

    // 编辑图片
    public function updateImage($data)
    {
    	$si = StoreImage::find($data['id']);

    	$si->name = $data['name'];

    	if ($si->save()) {
    		return true;
    	} else {
    		return false;
    	}
    }

    // 删除图片
    public function deleteImage($id)
    {
    	$res = true;
        DB::beginTransaction();

        if ($media_id = WechatMedia::where('f_id', $id)->where('media_type', 'image')->pluck('media_id')) {
            $data = ['action' => 'del', 'media_id' => $media_id, 'type' => 'image'];

            $wa = new WechatAction();
            $result = $wa->media($data);

            if ($result['errcode'] == 'error') {
                $res = false;
            }
        }

        if ($res) {
            if (! $url = StoreImage::where('id', $id)->pluck('url')) {
                $res = false;
            } else {
                $si = StoreImage::find($id);
                if (! $si->delete()) {
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

    // 批量删除图片
    public function dropImage($id)
    {	
    	$path = array();
    	$data = explode(',', $id);
    	foreach ($data as $k => $v) {
    		$path[$k] = StoreImage::where('id', $v)->pluck('url');
    	}

    	if (! StoreImage::whereIn('id', $data)->delete()) {
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