<?php
namespace Ecdo\EcdoHulk;

use Ecdo\EcdoHulk\WechatGraphic;
use Ecdo\EcdoHulk\WechatMedia;
use Illuminate\Support\Facades\DB;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoSuperMan\StoreImage;
use App\Wormhole\WechatAction;

/**
 * 微信普通图文
 * 
 * @category yunke
 * @package atlas\hell\hulk\src\lib\graphics
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatGraphicsUtils
{
    public function __construct()
    {
        // 分页
        $this->page = \Config::get('EcdoSpiderMan::setting')['page'];
        $this->guid = TowerUtils::fetchTowerGuid();
    }

    // 获取普通图文列表
    public function getGraphicsPage()
    {
        $dt = WechatGraphic::where('f_id', 0)->orderBy('updated_at', 'desc')->paginate($this->page);
        foreach ($dt as $k => $v) {
            $dt[$k]->item = WechatGraphic::where('f_id', $v->id)->get();
        }

        return $dt;
    }

    // 搜索普通图文
    public function getSearchGraphicsPage($search)
    {
        $dt = WechatGraphic::where('f_id', 0)->where('title', 'like', '%'.trim($search).'%')->paginate($this->page);
        foreach ($dt as $k => $v) {
            $dt[$k]->item = WechatGraphic::where('f_id', $v->id)->get();
        }

        return $dt;
    }

    // 筛选普通图文
    public function getFilterGraphicsPage($filter)
    {
        $arr = [];
        $type = '';

        $dt = WechatGraphic::where('f_id', 0)->orderBy('updated_at', 'desc');
        if (count($filter) > 0) {
            foreach ($filter as $k => $v) {
                if ($k == 'title') {
                    $dt = $dt->where($k, 'like', '%'.trim($v).'%');
                } elseif ($k == 'type') {
                    $dt = $dt->where($k, $v);
                }
            }
        }

        $dt = $dt->paginate($this->page);
        foreach ($dt as $k => $v) {
            $dt[$k]->item = WechatGraphic::where('f_id', $v->id)->get();
        }

        return $dt;
    }

    // 获取单条图文
    public function getOneGraphic($id)
    {
        $dt = WechatGraphic::where('id', $id)->first();
        if ($dt) {
            $dt = $dt->toArray();
            $dt['img_url'] = StoreImage::where('id', $dt['store_image_id'])->pluck('url');
        } else {
            $dt = [];
        }
        
        return $dt;
    }

    // 查看普通图文
    public function getOneGraphics($graphics_id)
    {
        $dt = WechatGraphic::where('id', $graphics_id)->where('f_id', 0)->first();
        if ($dt) {
            $dt = $dt->toArray();
            $dt['img_url'] = StoreImage::where('id', $dt['store_image_id'])->pluck('url');
            $dt['item'] = WechatGraphic::where('f_id', $dt['id'])->get();

            if (count($dt['item']) > 0) {
                foreach ($dt['item'] as $k => $v) {
                    $dt['item'][$k]['img_url'] = StoreImage::where('id', $v['store_image_id'])->pluck('url');
                }
            }
        } else {
            $dt = [];
        }
        
        return $dt;
    }

    // 创建或编辑普通单图文
    public function crupGraphic($data)
    {
        $res = ['err' => 'success'];
        DB::beginTransaction();

        if (! empty($data['id'])) {
            $wg = WechatGraphic::find($data['id']);
            $act = '编辑';
        } else {
            $wg = new WechatGraphic();
            $wg->type = '1';
            $act = '创建';
        }

        $wg->title = $data['title'];
        $wg->author = $data['author'];
        $wg->digest = $data['digest'];
        $wg->show_cover_pic = $data['show_cover_pic'];
        $wg->content = trim($data['content']);
        $wg->content_source_url = $data['content_source_url'];
        $wg->store_image_id = $data['image_url'];

        if (! $wg->save()) {
            $res['err'] == 'error';
        }

        if ($res['err'] == 'success') {
            $res['msg'] = $act . '图文完成';

            DB::commit();
        } else {
            $res['msg'] = $act . '图文失败';

            DB::rollBack();
        }

        return $res;
    }

    // 创建或编辑普通多图文
    public function crupGraphics($data, $file = '')
    {
        $dt = true;
        $res = ['errcode' => 'success'];
        DB::beginTransaction();

        if ($res['errcode'] == 'success') {
            if (! empty($data['f_id'])) {
                $act = '编辑';

                // 获取需要删除的对象
                $del = $data['u_id'];
                foreach ($del as $k => $v) {
                    if ($v == $data['f_id']) {
                        unset($del[$k]);
                    } elseif ($v == 0) {
                        unset($del[$k]);
                    }
                }

                if (count($del) > 0) {
                    if (! WechatGraphic::where('f_id', $data['f_id'])->whereNotIn('id', $del)->delete()) {
                        $res['errcode'] == 'error';
                    }
                }

                if ($res['errcode'] == 'success') {
                    for ($i = 0; $i < count($data['u_id']); $i++) {
                        if ($data['u_id'][$i] > 0) {
                            $wg = WechatGraphic::find($data['u_id'][$i]);
                        } else {
                            $wg = new WechatGraphic();
                        }

                        $wg->title = $data['title'][$i];
                        $wg->author = $data['author'][$i];
                        $wg->show_cover_pic = $data['show_cover_pic'][$i];
                        $wg->content_source_url = $data['content_source_url'][$i];
                        $wg->content = $data['content'][$i];
                        $wg->store_image_id = $data['image_url'][$i];
                        
                        if ($i > 0) {
                            $wg->f_id = $data['f_id'];
                        }

                        if (! $wg->save()) {
                            $dt = false;
                            $res['errcode'] == 'error';
                        }

                        if (! $dt) {
                            break;
                        }
                    }
                }
            } else {
                $act = '创建';
                $f_id = 0;

                for ($i = 0; $i < count($data['title']); $i++) {
                    $wg = new WechatGraphic();

                    $wg->title = $data['title'][$i];
                    $wg->author = $data['author'][$i];
                    $wg->show_cover_pic = $data['show_cover_pic'][$i];
                    $wg->content_source_url = $data['content_source_url'][$i];
                    $wg->content = $data['content'][$i];
                    $wg->store_image_id = $data['image_url'][$i];
                    
                    if ($i > 0) {
                        $wg->f_id = $f_id;
                    } elseif ($i == 0) {
                        $wg->type = '2';
                    }

                    if (! $wg->save()) {
                        $dt = false;
                        $res['errcode'] == 'error';
                    } else {
                        if ($i == 0) {
                            $f_id = $wg->id;
                        }
                    }

                    if (! $dt) {
                        break;
                    }
                }
            }
        }

        if ($res['errcode'] == 'success') {
            $res['errmsg'] = $act . '普通图文成功!';

            DB::commit();
        } else {
            $res['errmsg'] = $act . '普通图文失败!';

            DB::rollBack();
        }

        return $res;
    }

    // 删除普通图文
    public function deleteGraphics($id)
    {
        $res = true;
        DB::beginTransaction();

        if ($media_id = WechatMedia::where('f_id', $id)->where('media_type', 'graphics')->pluck('media_id')) {
            $data = ['action' => 'del', 'media_id' => $media_id, 'type' => 'graphics'];

            $wa = new WechatAction();
            $result = $wa->media($data);

            if ($result['errcode'] == 'error') {
                $res = false;
            }
        }

        if ($res) {
            $wg = WechatGraphic::find($id);
            if (! $wg->delete()) {
                $res = false;
            } else {
                if (WechatGraphic::where('f_id', $id)->count() > 0) {
                    $wgs = new WechatGraphic();
                    if (! $wgs->where('f_id', $id)->delete()) {
                        $res = false;
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

    // 获取图文发送模版
    public function graphicsTpl($data = [])
    {
        $arr = [];
        $articles = [];
        $graphics[] = self::getOneGraphics($data['id']);
        
        if (! empty($graphics)) {
            foreach ($graphics as $k => $v) {
                $articles[$k]['title'] = $v['title'];
                $articles[$k]['url'] = action('\Ecdo\EcdoHulk\WechatSite@graphics', 
                [$data['guid'], $v['id']]);

                $picurl = StoreImage::where('id', $v['store_image_id'])->pluck('url');
                $articles[$k]['picurl'] = asset($picurl);

                if (count($graphics[$k]['item']) > 0) {
                    foreach ($graphics[$k]['item'] as $ks => $vs) {
                        $articles[$ks + 1]['title'] = $vs['title'];
                        $articles[$ks + 1]['url'] = action('\Ecdo\EcdoHulk\WechatSite@graphics', 
                        [$data['guid'], $vs['id']]);

                        $picurl = StoreImage::where('id', $vs['store_image_id'])->pluck('url');
                        $articles[$ks + 1]['picurl'] = asset($picurl);
                    }
                }
            }

            if (! empty($articles)) {
                $arr['articleCount'] = count($articles);

                if (! empty($data['type']) && $data['type'] == 'passive') {
                    $item = '';
                    $tpl = self::graphicsXml();

                    foreach ($articles as $k => $v) {
                        $item .= sprintf($tpl, $v['title'], $v['url'], $v['picurl']);
                    }

                    $articles = $item;
                }

                $arr['articles'] = $articles;
            }
        }

        return $arr;
    }

    // 获取图文模版
    private function graphicsXml()
    {
        return '<item>
                <Title>%s</Title>
                <Url>%s</Url>
                <PicUrl>%s</PicUrl>
                <Description></Description>
                </item>';
    }
}
