/**
 * 门店交互
 *
 * @category yunke
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

$(document).ready(function() {
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

    $('.img_upload_wrp').on({
        mouseenter:function() {
          $(this).find('.js_edit_area').show();
        },

        mouseleave:function() {
          $(this).find('.js_edit_area').hide();
        },
    }, '.js_edit_pic_wrp');

    $('.img_upload_wrp').on({
        mouseenter:function() {
          $(this).removeClass('del_gray');
          $(this).addClass('del_black');
        },

        mouseleave:function() {
          $(this).removeClass('del_black');
          $(this).addClass('del_gray');
        },

        click:function() {
          $(this).parent().parent().remove();
        }
    }, '.js_delete');

    $('.es-click').click(function() {
      if (! $('.es-business_name').val()) {
        alertify.alert('请输入门店名称!');
        return false;
      }

      if (! $('.es-address').val()) {
        alertify.alert('请输入详细地址!');
        return false;
      }

      if (! $('.es-latitude').val()) {
        alertify.alert('请输入门店纬度!');
        return false;
      }

      if (! $('.es-longitude').val()) {
        alertify.alert('请输入门店经度!');
        return false;
      }

      if ($('.js_edit_pic_wrp').length == 0) {
        alertify.alert('至少上传一张门店图片!');
        return false;
      }

      if (! $('.es-telephone').val()) {
        alertify.alert('请输入门店电话!');
        return false;
      }

      var data = new FormData();
      var store_image_id = '';
      var dist = '';
      for (var i = 0; i < $('.js_edit_pic_wrp').size(); i++) {
        store_image_id += $($('.js_edit_pic_wrp').get(i)).attr('data-sid') + ',';
      }

      if ($('.dist').val() == null) {
        dist = '';
      } else {
        dist = $('.dist').val();
      }

      data.append('store_image_id', store_image_id);
      data.append('business_name', $('.es-business_name').val());
      data.append('branch_name', $('.es-branch_name').val());
      data.append('categories', $('.es-categories').val());
      data.append('sub', $('.es-sub').val());
      data.append('province', $('.prov').val());
      data.append('city', $('.city').val());
      data.append('district', dist);
      data.append('address', $('.es-address').val());
      data.append('latitude', $('.es-latitude').val());
      data.append('longitude', $('.es-longitude').val());
      data.append('telephone', $('.es-telephone').val());
      data.append('avg_price', $('.es-avg_price').val());
      data.append('open_time', $('.es-open_time').val());
      data.append('recommend', $('.es-recommend').val());
      data.append('special', $('.es-special').val());
      data.append('desc', $('.es-desc').val());
      data.append('signature', $('.es-signature').val());
      data.append('id', $(this).attr('data-id'));
      data.append('csrf_token', $('#csrf_token').val());
      data.append('csrf_guid', $('#csrf_guid').val());

      $.ajax({
         url: $('.form-es').attr('action'),
         type: $('.form-es').attr('method'),
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
          var count = $('.js_edit_pic_wrp').length;
          
          if (data.image != '') {
            if (count < 4) {
              var html = '<div data-sid="' + data.store_image_id + '" class="img_upload_box img_upload_preview_box js_edit_pic_wrp">';
              html += '<img src="' + data.image + '" alt="">';
              html += '<p class="img_upload_edit_area js_edit_area" style="display: none;">';
              html += '<a href="javascript:;" class="icon18_common del_gray js_delete"></a>';
              html += '</p>';
              html += '</div>';

              $('.img_upload_wrp').append(html);
            } else {
              reset();
              alertify.alert('最多添加4张门店图片!');
              return false;
            }
          }
       }
    });
}

