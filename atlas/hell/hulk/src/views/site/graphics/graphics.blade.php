@extends('EcdoSpiderMan::layouts.site.default')

@section('main')
<section id="content" class="scrollable" style="padding-top:10px;padding-left:3px;padding-right:3px;">    
  <h4>{{$graphics['title']}}</h4>
  <hr />

  <div style="margin-bottom:20px;">
    {{$graphics['updated_at']}} 
    <span style="margin-left:10px;">
      <a href="javascript:void(0);">{{Session::get('tower_name')}}</a>
    </span>
  </div>

  <div>
    @if ($graphics['show_cover_pic'] == '1')
      <img src="{{asset($graphics['img_url'])}}">
    @endif

    <div>
      {{$graphics['content']}}
    </div>
  </div>
</section>
@stop
