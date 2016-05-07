@extends('EcdoSpiderMan::layouts.modal.default')

@section('main')
<section class="panel-default">
  <div class="panel-body">
    <form class="form-horizontal">
        <div class="form-group">
          <label class="col-sm-2 control-label">设备ID</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" value="{{$device->device_id}}" disabled>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">uuid</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" value="{{$device->uuid}}" disabled>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">major</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" value="{{$device->major}}" disabled>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">minor</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" value="{{$device->minor}}" disabled>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">所在门店</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" value="{{$device->business_name}}" disabled>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">备注信息</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" value="{{$device->comment}}" disabled>
          </div>
        </div>
    </form>
  </div>
</section>
@stop
