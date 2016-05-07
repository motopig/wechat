@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
  <section class="panel panel-default">
    @include('EcdoIronMan::layouts.tabs.coupons')
    <input type="hidden" name="csrf_token" id="csrf_token" value="{{Session::getToken()}}" />
    <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
    
    <div class="row wrapper">
      <div class="col-sm-5 m-b-xs">
        <a href="{{ URL::to('angel/verificationCreate') }}" class="btn btn-success btn-xs">
          添加核销员
        </a>

        <a href="javascript:void(0);" class="btn btn-default btn-xs boltClick" 
        bolt-url="angel/verificationFilter" bolt-modal="筛选审核员" bolt-modal-icon="icon-target">
          筛选
        </a>
      </div>
      <div class="col-sm-4 m-b-xs"></div>
      <div class="col-sm-3">
        <div class="input-group">
          <input type="text" class="input-sm form-control bolt-search-input" placeholder="请输入核销员姓名">
          <span class="input-group-btn">
            <button type="button" class="btn btn-sm btn-default bolt-search" 
            bolt-search-url="{{URL::to('angel/verificationSearch')}}" 
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
            <th>头像 / 昵称</th>
            <th>姓名</th>
            <th>电话</th>
            <th>适用门店</th>
            <th>状态 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>注册时间 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>操作</th>
          </tr>
        </thead>

        <tbody>
          @if (count($verification) > 0)
            @foreach ($verification as $v)
              <tr>
                <td>
                  <a href="###" class="thumb-xs m-t-xs avatar m-l-xs m-r-sm">
                    <img src="{{$v->wechat->head}}">
                  </a>

                  {{$v->wechat->name}}
                </td>
                <td>{{$v->info['name']}}</td>
                <td>{{$v->info['mobile']}}</td>
                <td>{{$v->store_count}}</td>
                <td>{{$status[$v->status]}}</td>
                <td>{{$v->created_at}}</td>
                <td>
                  <a href="{{URL::to('angel/verificationUpdate?id=' . $v->id)}}" 
                    class="btn btn-success btn-xs">
                    编辑
                  </a>

                  <a href="javascript:void(0);" class="btn btn-dark btn-xs verification-del-click" 
                  data-url="{{URL::to('angel/verificationDelete')}}" data-val="{{$v->id}}">
                    删除
                  </a>
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
          ( 第 {{ $verification->getCurrentPage() }} 页 / 共 {{ $verification->getTotal() }}  条 @if (isset($search)) | <a href="{{ URL::to('angel/verification') }}">离开搜索列表</a> @elseif (isset($filter)) | <a href="{{ URL::to('angel/verification') }}">离开筛选列表</a> @endif)
        </div>

        <div class="col-sm-4 text-center"></div>
        <div class="col-sm-4 text-right text-center-xs">
          @if (isset($search))
            {{ $verification->appends(array('search'=>$search))->links() }}
          @elseif (isset($filter))
             {{ $verification->appends(array('filter'=>$filter))->links() }}
          @else
             {{ $verification->links() }}
          @endif
        </div>
      </div>
    </footer>
  </section>

  <script type="text/javascript">
    $('.verification-del-click').click(function () {
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