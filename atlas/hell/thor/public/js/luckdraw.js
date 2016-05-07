// 幸运大转盘交互 - no
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

  $('.date').datetimepicker({
    timeFormat: "HH:mm",
    dateFormat: "yy-mm-dd"
  });

  if ($('#oid').val() == '') {
    var data = {
      'number': 0,
      'class': 'add-prize',
      'title': '添加',
      'style': 'success',
      'type': JSON.parse($('#tpl_type').val()),
      'coupons': JSON.parse($('#tpl_coupons').val())
    };

    var template = Handlebars.compile($("#editor_prize").html());
    $('#prize-template').html(template(data));
  }

  $('.panel-body').on({
      click:function() {
        var _this = $(this);

        if (_this.hasClass('add-prize')) {
          if ($('.prize-float').size() == 4) {
            alertify.alert('最多只能添加4个奖品!');
            return false;
          }

          var data = {
            'number': new Date().getTime(),
            'class': 'del-prize',
            'title': '删除',
            'style': 'default',
            'type': JSON.parse($('#tpl_type').val()),
            'coupons': JSON.parse($('#tpl_coupons').val())
          };

          var template = Handlebars.compile($("#editor_prize").html());
          $('#prize-template').append(template(data));
        } else if (_this.hasClass('del-prize')) {
          _this.parent().parent().parent().parent().remove();
        }       
      },
  }, '.add-prize, .del-prize');

  $('.ld-click').click(function() {
    var begin = '';
    var end = '';

    if (! $('.ld-name').val()) {
      alertify.alert('请输入活动名称!');
      return false;
    }

    if (! $('.ld-begin_at').val()) {
      alertify.alert('请输入开始时间!');
      return false;
    }

    if (! $('.ld-end_at').val()) {
      alertify.alert('请输入结束时间!');
      return false;
    } else {
      end = new Date($('.ld-end_at').val().replace("-", "/").replace("-", "/")); 

      if (end < new Date()) {
        alertify.alert('结束时间不能小于当前时间!');
        return false;
      } 
    }

    if ($('.ld-end_at').val() <= $('.ld-begin_at').val()) {
      alertify.alert('结束时间必须大于开始时间!');
      return false;
    }

    if ($('.ld-nums').val() != '') {
      var re = new RegExp("^[0-9]*[1-9][0-9]*$");
      if ($('.ld-nums').val().match(re) == null) {
        alertify.alert('参与次数只能是大于0的正整数!');
        return false;
      }
    }

    $('.prize-panel').each(function (e) {
      if ($(this).find('.ld-prize-select').val() == '') {
        alertify.alert('请选择奖品!');
        return false;
      }

      if ($(this).find('.ld-chance').val() != '') {
        var re = new RegExp("^[0-9]*$");
        if ($(this).find('.ld-chance').val().match(re) == null) {
          alertify.alert('中奖概率只能是正整数!');
          return false;
        }
      }

      if ($(this).find('.ld-quantity').val() != '') {
        var re = new RegExp("^[0-9]*[1-9][0-9]*$");
        if ($(this).find('.ld-quantity').val().match(re) == null) {
          alertify.alert('奖品数量只能是大于0的正整数!');
          return false;
        }
      }
    });

    if ($('.ld-not_chance').val() != '') {
      var re = new RegExp("^[0-9]*$");
      if ($('.ld-not_chance').val().match(re) == null) {
        alertify.alert('未中奖概率只能是正整数!');
        return false;
      }
    }

    var data = new FormData();
    data.append('csrf_token', $('#csrf_token').val());
    data.append('csrf_guid', $('#csrf_guid').val());
    data.append('name', $('.ld-name').val());
    data.append('begin_at', $('.ld-begin_at').val());
    data.append('end_at', $('.ld-end_at').val());
    data.append('nums', $('.ld-nums').val());
    data.append('description', $('.ld-description').val());
    data.append('not_chance', $('.ld-not_chance').val());
    data.append('not_message', $('.ld-not_message').val());
    
    var type = [];
    var content = [];
    var chance = [];
    var quantity = [];
    var pid = [];
    $('.prize-panel').each(function (i) {
      type.push($(this).find('.ld-prize-radio:checked').val());
      content.push($(this).find('.ld-prize-select').val());
      chance.push($(this).find('.ld-chance').val());
      quantity.push($(this).find('.ld-quantity').val());
      pid.push($(this).attr('data-pid'));
    });

    data.append('prize', type + '@@@' + content + '@@@' + chance + '@@@' + quantity + '@@@' + pid);
    data.append('disabled', $('.ld-disabled-radio:checked').val());
    data.append('id', $('#oid').val());

    $.ajax({
       url: $('.form-ld').attr('action'),
       type: $('.form-ld').attr('method'),
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
