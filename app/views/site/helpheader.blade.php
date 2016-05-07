<div class="span2 help_list">
  <h3>版本说明</h3>
  <ul>
      <li class="@if ($view=='free') active @endif"><a href="{{ URL::to('help/free') }}">免费版本</a></li>
      <li class="@if ($view=='ent') active @endif"><a href="{{ URL::to('help/ent') }}">企业版本</a></li>
      <li class="@if ($view=='pro') active @endif"><a href="{{ URL::to('help/pro') }}">旗舰版本</a></li>
      <li class="@if ($view=='exp') active @endif"><a href="{{ URL::to('help/exp') }}">独立版本</a></li>
  </ul>
  <h3>营销助力</h3>
  <ul>
      <li class="@if ($view=='huodong') active @endif"><a href="{{ URL::to('help/huodong') }}">活动助力</a></li>
      <li class="@if ($view=='shop') active @endif"><a href="{{ URL::to('help/shop') }}">门店助力</a></li>
  </ul>
  <h3>微信帮助</h3>
  <ul>
      <li class="@if ($view=='weixinmp') active @endif"><a href="{{ URL::to('help/weixinmp') }}">公众号介绍</a></li>
      <li class="@if ($view=='weixinpro') active @endif"><a href="{{ URL::to('help/weixinpro') }}">微信认证介绍</a></li>
      <li class="@if ($view=='weixiniot') active @endif"><a href="{{ URL::to('help/weixiniot') }}">微信智能硬件</a></li>
  </ul>
  <h3>一点云客</h3>
  <ul>
      <li class="@if ($view=='contact') active @endif"><a href="{{ URL::to('help/contact') }}">联系我们</a></li>
      <li class="@if ($view=='partner') active @endif"><a href="{{ URL::to('help/partner') }}">伙伴合作</a></li>
      <li class="@if ($view=='protocal') active @endif"><a href="{{ URL::to('help/protocal') }}">用户协议</a></li>
  </ul>
</div>