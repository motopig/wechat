$(document).ready(function() {
	$('.tower-business-select li a').click(function () {
      if ($(this).attr('data-val') == 'other') {
         $('.business-input').show();
      } else {
         $('.tower-business-text').attr('');
         $('.business-input').hide();
      }
   });

  $('.tower-click').click(function() {
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

      var data = new FormData();
      if (! $('.tower-name').val()) {
          reset();
          alertify.alert('云号名称不能为空');
          return false;
      } else {
          data.append('name', $('.tower-name').val());
      }

      var business = $('.tower-business-select').find('.active > a').attr('data-val');
      data.append('business', business);
      if (business == 'other') {
          data.append('business_other', $('.tower-business-text').val());
      }

      data.append('id', $('.tower-click').attr('data-id'));
      data.append('csrf_token', $('#csrf_token').val());
      data.append('csrf_guid', $('#csrf_guid').val());

      $.ajax({
         url: $('.tower-form').attr('action'),
         type: $('.tower-form').attr('method'),
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
              if(data.url){
                  setTimeout(function() {
                      window.location.href = data.url;
                  }, 1000);
              }
              
            }
         }
      });
    });
});
