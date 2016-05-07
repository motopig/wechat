@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')

  	<section class="panel panel-default">
    @include('EcdoHulk::layouts.tabs.shakearound')
    <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
    <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
    
    <div class="row wrapper">
      <div class="col-sm-5 m-b-xs">
        <a href="javascript:void(0);" bolt-url="angel/wechat/shakearound/device/create" bolt-modal="申请设备" bolt-modal-icon="icon-plus" class="boltClick btn btn-success btn-xs">
          申请设备
        </a>

        <a data-toggle="tooltip" data-placement="bottom" data-original-title="更新设备信息" href="javascript:void(0);" 
        data-url="{{ URL::to('angel/wechat/shakearound/deviceReload') }}" class="deviceReloadClick btn btn-warning btn-xs">
          刷新设备
        </a>
      </div>

      <div class="col-sm-4 m-b-xs"></div>
      <div class="col-sm-3">
        <div class="input-group">
          <input type="text" class="input-sm form-control bolt-search-input" placeholder="请输入设备ID">
          <span class="input-group-btn">
            <button type="button" class="btn btn-sm btn-default bolt-search" bolt-search-url="{{ URL::to('angel/wechat/shakearound/seDevice') }}" 
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
              <th>设备ID <i class="fa fa-sort fa-sort-p"></i></th>
              <th>设备型号 </th>
              <th>所在门店 </th>
              <th>备注信息 </th>
              <th>配置页面数 <i class="fa fa-sort fa-sort-p"></i></th>
              <th>
                激活状态&nbsp; 
                <b class="badge bg-success" data-toggle="tooltip" data-placement="bottom" 
                    data-original-title="未激活的设备配置页面及硬件后，点击刷新设备按钮重新激活">
                    <span class="icon-question"></span>
                </b>
              </th>
              <th>更新时间 <i class="fa fa-sort fa-sort-p"></i></th>
              <th>操作</th>
            </tr>
          </thead>

          <tbody>
            @if (count($device) > 0)
              @foreach ($device as $d)
                <tr>
                  <td>
                    {{$d->device_id}}
                  </td>

                  <td>
                    {{$d->model}}
                  </td>

                  <td>
                    {{$d->business_name}}
                  </td>

                  <td>
                    {{$d->comment}}
                  </td>

                  <td>
                    {{$d->bind_count}}
                  </td>

                  <td>
                    @if ($d->status == 0)
                      <span class="label bg-danger m-t-xs">未激活</span>
                    @else
                      <span class="label bg-success m-t-xs">已激活</span>
                    @endif
                  </td>

                  <td>
                    {{$d->updated_at}}
                  </td>

                  <td>
                    <a href="javascript:void(0);" bolt-url="angel/wechat/shakearound/shDevice" bolt-data="id={{$d->id}}" bolt-modal="查看设备" bolt-modal-icon="icon-eyeglasses" 
                    class="boltClick btn btn-info btn-xs">
                      查看
                    </a>

                    <a href="{{ URL::to('angel/wechat/shakearound/device/update?id='.$d->id) }}" class="btn btn-success btn-xs">
                      配置
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
          ( 第 {{ $device->getCurrentPage() }} 页 / 共 {{ $device->getTotal() }}  条 @if (isset($search)) | <a href="{{ URL::to('angel/wechat/shakearound/device') }}">离开搜索列表</a> @endif)
        </div>

        <div class="col-sm-4 text-center"></div>
        <div class="col-sm-4 text-right text-center-xs">
          @if (isset($search))
            {{ $device->appends(array('search'=>$search))->links() }}
          @else
             {{ $device->links() }}
          @endif
        </div>
      </div>
    </footer>
  </section>

<script src="{{asset('atlas/hell/hulk/js/shakearound.js')}}"></script>
@stop