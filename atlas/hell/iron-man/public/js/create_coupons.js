// 创建卡券 - no

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

  shopTemplate();
  $('.date').datetimepicker({
    timeFormat: "HH:mm",
    dateFormat: "yy-mm-dd"
  });

  $('.msg_card').on({
    mouseenter:function() {
      $(this).removeClass('edit_gray');
      $(this).addClass('edit_black');
    },

    mouseleave:function() {
      $(this).removeClass('edit_black');
      $(this).addClass('edit_gray');
    }
  }, '.icon18_common');

  $('#create-coupons-body').on({
    keyup:function() {
      if ($(this).hasClass('coupons-title')) {
        if ($(this).val() == '') {
          $('#js_title_preview').text('');
        } else {
          $('#js_title_preview').text($(this).val());
        }
      } else if ($(this).hasClass('coupons-sub_title')) {
        if ($(this).val() == '') {
          $('#js_sub_title_preview').text('');
        } else {
          $('#js_sub_title_preview').text($(this).val());
        }
      }
    },

    click:function() {
      if ($(this).hasClass('js_preview')) {
        $('.coupons-groups').hide();

        if ($(this).hasClass('shop')) {
          $('#coupons-group-shop').show();

          if (! $('#arrow-shop').length) {
            shopTemplate();
          }
        } else if ($(this).hasClass('dispose')) {
          $('#coupons-group-dispose').show();

          if (! $('#arrow-dispose').length) {
            disposeTemplate();
          }
        } else if ($(this).hasClass('details')) {
          $('#coupons-group-details').show();

          if (! $('#arrow-details').length) {
            detailsTemplate();
          }
        } else if ($(this).hasClass('store')) {
          $('#coupons-group-store').show();

          if (! $('#arrow-store').length) {
            storeTemplate();
          }
        }

        arrowStyle($('.coupons-code_type:checked').val());
      } else if ($(this).hasClass('coupons-code_type')) {
        $('#destroy_title').hide();
        $('.js_code_preview').hide();

        switch($('.coupons-code_type:checked').val()) {
          case 'CODE_TYPE_TEXT':
            arrowStyle('CODE_TYPE_TEXT');
            $('.preview_CODE_TYPE_TEXT').show();
            break;
          case 'CODE_TYPE_QRCODE':
            arrowStyle('CODE_TYPE_QRCODE');
            $('.preview_CODE_TYPE_QRCODE').show();
            break;
          case 'CODE_TYPE_BARCODE':
            arrowStyle('CODE_TYPE_BARCODE');
            $('.preview_CODE_TYPE_BARCODE').show();
            break;
        }
      } else if ($(this).hasClass('store-list')) {
        $(this).parents('.list-group-item').remove();
        
        if ($('.store-list').length == 0) {
          $('.store-look').css('display', 'none');
        }
      } else if ($(this).hasClass('js-card-bgcolor')) {
        var _this = $(this).attr('data-val');
        $('.card-bgcolor-hide').hide();
        $('.card-bgcolor-show').show();
        $('.card-bgcolor-show').attr('data-val', _this);
        $('.card-bgcolor-show').css('background-color', _this);
        $('#js_color_preview').css('background-color', _this);
      } else if ($(this).hasClass('coupons-subPost')) {
        var end = '';
        var store_id = [];
        var re = new RegExp("^[0-9]*[1-9][0-9]*$");
        
        if (! $('.card-bgcolor-show').attr('data-val')) {
          alertify.alert('请选择卡券颜色!');
                return false;
        }

        if ($('.coupons-coupons_setting').length) {
          switch($('.coupons-coupons_setting').attr('data-type')) {
            case 'DISCOUNT':
              if (! $('.coupons-coupons_setting').val()) {
                alertify.alert('请输入折扣额度!');
                      return false;
              } else if (! $('.coupons-coupons_setting').val().replace(/[^1-9.]/g, '') || 
                $('.coupons-coupons_setting').val() < 1 || $('.coupons-coupons_setting').val() > 9.9) {
                alertify.alert('折扣额度只能是1-9.9之间的数字!');
                      return false;
              }

              break;
            case 'CASH':
              if (! $('.coupons-coupons_setting').val()) {
                alertify.alert('请输入减免金额!');
                      return false;
              } else if (! $('.coupons-coupons_setting').val().replace(/[^0-9.]/g, '') || 
                $('.coupons-coupons_setting').val() < 0.01 || isNaN($('.coupons-coupons_setting').val())) {
                alertify.alert('减免金额只能是大于0.01的数字!');
                      return false;
              }
              
              break;
          }
        }

        if (! $('.coupons-title').val()) {
            alertify.alert('请输入卡券标题!');
            return false;
          }

          if (! $('.coupons-begin_at').val()) {
            alertify.alert('请输入开始时间!');
            return false;
          }

          if (! $('.coupons-end_at').val()) {
            alertify.alert('请输入结束时间!');
            return false;
          } else {
            end = new Date($('.coupons-end_at').val().replace("-", "/").replace("-", "/")); 

            if (end < new Date()) {
              alertify.alert('结束时间不能小于当前时间!');
                return false;
              } 
          }

          if ($('.coupons-end_at').val() <= $('.coupons-begin_at').val()) {
            alertify.alert('结束时间必须大于开始时间!');
            return false;
          }

          if (! $('.coupons-quantity').val()) {
            alertify.alert('请输入库存!');
              return false;
          } else if ($('.coupons-quantity').val().match(re) == null) {
              alertify.alert('库存只能是大于0的整数!');
              return false;
          }

          if ($('.coupons-use_limit').val() != '') {
            if ($('.coupons-use_limit').val().match(re) == null) {
                alertify.alert('领取限制只能是大于0的整数!');
                return false;
            }
          }

          if (! $('.coupons-code_type:checked').val()) {
            alertify.alert('请输选择销券方式!');
            return false;
          }

          if (! $('.coupons-notice').val()) {
            alertify.alert('请输入操作提示!');
            return false;
          }

          if (! $('.coupons-description').val()) {
            alertify.alert('请输入使用须知!');
            return false;
          }

          $('.store-list').each(function (i) {
            store_id.push($(this).attr('data-id'));
          });

          if ($('.coupons-location_id_list:checked').val() == 'store' && store_id == '') {
            alertify.alert('请添加适用门店!');
            return false;
          }

          var data = new FormData();
          data.append('csrf_token', $('#csrf_token').val());
          data.append('csrf_guid', $('#csrf_guid').val());
          data.append('url', $('.setting-url').val());
          data.append('logo_url', $('.shop-logo_url').val());
          data.append('brand_name', $('.shop-brand_name').val());
          data.append('type', $('.coupons-type').val());
          data.append('coupons_type', $('.coupons-coupons_type').val());
          data.append('color', $('.card-bgcolor-show').attr('data-val'));
          data.append('coupons_setting', $('.coupons-coupons_setting').val());
          data.append('title', $('.coupons-title').val());
          data.append('sub_title', $('.coupons-sub_title').val());
          data.append('begin_at', $('.coupons-begin_at').val());
          data.append('end_at', $('.coupons-end_at').val());
          data.append('quantity', $('.coupons-quantity').val());
          data.append('use_limit', $('.coupons-use_limit').val());
          data.append('code_type', $('.coupons-code_type:checked').val());
          data.append('notice', $('.coupons-notice').val());
          data.append('default_detail', $('.coupons-default_detail').val());
          data.append('description', $('.coupons-description').val());
          data.append('service_phone', $('.coupons-service_phone').val());
          data.append('location_id_list', $('.coupons-location_id_list:checked').val());
          data.append('store_id', store_id);

          if ($('.coupons-can_share:checked').val() == 'true') {
            data.append('can_share', 'true');
          } else {
            data.append('can_share', 'false');
          }

          if ($('.coupons-can_give_friend:checked').val() == 'true') {
            data.append('can_give_friend', 'true');
          } else {
            data.append('can_give_friend', 'false');
          }

          $.ajax({
             url: $('.form-create-coupons').attr('action'),
             type: $('.form-create-coupons').attr('method'),
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
      }
    }
  }, '.coupons-code_type, .js_preview, .store-list, .coupons-subPost, .coupons-title, .coupons-sub_title, .js-card-bgcolor');

  var arrowStyle = function(type) {
    switch(type) {
      case 'CODE_TYPE_TEXT':
        $('.icon18_common_preview').css('margin-top', '18px');
        $('#J_editRight_dispose').css('margin-top', '143px');
        $('#J_editRight_details').css('margin-top', '213px');
        $('#J_editRight_store').css('margin-top', '258px');
        break;
      case 'CODE_TYPE_QRCODE':
        $('.icon18_common_preview').css('margin-top', '90px');
        $('#J_editRight_dispose').css('margin-top', '220px');
        $('#J_editRight_details').css('margin-top', '378px');
        $('#J_editRight_store').css('margin-top', '423px');
        break;
      case 'CODE_TYPE_BARCODE':
        $('.icon18_common_preview').css('margin-top', '38px');
        $('#J_editRight_dispose').css('margin-top', '165px');
        $('#J_editRight_details').css('margin-top', '270px');
        $('#J_editRight_store').css('margin-top', '316px');
        break;
    }
  };
});

