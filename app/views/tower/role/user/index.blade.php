@extends('EcdoSpiderMan::layouts.desktop.default')
@section('title')
用户列表
@stop
@section('styles')
<style>
<!--
    .table {
        table-layout: fixed;
    }
    
    .table td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
-->
</style>
@stop
@section('main')

  <section class="panel panel-default">
      <header class="panel-heading"><i class="icon-user"></i>&nbsp;客服管理</header>
    <div class="row wrapper">
        <div class="col-sm-5">
          <button type="button" bolt-url="angel/role/users/add" bolt-modal="创建角色" bolt-modal-icon="icon-plus" class="boltClick btn btn-success btn-xs">
            增加客服
          </button>
        
        </div>
    </div>
    
    <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th class="col-sm-3 col-md-2">邮箱账号<i class="fa fa-sort fa-sort-p"></i></th>
            <th class="col-sm-2 col-md-2">姓名昵称<i class="fa fa-sort fa-sort-p"></i></th>
            <th class="col-sm-4 col-md-4">角色<i class="fa"></i></th>
            <th class="col-sm-2 col-md-2">更新时间<i class="fa fa-sort fa-sort-p"></i></th>
            <th class="col-sm-2 col-md-2">操作</th>
          </tr>
        </thead>
        <tbody>
          @if (! empty($users))
            @foreach ($users as $user)
              <tr>
                <td>
                    {{$user['email']}}
                </td>
                <td>
                    {{$user['name'] or ''}}
                </td>
                <td>
                    {{$user['roles'] or ''}}
                </td>
                <td>
                    {{$user['updated_at'] or ''}}
                </td>
                <td>
                  <a data-toggle="tooltip" data-placement="bottom" data-original-title="编辑" href="###" bolt-url="angel/role/users/edit" bolt-data="id={{$user['id']}}" bolt-modal="编辑用户角色" bolt-modal-icon="icon-note" class="boltClick btn btn-success btn-xs">
                    <i class="icon-note"></i>
                  </a>
                </td>
              </tr>
             @endforeach
          @else
            <tr>
              <td colspan="5">暂无用户角色信息</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>
   <footer class="panel-footer">
      <div class="row">
        <div class="col-sm-4 hidden-xs">
          ( 第 {{$page['curPage']}} 页 / 共 {{$page['ttlPage']}} 页 )
        </div>
        <div class="col-sm-4 text-center"></div>
        <div class="col-sm-4 text-right text-center-xs">
        {{$page['links']}}
        </div>
      </div>
    </footer>
  </section>
@stop
