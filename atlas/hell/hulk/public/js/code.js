/**
 * 微信二维码交互
 *
 * @category yunke
 * @package atlas\hell\hulk\public\js
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

$(document).ready(function() {
  if ($('.code-click').attr('data-id') != '') {
    var _this = $('.use-select').find('.code-use');
    
    if (_this.attr('data-val') == 0) {
      var data = new FormData();

      data.append('id', $('.code-click').attr('data-id'));
        data.append('use', _this.attr('data-val'));
        data.append('csrf_token', $('#csrf_token').val());
        data.append('csrf_guid', $('#csrf_guid').val());

        $.ajax({
           url: _this.attr('data-url'),
           type: 'POST',
           data: data,
           contentType: false,
           processData: false,
           
           success:function(result) {
              var data = jQuery.parseJSON(result);

              if (data.html != '') {
                $('#code-action').empty().append(data.html);
              }
           }
        });
    }
  }
  
	$('.use-select li a').click(function () {
		var _this = $(this).attr('data-val');

    switch (_this) {
      case '':
        $('#code-action').hide();
        $('#code-verification').hide();
        break;
      case '0':
        $('#code-verification').hide();

        var data = new FormData();
        data.append('use', $(this).parent().val());
        data.append('csrf_token', $('#csrf_token').val());
        data.append('csrf_guid', $('#csrf_guid').val());

        $.ajax({
           url: $(this).attr('data-url'),
           type: 'POST',
           data: data,
           contentType: false,
           processData: false,
           
           success:function(result) {
              var data = jQuery.parseJSON(result);

              if (data.html != '') {
                $('#code-action').show();
                $('#code-action').empty().append(data.html);
              }
           }
        });

        break;
      case '1':
        $('#code-action').hide();
        $('#code-verification').show();
        break;
    }
	});

	$('#code-action').on({
	    click:function() {
	      $('#modal-editor-data').attr('data-type', '').attr('data-id', '');
	      $('#modal-editor').empty().hide();
	      $('#editor').show();
	    },

	    mouseenter:function() {
	      $('.text-right').show();
	    },

	    mouseleave:function() {
	      $('.text-right').hide();
	    }
	}, '.data-preview-trash, #messageList');

	$('.code-click').click(function() {
      var use = $('.use-select').find('.active > a').attr('data-val');
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

      if (use == '') {
          alertify.alert('请选择用途!');
          return false;
      } else if (use == 0) {
        if (! $('#editor').html() && ! $('#modal-editor').html()) {
          alertify.alert('回复内容不能为空!');
          return false;
        } else {
          if (! $('#modal-editor').html()) {
            data.append('type', 'text');
            data.append('content', $('#editor').html());
          } else {
            data.append('type', $('#modal-editor-data').attr('data-type'));
            data.append('content', $('#modal-editor-data').attr('data-id'));
          }
        }
      } else if (use == 1) {
        var re = new RegExp("^[0-9]*[1-9][0-9]*$");

        if ($('.code-quantity').val() != '' && $('.code-quantity').val().match(re) == null) {
          alertify.alert('核销验证数只能是大于0的整数!');
          return false;
        } else {
          data.append('type', 0);
          data.append('content', $('.code-quantity').val());
        }
      }

      data.append('name', $('.code-name').val());
      data.append('action_info', $('.code-acton-info').val());
      data.append('csrf_token', $('#csrf_token').val());
      data.append('csrf_guid', $('#csrf_guid').val());
      data.append('id', $(this).attr('data-id'));
      data.append('use', use);
      data.append('inventory', $('.code_inventory').val());

      $.ajax({
         url: $('.form-code').attr('action'),
         type: $('.form-code').attr('method'),
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
    });
});

var modalCallBack = function (type, id) {
    var url = $('.bolt-modal-preview-url').attr('data-url');
    var data = new FormData();

    data.append('csrf_token', $('#csrf_token').val());
    data.append('csrf_guid', $('#csrf_guid').val());
    data.append('type', type);
    data.append('id', id);

    $.ajax({
       url: url,
       type: 'POST',
       data: data,
       contentType: false,
       processData: false,
       
       success:function(result) {
          var data = jQuery.parseJSON(result);

          if (data.html != '') {
            $('#code-action').find('#editor').hide();
            $('#code-action').find('#modal-editor').empty().append(data.html).show();
            $('#code-action').find('#modal-editor-data').attr('data-type', data.type).attr('data-id', data.id);
          }
       }
    });
}
