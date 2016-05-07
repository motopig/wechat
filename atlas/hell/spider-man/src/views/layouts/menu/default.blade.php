<!doctype html>
<html class="app" lang="en">
<head>
<meta charset="utf-8">
<title>{{{$metas['title'] or '一点云客 | 移动智能营销管理平台'}}}</title>
@include('EcdoSpiderMan::layouts.menu.header')
</head>
<body class="">
	<section class="vbox">
        @include('EcdoSpiderMan::layouts.notify.notifications')
		@include('EcdoSpiderMan::layouts.menu.navbar')
		<section>
			<section class="hbox stretch">
				<aside class="bg-white dk aside-sm hidden-print" id="nav">
					<section class="vbox">
                		<section class="w-f-md scrollable">
                			<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="10px" data-railOpacity="0.2">
                				<nav class="nav-primary hidden-xs">
						            @include('EcdoSpiderMan::layouts.menu.sidebar')
                                </nav>
                            </div>
                        </section>
					</section>
				</aside>
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