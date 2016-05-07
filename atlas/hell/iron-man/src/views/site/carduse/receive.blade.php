@extends('EcdoSpiderMan::layouts.site.default')

@section('main')
<link href="{{asset('atlas/hell/iron-man/css/create_coupons.css')}}" rel="stylesheet" />
<link href="{{asset('atlas/hell/iron-man/css/editor_section_shop.css')}}" rel="stylesheet" />
<style type="text/css">
.msg_card_section:hover .msg_card_mask {
  display: none;
  background: none;
}

.media_preview_area .msg_card_inner {
  padding-bottom: 60px;
  background-color: #f4f5f9;
}
</style>

<section class="scrollable" id="bjax-target">
	<section id="content" class="receive-body-click">
		<input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
		
		<div class="media_preview_area" id="js_preview_area" style="width:100%;">
			<div class="msg_card">
				<div class="msg_card_inner">
					<div class="js_preview msg_card_section shop disabled focus">
						<div class="shop_panel" id="js_color_preview" style="background-color:{{$coupons->color}}">
							<div class="logo_area_type group">
								<span class="logo l">
									<img id="js_logo_url_preview" src="{{asset($setting['img_url'])}}">
								</span>
								<p id="js_brand_name_preview">
									{{$setting['content']['name']}}<br />
									<span class="logo_font_type">({{$type[$coupons->type]}}券)</span>
								</p>
							</div>

							<div class="msg_area">
								<div class="tick_msg">
									<p>
										<b id="js_title_preview">{{$coupons->title}}</b>
									</p>
									<span id="js_sub_title_preview">{{$coupons->sub_title}}</span>
									<br>
								</div>
								<p class="time" style="text-align:center">
									<span id="js_validtime_preview">
										{{{$coupons->begin_at}}} 至 {{{$coupons->end_at}}}
									</span>
								</p>
							</div>
						</div>
						<div class="msg_card_mask">
							<span class="vm_box"></span>
							<a href="javascript:void(0);" class="js_edit_icon edit_oper">
								<i class="icon18_common edit_gray" style="margin-top: 75px;"></i>
							</a>
						</div>
						<div class="deco"></div>
					</div>

					<div class="js_preview msg_card_section dispose disabled">
						<div class="" id="js_destroy_type_preview">
							<p class="sub_msg tc" id="js_notice_preview" 
							style="text-align:center;padding-top:10px;padding-bottom:10px;">
								<b>恭喜您获得1张 {{$coupons_type[$coupons['coupons_type']]}}</b>
							</p>
						</div>
					</div>

					<div class="btn-group btn-group-justified" 
					style="padding-top:40px;padding-left:80px;padding-right:80px;">
				    	<a href="javascript:void(0);" class="btn btn-success receive-click" 
				    	data-action="addCard" data-id="{{$coupons->card_id}}" 
				    	data-url="{{URL::to(Session::get('guid') . '/card/codeReceiveDis')}}">
				    		立即领取
				    	</a>
				    </div>
				</div>
			</div>
		</div>
	</section>
</section>

<script src="{{asset('assets/tower/api/wechat/jsapi.js?123')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
	reset = function () {
		alertify.set({
			labels : {
	        	ok     : "确认",
	        	cancel : "取消"
	    	},
	       	delay : 5000,
	       	buttonReverse : false,
	       	buttonFocus   : "ok"
		});
	};
	reset();

	$('.receive-body-click').on({
		click:function() {
			if ($(this).hasClass('receive-click')) {
				var data = new FormData();
	        	data.append('csrf_guid', $('#csrf_guid').val());
	        	data.append('card_id', $(this).attr('data-id'));
			    data.append('action', $(this).attr('data-action'));
			    data.append('url', window.location.href);

				$.ajax({
			    	url: $(this).attr('data-url'),
			    	type: 'POST',
			    	data: data,
			    	contentType: false,
			    	processData: false,
			       
			    	success:function(result) {
			        	var res = jQuery.parseJSON(result);
			      		yk.wechatCommon(res.config, res.data);
			    	}
			    });
			}
		}
	}, '.receive-click');
});

var wechatCallBack = function (res) {};
</script>
@stop
