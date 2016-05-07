/**
 * 公用模态框交互
 *
 * @category yunke
 * @package atlas\hell\spider-man\public\js
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */
$(document).ready(function() {
	// 弹出模态框
	$('.hell-modal-dialog').on({
		click:function() {
			var url = $('.bolt-modal-url').attr('data-url') + '/' + $(this).attr('data-type');
		    var data = new FormData();

		    data.append('csrf_token', $('.hellCsrfToken').attr('csrfToken'));
		    data.append('csrf_guid', $('.hellCsrfGuid').attr('CsrfGuid'));
		    data.append('current', 0);
		      
		    ajaxUrl(url, data);
	    }
	}, '.bolt-modal-click');

	// 微信表情
    $('#bjax-target').on({
		click:function(e) {
			if($(this).hasClass('noclick')){
	            $('#editor').focus();
	        }

	        if ($('.emoji_list').css('display') == 'none') {
	            $('.emoji_list').css('display', 'block');
	        } else {
	            $('.emoji_list').css('display', 'none');
	        }

	        $('.emoji_list').html(emoji());
	        e.stopPropagation();
	    }
    }, '.wechat_emoji');

    $(document).click(function(){
        $('.emoji_list').hide(0);
    });

    $('.hell-modal-dialog').on({
        click:function() {
            var emojiPreview = '';
            var selfImg = $(this).children('img');
            emojiPreview += '<img src="'+ selfImg.attr('src') +'" data="'+  $(this).attr('data') +'" />';
            if($('#modal-editor').css('display') == 'block'){
                $('#modal-editor').css('display', 'none');
                $('#modal-editor').html('');
                $('#editor').css('display', 'block');
            }
            _insertHtml($('#editor'),emojiPreview);
            $('.emoji_list').css('display', 'none');
        }
    }, '.emoji_list a');

	// 模态框内容交互
	$('#myModalBody').on({
		click:function() {
	    	if ($(this).hasClass('graphics-title')) { // 多图文标题下拉展示
	    		var _this = $(this).children().children();
	    		var id = _this.attr('graphics-title-id');

				if (_this.hasClass('fa fa-sort-down')) {
					_this.attr('class', 'fa fa-sort-up');
					$('.graphics-title-fid_' + id).show();
				} else {
					_this.attr('class', 'fa fa-sort-down');
					$('.graphics-title-fid_' + id).hide();
				}
	    	} else if ($(this).hasClass('modal-button-click')) { // 模态框按钮选用
		        var params = $(this).attr('data-params').split(',');

		        $('#myModal').modal('hide');
		        modalCallBack(params[0], params[1]);
		    } else if ($(this).hasClass('modal-button-all')) { // 模态框按钮全选
		        var modal_val = new Array(); // 批量参数数组
			    $.each($(":checkbox:checked"),function(i, e) {
			    	modal_val.push($(e).val()); // 将选中的对象加入到数组中
			    });

			    if (modal_val != '' && modal_val != '0') {
			        $('#myModal').modal('hide');
		        	modalCallBack($(this).attr('data-type'), modal_val);
			    } else {
			      	reset();
			        alertify.alert("请勾选需要操作的数据项!");
			        return false;
			    }
		    } else {
	    		var url = $('.bolt-modal-url').attr('data-url') + '/' + $(this).attr('data-type');
		        var data = new FormData();

		        if ($(this).hasClass('modal-search-click')) { // 模态框内容搜索
		            if ($('input[name="search_type"]').length != 0) {
		                if ($('input[name="search_type"]:checked').val() == '' && $('.modal-search-control').val() == '') {
		                    reset();
		                    alertify.alert('请输入需要搜索的内容!');
		                    return false;
		                }

		                data.append('search_type', $('input[name="search_type"]:checked').val());
		            } else {
		                if ($('.modal-search-control').val() == '') {
		                  reset();
		                  alertify.alert('请输入需要搜索的内容!');
		                  return false;
		                }
		            }

		            data.append('search', $('.modal-search-control').val());
		            data.append('current', 0);
		        } else if ($(this).hasClass('modal-refresh-click')) { // 模态框刷新
		            data.append('current', 0);
		        } else if ($(this).hasClass('modal-leave-click')) { // 模态框离开搜索列表
		            if ($('input[name="search_type"]').length != 0) {
		                data.append('search_type', '');
		            }

		            data.append('search', '');
		            data.append('current', 0);
		        } else if ($(this).hasClass('modal-ajax-click')) { // 模态框菜单按钮
		            data.append('search', '');
		            data.append('current', 0);
		            data.append('action', $(this).attr('data-action'));
		        } else if ($(this).hasClass('modal-target-click')) { // 模态框菜单跳转
		        	window.open($(this).attr('data-target-url'), '_blank');

		            if ($('input[name="search_type"]').length != 0) {
		                data.append('search_type', '');
		            }

		            data.append('search', '');
		            data.append('current', 0);
		        } else if ($(this).hasClass('modal-file-click')) { // 模态框上传数据
			        data.append('type', $(this).attr('data-type'));
			        
			        switch($(this).attr('data-type')) {
		            	case 'image':
		                    $.each($('.image-file')[0].files, function(i, file) {
		                    	data.append('file', file);
						    });
		                    break;
		                case 'voice':
		                    $.each($('.voice-file')[0].files, function(i, file) {
						    	data.append('file', file);
						    });
		                    break;
		                case 'video':
		                    $.each($('.video-file')[0].files, function(i, file) {
						    	data.append('file', file);
						    });
		                    break;
		            }
			    } else {
			    	// 模态框翻页
		            if ($('.modal-leave-click').length != 0 && $('.modal-leave-click').attr('data-parameter') != '') {
		                var arr = $('.modal-leave-click').attr('data-parameter').split(',');

		                if ($('input[name="search_type"]').length != 0) {
		                  data.append('search_type', arr[1]);
		                }

		                data.append('search', arr[0]);
		            }

		            data.append('current', $(this).attr('data-rel'));
		        }

		        data.append('csrf_token', $('.hellCsrfToken').attr('csrfToken'));
		        data.append('csrf_guid', $('.hellCsrfGuid').attr('CsrfGuid'));
		        ajaxUrl(url, data);
		    }
	    }
	}, '.modal-next-click, .modal-previous-click, .modal-refresh-click, .modal-search-click, .modal-leave-click, .graphics-title, .modal-button-click, .modal-ajax-click, .modal-target-click, .modal-file-click, .modal-button-all');

	// 模态框后台数据交互
	var ajaxUrl = function(url, data) {
	    $.ajax({
	         url: url,
	         type: 'POST',
	         data: data,
	         contentType: false,
	         processData: false,
	         
	         success:function(result) {
	            var data = jQuery.parseJSON(result);

	            if (data.html != '') {
	                switch(data.type) {
	                  case 'image':
	                    $('#myModalLabel').empty().append('<i class="fa fa-picture-o"></i>&nbsp;图片');
	                    break;
	                  case 'voice':
	                    $('#myModalLabel').empty().append('<i class="fa fa-microphone"></i>&nbsp;语音');
	                    break;
	                  case 'video':
	                    $('#myModalLabel').empty().append('<i class="fa fa-video-camera"></i>&nbsp;视频');
	                    break;
	                  case 'graphics':
	                  case 'material':
	                  	$('#myModalLabel').empty().append('<i class="fa fa-file-text"></i>&nbsp;图文');
	                    break;
	                  case 'store':
	                    $('#myModalLabel').empty().append('<i class="fa fa-home"></i>&nbsp;门店');
	                    break;
	                }

	                $('#myModalBody').empty().append(data.html);
	                $('#myModal').modal({
	                  keyboard: false
	                });
	            }
	         }
	    });
  	}
});
