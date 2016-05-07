@extends('EcdoSpiderMan::layouts.modal.default')

@section('main')
<section class="panel panel-default">
  <!-- 引入表单提交验证信息模版 -->
  <div class="bolt-response-error"></div>
  <div class="bolt-response-success"></div>
  <!-- 引入表单提交验证信息模版 -->

  <div class="panel-body">
    <form class="form-horizontal from-device" method="post" action="{{ URL::to('angel/wechat/shakearound/deviceCreateDis') }}">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
        <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
        
        <div class="form-group">
          <label class="col-sm-2 control-label">数量</label>
          <div class="col-sm-4">
            <input type="text"class="form-control device-quantity" placeholder="数量超过500个，需走人工审核流程">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">申请理由</label>
          <div class="col-sm-4">
            <input type="text" class="form-control device-apply_reason" maxlength="100" placeholder="不超过100个字">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">备注 (选填)</label>
          <div class="col-sm-4">
            <input type="text" class="form-control device-comment" maxlength="15" placeholder="不超过15个字">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">所在门店</label>
          <div class="col-sm-4">
            <select class="form-control m-b device-sid">
              <option value="">请选择</option>
              @if (count($entityshop) > 0)
                @foreach ($entityshop as $k => $v)
                  <option value="{{$v->sid}}">{{$v->business_name}}</option>
                @endforeach
              @endif
            </select>
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
            <button type="button" class="btn btn-success device-click">确认</button>&nbsp;
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
          </div>
        </div>
    </form>
  </div>
</section>

<script src="{{asset('atlas/hell/hulk/js/shakearound.js')}}"></script>
@stop
