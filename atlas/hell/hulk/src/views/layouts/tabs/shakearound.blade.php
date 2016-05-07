<header class="panel-heading text-right bg-light">
  <ul class="nav nav-tabs pull-left">
    <li @if (Request::is('angel/wechat/shakearound/device')) class="active" @else class="" @endif>
      <a @if (Request::is('angel/wechat/shakearound/device')) href="javascript:void(0);" data-toggle="tab" @else href="{{ URL::to('angel/wechat/shakearound/device') }}" @endif>
        <i class="fa fa-android"></i>&nbsp; 设备
      </a>
    </li>

    <li @if (Request::is('angel/wechat/shakearound/page')) class="active" @else class="" @endif>
      <a @if (Request::is('angel/wechat/shakearound/page')) href="javascript:void(0);" data-toggle="tab" @else href="{{ URL::to('angel/wechat/shakearound/page') }}" @endif>
        <i class="fa fa-file-text"></i>&nbsp; 页面
      </a>
    </li>
  </ul>
  <span class="hidden-sm">&nbsp;</span>
</header>
