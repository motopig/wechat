@extends('EcdoSpiderMan::layouts.clear.noside')

@section('main')
<div class="row order_shop">
    @include('EcdoSpiderMan::shop.side')
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <link href="<?php echo e(asset('atlas/hell/spider-man/css/alifont.css')); ?>" rel="stylesheet" />
        <h5 class="text-left m-b-lg b-b" style="padding-bottom:15px;"><i class="fa fa-shopping-cart"></i>&nbsp;购买云号套餐</h5>
        <form id="form_order" class="form-horizontal from-order-shop clear block m-t-lg" method="post" action="{{ URL::to('angel/order/create') }}">
            <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
            <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
            
            <div class="form-group">
          
                
                @if($cur_tower)
                
                <label class="col-xs-12 col-sm-2 control-label">当前云号</label>
                <div class="col-xs-12 col-sm-8">
                      <span id="{{{$cur_tower->id}}}" class="m-r-xs">
                          <a id="{{{$cur_tower->id}}}" class="btn btn-default btn_yunhao m-b-sm active " aria-pressed="true" data-toggle="button">
          	                  <span class="text text-dark">
                                  <i class="fa fa-cube" style="color:#ccc;"></i>&nbsp;{{{$cur_tower->name}}}
                              </span>
          	                  <span class="text-active text-black">
                                  <i class="fa fa-cube"></i>&nbsp;{{{$cur_tower->name}}}
                              </span>
                          </a>
                      </span>
                </div>
                
                @elseif($towers)
                
                <label class="col-xs-12 col-sm-2 control-label">选择云号</label>
                <div class="col-xs-12 col-sm-8">
                      
                      @foreach($towers as $key=>$tower)
                      
                      <span id="{{{$tower->id}}}" class="m-r-xs">
                          <a id="{{{$tower->id}}}" class="btn btn-default btn_yunhao m-b-sm @if($key==0) active @endif" @if($key==0)aria-pressed="true"@endif data-toggle="button">
          	                  <span class="text text-dark">
                                  <i class="fa fa-cube" style="color:#ccc;"></i>&nbsp;{{{$tower->name}}}
                              </span>
          	                  <span class="text-active text-black">
                                  <i class="fa fa-cube"></i>&nbsp;{{{$tower->name}}}
                              </span>
                          </a>
                      </span>
                      
                      @endforeach
                  
                </div>
                
                @endif
                
                
            </div>
            
            <div class="form-group">
          
                <label class="col-xs-12 col-sm-2 control-label">套餐期限</label>
                <div class="col-xs-12 col-sm-8">
                  <span>
                      <a class="btn btn-default btn_time btn_time_3 active" aria-pressed="true" data-month="3" data-toggle="button">
  	                  <span class="text text-black">3个月</span>
  	                  <span class="text-active text-black">3个月</span>
  	                </a>
                
                      <a class="btn btn-default btn_time btn_time_6" data-month="6" data-toggle="button">
  	                  <span class="text text-black">6个月</span>
  	                  <span class="text-active text-black">6个月</span>
  	                </a>
                    
                      <a class="btn btn-default btn_time btn_time_12" data-month="12" data-toggle="button">
  	                  <span class="text text-black">12个月(更省钱)</span>
  	                  <span class="text-active text-black">12个月(更省钱)</span>
  	                </a>
                
                  </span>
                  <div class="bootstrap-filestyle" style="display: inline;"></div>
                </div>
          
            </div>
            
            <div class="form-group">
                
                <label class="col-xs-12 col-sm-2 control-label">选择套餐</label>
                <div class="col-xs-12 col-sm-8 order-plans">

                    <div id="ent" price="{{$prices['ent']['price']}}" class="btn btn-default btn_plan btn_ent m-b-md m-r-sm active" aria-pressed="true" data-toggle="button">
  	                  <div class="text">
                          <div class="plan">
                              <h5>{{$prices['ent']['name']}}</h5>
                              <p class="price">
                                  ￥{{$prices['ent']['price']}}/月<br>
                                  年付套餐节省 ￥589元
                              </p>
                              <ul>
                                <li>公众号托管</li>
                                <li>微信高级功能</li>
                                <li>存储空间 {{$prices['ent']['space']}}G</li>
                                <li>摇一摇页面 {{$prices['ent']['yao_page']}}个</li>
                                <li>页面模板 {{$prices['ent']['template']}}个</li>
                                <li>含 {{$prices['ent']['sms']}} 条短信</li>
                            </ul>
                          </div>
  	                  </div>
  	                  <div class="text-active">
                          <div class="plan">
                              <h5>{{$prices['ent']['name']}}</h5>
                              <p class="price">
                                  ￥{{$prices['ent']['price']}}/月<br>
                                  年付套餐节省 ￥589元
                              </p>
                              <ul>
                                <li>公众号托管</li>
                                <li>微信高级功能</li>
                                <li>存储空间 {{$prices['ent']['space']}}G</li>
                                <li>摇一摇页面 {{$prices['ent']['yao_page']}}个</li>
                                <li>页面模板 {{$prices['ent']['template']}}个</li>
                                <li>含 {{$prices['ent']['sms']}} 条短信</li>
                            </ul>
                          </div>
  	                  </div>
  	                </div>
                    <div id="pro" price="{{$prices['pro']['price']}}" class="btn btn-default btn_plan btn_pro m-b-md" data-toggle="button">
    	                  <div class="text">
                            <div class="plan">
                                <h5>{{$prices['pro']['name']}}</h5>
                                <p class="price">
                                    ￥{{$prices['pro']['price']}}/月<br>
                                    年付套餐节省 ￥1989元
                                </p>
                                  <ul>
                                    <li>公众号托管</li>
                                    <li>微信高级功能</li>
                                    <li>存储空间 @if($prices['pro']['space']==99) 不限 @else{{$prices['pro']['space']}}G @endif</li>
                                    <li>摇一摇页面 @if($prices['pro']['yao_page']==99) 不限 @else{{$prices['pro']['yao_page']}}个 @endif</li>
                                    <li>页面模板 @if($prices['pro']['template']==99) 不限 @else{{$prices['pro']['template']}}个 @endif</li>
                                    <li>含 {{$prices['pro']['sms']}} 条短信</li>
                                </ul>
                            </div>
    	                  </div>
    	                  <div class="text-active">
                              <div class="plan">
                                  <h5>{{$prices['pro']['name']}}</h5>
                                  <p class="price">
                                      ￥{{$prices['pro']['price']}}/月<br>
                                      年付套餐节省 ￥1989元
                                  </p>
                                    <ul>
                                      <li>公众号托管</li>
                                      <li>微信高级功能</li>
                                      <li>存储空间 @if($prices['pro']['space']==99) 不限 @else{{$prices['pro']['space']}}G @endif</li>
                                      <li>摇一摇页面 @if($prices['pro']['yao_page']==99) 不限 @else{{$prices['pro']['yao_page']}}个 @endif</li>
                                      <li>页面模板 @if($prices['pro']['template']==99) 不限 @else{{$prices['pro']['template']}}个 @endif</li>
                                      <li>含 {{$prices['pro']['sms']}} 条短信</li>
                                  </ul>
                              </div>
    	                  </div>
                    </div>

                  <div class="bootstrap-filestyle" style="display: inline;"></div>
                </div>
          
            </div>
            
      
            <div class="form-group">
          
                <label class="col-xs-12 col-sm-2 control-label">选择支付方式</label>
                <div class="col-xs-12 col-sm-8">
                  <span>
                      <a id="wechat" class="btn btn-default btn_pay btn_wechat" data-toggle="button">
  	                  <span class="text text-black">
  	                    <i class="fa fa-wechat"></i>&nbsp;微信二维码支付
  	                  </span>
  	                  <span class="text-active text-black">
  	                    <i class="fa fa-wechat text-success"></i>&nbsp;微信二维码支付
  	                  </span>
  	                </a>
                
                      <a id="alipay" class="btn btn-default btn_pay btn_alipay disabled" data-toggle="button">
  	                  <span class="text text-muted">
  	                    <i class="icond icon-rectangle390 text-muted"></i>&nbsp;支付宝支付稍后开启
  	                  </span>
  	                  <span class="text-active text-black">
  	                    <i class="icond icon-rectangle390" style="color:#e66c06;"></i>&nbsp;支付宝支付
  	                  </span>
  	                </a>
                
                  </span>
                  <div class="bootstrap-filestyle" style="display: inline;"></div>
                </div>
          
            </div>
            
            <div class="form-group">
          
                <label class="col-xs-12 col-sm-2 control-label font-bold">应付金额</label>
                <div class="col-xs-12 col-sm-8 font-bold" style="min-height:36px;line-height:36px;color:#f83b3b;font-size:18px;">
                  <span class="order_count">
                      ￥299.00
                  </span>
                  <span class="order_info clear"></span>
                  <div class="bootstrap-filestyle" style="display: inline;"></div>
                </div>
          
            </div>
            
      
            <div class="form-group" style="margin-top:20px;">
              <div class="col-xs-10 col-sm-4 col-xs-offset-0 col-sm-offset-2">
                <button type="button" id="yk-button" class="btn btn-success btn-md font-bold">确认下单</button>&nbsp;
              </div>
            </div>
            @if($cur_tower)
            <input type="hidden" id="tower_{{{$cur_tower->id}}}" name="tower[]" value="{{{$cur_tower->id}}}">
            @elseif($towers)
            <input type="hidden" id="tower_{{{$towers[0]->id}}}" name="tower[]" value="{{{$towers[0]->id}}}">
            @endif
            
            <input type="hidden" price="299" id="plan_ent'" name="plan" value="ent">
            <input type="hidden" month="3" id="plan_time_3" name="plan_time" value="3">
      
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    planPrice();
    ykAjaxForm('form_order');
});
</script>

@stop