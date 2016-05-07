@extends('EcdoSpiderMan::layouts.clear.nonav')

@section('main')
<section class="panel panel-default">
    <header class="panel-heading">
        <i class="fa fa-credit-card"></i>&nbsp;账户充值 &nbsp;(近期即将上线)
    </header>
  <!-- 引入表单提交验证信息模版 -->
  <div class="bolt-response-error"></div>
  <div class="bolt-response-head-error"></div>
  <div class="bolt-response-success"></div>
  <!-- 引入表单提交验证信息模版 -->

<link href="<?php echo e(asset('atlas/hell/spider-man/css/alifont.css')); ?>" rel="stylesheet" />

  <div class="panel-body">
      
      <form id="form" class="form-horizontal from-charge" method="post" action="{{ URL::to('angel/order/chargePay') }}">
          
          <div class="form-group">
              <div class="text-left m-b-mb col-xs-12 col-sm-10 col-xs-offset-0 col-sm-offset-3 text-muted">
                  预充值，购买套餐更便捷!
              </div>
              
          </div>
          
          <div class="form-group">
              
              <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
              <label class="col-xs-12 col-sm-3 control-label font-bold">充值金额</label>
              <div class="col-xs-12 col-sm-4">
                <span>
                    <input type="text" placeholder="请输入充值金额，例如120.5" name="cash" class="form-control" style="width:50%;" max-length="10" value="{{$cash}}">
                </span>
                <div class="bootstrap-filestyle" style="display: inline;"></div>
              </div>
              
          </div>
          
          <div class="form-group">
              
              <label class="col-xs-12 col-sm-3 control-label font-bold">充值方式</label>
              <div class="col-xs-12 col-sm-4">
                <span>
                    <a class="btn btn-default btn_wechat" data-toggle="button">
	                  <span class="text">
	                    <i class="fa fa-wechat"></i>&nbsp;微信支付
	                  </span>
	                  <span class="text-active">
	                    <i class="fa fa-check text-success"></i>&nbsp;微信支付
	                  </span>
	                </a>
                    
                    <a class="btn btn-default btn_alipay" data-toggle="button">
	                  <span class="text">
	                    <i class="icond icon-rectangle390"></i>&nbsp;支付宝支付
	                  </span>
	                  <span class="text-active">
	                    <i class="fa fa-check text-success"></i>&nbsp;支付宝支付
	                  </span>
	                </a>
                    
                </span>
                <div class="bootstrap-filestyle" style="display: inline;"></div>
              </div>
              
          </div>
          
          <div class="form-group" style="margin-top:20px;">
            <div class="col-xs-10 col-sm-4 col-xs-offset-0 col-sm-offset-3">
              <button type="button" class="btn btn-warning btn-md font-bold">功能稍后开启</button>&nbsp;
            </div>
          </div>
          
      </form>
  </div>
</section>
@stop