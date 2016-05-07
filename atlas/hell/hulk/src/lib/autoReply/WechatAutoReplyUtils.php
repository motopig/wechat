<?php
namespace Ecdo\EcdoHulk;

use Ecdo\EcdoHulk\WechatAutoReply;
use Ecdo\EcdoHulk\WechatAutoReplyKeyword;
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
class WechatAutoReplyUtils
{
    public function __construct()
    {
        // 分页
        $this->page = \Config::get('EcdoSpiderMan::setting')['page'];
    }

    // 自动回复类型匹配
    public static function getType()
    {
        $arr = array(
            'text' => 0, // 文本
            'graphics' => 1, // 微信图文
            'material' => 2, // 高级图文
            'image' => 3, // 图片
            'voice' => 4, // 语音
            'video' => 5 // 视频
        );
        
        return $arr;
    }

	// 获取自动回复
    public function getAutoReplyPage()
    {
        $dt = WechatAutoReply::orderBy('updated_at', 'desc')->paginate($this->page);
        foreach ($dt as $k => $v) {
            switch ($v->type) {
                case '1':
                    $wgi = new \Ecdo\EcdoHulk\WechatGraphicsUtils();
                    $data = $wgi->getOneGraphics($v->content);
                    break;
                case '2':
                    $data = [];
                    break;
                case '3':
                    $siu = new \Ecdo\EcdoSuperMan\StoreImageUtils();
                    $data = $siu->getOneImage($v->content);
                    break;
                case '4':
                    $svu = new \Ecdo\EcdoSuperMan\StoreVoiceUtils();
                    $data = $svu->getOneVoice($v->content);
                    break;
                case '5':
                    $svu = new \Ecdo\EcdoSuperMan\StoreVideoUtils();
                    $data = $svu->getOneVideo($v->content);
                    break;
                default:
                    $data = [];
                    break;
            }

            $dt[$k]->preview = $data;
            $dt[$k]->item = WechatAutoReplyKeyword::where('auto_reply_id', $v->id)->get();
        }

        return $dt;
    }

    // 搜索自动回复
    public function getAutoReplySearchPage($search)
    {
        $dt = WechatAutoReply::where('name', 'like', '%'.trim($search).'%')->orderBy('updated_at', 'desc')->paginate($this->page);
        foreach ($dt as $k => $v) {
            switch ($v->type) {
                case '1':
                    $wgi = new \Ecdo\EcdoHulk\WechatGraphicsUtils();
                    $data = $wgi->getOneGraphics($v->content);
                    break;
                case '2':
                    $data = [];
                    break;
                case '3':
                    $siu = new \Ecdo\EcdoSuperMan\StoreImageUtils();
                    $data = $siu->getOneImage($v->content);
                    break;
                case '4':
                    $svu = new \Ecdo\EcdoSuperMan\StoreVoiceUtils();
                    $data = $svu->getOneVoice($v->content);
                    break;
                case '5':
                    $svu = new \Ecdo\EcdoSuperMan\StoreVideoUtils();
                    $data = $svu->getOneVideo($v->content);
                    break;
                default:
                    $data = [];
                    break;
            }

            $dt[$k]->preview = $data;
            $dt[$k]->item = WechatAutoReplyKeyword::where('auto_reply_id', $v->id)->get();
        }

        return $dt;
    }

    // 获取单条自动回复
    public function getAutoReplyOnePage($id)
    {
        $dt = WechatAutoReply::where('id', $id)->first();
        if ($dt) {
            $dt = $dt->toArray();

            if ($dt['type'] != '0') {
                $type = array_flip(self::getType());
                $amu = new \Ecdo\EcdoSpiderMan\AngelModalUtils();
                $data = ['type' => $type[trim($dt['type'])], 'id' => trim($dt['content'])];
                $dt['preview'] = $amu->getModalPreview($data);
            } else {
                $dt['preview'] = [];
            }

            $dt['item'] = WechatAutoReplyKeyword::where('auto_reply_id', $dt['id'])->get();
        } else {
            $dt = [];
        }

        return $dt;
    }

    // 获取创建中自动回复关键字
    public function autoReplykeywordValidate($data)
    {
        $res = ['err' => 'success', 'msg' => ''];

        foreach ($data as $k => $v) {
            $keyword = WechatAutoReplyKeyword::where('keyword', $v)->pluck('keyword');

            if ($keyword) {
                $res['err'] = 'error';
                $res['msg'] = '关键字：' . $keyword . ' 已经存在!';
                break;
            }
        }

        return $res;
    }

