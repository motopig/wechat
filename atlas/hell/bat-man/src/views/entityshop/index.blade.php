@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<style type="text/css">
  .es-wc-click {
    color: #459ae9;
  }

  .es-wc-click:hover {
    color: #459ae9;
    text-decoration: underline;
  }
</style>

<section class="panel panel-default es-panel">
    @include('EcdoBatMan::layouts.tabs.entityshop')
    <input type="hidden" name="csrf_token" id="csrf_token" value="{{Session::getToken()}}" />
    <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
    
    <div class="row wrapper">
      <div class="col-sm-5 m-b-xs">
        <a href="{{URL::to('angel/entityshop/crEntityShop')}}" class="btn btn-success btn-xs">
          添加门店
        </a>

        <a href="javascript:void(0);" class="btn btn-default btn-xs boltClick" 
        bolt-url="angel/entityshop/fiEntityShop" bolt-modal="筛选门店" bolt-modal-icon="icon-target">
          筛选
        </a>
      </div>

      <div class="col-sm-4 m-b-xs"></div>
      <div class="col-sm-3">
        <div class="input-group">
          <input type="text" class="input-sm form-control bolt-search-input" placeholder="请输入门店名称">
          <span class="input-group-btn">
            <button type="button" class="btn btn-sm btn-default bolt-search" bolt-search-url="{{ URL::to('angel/entityshop/seEntityShop') }}" 
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
            <th>门店编号 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>门店名</th>
            <th>门店地址</th>
            <th>门店电话 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>更新时间 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>操作</th>
          </tr>
        </thead>

        <tbody>
          @if (count($entityshop) > 0)
            @foreach ($entityshop as $es)
              <tr>
                <td>
                	{{$es->sid}}<br />

                  <span style="color:#4d5b65;font-size:13px;">
                    微信：
                    @if ($es->status != 1)
                    <a href="javascript:void(0);" class="es-wc-click" title="点击同步微信审核" 
                    data-val="{{$es->sid}}" data-url="{{URL::to('angel/wechatEntityShop')}}">
                    @endif

                    @if ($es->status == 0)
                      未同步
                    @elseif ($es->status == 1)
                      审核中
                    @elseif ($es->status == 2)
                      审核通过
                    @elseif ($es->status == 3)
                      审核失败
                    @endif

                    @if ($es->status != 1)
                    </a>
                    @endif
                  </span>
                </td>

                <td>
                  {{$es->business_name}}
                </td>

                <td>
                  {{$es->province}}{{$es->city}}{{$es->district}}
                  <br />{{$es->address}}
                </td>

                <td>
                	{{$es->item->telephone}}
                </td>

                <td>
                  {{$es->updated_at}}
                </td>

                <td>
                  <a href="{{URL::to('angel/entityshop/upEntityShop?id=' . $es->id)}}" class="btn btn-success btn-xs">
                    编辑
                  </a>

                  <a href="javascript:void(0);" data-url="{{ URL::to('angel/entityshop/deEntityShop') }}" data-val="{{$es->sid}}" 
                  class="btn btn-dark btn-xs es-del-click">
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
          ( 第 {{ $entityshop->getCurrentPage() }} 页 / 共 {{ $entityshop->getTotal() }}  条 @if (isset($search)) | <a href="{{ URL::to('angel/entityshop') }}">离开搜索列表</a> @elseif (isset($filter)) | <a href="{{ URL::to('angel/entityshop') }}">离开筛选列表</a> @endif)
        </div>

        <div class="col-sm-4 text-center"></div>
        <div class="col-sm-4 text-right text-center-xs">
          @if (isset($search))
            {{ $entityshop->appends(array('search'=>$search))->links() }}
          @elseif (isset($filter))
             {{ $entityshop->appends(array('filter'=>$filter))->links() }}
          @else
             {{ $entityshop->links() }}
          @endif
        </div>
      </div>
    </footer>
</section>

<script type="text/javascript">
$(document).ready(function() {
  $('.es-panel').on({
    click:function() {
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

      if ($(this).hasClass('es-del-click')) {
        var _this = $(this);

        alertify.confirm("确认删除吗?", function (e) {
           if (e) {
              var data = new FormData();
              data.append('sid', _this.attr('data-val'));
              data.append('csrf_token', '{{Session::getToken()}}');
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
      } else if ($(this).hasClass('es-wc-click')) {
        var _this = $(this);
        
        alertify.confirm("确认同步微信审核操作?", function (e) {
            if (e) {
               var data = new FormData();
               data.append('sid', _this.attr('data-val'));
               data.append('csrf_token', '{{Session::getToken()}}');
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
      }
    }
  }, '.es-del-click, .es-wc-click');
});
</script>
@stop
