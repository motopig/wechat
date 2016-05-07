@extends('EcdoSpiderMan::layouts.clear.nonav')

@section('main')
<div class="row order_shop">
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        
        <section class="panel panel-default">
            <header class="panel-heading">
        		<i class="fa fa-file-text-o"></i>&nbsp;<a href="{{URL::to('angel/order/index')}}">账单列表</a>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;账单信息
            </header>
            
            <div class="panel-body">
                <link href="<?php echo e(asset('atlas/hell/spider-man/css/alifont.css')); ?>" rel="stylesheet" />
                
                <div class="text-left m-b-md padder-v b-b font-bold">
                    订单号 {{{$order['id']}}}
                    <span class="pull-right">
                        @if($order['status']=='ready')
                        <a href="{{URL::to('angel/order/pay/'.$order['id'])}}" class="btn btn-success font-bold btn-sm m-l-lg">立即支付</a>
                        @elseif(($order['status']=='paid'))
                        <button class="btn font-bold btn-dark disabled btn-sm m-l-lg">订单已完成</button>
                        @elseif(($order['status']=='cancel'))
                        <button class="btn font-bold btn-default disabled btn-sm m-l-lg">订单已经作废</button>
                        @endif
                    </span>
                </div>
                
                <div class="text-left">
                    <span class="text-muted text-xs clear block m-b-sm">超过24小时未支付将自动作废</span>
                    <span class="order_status clear m-b-sm">
                        订单状态: {{{$order['status_name']}}}
                    </span>
                    <span class="order_count clear m-b-sm">订单金额: <font class="text-danger font-bold">{{{money($order['order_count']/100)}}}元</font></span>
                    <span class="order_create clear m-b-sm">下单时间: {{{$order['created_at']}}}</span>
                    @if($order['status']!='ready')
                    <span class="order_update clear m-b-sm">更新时间: {{{$order['updated_at']}}}</span>
                    @endif                    
                    <span class="order_pay_method clear m-b-sm">支付方式: {{{$order['pay_method_name']}}}</span>
                </div>
                
                
                @if(!empty($order) && !empty($order_infos))
        
                    @if($order_infos)

                        <div class="text-left m-t-md m-b-md padder-v b-b font-bold">
                            订单内容
                        </div>
                        <div class="text-success">
                            <ul class="order_info">
                                @foreach($order_infos as $key=>$info)
                                <li id="{{$info['id']}}">
                                    {{$info['content']}}
                                </li>
                                @endforeach
                            </ul>
                        </div>

                    @endif
                    
                @else
        
                订单信息出错了，现在去查看 “<a href="{{URL::to('angel/order/index')}}">我的订单</a>”.
        
                @endif
                
            </div>
            
        </section>
        
        
    </div>
</div>

<script>
$(document).ready(function() {
    planPrice();
    ykAjaxForm('form_order');
});
</script>

@stop