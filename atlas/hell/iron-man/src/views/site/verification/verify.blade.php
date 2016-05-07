@extends('EcdoSpiderMan::layouts.site.default')

@section('main')
<section id="content" class="scrollable">
  <div style="margin-bottom: 20px;">  
	  <h4 style="text-align: center;">
      {{$request['verification']->status == 0 ? '核销员信息审核中' : '当前账号已禁用'}}
    </h4>
  </div>
	
  <section class="panel panel-default">
    <header class="panel-heading bg-light no-border">
      <div class="clearfix">
        <a href="###" class="pull-left thumb-md avatar b-3x m-r">
          <img src="{{asset($request['verification']['wechat']->head)}}" alt="...">
        </a>
        <div class="clear">
          <div class="h4 m-t-xs m-b-xs">
            {{$request['verification']['wechat']->name}}
          </div>
        </div>
      </div>
    </header>
    <div class="list-group no-radius alt">
      <a class="list-group-item" href="#">
        性别：
        @if ($request['verification']['wechat']->gender == 'male')
            男
        @elseif ($request['verification']['wechat']->gender == 'female')
            女
        @elseif ($request['verification']['wechat']->gender == 'unknown')
            未知
        @else
            -
        @endif
      </a>

      <a class="list-group-item" href="#">
        地区：
        {{$request['verification']['wechat']->country}} -
        {{$request['verification']['wechat']->province}} -
        {{$request['verification']['wechat']->city}}
      </a>

      <a class="list-group-item" href="#">
        姓名：{{$request['verification']['info']['name']}}
      </a>

      <a class="list-group-item" href="#">
        电话：{{$request['verification']['info']['mobile']}}
      </a>
    </div>
  </section>
</section>
@stop
