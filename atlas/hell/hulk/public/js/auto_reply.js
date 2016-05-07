/**
 * 微信自动回复交互
 *
 * @category yunke
 * @package atlas\hell\hulk\public\js
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

$(document).ready(function() {
  	$('#bjax-target').on({
      	mouseenter:function() {
      		$(this).children('p').children('.rule-option-button').show();
      	},

      	mouseleave:function() {
          	$(this).children('p').children('.rule-option-button').hide();
      	}
  	}, '.rule-list-item');

	  $('#rule-keyword').keydown(function(e) {
      if (e.keyCode == 13 && $(this).val() != '') {
        if ($('.keyword-hack').children('span').length < 5) {
          var html = '<span class="data-keyword">' + $(this).val() + '</span>';
          $('.keyword-hack').prepend(html);
        }

        $(this).val('');
      }
    });

    $('.auto-reply-concern').click(function() {
      if ($(this).hasClass('checks-0')) {
        
        $(this).removeClass('checks-0');
        $(this).addClass('checks-1');
        $(this).val('1');
      } else {
      	$(this).removeClass('checks-1');
        $(this).addClass('checks-0');
        $(this).val('0');
      }
    });

    $('.keyword-hack').on({
        click:function() {
          $('.data-keyword:eq(' + $(this).index() + ')').remove();
        },

        blur:function() {
          if ($('.keyword-hack').children('span').length < 5 && $('#rule-keyword').val()) {
            var html = '<span class="data-keyword">' + $(this).val() + '</span>';
            $('.keyword-hack').prepend(html);
          }

          $('#rule-keyword').val('');
        }
    }, '.data-keyword, #rule-keyword');

    $('#modal-editor').on({
        click:function() {
       		$('#modal-editor-data').attr('data-type', '').attr('data-id', '');
          $('#modal-editor').empty().hide();
         	$('#editor').show().focus();
        },

        mouseenter:function() {
        	$('.text-right').show();
        },

        mouseleave:function() {
        	$('.text-right').hide();
        }
    }, '.data-preview-trash, #messageList');

    $('.auto-reply-click').click(function() {
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

      if (! $('.auto-reply-name').val()) {
        reset();
        alertify.alert('规则名称不能为空!');
        return false;
      } else {
        data.append('name', $('.auto-reply-name').val());
      }

      if ($('.keyword-hack').children('span').length == 0) {
        reset();
        alertify.alert('关键词不能为空!');
        return false;
      } else {
        var keyword = new Array();

        $('.data-keyword').each(function (i) {
          keyword.push($('.data-keyword:eq(' + i + ')').text());
        });

        data.append('keyword', keyword);
      }

      if (! $('#editor').html() && ! $('#modal-editor').html()) {
        reset();
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

      data.append('concern', $('.auto-reply-concern').val());
      data.append('csrf_token', $('#csrf_token').val());
      data.append('csrf_guid', $('#csrf_guid').val());
      data.append('id', $(this).attr('data-id'));

      $.ajax({
         url: $('.form-auto-reply').attr('action'),
         type: $('.form-auto-reply').attr('method'),
         data: data,
         contentType: false,
         processData: false,
         
         success:function(result) {
            var data = jQuery.parseJSON(result);

            if (data.err == 'error') {
              reset();
              alertify.alert(data.msg);
              return false;
            } else {
              alertify.success(data.msg);
              setTimeout(function() {
                  window.location.href = data.url;
              }, 2000);
            }
         }
      });
  	});

    $('.concern-click').click(function() {
        var _this = $(this);
        var data = new FormData();
        data.append('csrf_token', $('#csrf_token').val());
        data.append('csrf_guid', $('#csrf_guid').val());
        data.append('id', _this.attr('data-id'));
        data.append('concern', _this.attr('data-type'));

        $.ajax({
           url: _this.attr('data-url'),
           type: 'POST',
           data: data,
           contentType: false,
           processData: false,
           
           success:function(result) {
              var data = jQuery.parseJSON(result);

              if (data.err == 'error') {
                alertify.error(data.msg);
              } else {
                if (_this.attr('data-type') == '0') {
                  _this.css('display', 'none');
                  _this.removeClass('btn-success');
                  _this.removeClass('no-radius');
                  _this.addClass('btn-default');
                  _this.addClass('rule-option-button');
                  _this.text('设置成关注回复');
                  _this.attr('data-type', '1');
                  _this.show();
                } else if (_this.attr('data-type') == '1') {
                  var _this_ = $('.no-radius');

                  if (_this_ != 'undefined') {
                    _this_.css('display', 'none');
                    _this_.removeClass('btn-success');
                    _this_.removeClass('no-radius');
                    _this_.addClass('btn-default');
                    _this_.addClass('rule-option-button');
                    _this.text('设置成关注回复');
                    _this_.attr('data-type', '1');
                  }

                  _this.removeClass('btn-default');
                  _this.removeClass('rule-option-button');
                  _this.addClass('no-radius');
                  _this.addClass('btn-success');
                  _this.text('已设为关注回复');
                  _this.attr('data-type', '0');
                  _this.show();
                }
              }
           }
        });
    });

    $('.matching-click').click(function() {
        var _this = $(this);
        var data = new FormData();

        data.append('csrf_token', $('#csrf_token').val());
        data.append('csrf_guid', $('#csrf_guid').val());
        data.append('id', _this.attr('data-id'));
        data.append('matching', _this.attr('data-type'));

        $.ajax({
           url: _this.attr('data-url'),
           type: 'POST',
           data: data,
           contentType: false,
           processData: false,
           
           success:function(result) {
              var data = jQuery.parseJSON(result);

              if (data.err == 'error') {
                alertify.error(data.msg);
              } else {
                if (_this.hasClass('active')) {
                  _this.removeClass('active');
                  _this.attr('data-original-title', '设为全字匹配');
                  _this.attr('data-type', '1');
                } else {
                  _this.addClass('active');
                  _this.attr('data-original-title', '设为模糊匹配');
                  _this.attr('data-type', '0');
                }
              }
           }
        });
    });
});

// 模态返回函数
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
        	$('#editor').hide();
        	$('#modal-editor').empty().append(data.html).show();
        	$('#modal-editor-data').attr('data-type', data.type).attr('data-id', data.id);
          }
       }
    });
}
