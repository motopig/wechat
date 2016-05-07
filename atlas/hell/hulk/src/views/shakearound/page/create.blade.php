@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<link href="{{asset('atlas/hell/hulk/css/shakearound.css')}}" rel="stylesheet" />

<section class="panel panel-default">
    <div class="panel-body">
      <form class="form-horizontal form-page" method="post" action="{{URL::to('angel/wechat/shakearound/pageCreateDis')}}">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
        <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
        <div class="graphics-img-url" data-url="{{ URL::to('angel/wechat/graphics/graphicsImageUrl') }}"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">页面类型</label>
          <div class="col-sm-4">
            <div class="btn-group m-r">
              <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                <span class="dropdown-label">自定义链接</span> 
                &nbsp;<span class="caret"></span>
              </button>
              <ul class="dropdown-menu dropdown-select page-type-select">
                @foreach ($type as $k => $v)
                  <li class="@if ($k == 0) active @endif">
                    <input type="radio" name="type">
                    <a href="#" data-val="{{$k}}">{{$v}}</a>
                  </li>
                @endforeach
              </ul>
            </div>

            <a class="page-type-show" href="{{ asset('guanzhu.png') }}" target="_blank" style="display:none;">
	            <b class="badge bg-success" data-toggle="tooltip" data-placement="bottom" 
	                data-original-title="公众号关注跳转链接配置说明">
	                <span class="icon-question"></span>
	            </b>
            </a>

          </div>                
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="page-content" style="display:none;">
          <div class="form-group">
            <label class="col-sm-2 control-label">页面内容</label>
            <div class="col-sm-7">
              @if (count($content) > 0)
                @foreach ($content as $k => $v)
                  <select id="select_content_{{$k}}" name="content" class="form-control m-b page-content-select" 
                  style="width:150px;display:none;">
                    @if (count($content[$k]) > 0)
                      @foreach ($content[$k] as $ks => $vs)
                        <option value="{{$vs->id}}">
                          @if ($k == 2)
                            {{$vs->title}}
                          @elseif ($k == 5)
                            {{$vs->name}}
                          @endif
                        </option>
                      @endforeach
                    @endif
                  </select>
                @endforeach
              @endif
            </div>
          </div>
          <div class="line line-dashed b-b line-lg pull-in"></div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">缩略图</label>
          <div class="col-sm-10">
          	<div class="upload-photo fn-left" id="uploadPhotoWrap">
                <div id="J_uploadPhoto" class="uploadify">
                    <div id="J_uploadPhoto-button">
                    	<span class="uploadify-button-text bolt-modal-click" data-type="image">+上传</span>

                    	<span class="uploadify-image" style="display:none;">
                    		<img id="imgPre" src="" width="100%" height="100%">
                    	</span>
                    </div>

                    <div style="display:none;"> 
						<input type="hidden" name="image_url" class="f_image_url"> 
					</div>

					<div class="avator-upload-mask">
                        <div class="avator-upload-mask-overlay"></div>
                        <a class="avator-upload-mask-title bolt-modal-click" href="javascript:void(0);" data-type="image">更换图片</a>
                    </div>
                </div>
            </div>
            <a class="upload-del fn-left" href="javascript:void(0);" id="J_uploadDel" style="display:none;">删除</a>
           </div>
           <div class="note-word">建议尺寸 <span id="uploadImgWidth">120像素</span> * <span id="uploadImgHeight">120像素</span>，支持 .jpg .jpeg 格式，小于2M。</div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">主标题</label>
          <div class="col-sm-7">
            <input type="text" class="form-control page-title" maxlength="6" placeholder="在摇一摇页面展示的主标题，不超过6个字">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">副标题</label>
          <div class="col-sm-7">
            <input type="text" class="form-control page-description" maxlength="7" placeholder="在摇一摇页面展示的副标题，不超过7个字">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">跳转链接</label>
          <div class="col-sm-7">
            <input type="text" class="form-control page-page_url" placeholder="请以 http:// 或 https:// 开头">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">备注 (选填)</label>
          <div class="col-sm-7">
            <input type="text" class="form-control page-comment" maxlength="15" placeholder="页面的备注信息，不超过15个字">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
            <button type="button" class="btn btn-success page-click" data-page-id="" data-id="">保存</button>&nbsp;
            <a href="{{ URL::to('angel/wechat/shakearound/page') }}">
              <button type="button" class="btn btn-default">返回</button>
            </a>
          </div>
        </div>
      </form>
    </div>
</section>

@include('EcdoSpiderMan::layouts.modal.dialog')
<script src="{{asset('atlas/hell/hulk/js/shakearound.js')}}"></script>
@stop
