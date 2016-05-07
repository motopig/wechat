@extends('EcdoSpiderMan::layouts.sign.default')

@section('main')
<section id="content" class="m-t-lg wrapper-md animated fadeInUp">    
  <div class="container aside-xl">
    <a class="navbar-brand block" href="{{ URL::to('/') }}">
      <span class="h3 font-bold">创建帐号</span>
    </a>

    <section class="m-b-lg">
      <form method="post">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
        <input type="hidden" name="sign" value="{{$sign}}" />
        <input type="hidden" name="refer" value="{{$refer}}" />

        <div class="form-group">
         	<input readonly type="email" name="email" value="{{$email}}" class="form-control input-lg">
        </div>

        <div class="form-group">
        	<div class="radio i-checks">
        		<label class="radio-checks">
                	<input type="radio" name="property" value="enterprise" checked>
                	<i></i> 企业用户
              	</label>

              	<label class="radio-checks">
                	<input type="radio" name="property" value="personal">
                	<i></i> 个人用户
              	</label>

              	<b class="badge bg-success radio-checks" data-toggle="tooltip" data-placement="right" 
              	data-original-title="企业用户拥有更多权限">
              		<span class="icon-question"></span>
              	</b>
            </div>
        </div>

        <div class="form-group">
           <input type="password" name="password" placeholder="密码" class="form-control input-lg firefox-input">
           <span class="input-group-btn">
              <button class="firefox-input-password firefox-input-btn btn btn-default" type="button">显示</button>
           </span>
           <span class="help-block">{{{ $errors->first('password') }}}</span>
        </div>

        <button type="submit" class="btn btn-lg btn-success btn-block">
          <i class="icon-arrow-right pull-right"></i>
          <span class="m-r-n-lg">创建账号</span>
        </button>
      </form>
    </section>
  </div>
</section>

<script src="{{{ asset('atlas/hell/spider-man/js/angel.desktop.js') }}}"></script>
@stop
