<header class="panel-heading text-right bg-light">
  <ul class="nav nav-tabs pull-left">
    <li @if (Request::is('angel/entityshop')) class="active" @else class="" @endif>
      <a @if (Request::is('angel/entityshop')) href="javascript:void(0);" data-toggle="tab" @else href="{{ URL::to('angel/entityshop') }}" @endif>
        <i class="fa fa-home"></i>&nbsp; 门店列表
      </a>
    </li>

    <li @if (Request::is('angel/nearbyentityshop')) class="active" @else class="" @endif>
      <a @if (Request::is('angel/nearbyentityshop')) href="javascript:void(0);" data-toggle="tab" @else href="{{ URL::to('angel/nearbyentityshop') }}" @endif>
        <i class="icon-pointer"></i>&nbsp; 附近门店
      </a>
    </li>
  </ul>
  <span class="hidden-sm">&nbsp;</span>
</header>
