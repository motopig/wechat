<!doctype html>
<html class="no-js" lang="en">
<head>
<meta charset="utf-8">
<title>云客 :: mobile e-Commerce system</title>
@include('god.layouts.desktop.header')
</head>
<body>
	<div class="navbar">@include('god.layouts.desktop.navbar')</div>
	<div class="container-fluid-full">
		<div class="row-fluid">
			@include('god.layouts.desktop.sidebar')
			<div id="content" class="span10">@yield('main')</div>
		</div>
	</div>
	<div class="foot">
	   @include('god.layouts.desktop.footer')
   </div>
</body>
</html>