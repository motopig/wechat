@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<link href="{{asset('atlas/hell/thor/css/luckdraw.css')}}" rel="stylesheet" />

<section class="panel panel-default">
    <div class="panel-body">
      <form class="form-horizontal form-ld" method="post" action="{{URL::to('angel/luckdrawUpdateDis')}}">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{Session::getToken()}}" />
        <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
        <input type="hidden" id="tpl_type" value="{{{$type}}}" />
        <input type="hidden" id="tpl_coupons" value="{{{$coupons}}}" />
        <input type="hidden" id="oid" value="{{$id}}" />

        <h3 class="frm_title">活动基本信息</h3>
        <div class="form-group">
          <label class="col-sm-2 control-label">活动名称</label>
          <div class="col-sm-7">
            <input type="text" class="form-control ld-name" value="{{$luckdraw['name']}}">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">开始时间</label>
          <div class="col-sm-3">
            <div class="input-group">
              <input type="text" class="form-control date ld-begin_at" value="{{$luckdraw['begin_at']}}" onfocus=this.blur()>
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
              <input type="text" class="form-control date ld-end_at" value="{{$luckdraw['end_at']}}" onfocus=this.blur()>
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
            <input type="text" class="form-control ld-nums" value="{{$luckdraw['nums']}}" placeholder="不填此项，则活动每人只可参与1次">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">活动说明 (选填)</label>
          <div class="col-sm-7">
            <textarea class="form-control ld-description" rows="5" style="resize:none;">{{$luckdraw['description']}}</textarea>
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="line-top"></div>
        <h3 class="frm_title">活动奖品信息
          <span class="frm_title_dec">每个活动最多可配置 <b>4</b> 件奖品</span>
        </h3>

        <div id="prize-template">
          @foreach ($luckdraw['prize'] as $k => $v)
            <section class="panel panel-default portlet-item prize-float">
              <header class="panel-heading">
                <ul class="nav nav-pills pull-right">
                  <li>
                    <a href="javascript:void(0);" class="@if ($k == 0) add-prize @else del-prize @endif" data-number="{{$v['id']}}">
                      <span class="label @if ($k == 0) label-success @else label-default @endif">@if ($k == 0) 添加 @else 删除 @endif</span>
                    </a>
                  </li>

                  <li>
                    <a href="#" class="panel-toggle text-muted">
                      <i class="fa fa-caret-down text"></i>
                      <i class="fa fa-caret-up text-active"></i>
                    </a>
                  </li>
                </ul>
                奖品设置
              </header>

              <section class="prize-panel panel-body collapse" data-pid="{{$v['id']}}">
                <div class="form-group">
                  <label class="col-sm-2 control-label">奖品类型</label>
                  <div class="col-sm-7">
                    <label class="radio-inline i-checks">
                      @foreach ($_type as $ks => $vs)
                        <input class="ld-prize-radio" type="radio" name="type[{{$v['id']}}]" value="{{$vs['key']}}" @if ($vs['key'] == $v['type']) checked @endif><i></i> {{$vs['value']}}
                      @endforeach
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">选择奖品</label>
                  <div class="col-sm-7">
                    <select name="content[{{$v['id']}}]" class="form-control m-b ld-prize-select" style="width:150px;">
                      <option value="">请选择</option>
                      @foreach ($_coupons as $ks => $vs)
                        <option value="{{$vs['id']}}" @if ($vs['id'] == $v['content']) selected @endif>{{$vs['title']}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">中奖概率</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control ld-chance" name="chance[{{$v['id']}}]" value="{{$v['chance']}}" placeholder="不填此项，则概率默认为0">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">奖品数量</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control ld-quantity" name="quantity[{{$v['id']}}]" value="{{$v['quantity']}}" placeholder="不填此项，则奖品默认为1件">
                  </div>
                </div>
              </section>
            </section>
          @endforeach
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">未中奖概率</label>
          <div class="col-sm-7">
            <input type="text" class="form-control ld-not_chance" value="{{$luckdraw['not_chance']}}" placeholder="不填此项，则概率默认为50%">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">未中奖说明 (选填)</label>
          <div class="col-sm-7">
            <textarea class="form-control parsley-validated ld-not_message" rows="5" style="resize:none;">{{$luckdraw['not_message']}}</textarea>
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">启用状态</label>
          <div class="col-sm-7">
            <label class="radio-inline i-checks">
              <input class="ld-disabled-radio" type="radio" name="disabled" value="false" @if ($luckdraw['disabled'] == 'false') checked @endif><i></i> 启用
            </label>

            <label class="radio-inline i-checks">
              <input class="ld-disabled-radio" type="radio" name="disabled" value="true" @if ($luckdraw['disabled'] == 'true') checked @endif><i></i> 禁用
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
