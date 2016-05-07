@extends('EcdoSpiderMan::layouts.modal.default')

@section('main')
<section class="panel-default">
  <div class="panel-body">
    <form class="form-horizontal" method="post" action="{{ URL::to('angel/entityshop/fiEntityShopDis') }}">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
        <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
        
        <div class="form-group">
          <label class="col-sm-2 control-label">门店ID</label>
          <div class="col-sm-4">
            <input type="text" name="sid" class="form-control">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">门店名称</label>
          <div class="col-sm-4">
            <input type="text" name="name" class="form-control">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">门店地址</label>

          <div class="col-sm-10">
            <div class="row" id="cityselsct" data-url="{{URL::to('/')}}">
              <div class="col-md-2">
                <select name="province" class="form-control m-b prov" style="width:100px;"></select>
              </div>

              <div class="col-md-2">
                <select name="city" class="form-control m-b city" style="width:100px;"></select>
              </div>

              <div class="col-md-2">
                <select name="district" class="form-control m-b dist" style="width:100px;"></select>
              </div>
            </div>

            <input type="text" name="address" class="form-control" style="width:340px;">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
            <button type="submit" class="btn btn-success">确认</button>&nbsp;
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
          </div>
        </div>
    </form>
  </div>
</section>

<script src="{{asset('assets/universe/js/jquery.cityselect.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#cityselsct").citySelect({
      prov:"",
      city:"",
      nodata:"none"
    });
  });
</script>
@stop
