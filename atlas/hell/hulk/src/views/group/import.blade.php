@extends('EcdoSpiderMan::layouts.modal.default')

@section('main')
<div class="alert alert-warning alert-block">
  <button type="button" class="close" data-dismiss="alert">×</button>
  <b>请按照以下顺序编写导入文件的数据项：</b><br />
  <p>分组名称 (必填)</p>
</div>

<section class="panel-default">
  <div class="panel-body">
    <form class="form-horizontal" enctype="multipart/form-data" method="post" action="{{ URL::to('angel/wechat/group/imGroupDis') }}">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
        <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
        
        <div class="form-group">
          <label class="col-sm-2 control-label">导入文件</label>
          <div class="col-sm-6">
            <span>
	            <input type="file" name="file" class="filestyle" data-icon="false" data-classbutton="btn btn-default" 
	            data-classinput="form-control inline v-middle input-s" style="position: fixed; left: -500px;">
	        </span>

	        <b class="badge bg-success radio-checks" data-toggle="tooltip" data-placement="bottom" 
	            data-original-title="文件支持csv格式">
	            <span class="icon-question"></span>
	        </b>

            <span class="help-block">{{{ $errors->first('file') }}}</span>
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