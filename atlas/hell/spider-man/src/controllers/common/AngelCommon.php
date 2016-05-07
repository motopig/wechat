<?php
namespace Ecdo\EcdoSpiderMan;

use Session;
use App\Controllers\BaseController;
use Ecdo\EcdoSpiderMan\AngelDashboardUtils;
use Ecdo\EcdoSpiderMan\AngelAccountUtils;
use Ecdo\EcdoSpiderMan\AngelModalUtils;
use Ecdo\Universe\TowerUtils;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Ecdo\Universe\TowerDB;

/**
 * 商家控制器公用类
 * 
 * @category yunke
 * @package atlas\hell\spider-man\src\controllers\common
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class AngelCommon extends BaseController
{
    // guid
    public $guid;

    // guid白名单
    public $notTowerRoute = [];
    public $inTower = true;

    // 验证变量
    public $response_rules = array();
    public $response_type = 'success';
    public $response_msg = '';

    public function __construct()
    {
        parent::__construct();

        // 获取商户储存信息
        TowerDB::useConnUniverse();
        if (Auth::angel()->check()) {
            // 导入行数限制
            $this->rows = \Config::get('EcdoSpiderMan::setting')['rows'];

            $tu = new TowerUtils();
            $adu = new AngelDashboardUtils();
            $aau = new AngelAccountUtils();
            $angel_info = $aau->getAngelInfo(Auth::angel()->get()['id']);
            if (! empty($angel_info)) {
                $angel_info_head = $angel_info['head'];
            }

            $tower = $adu->getTowerByGuid($tu->fetchTowerGuid());
            if (! empty($tower)) {
                $this->guid = $tower->encrypt_id;
            }
            
            $territory = $adu->getOneByTerritoryId(Auth::angel()->get()['id']);
            $grade = $adu->getCountByTerritoryId(Auth::angel()->get()['id']);
            if (! empty($territory)) {
                $territory_info = $adu->getOneByTerritoryInfo($territory->id);
            }

            Session::put(Auth::angel()->get()->encrypt_id . '_angel_info_head', isset($angel_info_head) ? $angel_info_head : '');
            Session::put(Auth::angel()->get()->encrypt_id . '_tower', ! empty($tower) ? $tower : '');
            Session::put(Auth::angel()->get()->encrypt_id . '_territory', ! empty($territory) ? $territory : '');
            Session::put(Auth::angel()->get()->encrypt_id . '_grade', ! empty($grade) ? $grade : '');
            Session::put(Auth::angel()->get()->encrypt_id . '_territory_info', isset($territory_info) ? $territory_info : '');
            
            self::setGuid($this->guid);
            self::isPostGuid();
        }
        
    }

    // 设置Session Guid
    public function setGuid($guid = '')
    {
        Session::forget('guid');
        if (! empty($guid)) {
            Session::put('guid', $guid);
        } else {
            Session::put('guid', '');
        }
    }

    // 验证POST或AJAX匹配Guid
    public function isPostGuid()
    {
        $ajax = [];
        $post = '';
        
        $inTower = false;
        if($this->inTower){
            $inTower = true;
            if (! empty($this->notTowerRoute) && is_array($this->notTowerRoute)) {
                foreach ($this->notTowerRoute as $k => $v) {
                    if (strpos(\URL::current(), $v) !== false) {
                        $inTower = false;
                        break;
                    }
                }
            } elseif (substr(\URL::current(), -5) == 'angel') {
               $inTower = false; 
            }
        }
        

        if ($inTower && $_SERVER['REQUEST_METHOD'] == 'POST') {
            if (! Input::get('csrf_guid')) {
                if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && 
                    strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
                    $ajax['errmsg'] = '缺少参数，请重新选择云号';
                } else {
                    $post = '缺少参数，请重新选择云号';
                }
            } elseif ($this->guid != Input::get('csrf_guid')) {
                if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && 
                    strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
                    $ajax['errmsg'] = '您已切换云号，当前操作已失效';
                } else {
                    $post = '您已切换云号，当前操作已失效';
                }
            }
        }

        if (! empty($ajax)) {
            $ajax['errcode'] = 'error';
            exit(json_encode($ajax, JSON_UNESCAPED_UNICODE));
        } elseif (! empty($post)) {
            echo "<script>alert('" . $post . "');</script>";
            header("Content-type: text/html; charset=utf-8");
            echo "<meta http-equiv=refresh content='0; url=" . 
            action('\Ecdo\EcdoSpiderMan\AngelDashboard@index') . "'>";  
            exit(0);
        }
    }

    // ajax表单验证
    public function ajaxValidator($data = '')
    {
        if (count($this->response_rules) > 0) {
            // 开始验证
            $validator = Validator::make($data, $this->response_rules);

            // 验证不通过
            if (!$validator->passes()) {
                $messages = $validator->messages();
                foreach ($messages->all() as $message) {
                    $this->response_msg .= $message . '</br>';
                }

                $this->response_type = 'error';
            }
        }

        return $this->response_type;
    }

    // ajax表单提交返回方法
    public function end($url)
    {
        // ajax返回请求
        $arr = array(
            'response_type' => $this->response_type,
            'response_msg' => $this->response_msg,
            'response_url' => Action($url)
        );

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 导入文件
    public function fileValidator($file, $type = '')
    {
        $res = array(
            'errcode' => 'success',
            'msg' => ''
        );

        if ($type == '') {
            $res['errcode'] = 'error';
            $res['msg'] = '请指定文件格式类型!';
        } else {
            if ($type == 'csv') {
                if ($file->getMimeType() != 'text/plain' || explode(".", $file->getClientOriginalName())[1] != 'csv') {
                    $res['errcode'] = 'error';
                    $res['msg'] = '请上传csv格式的文件!';
                }
            } elseif ($type == 'head' || $type == 'image' || $type == 'voice' || $type == 'video') {
                switch ($type) {
                    case 'head': // 上传头像
                        $res = self::uploadHeadImage($file, $res);
                        break;
                    case 'image':
                        $res = is_array($file) ? self::uploadManyImage($file, $res) : self::uploadImage($file, $res);
                        break;
                    case 'voice':
                        $res = self::uploadVoice($file, $res);
                        break;
                    case 'video':
                        $res = self::uploadVideo($file, $res);
                        break;
                    default:
                        break;
                }
            }
        }

        return $res;
    }

    // 上传头像
    public function uploadHeadImage($file, $res)
    {
        $image_config = \Config::get('EcdoSpiderMan::setting')['store']['path']['head'];

        // 验证附件大小与格式
        if (ceil($file->getSize() / 1000) > $image_config['size']) {
            $res['errcode'] = 'error';
            $res['msg'] = '上传图片请控制在' . round($image_config['size'] / 1000) . 'MB内!';
        } elseif (! isset($image_config['mime'][$file->getClientOriginalExtension()])) {
            $res['errcode'] = 'error';
            $res['msg'] = '请上传bmp/png/jpeg/jpg/gif格式的图片!';
        }

        if ($res['errcode'] == 'success') {
            $path = \Config::get('EcdoSpiderMan::setting')['store']['file'] . '/' . $image_config['url'] 
            . '/' . Auth::angel()->get()->encrypt_id . '/';
            
            // 递归创建路径
            if (! is_dir($path)) {
                mkdir($path, 0775, true);
            } elseif (is_dir($path . $file->getClientOriginalName())) {
                unlink($path . $files);
            }

            if ($file->move($path, $file->getClientOriginalName())) {
                $res['file'] = $path . $file->getClientOriginalName();
            } else {
                $res['errcode'] = 'error';
                $res['msg'] = '上传图片失败!';
            }
        }

        return $res;
    }

    // 上传图片
    public function uploadImage($file, $res)
    {
        $image_config = \Config::get('EcdoSpiderMan::setting')['store']['path']['image'];

        // 验证附件大小与格式
        if (ceil($file->getSize() / 1000) > $image_config['size']) {
            $res['errcode'] = 'error';
            $res['msg'] = '上传图片请控制在' . round($image_config['size'] / 1000) . 'MB内!';
        } elseif (! isset($image_config['mime'][$file->getClientOriginalExtension()])) {
            $res['errcode'] = 'error';
            $res['msg'] = '请上传bmp/png/jpeg/jpg/gif格式的图片!';
        }

        if ($res['errcode'] == 'success') {
            $path = \Config::get('EcdoSpiderMan::setting')['store']['file'] . '/' . TowerUtils::fetchTowerGuid() 
            . '/' . $image_config['url'] . '/' . date('Y/m/d/');
            
            // 递归创建路径
            if (! is_dir($path)) {
                mkdir($path, 0775, true);
            }

            $files = sha1($file->getClientOriginalName() . time()) . '.' . $file->getClientOriginalExtension();
            if ($file->move($path, $files)) {
                $res['file'] = $path . $files;
            } else {
                $res['errcode'] = 'error';
                $res['msg'] = '上传图片失败!';
            }
        }

        return $res;
    }

    // 上传多张图片
    public function uploadManyImage($file, $res)
    {
        $dt = true;
        $image_config = \Config::get('EcdoSpiderMan::setting')['store']['path']['image'];

        foreach ($file as $k => $v) {
            if (ceil($v->getSize() / 1000) > $image_config['size']) {
                $dt = false;

                $res['errcode'] = 'error';
                $res['msg'] = '上传图片请控制在' . round($image_config['size'] / 1000) . 'MB内!';
            } elseif (! isset($image_config['mime'][$v->getClientOriginalExtension()])) {
                $dt = false;

                $res['errcode'] = 'error';
                $res['msg'] = '请上传bmp/png/jpeg/jpg/gif格式的图片!';
            }

            if (! $dt) {
                break;
            }
        }

        if ($res['errcode'] == 'success') {
            $path = \Config::get('EcdoSpiderMan::setting')['store']['file'] . '/' . TowerUtils::fetchTowerGuid() 
            . '/' . $image_config['url'] . '/' . date('Y/m/d/');

            // 递归创建路径
            if (! is_dir($path)) {
                mkdir($path, 0775, true);
            }

            foreach ($file as $k => $v) {
                $files = sha1($v->getClientOriginalName() . time()) . '.' . $v->getClientOriginalExtension();
                if ($v->move($path, $files)) {
                    $res['file'][$k] = $path . $files;
                } else {
                    $res['errcode'] = 'error';
                    $res['msg'] = '上传图片失败!';
                }

                if (! $dt) {
                    break;
                }
            }
        }

        return $res;
    }

    // 上传语音
    public function uploadVoice($file, $res)
    {
        $voice_config = \Config::get('EcdoSpiderMan::setting')['store']['path']['voice'];

        // 验证附件大小与格式
        if (ceil($file->getSize() / 1000) > $voice_config['size']) {
            $res['errcode'] = 'error';
            $res['msg'] = '上传语音请控制在' . round($voice_config['size'] / 1000) . 'MB内!';
        } elseif (! isset($voice_config['mime'][$file->getClientOriginalExtension()])) {
            $res['errcode'] = 'error';
            $res['msg'] = '请上传mp3/wma/wav/amr格式的语音!';
        }

        if ($res['errcode'] == 'success') {
            $path = \Config::get('EcdoSpiderMan::setting')['store']['file'] . '/' . TowerUtils::fetchTowerGuid() 
            . '/' . $voice_config['url'] . '/' . date('Y/m/d/');
            
            // 递归创建路径
            if (! is_dir($path)) {
                mkdir($path, 0775, true);
            }

            $files = sha1($file->getClientOriginalName() . time()) . '.' . $file->getClientOriginalExtension();
            if ($file->move($path, $files)) {
                $res['file'] = $path . $files;
            } else {
                $res['errcode'] = 'error';
                $res['msg'] = '上传语音失败!';
            }
        }

        return $res;
    }

    // 上传视频
    public function uploadVideo($file, $res)
    {
        $video_config = \Config::get('EcdoSpiderMan::setting')['store']['path']['video'];

        // 验证附件大小与格式
        if (ceil($file->getSize() / 1000) > $video_config['size']) {
            $res['errcode'] = 'error';
            $res['msg'] = '上传视频请控制在' . round($video_config['size'] / 1000) . 'MB内!';
        } elseif (! isset($video_config['mime'][$file->getClientOriginalExtension()])) {
            $res['errcode'] = 'error';
            $res['msg'] = '请上传mp4格式的视频!';
        }

        if ($res['errcode'] == 'success') {
            $path = \Config::get('EcdoSpiderMan::setting')['store']['file'] . '/' . TowerUtils::fetchTowerGuid() 
            . '/' . $video_config['url'] . '/' . date('Y/m/d/');
            
            // 递归创建路径
            if (! is_dir($path)) {
                mkdir($path, 0775, true);
            }

            $files = sha1($file->getClientOriginalName() . time()) . '.' . $file->getClientOriginalExtension();
            if ($file->move($path, $files)) {
                $res['file'] = $path . $files;
            } else {
                $res['errcode'] = 'error';
                $res['msg'] = '上传视频失败!';
            }
        }

        return $res;
    }

    // CSV导入
    public function csvImport($handle)
    {
        $out = array();
        $n = 0;
        while ($data = fgetcsv($handle, $this->rows, ',')) {
            $num = count($data);
            for ($i = 0; $i < $num; $i++) {
                $out[$n][$i] = $data[$i];
            }

            $n++;
        }

        return $out;
    }

    // Excel导出头信息及标题
    public function ExcelHead($title)
    {
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=".time().".xls");

        foreach ($title as $k => $v) {
            echo $this->ExcelGBK($v)."\t";
        }

        echo "\n";
    }

    // Excel导出内容
    public function ExcelBody($data)
    {
        foreach ($data as $k => $v) {
            echo $this->ExcelGBK($v)."\t";
        }

        echo "\n";
    }

    // Excel导出中文转码
    public function ExcelGBK($data)
    {
        return iconv("UTF-8", "GBK", $data);
    }

    // 公用模态框
    public function modal($type)
    {
        $file = '';
        $action = '';
        $filter = [];

        $current = Input::get('current');
        if (Input::get('search')) {
            $filter['search'] = Input::get('search');
        }

        if (Input::get('search_type')) {
            $filter['search_type'] = Input::get('search_type');
        }

        if (Input::get('action')) {
            $action = Input::get('action');
        }

        if (Input::file('file')) {
            $file = Input::file('file');
        }
        
        $data = ['type' => $type, 'current' => $current, 'filter' => $filter, 'action' => $action, 'file' => $file];

        $amu = new AngelModalUtils();
        $arr = $amu->getModal($data);

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    // 公用模态框预览
    public function modalPreview()
    {
        $amu = new AngelModalUtils();
        $arr = $amu->getModalPreview(Input::all());

        exit(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }
}
