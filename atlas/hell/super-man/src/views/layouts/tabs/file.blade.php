<header class="panel-heading text-right bg-light">
  <ul class="nav nav-tabs pull-left">
    <li @if (Request::is('angel/store/image')) class="active" @else class="" @endif>
      <a @if (Request::is('angel/store/image')) href="javascript:void(0);" data-toggle="tab" @else href="{{ URL::to('angel/store/image') }}" @endif>
        <i class="fa fa-picture-o"></i>&nbsp; 图片
      </a>
    </li>

    <li @if (Request::is('angel/store/voice')) class="active" @else class="" @endif>
      <a @if (Request::is('angel/store/voice')) href="javascript:void(0);" data-toggle="tab" @else href="{{ URL::to('angel/store/voice') }}" @endif>
        <i class="fa fa-microphone"></i>&nbsp; 语音
      </a>
    </li>

    <li @if (Request::is('angel/store/video')) class="active" @else class="" @endif>
      <a @if (Request::is('angel/store/video')) href="javascript:void(0);" data-toggle="tab" @else href="{{ URL::to('angel/store/video') }}" @endif>
        <i class="fa fa-video-camera"></i>&nbsp; 视频
      </a>
    </li>
  </ul>
  <span class="hidden-sm">&nbsp;</span>
</header>
