@extends('EcdoSpiderMan::layouts.sign.default')

@section('main')
<section id="content" class="m-t-lg wrapper-md animated fadeInUp">    
  <div class="container aside-xl">
    <a class="navbar-brand block" href="#">
      <span class="h3 font-bold">用户登录</span>
    </a>

    <section class="m-b-lg m-t-lg">
      <form method="post">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
        <div class="form-group">
          <input type="email" name="email" placeholder="邮箱地址" value="{{Input::old('email')}}" class="form-control input-lg">
          @if(!empty($errors))
          <span class="help-block">{{{ $errors->first('email') }}}</span>
          @endif
        </div>

        <div class="form-group">
           <input type="password" name="password" placeholder="密码" class="form-control input-lg">
           @if(!empty($errors))
           <span class="help-block">{{{ $errors->first('password') }}}</span>
           @endif
        </div>

        <button type="submit" class="btn btn-lg btn-dark btn-block">
          <i class="icon-arrow-right pull-right"></i>
          <span class="m-r-n-lg">登录</span>
        </button>

        <div class="text-center m-t m-b">
          <a href="{{ URL::to('angel/resetpwd') }}">
            <small>忘记密码?</small>
          </a>
        </div>

        <div class="line line-dashed"></div>
        <p class="text-muted text-center">
          <small>还未注册?</small>
        </p>

        <a href="{{ URL::to('angel/register') }}">
          <button type="button" class="btn btn-lg btn-success btn-block">
            <i class="icon-arrow-right pull-right"></i>
            <span class="m-r-n-lg">免费注册帐号</span>
          </button>
        </a>
      </form>
    </section>
  </div>
</section>
@stop
