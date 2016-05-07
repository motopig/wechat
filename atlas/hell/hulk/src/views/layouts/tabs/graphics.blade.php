<header class="panel-heading text-right bg-light">
  <ul class="nav nav-tabs pull-left">
    <li @if (Request::is('angel/wechat/graphics')) class="active" @else class="" @endif>
      <a @if (Request::is('angel/wechat/graphics')) href="javascript:void(0);" data-toggle="tab" @else href="{{ URL::to('angel/wechat/graphics') }}" @endif>
        <i class="fa fa-comments"></i>&nbsp; 微信图文
      </a>
    </li>

    <li @if (Request::is('angel/wechat/material')) class="active" @else class="" @endif>
      <a @if (Request::is('angel/wechat/material')) href="javascript:void(0);" data-toggle="tab" @else href="{{ URL::to('angel/wechat/material') }}" @endif>
        <i class="fa fa-gamepad"></i>&nbsp; 高级图文
      </a>
    </li>
  </ul>
  <span class="hidden-sm">&nbsp;</span>
</header>
