@extends('god.layouts.default.sign')
@section('main')
<div class="container aside-xl">
  <a class="navbar-brand block" href="{{ URL::to('/') }}"><span class="h1 font-bold">云客</span></a>
  <section class="m-b-lg">
    <form class="form-horizontal" method="post">
		@include('god.layouts.default.notice')
		<input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
		<fieldset>
			<input class="form-control input-lg text-center no-border" name="email" id="email" type="text" placeholder="邮箱地址" />
            @if($errors)
			<span class="help-block">{{{ $errors->first('email') }}}</span>
            @endif

			<input class="form-control input-lg text-center no-border" name="password" id="password" type="password" placeholder="密码" />
            @if($errors)
			<span class="help-block">{{{ $errors->first('password') }}}</span>
            @endif

			<div class="clearfix"></div>
			<label class="remember" for="remember"><input type="checkbox" id="remember" />记住我</label>
				
			<div class="clearfix"></div>
			<button type="submit" class="btn btn-lg lt b-2x btn-block">登录</button>
		</fieldset>
	</form>
	<hr />
  </section>
</div>
@stop
