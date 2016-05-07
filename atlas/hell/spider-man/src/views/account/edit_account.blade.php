@extends('EcdoSpiderMan::layouts.clear.nonav')

@section('main')
<div class="col-xs-12 col-sm-10">
<section class="apanel">
    <header class="apanel-heading">
	<i class="fa fa-user"></i>&nbsp;编辑资料
  </header>

  <div class="apanel-body">
    <form id="form" class="form-horizontal from-upAccount" method="post" enctype="multipart/form-data" action="{{ URL::to('angel/account/save') }}">
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
            {{Auth::angel()->get()->email}}
          </div>

          
        </div>
        
        <div class="form-group">
          <label class="col-xs-3 col-sm-2 control-label">头像</label>
          <div class="col-xs-9 col-sm-4">
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
          <label class="col-xs-3 col-sm-2 control-label">姓名</label>
          <div class="col-xs-9 col-sm-4">
            <input type="text" name="name" class="form-control" value="{{$angel_info['name']}}">
          </div>
        </div>

        <div class="form-group">
          <label class="col-xs-3 col-sm-2 control-label">生日</label>
          <div class="col-xs-9 col-sm-4">
            <input class="input-sm form-control" name="birthday" type="date" value="{{$angel_info['birthday']}}">
          </div>
        </div>

        <div class="form-group">
          <label class="col-xs-3 col-sm-2 control-label">性别</label>
          <div class="col-xs-9 col-sm-4">
            <div class="radio i-checks">
              <label>
                <input type="radio" name="gender" value="male" @if ($angel_info['gender'] == 'male' || $angel_info['gender'] != 'female') checked @endif>
                <i></i>
                男
              </label>
              &nbsp;
              <label>
                <input type="radio" name="gender" value="female" @if ($angel_info['gender'] == 'female') checked @endif>
                <i></i>
                女
              </label>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-xs-3 col-sm-2 control-label">密码</label>
          <div class="col-xs-9 col-sm-4">
            <input type="password" name="password" class="form-control firefox-input" placeholder="不修改请留空">
            <span class="input-group-btn">
              <button class="firefox-input-password firefox-input-btn firefox-input-btn-sm-4 btn btn-default no-radius" type="button">显示</button>
           </span>            
          </div>
        </div>
        
        <div class="form-group">
          <label class="col-xs-3 col-sm-2 control-label">推荐人/邀请码</label>
          <div class="col-xs-9 col-sm-4">
              <span>
              @if($angel_info['refer'])
              {{$angel_info['refer']}}
              @else
              无
              @endif
              </span>
          </div>
        </div>

        <div class="form-group" style="margin-top:20px;">
          <div class="col-sm-4 col-xs-offset-3 col-sm-offset-2">
            <button type="submit" class="btn btn-success boltClick fileClick">确认</button>&nbsp;
            <a href="{{URL::to('angel')}}">
              <button type="button" class="btn btn-default" data-dismiss="modal">返回</button>
            </a>
          </div>
        </div>
    </form>
  </div>
</section>
</div>
<script src="{{{ asset('atlas/hell/spider-man/js/file-input/bootstrap-filestyle.min.js') }}}"></script>
@stop