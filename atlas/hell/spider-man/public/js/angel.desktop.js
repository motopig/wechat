$(document).ready(function() {
   // alertify初始化操作
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

   $('.firefox-input-btn').click(function() {
      if ($(this).hasClass('firefox-input-password')) {
         $(this).removeClass('firefox-input-password');
         $(this).addClass('firefox-input-text');
         $('.firefox-input').attr('type', 'text');
         $(this).html('隐藏');
      } else if ($(this).hasClass('firefox-input-text')) {
         $(this).removeClass('firefox-input-text');
         $(this).addClass('firefox-input-password');
         $('.firefox-input').attr('type', 'password');
         $(this).html('显示');
      }
   });

   $('.enterprise-personal-validated').change(function() {
      if ($(this).val() == 'enterprise') {
         $('.company-area-validated').show();
      } else if ($(this).val() == 'personal') {
         $('.company-validated').val('');
         $('.area-validated').val('');
         $('.company-area-validated').hide();
      }
   });

   $('.business-select li a').click(function () {
      if ($(this).text() == '其他') {
         $('.business-input').show();
      } else {
         $('.business-text').attr('');
         $('.business-input').hide();
      }
   });

   // ajax表单验证错误信息返回
   boltAjax = function(result) {
      var data = jQuery.parseJSON(result);
      var html = '';
      var divshow = '';

      if (data.response_type == 'success') {
         html += '<div class="alert alert-success">';
         html += '<button class="close" data-dismiss="alert" type="button">×</button>';
         html += '<strong><i class="icon-spinner icon-spin bigger-125"></i></strong>&nbsp;&nbsp;';
         html += '操作成功!';

         divshow = $(".bolt-response-success");
         divshow.empty().append(html);

         // 跳转新地址
         setTimeout(function() {
             window.location.href = data.response_url;
         }, 1000);
      } else if (data.response_type == 'error') {
         html += '<div class="alert alert-danger">';
         html += '<button class="close" data-dismiss="alert" type="button">×</button>';
         html += '<p>请检查以下错误信息: </p>';
         html += data.response_msg;

         divshow = $(".bolt-response-error");
         divshow.empty().append(html);
      }
   }

   // 单个删除
   $('.bolt-delete').bind('click', function() {
      var _this = $(this);

      reset();
      alertify.confirm("确认删除吗?", function (e) {
         if (e) {
            window.location.href = _this.attr('bolt-delete-url');
         } else {
            return false;
         }
      });
   });

   // 批量删除
   $('.bolt-drop').bind('click', function() {
      var del_val = new Array(); // 批量参数数组
      $.each($(":checkbox:checked"),function(i,e) {
          del_val.push($(e).val()); // 将选中的对象加入到数组中
      });

      // 判断是否选择数据
      reset();
      if (del_val != '' && del_val != '0') {
         alertify.confirm("确认批量删除吗?", function (e) {
            if (e) {
               var param = '?id=' + del_val;
               window.location.href = $('.bolt-drop').attr('bolt-drop-url') + param;
            } else {
               return false;
            }
         });
      } else {
         alertify.alert("请勾选需要删除的数据项!");
         return false;
      }
   });

   // 导出
   $('.bolt-export').bind('click', function() {
      var del_val = new Array(); // 批量参数数组
      $.each($(":checkbox:checked"),function(i,e) {
          del_val.push($(e).val()); // 将选中的对象加入到数组中
      });

      // 判断是否选择数据
      if (del_val != '' && del_val != '0') {
         var param = '?id=' + del_val;
         window.location.href = $('.bolt-export').attr('bolt-export-url') + param;
      } else {
         reset();
         alertify.alert("请勾选需要导出的数据项!");
         return false;
      }
   });

   // 按钮搜索
   $('.bolt-search').bind('click', function() {
      if ($('.bolt-search-input').val() == '') {
         reset();
         alertify.alert("请输入需要搜索的数据!");
         $('.bolt-search-input').focus();
         return false;
      } else {
         var param = '?search=' + $('.bolt-search-input').val();
         window.location.href = $(this).attr('bolt-search-url') + param;
      }
   });

   // 回车搜索
   $('.bolt-search-input').bind('keypress', function(event) {
      var keycode = (event.keyCode ? event.keyCode : event.which);  
      if (keycode == '13') { // 回车键  
         if ($(this).val() == '') {
            reset();
            alertify.alert('请输入需要搜索的数据!');
            $(this).focus();
            return false;
         } else {
            var param = '?search=' + $(this).val();
            window.location.href = $('.bolt-search').attr('bolt-search-url') + param;
         }
      }
   });

   // 模态框上传头像处理
   $(".head-file").change(function() {
      // 创建FormData对象
      var data = new FormData();

      // 为FormData对象添加数据
      $.each($('.head-file')[0].files, function(i, file) {
         data.append('file', file);
         data.append('csrf_token', $('#csrf_token').val());
         data.append('csrf_guid', $('#csrf_guid').val());
      });

      $.ajax({
         url: $(this).attr('bolt-head-url'),
         type: 'POST',
         data: data,
         cache: false,
         contentType: false, // 不可缺
         processData: false, // 不可缺
         success:function(result) {
            var data = jQuery.parseJSON(result);
            if (data.response_type == 'error') {
               var html = '';
               html += '<div class="alert alert-warning">';
               html += '<button class="close" data-dismiss="alert" type="button">×</button>';
               html += data.response_msg;

               divshow = $(".bolt-response-head-error");
               divshow.empty().append(html);
            }
         }
      });
   });
   
   ykAjaxForm = function(form_id){
       var $ajax_form = $('#'+form_id);
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
       
       $ajax_form.find('#yk-button').on('click',function(){
           
           var form_data = new FormData($ajax_form[0]);
           
           $.ajax({
              url: $ajax_form.attr('action'),
              type: $ajax_form.attr('method'),
              data: form_data,
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
   };
   
   
   
   planPrice = function(){
       var tower_num = $('input[name="tower[]"]').length;
       var plan_price = $('input[name="plan"]').attr('price');
       var plan_time = $('input[name="plan_time"]').val();
       
       if($('input[name="plan"]').val()=='ent'){
           var plan_name = '企业版';
       }else if($('input[name="plan"]').val()=='pro'){
           var plan_name = '旗舰版';
       }
       
       var order_count = 0;
       
       if(plan_time<12){
           order_count = tower_num*plan_price*plan_time;
           $('span.order_count').html('￥'+order_count+'.00 元');
           $('span.order_info').html(tower_num+'个云号 - '+plan_name+' - '+plan_time+'个月');
       }else if(plan_time==12){
           order_count = tower_num*plan_price*(plan_time-2)+9;
           order_discount = tower_num*plan_price*2-9;
           $('span.order_count').html('￥'+order_count+'.00 元');
           $('span.order_info').html(tower_num+'个云号 - '+plan_name+' - '+plan_time+'个月 节省了￥'+order_discount+'元');
       }
       
       
       
   };
   
   //云号套餐
   $('.from-order-shop').find('.btn_yunhao').on('click',function(e){
       var yh = this;
       if($(yh).hasClass('active')){
           /**
           var tower_num = $('input[name="tower[]"]').length;
           if(tower_num<2){
               e.preventDefault();
               return false;
           }else{
               $('input#tower_'+$(yh).attr('id')).remove();
           }
           */
           $('input#tower_'+$(yh).attr('id')).remove();
           
       }else{
           $('<input type="hidden" id="tower_'+$(yh).attr('id')+'" name="tower[]" value="'+$(yh).attr('id')+'">').appendTo($('.from-order-shop'));
       }
       
       planPrice();
   });
   
   $('.from-order-shop').find('.btn_plan').on('click',function(){
       var plan = this;
       $('.btn_plan').removeClass('active');
       $('.btn_plan').attr('aria-pressed',false);
       
       $('input[name="plan"]').remove();
       
       if(!$(plan).hasClass('active')){
           $('<input type="hidden" price="'+$(plan).attr('price')+'" id="plan_'+$(plan).attr('id')+'" name="plan" value="'+$(plan).attr('id')+'">').appendTo($('.from-order-shop'));
       }
       planPrice();
   });
   
   $('.from-order-shop').find('.btn_time').on('click',function(){
       var plan_time = this;
       $('.btn_time').removeClass('active');
       $('.btn_time').attr('aria-pressed',false);
       
       $('input[name="plan_time"]').remove();
       
       if(!$(plan_time).hasClass('active')){
           $('<input type="hidden" month="'+$(plan_time).attr('data-month')+'" id="plan_time_'+$(plan_time).attr('data-month')+'" name="plan_time" value="'+$(plan_time).attr('data-month')+'">').appendTo($('.from-order-shop'));
       }
       planPrice();
   });
   
   $('.from-order-shop').find('.btn_pay').on('click',function(){
       var pay = this;
       $('.btn_pay').removeClass('active');
       $('.btn_pay').attr('aria-pressed',false);
       
       $('input[name="pay"]').remove();
       
       if(!$(pay).hasClass('active')){
           $('<input type="hidden" id="pay_'+$(pay).attr('id')+'" name="pay" value="'+$(pay).attr('id')+'">').appendTo($('.from-order-shop'));
       }
       planPrice();
   });
   
});
