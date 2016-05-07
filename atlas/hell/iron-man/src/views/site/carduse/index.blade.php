@extends('EcdoSpiderMan::layouts.site.default')

@section('main')
<section class="scrollable" id="bjax-target">
	<section class="panel panel-default portlet-item">
		<header class="panel-heading">
	    	<i class="fa fa-user"></i>&nbsp;
	      	核销员：{{$verification->info['name']}}

	        <span class="text-muted m-l-sm pull-right">
	            <a href="{{URL::to(Session::get('guid') . '/card/verification/' . $data . '/true')}}" data-dismiss="alert" class="btn btn-default btn-xs">
	              <i class="fa fa-home text-muted"></i>&nbsp; 首页
	            </a>
	        </span>
	    </header>
    </section>

	<section id="content" class="padder-lg carduse-body-click" style="margin-top:30px;">
		<input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
		<input type="hidden" class="carduse_openid" value="{{Session::get('openid')}}" />
		<input type="hidden" class="carduse-url" value="{{URL::to(Session::get('guid') . '/card/carduseDis')}}" />

	  	<div class="tab-pane" id="qrcode">
	    	<div class="btn-group btn-group-justified">
	        	<a href="javascript:void(0);" class="btn btn-success qrcode-click" 
	        	data-action="scanQRCode" data-url="{{URL::to(Session::get('guid') . '/card/wxjsQrcode')}}">
	        		<i class="fa fa-qrcode"></i> <i class="fa fa-barcode"></i> &nbsp; 扫码核销
	        	</a>
	        </div>
	    </div>
	    <hr class="hr-middle" style="margin-top:40px;margin-bottom:40px;" />

	    <div class="tab-pane" id="laptop">
	    	<div class="form-group">
	          <input type="text" class="form-control carduse-code" placeholder="请输入卡券券码">
	        </div>

	    	<div class="btn-group btn-group-justified" style="margin-top:20px;">
	        	<a href="javascript:void(0);" class="btn btn-success laptop-click">
	        		<i class="fa fa-laptop"></i> &nbsp; 网页核销
	        	</a>
	        </div>
	    </div>
	</section>
</section>

<script src="{{asset('assets/tower/api/wechat/jsapi.js?321')}}"></script>
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

	$('.carduse-body-click').on({
		click:function() {
			var data = new FormData();
            data.append('csrf_guid', $('#csrf_guid').val());
            data.append('openid', $('.carduse_openid').val());

            if ($(this).hasClass('laptop-click')) {
            	if ($('.carduse-code').val() == '') {
            		alertify.alert('请输入卡券券码!');
                    return false;
            	} else {
            		data.append('type', 1);
            		data.append('id', $('.carduse-code').val());
            	}

            	$.ajax({
	            	url: $('.carduse-url').val(),
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
	                		return true;
	                	}
	           		}
	            });
            } else if ($(this).hasClass('qrcode-click')) {
            	var wdata = new FormData();
            	wdata.append('csrf_guid', $('#csrf_guid').val());
			    wdata.append('action', $(this).attr('data-action'));
			    wdata.append('url', window.location.href); // ajax请求需要获取原先地址

				$.ajax({
			    	url: $(this).attr('data-url'),
			    	type: 'POST',
			    	data: wdata,
			    	contentType: false,
			    	processData: false,
			       
			    	success:function(result) {
			        	var res = jQuery.parseJSON(result);
			      		yk.wechatCommon(res.config, res.data);
			    	}
			    });
            }
		}
	}, '.qrcode-click, .laptop-click');
});

var wechatCallBack = function (res) {
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

	var code = '';
	if (res.data.indexOf(',') != -1) {
		code = res.data.split(',')[1];
	} else {
		code = res.data;
	}

	var data = new FormData();
    data.append('csrf_guid', $('#csrf_guid').val());
    data.append('openid', $('.carduse_openid').val());
    data.append('type', 0);
    data.append('id', code);
    
	$.ajax({
    	url: $('.carduse-url').val(),
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
	            }, 1000);
        	}
   		}
    });
};
</script>
@stop
