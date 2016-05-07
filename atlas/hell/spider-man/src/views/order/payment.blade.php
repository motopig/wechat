@extends('EcdoSpiderMan::layouts.clear.noside')

@section('main')
<div class="row order_shop">
    @include('EcdoSpiderMan::order.side')
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <link href="<?php echo e(asset('atlas/hell/spider-man/css/alifont.css')); ?>" rel="stylesheet" />
        <h4 class="text-left b-b" style="padding-bottom:15px;"><i class="fa fa-shopping-cart"></i>&nbsp;结算支付</h4>
        
        @if(!empty($order) && !empty($order_infos))
        
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 b-r">
            @include("EcdoSpiderMan::order.".$order['pay_method']."pay")
        </div>
        
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            
            <div class="text-left padder-v font-bold">
                订单号 {{{$order['id']}}}
            </div>
            
            <div class="text-left">
                <span class="text-muted text-xs clear block m-b-sm">超过24小时未支付将自动作废</span>
                <span class="order_count clear m-b-sm">订单金额: <font class="text-danger font-bold">{{{money($order['order_count']/100)}}}元</font></span>
                <span class="order_create clear m-b-sm">下单时间: {{{$order['created_at']}}}</span>
                @if($order['status']!='ready')
                <span class="order_update clear m-b-sm">更新时间: {{{$order['updated_at']}}}</span>
                @endif                    
                <span class="order_pay_method clear m-b-sm">支付方式: {{{$order['pay_method_name']}}}</span>
            </div>
            
            
            @if($order_infos)

                <div class="text-left m-b-sm font-bold">订单信息</div>
                <div class="text-left text-success">
                    <ul class="order_info">
                        @foreach($order_infos as $key=>$info)
                        <li id="{{$info['id']}}">
                            {{$info['content']}}
                        </li>
                        @endforeach
                    </ul>
                </div>

            @endif
            
        </div>
        
        
        @else
        
        订单信息出错了，现在去查看 “<a href="{{URL::to('angel/order/index')}}">我的订单</a>”.
        
        @endif
        
    </div>
</div>

<script>
$(document).ready(function() {
    planPrice();
    ykAjaxForm('form_order');
});
</script>

@stop