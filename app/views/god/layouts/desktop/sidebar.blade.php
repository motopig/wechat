<div id="sidebar-left" class="span2">
	<div class="nav-collapse sidebar-nav">
		<ul class="nav nav-tabs nav-stacked main-menu">
			<li @if (Request::is('god')) class="active" @endif>
				<a href="{{ URL::to('god') }}">
					<i class="fa fa-dashboard"></i>
					<span class="hidden-tablet"> 控制台</span>
				</a>
			</li>
			<li @if (Request::is('god/territory')) class="active" @endif>
				<a href="javascript:void(0);" class="winAction" ctl="territory">
					<i class="fa fa-facebook-square"></i>
					<span class="hidden-tablet"> 公司管理</span>
				</a>
			</li>
			<li>
				<a href="###">
					<i class="fa fa-home"></i>
					<span class="hidden-tablet"> 店铺管理</span>
				</a>
			</li>
			<li>
				<a class="dropmenu" href="#">
					<i class="fa fa-user"></i>
					<span class="hidden-tablet"> 用户管理</span>
					<span class="label">3</span>
				</a>
				<ul>
					<li>
						<a class="submenu" href="###">
							<i class="fa fa-user-md"></i>
							<span class="hidden-tablet"> 平台用户</span>
						</a>
					</li>
					<li>
						<a class="submenu" href="###">
							<i class="fa fa-twitter"></i>
							<span class="hidden-tablet"> 商家用户</span>
						</a>
					</li>
					<li>
						<a class="submenu" href="###">
							<i class="fa fa-github-alt"></i>
							<span class="hidden-tablet"> 消费者用户</span>
						</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="###">
					<i class="fa fa-google-plus-square"></i>
					<span class="hidden-tablet"> 角色管理</span>
				</a>
			</li>
		</ul>
	</div>
</div>
