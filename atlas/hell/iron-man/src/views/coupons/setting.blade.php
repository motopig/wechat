@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<link href="{{asset('atlas/hell/hulk/css/shakearound.css')}}" rel="stylesheet" />

<section class="panel panel-default">
	@include('EcdoIronMan::layouts.tabs.coupons')
    <div class="panel-body">
      <form class="form-horizontal form-coupons-setting" method="post" action="{{URL::to('angel/coupons/settingDis')}}">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
        <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
        <div class="graphics-img-url" data-url="{{ URL::to('angel/wechat/graphics/graphicsImageUrl') }}"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">商户Logo</label>
          <div class="col-sm-10">
          	<div class="upload-photo fn-left" id="uploadPhotoWrap">
                <div id="J_uploadPhoto" class="uploadify">
                    <div id="J_uploadPhoto-button">
                    	<span class="uploadify-button-text bolt-modal-click" data-type="image">+上传</span>

                    	<span class="uploadify-image" style="display:none;">
                    		<img id="imgPre" src="@if (isset($setting)){{asset($setting->img_url)}}@endif" width="100%" height="100%" />
                    	</span>
                    </div>

                    <div style="display:none;"> 
						          <input type="hidden" name="image_url" class="f_image_url" value="@if (isset($setting)){{$setting->content['logo']}}@endif"> 
					          </div>

					          <div class="avator-upload-mask">
                        <div class="avator-upload-mask-overlay"></div>
                        <a class="avator-upload-mask-title bolt-modal-click" href="javascript:void(0);" data-type="image">更换图片</a>
                    </div>
                </div>
            </div>
            <a class="upload-del fn-left" href="javascript:void(0);" id="J_uploadDel" style="display:none;">删除</a>
           </div>
           <div class="note-word">建议尺寸 <span id="uploadImgWidth">300像素</span> * <span id="uploadImgHeight">300像素</span>，支持 .jpg .jpeg .png 格式，小于2M。</div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">商户名称</label>
          <div class="col-sm-5">
            <input type="text" class="form-control coupons-setting-name" value="@if (isset($setting)){{$setting->content['name']}}@endif" 
            placeholder="商户名称，最多12个字">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
            <button type="button" class="btn btn-success coupons-setting-click" data-id="@if (isset($setting->id)){{$setting->id}}@endif">保存</button>&nbsp;
          </div>
        </div>
      </form>
    </div>
</section>

@include('EcdoSpiderMan::layouts.modal.dialog')
<script src="{{asset('atlas/hell/iron-man/js/setting.js')}}"></script>
@stop
