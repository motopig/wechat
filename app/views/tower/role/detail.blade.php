@extends('EcdoSpiderMan::layouts.modal.default')
@section('title')
角色明细
@stop
@section('main')
<section class="panel panel-default">
  <div class="bolt-response-error"></div>
  <div class="bolt-response-success"></div>
  <div class="panel-body">
    <div class="row">
      <div class="col-lg-2">
        <label class="control-label">角色名称</label>
      </div>
      <div class="col-lg-4">
        {{$role['title']}}
        <span class="help-block"></span>
      </div>
    </div>
    <div class="line b-b line-lg pull-in"></div>
@foreach ($perms as $group)
@if (++ $i > 1)
    <div class="line line-dashed b-b line-lg pull-in"></div>
@endif
    <div class="row">
        <div class="col-lg-2">
          <label class="control-label"  data-toggle="tooltip" data-original-title="{{$group['desc']}}">
               {{$group['title']}}
          </label>
        </div>
        <div class="col-lg-10 clkOpts">
            <div>
            @foreach ($group['perms'] as $row)
                <label class="btn btn-sm btn-info @if (! empty($row['checked'])) active @endif" data-toggle="tooltip" data-original-title="{{$row['desc']}}">
                    <i class="fa fa-check-square text"></i> {{$row['title']}}
                </label>
            @endforeach
            </div>
        </div>
    </div>
@endforeach
    <div class="line b-b line-lg pull-in"></div>
      <div class="row">
          <div class="col-lg-2">
            <label class="control-label">说明</label>
          </div>
      <div class="col-lg-8">
        {{$role['desc']}}
      </div>
      </div>
  </div>
</section>
@stop
