@extends('EcdoSpiderMan::layouts.sign.default')

@section('main')
<section id="content" class="m-t-lg wrapper-md animated fadeInUp">    
  <div class="container aside-xl">
    <a class="navbar-brand block" href="{{ URL::to('/') }}">
      <span class="h3 font-bold">邮件已发送</span>
    </a>

    <section class="m-b-lg">
      @if ($email)
        <div class="form-group" style="text-align:center;">
       	  已向邮箱 {{$email}} 发送了验证邮件
        </div>
      @endif

      <div class="line line-dashed"></div>
      <p class="text-muted text-center">
        <small>收不到？</small>
        <a href="{{ URL::to('angel/register') }}">重新发送验证邮件</a>
      </p>

      <a href="http://{{$uri}}" target="_blank">
        <button type="button" class="btn btn-lg btn-success btn-block">
          <i class="icon-arrow-right pull-right"></i>
          <span class="m-r-n-lg">立即去邮箱查收</span>
        </button>
      </a>
    </section>
  </div>
</section>

<script src="{{{ asset('atlas/hell/spider-man/js/angel.desktop.js') }}}"></script>
@stop
