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
  padding-bottom: 12px;
  background-color: #fff;
}

.msg_card_inner {
  border: 0px solid #e7e7eb;
}

.msg_card_section.shop .shop_panel {
  padding: 21px 12px 12px;
  color: #fff;
  background-color: #f4f5f9;
  height: 145px; 
}

.msg_card_section.shop .tick_msg b {
  font-weight: normal;
  font-size: 16px;
  color: #fff;
}

</style>

<section class="panel panel-default portlet-item navbar-fixed-top-xs">
	<header class="panel-heading">
    	<i class="fa fa-credit-card"></i>&nbsp;
      	我的卡券

        <span class="text-muted m-l-sm pull-right">
            <a href="{{URL::to(Session::get('guid') . '/member/center')}}" data-dismiss="alert" class="btn btn-default btn-xs">
              <i class="fa fa-home text-muted"></i>&nbsp; 首页
            </a>
        </span>
    </header>
</section>

<section class="scrollable wrapper" style="padding-left:5px;padding-right:5px;">
	@if (count($info) > 0)
		@foreach ($info as $i)
			<a href="{{URL::to(Session::get('guid') . '/card/codeInfo/' . $i->code . '/codePage')}}">
			<div class="media_preview_area" id="js_preview_area" style="width:100%;">
				<div class="msg_card">
					<div class="msg_card_inner">
						<div class="js_preview msg_card_section shop disabled focus">
							<div class="shop_panel" id="js_color_preview" style="background-color:{{$i->coupons['color']}}">
								<div class="logo_area_type group">
									<span class="logo l">
										<img id="js_logo_url_preview" src="{{asset($i->coupons['logo_url'])}}">
									</span>
									<p id="js_brand_name_preview">
										{{$i->coupons['brand_name']}}
										<span class="pull-right logo_font_type">
								            @if (isset($i->time))
							                	已过期
							                @else
							                	{{$code_type[$i->status]}}
							                @endif
								        </span>

										<br />
										<span class="logo_font_type">
											({{$type[$i->coupons['type']]}} / {{$coupons_type[$i->coupons['coupons_type']]}})
										</span>
									</p>
								</div>

								<div class="msg_area">
									<div class="tick_msg">
										<p>
											<b id="js_title_preview">{{$i->coupons['title']}}</b>
										</p>
										<span id="js_sub_title_preview">{{{$i->code}}}</span>
										<br>
									</div>
									<!-- <p class="time" style="text-align:center">
										<span id="js_validtime_preview">
											{{{$i->coupons['begin_at']}}} 至 {{{$i->coupons['end_at']}}}
										</span>
									</p> -->
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
					</div>
				</div>
			</div>
			</a>
		@endforeach
	@endif
</section>
@stop
