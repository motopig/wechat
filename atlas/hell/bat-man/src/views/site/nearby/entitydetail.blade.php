@extends('EcdoSpiderMan::layouts.site.default')

@section('main')
<link href="{{asset('atlas/hell/bat-man/css/entitydetail.css')}}" rel="stylesheet" />
<section id="content" class="scrollable">    
  <script src="{{$baidu}}">
  </script>

  <div class="row alert">
    @if (count($detail) == 0)
    没有查到门店信息！
    @else
    <div class="img-market">
    <img src="{{$detail['store_image'][0]['store_image_url']}}" style="width: 100%; height: 260px;" />
    </div>

    <div class="media media-l">
      <div class="pull-left">
        <i class="fa fa-home bigger-110"></i>
      </div>
      <div class="media-body">
          <h4 class="media-heading">{{$detail['business_name']}}</h4>
        <hr class="hr-middle" />
      </div>
    </div>

    <div class="media media-l">
      <div class="pull-left">
        <i class="icon-pointer bigger-110"></i>
      </div>
      <div class="media-body">
          <h4 class="media-heading">{{$detail['province']}}{{$detail['city']}}{{$detail['district']}}{{$detail['address']}}</h4>
        <hr class="hr-middle" />
      </div>
    </div>

    <div class="media media-l">
      <div class="pull-left">
        <i class="fa fa-phone light-blue bigger-110"></i>
      </div>
      <div class="media-body">
          <h4 class="media-heading">
            <a href="tel:{{$detail['item']['telephone']}}" style="color:#333;text-decoration:none;">
              {{$detail['item']['telephone']}}
            </a>
          </h4>
        <hr class="hr-middle" />
      </div>
    </div>

    <div class="img-baidu">
    <lbs-map>
      <lbs-poi name="{{$detail['province']}}{{$detail['city']}}{{$detail['district']}}{{$detail['address']}}" 
      location="{{$detail['longitude']}},{{$detail['latitude']}}" 
      addr="{{$detail['province']}}{{$detail['city']}}{{$detail['district']}}{{$detail['address']}}" 
      icon-src="asset(marker_red_sprite.png)" height="30px" width="30px">
      </lbs-poi>
    </lbs-map>
    </div>
    @endif
  </div>
</section>
@stop
