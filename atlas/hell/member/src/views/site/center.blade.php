@extends('EcdoSpiderMan::layouts.site.default')

@section('main')
<section class="scrollable" id="bjax-target">
	<section id="content" class="carduse-body-click">
		
		<section class="panel panel-default">
            <header class="panel-heading bg-light no-border">
            	<div class="clearfix">
                	<a href="{{URL::to(Session::get('guid') . '/member/info')}}" class="pull-left thumb-md avatar b-3x m-r">
                		<img src="{{asset($member->head)}}">
                	</a>
	                <div class="clear">
	                	<div class="h3 m-t-xs m-b-xs">
	                		<a href="{{URL::to(Session::get('guid') . '/member/info')}}">
	                    		{{$member->name}}
	                    		<i class="fa fa-angle-right pull-right m-t-sm" style="font-size:14px;"></i>
	                    	</a>
	                	</div>
	                  	<small class="text-muted">会员中心</small>
	                </div>
            	</div>
            </header>
        </section>

    	<section class="panel panel-default">
    		<div class="list-group no-radius alt">
	        	<a class="list-group-item" href="{{URL::to(Session::get('guid') . '/member/card')}}">
	            	<i class="fa fa-angle-right pull-right"></i>
	            	<i class="fa fa-credit-card"></i> &nbsp;
	            	卡券
	        	</a>
    		</div>
    	</section>

	</section>
</section>
@stop
