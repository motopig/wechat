@extends('god.layouts.default.frame')
@section('main')
    <aside class="bg-white">
      <section class="vbox">
        <header class="header bg-light lt">
          <ul class="nav nav-tabs nav-white">
            <li class="active"><a href="#tagAll" data-toggle="tab">全部</a></li>
            <li class=""><a href="#tagBase" data-toggle="tab">基础</a></li>
            <li class=""><a href="#tagOpt" data-toggle="tab">可选</a></li>
          </ul>
        </header>
        <section class="scrollable">
          <div class="tab-content">
            <div class="tab-pane active" id="tagAll">
@if (empty($stars))
    <div class="text-center wrapper">
        暂无应用
    </div>
@endif
@foreach ($stars as $star)
              <ul class="list-group no-radius m-b-none m-t-n-xxs list-group-lg no-border">
                <li class="list-group-item">
                  <a href="#" bolt-data="star={{$star['star']}}" bolt-url="god/appCenter/show" bolt-func-success="showStar" class="thumb-sm pull-left m-r-sm boltClick">
                  @if (empty($star['icon']))
                    <i class="fa fa-star"></i>
                  @else
                    <img src="{{$star['icon']}}" class="img-circle">
                  @endif
                  </a>
                  <a href="#" bolt-data="star={{$star['star']}}" bolt-url="god/appCenter/show" bolt-func-success="showStar" class="clear boltClick">
                    <small class="pull-right"></small>
                    <strong class="block">{{$star['title']}}</strong>
                    <small>{{$star['star']}}</small>
                  </a>
                </li>
              </ul>
@endforeach
            </div>
            <div class="tab-pane" id="tagBase">
@if (empty($baseStars))
    <div class="text-center wrapper">
        暂无应用
    </div>
@endif
@foreach ($baseStars as $star)
              <ul class="list-group no-radius m-b-none m-t-n-xxs list-group-lg no-border">
                <li class="list-group-item">
                  <a href="#" bolt-data="star={{$star['star']}}" bolt-url="god/appCenter/show" bolt-func-success="showStar" class="thumb-sm pull-left m-r-sm boltClick">
                  @if (empty($star['icon']))
                    <i class="fa fa-star"></i>
                  @else
                    <img src="{{$star['icon']}}" class="img-circle">
                  @endif
                  </a>
                  <a href="#" bolt-data="star={{$star['star']}}" bolt-url="god/appCenter/show" bolt-func-success="showStar" class="clear boltClick">
                    <small class="pull-right"></small>
                    <strong class="block">{{$star['title']}}</strong>
                    <small>{{$star['star']}}</small>
                  </a>
                </li>
              </ul>
@endforeach
              </div>
            <div class="tab-pane" id="tagOpt">
@if (empty($optStars))
    <div class="text-center wrapper">
        暂无应用
    </div>
@endif
@foreach ($optStars as $star)
              <ul class="list-group no-radius m-b-none m-t-n-xxs list-group-lg no-border">
                <li class="list-group-item">
                  <a href="#" bolt-data="star={{$star['star']}}" bolt-url="god/appCenter/show" bolt-func-success="showStar" class="thumb-sm pull-left m-r-sm boltClick">
                  @if (empty($star['icon']))
                    <i class="fa fa-star"></i>
                  @else
                    <img src="{{$star['icon']}}" class="img-circle">
                  @endif
                  </a>
                  <a href="#" bolt-data="star={{$star['star']}}" bolt-url="god/appCenter/show" bolt-func-success="showStar" class="clear boltClick">
                    <small class="pull-right"></small>
                    <strong class="block">{{$star['title']}}</strong>
                    <small>{{$star['star']}}</small>
                  </a>
                </li>
              </ul>
@endforeach
            </div>
          </div>
        </section>
      </section>
    </aside>
    <aside class="col-lg-6 b-l hide showDetail">
      <section class="vbox">
        <section class="scrollable padder-v">
        </section>
      </section>              
    </aside>
@stop

@section('scripts')
<script>
(function() {
	$(document).ready(function() {
	    var showStar = function(html) {
	        $(".showDetail").removeClass("hide");
	        $(".showDetail .scrollable").html(html);
	    }

	    $.hell.fn.regFunc("showStar", showStar);
	});
})();
</script>
@stop