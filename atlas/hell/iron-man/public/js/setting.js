// 卡券基础设置 － no

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
    $('.J_change_image').text('封面大图');
    $('.uploadify-button-text').show();
    $('#J_uploadPhoto').removeClass('uploadify-no').addClass('uploadify');
  });

  $('.coupons-setting-click').click(function() {
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

    reset();
    if (! $('.f_image_url').val()) {
      alertify.alert('请上传商户Logo!');
      return false;
    }

    if (! $('.coupons-setting-name').val()) {
      alertify.alert('请输入商户名称!');
      return false;
    }

    data.append('logo', $('.f_image_url').val());
    data.append('name', $('.coupons-setting-name').val());
    data.append('id', $(this).attr('data-id'));
    data.append('csrf_token', $('#csrf_token').val());
    data.append('csrf_guid', $('#csrf_guid').val());

    $.ajax({
       url: $('.form-coupons-setting').attr('action'),
       type: $('.form-coupons-setting').attr('method'),
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

