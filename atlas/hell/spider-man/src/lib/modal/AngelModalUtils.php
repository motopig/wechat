<?php
namespace Ecdo\EcdoSpiderMan;

use Illuminate\Support\Facades\DB;
use Ecdo\Universe\TowerUtils;
use Ecdo\EcdoHulk\WechatGraphic;
use Ecdo\EcdoSuperMan\StoreImage;
use Ecdo\EcdoSuperMan\StoreVoice;
use Ecdo\EcdoSuperMan\StoreVideo;
use Ecdo\EcdoBatMan\EntityShop;

/**
 * 公用模态框
 * 
 * @category yunke
 * @package atlas\hell\spider-man\src\lib\modal
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
class AngelModalUtils
{
    protected $current = 0; // 当前页标识
    protected $page = 0; // 每页显示多少条
    protected $previous = 0; // 上一页
    protected $next = 0; // 下一页
    protected $total = 0; // 总记录数
    protected $totalPage = 0; // 总页数
    protected $search = ''; // 搜索内容
    protected $search_type = ''; // 搜索参数

    public function getModal($data)
    {   
        $this->page = \Config::get('EcdoSpiderMan::setting')['modal'][$data['type']];

        switch ($data['type']) {
            case 'graphics':
                $res = self::graphicsData($data);
                $template = self::graphicsTemplate($res, $data['action']);
                break;
            case 'material':
                $res = self::materialData($data);
                $template = self::materialTemplate($res, $data['action']);
                break;
            case 'image':
                if (! empty($data['file'])) {
                    $res = self::fileData($data);

                    if ($res['errcode'] == 'success') {
                        $res = self::imageData($data);
                    } elseif ($res['errcode'] == 'error') {
                        $data['action'] = 'add';
                    }
                } else {
                    $res = self::imageData($data);
                }
                
                $template = self::imageTemplate($res, $data['action']);
                break;
            case 'voice':
                if (! empty($data['file'])) {
                    $res = self::fileData($data);

                    if ($res['errcode'] == 'success') {
                        $res = self::voiceData($data);
                    } elseif ($res['errcode'] == 'error') {
                        $data['action'] = 'add';
                    }
                } else {
                    $res = self::voiceData($data);
                }

                $template = self::voiceTemplate($res, $data['action']);
                break;
            case 'video':
                if (! empty($data['file'])) {
                    $res = self::fileData($data);

                    if ($res['errcode'] == 'success') {
                        $res = self::videoData($data);
                    } elseif ($res['errcode'] == 'error') {
                        $data['action'] = 'add';
                    }
                } else {
                    $res = self::videoData($data);
                }

                $template = self::videoTemplate($res, $data['action']);
                break;
            case 'store':
                $res = self::storeData($data);
                $template = self::storeTemplate($res, $data['action']);
                break;
        }

        $arr = ['html' => $template, 'type' => $data['type']];

        return $arr;
    }

    // 模态框预览
    public function getModalPreview($data)
    {
        switch ($data['type']) {
            case 'graphics':
                $template = self::graphicsTemplatePreview($data['id'], 'graphics');
                break;
            case 'material':
                $template = self::graphicsTemplatePreview($data['id'], 'material');
                break;
            case 'image':
                $template = self::imageTemplatePreview($data['id']);
                break;
            case 'voice':
                $template = self::vocieTemplatePreview($data['id']);
                break;
            case 'video':
                $template = self::videoTemplatePreview($data['id']);
                break;
        }

        $arr = ['html' => $template, 'id' => $data['id'], 'type' => $data['type']];

        return $arr;
    }

    // 模态框上传文件
    protected function fileData($data)
    {
        $arr = ['type' => $data['type'], 'errcode' => 'success', 'msg' => ''];

        switch ($arr['type']) {
            case 'image':
                $ac = new \Ecdo\EcdoSpiderMan\AngelCommon();
                $res = $ac->fileValidator($data['file'], 'image');

                if ($res['errcode'] == 'error') {
                    $arr['errcode'] = $res['errcode'];
                    $arr['msg'] = $res['msg'];
                } else {
                    $siu = new \Ecdo\EcdoSuperMan\StoreImageUtils();
                    if (! $siu->createImage($res['file'])) {
                        $arr['errcode'] = 'error';
                        $arr['msg'] = '创建图片失败!';
                    }
                }

                break;
            case 'voice':
                $ac = new \Ecdo\EcdoSpiderMan\AngelCommon();
                $res = $ac->fileValidator($data['file'], 'voice');

                if ($res['errcode'] == 'error') {
                    $arr['errcode'] = $res['errcode'];
                    $arr['msg'] = $res['msg'];
                } else {
                    $svu = new \Ecdo\EcdoSuperMan\StoreVoiceUtils();
                    if (! $svu->createImage($res['file'])) {
                        $arr['errcode'] = 'error';
                        $arr['msg'] = '创建语音失败!';
                    }
                }

                break;
            case 'video':
                $ac = new \Ecdo\EcdoSpiderMan\AngelCommon();
                $res = $ac->fileValidator($data['file'], 'video');

                if ($res['errcode'] == 'error') {
                    $arr['errcode'] = $res['errcode'];
                    $arr['msg'] = $res['msg'];
                } else {
                    $svu = new \Ecdo\EcdoSuperMan\StoreVideoUtils();
                    if (! $svu->createImage($res['file'])) {
                        $arr['errcode'] = 'error';
                        $arr['msg'] = '创建视频失败!';
                    }
                }

                break;
        }

        return $arr;
    }

    // 微信图文数据
    protected function graphicsData($data)
    {
        $this->current = $data['current'];

        $dt = WechatGraphic::where('f_id', 0)->orderBy('updated_at', 'desc');
        if (count($data['filter']) > 0) {
            $count = WechatGraphic::where('f_id', 0);

            foreach ($data['filter'] as $k => $v) {
                if ($k == 'search') {
                    $this->search = $v;
                    $count = $count->where('title', 'like', '%'.trim($v).'%');
                    $dt = $dt->where('title', 'like', '%'.trim($v).'%');
                } elseif ($k == 'search_type') {
                    $this->search_type = $v;
                    $count = $count->where('type', $v);
                    $dt = $dt->where('type', $v);
                }
            }

            $this->total = $count->count();
        } else {
            $this->total = WechatGraphic::where('f_id', 0)->count();
        }

        $this->totalPage = ceil($this->total / $this->page);
        $startPage = $this->current * $this->page; // 开始记录

        $dt = $dt->skip($startPage)->take($this->page)->get();
        foreach ($dt as $k => $v) {
            $dt[$k]->item = WechatGraphic::where('f_id', $v->id)->get();
        }

        return $dt;
    }

    // 高级图文数据
    protected function materialData($data)
    {
        return [];
    }

    // 图片数据
    protected function imageData($data)
    {
        $this->current = $data['current'];

        $dt = StoreImage::orderBy('updated_at', 'desc');
        if (count($data['filter']) > 0) {
            $count = StoreImage::orderBy('updated_at', 'desc');

            foreach ($data['filter'] as $k => $v) {
                if ($k == 'search') {
                    $this->search = $v;
                    $count = $count->where('name', 'like', '%'.trim($v).'%');
                    $dt = $dt->where('name', 'like', '%'.trim($v).'%');
                }
            }

            $this->total = $count->count();
        } else {
            $this->total = StoreImage::count();
        }

        $this->totalPage = ceil($this->total / $this->page);
        $startPage = $this->current * $this->page;

        $dt = $dt->skip($startPage)->take($this->page)->get();

        return $dt;
    }

    // 语音数据
    protected function voiceData($data)
    {
        $this->current = $data['current'];

        $dt = StoreVoice::orderBy('updated_at', 'desc');
        if (count($data['filter']) > 0) {
            $count = StoreVoice::orderBy('updated_at', 'desc');

            foreach ($data['filter'] as $k => $v) {
                if ($k == 'search') {
                    $this->search = $v;
                    $count = $count->where('name', 'like', '%'.trim($v).'%');
                    $dt = $dt->where('name', 'like', '%'.trim($v).'%');
                }
            }

            $this->total = $count->count();
        } else {
            $this->total = StoreVoice::count();
        }

        $this->totalPage = ceil($this->total / $this->page);
        $startPage = $this->current * $this->page;

        $dt = $dt->skip($startPage)->take($this->page)->get();
        
        return $dt;
    }

    // 视频数据
    protected function videoData($data)
    {
        $this->current = $data['current'];

        $dt = StoreVideo::orderBy('updated_at', 'desc');
        if (count($data['filter']) > 0) {
            $count = StoreVideo::orderBy('updated_at', 'desc');

            foreach ($data['filter'] as $k => $v) {
                if ($k == 'search') {
                    $this->search = $v;
                    $count = $count->where('name', 'like', '%'.trim($v).'%');
                    $dt = $dt->where('name', 'like', '%'.trim($v).'%');
                }
            }

            $this->total = $count->count();
        } else {
            $this->total = StoreVideo::count();
        }

        $this->totalPage = ceil($this->total / $this->page);
        $startPage = $this->current * $this->page;

        $dt = $dt->skip($startPage)->take($this->page)->get();
        
        return $dt;
    }

    // 门店数据
    protected function storeData($data)
    {
        $this->current = $data['current'];

        $dt = EntityShop::orderBy('updated_at', 'desc');
        if (count($data['filter']) > 0) {
            $count = EntityShop::orderBy('updated_at', 'desc');

            foreach ($data['filter'] as $k => $v) {
                if ($k == 'search') {
                    $this->search = $v;
                    $count = $count->where('business_name', 'like', '%'.trim($v).'%');
                    $dt = $dt->where('business_name', 'like', '%'.trim($v).'%');
                }
            }

            $this->total = $count->count();
        } else {
            $this->total = EntityShop::count();
        }

        $this->totalPage = ceil($this->total / $this->page);
        $startPage = $this->current * $this->page;

        $dt = $dt->skip($startPage)->take($this->page)->get();

        return $dt;
    }

    // 微信图文模版
    protected function graphicsTemplate($data, $action = '')
    {
        $tp = <<<EOF
        <section class="panel panel-default">%s</section>
EOF;

        $header = self::menuTemplate('graphics', $action); // 获取菜单栏目
        $body = '<div class="panel-body">';
        $body .= '<div class="col-sm-3 modal-search">';
        $body .= '<div class="input-group">';
        $body .= '<input type="text" class="input-sm form-control bolt-search-input modal-search-control search-border" placeholder="请输入图文标题">';
        $body .= '<span class="input-group-btn">';
        $body .= '<button type="button" class="btn btn-sm btn-default bolt-search modal-search-click" data-type="graphics" title="搜索">';
        $body .= '<i class="icon-magnifier"></i>';
        $body .= '</button>';
        $body .= '</span>';
        $body .= '</div>';
        $body .= '</div>';
        $body .= '<div class="btn-group modal-dropdown-select">';
        $body .= '<button data-toggle="dropdown" class="btn btn-sm btn-default modal-dropdown-select-control dropdown-toggle">';
        $body .= '<span class="dropdown-label modal-dropdown-label">类型</span>&nbsp;<span class="caret"></span>';
        $body .= '</button>';
        $body .= '<ul class="dropdown-menu dropdown-select">';
        $body .= '<li><input type="radio" name="search_type" value="" checked><a href="#">类型</a></li>';
        $body .= '<li><input type="radio" name="search_type" value="1"><a href="#">单图文</a></li>';
        $body .= '<li><input type="radio" name="search_type" value="2"><a href="#">多图文</a></li>';
        $body .= '</ul>';
        $body .= '</div>';
        $body .= '<a href="javascript:void(0);" class="btn btn-default btn-xs modal-refresh-click" data-type="graphics">';
        $body .= '<i class="icon-refresh"></i>&nbsp; 刷新';
        $body .= '</a>';
        $body .= '<div class="tab-content modal-tab">';
        $body .= '<div class="tab-pane fade active in" id="messages">';
        $body .= '<div>';
        $body .= '<section class="panel panel-default">';
        $body .= '<table class="table table-striped m-b-none">';
        $body .= '<thead>';
        $body .= '<tr>';
        $body .= '<th>图文标题 <i class="fa fa-sort fa-sort-p"></i></th>';
        $body .= '<th>更新时间 <i class="fa fa-sort fa-sort-p"></i></th>';
        $body .= '<th>操作</th>';
        $body .= '</tr>';
        $body .= '</thead>';
        $body .= '<tbody>';

        if (count($data) > 0) {
            foreach ($data as $k => $v) {
                $body .= '<tr>';
                $body .= '<td>';
                $body .= '<div class="ng">';
                $body .= '<div class="ng-item">';
                $body .= '<div class="td-cont with-label">';
                $body .= '<span class="label label-success">';
                $body .= count($v->item) > 0 ? '多图文' : '单图文';
                $body .= '</span>';
                $body .= '<span class="ng-title">' . $v->title . '</span>';

                if (count($v->item) > 0) {
                    $body .= '<span class="pull-right graphics-title">';
                    $body .= '<a href="###">';
                    $body .= '<i class="fa fa-sort-down" graphics-title-id="' . $v->id . '"></i>';
                    $body .= '</a>';
                    $body .= '</span>';
                }

                $body .= '</div>';
                $body .= '</div>';
                
                if (count($v->item) > 0) {
                    $body .= '<div class="ng-item graphics-title-fid_' . $v->id . '" style="display:none;">';

                    foreach ($v->item as $i) {
                        $body .= '<div class="td-cont with-label" style="padding:2px;">';
                        $body .= '<span class="ng-title">' . $i->title . '</span>';
                        $body .= '</div>';
                    }

                    $body .= '</div>';
                }

                $body .= '</div>';
                $body .= '</td>';
                $body .= '<td>' . $v->updated_at . '</td>';
                $body .= '<td class="text-right">';
                $body .= '<a href="javascript:void(0);" class="btn btn-success btn-xs modal-button-click" 
                data-params="graphics,' . $v->id . '"><i class="fa fa-check"></i>&nbsp; 选用</a>';
                $body .= '</td>';
                $body .= '</tr>';
            }
        }

        $body .= '</tbody>';
        $body .= '</table>';
        $body .= '</section>';
        $body .= self::getPage('graphics'); // 获取分页数据
        $body .= '</div>';
        $body .= '</div>';
        $body .= '</div>';
        $body .= '</div">';

        $footer = '<script src="' . asset('atlas/hell/spider-man/js/table/tables.js') . '"></script>';
        $footer .= '<link href="' . asset('atlas/hell/hulk/css/graphics.css') . '" rel="stylesheet" />';
        $html = $header . $body . $footer;

        return sprintf($tp, $html);
    }

    // 高级图文模版
    protected function materialTemplate($data, $action = '')
    {
        $tp = <<<EOF
        <section class="panel panel-default">%s</section>
EOF;

        $header = self::menuTemplate('material', $action);
        $body = '<div class="panel-body">';
        $body .= '<div class="col-sm-3 modal-search">';
        $body .= '<div class="input-group">';
        $body .= '<input type="text" class="input-sm form-control bolt-search-input modal-search-control search-border" placeholder="请输入图文标题">';
        $body .= '<span class="input-group-btn">';
        $body .= '<button type="button" class="btn btn-sm btn-default bolt-search modal-search-click" data-type="material" title="搜索">';
        $body .= '<i class="icon-magnifier"></i>';
        $body .= '</button>';
        $body .= '</span>';
        $body .= '</div>';
        $body .= '</div>';
        $body .= '<div class="btn-group modal-dropdown-select">';
        $body .= '<button data-toggle="dropdown" class="btn btn-sm btn-default modal-dropdown-select-control dropdown-toggle">';
        $body .= '<span class="dropdown-label modal-dropdown-label">类型</span>&nbsp;<span class="caret"></span>';
        $body .= '</button>';
        $body .= '<ul class="dropdown-menu dropdown-select">';
        $body .= '<li><input type="radio" name="search_type" value="" checked><a href="#">类型</a></li>';
        $body .= '<li><input type="radio" name="search_type" value="1"><a href="#">单图文</a></li>';
        $body .= '<li><input type="radio" name="search_type" value="2"><a href="#">多图文</a></li>';
        $body .= '</ul>';
        $body .= '</div>';
        $body .= '<a href="javascript:void(0);" class="btn btn-default btn-xs modal-refresh-click" data-type="material">';
        $body .= '<i class="icon-refresh"></i>&nbsp; 刷新';
        $body .= '</a>';
        $body .= '<div class="tab-content modal-tab">';
        $body .= '<div class="tab-pane fade active in" id="messages">';
        $body .= '<div>';
        $body .= '<section class="panel panel-default">';
        $body .= '<table class="table table-striped m-b-none">';
        $body .= '<thead>';
        $body .= '<tr>';
        $body .= '<th>图文标题 <i class="fa fa-sort fa-sort-p"></i></th>';
        $body .= '<th>更新时间 <i class="fa fa-sort fa-sort-p"></i></th>';
        $body .= '<th>操作</th>';
        $body .= '</tr>';
        $body .= '</thead>';
        $body .= '<tbody>';

        if (count($data) > 0) {
            foreach ($data as $k => $v) {
                $body .= '<tr>';
                $body .= '<td>';
                $body .= '<div class="ng">';
                $body .= '<div class="ng-item">';
                $body .= '<div class="td-cont with-label">';
                $body .= '<span class="label label-success">';
                $body .= count($v->item) > 0 ? '多图文' : '单图文';
                $body .= '</span>';
                $body .= '<span class="ng-title">' . $v->title . '</span>';

                if (count($v->item) > 0) {
                    $body .= '<span class="pull-right material-title">';
                    $body .= '<a href="###">';
                    $body .= '<i class="fa fa-sort-down" material-title-id="' . $v->id . '"></i>';
                    $body .= '</a>';
                    $body .= '</span>';
                }

                $body .= '</div>';
                $body .= '</div>';
                
                if (count($v->item) > 0) {
                    $body .= '<div class="ng-item material-title-fid_' . $v->id . '" style="display:none;">';

                    foreach ($v->item as $i) {
                        $body .= '<div class="td-cont with-label" style="padding:2px;">';
                        $body .= '<span class="ng-title">' . $i->title . '</span>';
                        $body .= '</div>';
                    }

                    $body .= '</div>';
                }

                $body .= '</div>';
                $body .= '</td>';
                $body .= '<td>' . $v->updated_at . '</td>';
                $body .= '<td class="text-right">';
                $body .= '<a href="javascript:void(0);" class="btn btn-success btn-xs modal-button-click" 
                data-params="material,' . $v->id . '"><i class="fa fa-check"></i>&nbsp; 选用</a>';
                $body .= '</td>';
                $body .= '</tr>';
            }
        }

        $body .= '</tbody>';
        $body .= '</table>';
        $body .= '</section>';
        $body .= self::getPage('material');
        $body .= '</div>';
        $body .= '</div>';
        $body .= '</div>';
        $body .= '</div">';

        $footer = '<script src="' . asset('atlas/hell/spider-man/js/table/tables.js') . '"></script>';
        $footer .= '<link href="' . asset('atlas/hell/hulk/css/graphics.css') . '" rel="stylesheet" />';
        $html = $header . $body . $footer;

        return sprintf($tp, $html);
    }

    // 图片模版
    protected function imageTemplate($data, $action = '')
    {
        $tp = <<<EOF
        <section class="panel panel-default">%s</section>
EOF;
        
        $header = self::menuTemplate('image', $action);
        if (empty($action)) {
            $body = '<div class="panel-body">';
            $body .= '<div class="col-sm-3 modal-search">';
            $body .= '<div class="input-group">';
            $body .= '<input type="text" class="input-sm form-control bolt-search-input modal-search-control" placeholder="请输入图片别名">';
            $body .= '<span class="input-group-btn">';
            $body .= '<button type="button" class="btn btn-sm btn-default bolt-search modal-search-click" data-type="image" title="搜索">';
            $body .= '<i class="icon-magnifier"></i>';
            $body .= '</button>';
            $body .= '</span>';
            $body .= '</div>';
            $body .= '</div>';
            $body .= '<a href="javascript:void(0);" class="btn btn-default btn-xs modal-refresh-click" data-type="image">';
            $body .= '<i class="icon-refresh"></i>&nbsp; 刷新';
            $body .= '</a>';
            $body .= '<div class="tab-content modal-tab">';
            $body .= '<div class="tab-pane fade active in" id="messages">';
            $body .= '<div>';
            
            if (count($data) > 0) {
                $body .= '<hr class="hr-middle" />';
                $body .= '<div class="row row-sm">';

                foreach ($data as $k => $v) {
                    $body .= '<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">';
                    $body .= '<div class="item">';
                    $body .= '<div class="pos-rlt">';
                    $body .= '<div class="item-overlay opacity r bg-black">';

                    $body .= '<div class="text-info padder m-t-sm text-sm" 
                    style="white-space:nowrap;text-overflow:ellipsis;overflow:hidden;">';
                    $body .= '<a href="###" title="' . $v->name . '">' . $v->name . '</a>';
                    $body .= '</div>';
                    
                    if (! empty($v->name)) {
                        $body .= '<div class="center text-center m-t-n" style="top:53%;">';
                    } else {
                        $body .= '<div class="center text-center m-t-n">';
                    }
                    
                    $body .= '<a href="javascript:void(0);" class="modal-button-click" 
                    data-params="image,' . $v->id . '">';
                    $body .= '<i class="icon-check i-2x" title="选用"></i>';
                    $body .= '</a>';
                    $body .= '</div>';
                    $body .= '</div>';
                    $body .= '<a href="###">';
                    $body .= '<img src="' . asset($v->url) . '" class="r img-full" height="123px">';
                    $body .= '</a>';
                    $body .= '</div>';
                    $body .= '<div class="padder-v"></div>';
                    $body .= '</div>';
                    $body .= '</div>';
                }

                $body .= '</div>';
                $body .= '<hr class="hr-middle" style="margin-top:0px;" />';
            }

            $body .= self::getPage('image');
            $body .= '</div>';
            $body .= '</div>';
            $body .= '</div>';
            $body .= '</div">';

            $html = $header . $body;
        } else {
            $body = '<div class="panel-body">';

            if (isset($data['errcode']) && $data['errcode'] == 'error') {
                $body .= '<div class="bolt-response-head-error">';
                $body .= '<div class="alert alert-danger">';
                $body .= '<button class="close" data-dismiss="alert" type="button">×</button>';
                $body .= $data['msg'];
                $body .= '</div>';
                $body .= '</div>';
            }

            $body .= '<form class="form-horizontal" method="post" enctype="multipart/form-data">';
            $body .= '<div class="form-group">';
            $body .= '<label class="col-sm-2 control-label">上传图片</label>';
            $body .= '<div class="col-sm-6">';
            $body .= '<span>';
            $body .= '<input type="file" name="file" class="filestyle image-file" data-icon="false" data-classbutton="btn btn-default" 
            data-classinput="form-control inline v-middle input-s" style="position: fixed; left: -500px;">';
            $body .= '</span>';
            $body .= '<b class="badge bg-success radio-checks" data-toggle="tooltip" data-placement="bottom" 
            data-original-title="支持bmp/png/jpeg/jpg/gif格式, 小于2M">';
            $body .= '<span class="icon-question"></span>';
            $body .= '</b>';
            $body .= '</div>';
            $body .= '</div>';
            $body .= '<div class="line line-dashed b-b line-lg pull-in"></div>';
            $body .= '<div class="form-group">';
            $body .= '<div class="col-sm-4 col-sm-offset-2">';
            $body .= '<button type="button" class="btn btn-success modal-file-click" data-type="image">确认</button>';
            $body .= '</div>';
            $body .= '</div>';
            $body .= '</form>';
            $body .= '</div>';

            $footer = '<script src="' . asset('atlas/hell/spider-man/js/file-input/bootstrap-filestyle.min.js') . '"></script>';
            $footer .= '<script src="' . asset('atlas/hell/spider-man/js/app.js') . '"></script>';
            
            $html = $header . $body . $footer;
        }

        return sprintf($tp, $html);
    }

    // 语音模版
    protected function voiceTemplate($data, $action = '')
    {
        $tp = <<<EOF
        <section class="panel panel-default">%s</section>
EOF;

        $header = self::menuTemplate('voice', $action);
        if (empty($action)) {
            $body = '<div class="panel-body">';
            $body .= '<div class="col-sm-3 modal-search">';
            $body .= '<div class="input-group">';
            $body .= '<input type="text" class="input-sm form-control bolt-search-input modal-search-control search-border" placeholder="请输入语音别名">';
            $body .= '<span class="input-group-btn">';
            $body .= '<button type="button" class="btn btn-sm btn-default bolt-search modal-search-click" data-type="voice" title="搜索">';
            $body .= '<i class="icon-magnifier"></i>';
            $body .= '</button>';
            $body .= '</span>';
            $body .= '</div>';
            $body .= '</div>';
            $body .= '<a href="javascript:void(0);" class="btn btn-default btn-xs modal-refresh-click" data-type="voice">';
            $body .= '<i class="icon-refresh"></i>&nbsp; 刷新';
            $body .= '</a>';
            $body .= '<div class="tab-content modal-tab">';
            $body .= '<div class="tab-pane fade active in" id="messages-2">';
            $body .= '<div>';
            $body .= '<section class="panel panel-default">';
            $body .= '<table class="table table-striped m-b-none">';
            $body .= '<thead>';
            $body .= '<tr>';
            $body .= '<th></th>';
            $body .= '<th>语音别名 <i class="fa fa-sort fa-sort-p"></i></th>';
            $body .= '<th>更新时间 <i class="fa fa-sort fa-sort-p"></i></th>';
            $body .= '<th>操作</th>';
            $body .= '</tr>';
            $body .= '</thead>';
            $body .= '<tbody>';

            if (count($data) > 0) {
                foreach ($data as $k => $v) {
                    $body .= '<tr>';
                    $body .= '<td>';
                    $body .= '<audio controls="controls" style="margin-left:18px;">';
                    $body .= '<source src="' . asset($v->url) . '" type="audio/mpeg" />';
                    $body .= '</audio>';
                    $body .= '</td>';
                    $body .= '<td>' . $v->name . '</td>';
                    $body .= '<td>' . $v->updated_at . '</td>';
                    $body .= '<td class="text-right">';
                    $body .= '<a href="javascript:void(0);" class="btn btn-success btn-xs modal-button-click" 
                    data-params="voice,' . $v->id . '"><i class="fa fa-check"></i>&nbsp; 选用</a>';
                    $body .= '</td>';
                    $body .= '</tr>';
                }
            }

            $body .= '</tbody>';
            $body .= '</table>';
            $body .= '</section>';
            $body .= self::getPage('voice');
            $body .= '</div>';
            $body .= '</div>';
            $body .= '</div>';
            $body .= '</div">';

            $footer = '<script src="' . asset('atlas/hell/spider-man/js/table/tables.js') . '"></script>';
            
            $html = $header . $body . $footer;
        } else {
            $body = '<div class="panel-body">';

            if (isset($data['errcode']) && $data['errcode'] == 'error') {
                $body .= '<div class="bolt-response-head-error">';
                $body .= '<div class="alert alert-danger">';
                $body .= '<button class="close" data-dismiss="alert" type="button">×</button>';
                $body .= $data['msg'];
                $body .= '</div>';
                $body .= '</div>';
            }

            $body .= '<form class="form-horizontal">';
            $body .= '<div class="form-group">';
            $body .= '<label class="col-sm-2 control-label">上传语音</label>';
            $body .= '<div class="col-sm-6">';
            $body .= '<span>';
            $body .= '<input type="file" name="file" class="filestyle voice-file" data-icon="false" data-classbutton="btn btn-default" 
            data-classinput="form-control inline v-middle input-s" style="position: fixed; left: -500px;">';
            $body .= '</span>';
            $body .= '<b class="badge bg-success radio-checks" data-toggle="tooltip" data-placement="bottom" 
            data-original-title="支持mp3/wma/wav/amr格式, 小于5M">';
            $body .= '<span class="icon-question"></span>';
            $body .= '</b>';
            $body .= '</div>';
            $body .= '</div>';
            $body .= '<div class="line line-dashed b-b line-lg pull-in"></div>';
            $body .= '<div class="form-group">';
            $body .= '<div class="col-sm-4 col-sm-offset-2">';
            $body .= '<button type="button" class="btn btn-success modal-file-click" data-type="voice">确认</button>';
            $body .= '</div>';
            $body .= '</div>';
            $body .= '</form>';
            $body .= '</div>';

            $footer = '<script src="' . asset('atlas/hell/spider-man/js/file-input/bootstrap-filestyle.min.js') . '"></script>';
            $footer .= '<script src="' . asset('atlas/hell/spider-man/js/app.js') . '"></script>';
            
            $html = $header . $body . $footer;
        }

        return sprintf($tp, $html);
    }

    // 视频模版
    protected function videoTemplate($data, $action = '')
    {
        $tp = <<<EOF
        <section class="panel panel-default">%s</section>
EOF;

        $header = self::menuTemplate('video', $action);
        if (empty($action)) {
            $body = '<div class="panel-body">';
            $body .= '<div class="col-sm-3 modal-search">';
            $body .= '<div class="input-group">';
            $body .= '<input type="text" class="input-sm form-control bolt-search-input modal-search-control search-border" placeholder="请输入视频别名">';
            $body .= '<span class="input-group-btn">';
            $body .= '<button type="button" class="btn btn-sm btn-default bolt-search modal-search-click" data-type="video" title="搜索">';
            $body .= '<i class="icon-magnifier"></i>';
            $body .= '</button>';
            $body .= '</span>';
            $body .= '</div>';
            $body .= '</div>';
            $body .= '<a href="javascript:void(0);" class="btn btn-default btn-xs modal-refresh-click" data-type="video">';
            $body .= '<i class="icon-refresh"></i>&nbsp; 刷新';
            $body .= '</a>';
            $body .= '<div class="tab-content modal-tab">';
            $body .= '<div class="tab-pane fade active in" id="messages-2">';
            $body .= '<div>';
            $body .= '<section class="panel panel-default">';
            $body .= '<table class="table table-striped m-b-none">';
            $body .= '<thead>';
            $body .= '<tr>';
            $body .= '<th></th>';
            $body .= '<th>视频别名 <i class="fa fa-sort fa-sort-p"></i></th>';
            $body .= '<th>更新时间 <i class="fa fa-sort fa-sort-p"></i></th>';
            $body .= '<th>操作</th>';
            $body .= '</tr>';
            $body .= '</thead>';
            $body .= '<tbody>';

            if (count($data) > 0) {
                foreach ($data as $k => $v) {
                    $body .= '<tr>';
                    $body .= '<td>';
                    $body .= '<a href="' . asset($v->url) . '" target="_blank"><i class="fa fa-play-circle i-2x"></i></a>';
                    $body .= '</td>';
                    $body .= '<td>' . $v->name . '</td>';
                    $body .= '<td>' . $v->updated_at . '</td>';
                    $body .= '<td class="text-right">';
                    $body .= '<a href="javascript:void(0);" class="btn btn-success btn-xs modal-button-click" 
                    data-params="video,' . $v->id . '"><i class="fa fa-check"></i>&nbsp; 选用</a>';
                    $body .= '</td>';
                    $body .= '</tr>';
                }
            }

            $body .= '</tbody>';
            $body .= '</table>';
            $body .= '</section>';
            $body .= self::getPage('video');
            $body .= '</div>';
            $body .= '</div>';
            $body .= '</div>';
            $body .= '</div">';

            $footer = '<script src="' . asset('atlas/hell/spider-man/js/table/tables.js') . '"></script>';
            
            $html = $header . $body . $footer;
        } else {
            $body = '<div class="panel-body">';

            if (isset($data['errcode']) && $data['errcode'] == 'error') {
                $body .= '<div class="bolt-response-head-error">';
                $body .= '<div class="alert alert-danger">';
                $body .= '<button class="close" data-dismiss="alert" type="button">×</button>';
                $body .= $data['msg'];
                $body .= '</div>';
                $body .= '</div>';
            }

            $body .= '<form class="form-horizontal">';
            $body .= '<div class="form-group">';
            $body .= '<label class="col-sm-2 control-label">上传视频</label>';
            $body .= '<div class="col-sm-6">';
            $body .= '<span>';
            $body .= '<input type="file" name="file" class="filestyle video-file" data-icon="false" data-classbutton="btn btn-default" 
            data-classinput="form-control inline v-middle input-s" style="position: fixed; left: -500px;">';
            $body .= '</span>';
            $body .= '<b class="badge bg-success radio-checks" data-toggle="tooltip" data-placement="bottom" 
            data-original-title="支持mp4格式, 小于10M">';
            $body .= '<span class="icon-question"></span>';
            $body .= '</b>';
            $body .= '</div>';
            $body .= '</div>';
            $body .= '<div class="line line-dashed b-b line-lg pull-in"></div>';
            $body .= '<div class="form-group">';
            $body .= '<div class="col-sm-4 col-sm-offset-2">';
            $body .= '<button type="button" class="btn btn-success modal-file-click" data-type="video">确认</button>';
            $body .= '</div>';
            $body .= '</div>';
            $body .= '</form>';
            $body .= '</div>';

            $footer = '<script src="' . asset('atlas/hell/spider-man/js/file-input/bootstrap-filestyle.min.js') . '"></script>';
            $footer .= '<script src="' . asset('atlas/hell/spider-man/js/app.js') . '"></script>';
            
            $html = $header . $body . $footer;
        }

        return sprintf($tp, $html);
    }

    // 门店模版
    protected function storeTemplate($data, $action = '')
    {
        $tp = <<<EOF
        <section class="panel panel-default">%s</section>
EOF;

        $header = self::menuTemplate('store', $action);
        $body = '<div class="panel-body">';
        $body .= '<div class="col-sm-3 modal-search">';
        $body .= '<div class="input-group">';
        $body .= '<input type="text" class="input-sm form-control bolt-search-input modal-search-control search-border" placeholder="请输入门店名称">';
        $body .= '<span class="input-group-btn">';
        $body .= '<button type="button" class="btn btn-sm btn-default bolt-search modal-search-click" data-type="store" title="搜索">';
        $body .= '<i class="icon-magnifier"></i>';
        $body .= '</button>';
        $body .= '</span>';
        $body .= '</div>';
        $body .= '</div>';
        $body .= '<a href="javascript:void(0);" class="btn btn-success btn-xs modal-button-all" data-type="store">';
        $body .= '批量选用</a>&nbsp;&nbsp;';
        $body .= '</a>';
        $body .= '<a href="javascript:void(0);" class="btn btn-default btn-xs modal-refresh-click" data-type="store">';
        $body .= '<i class="icon-refresh"></i>&nbsp; 刷新';
        $body .= '</a>';
        $body .= '<div class="tab-content modal-tab">';
        $body .= '<div class="tab-pane fade active in" id="messages-2">';
        $body .= '<div>';
        $body .= '<section class="panel panel-default">';
        $body .= '<table class="table table-striped m-b-none">';
        $body .= '<thead>';
        $body .= '<tr>';
        $body .= '<th>';
        $body .= '<a class="modal-all-checkbox" href="javascript:void(0);">';
        $body .= '<i class="fa fa-square-o"></i>';
        $body .= '</a>';
        $body .= '</th>';
        $body .= '<th>门店名称 <i class="fa fa-sort fa-sort-p"></i></th>';
        $body .= '<th>门店地址 </th>';
        $body .= '<th>更新时间 <i class="fa fa-sort fa-sort-p"></i></th>';
        $body .= '<th>操作</th>';
        $body .= '</tr>';
        $body .= '</thead>';
        $body .= '<tbody>';

        if (count($data) > 0) {
            foreach ($data as $k => $v) {
                $body .= '<tr>';
                $body .= '<td>';
                $body .= '<input class="modal-checkbox" type="checkbox" value="' . $v->id . '">';
                $body .= '</td>';
                $body .= '<td>' . $v->business_name . '</td>';
                $body .= '<td>' . $v->province . $v->city . $v->district . '<br />' . $v->address . '</td>';
                $body .= '<td>' . $v->updated_at . '</td>';
                $body .= '<td class="text-right">';
                $body .= '<a href="javascript:void(0);" class="btn btn-success btn-xs modal-button-click" 
                data-params="store,' . $v->id . '"><i class="fa fa-check"></i>&nbsp; 选用</a>';
                $body .= '</td>';
                $body .= '</tr>';
            }
        }

        $body .= '</tbody>';
        $body .= '</table>';
        $body .= '</section>';
        $body .= self::getPage('store');
        $body .= '</div>';
        $body .= '</div>';
        $body .= '</div>';
        $body .= '</div">';

        $footer = '<script src="' . asset('atlas/hell/spider-man/js/table/tables.js') . '"></script>';
        $footer .= '<script src="' . asset('atlas/hell/spider-man/js/jquery.checkbox.js') . '"></script>';
        
        $html = $header . $body . $footer;

        return sprintf($tp, $html);
    }

    // 菜单模版
    protected function menuTemplate($type, $action = '')
    {
        switch ($type) {
            case 'graphics':
                $header = '<header class="panel-heading text-right bg-light">';
                $header .= '<ul class="nav nav-tabs pull-left">';
                $header .= '<li class="active">';
                $header .= '<a href="#messages" data-toggle="tab">';
                $header .= '<i class="fa fa-comments text-muted"></i>&nbsp; 微信图文';
                $header .= '</a>';
                $header .= '</li>';
                $header .= '<li class="">';
                $header .= '<a href="javascript:void(0);" class="modal-ajax-click" 
                data-type="material" data-action>';
                $header .= '<i class="fa fa-gamepad text-muted"></i>&nbsp; 高级图文';
                $header .= '</a>';
                $header .= '</li>';
                $header .= '<li class="dropdown">';
                $header .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
                $header .= '<i class="fa fa-plus text-muted"></i>&nbsp; 新图文<b class="caret"></b>';
                $header .= '</a>';
                $header .= '<ul class="dropdown-menu text-left">';
                $header .= '<li>';
                $header .= '<a href="javascript:void(0);" class="modal-target-click" data-type="graphics" 
                data-target-url="' . action('\Ecdo\EcdoHulk\WechatGraphics@index') . '">微信图文</a>';
                $header .= '</li>';
                $header .= '<li>';
                $header .= '<a href="javascript:void(0);" class="modal-target-click" data-type="material" 
                data-target-url="' . action('\Ecdo\EcdoHulk\WechatMaterial@index') . '">高级图文</a>';
                $header .= '</li>';
                $header .= '</ul>';
                $header .= '</li>';
                $header .= '</ul>';
                $header .= '<span class="hidden-sm">&nbsp;</span>';
                $header .= '</header>';
                break;
            case 'material':
                $header = '<header class="panel-heading text-right bg-light">';
                $header .= '<ul class="nav nav-tabs pull-left">';
                $header .= '<li class="">';
                $header .= '<a href="javascript:void(0);" class="modal-ajax-click" 
                data-type="graphics" data-action>';
                $header .= '<i class="fa fa-comments text-muted"></i>&nbsp; 微信图文';
                $header .= '</a>';
                $header .= '</li>';
                $header .= '<li class="active">';
                $header .= '<a href="#messages" data-toggle="tab">';
                $header .= '<i class="fa fa-gamepad text-muted"></i>&nbsp; 高级图文';
                $header .= '</a>';
                $header .= '</li>';
                $header .= '<li class="dropdown">';
                $header .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
                $header .= '<i class="fa fa-plus text-muted"></i>&nbsp; 新图文<b class="caret"></b>';
                $header .= '</a>';
                $header .= '<ul class="dropdown-menu text-left">';
                $header .= '<li>';
                $header .= '<a href="javascript:void(0);" class="modal-target-click" data-type="graphics" 
                data-target-url="' . action('\Ecdo\EcdoHulk\WechatGraphics@index') . '">微信图文</a>';
                $header .= '</li>';
                $header .= '<li>';
                $header .= '<a href="javascript:void(0);" class="modal-target-click" data-type="material" 
                data-target-url="' . action('\Ecdo\EcdoHulk\WechatMaterial@index') . '">高级图文</a>';
                $header .= '</li>';
                $header .= '</ul>';
                $header .= '</li>';
                $header .= '</ul>';
                $header .= '<span class="hidden-sm">&nbsp;</span>';
                $header .= '</header>';
                break;
            case 'image':
                $header = '<header class="panel-heading text-right bg-light">';
                $header .= '<ul class="nav nav-tabs pull-left">';

                if (empty($action)) {
                    $header .= '<li class="active">';
                    $header .= '<a href="#messages" data-toggle="tab">';
                } else {
                    $header .= '<li class="">';
                    $header .= '<a href="javascript:void(0);" class="modal-ajax-click" 
                    data-type="' .  $type . '" data-action>';
                }

                $header .= '<i class="icon-picture"></i>&nbsp; 图片库';
                $header .= '</a>';
                $header .= '</li>';

                if (empty($action)) {
                    $header .= '<li class="">';
                    $header .= '<a href="javascript:void(0);" class="modal-ajax-click" 
                    data-type="' .  $type . '" data-action="' . $type . '_add">';
                } else {
                    $header .= '<li class="active">';
                    $header .= '<a href="#messages" data-toggle="tab">';
                }

                $header .= '<i class="fa fa-plus text-muted"></i>&nbsp; 新图片';
                $header .= '</a>';
                $header .= '</li>';
                $header .= '</ul>';
                $header .= '<span class="hidden-sm">&nbsp;</span>';
                $header .= '</header>';
                break;
            case 'voice':
                $header = '<header class="panel-heading text-right bg-light">';
                $header .= '<ul class="nav nav-tabs pull-left">';
                
                if (empty($action)) {
                    $header .= '<li class="active">';
                    $header .= '<a href="#messages" data-toggle="tab">';
                } else {
                    $header .= '<li class="">';
                    $header .= '<a href="javascript:void(0);" class="modal-ajax-click" 
                    data-type="' .  $type . '" data-action>';
                }

                $header .= '<i class="fa fa-volume-down"></i>&nbsp; 语音库';
                $header .= '</a>';
                $header .= '</li>';
                
                if (empty($action)) {
                    $header .= '<li class="">';
                    $header .= '<a href="javascript:void(0);" class="modal-ajax-click" 
                    data-type="' .  $type . '" data-action="' . $type . '_add">';
                } else {
                    $header .= '<li class="active">';
                    $header .= '<a href="#messages" data-toggle="tab">';
                }

                $header .= '<i class="fa fa-plus text-muted"></i>&nbsp; 新语音';
                $header .= '</a>';
                $header .= '</li>';
                $header .= '</ul>';
                $header .= '<span class="hidden-sm">&nbsp;</span>';
                $header .= '</header>';
                break;
            case 'video':
                $header = '<header class="panel-heading text-right bg-light">';
                $header .= '<ul class="nav nav-tabs pull-left">';
                
                if (empty($action)) {
                    $header .= '<li class="active">';
                    $header .= '<a href="#messages" data-toggle="tab">';
                } else {
                    $header .= '<li class="">';
                    $header .= '<a href="javascript:void(0);" class="modal-ajax-click" 
                    data-type="' .  $type . '" data-action>';
                }

                $header .= '<i class="fa fa-youtube-play"></i>&nbsp; 视频库';
                $header .= '</a>';
                $header .= '</li>';
                
                if (empty($action)) {
                    $header .= '<li class="">';
                    $header .= '<a href="javascript:void(0);" class="modal-ajax-click" 
                    data-type="' .  $type . '" data-action="' . $type . '_add">';
                } else {
                    $header .= '<li class="active">';
                    $header .= '<a href="#messages" data-toggle="tab">';
                }

                $header .= '<i class="fa fa-plus text-muted"></i>&nbsp; 新视频';
                $header .= '</a>';
                $header .= '</li>';
                $header .= '</ul>';
                $header .= '<span class="hidden-sm">&nbsp;</span>';
                $header .= '</header>';
                break;
            case 'store':
                $header = '<header class="panel-heading text-right bg-light">';
                $header .= '<ul class="nav nav-tabs pull-left">';
                $header .= '<li class="active">';
                $header .= '<a href="#messages" data-toggle="tab">';
                $header .= '<i class="icon-home"></i>&nbsp; 门店库';
                $header .= '</a>';
                $header .= '</li>';
                $header .= '<li class="">';
                $header .= '<a href="javascript:void(0);" class="modal-target-click" data-type="store" 
                data-target-url="' . action('\Ecdo\EcdoBatMan\EntityShops@index') . '">';
                $header .= '<i class="fa fa-plus text-muted"></i>&nbsp; 新建门店';
                $header .= '</a>';
                $header .= '</li>';
                $header .= '</ul>';
                $header .= '<span class="hidden-sm">&nbsp;</span>';
                $header .= '</header>';
                break;
        }

        return $header;
    }

    // 分页展示
    public function getPage($type)
    {
        $page = '<div class="mi-paging">';

        if ($this->total > 0) {
            if ($this->current == 0) {
                $current = 1;
            } else {
                $current = $this->current + 1;
            }

            $page .= '<span class="fn-right"><b class="b-page">(共 ' . $this->total . ' 条)</b><b class="b-page">第 ' . 
            $current . ' / ' . $this->totalPage . ' 页</b></span>';

            if ($current == 1) {
                $page .= '<a href="###" class="btn btn-default btn-xs active">';
                $page .= '<i class="fa fa-caret-left text-muted"></i>&nbsp;上一页</a>&nbsp;';
            } else {
                $parameter = '';

                if (! empty($this->search)) {
                    $parameter .= $this->search;
                } elseif (! empty($this->search_type)) {
                    $parameter .= ',' . $this->search_type;
                }

                $this->previous = $this->current - 1;
                $page .= '<a href="javascript:void(0);" class="btn btn-info btn-xs modal-previous-click" 
                data-type="' .  $type . '" data-rel=' . $this->previous . '>';
                $page .= '<i class="fa fa-caret-left text"></i>&nbsp;上一页</a>&nbsp;';
            }

            if ($current == $this->totalPage) {
                $page .= '<a href="###" class="btn btn-default btn-xs active">';
                $page .= '下一页&nbsp;<i class="fa fa-caret-right text-muted"></i></a>';
            } else {
                $parameter = '';

                if (! empty($this->search)) {
                    $parameter .= $this->search;
                } elseif (! empty($this->search_type)) {
                    $parameter .= ',' . $this->search_type;
                }

                $this->next = $this->current + 1;
                $page .= '<a href="javascript:void(0);" class="btn btn-info btn-xs modal-next-click" 
                data-type="' .  $type . '" data-rel=' . $this->next . '>';
                $page .= '下一页&nbsp;<i class="fa fa-caret-right text"></i></a>';
            }
        } else {
            $page .= '暂无相关数据';
        }

        if (! empty($this->search) || ! empty($this->search_type)) {
            $parameter = $this->search . ',' . $this->search_type;
            $page .= ' &nbsp; | <span class="fn-right modal-leave-click" 
            data-type="' .  $type . '" data-parameter="' . $parameter . '">
            <a href="javascript:void(0);" class="b-serach-page">离开搜索列表</a></span>';
        }

        $page .= '</div>';

        return $page;
    }

    // 图文预览模版
    protected function graphicsTemplatePreview($id, $type)
    {
        if ($type == 'graphics') {
            $wgu = new \Ecdo\EcdoHulk\WechatGraphicsUtils();
            $graphics = $wgu->getOneGraphics($id);
        } else {
            $wmu = new \Ecdo\EcdoHulk\WechatMaterialUtils();
            $graphics = $wmu->getOneMaterial($id);
        }
        
        $tp = <<<EOF
        <div style="margin-top:10px;margin-left:6px;">%s</div>
EOF;
        
        $html = '';
        if (count($graphics) > 0) {
            if (count($graphics['item']) == 0) {
                $html .= '<link href="' . asset('atlas/hell/hulk/css/graphics_single.css') . '" rel="stylesheet" />';
                $html .= '<div class="newmessage">';
                $html .= '<div class="left-show fn-left" id="messageList">';
                $html .= '<ul class="show-cont ui-sortable" id="J_showCont">';
                $html .= '<a style="float:right;display:none;" class="data-preview-trash text-right" 
                href="javascript:void(0);" data-type="' . $type . '" data-id="' . $id . '">';
                $html .= '<i class="fa fa-trash-o" title="删除"></i>';
                $html .= '</a>';
                $html .= '<li class="first-item state-disabled singleMsgItem" id="item_0">';
                $html .= '<div class="singleMsgMode">';
                $html .= '<h4 class="singlemessage-show-title J_change_title" data-title="title" data-default="标题">';
                $html .= $graphics['title'];
                $html .= '</h4>';
                $html .= '<div class="cover-pic J_change_image" data-image="image" data-default="封面图片">';
                $html .= '<img src="' . asset($graphics['img_url']) . '" height="100%" width="100%">';
                $html .= '</div>';
                $html .= '<div class="article-description J_change_description" data-description="description" data-default=""></div>';
                $html .= '<div class="goview singleMsgMode J_change_hrefName" data-hrefname="hrefName" data-default="立即查看">立即查看</div>';
                $html .= '</div>';
                $html .= '</li>';
                $html .= '</ul>';
                $html .= '</div>';
                $html .= '</div>';
            } else {
                $html .= '<link href="' . asset('atlas/hell/hulk/css/graphics_many.css') . '" rel="stylesheet" />';
                $html .= '<div class="left-show fn-left" id="messageList">';
                $html .= '<div class="text-right" style="display:none;margin-bottom:5px;">';
                $html .= '<a class="data-preview-trash" href="javascript:void(0);" title="删除" 
                data-type="' . $type . '" data-id="' . $id . '"><i class="fa fa-trash-o"></i></a>';
                $html .= '</div>';
                $html .= '<ul class="show-cont ui-sortable" id="J_showCont">';
                $html .= '<li class="first-item state-disabled multiMsgItem" id="item_0">';
                $html .= '<div class="multiMsgMode">';
                $html .= '<div class="multimessage-show-title">';
                $html .= '<h1 class="J_change_title" data-title="title" data-default="标题">';
                $html .= $graphics['title'];
                $html .= '</h1>';
                $html .= '<div class="title-mask-bg"></div>';
                $html .= '</div>';
                $html .= '<div class="cover-pic J_change_image" data-image="image" data-default="封面图片">';
                $html .= '<img src="' . asset($graphics['img_url']) . '" height="100%" width="100%">';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</li>';

                foreach ($graphics['item'] as $k => $v) {
                    $html .= '<li class="show-item fn-clear state-disabled" id="item_1">';
                    $html .= '<div class="cover-pic J_change_image" data-image="image" data-default="缩略图">';
                    $html .= '<img src="' . asset($v['img_url']) . '" height="100%" width="100%">';
                    $html .= '</div>';
                    $html .= '<h1 class="show-title title-break J_change_title" data-title="title" data-default="标题">';
                    $html .= $v['title'];
                    $html .= '</h1>';
                    $html .= '</li>';
                }

                $html .= '</ul>';
                $html .= '</div>';
            }
        }

        return sprintf($tp, $html);
    }

    // 图片预览模版
    protected function imageTemplatePreview($id)
    {
        $siu = new \Ecdo\EcdoSuperMan\StoreImageUtils();
        $image = $siu->getOneImage($id);

        $tp = <<<EOF
        <div style="margin-top:10px;margin-left:6px;">%s</div>
EOF;

        $html = '';
        if ($image) {
            $html .= '<div class="item">';
            $html .= '<div class="pos-rlt">';
            $html .= '<div class="item-overlay overlay-modal-preview opacity r bg-black">';

            $html .= '<div class="text-info padder m-t-sm text-sm" 
            style="white-space:nowrap;text-overflow:ellipsis;overflow:hidden;">';
            $html .= '<a href="###" title="' . $image->name . '">' . $image->name . '</a>';
            $html .= '</div>';
            
            if (! empty($image->name)) {
                $html .= '<div class="center text-center m-t-n" style="top:53%">';
            } else {
                $html .= '<div class="center text-center m-t-n">';
            }
            
            $html .= '<a class="data-preview-trash" href="javascript:void(0);" 
            data-type="voice" data-id="' . $id . '">';
            $html .= '<i class="fa fa-trash-o i-2x" title="删除"></i>';
            $html .= '</a>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<a href="###">';
            $html .= '<img src="' . asset($image->url) . '" class="r" height="130px" width="50%">';
            $html .= '</a>';
            $html .= '</div>';
            $html .= '<div class="padder-v"></div>';
            $html .= '</div>';
        }

        return sprintf($tp, $html);
    }

    // 语音预览模版
    protected function vocieTemplatePreview($id)
    {
        $svu = new \Ecdo\EcdoSuperMan\StoreVoiceUtils();
        $voice = $svu->getOneVoice($id);

        $tp = <<<EOF
        <div style="margin-top:10px;margin-left:10px;">%s</div>
EOF;

        $html = '';
        if ($voice) {
            $html .= '<div style="margin-bottom:3px;">';
            $html .= '<span style="margin-right:3px;"><b>';

            if ($voice->name) {
                $html .= $voice->name;
            } else {
                $html .= '语音';
            }
            
            $html .= '</b></span>';
            $html .= '<a class="data-preview-trash" href="javascript:void(0);" title="删除" 
            data-type="voice" data-id="' . $id . '"><i class="fa fa-trash-o"></i></a>';
            $html .= '</div>';
            $html .= '<audio controls="controls">';
            $html .= '<source src="' . asset($voice->url) . '" type="audio/mpeg" />';
            $html .= '</audio>';
        }

        return sprintf($tp, $html);
    }

    // 视频预览模版
    protected function videoTemplatePreview($id)
    {
        $svu = new \Ecdo\EcdoSuperMan\StoreVideoUtils();
        $video = $svu->getOneVideo($id);

        $tp = <<<EOF
        <div style="margin-top:10px;margin-left:10px;">%s</div>
EOF;

        $html = '';
        if ($video) {
            $html .= '<a href="' . asset($video->url) . '" target="_blank" style="margin-right:5px;">';
            $html .= '<i class="fa fa-play-circle i-2x"></i></a>';
            $html .= '<span style="margin-right:3px;"><b>';

            if ($video->name) {
                $html .= $video->name;
            } else {
                $html .= '视频';
            }
            
            $html .= '</b></span>';
            $html .= '<a class="data-preview-trash" href="javascript:void(0);" title="删除" 
            data-type="video" data-id="' . $id . '"><i class="fa fa-trash-o"></i></a>';
        }

        return sprintf($tp, $html);
    }
}
