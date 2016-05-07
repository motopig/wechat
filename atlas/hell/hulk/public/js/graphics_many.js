/**
 * 微信普通多图文交互
 *
 * @category yunke
 * @package atlas\hell\hulk\public\js
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

$(document).ready(function() {
	// alertify初始化操作
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

	// 表单内容操作
	$('#J_editRight').on({
		keyup:function() {
			if ($('.arrow-icon').attr('data-arrow') == 'arrow-first') {
				if ($(this).hasClass('i_title')) {
					$('.J_change_title').html($(this).val());
				  	$('.J_change_title').attr('data-title', $(this).val());

					if ($(this).val() == '') {
				  		$('.J_change_title').html('标题');
				  	}
				} else if ($(this).hasClass('i_author')) {
					$('.J_change_data').attr('data-author', $(this).val());
				} else if ($(this).hasClass('i_csu')) {
					$('.J_change_data').attr('data-csu', $(this).val());
				}
			} else {
				var dt = 0;

				for (var i = 0; i <= 6; i++) {
					if ($('.arrow-icon').attr('data-arrow') == 'arrow-'+i) {
						dt = 1;

						if ($(this).hasClass('i_title')) {
							$('.J_change_title_'+i).html($(this).val());
						  	$('.J_change_title_'+i).attr('data-title', $(this).val());

							if ($(this).val() == '') {
						  		$('.J_change_title_'+i).html('标题');
						  	}
						} else if ($(this).hasClass('i_author')) {
							$('.J_change_data_'+i).attr('data-author', $(this).val());
						} else if ($(this).hasClass('i_csu')) {
							$('.J_change_data_'+i).attr('data-csu', $(this).val());
						}
					}

					if (dt > 0) {
						break;
					}
				}
			}
		},

		click:function() {
			if ($('.arrow-icon').attr('data-arrow') == 'arrow-first') {
				if ($(this).hasClass('i_scp')) {
					var _this = $(this);
			      	if (_this.hasClass('scp')) {
			        	_this.removeClass('scp');
			        	_this.addClass('scp-o');
			        	_this.prop('checked', false);
			        	_this.val(0);

			        	$('.J_change_data').attr('data-scp', 0);
			      	} else {
			        	_this.removeClass('scp-o');
			        	_this.addClass('scp');
			        	_this.prop('checked', true);
			        	_this.val(1);

			        	$('.J_change_data').attr('data-scp', 1);
			      	}
				}
			} else {
				if ($(this).hasClass('i_scp')) {
					var _this = $(this);
					var dt = 0;

					for (var i = 0; i <= 6; i++) {
						if ($('.arrow-icon').attr('data-arrow') == 'arrow-'+i) {
							dt = 1;

							if (_this.hasClass('scp')) {
					        	_this.removeClass('scp');
					        	_this.addClass('scp-o');
					        	_this.prop('checked', false);
					        	_this.val(0);

					        	$('.J_change_data_'+i).attr('data-scp', 0);
					      	} else {
					        	_this.removeClass('scp-o');
					        	_this.addClass('scp');
					        	_this.prop('checked', true);
					        	_this.val(1);

					        	$('.J_change_data_'+i).attr('data-scp', 1);
					      	}

							if (dt > 0) {
								break;
							}
						}
					}
				}
			}
		}
	}, '.I_editRight');

	// 添加多图文
	$('#J_multiBox').on('click', function() {
		var length = $(".show-item h1").length;
  		var num = 6 - length;
  		var html = '';

		html += '<li class="show-item fn-clear state-disabled-'+num+'" data-item="'+num+'">';
		html += '<div class="cover-pic J_change_image_'+num+'" data-image>缩略图</div>';
		html += '<h1 class="show-title title-break J_change_title_'+num+'" data-title>标题</h1>';
		html += '<div class="overlay-article-mask J_hoverShow">';
		html += '<div class="icon-box-item">';
		html += '<a href="javascript:void(0);" id="'+num+'" class="editor-icon J_editArticle" title="编辑"></a>';
		html += '<a href="javascript:void(0);" id="'+num+'" class="del-icon J_deleteArticle" title="删除"></a>';
		// html += '<span><a href="javascript:void(0);" id="'+num+'" class="dragsort-icon J_dragSort" title="排序"></a></span>';
		html += '<div class="ver_mh"></div>';
		html += '</div>';
		html += '</div>';
		html += '<span class="data-break J_change_data_'+num+'" data-author data-image  data-image-id data-scp="1" data-content data-csu></span>';
		html += '</li>';
		$('#J_sortable').append(html);
		
		if (length == 7 || num == 0) {
			$('#J_multiBox').hide();
		} else {
			$('#J_aticleNum').html(num);
		}
	});

	// 多图文展示背景
	$('#J_showCont').on({
		// 离开多图文按钮区域
    	mouseleave:function() {
    		if ($(this).hasClass('show-item')) {
    			var length = $(".show-item h1").length;

    			if (length == 1) {
    				$('.J_deleteArticle, .J_dragSort').hide();
    			} else {
    				$('.J_deleteArticle, .J_dragSort').show();
    			}
    		}
    		
    		$(this).children('div .J_hoverShow').css('display', '');
		},

		// 进入多图文按钮区域
		mouseenter:function() {
			if ($(this).hasClass('show-item')) {
    			var length = $(".show-item h1").length;

    			if (length == 1) {
    				$('.J_deleteArticle, .J_dragSort').hide();
    			} else {
    				$('.J_deleteArticle, .J_dragSort').show();
    			}
    		}
    		
	    	$(this).children('div .J_hoverShow').css('display', 'block');
	    }
  	}, 'li');

	// 副文本编辑器
	var editor = new UE.ui.Editor({
		initialFrameHeight:300,
		autoHeightEnabled:false,
		enterTag:'',
	});

	editor.render('container');

	// 每秒缓存富文本内容
	var cache_content = function () {
		if ($('.arrow-icon').attr('data-arrow') == 'arrow-first') {
			$('.J_change_data').attr('data-content', editor.getContent());
		} else {
			var dt = 0;

			for (var i = 0; i <= 6; i++) {
				if ($('.arrow-icon').attr('data-arrow') == 'arrow-'+i) {
					dt = 1;

					$('.J_change_data_'+i).attr('data-content', editor.getContent());
				}

				if (dt > 0) {
					break;
				}
			}
		}
	}

	setInterval(cache_content, 1000);

	// // 拖拽排序
	// $('#J_sortable').on({
 //  		click:function() {
	// 		if ($(this).hasClass('J_dragSort')) {
	// 			$('.state-disabled-'+$(this).attr('id')).addClass('sortable-border');
	// 			$('#J_editRight').css('margin-top', '0px');
	// 			$('.arrow-icon').attr('data-arrow', 'arrow-first');
	// 			$('.arrow-icon').removeAttr('style');
	// 			$('.i_title').val($('.J_change_title').attr('data-title'));
	// 			$('.i_author').val($('.J_change_data').attr('data-author'));
	// 			$('.i_csu').val($('.J_change_data').attr('data-csu'));
	// 			$('.i_scp').val($('.J_change_data').attr('data-scp'));
	// 			editor.setContent($('.J_change_data').attr('data-content'));
				
	// 			$('#J_sortable').sortable();
 //  			}
	//     },

	//     mouseleave:function() {
	//     	if ($(this).hasClass('J_dragSort')) {
	//     		if ($('.state-disabled-'+$(this).attr('id')).hasClass('sortable-border')) {
	// 	    		$('.state-disabled-'+$(this).attr('id')).removeClass('sortable-border');
	// 	    		$('#J_sortable').sortable('destroy');
	// 	    	}
	//     	}
 //  		}
 //  	}, 'a');


	if ($('#imgPre').attr('src') != '') {
		$('.uploadify-button-text').hide();
		$('.uploadify-image').show();
		$('#J_uploadDel').show();
		$('#J_uploadPhoto').removeClass('uploadify').addClass('uploadify-no');
	}

	// 删除更换图片预览
	$('#J_uploadDel').click(function() {
		var num = $('.arrow-icon').attr('data-arrow').split('-')[1];

		$('#imgPre').attr('src', '');
		$('.f_image_url').val('');
		$('.uploadify-image').hide();
		$('#J_uploadDel').hide();
		$('.uploadify-button-text').show();
		$('#J_uploadPhoto').removeClass('uploadify-no').addClass('uploadify');

		if (num == 'first') {
			$('.J_change_image').text('封面图片');
			$('.J_change_data').attr('data-image', '');
			$('.J_change_data').attr('data-image-id', '');
		} else {
			$('.J_change_image_'+num).text('缩略图');
			$('.J_change_data_'+num).attr('data-image', '');
	        $('.J_change_data_'+num).attr('data-image-id', '');
		}
	});

	// 更换图片显示
	$('#uploadPhotoWrap').on({
		mouseenter:function() {
			if ($('#imgPre').attr('src') != '') {
				$('.avator-upload-mask').css('display', 'block');
			}
		},

		mouseleave:function() {
			if ($('#imgPre').attr('src') != '') {
	  			$('.avator-upload-mask').css('display', 'none');
	  		}
		}
	}, '#J_uploadPhoto');

	// 图文按钮操作
  	$('#J_showCont').on({
		click:function() {
			if ($(this).hasClass('J_deleteArticle')) {
				var length = $(".show-item h1").length;
				var num = 8 - length;
				var id = $(this).attr('id');

				$('#J_aticleNum').html(num);
				$('#J_multiBox').show();
				$('ul li').remove('li[data-item='+id+']');
				$('#J_editRight').css('margin-top', '0px');
				$('.arrow-icon').attr('data-arrow', 'arrow-first');
				$('.arrow-icon').removeAttr('style');
				$('.i_title').val($('.J_change_title').attr('data-title'));
				$('.i_author').val($('.J_change_data').attr('data-author'));
				$('.i_csu').val($('.J_change_data').attr('data-csu'));
				$('.i_scp').val($('.J_change_data').attr('data-scp'));
				editor.setContent($('.J_change_data').attr('data-content'));

				if ($('.J_change_data').attr('data-image') != '') {
					$('.uploadify-button-text').hide();
					$('#imgPre').attr('src', $('.J_change_data').attr('data-image'));
					$('.uploadify-image').show();
					$('#J_uploadDel').show();
					$('#J_uploadPhoto').removeClass('uploadify').addClass('uploadify-no');
				} else {
					$('#imgPre').attr('src', '');
					$('.uploadify-image').hide();
					$('#J_uploadDel').hide();
					$('.uploadify-button-text').show();
					$('#J_uploadPhoto').removeClass('uploadify-no').addClass('uploadify');
				}
			} else if ($(this).hasClass('J_editArticle')) {
				var dt = 0;
				var id = $(this).attr('id');
				var no = $('.J_editArticle').index(this);
				$('.arrow-icon').removeAttr('style');
				
				if (no == 0) {
					$('.i-article').show();

					$('#J_editRight').css('margin-top', '0px');
					$('.arrow-icon').attr('data-arrow', 'arrow-first');
					$('.i_title').val($('.J_change_title').attr('data-title'));
					$('.i_author').val($('.J_change_data').attr('data-author'));
					$('.i_csu').val($('.J_change_data').attr('data-csu'));
					$('.i_scp').val($('.J_change_data').attr('data-scp'));
					if ($('.i_scp').val() == 1) {
						$('.i_scp').prop('checked', true);
					} else {
						$('.i_scp').prop('checked', false);
					}

					if ($('.J_change_data').attr('data-image') != '') {
						$('.uploadify-button-text').hide();
						$('#imgPre').attr('src', $('.J_change_data').attr('data-image'));
						$('.uploadify-image').show();
						$('#J_uploadDel').show();
						$('#J_uploadPhoto').removeClass('uploadify').addClass('uploadify-no');
					} else {
						$('#imgPre').attr('src', '');
						$('.uploadify-image').hide();
						$('#J_uploadDel').hide();
						$('.uploadify-button-text').show();
						$('#J_uploadPhoto').removeClass('uploadify-no').addClass('uploadify');
					}

					editor.setContent($('.J_change_data').attr('data-content'));
					$('#uploadImgWidth').text('900像素');
					$('#uploadImgHeight').text('500像素');
				} else {
					$('.i-article').hide();

					for (var i = 1; i <= 8; i++) {
						if (no == i) {
							dt = 1;

							if (no == 1) {
								$('#J_editRight').css('margin-top', '105px');
							} else {
								$('#J_editRight').css('margin-top', '167px');
							}

							switch(no) {
								case 3:
									$('.arrow-icon').css('top', '129.5px');
									break;
								case 4:
									$('.arrow-icon').css('top', '191.5px');
									break;
								case 5:
									$('.arrow-icon').css('top', '253.5px');
									break;
								case 6:
									$('.arrow-icon').css('top', '315.5px');
									break;
								case 7:
									$('.arrow-icon').css('top', '377.5px');
									break;
								case 8:
									$('.arrow-icon').css('top', '439.5px');
									break;
							}
							
							$('.arrow-icon').attr('data-arrow', 'arrow-'+id);
							$('.i_title').val($('.J_change_title_'+id).attr('data-title'));
							$('.i_author').val($('.J_change_data_'+id).attr('data-author'));
							$('.i_csu').val($('.J_change_data_'+id).attr('data-csu'));
							$('.i_scp').val($('.J_change_data_'+id).attr('data-scp'));
							if ($('.i_scp').val() == 1) {
								$('.i_scp').prop('checked', true);
							} else {
								$('.i_scp').prop('checked', false);
							}

							if ($('.J_change_data_'+id).attr('data-image') != '') {
								$('.uploadify-button-text').hide();
								$('#imgPre').attr('src', $('.J_change_data_'+id).attr('data-image'));
								$('.uploadify-image').show();
								$('#J_uploadDel').show();
								$('#J_uploadPhoto').removeClass('uploadify').addClass('uploadify-no');
							} else {
								$('#imgPre').attr('src', '');
								$('.uploadify-image').hide();
								$('#J_uploadDel').hide();
								$('.uploadify-button-text').show();
								$('#J_uploadPhoto').removeClass('uploadify-no').addClass('uploadify');
							}

							editor.setContent($('.J_change_data_'+id).attr('data-content'));
							$('#uploadImgWidth').text('200像素');
							$('#uploadImgHeight').text('200像素');
						}

						if (dt > 0) {
							break;
						}
					}
				}
			}
    	}
	}, 'a');

	// 表单提交验证
	// var beforeunload = false;
	$('.subPost').click(function() {
		var status = 0;
		var title = [];
		var u_id = [];
		var author = [];
		var show_cover_pic = [];
		var image_url = [];
		var content_source_url = [];
		var content = [];

		var data = new FormData();
		data.append('csrf_token', $('#csrf_token').val());
    	data.append('csrf_guid', $('#csrf_guid').val());
    	data.append('f_id', $('.graphics-id').val());
		
		$('.title-break').each(function (i) {
			if ($(this).attr('data-title') == '') {
				reset();
				alertify.alert('标题不能为空!');
				
				status = 1;
				return false;
			} else {
				title.push($(this).attr('data-title'));
			}
	    });

		if (status == 1) {
	    	return false;
	    } else {
	    	data.append('title', title);
	    }

		$('.data-break').each(function (i) {
			if ($(this).attr('data-image') == '') {
				reset();
				alertify.alert('请上传封面图片及缩略图!');
				
				status = 1;
				return false;
			} else if ($(this).attr('data-content') == '') {
				reset();
				alertify.alert('正文内容不能为空!');
				
				status = 1;
				return false;
			} else {
				u_id.push($(this).attr('data-uid'));
				author.push($(this).attr('data-author'));
				show_cover_pic.push($(this).attr('data-scp'));
				image_url.push($(this).attr('data-image-id'));
				content_source_url.push($(this).attr('data-csu'));
				content.push($(this).attr('data-content'));
			}
		});

		if (status == 1) {
	    	return false;
	    } else {
	    	data.append('u_id', u_id);
	    	data.append('author', author);
	    	data.append('show_cover_pic', show_cover_pic);
	    	data.append('image_url', image_url);
	    	data.append('content_source_url', content_source_url);
	    	data.append('content', content);
	    }

	    $.ajax({
	       url: $('.form-graphics').attr('action'),
	       type: $('.form-graphics').attr('method'),
	       data: data,
	       contentType: false,
	       processData: false,
	       
	       success:function(result) {
	          var data = jQuery.parseJSON(result);

	          if (data.errcode == 'error') {
	            alertify.alert(data.errmsg);
	            return false;
	          } else {
	            alertify.success(data.errmsg);
	            setTimeout(function() {
	                window.location.href = data.url;
	            }, 2000);
	          }
	       }
	    });

		// return beforeunload = true;
		// return true;
	});

	// 刷新、离开、倒退当前页提示信息
	// $(window).bind('beforeunload',function() {
	// 	if ($('.form-graphics').serialize() != '' && beforeunload == false) {
	// 		return '页面已保存数据!';
	// 	}
	// });
});

var modalCallBack = function (type, id) {
	var num = $('.arrow-icon').attr('data-arrow').split('-')[1];
    var url = $('.graphics-img-url').attr('data-url');
    var data = new FormData();

    data.append('csrf_token', $('#csrf_token').val());
    data.append('csrf_guid', $('#csrf_guid').val());
    data.append('id', id);

    $.ajax({
       url: url,
       type: 'POST',
       data: data,
       contentType: false,
       processData: false,
       
       success:function(result) {
          var data = jQuery.parseJSON(result);

          if (data.image != '') {
          	$('.uploadify-button-text').hide();
          	$('#imgPre').attr('src', data.image);
        	$('.uploadify-image').show();
        	$('#J_uploadDel').show();
        	$('#J_uploadPhoto').removeClass('uploadify').addClass('uploadify-no');

        	if (num == 'first') {
        		$('.J_change_image').text('');
        		$('.J_change_image').html('<img src="'+data.image+'" width="100%" height="100%" />');

        		$('.J_change_data').attr('data-image', data.image);
        		$('.J_change_data').attr('data-image-id', data.store_image_id);
        	} else {
        		$('.J_change_image_'+num).text('');
        		$('.J_change_image_'+num).html('<img src="'+data.image+'" width="100%" height="100%" />');

        		$('.J_change_data_'+num).attr('data-image', data.image);
        		$('.J_change_data_'+num).attr('data-image-id', data.store_image_id);
        	}
          }
       }
    });
}
