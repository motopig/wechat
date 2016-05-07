@extends('EcdoSpiderMan::layouts.modal.default')

@section('main')
<section class="panel-default">
  <div class="panel-body">
    
    @if (count($graphics['item']) == 0)
    <link href="{{{ asset('atlas/hell/hulk/css/graphics_single.css') }}}" rel="stylesheet" />
  	<div class="newmessage">
		<div class="left-show fn-left" id="messageList">
			<ul class="show-cont ui-sortable" id="J_showCont">
            	<li class="first-item state-disabled singleMsgItem" id="item_0">
                    <div class="singleMsgMode">
                        <h4 class="singlemessage-show-title J_change_title" data-title="title" data-default="标题">
                        	{{$graphics['title']}}
                        </h4>

                        <div class="cover-pic J_change_image" data-image="image" data-default="封面图片">
                        	<img src="{{asset($graphics['img_url'])}}" height="100%" width="100%">
                        </div>

                        <div class="article-description J_change_description" data-description="description" data-default=""></div>
                        <div class="goview singleMsgMode J_change_hrefName" data-hrefname="hrefName" data-default="立即查看">立即查看</div>
                    </div>
            	</li>
            </ul>
        </div>
    </div>
    @else
    <link href="{{{ asset('atlas/hell/hulk/css/graphics_many.css') }}}" rel="stylesheet" />
    <div class="left-show fn-left" id="messageList">
		<ul class="show-cont ui-sortable" id="J_showCont">
			<li class="first-item state-disabled multiMsgItem" id="item_0">
                <div class="multiMsgMode">
                    <div class="multimessage-show-title">
                    	<h1 class="J_change_title" data-title="title" data-default="标题">
                    		{{$graphics['title']}}
                    	</h1>

                    	<div class="title-mask-bg"></div>
                    </div>

                    <div class="cover-pic J_change_image" data-image="image" data-default="封面图片">
                    	<img src="{{asset($graphics['img_url'])}}" height="100%" width="100%">
                    </div>
                </div>
            </li>

            @foreach ($graphics['item'] as $k => $v)
            <li class="show-item fn-clear state-disabled" id="item_1">
                <div class="cover-pic J_change_image" data-image="image" data-default="缩略图">
                	<img src="{{asset($v['img_url'])}}" height="100%" width="100%">
                </div>

                <h1 class="show-title title-break J_change_title" data-title="title" data-default="标题">
                	{{$v['title']}}
                </h1>
            </li>
            @endforeach
		</ul>
	</div>
	@endif
  </div>
</section>
@stop