@extends('EcdoSpiderMan::layouts.site.default')

@section('main')
<link href="{{asset('atlas/hell/iron-man/css/create_coupons.css')}}" rel="stylesheet" />
<link href="{{asset('atlas/hell/iron-man/css/editor_section_shop.css')}}" rel="stylesheet" />
<style type="text/css">
.msg_card_section:hover .msg_card_mask {
  display: none;
  background: none;
}

.yk-qrcode {
	width: 174px;
  	height: 174px;
  	margin: 0 auto 5px;
}

.yk-barcode {
  width: 264px;
  height: 71px;
  margin: 0 auto 0;
}
</style>

<section class="scrollable" id="bjax-target">
	<section id="content">
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
									<span id="js_validtime_preview">{{{$coupons->begin_at}}} 至 {{{$coupons->end_at}}}</span>
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
							<div class="barcode_area js_code_preview preview_CODE_TYPE_BARCODE" 
							style="@if ($coupons->code_type == 'CODE_TYPE_BARCODE') display: block; @else display: none; @endif">
								<div class="barcode">
									<img class="yk-barcode" src="{{URL::to(Session::get('guid') . '/card/codeImage/' . $coupons->code_type . '/' . $code)}}">
								</div>
								<p class="code_num">{{{$code}}}</p>
							</div>

							<div class="qrcode_area js_code_preview preview_CODE_TYPE_QRCODE" 
							style="@if ($coupons->code_type == 'CODE_TYPE_QRCODE') display: block; @else display: none; @endif">
								<div class="qrcode">
									<img class="yk-qrcode" src="{{URL::to(Session::get('guid') . '/card/codeImage/' . $coupons->code_type . '/' . $code)}}">
								</div>
								<p class="code_num">{{{$code}}}</p>
							</div>

							<div class="sn_area js_code_preview preview_CODE_TYPE_TEXT" 
							style="@if ($coupons->code_type == 'CODE_TYPE_TEXT') display: block; @else display: none; @endif">
								{{{$code}}}
							</div>

							<p class="sub_msg tc" id="js_notice_preview" style="text-align:center">
								@if ($info->status == 0)
									{{$coupons->notice}}
								@else
									<font color="#f84040">
									卡券{{$status[$info->status]}}
									</font>
								@endif
							</p>
						</div>
						<div class="msg_card_mask">
							<span class="vm_box"></span>
							<a href="javascript:void(0);" class="js_edit_icon edit_oper">
								<i class="icon18_common edit_gray icon18_common_preview" style="margin-top: 18px;"></i>
							</a>
						</div>
					</div>

					<div class="shop_detail">
						<ul class="list">
							<li class="msg_card_section js_preview details">
								<a class="li_panel" href="{{URL::to(Session::get('guid') . '/card/codeContent/' . $data)}}">
									<div class="li_content">
										<p>
											{{$coupons_type[$coupons->coupons_type]}}详情
										</p>
									</div>
									<span class="ic ic_go"></span>
								</a>
								<div class="msg_card_mask">
									<span class="vm_box"></span>
									<a href="javascript:void(0);" class="js_edit_icon edit_oper"><i class="icon18_common edit_gray"></i></a>
								</div>
							</li>
							
							<li class="msg_card_section js_preview last_li store">
								<a class="li_panel" href="{{$register}}">
									<div class="li_content">
										<p>返回</p>
									</div>
									<span class="ic ic_go"></span>
								</a>
								<div class="msg_card_mask">
									<span class="vm_box"></span>
									<a href="javascript:void(0);" class="js_edit_icon edit_oper"><i class="icon18_common edit_gray"></i></a>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>
</section>
@stop
