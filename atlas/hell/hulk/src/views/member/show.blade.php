@extends('EcdoSpiderMan::layouts.modal.default')

@section('main')
<section class=" panel-default">
  <div class="panel-body">
    <form class="form-horizontal">
        <div class="form-group">
          <label class="col-sm-2 control-label">
          	<span class="thumb-sm avatar m-l-xs" style="margin-top:-12px;margin-left:80px;">
              <img src="{{$member->head}}" class="dker" />
            </span>
          </label>
          <div class="col-sm-4">
            <input type="text" class="form-control" value="{{$member->name}}" disabled>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">open_id</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" value="{{$member->open_id}}" disabled>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">性别</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" value="@if ($member->gender == 'male') 男 @elseif ($member->gender == 'female') 女 @else 未知 @endif" disabled>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">地区</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" value="{{$member->country}} - {{$member->province}} - {{$member->city}}" disabled>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">所属组</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" value="{{$member->group_name}}" disabled>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">关注状态</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" value="@if ($member->concern == 'follow') 已关注 @else 已逃离 @endif" disabled>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">关注时间</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" value="{{$member->concern_time}}" disabled>
          </div>
        </div>
    </form>
  </div>
</section>
@stop