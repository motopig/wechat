@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')

    <section class="panel panel-default">
    @include('EcdoHulk::layouts.tabs.shakearound')
    <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
    <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
    
    <div class="row wrapper">
      <div class="col-sm-5 m-b-xs">
        <a href="{{URL::to('angel/wechat/shakearound/page/create')}}" class="btn btn-success btn-xs">
          添加页面
        </a>
      </div>

      <div class="col-sm-4 m-b-xs"></div>
      <div class="col-sm-3">
        <div class="input-group">
          <input type="text" class="input-sm form-control bolt-search-input" placeholder="请输入页面主标题">
          <span class="input-group-btn">
            <button type="button" class="btn btn-sm btn-default bolt-search" bolt-search-url="{{ URL::to('angel/wechat/shakearound/sePage') }}" 
            data-toggle="tooltip" data-placement="bottom" data-original-title="搜索">
            <i class="icon-magnifier"></i>
            </button>
          </span>
        </div>
      </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped b-t b-light">
          <thead>
            <tr>
              <th>展示图片 </th>
              <th>页面主标题 </th>
              <th>页面类型 </th>
              <th>微信页面ID <i class="fa fa-sort fa-sort-p"></i></th>
              <th>更新时间 <i class="fa fa-sort fa-sort-p"></i></th>
              <th>操作</th>
            </tr>
          </thead>

          <tbody>
            @if (count($page) > 0)
              @foreach ($page as $p)
                <tr>
                  <td width="150px">
                    <a href="###" class="thumb-sm m-t-xs avatar m-l-xs m-r-sm">
                      <img src="{{asset($p->icon_url)}}">
                    </a>
                    </div>
                  </td>

                  <td>
                    {{$p->title}}
                  </td>

                  <td>
                    {{$p->type_val}}
                  </td>

                  <td>
                    {{$p->page_id}}
                  </td>

                  <td>
                    {{$p->updated_at}}
                  </td>

                  <td>
                    <a href="{{ URL::to('angel/wechat/shakearound/page/update?id='.$p->id) }}" class="btn btn-success btn-xs">
                      编辑
                    </a>

                    <a href="javascript:void(0);" bolt-delete-url="{{ URL::to('angel/wechat/shakearound/page/delete?id='.$p->id) }}" class="btn btn-dark btn-xs bolt-delete">
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
          ( 第 {{ $page->getCurrentPage() }} 页 / 共 {{ $page->getTotal() }}  条 @if (isset($search)) | <a href="{{ URL::to('angel/wechat/shakearound/page') }}">离开搜索列表</a> @endif)
        </div>

        <div class="col-sm-4 text-center"></div>
        <div class="col-sm-4 text-right text-center-xs">
          @if (isset($search))
            {{ $page->appends(array('search'=>$search))->links() }}
          @else
             {{ $page->links() }}
          @endif
        </div>
      </div>
    </footer>
  </section>
@stop
