// 核销券交互 - no

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
	$('#carduse-body').on({
	  	click:function() {
	      	if ($(this).hasClass('carduse-verification-click')) {
		      	var _this = $(this);

		        alertify.confirm("核销后，用户卡券将失效；确认核销吗?", function (e) {
			    	if (e) {
			            var data = new FormData();
			            data.append('csrf_token', $('#csrf_token').val());
			            data.append('csrf_guid', $('#csrf_guid').val());
			            data.append('id', _this.attr('data-id'));
			            data.append('type', 1);
			            
			            $.ajax({
			               url: _this.attr('data-url'),
			               type: 'POST',
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
			        } else {
			    		return false;
			        }
		      	});
	    	}
		}
	}, '.carduse-verification-click');
});
