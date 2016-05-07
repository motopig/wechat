/**
 * 摇一摇交互
 *
 * @category yunke
 * @package \hulk\public\js
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

$(document).ready(function() {
  if ($('#imgPre').attr('src') != '') {
    $('.uploadify-button-text').hide();
    $('.uploadify-image').show();
    $('#J_uploadDel').show();
    $('#J_uploadPhoto').removeClass('uploadify').addClass('uploadify-no');
  }

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

  $('#J_uploadDel').click(function() {
    $('#imgPre').attr('src', '');
    $('.f_image_url').val('');
    $('.uploadify-image').hide();
    $('#J_uploadDel').hide();
    $('.J_change_image').text('封面图片');
    $('.uploadify-button-text').show();
    $('#J_uploadPhoto').removeClass('uploadify-no').addClass('uploadify');
  });

  $('.page-type-select li a').click(function () {
    var _this = $(this).attr('data-val');
    $('.page-type-show').hide();
    $('.page-content').hide();
    $('.page-page_url').attr('readonly', false);
    $('.page-content-select').hide();

    switch (_this) {
      case '1':
        $('.page-type-show').show();
        break;
      case '2':
      case '5':
        $('#select_content_' + _this).show();
        $('.page-content').show();
        $('.page-page_url').attr('readonly', true);
        break;
    }
  });

  $('.page-click').click(function() {
    var type = $('.page-type-select').find('.active > a').attr('data-val');
    var data = new FormData();
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

    if (! $('.f_image_url').val()) {
      alertify.alert('请上传缩略图!');
      return false;
    }

    if (! $('.page-title').val()) {
      reset();
      alertify.alert('请输入主标题!');
      return false;
    }

    if (! $('.page-description').val()) {
      reset();
      alertify.alert('请输入副标题!');
      return false;
    }

    if (! $('.page-page_url').val() && type < 2) {
      reset();
      alertify.alert('请输入跳转链接!');
      return false;
    }

    if (type > 1 && $('#select_content_' + type).val() == '') {
      reset();
      alertify.alert('请选择页面内容!');
      return false;
    }

    data.append('csrf_token', $('#csrf_token').val());
    data.append('csrf_guid', $('#csrf_guid').val());
    data.append('title', $('.page-title').val());
    data.append('description', $('.page-description').val());
    data.append('page_url', $('.page-page_url').val());
    data.append('comment', $('.page-comment').val());
    data.append('id', $(this).attr('data-id'));
    data.append('page_id', $(this).attr('data-page-id'));
    data.append('store_image_id', $('.f_image_url').val());
    data.append('type', type);
    data.append('content', $('#select_content_' + type).val());

    $.ajax({
       url: $('.form-page').attr('action'),
       type: $('.form-page').attr('method'),
       data: data,
       contentType: false,
       processData: false,
       
       success:function(result) {
          var data = jQuery.parseJSON(result);

          if (data.errcode == 'error') {
            reset();
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
  });

  $('.deviceReloadClick').click(function() {
    var data = new FormData();
    data.append('csrf_token', $('#csrf_token').val());
    data.append('csrf_guid', $('#csrf_guid').val());

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

    $.ajax({
       url: $(this).attr('data-url'),
       type: 'POST',
       data: data,
       contentType: false,
       processData: false,
       
       success:function(result) {
          var data = jQuery.parseJSON(result);

          if (data.errcode == 'error') {
            reset();
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
  });

  $('.device-click').click(function() {
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

      if (! $('.device-quantity').val()) {
        reset();
        alertify.alert('请输入数量!');
        return false;
      } else {
        var re = new RegExp("^[0-9]*[1-9][0-9]*$");
        if ($('.device-quantity').val().match(re) == null) {
          alertify.alert('数量只能是大于0的正整数!');
          return false;
        }
      }

      if (! $('.device-apply_reason').val()) {
        reset();
        alertify.alert('请输入申请理由!');
        return false;
      }

      var data = new FormData();
      data.append('quantity', $('.device-quantity').val());
      data.append('apply_reason', $('.device-apply_reason').val());
      data.append('comment', $('.device-comment').val());
      data.append('sid', $('.device-sid').val());
      data.append('csrf_token', $('#csrf_token').val());
      data.append('csrf_guid', $('#csrf_guid').val());
      
      $.ajax({
         url: $('.from-device').attr('action'),
         type: $('.from-device').attr('method'),
         data: data,
         contentType: false,
         processData: false,
         
         success:function(result) {
            var data = jQuery.parseJSON(result);

            if (data.errcode == 'error') {
                reset();
                alertify.alert(data.errmsg);
                return false;
            } else {
                $('.device-click').attr('disabled', true);

                if (data.errcode == 'success') {
                    alertify.success(data.errmsg);
                    setTimeout(function() {
                        window.location.href = data.url;
                    }, 2000);
                } else if (data.errcode == 'log') {
                    alertify.log(data.errmsg);
                    setTimeout(function() {
                        window.location.href = data.url;
                    }, 4000);
                }
            }
         }
      });
  });

  $('.bind-device-page').on({
    change:function() {

    },

    click:function() {
        $('.page-' + $(this).attr('data-id')).remove();
    }
  }, '.close');
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
