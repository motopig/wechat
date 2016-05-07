@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<div class="col-xs-12 col-sm-10">

<section class="panel panel-default">
    <header class="apanel-heading">
      		<i class="fa fa-cog"></i>&nbsp;云号设置
          </header>
    <div class="apanel-body">
      <form class="form-horizontal tower-form" method="post" action="{{URL::to('angel/towerConfigDis')}}">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
        <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />

        <div class="form-group">
          <label class="col-sm-2 control-label">云号名称</label>
          <div class="col-sm-5">
            <input type="text" class="form-control tower-name" placeholder="请输入云号名称" value="{{$tower->name}}">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">主营类目</label>
          <div class="col-sm-5">
              <div class="btn-group m-r">
                <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                     <span class="dropdown-label">
                      @if ($tower->business == 'other')
                        其他
                      @else
                        {{$business[$tower->business]}}
                      @endif
                    </span>
                    &nbsp;<span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu dropdown-select tower-business-select">
                      @foreach ($business as $k => $v)
                        <li @if ($tower->business == $k && $tower->business != 'other') class="active" @endif>
                          <input type="radio" name="business" value="{{$k}}">
                          <a href="###" data-val="{{$k}}">{{$v}}</a>
                        </li>
                      @endforeach

                      <li @if ($tower->business == 'other') class="active" @endif>
                        <input type="radio" name="business" value="other">
                        <a href="###" data-val="other">其他</a>
                      </li>
                  </ul>
              </div>

              <div class="btn-group m-r business-input" @if ($tower->business != 'other') style="display:none;" @endif>
                <input type="text" name="business_other" value="{{$tower->business_other}}" class="form-control tower-business-text" placeholder="请输入自定义类目">
              </div>
            </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group m-t-lg">
          <div class="col-sm-4 col-sm-offset-2">
            <button type="button" class="btn btn-success tower-click" data-id="{{$tower->id}}">保存设置</button>&nbsp;
          </div>
        </div>
      </form>
    </div>
</section>    
    
</div>


<script src="{{{ asset('atlas/hell/spider-man/js/yunhao.js') }}}"></script>
@stop
