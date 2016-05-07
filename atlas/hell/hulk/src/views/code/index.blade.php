@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
  <link href="{{asset('atlas/hell/hulk/css/code.css')}}" rel="stylesheet" />

  <section class="panel panel-default">
    <div class="row wrapper">
      <div class="col-sm-5 m-b-xs">
        <a href="{{ URL::to('angel/wechat/code/crCode') }}" class="btn btn-success btn-xs">
          添加二维码
        </a>
      </div>

      <div class="col-sm-4 m-b-xs"></div>
      <div class="col-sm-3">
        <div class="input-group">
          <input type="text" class="input-sm form-control bolt-search-input" placeholder="请输入二维码名称">
          <span class="input-group-btn">
            <button type="button" class="btn btn-sm btn-default bolt-search" bolt-search-url="{{ URL::to('angel/wechat/code/seCode') }}" 
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
            <th>推广二维码</th>
            <th>用途</th>
            <th>名称</th>
            <th>备注</th>
            <th>更新时间 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>操作</th>
          </tr>
        </thead>

        <tbody>
          @if (count($code) > 0)
            @foreach ($code as $c)
              <tr>
                <td class="t-col-1">
                  <div id="round">
                    <img class="qrcode-img" src="{{asset($c->url)}}">
                  </div>
                </td>

                <td>
                  {{$use[$c->use]}}<br />
                  @if ($c->use == 1)
                    限校验次数：{{isset($c->verification) ? $c->verification['quantity'] : ''}}<br />
                    已校验次数：{{isset($c->verification) ? $c->verification['inventory'] : 0}}
                  @endif
                </td>

                <td>
                  {{$c->name}}
                </td>

                <td>
                  {{$c->action_info}}
                </td>

                <td>
                  {{$c->updated_at}}
                </td>

                <td>
                  <a data-toggle="tooltip" data-placement="bottom" data-original-title="下载二维码" 
                  href="{{asset($c->url)}}" download="{{$c->name}}" class="btn btn-info btn-xs code-download">
                    下载
                  </a>

                  <a href="{{asset($c->url)}}" download="{{$c->name}}" class="code-download-href"></a>

                  <a href="{{ URL::to('angel/wechat/code/upCode?id=' . $c->id) }}" class="btn btn-success btn-xs">
                    编辑
                  </a>

                  <a href="javascript:void(0);" bolt-delete-url="{{ URL::to('angel/wechat/code/deCode?id=' . $c->id) }}" class="btn btn-dark btn-xs bolt-delete">
                    删除
                  </a>
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
          ( 第 {{ $code->getCurrentPage() }} 页 / 共 {{ $code->getTotal() }}  条 @if (isset($search)) | <a href="{{ URL::to('angel/wechat/code') }}">离开搜索列表</a> @endif)
        </div>

        <div class="col-sm-4 text-center"></div>
        <div class="col-sm-4 text-right text-center-xs">
          @if (isset($search))
            {{ $code->appends(array('search'=>$search))->links() }}
          @elseif (isset($filter))
             {{ $code->appends(array('filter'=>$filter))->links() }}
          @else
             {{ $code->links() }}
          @endif
        </div>
      </div>
    </footer>
  </section>
@stop