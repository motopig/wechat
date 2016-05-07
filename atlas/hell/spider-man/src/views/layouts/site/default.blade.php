<!doctype html>
<html class="app" lang="en">
	<head>
	<meta charset="utf-8">
	<title>
		@if (Session::get('tower_name'))
			{{Session::get('tower_name')}}
		@else
			一点云客
		@endif
	</title> 
	@include('EcdoSpiderMan::layouts.site.header')
	@yield('styles')
	</head>

	<body class="">
		<section class="vbox">
			<section>
				<section class="hbox stretch">
					<section class="vbox">
					    @yield('main')
					    @include('EcdoSpiderMan::layouts.site.footer')
					</section>
				</section>
			</section>
		</section>
		@yield('scripts')
	</body>
</html>
