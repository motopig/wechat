@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<section class="panel panel-default" id="verification-body">
    <div class="panel-body">
      <form class="form-horizontal form-verification" method="post" action="{{URL::to('angel/verificationCreateDis')}}">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
        <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
        <input type="hidden" class="verification-id" />

        <div class="form-group">
          <label class="col-sm-2 control-label">openid</label>
          <div class="col-sm-5">
            <input type="text" class="form-control verification-openid" placeholder="核销员微信openid">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">核销员名称</label>
          <div class="col-sm-5">
            <input type="text" class="form-control verification-name" placeholder="核销员真实姓名">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">核销员电话</label>
          <div class="col-sm-5">
            <input type="text" class="form-control verification-mobile" placeholder="核销员联系方式">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
            <button type="button" class="btn btn-success verification-subPost" data-id="">保存</button>&nbsp;
            <a href="{{ URL::to('angel/verification') }}">
              <button type="button" class="btn btn-default">返回</button>
            </a>
          </div>
        </div>
      </form>
    </div>
</section>

@include('EcdoSpiderMan::layouts.modal.dialog')
<script src="{{asset('atlas/hell/iron-man/js/verification.js')}}"></script>
@stop
