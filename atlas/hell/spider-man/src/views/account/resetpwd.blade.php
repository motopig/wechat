@extends('EcdoSpiderMan::layouts.sign.default')

@section('main')
<section id="content" class="m-t-lg wrapper-md animated fadeInUp">    
  <div class="container aside-xl">
    <a class="navbar-brand block" href="{{ URL::to('/') }}">
      <span class="h3 font-bold">找回密码</span>
    </a>

    <section class="m-b-lg">
      <form method="post">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />

        <div class="form-group">
          <input type="email" name="email" placeholder="请输入邮箱" class="form-control input-lg">
          <span class="help-block">{{{ $errors->first('email') }}}</span>
        </div>

        <div class="form-group">
          <input type="code" name="code" placeholder="验证码" class="form-control input-lg code-input" maxlength="4">

          <span class="code-image-get" data-url="{{URL::to('angel/code_validator')}}">
            <img src="{{URL::to('angel/code_validator')}}" title="看不清点击刷新图片" style="margin-top:-70px;margin-left:150px;">
          </span>

          <span class="help-block">{{{ $errors->first('code') }}}</span>
        </div>

        <button type="submit" class="btn btn-lg btn-success btn-block">
          <i class="icon-arrow-right pull-right"></i>
          <span class="m-r-n-lg">发送到验证邮件</span>
        </button>

        <div class="line line-dashed"></div>
        
        <a href="{{ URL::to('angel/login') }}">
          <button type="button" class="btn btn-lg btn-dark btn-block">
            <i class="icon-arrow-left pull-right"></i>
            <span class="m-r-n-lg">返回登录</span>
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
