@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
  <section class="panel panel-default" id="carduse-body">
    @include('EcdoIronMan::layouts.tabs.coupons')
    <input type="hidden" name="csrf_token" id="csrf_token" value="{{Session::getToken()}}" />
    <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />

    <div class="row wrapper">
      <div class="col-sm-5 m-b-xs">
        <a href="javascript:void(0);" class="btn btn-default btn-xs boltClick" 
        bolt-url="angel/carduseFilter" bolt-modal="筛选核销券" bolt-modal-icon="icon-target">
          筛选
        </a>
      </div>
      <div class="col-sm-4 m-b-xs"></div>
      <div class="col-sm-3">
        <div class="input-group">
          <input type="text" class="input-sm form-control bolt-search-input" placeholder="请输入卡券券码">
          <span class="input-group-btn">
            <button type="button" class="btn btn-sm btn-default bolt-search" bolt-search-url="{{URL::to('angel/carduseSearch')}}" 
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
            <th>券号 / 券码</th>
            <th>状态 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>领券人 / 核销员</th>
            <th>领券 / 销券时间 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>操作</th>
          </tr>
        </thead>

        <tbody>
          @if (count($carduse) > 0)
            @foreach ($carduse as $c)
              <tr>
                <td>
                  {{$type[$c->coupons['type']]}} / {{$coupons_type[$c->coupons['coupons_type']]}} <br />
                  券名：{{$c->coupons['title']}}
                </td>
                <td>
                  券号：{{$c->coupons['card_id']}} <br />
                  券码：{{$c->code}}
                </td>
                <td>
                  {{$status[$c->status]}}
                  @if ($c->status == 1 && isset($c->carduse)) <br />
                    <span style="font-size:12px;">({{$carduseType[$c->carduse['type']]}})</span>
                  @endif
                </td>
                <td>
                  领券人：{{isset($c->wechat) ? $c->wechat->name : '-'}} <br />
                  核销员：{{isset($c->verification) ? $c->verification['info']['name'] : '-'}}
                </td>
                <td>
                  领券时间：{{$c->created_at}} <br />
                  销券时间：{{isset($c->carduse) ? $c->carduse['created_at'] : '-'}}
                </td>
                <td>
                  @if ($c->coupons['type'] != 2 && $c->status == 0)
                  <a href="javascript:void(0);" class="btn btn-success btn-xs carduse-verification-click" 
                  data-url="{{URL::to('angel/carduseVerification')}}" data-id="{{$c->id}}">
                    核销
                  </a>
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
          ( 第 {{ $carduse->getCurrentPage() }} 页 / 共 {{ $carduse->getTotal() }}  条 @if (isset($search)) | <a href="{{ URL::to('angel/carduse') }}">离开搜索列表</a> @elseif (isset($filter)) | <a href="{{ URL::to('angel/carduse') }}">离开筛选列表</a> @endif)
        </div>

        <div class="col-sm-2 text-center"></div>
        <div class="col-sm-6 text-right text-center-xs">
          @if (isset($search))
            {{ $carduse->appends(array('search'=>$search))->links() }}
          @elseif (isset($filter))
             {{ $carduse->appends(array('filter'=>$filter))->links() }}
          @else
             {{ $carduse->links() }}
          @endif
        </div>
      </div>
    </footer>
  </section>

  <script src="{{asset('atlas/hell/iron-man/js/carduse.js')}}"></script>
@stop