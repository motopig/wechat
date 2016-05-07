<header class="bg-black header header-md navbar navbar-fixed-top-xs">
	<div class="bg-black navbar-header aside-sm">
		<a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen,open" data-target="#nav,html">
			<i class="fa fa-list"></i>
		</a>
		<!-- <a href="###" class="navbar-brand text-lt"> -->
		<a href="{{ URL::to('angel/dashboard') }}" class="navbar-brand text-lt">
    		<span class="hidden-nav-xs">
    			<strong class="logo">一点云客</strong>
    		</span>
		</a>
		<a class="btn btn-link visible-xs" data-toggle="dropdown" data-target=".user">
			<i class="icon-settings"></i>
		</a>
	</div>
	<ul class="nav navbar-nav hidden-xs">
		<!--<li>
			<a href="#nav,.navbar-header" data-toggle="class:nav-xs,nav-xs" class="text-muted">
				<i class="fa fa-indent text"></i> <i class="fa fa-dedent text-active"></i>
			</a>
		</li>-->

		<li>
			<a href="{{ URL::to('angel') }}">
				<i class="fa fa-chevron-left"></i>&nbsp;返回首页
			</a>
		</li>

	</ul>
    @include('EcdoSpiderMan::layouts.desktop.userbar')
	
</header>