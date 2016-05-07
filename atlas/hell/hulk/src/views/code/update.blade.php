@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<section class="panel panel-default">
    <div class="panel-body">
      <form class="form-horizontal form-code" method="post" action="{{URL::to('angel/wechat/code/upCodeDis')}}">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
        <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
        <input type="hidden" class="code_inventory" 
        value="@if (isset($code->verification)){{$code->verification['inventory']}}@endif" />
        
        <div class="form-group">
          <label class="col-sm-2 control-label">用途</label>
          <div class="col-sm-4">
            <div class="btn-group m-r">
              <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                <span class="dropdown-label">{{$use[$code->use]}}</span> 
                &nbsp;<span class="caret"></span>
              </button>
              <ul class="dropdown-menu dropdown-select use-select">
                @foreach ($use as $k => $v)
                  <li class="@if ($k == $code->use) active @endif">
                    <input type="radio" name="use">
                    <a href="#" class="@if ($k == $code->use) code-use @endif" data-val="{{$k}}" data-url="{{URL::to('angel/wechat/code/uses')}}">{{$v}}</a>
                  </li>
                @endforeach

                <li class="">
                  <input type="radio" name="use">
                  <a data-val="" href="#">请选择</a>
                </li>
              </ul>
            </div>
          </div>                
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">名称</label>
          <div class="col-sm-5">
            <input type="text" class="form-control code-name" value="{{$code->name}}" 
            maxlength="8" placeholder="最多可输入8个字">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">备注</label>
          <div class="col-sm-5">
            <input type="text" class="form-control code-acton-info" value="{{$code->action_info}}" 
            maxlength="10" placeholder="最多可输入10个字">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div id="code-action">
        </div>

        <div id="code-verification" @if ($code->use != 1) style="display:none;" @endif>
          <div class="form-group">
            <label class="col-sm-2 control-label">核销校验次数</label>
            <div class="col-sm-5">
              <input type="text" class="form-control code-quantity" 
              value="@if (isset($code->verification)){{$code->verification['quantity']}}@endif" 
              placeholder="卡券核销管理员的校验次数限制，默认为20">
              <span class="help-block m-b-none">
                已校验次数：{{isset($code->verification) ? $code->verification['inventory'] : 0}}
              </span>
            </div>
          </div>
          <div class="line line-dashed b-b line-lg pull-in"></div>
        </div>

        <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
            <button type="button" class="btn btn-success code-click" data-id="{{$code->id}}">保存</button>&nbsp;
            <a href="{{ URL::to('angel/wechat/code') }}">
              <button type="button" class="btn btn-default">返回</button>
            </a>
          </div>
        </div>
      </form>
    </div>
</section>

@include('EcdoSpiderMan::layouts.modal.dialog')
<script src="{{asset('atlas/hell/hulk/js/code.js')}}"></script>
@stop
