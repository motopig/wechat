@extends('EcdoSpiderMan::layouts.clear.nonav')

@section('main')
    

    <div class="row">
        <div class="col-sm-12 col-sm-10">
            <section class="panel panel-default">
                <header class="panel-heading">
            		<i class="fa fa-file-text-o"></i>&nbsp;账单列表
                </header>
                
                <div class="table-responsive">
                  <table class="table table-striped b-light">
                          <thead>
                            <tr>
                              <th class="col-sm-2 col-md-2 header">订单号<i class="fa fa-sort fa-sort-p"></i></th>
                              <th class="col-sm-2 col-md-2 header">下单时间<i class="fa fa-sort fa-sort-p"></i></th>
                              <th class="col-sm-2 col-md-2 header">金额<i class="fa fa-sort fa-sort-p"></i></th>
                              <th class="col-sm-4 col-md-4 header">内容<i class="fa fa-sort fa-sort-p"></i></th>
                              <th class="col-sm-2 col-md-2 header">支付状态</th>
                            </tr>
                          </thead>
                          <tbody>
                                
                                  @if($orders_data)
                                  
                                  @foreach($orders_data as $order)
                                  <tr @if($order['status']=='cancel')style="color:#999;" @endif>
                                  <td>
                                      {{{$order["id"]}}}
                                  </td>
                                  <td>
                                      {{{$order["created_at"]}}}
                                  </td>
                                  <td>
                                      {{{money($order["order_count"]/100)}}}
                                  </td>
                                  <td>
                                      {{{$order["content"]}}}
                                  </td>
                                  <td>
                                      {{{$order["status_name"]}}}&nbsp;
                                      <a href="{{URL::to('angel/order/detail/'.$order['id'])}}" class="btn btn-default btn-xs">查看</a>
                                  </td>
                                  </tr>
                                  @endforeach
                                  
                                  @endif
                                
                            </tbody>
                        </table>
                </div>
                
                <footer class="panel-footer">
                      <div class="row">
                        
                        <div class="col-sm-4 text-left">
                          <small class="text-muted inline m-t-sm m-b-sm">共有 {{{$order_count}}} 个订单</small>
                        </div>
                        <div class="col-sm-8 text-xs text-right">
                          {{ $orders->links('pagination.custom') }}
                        </div>
                      </div>
                    </footer>
                
            </section>
        </div>
    </div>
    
@stop
