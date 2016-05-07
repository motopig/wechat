@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
  <section class="panel panel-default">
    <input type="hidden" name="csrf_token" id="csrf_token" value="{{Session::getToken()}}" />
    <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />

    <div class="row wrapper">
      <div class="col-sm-5 m-b-xs">
        <a href="{{URL::to('angel/luckdrawCreate')}}" class="btn btn-success btn-xs">
          添加活动
        </a>
      </div>

      <div class="col-sm-4 m-b-xs"></div>
      <div class="col-sm-3">
        <div class="input-group">
          <input type="text" class="input-sm form-control bolt-search-input" placeholder="请输入活动名称">
          <span class="input-group-btn">
            <button type="button" class="btn btn-sm btn-default bolt-search" bolt-search-url="{{ URL::to('angel/luckdrawSearch') }}" 
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
            <th>活动名称</th>
            <th>参与限制 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>有效期 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>用到奖品</th>
            <th>启用状态</th>
            <th>操作</th>
          </tr>
        </thead>

        <tbody>
           @if (count($luckdraw) > 0)
            @foreach ($luckdraw as $ld)
              <tr>
                <td>{{$ld->name}}</td>
                <td>{{$ld->nums}}</td>
                <td>
                  开始时间: {{$ld->begin_at}}
                  <br />
                  结束时间: {{$ld->end_at}}
                </td>
                <td>{{$ld->prize}}</td>
                <td>
                  @if ($ld->disabled == 'false')
                    <span class="label bg-success m-t-xs">已启用</span>
                  @else
                    <span class="label bg-danger m-t-xs">未启用</span>
                  @endif
                </td>
                <td>
                  <a href="{{URL::to('angel/luckdrawUpdate?id=' . $ld->id)}}" class="btn btn-success btn-xs">
                    编辑
                  </a>

                  <a href="javascript:void(0);" class="btn btn-dark btn-xs ld-del-click" 
                  data-url="{{URL::to('angel/luckdrawDelete')}}" data-val="{{$ld->id}}">
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
          ( 第 {{ $luckdraw->getCurrentPage() }} 页 / 共 {{ $luckdraw->getTotal() }}  条 @if (isset($search)) | <a href="{{ URL::to('angel/luckdraw') }}">离开搜索列表</a> @endif)
        </div>

        <div class="col-sm-4 text-center"></div>
        <div class="col-sm-4 text-right text-center-xs">
          @if (isset($search))
            {{ $luckdraw->appends(array('search'=>$search))->links() }}
          @else
             {{ $luckdraw->links() }}
          @endif
        </div>
      </div>
    </footer>
  </section>

  <script type="text/javascript">
    $('.ld-del-click').click(function () {
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