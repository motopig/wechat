@extends('EcdoSpiderMan::layouts.sign.default')

@section('main')
<section id="content" class="m-t-lg wrapper-md animated fadeInUp">    
  <div class="container aside-xl">
    <div class="navbar-brand block m-b-lg">
      <span class="h3 font-bold">注册帐号</span>
    </div>
    
    <section class="m-b-lg m-t-lg">
      <form method="post">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
        <div class="form-group">
          <input type="email" name="email" placeholder="邮箱地址" value="{{Input::old('email')}}" class="form-control input-lg">
          <span class="help-block">{{{ $errors->first('email') }}}</span>
        </div>
        
        <div class="form-group">
          <input type="text" name="refer" placeholder="邀请码（选填）" value="{{Input::old('refer')}}" class="form-control input-lg">
          <span class="help-block">{{{ $errors->first('refer') }}}</span>
        </div>
        
        <div class="form-group">
          <input type="code" name="code" placeholder="验证码" class="form-control input-lg code-input" maxlength="4">

          <span class="code-image-get" data-url="{{URL::to('angel/code_validator')}}">
            <img src="{{URL::to('angel/code_validator')}}" title="看不清点击刷新图片" style="margin-top:-70px;margin-left:150px;">
          </span>

          <span class="help-block">{{{ $errors->first('code') }}}</span>
        </div>

        <div class="checkbox i-checks m-b">
          <label class="m-l">
            <input type="checkbox" checked=""><i></i> 我已同意<a href="{{ URL::to('help/protocal') }}" target='_blank'>《一点云客平台注册协议》</a>
          </label>
        </div>

        <button type="submit" class="btn btn-lg btn-success btn-block">
          <i class="icon-arrow-right pull-right"></i>
          <span class="m-r-n-lg">立即注册</span>
        </button>

        <div class="line line-dashed"></div>
        <p class="text-muted text-center">
          <small>已经有一个帐户?</small>
        </p>

        <a href="{{ URL::to('angel/login') }}">
          <button type="button" class="btn btn-lg btn-dark btn-block">
            <i class="icon-arrow-right pull-right"></i>
            <span class="m-r-n-lg">立即登录</span>
          </button>
        </a>
      </form>
    </section>
  </div>
</section>

<script src="{{{ asset('atlas/hell/spider-man/js/angel.desktop.js') }}}"></script>
<script type="text/javascript">
  $('.code-image-get').bind('click', function() {
    var code = Math.random();
    $('.code-image-get img').attr('src', '{{URL::to("angel/code_validator?code=")}}' + code);
  });
</script>
@stop
