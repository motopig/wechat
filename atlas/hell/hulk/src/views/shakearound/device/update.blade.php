@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<link href="{{asset('atlas/hell/hulk/css/devicepage.css')}}" rel="stylesheet" />

<section class="panel panel-default">
	<div class="panel-body bind-device-page">
		<form method="POST" action="{{URL::to('angel/wechat/shakearound/deviceUpdateDis')}}">
			<input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
			<input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
    		<input type="hidden" name="device_id" value="{{$device['device_id']}}">
    		
			<div class="alert-info">
				<span>
			        设备ID：{{$device['device_id']}}
			    </span>
			    <br />

			    <span>
			        UUID：{{$device['uuid']}}
			    </span>

			    <span class="left-style-info">
			        Major：{{$device['major']}}
			    </span>

			    <span class="left-style-info">
			        Minor：{{$device['minor']}}
			    </span>
			</div>
			<div class="line line-dashed b-b line-lg pull-in"></div>

        	<div class="mod-form__group">
            	<label class="mod-form__label"> 设备型号 (选填) </label>
            	<div class="mod-form__controls"> 
	              	<span class="mod-form__box">
	                	<input type="text" class="input form-control" name="model" value="{{$device['model']}}" 
	                	placeholder="设备真实序列号">
	                </span>
	           		<p class="mod-form__boxtips"></p>
              	</div>
            </div>

            <div class="mod-form__group">
            	<label class="mod-form__label"> 备注信息 (选填) </label>
	              <div class="mod-form__controls">
	              	<span class="mod-form__box">
	                	<input type="text" class="input form-control" name="comment" value="{{$device['comment']}}" 
	                	maxlength="15" placeholder="设备的备注信息，不超过15个字">
	               	</span>
	                <p class="mod-form__boxtips"></p>
	              </div>
            </div>

          	<div class="mod-form__group">
            	<label class="mod-form__label"> 所在门店 </label>
            	<div class="mod-form__controls"> 
              		<span class="mod-form__box">
		                <select name="sid" class="form-control m-b" style="width:120px;">
			              <option value="">请选择</option>
			              @if (count($entityshop) > 0)
			                @foreach ($entityshop as $k => $v)
			                  <option @if ($v->sid == $device['sid']) selected @endif 
			                  value="{{$v->sid}}">{{$v->business_name}}</option>
			                @endforeach
			              @endif
			            </select>
	            	</span>
                	<p class="mod-form__boxtips"></p>
              	</div>
            </div>
            <div class="line line-dashed b-b line-lg pull-in"></div>

	       	<div class="ui-c-red ui-mb-large">如果配置多个页面，摇出的页面将会以随机的方式出现</div>
		    <select class="add-device form-control m-b" style="width:120px;">
				@if (count($page) > 0)
					<option value="">新增页面</option>
	                @foreach ($page as $k => $v)
	                    <option value="{{$k}}">
	                        {{$v->title}}
	                    </option>
	                @endforeach
	            @endif
		    </select>
	       	<div class="mod-sep-20"></div>

			<ul class="selected_page_list" style="list-style-type:none;margin-left:-40px;">
			  @if (isset($device_bind_page))
				   	@foreach ($device_bind_page as $k => $v)
				   		@if ($v->page_item)
				   			<li class="ui-mb-large page-{{$v->page_item['page_id']}}">
						    	<div class="ui-clearfix">
						    		<div class="mod-weixin-share_shake ui-fl-l ui-mr-large">
						        		<div class="mod-weixin-share_shake-img">
						                	<img class="avatar" width="56px" height="56px" src="{{$v->page_item['icon_url']}}">
						            	</div>

						            	<div class="mod-weixin-share_shake-content">
						                	<div class="mod-weixin-share_shake-title">{{$v->page_item['title']}}</div>
						                	<div class="mod-weixin-share_shake-desc">{{$v->page_item['description']}}</div>
						            	</div>
						        	</div>
						       	</div>

						        <div class="ui-mt-small" style="text-overflow: ellipsis;overflow: hidden;">
						        	备注信息 <span class="ui-ml-large ui-c-gray">{{$v->page_item['comment']}}</span>
						        </div>
						        
						        <div class="imageList">
						        	<div class="imageList delete-news-item">
						            	<a href="javascript:void(0);" data-id="{{$v->page_item['page_id']}}" class="close top">×</a>
						            </div>
					            </div>

					            <input type="hidden" name="page_id[]" value="{{$v->page_item['page_id']}}" />
						   </li>
				   		@endif
				   	@endforeach
			   @endif 
			</ul>

            <div class="line line-dashed b-b line-lg pull-in"></div> 
            <div style="text-align:center;">
            	<button type="submit" class="btn btn-success">保存</button>&nbsp;
	            <a href="{{ URL::to('angel/wechat/shakearound/device') }}">
	              <button type="button" class="btn btn-default">返回</button>
	            </a>
	        </div>
	    </form>
	</div>
</section>

<script type="text/javascript">
// 新增页面
$('.add-device').change(function () {
	var html = '';
	var id = $(this).val();

	if (id != '') {
	  $('.add-device').val('');
	  var page = {{$json_page}}[id];

	  if ($('.ui-mb-large').hasClass('page-'+page.page_id)) {
	    return false;
	  }

	  if (page.comment == null) {
	    page.comment = '';
	  }

	  html += '<li class="ui-mb-large page-'+page.page_id+'">';
	  html += '<div class="ui-clearfix">';
	  html += '<div class="mod-weixin-share_shake ui-fl-l ui-mr-large">';
	  html += '<div class="mod-weixin-share_shake-img"><img class="avatar" width="56px" height="56px" src="'+page.icon_url+'"></div>';
	  html += '<div class="mod-weixin-share_shake-content">';
	  html += '<div class="mod-weixin-share_shake-title">'+page.title+'</div>';
	  html += '<div class="mod-weixin-share_shake-title">'+page.description+'</div>';
	  html += '</div></div></div>';         
	  html += '<div class="ui-mt-small" style="text-overflow: ellipsis;overflow: hidden;">';
	  html += '备注信息 <span class="ui-ml-large ui-c-gray">'+page.comment+'</span></div>';
	  html += '<div class="imageList"><div class="imageList delete-news-item">';
	  html += '<a href="javascript:void(0);" data-id="'+page.page_id+'" class="close tops">×</a>';
	  html += '</div></div>';
	  html += '<input type="hidden" name="page_id[]" value="'+page.page_id+'" />';
	  html += '</li>';

	  $('.selected_page_list').append(html);
	}
});
</script>
<script src="{{asset('atlas/hell/hulk/js/shakearound.js')}}"></script>
@stop
