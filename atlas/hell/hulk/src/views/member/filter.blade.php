@extends('EcdoSpiderMan::layouts.modal.default')

@section('main')
<section class="panel-default">
  <div class="panel-body">
    <form class="form-horizontal" method="post" action="{{ URL::to('angel/wechat/member/fiMemberDis') }}">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
        <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
        
        <div class="form-group">
          <label class="col-sm-2 control-label">昵称</label>
          <div class="col-sm-4">
            <input type="text" name="name" class="form-control">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">所属组</label>
          <div class="col-sm-4">
            <div class="btn-group m-r">
              <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                <span class="dropdown-label">请选择</span> 
                &nbsp;<span class="caret"></span>
              </button>
              @if (count($group) > 0)
              <ul class="dropdown-menu dropdown-select">
                @foreach ($group as $g)
                  <li class=""><input type="radio" name="group_id" value="{{$g['id']}}">
                    <a href="#">{{$g['name']}}</a>
                  </li>
                @endforeach
              </ul>
              @endif
            </div>
          </div>                
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">性别</label>
          <div class="col-sm-4">
            <div class="radio i-checks">
              <label>
                <input type="radio" name="gender" value="male">
                <i></i>
                男
              </label>
            </div>
            <div class="radio i-checks">
              <label>
                <input type="radio" name="gender" value="female">
                <i></i>
                女
              </label>
            </div>
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">关注状态</label>
          <div class="col-sm-4">
            <div class="radio i-checks">
              <label>
                <input type="radio" name="concern" value="follow">
                <i></i>
                已关注
              </label>
            </div>
            <div class="radio i-checks">
              <label>
                <input type="radio" name="concern" value="unfollow">
                <i></i>
                未关注
              </label>
            </div>
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
@stop