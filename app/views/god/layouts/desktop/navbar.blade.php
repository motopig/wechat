<div class="navbar-inner">
	<div class="container-fluid">
		<a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
			<span class="fa fa-bars"></span>
			<span class="fa fa-bars"></span>
			<span class="fa fa-bars"></span>
		</a>
		<a id="main-menu-toggle" class="hidden-phone open">
			<i class="fa fa-reorder"></i>
		</a>
		<div class="row-fluid">
			<a class="brand span1" href="###">
				<span>
					<strong>云客</strong> yunke
				</span>
			</a>
		</div>
		<div class="nav-no-collapse header-nav">
			<ul class="nav pull-right">
				<li class="dropdown">
					<a class="btn account dropdown-toggle" data-toggle="dropdown" href="#">
						<div class="avatar">
							<img src="{{{ asset('/admin.png') }}}" alt="Avatar" />
						</div>
						<div class="user">
							<span class="name">
								@if (Auth::god()->check()) {{Auth::god()->get()->email}} @endif &nbsp;<b class="caret"></b>
							</span>
						</div>
					</a>
					<ul class="dropdown-menu">
						<li class="dropdown-menu-title"></li>
						<li>
							<a href="###">
								<i class="fa fa-user"></i> 个人资料
							</a>
						</li>
						<li>
							<a href="###">
								<i class="fa fa-cog"></i> 个人设置
							</a>
						</li>
						<li>
							<a href="{{ URL::to('god/logout') }}">
								<i class="fa fa-off"></i> 退出
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>