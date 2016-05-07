<?php
namespace Ecdo\EcdoHulk;

use Ecdo\EcdoHulk\WechatMessage;
use Ecdo\EcdoSuperMan\StoreImage;
use Ecdo\EcdoSuperMan\StoreVoice;
use Ecdo\EcdoSuperMan\StoreVideo;
use Ecdo\EcdoHulk\WechatCode;
use Ecdo\EcdoHulk\WechatMemberUtils;
use Ecdo\EcdoHulk\WechatMenuUtils;
use Illuminate\Support\Facades\DB;
use Ecdo\Universe\TowerUtils;
use App\Wormhole\WechatAction;

/**
 * 微信用户消息公共类
 *
 * @category yunke
 * @package atlas\hell\hulk\src\lib\group
 * @author Dev<dev@no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class WechatMessageUtils
{
    public function __construct()
    {
        // 分页
        $this->page = 10;
        $this->guid = TowerUtils::fetchTowerGuid();
        $this->per_member_num = 30;
        $this->time = time() - 48 * 3600;//48小时期限
    }

    // 用户消息类型匹配
    public static function getMsgType()
    {  //0=>text 1=>image 2=>voice 3=>video 4=>shortvideo 5=>location 6=>link 7=>graphics
        $arr = array(
            'text' => 0, // 文本
            'image' => 1, // 图片
            'voice' => 2, // 语音
            'video' => 3, // 视频
            'shortvideo' => 4, // 小视频
            'location' => 5, // 地址
            'link' => 6, // 链接
            'graphics' => 7, //图文 微信图文 高级图文
        );

        return $arr;
    }

    // 用户事件类型匹配
    public static function getEventType()
    {
        $arr = array(
            'subscribe' => 0, //
            'unsubscribe' => 1, //
            'scan' => 2, //
            'location' => 3, //
            'click' => 4, //
            'view' => 5, //
        );

        return $arr;
    }

    public static function mediaType(){
        $arr = array(
            'image' => 'StoreImage', // 图片
            'voice' => 'StoreVoice', // 语音
            'video' => 'StoreVideo', // 视频
            'shortvideo' => 'StoreVideo', // 小视频=
        );

        return $arr;
    }

    public function getPreData($dead_line = ''){
        $TablePrefix = \Schema::getConnection()->getTablePrefix();

        $table = $TablePrefix.$this->guid.'_wechat_message';
        if($dead_line == 'true'){
            $sql = 'select e.* from
            (select id, member_id from `'.$table.'`
            where `op_id` < 1

            and `cat` = 0
            ORDER by create_time desc)
            as e
            group by e.member_id';
        }elseif($dead_line == 'ignore'){
            $sql = 'select e.* from
            (select id, member_id from `'.$table.'`
            where `op_id` < 1
            and `create_time` > '.$this->time.'
            and `cat` = 0
            ORDER by create_time desc)
            as e
            group by e.member_id';
        }elseif($dead_line == 'auto'){//查询自动触发的
            $sql = 'select e.* from
            (select id, member_id from `'.$table.'`
            where `op_id` < 1
            and `cat` = 1
            ORDER by create_time desc)
            as e
            group by e.member_id';
        }else{
            $sql = 'select e.* from
            (select id, member_id from `'.$table.'`
            where `op_id` < 1
            and `cat` = 0
            ORDER by create_time desc)
            as e
            group by e.member_id';
        }

        $dt = DB::select($sql);

//        $sqls = DB::getQueryLog();
//        $query = end($sqls);
//        echo "<pre>";
//        print_r($query);
//        exit;
        //组合信息数据
        $Ids = array();
        foreach($dt as $v){
            $Ids[$v->member_id] = $v->id;
        }

        return $Ids;
    }

    public function getFinData($Ids,$concern = ''){
         $st = DB::table($this->guid . '_wechat_message')->join($this->guid . '_wechat_member', $this->guid . '_wechat_member.id', '=', $this->guid . '_wechat_message.member_id')
            ->select($this->guid . '_wechat_message.*', $this->guid . '_wechat_member.concern')
            ->whereIn($this->guid . '_wechat_message.id',  $Ids);
        if($concern == 'follow'){
            $st = $st->where($this->guid . '_wechat_member.concern', 'follow');
        }
        if($concern == 'danger'){
            $st = $st->where($this->guid . '_wechat_message.create_time', '<', $this->time);
        }
        if($concern == 'ignore'){
            $st = $st->where($this->guid . '_wechat_message.create_time', '>', $this->time);
        }

            $st = $st->orderBy($this->guid . '_wechat_message.create_time', 'desc')
            ->paginate($this->page);

         return $st;
    }

    public function object_to_array($obj){
        $_arr = is_object($obj)? get_object_vars($obj) :$obj;
        $arr = array();
        foreach ($_arr as $key => $val){
            $val=(is_array($val)) || is_object($val) ? $this->object_to_array($val) :$val;
            $arr[$key] = $val;
        }
        return $arr;
    }

    public function combData (&$st, $toArray = ''){
        $wm = new WechatMemberUtils();

        foreach($st as $k => &$v){
            $memberInfo = $wm->getOneMember($v->member_id);
            $v->name = $memberInfo->name;
            $v->head = $memberInfo->head;
            $v->replay =  WechatMessage::where('op_id', '>', 0)->where('create_time', '>', $v->create_time)->where('member_id', $v->member_id)->orderBy('create_time', 'desc')->first();
        }
    }

    // 获取用户消息
    public function getMessagePage()
    {
        $Ids = $this->getPreData('no');
        if(empty($Ids)){
            return array();
        }
        $st = $this->getFinData($Ids, 'follow');

        $this->combData($st);

        return $st;
    }


    // 获取单个用户所有消息
    public function getMemberMessage($id, $page = 0, $cat = 'all')
    {
        if($page){
            $page = $page * $this->per_member_num;
        }
        if($cat == 'automatic'){
            $dt = WechatMessage::where('member_id', $id)->where('cat',1)->orderBy('create_time', 'desc')->skip($page)->take($this->per_member_num)->get();
        }else{
            $dt = WechatMessage::where('member_id', $id)->where('cat',0)->orderBy('create_time', 'desc')->skip($page)->take($this->per_member_num)->get();
        }


        if ($dt) {
            $dt = array_reverse($dt->toArray());
            //处理显示消息类型

            foreach($dt as $k => &$v){
                if($dt[$k]['mold'] == 0){
                    $type =  array_flip(self::getMsgType());
                }else{
                    $type =  array_flip(self::getEventType());
                }
                $dt[$k]['type'] = $type[$dt[$k]['type']];
                $dt[$k]['create_time'] = date('Y-m-d H:i:s' , $dt[$k]['create_time']);
            }
        } else {
            $dt = [];
        }

        return $dt;
    }


    //保存并发送消息给用户
    public function saveMessage($data){

        $wm = new WechatMessage();
        //文本
        //检查时间
        $checkLast = WechatMessage::where('member_id', $data['user_id'])->where('create_time', '>', $this->time)->get()->toArray();

        if(empty($checkLast)){
            echo json_encode(array('status'=>'当前用户无法发送消息'));exit;
        }
        if(isset($data['content'])){
            $wm->content = $data['content'];
            $wm->type = 'text';
            $data['type'] = 'text';
        }else{
            $wm->type = $data['type'];
            $wm->content = $data['message_id'];
        }

        $wm->member_id = $data['user_id'];
        $wm->op_id = 1;
        $wm->mold = 0;
        $wm->type = self::getMsgType()[$data['type']];
        $wm->cat = 0;
        $wm->create_time = time();
        $wm->disabled = 'false';

        if($wm->save()){
            //wechat 发送消息接口
            $result = $this->sendMsg($data);
            if($result['errcode'] == 'success'){
                echo json_encode(array('status'=>'success'));exit;
            }else{
                echo json_encode(array('status'=>$result['errmsg']));exit;
            }

        }else{
            echo json_encode(array('status'=>$result['errmsg']));exit;
        }
    }


    //wechat 发送消息接口
    public function sendMsg($data){
        $wmu = new WechatMemberUtils();
        //获取用户的OPENID
        $member_info = $wmu->getOneMember($data['user_id']);
        $wa = new WechatAction();
        //调用微信接口发送信息给用户
        $send =array();

        switch ($data['type']){

            case 'text' :
                //调用微信接口发送信息给用户
                //emoji 替换
                $data['content'] = preg_replace_callback(
                    '/<img[^>]*data="([^"]*)">/',
                    function ($matches)
                    {
                        if(is_array($matches)){
                            $b = explode('"', $matches[0]);
                            return $b[3];
                        }
                    },
                    $data['content']);
                $arr = [
                    'type' => 'sendMsgText',
                    'action' => [
                        'touser' => $member_info->open_id,
                        'content' => $data['content']
                    ],
                    'parameter' => [ // 发送地址 (被动推送调用时可不填)
                        'key' => 'sendMsg', // 接口地址
                        'value' => [
                            'access_token' => '' // 接口参数
                        ]
                    ],
                ];

                $result = $wa->send($arr);
                break;
            case 'video' :
                $arr = $wa->media(['action' => 'add', 'type' => 'video', 'id' => trim($data['message_id'])]);
                if (! empty($arr['data'])) {
                    $send = [
                        'type' => 'sendMsgVideo',
                        'action' => [
                            'touser' => $member_info->open_id,
                            'media_id' => $arr['data'],
                            'title' => '',
                            'description' => ''
                        ],
                        'parameter' => [ // 发送地址 (被动推送调用时可不填)
                            'key' => 'sendMsg', // 接口地址
                            'value' => [
                                'access_token' => '' // 接口参数
                            ]
                        ],
                    ];

                    $result = $wa->send($send);
                }

                break;
            case 'voice' :
                $arr = $wa->media(['action' => 'add', 'type' => 'voice', 'id' => trim($data['message_id'])]);
                $send = [
                    'type' => 'sendMsgVoice',
                    'action' => [
                        'touser' => $member_info->open_id,
                        'media_id' => $arr['data']
                    ],
                    'parameter' => [ // 发送地址 (被动推送调用时可不填)
                        'key' => 'sendMsg', // 接口地址
                        'value' => [
                            'access_token' => '' // 接口参数
                        ]
                    ],
                ];
                $result = $wa->send($send);
                break;
            case 'graphics' :
                $wgu = new \Ecdo\EcdoHulk\WechatGraphicsUtils();
                $arr = $wgu->graphicsTpl(['id' => trim($data['message_id']), 'guid' => $this->guid, 'type' => '']);
                $send = [
                    'type' => 'sendMsgNews',
                    'action' => [
                        'touser' => $member_info->open_id,
                        'articles' => $arr['articles']
                    ],
                    'parameter' => [ // 发送地址 (被动推送调用时可不填)
                        'key' => 'sendMsg', // 接口地址
                        'value' => [
                            'access_token' => '' // 接口参数
                        ]
                    ],
                ];
                $result = $wa->send($send);
                break;
            case 'image' :
                $arr = $wa->media(['action' => 'add', 'type' => 'image', 'id' => trim($data['message_id'])]);
                $send = [
                    'type' => 'sendMsgImage',
                    'action' => [
                        'touser' => $member_info->open_id,
                        'media_id' => $arr['data']
                    ],
                    'parameter' => [ // 发送地址 (被动推送调用时可不填)
                        'key' => 'sendMsg', // 接口地址
                        'value' => [
                            'access_token' => '' // 接口参数
                        ]
                    ],
                ];

                $result = $wa->send($send);
                break;
        }
        return $result;

    }

    //检查是否有新消息插入
    public function checkNewMessage($time){
        $message = WechatMessage::where('create_time', '>', $time)->where('cat', 0)->where('op_id', '=', '0')->get()->toArray();
        $return = [];
        foreach($message as $k => $v){
            if(array_key_exists($v['member_id'], $return)){
                $return[$v['member_id']] += 1;
            }else{
                $return[$v['member_id']] = 1;
            }

        }

        return $return;
    }

    //根据分类获取用户消息
    public function getCatMessage($type){

        switch ($type){
            case 'all':
                return $this->getMessagePage();
                break;
            case 'ignore':
                //获取未接待的用户消息列表
                return $this->getIgnoreMessagePage();
                break;
            case 'danger':
                //获取已跑路的和48小时未发送过消息的用户
                return $this->getDangerMessagePage();
                break;
            case 'automatic':
                //获取自动回复的消息
                return $this->getAutomaticMessagePage();
                break;
        }
    }

    public function getReplayMessage($member_id){
        return WechatMessage::where('op_id', '>', 0)->where('member_id', $member_id)->orderBy('create_time', 'desc')->first();
    }

    public function getIgnoreMessagePage(){

        $Ids = $this->getPreData('ignore');
        if(empty($Ids)){
            return array();
        }
        //过滤已经回复过的用户
        foreach($Ids as $k => &$v){
            $replay = $this->getReplayMessage($k);
            if(isset($replay->id)){
                if($replay->id > $v){
                    unset($Ids[$k]);
                }
            }

        }

        $data = $this->getFinData($Ids, 'follow');

        $this->combData($data);

        return $data;
    }

    public function getDangerMessagePage(){
        $Ids = $this->getPreData('true');
        if(empty($Ids)){
            return array();
        }

        //过滤用户只在48小时前发过信息，至今没有发送过
        $data = $this->getFinData($Ids,'danger');

        $this->combData($data);

        return $data;
    }

    public function getAutomaticMessagePage(){
        $Ids = $this->getPreData('auto');
        if(empty($Ids)){
            return array();
        }

        $data = $this->getFinData($Ids);

        $this->combData($data);

        return $data;
    }

    //搜索用户昵称
    public function getSearchMessagePage($name){
            $dt = DB::table($this->guid . '_wechat_member')
            ->select($this->guid . '_wechat_message.id', $this->guid . '_wechat_member.id as member_id')
            ->join($this->guid . '_wechat_member_info', $this->guid . '_wechat_member.id', '=', $this->guid . '_wechat_member_info.wechat_member_id')
            ->join($this->guid . '_wechat_message', $this->guid . '_wechat_message.member_id', '=', $this->guid . '_wechat_member.id')
            ->where($this->guid . '_wechat_message.op_id', '<', 1)
            ->where($this->guid . '_wechat_member_info.name', 'like', '%'.trim($name).'%')
            ->orderBy($this->guid . '_wechat_message.id', 'desc')
            ->first();
        if(!count($dt)){
            return array();
        }
        $tmp = $this->object_to_array($dt);
        $Ids[$tmp['member_id']] = $tmp['id'];
        $data = $this->getFinData($Ids);
        $this->combData($data);

        return $data;
    }

    public function pitStation($data){

        $message = new WechatMessage();
        $wm = new WechatMemberUtils();
        $message->cat = 0;//默认用户发送的消息
        if($data['msgType'] != 'text' && $data['msgType'] != 'event'){

            $arr = [
                'type' => 'downloadMedia',
                'action' => [
                    'media_id' => $data['mediaId']
                ],
                'parameter' => [ // 发送地址 (被动推送调用时可不填)
                    'key' => 'downloadMedia', // 接口地址
                    'value' => [
                        'access_token' => '' // 接口参数
                    ]
                ],
            ];

            $wa = new WechatAction();
            $fileInfo = $wa->send($arr);

            if ($fileInfo) {
                // 上传文件存放路径
                $path_name = \Config::get('EcdoSpiderMan::setting')['store']['usermedia']['dir'] . '/' . $this->guid . '/' . \Config::get('EcdoSpiderMan::setting')['store']['usermedia']['wechat_url'] . '/' . $data['fromUser'] . '/' . $data['msgType'] . '/' . date('Y/m/d/');
                switch ($data['msgType']) {
                    case 'image':
                        $suffix = '.jpg';
                        $media = new StoreImage();
                        break;
                    case 'voice':
                        $suffix = '.amr';
                        $media = new StoreVoice();
                        break;
                    case 'video':
                        $suffix = '.mp4';
                        $media = new StoreVideo();
                        break;
                    case 'shortvideo':
                        $suffix = '.mp4';
                        $media = new StoreVideo();
                    default:
                        break;
                }

                //先判断最终路径是否创建
                if(!is_dir($path_name)) {
                    mkdir($path_name, 0775, true); //递归创建
                }

                $filename = $path_name . $data['msgId'] . $suffix;
                if ($this->saveWeixinFile($filename, $fileInfo["data"]["body"], $suffix)) {
                    //保存 media 到数据库
                    if(array_key_exists($data['msgType'], self::mediaType())){
                        $media->url = $filename;
                        $media->save();
                    }

                }
                $message->content = isset($media->id) ? $media->id : '';
            }

        }else if($data['msgType'] == 'event'){
            /*$arr = array(
            'subscribe' => 0, //
            'unsubscribe' => 1, //
            'scan' => 2, //
            'location' => 3, //
            'click' => 4, //
            'view' => 5, //
        );*/
            $wmenu = new WechatMenuUtils();

            switch ($data['event']){
                case 'scan':
                    $name = WechatCode::where('ticket', $data['ticket'])->pluck('name');
                    $message->content = $name ? '扫二维码 '.$name : '扫二维码';
                    break;
                case 'location':
                    return ;
                    break;
                case 'subscribe':
                    $message->content = '关注公众号';
                    break;
                case 'unsubscribe':
                    $message->content = '取消关注公众账号';
                    break;
                case 'click':
                case 'view':
                    //点击菜单 菜单为发送消息 菜单为url
                    $menuInfo = $wmenu->getMenuByKey($data['eventKey']);
                    $message->content = '点击 '. isset($menuInfo->name) ? '菜单'.$menuInfo->name : '菜单';
                    break;
            }
            $message->cat = 1;
        }else if($data['msgType'] == 'text'){
            $message->content = $data['content'] ? $data['content'] : '连接WIFI';
        }else{
            return;
        }

        $memberInfo = $wm->getOneMemberByOpenID($data['fromUser']);

        $message->member_id = $memberInfo->wechat_member_id;
        $message->op_id = 0;

        //判断是否是事件
        if(array_key_exists('event', $data)){
            if(array_key_exists($data['event'], self::getEventType())){
                $message->type = self::getEventType()[$data['event']];
                $message->mold = 1;
            }
        }else{
            $message->type = self::getMsgType()[$data['msgType']];
            $message->mold = 0;
        }


        $message->create_time = time();
        $message->disabled = 'false';
        $message->save();

    }

    function saveWeixinFile($filename, $filecontent, $suffix)
    {
        $local_file = fopen($filename, 'w');
        if (false !== $local_file) {
            if (false !== fwrite($local_file, $filecontent)) {
                fclose($local_file);

                // amr转换mp3
                if ($suffix == '.amr') {
                    $armfile = base_path().'/public/'.$filename;
                    chmod($armfile, 0755);

                    $command = '/usr/local/bin/ffmpeg -i '.$armfile.' '.$armfile.'.mp3';
                    popen($command, 'r');
                }

                return true;
            }
        }
    }

}
