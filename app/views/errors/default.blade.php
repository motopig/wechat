<!doctype html>
<html class="no-js" lang="en">
<head>
<meta charset="utf-8">
<title>{{{$metas['title'] or '一点云客 - 移动智能营销平台'}}}</title>
<meta content="{{{$metas['keyword'] or '云客移动智能营销平台'}}}" name="keywords">
<meta content="{{{$metas['description'] or '云客移动智能营销平台'}}}" name="description">
@include('errors.header')
</head>
<body>
<header>
	<div class="container">
    	<div class="navbar">
        	<div class="navbar-inner">
        		<a class="brand" href="{{ URL::to('/') }}">
        			<img src="{{{ asset('assets/god/site/images/restart_logo.png') }}}" width="90" height="90" alt="optional logo" />
               		<div class="logo_title">
                        <strong>一点云客</strong>
                        <span class="logo_subtitle">移动智能营销平台</span>
                    </div>
               	</a>

            	<div class="nav-collapse collapse">
                    <div id="sign">
                    	<a href="{{ URL::to('angel/login') }}">
                    		登陆
                    	</a>
                    	<a href="{{ URL::to('angel/register') }}">
                    		注册
                    	</a>
                    </div>
                	<ul class="nav pull-right">
	                    <li>
	                   		<a href="{{ URL::to('/') }}">首页</a>
	                   	</li>

	                    <li>
	                    	<a href="{{ URL::to('feature') }}">功能</a>
	                    </li>

	                    <li>
	                    	<a href="{{ URL::to('price') }}">价格</a>
	                    </li>

	                    <li>
	                    	<a href="{{ URL::to('help') }}" target="_blank">帮助</a>
	                    </li>
                	</ul>
            	</div>
                
        	</div>
		</div>
	</div>
</header>
@yield('main')
@include('errors.footer')
</body>
</html>