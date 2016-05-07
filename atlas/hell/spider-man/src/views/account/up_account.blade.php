@extends('EcdoSpiderMan::layouts.modal.default')

@section('main')
<section class="panel-default">
  <!-- 引入表单提交验证信息模版 -->
  <div class="bolt-response-error"></div>
  <div class="bolt-response-head-error"></div>
  <div class="bolt-response-success"></div>
  <!-- 引入表单提交验证信息模版 -->

  <div class="panel-body">
    <form id="form" class="form-horizontal from-upAccount" method="post" enctype="multipart/form-data" action="{{ URL::to('angel/upAccount') }}">
        <div class="form-group">
          <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
          <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />

          <label class="col-sm-2 control-label">
            <span class="thumb-sm avatar m-l-xs" style="margin-top:-12px;margin-left:80px;">
              <img @if (Session::get(Auth::angel()->get()->encrypt_id . '_angel_info_head')) src="{{asset(Session::get(Auth::angel()->get()->encrypt_id . '_angel_info_head'))}}"
              @else src="{{{ asset('/admin.png') }}}" @endif class="dker" />

            </span>
          </label>
          <div class="col-sm-4">
            <input disabled type="text" class="form-control" value="{{Auth::angel()->get()->email}}">
          </div>

        </div>
        
        <div class="form-group">
          <label class="col-sm-2 control-label">头像</label>
          <div class="col-sm-4">
            <span>
              <input bolt-head-url="{{ URL::to('angel/accountUpload') }}" type="file" name="head_file" class="filestyle head-file" data-icon="false" data-classbutton="btn btn-default" 
              data-classinput="form-control inline v-middle input-s" data-input="false" style="position: fixed; left: -500px;">
              <span class="help-block m-b-none" style="font-size:12px;color:#999;">
                支持bmp/png/jpeg/jpg/gif格式, 小于2M
              </span>
            </span>          
            <div class="bootstrap-filestyle" style="display: inline;"></div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">姓名</label>
          <div class="col-sm-4">
            <input type="text" name="name" class="form-control" value="{{$angel_info['name']}}">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">生日</label>
          <div class="col-sm-4">
            <input class="input-sm form-control" name="birthday" type="date" value="{{$angel_info['birthday']}}">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">性别</label>
          <div class="col-sm-4">
            <div class="radio i-checks">
              <label>
                <input type="radio" name="gender" value="male" @if ($angel_info['gender'] == 'male' || $angel_info['gender'] != 'female') checked @endif>
                <i></i>
                男
              </label>
            </div>
            <div class="radio i-checks">
              <label>
                <input type="radio" name="gender" value="female" @if ($angel_info['gender'] == 'female') checked @endif>
                <i></i>
                女
              </label>
            </div>
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">密码</label>
          <div class="col-sm-4">
            <input type="password" name="password" class="form-control firefox-input" placeholder="不修改请留空">
            <span class="input-group-btn">
              <button class="firefox-input-password firefox-input-btn firefox-input-btn-sm-4 btn btn-default" type="button">显示</button>
           </span>            
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
            <button bolt-url="angel/upAccount" bolt-func-success="boltAjax" bolt-form="from-upAccount" bolt-post="true" 
            type="button" class="btn btn-success boltClick fileClick">确认</button>&nbsp;
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
          </div>
        </div>
    </form>
  </div>
</section>
@stop