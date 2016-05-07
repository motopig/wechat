@extends('EcdoSpiderMan::layouts.modal.default')

@section('main')
<section class="panel-default">
  <!-- 引入表单提交验证信息模版 -->
  <div class="bolt-response-error"></div>
  <div class="bolt-response-success"></div>
  <!-- 引入表单提交验证信息模版 -->

  <div class="panel-body">
    <form class="form-horizontal from-upVideoDis" method="post" action="{{ URL::to('angel/store/video/upVideoDis') }}">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
        <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
        <input type="hidden" name="id" value="{{$video->id}}" />
        <input type="hidden" name="o_name" value="{{$video->name}}" />
        
        <div class="form-group">
          <label class="col-sm-2 control-label">视频别名</label>
          <div class="col-sm-4">
            <input type="text" name="name" class="form-control" value="{{$video->name}}">
            <span class="help-block">{{{ $errors->first('name') }}}</span>
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
            <button bolt-url="angel/store/video/upVideoDis" bolt-func-success="boltAjax" bolt-form="from-upVideoDis" 
            bolt-post="true" type="button" class="btn btn-success boltClick">确认</button>&nbsp;
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
          </div>
        </div>
    </form>
  </div>
</section>
@stop