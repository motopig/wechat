<!doctype html>
<html class="app" lang="en">
<head>
<meta charset="utf-8">
<title>{{{$metas['title'] or '一点云客 | 移动智能营销管理平台'}}}</title> 
@include('EcdoSpiderMan::layouts.desktop.header')
@yield('styles')
</head>
<body class="">
	<section class="vbox">
        @include('EcdoSpiderMan::layouts.notify.notifications')
		@include('EcdoSpiderMan::layouts.desktop.navbar')
		<section>
			<section class="hbox stretch">
				@include('EcdoSpiderMan::layouts.desktop.sidebar')
				<section class="vbox hell-modal-dialog">
					<section class="scrollable padder-lg" id="bjax-target" style="bottom:45px;top:15px;">
				    	@yield('main')
				    </section>
				    @include('EcdoSpiderMan::layouts.desktop.footer')
				</section>
			</section>
		</section>
	</section>
	@yield('scripts')
</body>
</html>
