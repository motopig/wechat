<header class="panel-heading text-right bg-light">
  <ul class="nav nav-tabs pull-left">
    <li @if (Request::is('angel/coupons')) class="active" @else class="" @endif>
      <a @if (Request::is('angel/coupons')) href="javascript:void(0);" data-toggle="tab" @else href="{{ URL::to('angel/coupons') }}" @endif>
        <i class="fa fa-money"></i>&nbsp; 卡券列表
      </a>
    </li>

    <li @if (Request::is('angel/carduse')) class="active" @else class="" @endif>
      <a @if (Request::is('angel/carduse')) href="javascript:void(0);" data-toggle="tab" @else href="{{ URL::to('angel/carduse') }}" @endif>
        <i class="fa fa-dropbox"></i>&nbsp; 卡券核销
      </a>
    </li>

    <li @if (Request::is('angel/verification')) class="active" @else class="" @endif>
      <a @if (Request::is('angel/verification')) href="javascript:void(0);" data-toggle="tab" @else href="{{ URL::to('angel/verification') }}" @endif>
        <i class="fa fa-android"></i>&nbsp; 核销员
      </a>
    </li>

    <li @if (Request::is('angel/coupons/setting')) class="active" @else class="" @endif>
      <a @if (Request::is('angel/coupons/setting')) href="javascript:void(0);" data-toggle="tab" @else href="{{ URL::to('angel/coupons/setting') }}" @endif>
        <i class="fa fa-cog"></i>&nbsp; 基础设置
      </a>
    </li>
  </ul>
  <span class="hidden-sm">&nbsp;</span>
</header>
