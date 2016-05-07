@extends('EcdoSpiderMan::layouts.site.default')

@section('main')
<section id="content" class="scrollable">
    <div style="margin-bottom: 30px;">
	   <h4 style="text-align: center;">核销员注册</h4>
    </div>
	
    <form class="form-horizontal form-verification" role="form" method="POST" action="{{URL::to($request['guid'] . '/card/verificationDis')}}">
        <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
        <input type="hidden" class="verification-code_id" value="{{$request['code_id']}}" />
        <input type="hidden" class="verification-openid" value="{{$request['openid']}}" />

        <div class="col-xs-12">
            <div class="form-group">
                <label class="col-lg-2 control-label">您的姓名</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control verification-name" placeholder="核销员真实姓名">
                </div>
            </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="col-xs-12">
            <div class="form-group">
                <label class="col-lg-2 control-label">您的电话</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control verification-mobile" placeholder="核销员联系方式">
                </div>
            </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="col-xs-12">
            <div class="fn-clear" style="text-align: center;">
                <button class="btn btn-s-md btn-success verification-subPost" type="button">保存</button>
            </div>
        </div>
    </form>
</section>

<script type="text/javascript">
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

    $('.verification-subPost').click(function() {
    	var re = new RegExp("^[0-9]*[1-9][0-9]*$");

    	if (! $('.verification-name').val()) {
        	alertify.alert('请输入核销员姓名!');
            return false;
        }

        if (! $('.verification-mobile').val()) {
        	alertify.alert('请输入核销员电话!');
            return false;
        } else if ($('.verification-mobile').val().match(re) == null) {
        	alertify.alert('核销员电话只能是大于0的整数!');
            return false;
        }

        var data = new FormData();
        data.append('csrf_guid', $('#csrf_guid').val());
        data.append('code_id', $('.verification-code_id').val());
    	data.append('openid', $('.verification-openid').val());
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
	              }, 1000);
	            }
        	}
    	});
    });
});
</script>
@stop
