@extends('EcdoSpiderMan::layouts.modal.default')

@section('main')
<section class="panel-default">
  <div class="panel-body">
    <form class="form-horizontal">
        <div class="form-group">
          <label class="col-sm-2 control-label">分组名称</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" value="{{$group->name}}" disabled>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">分组ID</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" value="{{$group->wecaht_group_id}}" disabled>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">分组人数</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" value="{{$group->count}}" disabled>
          </div>
        </div>
    </form>
  </div>
</section>
@stop