    // 获取编辑中自动回复关键字
    public function autoReplykeywordValidateUpdate($id, $data)
    {
        // 获取原有关键字集合
        $old_keyword = WechatAutoReplyKeyword::where('auto_reply_id', $id)->lists('keyword');
        
        // 获取是否有删除的差集
        $minus = [];
        $minus_data = array_diff($old_keyword, $data);
        if (count($minus_data) > 0) {
            foreach ($minus_data as $k => $v) {
                $minus[] = WechatAutoReplyKeyword::where('keyword', $v)->pluck('id');
            }
        }

        unset($minus_data);
        
        // 获取是否有新增的差集
        $plus = [];
        $plus_data = array_diff($data, $old_keyword);

        $res = ['err' => 'success', 'msg' => ''];
        if (count($plus_data) > 0) {
            foreach ($plus_data as $k => $v) {
                $keyword = WechatAutoReplyKeyword::where('keyword', $v)->pluck('keyword');

                if ($keyword) {
                    $res['err'] = 'error';
                    $res['msg'] = '关键字：' . $keyword . ' 已经存在!';
                    break;
                } else {
                    $plus[] = $v;
                }
            }
        }

        unset($plus_data);

        $res['keyword'] = array(
            'minus' => $minus,
            'plus' => $plus
        );

        return $res;
    }

