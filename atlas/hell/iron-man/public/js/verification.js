// 核销员交互 - no

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
  if ($('.verification-store').val() != '') {
    $('.store-look').css('display', 'block');
  }

  $('#verification-body').on({
    click:function() {
      if ($(this).hasClass('verification-subPost')) {
        var store_id = [];
        var re = new RegExp("^[0-9]*[1-9][0-9]*$");
        var data = new FormData();

        if (! $('.verification-id').val() && ! $('.verification-openid').val()) {
          alertify.alert('请输入核销员openid!');
          return false;
        }

        if (! $('.verification-name').val()) {
          alertify.alert('请输入核销员姓名!');
          return false;
        }

        if (! $('.verification-mobile').val()) {
          alertify.alert('请输入核销员电话!');
          return false;
        } else if ($('.verification-mobile').val().match(re) == null) {
          alertify.alert('电话只能是大于0的整数!');
          return false;
        }

        if ($('.verification-id').val()) {
          $('.store-list').each(function (i) {
            store_id.push($(this).attr('data-id'));
          });

          if ($('.verification-status:checked').val() == undefined) {
            alertify.alert('请选择审核状态!');
            return false;
          } else if ($('.verification-location_id_list:checked').val() == 'store' && store_id == '') {
            alertify.alert('请添加适用门店!');
            return false;
          }

          data.append('status', $('.verification-status:checked').val());
          data.append('location_id_list', $('.verification-location_id_list:checked').val());
          data.append('store_id', store_id);
        } else {
          data.append('openid', $('.verification-openid').val());
        }

        data.append('csrf_token', $('#csrf_token').val());
        data.append('csrf_guid', $('#csrf_guid').val());
        data.append('id', $('.verification-id').val());
        data.append('name', $('.verification-name').val());
        data.append('mobile', $('.verification-mobile').val());

        $.ajax({
           url: $('.form-verification').attr('action'),
           type: $('.form-verification').attr('method'),
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
      } else if ($(this).hasClass('store-list')) {
        $(this).parents('.list-group-item').remove();

        if ($('.store-list').length == 0) {
          $('.store-look').css('display', 'none');
        }
      }
    }
  }, '.verification-subPost, .store-list');

});

var itemTemplate = function(data) {
  var template = Handlebars.compile($("#editor_section_item").html());
  $('#verification-group-item').append(template(data));
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
