@extends('EcdoSpiderMan::layouts.site.default')

@section('main')
<section class="scrollable" id="bjax-target">
	<section id="content" class="carduse-body-click">
		<section class="panel panel-default portlet-item">
            <header class="panel-heading">
            	<i class="fa fa-user"></i>&nbsp;
              	个人信息

                <span class="text-muted m-l-sm pull-right">
                    <a href="{{URL::to(Session::get('guid') . '/member/center')}}" data-dismiss="alert" class="btn btn-default btn-xs">
                      <i class="fa fa-home text-muted"></i>&nbsp; 首页
                    </a>
                </span>
            </header>

            <ul class="list-group alt">
            	<li class="list-group-item">
                	<div class="media">
                		<span class="pull-right thumb-sm">
                  			<img src="{{asset($member->head)}}" class="img-circle">
                		</span>
                  
                		<div class="media-body">
                    		<div>头像</div>
                		</div>
                	</div>
            	</li>

            	<li class="list-group-item">
                	<div class="media">
                		<div class="pull-right text-muted">
                    		{{$member->name}}
                		</div>
                		<div class="media-body">
                			<div>昵称</div>
                		</div>
                	</div>
            	</li>

            	<li class="list-group-item">
                	<div class="media">
                		<div class="pull-right text-muted">
                    		@if ($member->gender == 'male')
                    			男
                    		@elseif ($member->gender == 'female')
                    			女
                    		@elseif ($member->gender == 'unknown')
                    			未知
                    		@else
                    			-
                    		@endif
                		</div>
                		<div class="media-body">
                			<div>性别</div>
                		</div>
                	</div>
            	</li>

            	<li class="list-group-item">
                	<div class="media">
                		<div class="pull-right text-muted">
                    		{{$member->country}} - {{$member->province}} - {{$member->city}}
                		</div>
                		<div class="media-body">
                			<div>地区</div>
                		</div>
                	</div>
            	</li>
        	</ul>
      	</section>
	</section>
</section>
@stop
