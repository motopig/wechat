@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<link href="{{asset('atlas/hell/hulk/css/shakearound.css')}}" rel="stylesheet" />

<section class="panel panel-default">
	@include('EcdoBatMan::layouts.tabs.entityshop')
    <div class="panel-body">
      <form class="form-horizontal form-nearby" method="post" action="{{URL::to('angel/nearbyentityshopDis')}}">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
        <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
        <div class="graphics-img-url" data-url="{{ URL::to('angel/wechat/graphics/graphicsImageUrl') }}"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">封面大图</label>
          <div class="col-sm-10">
          	<div class="upload-photo fn-left" id="uploadPhotoWrap">
                <div id="J_uploadPhoto" class="uploadify">
                    <div id="J_uploadPhoto-button">
                    	<span class="uploadify-button-text bolt-modal-click" data-type="image">+上传</span>

                    	<span class="uploadify-image" style="display:none;">
                    		<img id="imgPre" src="@if (isset($nearby)){{asset($nearby->img_url)}}@endif" width="100%" height="100%" />
                    	</span>
                    </div>

                    <div style="display:none;"> 
						          <input type="hidden" name="image_url" class="f_image_url" value="@if (isset($nearby)){{$nearby->content['store_image_id']}}@endif"> 
					          </div>

					          <div class="avator-upload-mask">
                        <div class="avator-upload-mask-overlay"></div>
                        <a class="avator-upload-mask-title bolt-modal-click" href="javascript:void(0);" data-type="image">更换图片</a>
                    </div>
                </div>
            </div>
            <a class="upload-del fn-left" href="javascript:void(0);" id="J_uploadDel" style="display:none;">删除</a>
           </div>
           <div class="note-word">建议尺寸 <span id="uploadImgWidth">900像素</span> * <span id="uploadImgHeight">500像素</span>，支持 .jpg .jpeg .png 格式，小于2M。</div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">图文显示条目 (选填)</label>
          <div class="col-sm-5">
            <input type="text" class="form-control nearby-sum" value="@if (isset($nearby)){{$nearby->content['sum']}}@endif" 
            placeholder="附近门店图文信息；最多添加7篇图文，默认不填显示3篇">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">列表显示条目 (选填)</label>
          <div class="col-sm-5">
            <input type="text" class="form-control nearby-num" value="@if (isset($nearby)){{$nearby->content['num']}}@endif" 
            placeholder="附近门店列表显示条目，默认不填显示10条">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">公里范围 (选填)</label>
          <div class="col-sm-5">
            <input type="text" class="form-control nearby-km" value="@if (isset($nearby)){{$nearby->content['km']}}@endif" 
            placeholder="附近门店搜索公里范围，默认不填显示20公里">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">启用状态</label>
          <div class="col-sm-10">
            <div class="radio i-checks">
              <label>
                <input type="radio" name="disabled" value="false" @if (! isset($nearby) || 
                (isset($nearby) && $nearby->content['disabled'] == 'false')) checked @endif>
                <i></i>
                启用
              </label>
            </div>
            <div class="radio i-checks">
              <label>
                <input type="radio" name="disabled" value="true" @if (isset($nearby) && 
                $nearby->content['disabled'] == 'true') checked @endif>
                <i></i>
                禁用
              </label>
            </div>
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
            <button type="button" class="btn btn-success nearby-click" data-id="@if (isset($nearby->id)){{$nearby->id}}@endif">保存</button>&nbsp;
          </div>
        </div>
      </form>
    </div>
</section>

@include('EcdoSpiderMan::layouts.modal.dialog')
<script src="{{asset('atlas/hell/bat-man/js/nearby.js')}}"></script>
@stop
