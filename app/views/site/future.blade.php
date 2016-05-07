@extends('site.layouts.default')

@section('main')

<section id="slider_wrapper" class="gray">
	<div class="container" style="text-align:center">
	    <h3>功能介绍</h3>
        <p>一点云客智能营销平台致力解决客户的移动营销难题</p>
	</div>
</section>

<div id="main">
  <div class="container">
      
    <section id="features_teasers_wrapper">
      <div class="row">
        <div class="span4 feature_teaser">
          <img alt="responsive" class="zbyao" src="{{{ asset('assets/god/site/images/zbyao.png') }}}" />
          <h3>微信周边摇一摇</h3>
          <p>部署摇一摇设备，开启周边优惠推送</p>
        </div>

        <div class="span4 feature_teaser">
            <img alt="responsive" class="wifi" src="{{{ asset('assets/god/site/images/wifi.png') }}}" />
          <h3>智能微信Wi-Fi</h3>
          <p>微信认证上网，关注公众帐号 获取到店访客数据</p>
        </div>
        
        <div class="span4 feature_teaser">
            <img alt="responsive" class="banner" src="{{{ asset('assets/god/site/images/banner.png') }}}" />
          <h3>微信数字标牌</h3>
          <p>店内广告屏/入口广告屏 微信互动，到店签到</p>
        </div>
      </div>
    </section>
    
    <section id="features_teasers_wrapper">
      <div class="row">
        <div class="span4 feature_teaser">
          <img alt="responsive" class="kaquan" src="{{{ asset('assets/god/site/images/kaquan.png') }}}" />
          <h3>微信营销</h3>
          <p>商家门店卡券/商家优惠活动</p>
        </div>

        <div class="span4 feature_teaser">
            <img alt="responsive" class="qiang" src="{{{ asset('assets/god/site/images/qiang.png') }}}" />
          <h3>微信墙</h3>
          <p>微信上墙,支持会议模式和年会模式</p>
        </div>
        
        <div class="span4 feature_teaser">
            <img alt="responsive" class="map" src="{{{ asset('assets/god/site/images/map.png') }}}" />
          <h3>附近门店</h3>
          <p>通过微信快速搜索附近门店</p>
        </div>
      </div>
    </section>

    <div align="center">
        <h3>更多客户的选择</h3>
      <img alt="responsive" src="{{{ asset('assets/god/site/images/clients.png') }}}" />
    </div>
    
  </div>

</div>
@stop