<?php
namespace Ecdo\EcdoHulk;

use Ecdo\EcdoHulk\WechatCode;
use Illuminate\Support\Facades\DB;
use Ecdo\Universe\TowerUtils;
use App\Wormhole\WechatAction;

/**
 * 微信组别
 * 
 * @category yunke
 * @package atlas\hell\hulk\src\lib\code
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatCodeUtils
{
    public function __construct()
    {
        // 分页
        $this->page = \Config::get('EcdoSpiderMan::setting')['page'];
    }

    // 二维码用途匹配
    public static function getUse()
    {
        $arr = array(
            0 => '消息回复',
            1 => '卡券核销'
        );
        
        return $arr;
    }

    // 二维码动作匹配
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

	// 获取二维码
    public function getCodePage()
    {
    	$dt = WechatCode::orderBy('updated_at', 'desc')->paginate($this->page);
        if (! empty($dt)) {
            foreach ($dt as $k => $v) {
                if ($v->use == 1) {
                    $dt[$k]->verification = unserialize($v->content);
                }
            }
        }

        return $dt;
    }

    // 搜索二维码
    public function getCodeSearchPage($search)
    {
    	$dt = WechatCode::where('name', 'like', '%'.trim($search).'%')
    	->orderBy('updated_at', 'desc')->paginate($this->page);
        if (! empty($dt)) {
            foreach ($dt as $k => $v) {
                if ($v->use == 1) {
                    $dt[$k]->verification = unserialize($v->content);
                }
            }
        }

        return $dt;
    }

    // 获取单个二维码
    public function getOneCode($id)
    {
        $dt = WechatCode::where('id', $id)->first();
        if ($dt->use == 1) {
            $dt->verification = unserialize($dt->content);
        }

        return $dt;
    }

    // 获取二维码用途模版数据
    public function getUseTpl($data)
    {
    	$res = ['errcode' => 'success', 'msg' => '', 'html' => ''];

    	switch ($data['use']) {
    		case 0:
    			$res['html'] = self::messageTpl($data);
    			break;
    	}

    	return $res;
    }

    // 删除二维码
    public function deleteCode($id)
    {
        $res = true;
        DB::beginTransaction();

        $wc = WechatCode::find($id);
        if (! $wc->delete()) {
            $res = false;
        }

        if ($res) {
            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 二维码微信接口
    public function codeWechatAction($data, $type)
    {
        switch ($type) {
            case 'createQrcode':
                $arr = ['type' => 'createQrcode', 'action' => ['scene_str' => $data['scene_str']], 
                'parameter' => ['key' => 'createQrcode', 'value' => ['access_token' => '']]];
                break;
            case 'getQrcode': // 需要下载时才使用
                $arr = ['type' => 'getQrcode', 'action' => [], 'parameter' => ['key' => 'getQrcode', 
                'value' => ['ticket' => UrlEncode($data['ticket'])]]];
                break;
        }

        $wa = new WechatAction();
        $result = $wa->send($arr);
        
        return $result;
    }

    // 随机生成1-64位不重复字符串
    public function genKey()
    {
    	$re = '';
    	$num = rand(1, 64);
        $s = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

        do {
            while (strlen($re) < $num) {
	            $re .= $s[rand(0, strlen($s) - 1)];
	        }

            $scene_str = WechatCode::where('scene_str', $re)->pluck('scene_str');
        } while ($scene_str);

        return $re;
    }

    // 创建二维码
    public function codeCreate($data)
    {
    	DB::beginTransaction();
        $res = ['errcode' => 'success', 'errmsg' => '', 'data' => ''];
        $data['scene_str'] = self::genKey();

        $res = self::codeWechatAction($data, 'createQrcode');
        if ($res['errcode'] == 'success') {
            $type = self::getType();
            $wc = new WechatCode();

            $wc->scene_str = $data['scene_str'];
            $wc->ticket = $res['data']['ticket'];
            $wc->url = \Config::get('gravity.wechat.url')['getQrcode'] . '?ticket=' 
        	. UrlEncode($res['data']['ticket']);
            $wc->name = trim($data['name']);
            $wc->use = trim($data['use']);
            $wc->action_info = trim($data['action_info']);
            switch ($wc->use) {
                case 0:
                    $wc->type = $type[trim($data['type'])];
                    if ($wc->type == 0) {
                        $data['content'] = $this->filterText($data['content']);
                    }

                    break;
                case 1:
                    $quantity = ! empty($data['content']) ? $data['content'] : 20;
                    $data['content'] = serialize(['quantity' => $quantity, 'inventory' => 0]);
                    break;
            }

            $wc->content = trim($data['content']);
            if (! $wc->save()) {
                $res['errcode'] = 'error';
                $res['errmsg'] = '创建二维码失败!';
            }
        }

        if ($res['errcode'] == 'success') {
            $res['errmsg'] = '创建二维码成功!';

            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 编辑二维码
    public function codeUpdate($data)
    {
        DB::beginTransaction();
        $res = ['errcode' => 'success', 'errmsg' => '', 'data' => ''];
        
        $type = self::getType();
        $wc = WechatCode::find($data['id']);

        $wc->name = trim($data['name']);
        $wc->use = trim($data['use']);
        $wc->action_info = trim($data['action_info']);
        switch ($wc->use) {
            case 0:
                $wc->type = $type[trim($data['type'])];
                if ($wc->type == 0) {
                    $data['content'] = $this->filterText($data['content']);
                }

                break;
            case 1:
                $quantity = ! empty($data['content']) ? $data['content'] : 20;
                $inventory = ! empty($data['inventory']) ? $data['inventory'] : 0;
                $data['content'] = serialize(['quantity' => $quantity, 'inventory' => $inventory]);
                break;
        }

        $wc->content = trim($data['content']);

        if (! $wc->save()) {
            $res['errcode'] = 'error';
            $res['errmsg'] = '编辑二维码失败!';
        }

        if ($res['errcode'] == 'success') {
            $res['errmsg'] = '编辑二维码成功!';

            DB::commit();
        } else {
            DB::rollBack();
        }

        return $res;
    }

    // 消息回复模版
    protected function messageTpl($data)
    {
    	$tp = <<<EOF
        <div class="form-group">%s</div><div class="line line-dashed b-b line-lg pull-in"></div>
EOF;

		$body = '<label class="col-sm-2 control-label">回复内容</label>';
		$body .= '<div class="col-sm-7" style="z-index: 999;">';
		$body .= '<div class="btn-toolbar m-b-sm btn-editor" 
		data-role="editor-toolbar" data-target="#editor">';
		$body .= '<div class="emoji_list" style="display: none;"></div>';
		$body .= '<div class="btn-group">';
		$body .= '<a class="btn btn-default btn-sm wechat_emoji noclick" 
		data-edit="bold" href="javascript:void(0);">';
		$body .= '<i class="fa fa-smile-o"></i>&nbsp; 表情';
		$body .= '</a>';
		$body .= '</div>';
		$body .= '<div class="btn-group">';
		$body .= '<a href="javascript:void(0);" class="btn btn-default btn-sm dropdown-toggle" 
		data-toggle="dropdown">';
		$body .= '<i class="fa fa-file-text"></i>&nbsp; 图文 &nbsp;<b class="caret"></b>';
		$body .= '</a>';
		$body .= '<ul class="dropdown-menu">';
		$body .= '<li>';
		$body .= '<a href="javascript:void(0);" class="bolt-modal-click" data-type="graphics">';
		$body .= '微信图文';
		$body .= '</a>';
		$body .= '</li>';
		$body .= '<li>';
		$body .= '<a href="javascript:void(0);" class="bolt-modal-click" data-type="material">';
		$body .= '高级图文';
		$body .= '</a>';
		$body .= '</li>';
		$body .= '</ul>';
		$body .= '</div>';
		$body .= '<div class="btn-group">';
		$body .= '<a href="javascript:void(0);" class="btn btn-default btn-sm bolt-modal-click" 
		data-type="image">';
		$body .= '<i class="fa fa-picture-o"></i>&nbsp; 图片';
		$body .= '</a>';
		$body .= '<a href="javascript:void(0);" class="btn btn-default btn-sm bolt-modal-click" 
		data-type="voice">';
		$body .= '<i class="fa fa-microphone"></i>&nbsp; 语音';
		$body .= '</a>';
		$body .= '<a href="javascript:void(0);" class="btn btn-default btn-sm bolt-modal-click" 
		data-type="video">';
		$body .= '<i class="fa fa-video-camera"></i>&nbsp; 视频';
		$body .= '</a>';
		$body .= '</div>';
		$body .= '</div>';

        if (! empty($data['id'])) {
            $arr = '';
            $dt = self::getOneCode($data['id']);

            if ($dt['type'] != 0) {
                $type = array_flip(self::getType());
                $amu = new \Ecdo\EcdoSpiderMan\AngelModalUtils();
                $data = ['type' => $type[trim($dt['type'])], 'id' => trim($dt['content'])];
                $arr = $amu->getModalPreview($data);

                $body .= '<div id="editor" style="display:none;overflow:scroll;overflow-x:hidden;height:222px;max-height:600px" 
                class="form-control" contenteditable="true"></div>';
                $body .= '<div id="modal-editor" style="overflow:scroll;overflow-x:hidden;height:222px;max-height:600px" 
                class="form-control">' . $arr['html'] . '</div>';
                $body .= '<span id="modal-editor-data" data-type="' . $arr['type'] . '" data-id="' . $arr['id'] . '" style="display:none;"></span>';
            } else {
                $arr = $dt['content'];

                $body .= '<div id="editor" style="overflow:scroll;overflow-x:hidden;height:222px;max-height:600px" 
                class="form-control" contenteditable="true">' . $arr . '</div>';
                $body .= '<div id="modal-editor" style="display:none;overflow:scroll;overflow-x:hidden;height:222px;max-height:600px" 
                class="form-control"></div>';
                $body .= '<span id="modal-editor-data" data-type="" data-id="" style="display:none;"></span>';
            }
        } else {
            $body .= '<div id="editor" style="overflow:scroll;overflow-x:hidden;height:222px;max-height:600px" 
            class="form-control" contenteditable="true"></div>';
            $body .= '<div id="modal-editor" style="display:none;overflow:scroll;overflow-x:hidden;height:222px;max-height:600px" 
            class="form-control"></div>';
            $body .= '<span id="modal-editor-data" data-type="" data-id="" style="display:none;"></span>';
        }

		$body .= '</div>';

		$html = $body;

        return sprintf($tp, $html);
    }

    // 过滤文本中的表情
    public function filterText($text)
    {
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

    // 二维码微信被动回复
    public function codeSend($data = [])
    {
        $str = '';
        $res = ['errcode' => 'success', 'msg' => '', 'data' => ''];
        $str = WechatCode::where('ticket', $data['ticket'])->where('scene_str', $data['eventKey'])->first();

        if (! empty($str)) {
            $type = array_flip(self::getType());
            $wa = new WechatAction();

            switch ($str['use']) {
                case 0:
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
                            $arr = $wa->media(['action' => 'add', 'type' => 'image', 'id' => trim($str['content'])]);
                            if (! empty($arr['data'])) {
                                $res['data'] = ['passive' => true, 'type' => 'sendMsgImage', 
                                'action' => ['touser' => $data['fromUser'], 'fromuser' => $data['toUser'], 'media_id' => $arr['data']]];
                            }

                            break;
                        case 'voice':
                            $arr = $wa->media(['action' => 'add', 'type' => 'voice', 'id' => trim($str['content'])]);
                            if (! empty($arr['data'])) {
                                $res['data'] = ['passive' => true, 'type' => 'sendMsgVoice', 
                                'action' => ['touser' => $data['fromUser'], 'fromuser' => $data['toUser'], 'media_id' => $arr['data']]];
                            }

                            break;
                        case 'video':
                            $arr = $wa->media(['action' => 'add', 'type' => 'video', 'id' => trim($str['content'])]);
                            if (! empty($arr['data'])) {
                                $res['data'] = ['passive' => true, 'type' => 'sendMsgVideo', 
                                'action' => ['touser' => $data['fromUser'], 'fromuser' => $data['toUser'], 'media_id' => $arr['data']]];
                            }

                            break;
                    }

                    break;
                case 1:
                    $arr = \Crypt::encrypt($str['id'] . '@@@' . $data['fromUser']);
                    $registerUrl = action('\Ecdo\EcdoIronMan\CouponsSite@verification', [$data['guid'], $arr]);
                    $url = $wa->oauth2Authorize($registerUrl);
                    $content = '<a href="' . $url . '">卡券核销登录校验</a>';

                    $res['data'] = ['passive' => true, 'type' => 'sendMsgText', 
                    'action' => ['touser' => $data['fromUser'], 'fromuser' => $data['toUser'], 'content' => $content]];
                    break;
            }
        }

        return $res;
    }
}
