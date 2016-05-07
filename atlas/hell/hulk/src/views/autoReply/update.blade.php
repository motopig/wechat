@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<link href="{{asset('atlas/hell/hulk/css/auto_reply.css')}}" rel="stylesheet" />
<section class="panel panel-default">
    <div class="panel-body">
      <form class="form-horizontal form-auto-reply" method="post" action="{{URL::to('angel/wechat/autoReply/upAutoReplyDis')}}">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
        <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />

        <div class="form-group">
          <label class="col-sm-2 control-label">规则名称</label>
          <div class="col-sm-7">
            <input type="text" class="form-control auto-reply-name" value="{{$autoreply['name']}}">

            <label class="checkbox-inline i-checks">
              <input type="checkbox" value="{{$autoreply['concern']}}" @if ($autoreply['concern'] == '1') checked @endif 
              class="auto-reply-concern @if ($autoreply['concern'] == '1') checks-1 @else checks-0 @endif"><i></i> 设置关注自动回复
            </label>
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">关键词</label>

          <div class="keyword-container col-sm-7">
            <div class="keyword-hack">
                @foreach ($autoreply['item'] as $i)
                  <span class="data-keyword">{{$i->keyword}}</span>
                @endforeach
                <input type="text" id="rule-keyword" maxlength="10" placeholder="最多5个关键词，用回车分隔" 
                style="outline:none;padding-left:6px;border:none;width:400px;color:#666;">
            </div>
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">回复内容</label>
          <div class="col-sm-7" style="z-index: 999;">
              <div class="btn-toolbar m-b-sm btn-editor" data-role="editor-toolbar" data-target="#editor">
                  <div class="emoji_list" style="display: none;">

                  </div>
                <div class="btn-group">
                  <a class="btn btn-default btn-sm wechat_emoji noclick" data-edit="bold" href="javascript:void(0);">
                    <i class="fa fa-smile-o"></i>&nbsp; 表情
                  </a>
                </div>

                <div class="btn-group">
                  <a href="javascript:void(0);" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-file-text"></i>&nbsp; 图文 &nbsp;<b class="caret"></b>
                  </a>
                    <ul class="dropdown-menu">
                      <li>
                        <a href="javascript:void(0);" class="bolt-modal-click" data-type="graphics">
                          微信图文
                        </a>
                      </li>
                      <li>
                        <a href="javascript:void(0);" class="bolt-modal-click" data-type="material">
                          高级图文
                        </a>
                      </li>
                    </ul>
                </div>
                
                <div class="btn-group">
                  <a href="javascript:void(0);" class="btn btn-default btn-sm bolt-modal-click" data-type="image">
                    <i class="fa fa-picture-o"></i>&nbsp; 图片
                  </a>

                  <a href="javascript:void(0);" class="btn btn-default btn-sm bolt-modal-click" data-type="voice">
                    <i class="fa fa-microphone"></i>&nbsp; 语音
                  </a>

                  <a href="javascript:void(0);" class="btn btn-default btn-sm bolt-modal-click" data-type="video">
                    <i class="fa fa-video-camera"></i>&nbsp; 视频
                  </a>
                </div>
              </div>

              <div id="editor" style="overflow:scroll;overflow-x:hidden;height:222px;max-height:600px; @if ($autoreply['type'] != '0') display:none; @endif" 
              class="form-control" contenteditable="true">@if ($autoreply['type'] == '0') {{$autoreply['content']}} @endif</div>

              <div id="modal-editor" style="overflow:scroll;overflow-x:hidden;height:222px;max-height:600px; @if ($autoreply['type'] == '0') display:none; @endif" 
                class="form-control">@if (count($autoreply['preview']) > 0) {{$autoreply['preview']['html']}} @endif</div>

              <span id="modal-editor-data" data-type="@if (count($autoreply['preview']) > 0) {{$autoreply['preview']['type']}} @endif" 
              data-id="@if (count($autoreply['preview']) > 0) {{$autoreply['preview']['id']}} @endif" style="display:none;">
              </span>
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

          <div class="form-group">
            <div class="col-sm-4 col-sm-offset-2">
              <button type="button" class="btn btn-success auto-reply-click" data-id="@if ($autoreply['id']) {{$autoreply['id']}} @endif">保存</button>&nbsp;
              <a href="{{ URL::to('angel/wechat/autoReply') }}">
                <button type="button" class="btn btn-default">返回</button>
              </a>
            </div>
          </div>
      </form>
    </div>
</section>

<!-- 引入公用模态框 -->
@include('EcdoSpiderMan::layouts.modal.dialog')
<script src="{{asset('atlas/hell/hulk/js/auto_reply.js')}}"></script>
@stop