@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<link href="{{asset('atlas/hell/iron-man/css/verification.css')}}" rel="stylesheet" />

<section class="panel panel-default" id="verification-body">
    <div class="panel-body">
      <form class="form-horizontal form-verification" method="post" action="{{URL::to('angel/verificationUpdateDis')}}">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
        <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
        <input type="hidden" class="verification-id" value="{{$verification->id}}" />
        <input type="hidden" class="verification-store" @if (isset($verification->store)) value="{{$verification->store}}" @endif />
		<input type="hidden" class="bolt-modal-preview-url" value="{{URL::to('angel/coupons/store')}}" />
        
        <h3 class="frm_title">核销员基本信息</h3>
        <div class="form-group">
          <label class="col-sm-2 control-label">
          	<span class="thumb-sm avatar m-l-xs" style="margin-top:-12px;">
          		<img src="{{asset($verification->wechat->head)}}">
          	</span>
          </label>
          <div class="col-sm-5">
            <p class="form-control-static">{{$verification->openid}}</p>
          </div>
        </div>

        <div class="form-group cfgb">
          <label class="col-sm-2 control-label">昵称</label>
          <div class="col-sm-5">
            <p class="form-control-static">{{$verification->wechat->name}}</p>
          </div>
        </div>

        <div class="form-group cfgb">
          <label class="col-sm-2 control-label">性别</label>
          <div class="col-sm-5">
            <p class="form-control-static">
            	@if ($verification->wechat->gender == 'male')
            		男
            	@elseif ($verification->wechat->gender == 'female')
            		女
            	@elseif ($verification->wechat->gender == 'unknown')
            		未知
            	@else
            		-
            	@endif
            </p>
          </div>
        </div>

        <div class="form-group cfgb">
          <label class="col-sm-2 control-label">地区</label>
          <div class="col-sm-5">
            <p class="form-control-static">
            	{{$verification->wechat->country}} - 
            	{{$verification->wechat->province}} - 
            	{{$verification->wechat->city}}
            </p>
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">核销员姓名</label>
          <div class="col-sm-5">
            <input type="text" class="form-control verification-name" value="{{$verification->info['name']}}" 
            placeholder="核销员真实姓名">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">核销员电话</label>
          <div class="col-sm-5">
            <input type="text" class="form-control verification-mobile" value="{{$verification->info['mobile']}}" 
            placeholder="核销员联系方式">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">审核状态</label>
          <div class="col-sm-7">
          	@foreach ($status as $k => $v)
          		@if ($k != 0)
	          		<label class="radio-inline i-checks">
	            		<input class="verification-status" type="radio" name="disabled" value="{{$k}}" 
	            		@if ($k == $verification->status && $verification->status != 0) checked @endif><i></i> {{$v}}
	            	</label>
            	@endif
          	@endforeach
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="line-top"></div>
        <h3 class="frm_title">核销员核销权限
          <span class="frm_title_dec">无指定门店为最高权限，任意卡券都可核销</span>
        </h3>

        <div class="form-group">
	      <label class="col-sm-2 control-label">适用门店</label>
	      <div class="col-sm-10">
	        <div class="radio i-checks">
	          <label>
	            <input type="radio" class="verification-location_id_list" name="location_id_list" value="store" 
	            @if (isset($verification->store)) checked @endif>
	            <i></i>
	            指定门店适用 
	            <span class="frm_tips">
	              (<a href="javascript:void(0);" class="bolt-modal-click" data-type="store">添加适用门店</a>)
	            </span>
	          </label>
	        </div>

	        <div class="store-look">
	          <section class="panel panel-default portlet-item" style="margin-bottom: 10px;">
	              <ul class="list-group alt" id="verification-group-item">
	              	@if (isset($verification->store))
		              	@foreach ($verification->store as $k => $v)
			              	<li class="list-group-item">
							    <div class="media">
							      <div class="pull-right" style="font-size:18px;">
							        <a class="store-list" href="javascript:void(0);" data-id="{{$v->id}}" title="删除">
							          <i class="fa fa-trash-o"></i>
							        </a>
							      </div>
							      <div class="media-body">
							        <div>{{$v->business_name}}</div>
							      </div>
							    </div>
							</li>
						@endforeach
					@endif
	              </ul>
	            </section>
	        </div>

	        <div class="radio i-checks">
	          <label>
	            <input type="radio" class="verification-location_id_list" name="location_id_list" value="all" 
	            @if ($verification->location_id_list == 'all') checked @endif>
	            <i></i>
	            全部门店适用
	          </label>
	        </div>

	        <div class="radio i-checks">
	          <label>
	            <input type="radio" class="verification-location_id_list" name="location_id_list" value="null" 
	            @if ($verification->location_id_list == 'null') checked @endif>
	            <i></i>
	            无指定门店
	          </label>
	        </div>
	      </div>
	    </div>
	    <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
            <button type="button" class="btn btn-success verification-subPost" data-id="">保存</button>&nbsp;
            <a href="{{ URL::to('angel/verification') }}">
              <button type="button" class="btn btn-default">返回</button>
            </a>
          </div>
        </div>
      </form>
    </div>
</section>

<script src="{{asset('assets/universe/js/handlebars.js')}}"></script>
<script src="{{asset('atlas/hell/iron-man/js/verification.js')}}"></script>
@include('EcdoIronMan::coupons/handlebars/item')
@include('EcdoSpiderMan::layouts.modal.dialog')

@stop
