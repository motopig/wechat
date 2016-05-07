/*卡券app交互 - no*/

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

    $('.coupons-add').click(function() {
        $('#couponModal').modal({
          keyboard: false
        });
    });

    $('.coupons-setting').click(function() {
    	var url = $(this).attr('data-url');
    	setting = function () {
	       alertify.set({
	           labels : {
	               ok     : "立即设置",
	               cancel : "关闭"
	           },
	           delay : 5000,
	           buttonReverse : false,
	           buttonFocus   : "ok"
	       });
	    };

	    setting();
        alertify.confirm("请先执行卡券基础设置", function (e) {
	    	if (e) {
		    	window.location.href = url;
	    	} else {
	        	return false;
	    	}
	    });
    });

    $('.add-coupons').click(function() {
	    var type = $('.type-select').find('.active > a').attr('data-val');
	    var coupons_type = $('input[name="coupons_type"]:checked').val();
	    if (type == '') {
	        
	        alertify.alert('请选择卡券类别!');
	        return false;
	    } else if (coupons_type == undefined) {
	    	alertify.alert('请选择卡券类型!');
	        return false;
	    }

        var data = new FormData();
        data.append('type', type);
	    data.append('coupons_type', coupons_type);
	    data.append('csrf_token', $('#csrf_token').val());
        data.append('csrf_guid', $('#csrf_guid').val());
	    
	    $.ajax({
	        url: $('.from-add-coupons').attr('action'),
	        type: $('.from-add-coupons').attr('method'),
	        data: data,
	        contentType: false,
	        processData: false,
	         
	        success:function(result) {
	            var data = jQuery.parseJSON(result);

	            if (data.errcode == 'success') {
	              window.location.href = data.url;
	            }
	        }
	    });
    });

	$('.coupons-delivery').click(function() {
        $('#delivery_id').val($(this).attr('data-id'));

        $('#deliveryModal').modal({
          keyboard: false
        });
    });

    $('.add-delivery').click(function () {
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

      var delivery = $('input[name="delivery"]:checked').val();
      if (delivery == undefined) {
          alertify.alert('请选择投放类型!');
          return false;
      }

      var data = new FormData();
      data.append('delivery', delivery);
      data.append('csrf_token', $('#csrf_token').val());
      data.append('csrf_guid', $('#csrf_guid').val());
      data.append('id', $('#delivery_id').val());

      $.ajax({
         url: $('.from-delivery').attr('action'),
         type: $('.from-delivery').attr('method'),
         data: data,
         contentType: false,
         processData: false,
         
         success:function(result) {
            var data = jQuery.parseJSON(result);

            if (data.errcode == 'error') {
            	alertify.alert(data.errmsg);
            	return false;
            } else {
            	window.open(data.url);
            }
         }
      });
    });
});
