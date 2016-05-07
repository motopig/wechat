@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<link href="{{asset('atlas/hell/iron-man/css/create_coupons.css')}}" rel="stylesheet" />
<link href="{{asset('assets/universe/dist/timepicker-addon/jquery-ui-smoothness.css')}}" rel="stylesheet" />
<link href="{{asset('assets/universe/dist/timepicker-addon/jquery-ui-timepicker-addon.css')}}" rel="stylesheet" />
<link href="{{asset('atlas/hell/iron-man/css/editor_section_shop.css')}}" rel="stylesheet" />

<style type="text/css">
.msg_card_section:hover .msg_card_mask {
  display: none;
  background: none;
}
</style>

<form id="update-coupons-body" class="form-horizontal form-update-coupons" role="form" method="POST" action="{{URL::to('angel/updateCouponsDis')}}">
	<input type="hidden" name="csrf_token" id="csrf_token" value="{{Session::getToken()}}" />
	<input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
	<input type="hidden" class="setting-url" value="{{$setting['content']['url']}}" />
	<input type="hidden" class="shop-logo_url" value="{{asset($setting['img_url'])}}" />
	<input type="hidden" class="shop-brand_name" value="{{$setting['content']['name']}}" />
	<input type="hidden" class="coupons-id" value="{{$coupons->id}}" />
	<input type="hidden" class="coupons-store" @if (isset($coupons->store)) value="{{$coupons->store}}" @endif />
	<input type="hidden" class="bolt-modal-preview-url" value="{{URL::to('angel/coupons/store')}}" />

	<div class="col-xs-12">
		<div class="media_preview_area" id="js_preview_area">
			<div class="msg_card">
				<div class="msg_card_inner">
					<p class="msg_title">{{$type['value']}} - {{$coupons_type['value']}}</p>

					<div class="js_preview msg_card_section shop disabled focus">
						<div class="shop_panel" id="js_color_preview" style="background-color:{{$coupons->color}}">
							<div class="logo_area group">
								<span class="logo l">
									<img id="js_logo_url_preview" src="{{asset($setting['img_url'])}}">
								</span>
								<p id="js_brand_name_preview">{{$setting['content']['name']}}</p>
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
									<span id="js_validtime_preview">{{$coupons->begin_at}} - {{$coupons->end_at}}</span>
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
								<div class="barcode"></div>
								<p class="code_num">1513-2290-1878</p>
							</div>

							<div class="qrcode_area js_code_preview preview_CODE_TYPE_QRCODE" 
							style="@if ($coupons->code_type == 'CODE_TYPE_QRCODE') display: block; @else display: none; @endif">
								<div class="qrcode"></div>
								<p class="code_num">1513-2290-1878</p>
							</div>

							<div class="sn_area js_code_preview preview_CODE_TYPE_TEXT" 
							style="@if ($coupons->code_type == 'CODE_TYPE_TEXT') display: block; @else display: none; @endif">
								1513-2290-1878
							</div>

							<p class="sub_msg tc" id="js_notice_preview" style="text-align:center">
								{{$coupons->notice}}
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

									@if ($coupons->store_count > 0)
										<span class="supply_area">
											{{$coupons->store_count}}家
										</span>
									@endif

									<span class="ic ic_go"></span>
								</div>
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

		<div id="coupons-group-shop" class="coupons-groups">
			
			<div class="edit-right fn-left" id="J_editRight_shop" style="margin-top: 0px;">
			    <div class="arrow-icon" title="箭头" id="arrow-shop"></div>
				<p class="title">券面信息</p>

			    <div class="form-group cfgb">
			      <label class="col-lg-2 control-label">商家名称</label>
			      <div class="col-lg-8">
			        <p class="form-control-static">{{$coupons->brand_name}}</p>
			      </div>
			    </div>

			    <div class="form-group cfgb">
			      <label class="col-lg-2 control-label">商家Logo</label>
			      <div class="col-lg-8">
			      	<span class="appmsg_preview_msg">
			        	<img src="{{asset($setting['img_url'])}}">
			      	</span>
			      </div>
			    </div>

				<div class="form-group cfgb">
			      <label class="col-lg-2 control-label">卡券颜色</label>
			      <div class="col-lg-8">
			        <p class="form-control-static">
			        	<span class="card-bgcolor-show" style="background-color:{{$coupons->color}};"></span>
			        </p>
			      </div>
			    </div>

			    <div class="form-group cfgb">
			      <label class="col-lg-2 control-label">券号</label>
			      <div class="col-lg-8">
			        <p class="form-control-static">{{$coupons->card_id}}</p>
			      </div>
			    </div>

			    <div class="form-group cfgb">
			      <label class="col-lg-2 control-label">开始时间</label>
			      <div class="col-lg-8">
			        <p class="form-control-static">{{$coupons->begin_at}}</p>
			      </div>
			    </div>

			    <div class="form-group cfgb">
			      <label class="col-lg-2 control-label">结束时间</label>
			      <div class="col-lg-8">
			        <p class="form-control-static">{{$coupons->end_at}}</p>
			      </div>
			    </div>

			    <div class="form-group cfgb">
			      <label class="col-lg-2 control-label">卡券标题</label>
			      <div class="col-lg-8">
			        <p class="form-control-static">{{$coupons->title}}</p>
			      </div>
			    </div>

			    <div class="form-group cfgb">
			      <label class="col-lg-2 control-label">副标题</label>
			      <div class="col-lg-8">
			        <p class="form-control-static">{{$coupons->sub_title}}</p>
			      </div>
			    </div>

			    @if ($coupons->coupons_type == 'DISCOUNT')
			    <div class="form-group cfgb">
			      <label class="col-lg-2 control-label">折扣额度</label>
			      <div class="col-lg-8">
			        <p class="form-control-static">打{{$coupons->coupons_setting}}折</p>
			      </div>
			    </div>
			    @elseif ($coupons->coupons_type == 'CASH')
			    <div class="form-group cfgb">
			      <label class="col-lg-2 control-label">减免金额</label>
			      <div class="col-lg-8">
			        <p class="form-control-static">减{{$coupons->coupons_setting}}元</p>
			      </div>
			    </div>
			    @endif

			    <p class="titles">投放设置</p>
			    <div class="form-group cfgb">
			      <label class="col-lg-2 control-label">销券方式</label>
			      <div class="col-lg-8">
			        <p class="form-control-static">
			        	@if ($coupons->code_type == 'CODE_TYPE_QRCODE')
			        		二维码
			        	@elseif ($coupons->code_type == 'CODE_TYPE_BARCODE')
			        		条形码
			        	@else
			        		仅卡券号
			        	@endif
			        </p>
			      </div>
			    </div>

			    <div class="form-group cfgb">
			      <label class="col-lg-2 control-label">操作提示</label>
			      <div class="col-lg-8">
			        <p class="form-control-static">{{$coupons->notice}}</p>
			      </div>
			    </div>

			    <div class="form-group cfgb">
			      <label class="col-lg-2 control-label">领取限制</label>
			      <div class="col-lg-8">
			        <p class="form-control-static">每个用户限领{{$coupons->use_limit}}张</p>
			      </div>
			    </div>

			    <div class="form-group cfgb">
			      <label class="col-lg-2 control-label">分享设置</label>
			      <div class="col-lg-8">
			        <p class="form-control-static">
			        	{{$coupons->can_share == 'true' ? '用户可以分享领券链接' : '用户不可以分享领券链接'}}
			        </p>
			      </div>
			    </div>

			    <div class="form-group cfgb">
			      <label class="col-lg-2 control-label">转赠设置</label>
			      <div class="col-lg-8">
			        <p class="form-control-static">
			        	{{$coupons->can_give_friend == 'true' ? '用户领券后可转赠其他好友' : '用户领券后不可转赠其他好友'}}
			        </p>
			      </div>
			    </div>

			    <p class="titles">{{$coupons_type['value']}}详情</p>
			    <div class="form-group cfgb">
			      <label class="col-lg-2 control-label">使用须知</label>
			      <div class="col-lg-8">
			        <p class="form-control-static">
			        	{{$coupons->description}}
			        </p>
			      </div>
			    </div>

			    <div class="form-group cfgb">
			      <label class="col-lg-2 control-label">优惠详情</label>
			      <div class="col-lg-8">
			        <p class="form-control-static">
			        	{{$coupons->default_detail}}
			        </p>
			      </div>
			    </div>

			    <div class="form-group cfgb">
			      <label class="col-lg-2 control-label">客服电话</label>
			      <div class="col-lg-8">
			        <p class="form-control-static">
			        	{{$coupons->service_phone != '' ? $coupons->service_phone : '-'}}
			        </p>
			      </div>
			    </div>

			    <p class="titles">
			    	服务信息
			    	<span class="frm_tips">(可编辑栏目)</span>
			    </p>
			    <div class="form-group">
			      <label class="col-lg-2 control-label">库存</label>
			      <div class="col-lg-5">
			        <input type="text" class="form-control coupons-quantity" value="{{$coupons->quantity}}" 
			        placeholder="库存只能是大于0的整数">
			        <span class="help-block frm_tips">{{$coupons_type['value']}}已领取{{$coupons->inventory}}张</span>
			      </div>
			    </div>

			    <div class="form-group">
			      <label class="col-sm-2 control-label">延长有效期</label>
			      <div class="col-sm-5">
			        <div class="input-group">
			          <input type="text" class="form-control date coupons-end_at" value="{{$coupons->end_at}}" onfocus=this.blur()>
			          <span class="input-group-btn">
			            <button class="btn btn-default" type="button" onfocus=this.blur()>
			              <i class="fa fa-calendar"></i>
			            </button>
			          </span>
			        </div>
			      </div>
			    </div>

			    <div class="form-group">
			      <label class="col-sm-2 control-label">适用门店</label>
			      <div class="col-sm-10">
			        <div class="radio i-checks">
			          <label>
			            <input type="radio" class="coupons-location_id_list" name="location_id_list" value="store" 
			            @if (isset($coupons->store)) checked @endif>
			            <i></i>
			            指定门店适用 
			            <span class="frm_tips">
			              (<a href="javascript:void(0);" class="bolt-modal-click" data-type="store">添加适用门店</a>)
			            </span>
			          </label>
			        </div>

			        <div class="store-look">
			          <section class="panel panel-default portlet-item" style="margin-bottom: 10px;">
			              <ul class="list-group alt" id="coupons-group-item">
			              	@if (isset($coupons->store))
				              	@foreach ($coupons->store as $k => $v)
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
			            <input type="radio" class="coupons-location_id_list" name="location_id_list" value="all" 
			            @if ($coupons->location_id_list == 'all') checked @endif>
			            <i></i>
			            全部门店适用
			          </label>
			        </div>

			        <div class="radio i-checks">
			          <label>
			            <input type="radio" class="coupons-location_id_list" name="location_id_list" value="null" 
			            @if ($coupons->location_id_list == 'null') checked @endif>
			            <i></i>
			            无指定门店
			          </label>
			        </div>
			      </div>
			    </div>
			</div>

		</div>
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
<script src="{{asset('atlas/hell/iron-man/js/update_coupons.js')}}"></script>

@include('EcdoIronMan::coupons/handlebars/item')
@include('EcdoSpiderMan::layouts.modal.dialog')

@stop
