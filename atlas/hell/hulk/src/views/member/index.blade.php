@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<section class="panel panel-default">
    <div class="row wrapper">
      <div class="col-sm-5 m-b-xs">
        <a href="javascript:void(0);" class="btn btn-default btn-xs boltClick" bolt-url="angel/wechat/member/fiMember" bolt-modal="筛选会员" bolt-modal-icon="icon-target">
          筛选
        </a>

        <a href="javascript:void(0);" class="btn btn-warning btn-xs">
          绑定分组
        </a>
      </div>

      <div class="col-sm-4 m-b-xs"></div>
      <div class="col-sm-3">
        <div class="input-group">
          <input type="text" class="input-sm form-control bolt-search-input" placeholder="请输入昵称">
          <span class="input-group-btn">
            <button type="button" class="btn btn-sm btn-default bolt-search" bolt-search-url="{{ URL::to('angel/wechat/member/seMember') }}" 
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
            <th>性别 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>所属组</th>
            <th>关注状态 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>关注时间 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>操作</th>
          </tr>
        </thead>

        <tbody>
          @if (count($member) > 0)
            @foreach ($member as $m)
              <tr>
                <td>
                  <input class="drop-checkbox" type="checkbox" name="member_id" value="{{$m->wechat_member_id}}">

                  <a href="###" class="thumb-xs m-t-xs avatar m-l-xs m-r-sm">
                  	<img src="{{$m->head}}">
                  </a>

                  {{$m->name}}
                </td>

                <td>
                	@if ($m->gender == 'male')
                  		男
                  	@elseif ($m->gender == 'female')
                  		女
                  	@else
                  		未知
                  	@endif
                </td>

                <td>
                  {{$m->group_name}}
                </td>

                <td>
                	@if ($m->concern == 'follow')
                  		<span class="badge badge-empty">已关注</span>
                  	@else
                  		<span class="badge bg-default">已逃离</span>
                  	@endif
                </td>

                <td>
                  {{$m->concern_time}}
                </td>

                <td>
                  <a href="javascript:void(0);" bolt-url="angel/wechat/member/shMember" bolt-data="member_id={{$m->wechat_member_id}}" bolt-modal="查看会员" bolt-modal-icon="icon-eyeglasses" 
                  class="boltClick btn btn-info btn-xs">
                    查看
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
          ( 第 {{ $member->getCurrentPage() }} 页 / 共 {{ $member->getTotal() }}  条 @if (isset($search)) | <a href="{{ URL::to('angel/wechat/member') }}">离开搜索列表</a> @elseif (isset($filter)) | <a href="{{ URL::to('angel/wechat/member') }}">离开筛选列表</a> @endif)
        </div>

        <div class="col-sm-4 text-center"></div>
        <div class="col-sm-8 text-right text-center-xs">
          @if (isset($search))
            {{ $member->appends(array('search'=>$search))->links() }}
          @elseif (isset($filter))
             {{ $member->appends(array('filter'=>$filter))->links() }}
          @else
             {{ $member->links() }}
          @endif
        </div>
      </div>
    </footer>
</section>
@stop