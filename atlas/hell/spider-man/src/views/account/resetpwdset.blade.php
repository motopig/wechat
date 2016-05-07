@extends('EcdoSpiderMan::layouts.sign.default')

@section('main')
<section id="content" class="m-t-lg wrapper-md animated fadeInUp">    
  <div class="container aside-xl">
    <a class="navbar-brand block" href="{{ URL::to('/') }}">
      <span class="h3 font-bold">重置密码</span>
    </a>

    <section class="m-b-lg">
      <form method="post">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />

        <div class="form-group">
           <input type="password" name="password" placeholder="输入密码" class="form-control rounded input-lg firefox-input">
           <span class="input-group-btn">
              <button class="firefox-input-password firefox-input-btn btn btn-default" type="button">显示</button>
           </span>
           <span class="help-block">{{{ $errors->first('password') }}}</span>
        </div>

        <button type="submit" class="btn btn-lg btn-success btn-block">
          <i class="icon-arrow-right pull-right"></i>
          <span class="m-r-n-lg">确认修改密码</span>
        </button>
      </form>
    </section>
  </div>
</section>

<script src="{{{ asset('atlas/hell/spider-man/js/angel.desktop.js') }}}"></script>
@stop
