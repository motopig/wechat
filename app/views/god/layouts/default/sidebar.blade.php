<nav class="nav-primary hidden-xs">
  <ul class="nav" data-ride="collapse">
    <li @if (Request::is('god')) class="active" @endif>
        <a href="{{url('god')}}">
          <i class="fa fa-dashboard"></i>
          <span class="font-bold">控制台</span>
        </a>
      </li>
      <li @if (Request::is('god/territory')) class="active" @endif>
        <a href="###" class="winAction" ctl="territory">
          <i class="fa fa-facebook-square"></i>
          <span class="font-bold">公司管理</span>
        </a>
      </li>
      <li>
        <a href="{{url('god/appCenter')}}">
          <i class="fa fa-link"></i>
          <span class="font-bold">应用管理</span>
        </a>
      </li>
      <li>
        <a href="###">
          <i class="fa fa-home"></i>
          <span class="font-bold">店铺管理</span>
        </a>
      </li>
      <li>
        <a class="dropmenu" href="###">
          <i class="fa fa-user"></i>
          <span class="font-bold">用户管理</span>
        </a>
        <ul class="nav dk text-sm">
          <li>
            <a class="auto" href="###">
              <i class="fa fa-user-md"></i>
              <span>平台用户</span>
            </a>
          </li>
          <li>
            <a class="auto" href="###">
              <i class="fa fa-twitter"></i>
              <span>商家用户</span>
            </a>
          </li>
          <li>
            <a class="auto" href="###">
              <i class="fa fa-github-alt"></i>
              <span>消费者用户</span>
            </a>
          </li>
        </ul>
      </li>
      <li>
        <a href="###">
          <i class="fa fa-google-plus-square"></i>
          <span class="font-bold">角色管理</span>
        </a>
      </li>
  </ul>
</nav>
