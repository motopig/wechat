<div class="navbar-header aside-sm bg-info dk">
  <a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen,open" data-target="#nav,html">
    <i class="icon-list"></i>
  </a>
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
  <li>
    <a href="#nav,.navbar-header" data-toggle="class:nav-xs,nav-xs" class="text-muted">
      <i class="fa fa-indent text"></i> <i class="fa fa-dedent text-active"></i>
    </a>
  </li>
</ul>
<div class="navbar-right ">
  <ul class="nav navbar-nav m-n hidden-xs nav-user user">
    <li class="dropdown">
      <a href="#" class="dropdown-toggle bg clear" data-toggle="dropdown">
        <span class="thumb-sm avatar pull-right m-t-n-sm m-b-n-sm m-l-sm">
          <img src="{{{ asset('/admin.png') }}}" alt="Avatar" />
        </span>
        @if (Auth::god()->check()) {{Auth::god()->get()->email}} @endif &nbsp;<b class="caret"></b>
      </a>
      <ul class="dropdown-menu animated fadeInRight">
        <li class="dropdown-menu-title"></li>
        <li>
          <a href="###">
            <i class="fa fa-user"></i>个人资料
          </a>
        </li>
        <li>
          <a href="###">
            <i class="fa fa-cog"></i> 设置
          </a>
        </li>
        <li>
          <a href="{{url('god/logout')}}">
            <i class="fa fa-power-off"></i> 退出
          </a>
        </li>
      </ul>
    </li>
  </ul>
</div>
