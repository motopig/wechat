@extends('EcdoSpiderMan::layouts.modal.default')

@section('main')
<section class="panel-default">
 <div class="panel-body">
    <form class="form-horizontal" enctype="multipart/form-data" method="post" action="{{ URL::to('angel/store/video/crVideoDis') }}">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
        <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
        
        <div class="form-group">
          <label class="col-sm-2 control-label">上传视频</label>
          <div class="col-sm-6">
            <span>
              <input type="file" name="file" class="filestyle" data-icon="false" data-classbutton="btn btn-default" 
              data-classinput="form-control inline v-middle input-s" style="position: fixed; left: -500px;">
            </span>

            <span class="help-block m-b-none">
              支持mp4格式, 小于10M
            </span>
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
            <button type="submit" class="btn btn-success">确认</button>&nbsp;
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
          </div>
        </div>
    </form>
  </div>
</section>
@stop