var shopTemplate = function() {
  var type = '';
  if ($('.coupons-coupons_type').val() == 'DISCOUNT' 
    || $('.coupons-coupons_type').val() == 'CASH') {
    type = 1;
  }

  var data = {
    'logo_url': $('.shop-logo_url').val(),
    'brand_name': $('.shop-brand_name').val(),
    'type': type,
    'setting_url': $('.shop-setting_url').val(),
    'color': JSON.parse($('.shop-color').val()),
    'content': JSON.parse($('.shop-content').val())
  };

  var template = Handlebars.compile($("#editor_section_shop").html());
  Handlebars.registerHelper("compare_shop", function(key, value, options) {
    if (key == value) {
      return options.fn(this);
    } else {
      return options.inverse(this);
    }
  });

  $('#coupons-group-shop').html(template(data));
};

var disposeTemplate = function() {
  var template = Handlebars.compile($("#editor_section_dispose").html());
  $('#coupons-group-dispose').html(template());
};

var detailsTemplate = function() {
  var template = Handlebars.compile($("#editor_section_details").html());
  $('#coupons-group-details').html(template());
};

var storeTemplate = function() {
  var template = Handlebars.compile($("#editor_section_store").html());
  $('#coupons-group-store').html(template());
};

var itemTemplate = function(data) {
  var template = Handlebars.compile($("#editor_section_item").html());
  $('#coupons-group-item').append(template(data));
};

var modalCallBack = function(type, id) {
  var store_id = [];
  
  $('.store-list').each(function (i) {
    store_id.push($(this).attr('data-id'));
  });

    var data = new FormData();
    data.append('csrf_token', $('#csrf_token').val());
    data.append('csrf_guid', $('#csrf_guid').val());
    data.append('id', id);
    data.append('store_id', store_id);

    $.ajax({
       url: $('.bolt-modal-preview-url').val(),
       type: 'POST',
       data: data,
       contentType: false,
       processData: false,
       
       success:function(result) {
          var res = jQuery.parseJSON(result);

          if (res != '') {
            var data = {
        'store': res
      };

      itemTemplate(data);
      $('.store-look').css('display', 'block');
          }
       }
    });
};
