<!doctype html>
<html class="app" lang="en">
<head>
<meta charset="utf-8">
<title>{{{$metas['title'] or '一点云客 | 移动智能营销管理平台'}}}</title>
@include('EcdoSpiderMan::layouts.menu.header')
</head>
<body class="bg-white">
	<section class="vbox">
        @include('EcdoSpiderMan::layouts.notify.notifications')
		<section>
            @include('EcdoSpiderMan::layouts.dashboard.navbar')
			<section class="hbox stretch">
				<section class="vbox hell-modal-dialog">
    				<section class="scrollable padder-lg w-f-md" id="bjax-target" style="bottom:45px;top:15px;">
				    	@yield('main')
				    </section>
    				@include('EcdoSpiderMan::layouts.menu.footer')
				</section>
			</section>
		</section>
	</section>
</body>
</html>