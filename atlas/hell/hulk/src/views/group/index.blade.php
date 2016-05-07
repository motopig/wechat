@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<!-- <section class="scrollable padder-lg w-f-md" id="bjax-target">
  <h4 class="font-thin m-b"></h4> -->

  <section class="panel panel-default">
    <!-- <header class="panel-heading">
    	<i class="icon-bubbles"></i> &nbsp;
      微信组别
    </header> -->

    <div class="row wrapper">
      <div class="col-sm-5 m-b-xs">
        <!-- <select class="input-sm form-control input-s-sm inline v-middle">
          <option value="0">添加</option>
          <option value="1">删除</option>
          <option value="2">导出</option>
          <option value="3">导入</option>
          <option value="3">筛选</option>
        </select>

        <button class="btn btn-sm btn-success">
        	<i class="icon-mouse" title="执行"></i>
        </button> -->

        <a href="javascript:void(0);" bolt-url="angel/wechat/group/crGroup" bolt-modal="创建分组" bolt-modal-icon="icon-plus" class="boltClick btn btn-success btn-xs">
          新建分组
        </a>

        <a href="javascript:void(0);" class="btn btn-default btn-xs boltClick" bolt-url="angel/wechat/group/fiGroup" bolt-modal="筛选分组" bolt-modal-icon="icon-target">
          筛选
        </a>

        <a href="javascript:void(0);" class="btn btn-default btn-xs boltClick" bolt-url="angel/wechat/group/imGroup" bolt-modal="导入分组" bolt-modal-icon="icon-cloud-upload">
          导入
        </a>

        <a href="javascript:void(0);" class="btn btn-default btn-xs bolt-export" bolt-export-url="{{ URL::to('angel/wechat/group/exGroup') }}">
          导出
        </a>
        
        <a href="javascript:void(0);" class="btn btn-dark btn-xs bolt-drop" bolt-drop-url="{{ URL::to('angel/wechat/group/drGroup') }}">
          批量删除
        </a>

        <!-- <a data-toggle="tooltip" data-placement="bottom" data-original-title="同步微信" href="javascript:void(0);" 
        class="btn btn-warning btn-xs">
          <i class="icon-mouse"></i>
        </a> -->
      </div>

      <div class="col-sm-4 m-b-xs"></div>
      <div class="col-sm-3">
        <div class="input-group">
          <input type="text" class="input-sm form-control bolt-search-input" placeholder="请输入分组名称">
          <span class="input-group-btn">
            <button type="button" class="btn btn-sm btn-default bolt-search" bolt-search-url="{{ URL::to('angel/wechat/group/seGroup') }}" 
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
            <th>
              <a class="all-checkbox" href='javascript:void(0);'>
                <i class="fa fa-square-o"></i>
              </a>
            </th>
            <th>分组名称 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>分组ID</th>
            <th>组内人数 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>更新时间 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>操作</th>
          </tr>
        </thead>

        <tbody>
          @if (count($group) > 0)
            @foreach ($group as $g)
              <tr>
                <td>
                  <input class="drop-checkbox" type="checkbox" name="group_id" value="{{$g->id}}">
                </td>

                <td>
                  {{$g->name}}
                </td>

                <td>
                  {{$g->wechat_group_id}}
                </td>

                <td>
                  {{$g->count}}
                </td>

                <td>
                  {{$g->updated_at}}
                </td>

                <td>
                  <a href="javascript:void(0);" bolt-url="angel/wechat/group/shGroup" bolt-data="group_id={{$g->id}}" bolt-modal="查看分组" bolt-modal-icon="icon-eyeglasses" 
                  class="boltClick btn btn-info btn-xs">
                    查看
                  </a>

                  <a href="javascript:void(0);" bolt-url="angel/wechat/group/upGroup" bolt-data="group_id={{$g->id}}" bolt-modal="编辑分组" bolt-modal-icon="icon-note" 
                  class="boltClick btn btn-success btn-xs">
                    编辑
                  </a>

                  <a href="javascript:void(0);" bolt-delete-url="{{ URL::to('angel/wechat/group/deGroup?group_id='.$g->id) }}" class="btn btn-dark btn-xs bolt-delete">
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
          ( 第 {{ $group->getCurrentPage() }} 页 / 共 {{ $group->getTotal() }}  条 @if (isset($search)) | <a href="{{ URL::to('angel/wechat/group') }}">离开搜索列表</a> @elseif (isset($filter)) | <a href="{{ URL::to('angel/wechat/group') }}">离开筛选列表</a> @endif)
        </div>

        <div class="col-sm-4 text-center"></div>
        <div class="col-sm-4 text-right text-center-xs">
          @if (isset($search))
            {{ $group->appends(array('search'=>$search))->links() }}
          @elseif (isset($filter))
             {{ $group->appends(array('filter'=>$filter))->links() }}
          @else
             {{ $group->links() }}
          @endif
        </div>
      </div>
    </footer>
  </section>

<!-- </section> -->
@stop