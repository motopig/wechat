@extends('EcdoSpiderMan::layouts.site.default')

@section('main')
<link href="{{asset('atlas/hell/iron-man/css/create_coupons.css')}}" rel="stylesheet" />
<link href="{{asset('atlas/hell/iron-man/css/editor_section_shop.css')}}" rel="stylesheet" />

<section class="scrollable" id="bjax-target">
	<section class="panel panel-default portlet-item">
	    <header class="panel-heading">                    
	      {{$coupons_type[$coupons->coupons_type]}}详情
	    </header>

	    <section class="panel-body">
	    	@if ($coupons->coupons_type == 'DISCOUNT' || $coupons->coupons_type == 'CASH')
		   	<article class="media">
		        <div class="media-body">
		        	<span class="h5">
		        		<b>{{$coupons->favourable}}</b>
		        	</span>
		          	<small class="block m-t-sm">
		          		打{{$coupons->coupons_setting}}折
		          	</small>
		        </div>
	      	</article>
	      	<div class="line pull-in"></div>
			@endif

			<article class="media">
		        <div class="media-body">
		        	<span class="h5">
		        		<b>优惠详情</b>
		        	</span>
		          	<small class="block m-t-sm">
		          		{{$coupons->default_detail}}
		          	</small>
		        </div>
	      	</article>
	      	<div class="line pull-in"></div>

	      	<article class="media">
		        <div class="media-body">
		        	<span class="h5">
		        		<b>使用须知</b>
		        	</span>
		          	<small class="block m-t-sm">
		          		{{$coupons->description}}
		          	</small>
		        </div>
	      	</article>
	      	<div class="line pull-in"></div>

	      	<article class="media">
		        <div class="media-body">
		        	<span class="h5">
		        		<b>客服电话</b>
		        	</span>
		          	<small class="block m-t-sm">
		          		{{$coupons->service_phone != '' ? $coupons->service_phone : '-'}}
		          	</small>
		        </div>
	      	</article>
	    </section>
  	</section>

  	<div class="btn-group btn-group-justified" style="padding-left:60px;padding-right:60px;">
    	<a href="{{URL::to(Session::get('guid') . '/card/codeInfo/' . $code . '/' . $action)}}" class="btn btn-dark">
    		返回
    	</a>
    </div>
</section>
@stop
