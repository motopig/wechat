@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
  <link href="{{asset('atlas/hell/iron-man/css/coupons.css')}}" rel="stylesheet" />

  <section class="panel panel-default">
    @include('EcdoIronMan::layouts.tabs.coupons')
    <div class="row wrapper">
      <div class="col-sm-5 m-b-xs">
        <a href="javascript:void(0);" class="btn btn-success btn-xs 
        @if ($setting != '') coupons-add @else coupons-setting @endif" 
        data-url='{{URL::to('angel/coupons/setting')}}'>
          添加卡券
        </a>

        <a href="javascript:void(0);" class="btn btn-default btn-xs boltClick" 
        bolt-url="angel/couponsFilter" bolt-modal="筛选卡券" bolt-modal-icon="icon-target">
          筛选
        </a>
      </div>

      <div class="col-sm-4 m-b-xs"></div>
      <div class="col-sm-3">
        <div class="input-group">
          <input type="text" class="input-sm form-control bolt-search-input" placeholder="请输入卡券券名">
          <span class="input-group-btn">
            <button type="button" class="btn btn-sm btn-default bolt-search" bolt-search-url="{{URL::to('angel/couponSearch')}}"  
            data-toggle="tooltip" data-placement="bottom" data-original-title="搜索">
            <i class="icon-magnifier"></i>
            </button>
          </span>
        </div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-striped b-t b-light qrcode-list-table">
        <thead>
          <tr>
            <th>类别 / 类型</th>
            <th>券号</th>
            <th>有效期 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>库存 / 已领取 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>状态 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>操作</th>
          </tr>
        </thead>

        <tbody>
          @if (count($coupons) > 0)
            @foreach ($coupons as $c)
              <tr>
                <td>
                  {{$type[$c->type]}} / {{$coupons_type[$c->coupons_type]}} <br />
                  券名：{{$c->title}}
                </td>
                <td>{{$c->card_id}}</td>
                <td>
                  开始时间: {{$c->begin_at}}
                  <br />
                  结束时间: {{$c->end_at}}
                </td>
                <td>
                  库存：{{$c->quantity}} <br />
                  已领取：{{$c->inventory}}
                </td>
                <td>
                  @if (isset($c->time))
                    已过期
                  @else
                    @if ($c->status == 0)
                      审核中
                    @elseif ($c->status == 1)
                      可投放
                    @elseif ($c->status == 2)
                      审核失败
                    @else
                      -
                    @endif
                  @endif
                </td>
                <td>
                  @if ($c->type != 2)
                    <a href="{{URL::to('angel/updateCoupons/' . $c->id . '/' . $c->type . '_' . $c->coupons_type)}}" 
                    class="btn btn-success btn-xs">
                      编辑
                    </a>

                    <a href="javascript:void(0);" class="btn btn-dark btn-xs coupons-del-click" 
                    data-url="{{URL::to('angel/couponsDelete')}}" data-val="{{$c->id}}">
                      删除
                    </a>

                    @if ($c->type == 1 && $c->status == 1 && ! isset($c->time))
                    <a href="###" class="btn btn-info btn-xs coupons-delivery" data-id="{{$c->id}}">
                      投放
                    </a>
                    @endif
                  @endif
                </td>
                </td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>

   <footer class="panel-footer">
      <div class="row">
        <div class="col-sm-4 hidden-xs">
          ( 第 {{ $coupons->getCurrentPage() }} 页 / 共 {{ $coupons->getTotal() }}  条 @if (isset($search)) | <a href="{{ URL::to('angel/coupons') }}">离开搜索列表</a> @elseif (isset($filter)) | <a href="{{ URL::to('angel/coupons') }}">离开筛选列表</a> @endif)
        </div>

        <div class="col-sm-4 text-center"></div>
        <div class="col-sm-4 text-right text-center-xs">
          @if (isset($search))
            {{ $coupons->appends(array('search'=>$search))->links() }}
          @elseif (isset($filter))
             {{ $coupons->appends(array('filter'=>$filter))->links() }}
          @else
             {{ $coupons->links() }}
          @endif
        </div>
      </div>
    </footer>
  </section>

  <div class="modal fade bs-example-modal-xs" id="couponModal" tabindex="-1" role="dialog" 
  aria-labelledby="couponLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xs">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="couponModalLabel"><i class="icon-plus"></i>&nbsp;添加卡券</h4>
        </div>
        <div class="modal-body" id="couponModalBody">
          
          <section class="panel-default">
           <div class="panel-body">
              <form class="form-horizontal from-add-coupons" method="post" action="{{URL::to('angel/couponsType')}}">
                  <input type="hidden" name="csrf_token" id="csrf_token" value="{{Session::getToken()}}" />
                  <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />

                  <div class="form-group">
                    <label class="col-sm-2 control-label">卡券类别</label>
                    <div class="col-sm-4">
                      <div class="btn-group m-r">
                        <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                          <span class="dropdown-label">请选择</span> 
                          &nbsp;<span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-select type-select">
                          @foreach ($type as $k => $v)
                            @if ($k != 2)
                              <li class="">
                                <input type="radio" name="type">
                                <a href="#" data-val="{{$k}}">{{$v}}</a>
                              </li>
                            @endif
                          @endforeach

                          <!-- 暂时只能添加云号券 - no -->
                          <!-- <li class="">
                              <input type="radio" name="type">
                              <a href="#" data-val="0">云号</a>
                          </li> -->

                          <li class="active">
                            <input type="radio" name="type">
                            <a data-val="" href="#">请选择</a>
                          </li>
                        </ul>
                      </div>
                    </div>                
                  </div>
                  <div class="line line-dashed b-b line-lg pull-in"></div>

                  <div class="form-group">
                      <label class="col-sm-2 control-label">卡券类型</label>
                      <div class="col-sm-10">
                        @foreach ($coupons_type as $k => $v)
                          <div class="radio i-checks">
                            <label>
                              <input type="radio" name="coupons_type" value="{{$k}}">
                              <i></i>
                              {{$v}} <span class="frm_tips">{{$coupons_type_notice[$k]}}</span>
                            </label>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  <div class="line line-dashed b-b line-lg pull-in"></div>

                  <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                      <button type="button" class="btn btn-success add-coupons">确认</button>&nbsp;
                      <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                  </div>
              </form>
            </div>
          </section>

        </div>
      </div>
    </div>
  </div>

  <div class="modal fade bs-example-modal-xs" id="deliveryModal" tabindex="-1" role="dialog" 
  aria-labelledby="couponLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xs">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="couponModalLabel"><i class="icon-plus"></i>&nbsp;投放卡券</h4>
        </div>
        <div class="modal-body" id="couponModalBody">
          <section class="panel-default">
           <div class="panel-body">
              <form class="form-horizontal from-delivery" method="post" action="{{URL::to('angel/couponsDelivery')}}">
                  <input type="hidden" id="delivery_id" />
                  
                  <div class="form-group">
                      <label class="col-sm-2 control-label">投放类型</label>
                      <div class="col-sm-10">
                        @foreach ($delivery_type as $k => $v)
                          <div class="radio i-checks">
                            <label>
                              <input type="radio" name="delivery" value="{{$k}}">
                              <i></i>
                              {{$v}} <span class="frm_tips">{{$delivery_notice[$k]}}</span>
                            </label>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  <div class="line line-dashed b-b line-lg pull-in"></div>

                  <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                      <button type="button" class="btn btn-success add-delivery">确认</button>&nbsp;
                      <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                  </div>
              </form>
            </div>
          </section>

        </div>
      </div>
    </div>
  </div>

  <script src="{{asset('atlas/hell/iron-man/js/coupons.js')}}"></script>
  <script type="text/javascript">
    $('.coupons-del-click').click(function () {
      var _this = $(this);
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

      alertify.confirm("确认删除吗?", function (e) {
         if (e) {
            var data = new FormData();
            data.append('id', _this.attr('data-val'));
            data.append('csrf_token', $('#csrf_token').val());
            data.append('csrf_guid', $('#csrf_guid').val());
            
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
    });
  </script>
@stop