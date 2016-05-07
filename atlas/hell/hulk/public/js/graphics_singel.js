/**
 * 微信普通单图文交互
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

  	// 副文本编辑器
	var editor = new UE.ui.Editor({
		initialFrameHeight:300,
		autoHeightEnabled:false,
		enterTag:'',
	});
	editor.render('container');

	// 输入框进入标题预览
	$('.i_title').keypress(function() {
	  	if ($(this).val() == '') {
	  		$('.J_change_title').text('标题');
	  	} else {
	  		$('.J_change_title').text($(this).val());
	  	}
	});

	// 输入框离开标题预览
	$('.i_title').keyup(function() {
		if ($(this).val() == '') {
	  		$('.J_change_title').text('标题');
	  	} else {
	  		$('.J_change_title').text($(this).val());
	  	}
	});

	// 输入框进入摘要预览
	$('.i_digest').keypress(function() {
	  	$('.J_change_description').text($(this).val());
	});

	// 输入框离开摘要预览
	$('.i_digest').keyup(function() {
		$('.J_change_description').text($(this).val());
	});

	if ($('#imgPre').attr('src') != '') {
		$('.uploadify-button-text').hide();
		$('.uploadify-image').show();
		$('#J_uploadDel').show();
		$('#J_uploadPhoto').removeClass('uploadify').addClass('uploadify-no');
	}

	// 删除更换图片预览
	$('#J_uploadDel').click(function() {
		$('#imgPre').attr('src', '');
		$('.f_image_url').val('');
		$('.uploadify-image').hide();
		$('#J_uploadDel').hide();
		$('.J_change_image').text('封面图片');
		$('.uploadify-button-text').show();
		$('#J_uploadPhoto').removeClass('uploadify-no').addClass('uploadify');
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

	// 表单提交验证
	// var beforeunload = false;
	$('.subPost').click(function() {
		if (! $('.i_title').val()) {
			reset();
			alertify.alert('标题不能为空!');
			return false;
		}

		if ($('.graphics-id').val()) {
			if ($('#imgPre').attr('src') == '') {
				reset();
				alertify.alert('请上传封面图片!');
				return false;
			}
		} else {
			if (! $('.f_image_url').val()) {
				reset();
				alertify.alert('请上传封面图片!');
				return false;
			}
		}

		var content = editor.getContent();
		if (content == '') {
			reset();
			alertify.alert('正文内容不能为空!');
			return false;
		}

		// return beforeunload = true;
		return true;
	});

	// 刷新、离开、倒退当前页提示信息
	// $(window).bind('beforeunload',function() {
	// 	if ($('.form-graphics').serialize() != '' && beforeunload == false) {
	// 		return '页面已保存数据!';
	// 	}
	// });
});

var modalCallBack = function (type, id) {
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
          	$('.f_image_url').val(data.store_image_id);
        	$('.uploadify-image').show();
        	$('#J_uploadDel').show();
        	$('#J_uploadPhoto').removeClass().addClass('uploadify-no');

        	$('.J_change_image').text('');
        	$('.J_change_image').html('<img src="'+data.image+'" width="100%" height="100%" />');
          }
       }
    });
}
