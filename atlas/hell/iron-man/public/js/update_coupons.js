// 编辑卡券 - no

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

	if ($('.coupons-store').val() != '') {
		$('.store-look').css('display', 'block');
	}

	$('.date').datetimepicker({
    	timeFormat: "HH:mm",
    	dateFormat: "yy-mm-dd"
	});

	$('#update-coupons-body').on({
		click:function() {
			if ($(this).hasClass('store-list')) {
			    $(this).parents('.list-group-item').remove();
      
	        if ($('.store-list').length == 0) {
	        	$('.store-look').css('display', 'none');
	        }
			} else if ($(this).hasClass('coupons-subPost')) {
			    var end = '';
      		var store_id = [];
      		var re = new RegExp("^[0-9]*[1-9][0-9]*$");

      		if (! $('.coupons-quantity').val()) {
	        	alertify.alert('请输入库存!');
	            return false;
	        } else if ($('.coupons-quantity').val().match(re) == null) {
	            alertify.alert('库存只能是大于0的整数!');
	            return false;
	        }

      		end = new Date($('.coupons-end_at').val().replace("-", "/").replace("-", "/")); 
        	if (end < new Date()) {
        		alertify.alert('结束时间不能小于当前时间!');
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
      		data.append('id', $('.coupons-id').val());
      		data.append('end_at', $('.coupons-end_at').val());
      		data.append('quantity', $('.coupons-quantity').val());
      		data.append('location_id_list', $('.coupons-location_id_list:checked').val());
      		data.append('store_id', store_id);

      		$.ajax({
         		url: $('.form-update-coupons').attr('action'),
         		type: $('.form-update-coupons').attr('method'),
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
	}, '.store-list, .coupons-subPost');
});

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

