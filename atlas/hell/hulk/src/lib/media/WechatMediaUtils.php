<?php
namespace Ecdo\EcdoHulk;

use Ecdo\EcdoHulk\Wechat;
use Illuminate\Support\Facades\DB;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoHulk\WechatMedia;
use Ecdo\EcdoHulk\WechatGraphic;
use Ecdo\EcdoSuperMan\StoreImage;
use Ecdo\EcdoSuperMan\StoreVideo;
use Ecdo\EcdoSuperMan\StoreVoice;
use Ecdo\EcdoHulk\WechatGraphicsUtils;
use App\Wormhole\WechatAction;

/**
 * 商家用户数据获取类
 * 
 * @category yunke
 * @package atlas\hell\hulk\src\lib\media
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatMediaUtils
{
	// 图文素材同步微信
    public function addNewMedia($res, $data = array())
    {
        $media_id = '';
        $media = false;
        
        if (! $media_id = WechatMedia::where('media_type', $data['type'])->where('f_id', $data['id'])->pluck('media_id')) {
            $media = true;
            $wgu = new WechatGraphicsUtils();
            $graphics[] = $wgu->getOneGraphics($data['id']);
        }

        if ($media && ! empty($graphics)) {
            $articles = [];
            foreach ($graphics as $k => $v) {
                $articles[$k]['title'] = $v['title'];
                $articles[$k]['author'] = $v['author'];
                $articles[$k]['digest'] = $v['digest'];
                $articles[$k]['show_cover_pic'] = $v['show_cover_pic'];
                $articles[$k]['content'] = $v['content'];
                $articles[$k]['content_source_url'] = action('\Ecdo\EcdoHulk\WechatSite@graphics', 
                [$data['guid'], $v['id']]);

                $data = ['type' => 'image', 'id' => $v['store_image_id']];
                $media = self::addMedia($data);
                if ($res['errcode'] == 'error') {
                    break;
                } else {
                    $articles[$k]['thumb_media_id'] = $media['data'];

                    if (count($graphics[$k]['item']) > 0) {
                        foreach ($graphics[$k]['item'] as $ks => $vs) {
                            $articles[$ks + 1]['title'] = $vs['title'];
                            $articles[$ks + 1]['author'] = $vs['author'];
                            $articles[$ks + 1]['show_cover_pic'] = $vs['show_cover_pic'];
                            $articles[$ks + 1]['content'] = $vs['content'];
                            $articles[$ks + 1]['content_source_url'] = action('\Ecdo\EcdoHulk\WechatSite@graphics', 
                            [$data['guid'], $vs['id']]);

                            $datas = ['type' => 'image', 'id' => $vs['store_image_id']];
                            $medias = self::addMedia($datas);
                            if ($res['errcode'] == 'error') {
                                break;
                            } else {
                                $articles[$ks + 1]['thumb_media_id'] = $medias['data'];
                            }
                        }
                    }
                }
            }

            if ($res['errcode'] == 'success') {
                $arr = ['type' => 'addNews', 'action' => ['articles' => $articles], 
                'parameter' => ['key' => 'addNews', 'value' => ['access_token' => '']]];

                $wa = new WechatAction();
                $result = $wa->send($arr);
                if ($result == '' || $result['errcode'] == 'error') {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = $result['errmsg'];
                } else {
                    $wm = new WechatMedia();
                    $wm->f_id = $data['id'];
                    $wm->media_id = $result['data']['media_id'];
                    $wm->media_type = $data['type'];

                    if (! $wm->save()) {
                        $res['errcode'] = 'error';
                        $res['errmsg'] = '保存微信永久素材 media_id 失败!';
                    } else {
                        $res['data'] = $result['data']['media_id'];
                    }
                }
            }
        } else if (! empty($media_id)) {
            $res['data'] = $media_id;
        } else {
            $res['errcode'] = 'error';
            $res['errmsg'] = '获取media_id失败!';
        }

        return $res;
    }

    // 图片、视频、语音素材同步微信
    public function addMedia($res, $data = array())
    {
        $media_id = '';
        $media = false;
        
        switch ($data['type']) {
            case 'image':
                if (! $media_id = WechatMedia::where('media_type', $data['type'])->where('f_id', $data['id'])->pluck('media_id')) {
                    $media = true;
                    $object = StoreImage::where('id', $data['id']);
                }

                break;
            case 'voice':
                if (! $media_id = WechatMedia::where('media_type', $data['type'])->where('f_id', $data['id'])->pluck('media_id')) {
                    $media = true;
                    $object = StoreVoice::where('id', $data['id']);
                }

                break;
            case 'video':
                if (! $media_id = WechatMedia::where('media_type', $data['type'])->where('f_id', $data['id'])->pluck('media_id')) {
                    $media = true;
                    $object = StoreVideo::where('id', $data['id']);
                }

                break;
        }

        if ($media) {
            $path = $object->pluck('url');
            if (! $path) {
                $res['errcode'] = 'error';
                $res['errmsg'] = '文件存放路径不存在!';
            }

            if ($res['errcode'] == 'success') {
                $name = substr($path, strrpos($path, '/') + 1);
                $media = '@' . base_path() . '/public/' . $path . ';filename=' . $name;
                $arr = ['type' => 'addMaterial', 'action' => ['type' => $data['type'], 'media' => $media], 
                'parameter' => ['key' => 'addMaterial', 'value' => ['access_token' => '']]];

                $wa = new WechatAction();
                $result = $wa->send($arr);
                if ($result == '' || $result['errcode'] == 'error') {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = $result['errmsg'];
                } else {
                    $wm = new WechatMedia();
                    $wm->f_id = $data['id'];
                    $wm->media_id = $result['data']['media_id'];
                    $wm->media_type = $data['type'];

                    if (! $wm->save()) {
                        $res['errcode'] = 'error';
                        $res['errmsg'] = '保存微信永久素材 media_id 失败!';
                    } else {
                        $res['data'] = $result['data']['media_id'];
                    }
                }
            }
        } else if (! empty($media_id)) {
            $res['data'] = $media_id;
        } else {
            $res['errcode'] = 'error';
            $res['errmsg'] = '获取media_id失败!';
        }

        return $res;
    }

    // 删除永久素材
    public function delMaterial($res, $data = array())
    {
        if (! $id = WechatMedia::where('media_type', $data['type'])->where('media_id', $data['media_id'])->pluck('id')) {
            $res['errcode'] = 'error';
            $res['errmsg'] = 'media_id不存在!';
        } else {
            $arr = ['type' => 'delMaterial', 'action' => ['media_id' => $data['media_id']], 
            'parameter' => ['key' => 'delMaterial', 'value' => ['access_token' => '']]];

            $wa = new WechatAction();
            $result = $wa->send($arr);
            if ($result == '' || $result['errcode'] == 'error') {
                $res['errcode'] = 'error';
                $res['errmsg'] = $result['errmsg'];
            } else {
                if (! WechatMedia::where('id', $id)->delete()) {
                    $res['errcode'] = 'error';
                    $res['errmsg'] = '删除微信永久素材 media_id 失败!';
                }
            }
        }

        return $res;
    }
}
