<header class="panel-heading text-right bg-light">
  <ul class="nav nav-tabs pull-left">
    <li @if (Request::is('angel/wechat')) class="active" @else class="" @endif>
      <a @if (Request::is('angel/wechat')) href="javascript:void(0);" data-toggle="tab" @else href="{{ URL::to('angel/wechat') }}" @endif>
        <i class="fa fa-eye"></i>&nbsp; 关注统计
      </a>
    </li>
  </ul>
  <span class="hidden-sm">&nbsp;</span>
</header>
