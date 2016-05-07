@extends('EcdoSpiderMan::layouts.site.default')

@section('main')
<section class="panel panel-default portlet-item navbar-fixed-top-xs">
  <header class="panel-heading">
      <i class="fa fa-credit-card"></i>&nbsp;
        核销记录

        <span class="text-muted m-l-sm pull-right">
            <a href="{{URL::to(Session::get('guid') . '/card/verification/' . $data . '/true')}}" data-dismiss="alert" class="btn btn-default btn-xs">
              <i class="fa fa-home text-muted"></i>&nbsp; 首页
            </a>
        </span>
    </header>
</section>

<section class="scrollable" id="bjax-target">
  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="input-group" style="margin-bottom:30px;margin-top:20px;">
      <input type="text" class="input-sm form-control bolt-search-input" placeholder="请输入卡券券码">
      <span class="input-group-btn">
        <button type="button" class="btn btn-sm btn-default bolt-search" 
        bolt-search-url="{{URL::to(Session::get('guid') . '/card/carduseLogSearch')}}" >
        <i class="icon-magnifier"></i>
        </button>
      </span>
    </div>

    @if (count($carduse) > 0)
      <div class="panel-group m-b" id="accordion2">
      @foreach ($carduse as $c)
        <div class="panel panel-default">
          <div class="panel-heading">
            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" 
            href="#collapse_{{$c->id}}">
              {{{$c->code}}}
            </a>

            <a href="{{URL::to(Session::get('guid') . '/card/codeInfo/' . $c->code . '/carduseLog')}}" style="float:right;">
              <i class="fa fa-angle-double-right"></i>
            </a>
          </div>

          <div id="collapse_{{$c->id}}" class="panel-collapse collapse" style="height: auto;">
            <div class="panel-body text-sm">
              <article class="media">
                <div class="media-body">  
                  <small class="block m-t-xs">
                    券号：{{{$c->card_id}}}
                  </small>                      
                  <small class="block m-t-xs">
                    券码：{{{$c->code}}}
                  </small>
                  <small class="block m-t-xs">
                    券名：{{$c->coupons['title']}}
                  </small>
                  <small class="block m-t-xs">
                    券型：{{$type[$c->coupons['type']]}} / {{$coupons_type[$c->coupons['coupons_type']]}}
                  </small>
                  <small class="block m-t-xs">
                    状态：{{$status[$c->info['status']]}}
                  </small>
                  <small class="block m-t-xs">
                    领券人：{{$c->wechat->name}}
                  </small>
                  <small class="block m-t-xs">
                    核销员：{{$verification->info['name']}}
                  </small>
                  <small class="block m-t-xs">
                    领券时间：{{$c->info['created_at']}}
                  </small>
                  <small class="block m-t-xs">
                    销券时间：{{$c->created_at}}
                  </small>
                  <small class="block m-t-xs">
                    {{$c->coupons['favourable']}}：
                    @if ($c->coupons['coupons_type'] == 'DISCOUNT')
                      打{{$c->coupons['coupons_setting']}}折
                    @elseif ($c->coupons['coupons_type'] == 'CASH')
                      减{{$c->coupons['coupons_setting']}}元
                    @else
                      {{$c->coupons['default_detail']}}
                    @endif
                  </small>
                </div>
              </article>
            </div>
          </div>
        </div>
      @endforeach
      </div>
    @endif

   <div class="row">
      <div class="col-sm-4">
        ( 第 {{ $carduse->getCurrentPage() }} 页 / 共 {{ $carduse->getTotal() }}  条 @if (isset($search)) | <a href="{{ URL::to(Session::get('guid') . '/card/carduseLogSearch') }}">离开搜索列表</a> @endif)
      </div>

      <div class="col-sm-4 text-center"></div>
      <div class="col-sm-4 text-right text-center-xs">
        @if (isset($search))
          {{ $carduse->appends(array('search'=>$search))->links() }}
        @else
           {{ $carduse->links() }}
        @endif
      </div>
    </div>
  </div>
</section>
@stop
