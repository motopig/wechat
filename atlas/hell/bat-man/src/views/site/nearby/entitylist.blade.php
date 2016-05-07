@extends('EcdoSpiderMan::layouts.site.default')

@section('main')
<link href="{{asset('atlas/hell/bat-man/css/entitylist.css')}}" rel="stylesheet" />
<section id="content" class="scrollable">    
  <div class="row alert">
  @if (count($list) == 0)
  没有查到门店信息！
  @else
  <div class="breadcrumbs">
  <p class="muted">
  <b>距离您 {{$list['nearby_km']}} 公里以内的门店</b>
  </p>
  </div>

  <div class="media-breadcrumbs">
    @foreach ($list['shop'] as $k => $v)
    <div class="media">
      <a class="pull-left" href="{{$v['url']}}">
        <img class="media-object" src="{{$v['img_url']}}" />
      </a>
      <div class="media-body">
        <a class="pull" href="{{$v['url']}}">
          <h4 class="media-heading">{{$v['business_name']}}</h4>
          <div class="content">
          <span class="fn">
            <i class="icon-pointer light-orange bigger-110"></i>&nbsp;
            {{$v['km']}}
          </span>
          </div>
        </a>
        <div class="pull-right">
        <a class="pull" href="{{$v['url']}}">
        <i class="fa fa-chevron-right blue bigger-110"></i>
        </a>
        </div>
        <hr class="hr-middle" />
      </div>
    </div>
    @endforeach
  </div>
  @endif
  </div>
</section>
@stop