    // 创建自动回复处理
    public function autoReplyCreate($data, $keyword)
    {
        DB::beginTransaction();
        $res = ['err' => 'success', 'msg' => ''];
        
        if ($data['concern'] == '1' && $id = WechatAutoReply::where('concern', '1')->pluck('id')) {
            $warc = WechatAutoReply::find($id);
            $warc->concern = '0';

            if (! $warc->save()) {
                $res['err'] = 'error';
                $res['msg'] = '设置关注自动回复失败!';
            }
        }

        if ($res['err'] == 'success') {
            $type = self::getType();
            $war = new WechatAutoReply();

            $war->name = trim($data['name']);
            $war->type = $type[trim($data['type'])];
            
            if ($war->type == 0) {
                $data['content'] = $this->filterText($data['content']);
            }

            $war->content = trim($data['content']);
            $war->concern = trim($data['concern']);

            if (! $war->save()) {
                $res['err'] = 'error';
                $res['msg'] = '设置自动回复失败!';
            } else {
                $id = $war->id;

                foreach ($keyword as $k => $v) {
                    $wark = new WechatAutoReplyKeyword();

                    $wark->auto_reply_id = $id;
                    $wark->keyword = trim($v);

                    if (! $wark->save()) {
                        $res['err'] = 'error';
                        $res['msg'] = '设置自动回复关键字失败!';

                        break;
                    }
                }
            }
        }

        if ($res['err'] == 'success') {
            $res['msg'] = '自动回复创建成功!';

            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 编辑自动回复处理
    public function autoReplyUpdate($data, $keyword)
    {
        DB::beginTransaction();
        $res = ['err' => 'success', 'msg' => ''];
        
        if ($data['concern'] == '1' && $id = WechatAutoReply::where('concern', '1')->pluck('id')) {
            $warc = WechatAutoReply::find($id);
            $warc->concern = '0';

            if (! $warc->save()) {
                $res['err'] = 'error';
                $res['msg'] = '设置关注自动回复失败!';
            }
        }

        if ($res['err'] == 'success') {
            $type = self::getType();
            $war = WechatAutoReply::find($data['id']);
            $war->name = trim($data['name']);
            $war->type = $type[trim($data['type'])];

            if ($war->type == 0) {
                $data['content'] = $this->filterText($data['content']);
            }

            $war->content = trim($data['content']);
            $war->concern = trim($data['concern']);

            if (! $war->save()) {
                $res['err'] = 'error';
                $res['msg'] = '设置自动回复失败!';
            } else {
                if (count($keyword['minus']) > 0) {
                    if (! WechatAutoReplyKeyword::whereIn('id', $keyword['minus'])->delete()) {
                        $res['err'] = 'error';
                        $res['msg'] = '删除关键字失败!';
                    }
                }

                if ($res['err'] == 'success') {
                    if (count($keyword['plus']) > 0) {
                       foreach ($keyword['plus'] as $k => $v) {
                            $wark = new WechatAutoReplyKeyword();

                            $wark->auto_reply_id = $data['id'];
                            $wark->keyword = trim($v);

                            if (! $wark->save()) {
                                $res['err'] = 'error';
                                $res['msg'] = '设置自动回复关键字失败!';

                                break;
                            }
                        }
                    }
                }
            }
        }

        if ($res['err'] == 'success') {
            $res['msg'] = '自动回复编辑成功!';

            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 删除自动回复
    public function deleteAutoReply($id)
    {
        DB::beginTransaction();
        $res = true;

        if (! $id = WechatAutoReply::where('id', $id)->pluck('id')) {
            $res = false;
        } else {
            $war = WechatAutoReply::find($id);

            if (! $war->delete()) {
                $res = false;
            } else {
                if (! WechatAutoReplyKeyword::where('auto_reply_id', $id)->delete()) {
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

    // 关注自动回复设置
    public function concernAutoReplyDis($data)
    {
        DB::beginTransaction();
        $res = ['err' => 'success', 'msg' => ''];

        if ($data['concern'] == '1') {
            if ($id = WechatAutoReply::where('concern', '1')->pluck('id')) {
                $war = WechatAutoReply::find($id);
                $war->concern = '0';

                if (! $war->save()) {
                    $res['err'] = 'error';
                    $res['msg'] = '关注回复变更失败!';
                }
            }
        }

        if ($res['err'] == 'success') {
            $war = WechatAutoReply::find($data['id']);
            $war->concern = $data['concern'];

            if (! $war->save()) {
                $res['err'] = 'error';
                $res['msg'] = '关注回复配置失败!';
            }
        }

        if ($res['err'] == 'success') {
            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 关键字匹配设置
    public function matchingAutoReplyDis($data)
    {
        DB::beginTransaction();
        $res = ['err' => 'success', 'msg' => ''];

        $wark = WechatAutoReplyKeyword::find($data['id']);
        $wark->matching = $data['matching'];

        if (! $wark->save()) {
            $res['err'] = 'error';
            $res['msg'] = '关键字匹配设置失败!';
        }

        if ($res['err'] == 'success') {
            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 自动回复微信被动回复
    public function autoReplySend($data = [])
    {
        $str = '';
        $res = ['errcode' => 'success', 'msg' => '', 'data' => ''];

        if ($data['msgType'] == 'event') {
            $str = WechatAutoReply::where('concern', '1')->first();
        } else {
            if (! $id = WechatAutoReplyKeyword::where('matching', '1')
                ->where('keyword', trim($data['content']))->pluck('auto_reply_id')) {
                $id = WechatAutoReplyKeyword::where('matching', '0')
                ->where('keyword', 'like', '%' . trim($data['content']) . '%')->pluck('auto_reply_id');
            }

            $str = WechatAutoReply::where('id', $id)->first();
        }

        if (! empty($str)) {
            $type = array_flip(self::getType());
            
            switch ($type[$str['type']]) {
                case 'text':
                    $res['data'] = ['passive' => true, 'type' => 'sendMsgText', 
                    'action' => ['touser' => $data['fromUser'], 'fromuser' => $data['toUser'], 'content' => $str['content']]];
                    break;
                case 'graphics':
                    $wgu = new \Ecdo\EcdoHulk\WechatGraphicsUtils();
                    $arr = $wgu->graphicsTpl(['id' => trim($str['content']), 'guid' => $data['guid'], 'type' => 'passive']);
                    
                    if (! empty($arr)) {
                        $res['data'] = ['passive' => true, 'type' => 'sendMsgNews', 
                        'action' => ['touser' => $data['fromUser'], 'fromuser' => $data['toUser'], 
                        'articleCount' => $arr['articleCount'], 'articles' => $arr['articles']]];
                    }

                    break;
                case 'material':
                    break;
                case 'image':
                    $wa = new WechatAction();
                    $arr = $wa->media(['action' => 'add', 'type' => 'image', 'id' => trim($str['content'])]);
                    if (! empty($arr['data'])) {
                        $res['data'] = ['passive' => true, 'type' => 'sendMsgImage', 
                        'action' => ['touser' => $data['fromUser'], 'fromuser' => $data['toUser'], 'media_id' => $arr['data']]];
                    }

                    break;
                case 'voice':
                    $wa = new WechatAction();
                    $arr = $wa->media(['action' => 'add', 'type' => 'voice', 'id' => trim($str['content'])]);
                    if (! empty($arr['data'])) {
                        $res['data'] = ['passive' => true, 'type' => 'sendMsgVoice', 
                        'action' => ['touser' => $data['fromUser'], 'fromuser' => $data['toUser'], 'media_id' => $arr['data']]];
                    }

                    break;
                case 'video':
                    $wa = new WechatAction();
                    $arr = $wa->media(['action' => 'add', 'type' => 'video', 'id' => trim($str['content'])]);
                    if (! empty($arr['data'])) {
                        $res['data'] = ['passive' => true, 'type' => 'sendMsgVideo', 
                        'action' => ['touser' => $data['fromUser'], 'fromuser' => $data['toUser'], 'media_id' => $arr['data']]];
                    }

                    break;
            }
        }

        return $res;
    }

    //过滤文本中的表情
    public function filterText($text){
        return strip_tags(preg_replace_callback(
            '/<img[^>]*data="([^"]*)">/',
            function ($matches)
            {
                if(is_array($matches)){
                    $b = explode('"', $matches[0]);
                    return $b[3];
                }
            },
            $text));
    }

}
