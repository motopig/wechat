@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<link href="{{asset('atlas/hell/thor/css/luckdraw.css')}}" rel="stylesheet" />

<section class="panel panel-default">
    <div class="panel-body">
      <form class="form-horizontal form-ld" method="post" action="{{URL::to('angel/luckdrawCreateDis')}}">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{Session::getToken()}}" />
        <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
        <input type="hidden" id="tpl_type" value="{{{$type}}}" />
        <input type="hidden" id="tpl_coupons" value="{{{$coupons}}}" />
        <input type="hidden" id="oid" value="" />

        <h3 class="frm_title">活动基本信息</h3>
        <div class="form-group">
          <label class="col-sm-2 control-label">活动名称</label>
          <div class="col-sm-7">
            <input type="text" class="form-control ld-name">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">开始时间</label>
          <div class="col-sm-3">
            <div class="input-group">
              <input type="text" class="form-control date ld-begin_at" onfocus=this.blur()>
              <span class="input-group-btn">
                <button class="btn btn-default" type="button" onfocus=this.blur()>
                  <i class="fa fa-calendar"></i>
                </button>
              </span>
            </div>
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">结束时间</label>
          <div class="col-sm-3">
            <div class="input-group">
              <input type="text" class="form-control date ld-end_at" onfocus=this.blur()>
              <span class="input-group-btn">
                <button class="btn btn-default" type="button" onfocus=this.blur()>
                  <i class="fa fa-calendar"></i>
                </button>
              </span>
            </div>
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">参与次数</label>
          <div class="col-sm-7">
            <input type="text" class="form-control ld-nums" placeholder="不填此项，则活动每人只可参与1次">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">活动说明 (选填)</label>
          <div class="col-sm-7">
            <textarea class="form-control ld-description" rows="5" style="resize:none;"></textarea>
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="line-top"></div>
        <h3 class="frm_title">活动奖品信息
          <span class="frm_title_dec">每个活动最多可配置 <b>4</b> 件奖品</span>
        </h3>

        <div id="prize-template">
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">未中奖概率</label>
          <div class="col-sm-7">
            <input type="text" class="form-control ld-not_chance" placeholder="不填此项，则概率默认为50%">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">未中奖说明 (选填)</label>
          <div class="col-sm-7">
            <textarea class="form-control parsley-validated ld-not_message" rows="5" style="resize:none;"></textarea>
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">启用状态</label>
          <div class="col-sm-7">
            <label class="radio-inline i-checks">
              <input class="ld-disabled-radio" type="radio" name="disabled" value="false" checked><i></i> 启用
            </label>

            <label class="radio-inline i-checks">
              <input class="ld-disabled-radio" type="radio" name="disabled" value="true"><i></i> 禁用
            </label>
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
            <button type="button" class="btn btn-success ld-click">保存</button>&nbsp;
            <a href="{{ URL::to('angel/luckdraw') }}">
              <button type="button" class="btn btn-default">返回</button>
            </a>
          </div>
        </div>
      </form>
    </div>
</section>

<link href="{{asset('assets/universe/dist/timepicker-addon/jquery-ui-smoothness.css')}}" rel="stylesheet" />
<link href="{{asset('assets/universe/dist/timepicker-addon/jquery-ui-timepicker-addon.css')}}" rel="stylesheet" />
<script src="{{asset('assets/universe/dist/jquery-ui/jquery-ui.js')}}"></script>
<script src="{{asset('assets/universe/dist/timepicker-addon/jquery-ui-timepicker-addon.js')}}"></script>
<script src="{{asset('assets/universe/dist/timepicker-addon/jquery.ui.datepicker-zh-CN.js.js')}}" charset="gb2312"></script>
<script src="{{asset('assets/universe/dist/timepicker-addon/jquery-ui-timepicker-zh-CN.js')}}"></script>
<script src="{{asset('assets/universe/js/handlebars.js')}}"></script>

@include('EcdoThor::luckdraw/handlebars/prize')
<script src="{{asset('atlas/hell/thor/js/luckdraw.js')}}"></script>
@stop
