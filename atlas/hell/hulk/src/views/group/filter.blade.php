@extends('EcdoSpiderMan::layouts.modal.default')

@section('main')
<section class="panel-default">
  <div class="panel-body">
    <form class="form-horizontal" method="post" action="{{ URL::to('angel/wechat/group/fiGroupDis') }}">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
        <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
        
        <div class="form-group">
          <label class="col-sm-2 control-label">分组名称</label>
          <div class="col-sm-4">
            <input type="text" name="name" class="form-control">
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