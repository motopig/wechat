@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<link href="{{asset('atlas/hell/iron-man/css/create_coupons.css')}}" rel="stylesheet" />
<link href="{{asset('assets/universe/dist/timepicker-addon/jquery-ui-smoothness.css')}}" rel="stylesheet" />
<link href="{{asset('assets/universe/dist/timepicker-addon/jquery-ui-timepicker-addon.css')}}" rel="stylesheet" />
<link href="{{asset('atlas/hell/iron-man/css/editor_section_shop.css')}}" rel="stylesheet" />

<form id="create-coupons-body" class="form-horizontal form-create-coupons" role="form" method="POST" action="{{URL::to('angel/createCouponsDis')}}">
	<input type="hidden" name="csrf_token" id="csrf_token" value="{{Session::getToken()}}" />
	<input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
	<input type="hidden" class="coupons-type" value="{{$type['key']}}" />
	<input type="hidden" class="coupons-coupons_type" value="{{$coupons_type['key']}}" />
	<input type="hidden" class="setting-url" value="{{$setting['content']['url']}}" />
	<input type="hidden" class="shop-logo_url" value="{{asset($setting['img_url'])}}" />
	<input type="hidden" class="shop-brand_name" value="{{$setting['content']['name']}}" />
	<input type="hidden" class="shop-color" value="{{{$color}}}" />
	<input type="hidden" class="shop-content" value="{{{$content}}}" />
	<input type="hidden" class="shop-setting_url" value="{{URL::to('angel/coupons/setting')}}" />
	<input type="hidden" class="bolt-modal-preview-url" value="{{URL::to('angel/coupons/store')}}" />
	
	<div class="col-xs-12">
		<div class="media_preview_area" id="js_preview_area">
			<div class="msg_card">
				<div class="msg_card_inner">
					<p class="msg_title">{{$type['value']}} - {{$coupons_type['value']}}</p>

					<div class="js_preview msg_card_section shop disabled focus">
						<div class="shop_panel" id="js_color_preview">
							<div class="logo_area group">
								<span class="logo l">
									<img id="js_logo_url_preview" src="{{asset($setting['img_url'])}}">
								</span>
								<p id="js_brand_name_preview">{{$setting['content']['name']}}</p>
							</div>

							<div class="msg_area">
								<div class="tick_msg">
									<p>
										<b id="js_title_preview"></b>
									</p>
									<span id="js_sub_title_preview"></span>
									<br>
								</div>
								<p class="time" style="text-align:center">
									<span id="js_validtime_preview"></span>
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
						<div class="unset" id="destroy_title">
							<p>销券设置</p>
						</div>
						
						<div class="" id="js_destroy_type_preview">
							<div class="barcode_area js_code_preview preview_CODE_TYPE_BARCODE" style="display: none;">
								<div class="barcode"></div>
								<p class="code_num">1513-2290-1878</p>
							</div>

							<div class="qrcode_area js_code_preview preview_CODE_TYPE_QRCODE" style="display: none;">
								<div class="qrcode"></div>
								<p class="code_num">1513-2290-1878</p>
							</div>

							<div class="sn_area js_code_preview preview_CODE_TYPE_TEXT" style="display: none;">
								1513-2290-1878
							</div>
							
							<p class="sub_msg tc" id="js_notice_preview" style="text-align:center"></p>
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
								<div class="li_panel" href="">
									<div class="li_content">
										<p>{{$coupons_type['value']}}详情</p>
									</div>
									<span class="ic ic_go"></span>
								</div>
								<div class="msg_card_mask">
									<span class="vm_box"></span>
									<a href="javascript:void(0);" class="js_edit_icon edit_oper"><i class="icon18_common edit_gray"></i></a>
								</div>
							</li>
							
							<li class="msg_card_section js_preview last_li store">
								<div class="li_panel" href="">
									<div class="li_content">
										<p>适用门店</p>
									</div>
									<span class="ic ic_go"></span>
								</div>
								<div class="msg_card_mask">
									<span class="vm_box"></span>
									<a href="javascript:void(0);" class="js_edit_icon edit_oper"><i class="icon18_common edit_gray"></i></a>
								</div>
							</li>
						</ul>
					</div>
					
					<!-- <div class="shop_wepay" id="js_shop_wepay" style="">
						<ul class="list">
							<li class="msg_card_section js_preview last_li">
								<div class="li_panel" href="">
									<div class="li_content">
										<p>线下核销</p>
									</div>
									<span class="ic ic_go"></span>
								</div>
								<div class="msg_card_mask">
									<span class="vm_box"></span>
									<a href="javascript:void(0);" class="js_edit_icon edit_oper"><i class="icon18_common edit_gray"></i></a>
								</div>
							</li>
						</ul>
					</div> -->
				</div>
			</div>
		</div>

		<div id="coupons-group-shop" class="coupons-groups"></div>
		<div id="coupons-group-dispose" class="coupons-groups"></div>
		<div id="coupons-group-details" class="coupons-groups"></div>
		<div id="coupons-group-store" class="coupons-groups"></div>
	</div>

	<div class="col-xs-12">
		<hr style="margin-top:40px;" />
		<div class="edit-btn fn-clear">
	        <button class="btn btn-success coupons-subPost" type="button">保存</button>&nbsp;&nbsp;
	        <a href="{{ URL::to('angel/coupons') }}">
	        	<button class="btn btn-default" type="button">返回</button>
	        </a>
	    </div>
	</div>
</form>

<script src="{{asset('assets/universe/dist/jquery-ui/jquery-ui.js')}}"></script>
<script src="{{asset('assets/universe/dist/timepicker-addon/jquery-ui-timepicker-addon.js')}}"></script>
<script src="{{asset('assets/universe/dist/timepicker-addon/jquery.ui.datepicker-zh-CN.js.js')}}" charset="gb2312"></script>
<script src="{{asset('assets/universe/dist/timepicker-addon/jquery-ui-timepicker-zh-CN.js')}}"></script>
<script src="{{asset('assets/universe/js/handlebars.js')}}"></script>
<script src="{{asset('atlas/hell/iron-man/js/create_coupons.js')}}"></script>

@include('EcdoIronMan::coupons/handlebars/shop')
@include('EcdoIronMan::coupons/handlebars/dispose')
@include('EcdoIronMan::coupons/handlebars/details')
@include('EcdoIronMan::coupons/handlebars/store')
@include('EcdoIronMan::coupons/handlebars/item')
@include('EcdoSpiderMan::layouts.modal.dialog')

@stop
