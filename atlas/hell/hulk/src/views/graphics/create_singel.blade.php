@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<link href="{{asset('atlas/hell/hulk/css/graphics_single.css')}}" rel="stylesheet" />

<form class="form-horizontal form-graphics" role="form" method="POST" action="{{ URL::to('angel/wechat/graphics/crupGraphicDis') }}" enctype="multipart/form-data">
	<input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
	<input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
	<div class="graphics-img-url" data-url="{{ URL::to('angel/wechat/graphics/graphicsImageUrl') }}"></div>

	<div class="col-xs-12">
		<div class="newmessage">
			<div class="left-show fn-left" id="messageList">
				<ul class="show-cont ui-sortable" id="J_showCont">
                	<li class="first-item state-disabled singleMsgItem" id="item_0">
	                    <div class="singleMsgMode">
	                        <h4 class="singlemessage-show-title J_change_title" data-title="title" data-default="标题">标题</h4>
	                        <div class="cover-pic J_change_image" data-image="image" data-default="封面图片">封面图片</div>
	                        <div class="article-description J_change_description" data-description="description" data-default=""></div>
	                        <div class="goview singleMsgMode J_change_hrefName" data-hrefname="hrefName" data-default="立即查看">立即查看</div>
	                    </div>
                	</li>
                </ul>
            </div>
        </div>

        <div class="newmessage">
            <div class="edit-right fn-left" id="J_editRight" style="margin-top: 0px;">
	            <div class="arrow-icon" title="箭头"></div>
				<div class="form-group">
					<label class="col-sm-6">标题</label>
					<div class="col-sm-9">
						<input type="text" name="title" placeholder="最多可输入64个字符" maxlength="64" class="form-control i_title">
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-6">作者 (选填)</label>
					<div class="col-sm-9">
						<input type="text" name="author" placeholder="最多可输入8个字符" maxlength="8" class="form-control">
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
                                		<img id="imgPre" src="" width="100%" height="100%" />
                                	</span>
                                </div>

                                <div style="display:none;"> 
									<input type="hidden" name="image_url" class="f_image_url" /> 
								</div>

								<div class="avator-upload-mask">
                                    <div class="avator-upload-mask-overlay"></div>
                                    <a class="avator-upload-mask-title bolt-modal-click" href="javascript:void(0);" data-type="image">更换图片</a>
                                </div>
                            </div>
                        </div>
                        <a class="upload-del fn-left" href="javascript:void(0);" id="J_uploadDel" style="display:none;">删除</a>
                        <div class="fn-left note-word">建议尺寸 <span id="uploadImgWidth">900像素</span> * <span id="uploadImgHeight">500像素</span>，支持 .jpg .jpeg .png 格式，小于2M。</div>

                        <div class="fn-left checkbox i-checks">
                          <label>
                            <input type="checkbox" checked name="show_cover_pic" value="1">
                            <i></i> 封面图片显示在正文中
                          </label>
                        </div>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-6">摘要 (选填)</label>
					<div class="col-sm-9">
						<input type="text" name="digest" placeholder="最多可输入120个字符" maxlength="120" class="form-control i_digest">
					</div>
				</div>

				<span>正文内容</span>
				<div class="transfers-text">
					<script id="container" name="content" type="text/plain"></script>
				</div>
				<br />

				<div class="form-group">
					<label class="col-sm-6">原文链接 (选填)</label>
					<div class="col-sm-9">
						<input type="text" name="content_source_url" class="form-control">
					</div>
				</div>

				<div class="fn-left checkbox i-checks">
                  <label>
                    <input type="checkbox" name="save_article" value="0">
                    <i></i> 图文同时保存为文章
                  </label>
                </div>
            </div>
        </div>
	</div>

	<div class="col-xs-12">
		<hr />
		<div class="edit-btn fn-clear">
	        <button class="btn btn-success subPost" type="submit">保存</button>&nbsp;&nbsp;
	        <a href="{{ URL::to('angel/wechat/graphics') }}">
	        	<button class="btn btn-default" type="button">返回</button>
	        </a>
	    </div>
	</div>
</form>

<script src="{{asset('assets/tower/ueditor/ueditor.config.js')}}"></script>
<script src="{{asset('assets/tower/ueditor/ueditor.all.min.js')}}"></script>
<script src="{{{ asset('atlas/hell/hulk/js/graphics_singel.js') }}}"></script>

@include('EcdoSpiderMan::layouts.modal.dialog')
@stop