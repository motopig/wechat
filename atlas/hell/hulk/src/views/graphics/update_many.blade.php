@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<link href="{{asset('atlas/hell/hulk/css/graphics_many.css')}}" rel="stylesheet" />

<form class="form-horizontal form-graphics" role="form" method="POST" action="{{ URL::to('angel/wechat/graphics/crupGraphicsDis') }}" enctype="multipart/form-data">
	<input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
	<input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
	<input type="hidden" name="f_id" class="graphics-id" value="{{$graphics['id']}}" />
	<div class="graphics-img-url" data-url="{{ URL::to('angel/wechat/graphics/graphicsImageUrl') }}"></div>

	<div class="col-xs-12">
		<div class="left-show fn-left" id="messageList">
			<ul class="show-cont" id="J_showCont">
				<li class="first-item state-disabled multiMsgItem">
					<div class="multiMsgMode">
                        <div class="multimessage-show-title">
                        	<h1 class="J_change_title title-break" data-title="{{$graphics['title']}}">{{$graphics['title']}}</h1>

                        	<div class="title-mask-bg"></div>
                        </div>

                        <div class="cover-pic J_change_image" data-image="{{asset($graphics['img_url'])}}">
                        	<img src="{{asset($graphics['img_url'])}}" width="100%" height="100%" />
                        </div>
                    </div>
                    
                    <div class="overlay-first-item J_hoverShow">
					    <div class="icon-box">
					        <a href="javascript:void(0);" id="first" class="editor-icon first-position J_editArticle" title="编辑" 
					        seed="iconBox-editorIcon" smartracker="on"></a>
					    </div>
					    <div class="ver_mh"></div>
					</div>

					<span class="data-break J_change_data" data-uid="{{$graphics['id']}}" data-author="{{$graphics['author']}}" data-image="{{asset($graphics['img_url'])}}" 
					data-image-id="{{$graphics['store_image_id']}}" data-scp="{{$graphics['show_cover_pic']}}" data-content="{{{$graphics['content']}}}" data-csu="{{{$graphics['content_source_url']}}}"></span>
                </li>

                <div id="J_sortable">
                	@foreach ($graphics['item'] as $k => $v)
                		<li class="show-item fn-clear state-disabled-{{$k}}" data-item="{{$k}}">
	                    	<div class="cover-pic J_change_image_{{$k}}" data-image="{{asset($v['img_url'])}}">
	                    		<img src="{{asset($v['img_url'])}}" height="100%" width="100%">
	                    	</div>
	                        <h1 class="show-title title-break J_change_title_{{$k}}" data-title="{{$v['title']}}">{{$v['title']}}</h1>

	                        <div class="overlay-article-mask J_hoverShow">
							    <div class="icon-box-item">
					                <a href="javascript:void(0);" id="{{$k}}" class="editor-icon J_editArticle" title="编辑"></a>
					                <a href="javascript:void(0);" id="{{$k}}" class="del-icon J_deleteArticle" title="删除"></a>
					                <!-- <a href="javascript:void(0);" id="{{$k}}" class="dragsort-icon J_dragSort" title="排序"></a> -->
					            </div>
							    <div class="ver_mh"></div>
							</div>

							<span class="data-break J_change_data_{{$k}}" data-uid="{{$v['id']}}" data-author="{{$v['author']}}" data-image="{{asset($v['img_url'])}}"  
							data-image-id="{{$v['store_image_id']}}" data-scp="{{$v['show_cover_pic']}}" data-content="{{{$v['content']}}}" data-csu="{{{$v['content_source_url']}}}"></span>
	                    </li>
                	@endforeach
                </div>
			</ul>
			<div id="J_multiBox" class="multiMsgMode" @if (count($graphics['item']) == 7) style="display:none;" @endif>
                <a href="javascript:void(0);" class="add-item add-style" id="J_addItem"></a>
                <p class="article-left-tip">还可添加 <span class="article-left" id="J_aticleNum">{{7 - count($graphics['item'])}}</span> 篇图文</p>
            </div>
		</div>

		<div class="edit-right fn-left" id="J_editRight" style="margin-top:0px;">
	        <div class="arrow-icon" data-arrow="arrow-first" title="箭头"></div>
			<div class="form-group">
				<label class="col-sm-6">标题</label>
				<div class="col-sm-9">
					<input type="text" placeholder="最多可输入64个字符" maxlength="64" value="{{$graphics['title']}}" class="form-control I_editRight i_title">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-6">作者 (选填)</label>
				<div class="col-sm-9">
					<input type="text" placeholder="最多可输入8个字符" maxlength="8" value="{{$graphics['author']}}" class="form-control I_editRight i_author">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-6">封面</label>
				<div class="col-sm-9">
					<div class="upload-photo fn-left" id="uploadPhotoWrap">
                        <div id="J_uploadPhoto" class="uploadify">
                            <div id="J_uploadPhoto-button">
                            	<span class="uploadify-button-text bolt-modal-click" data-type="image">+上传</span>

                            	<span class="uploadify-image" style="display:none;">
                            		<img id="imgPre" src="{{asset($graphics['img_url'])}}" width="100%" height="100%" />
                            	</span>

                            	<div class="avator-upload-mask">
                                    <div class="avator-upload-mask-overlay"></div>
                                    <a class="avator-upload-mask-title bolt-modal-click" href="javascript:void(0);" data-type="image">更换图片</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="upload-del fn-left" href="javascript:void(0);" id="J_uploadDel" style="display:none;">删除</a>
                    <div class="fn-left note-word">建议尺寸 <span id="uploadImgWidth">900像素</span> * <span id="uploadImgHeight">500像素</span>，支持 .jpg .jpeg .png 格式，小于2M。</div>

                    <div class="fn-left checkbox i-checks">
                      <label>
                        <input type="checkbox" checked value="{{asset($graphics['show_cover_pic'])}}" class="I_editRight i_scp scp">
                        <i></i> 封面图片显示在正文中
                      </label>
                    </div>
				</div>
			</div>

			<span>正文内容</span>
			<div class="transfers-text">
				<script id="container" type="text/plain">{{$graphics['content']}}</script>
			</div>
			<br />

			<div class="form-group">
				<label class="col-sm-6">原文链接 (选填)</label>
				<div class="col-sm-9">
					<input type="text" class="form-control I_editRight i_csu" value="{{$graphics['content_source_url']}}">
				</div>
			</div>

			<div class="fn-left checkbox i-checks i-article">
              <label>
                <input type="checkbox" name="save_article" @if ($graphics['show_cover_pic'] == '1') value="1"  @else value="0" @endif>
                <i></i> 图文同时保存为文章
              </label>
            </div>
        </div>

		<div class="col-xs-12">
			<hr />
			<div class="edit-btn fn-clear">
		        <button class="btn btn-success subPost" type="button">保存</button>&nbsp;&nbsp;
		        <a href="{{ URL::to('angel/wechat/graphics') }}">
		        	<button class="btn btn-default" type="button">返回</button>
		        </a>
		    </div>
		</div>
	</div>
</form>

<script src="{{asset('assets/universe/dist/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('assets/tower/ueditor/ueditor.config.js')}}"></script>
<script src="{{asset('assets/tower/ueditor/ueditor.all.min.js')}}"></script>
<script src="{{asset('atlas/hell/hulk/js/graphics_many.js')}}"></script>

@include('EcdoSpiderMan::layouts.modal.dialog')
@stop