@extends('site.layouts.default')

@section('main')

<section id="slider_wrapper">
	<div id="main_flexslider" class="flexslider">
    	<ul class="slides">
        	<li class="item" style="background-image: url({{{ asset('assets/god/site/images/zbyy.jpg') }}})">
            	<div class="container">
                	<div class="carousel-caption">
                   		<h1>一起开启移动智能营销新时代</h1>
                        <p class="lead inverse">
                        	<strong>微信公众号接入 | 周边摇一摇 | 商业WiFi | 会员营销</strong>
                        </p>
                        <span class="round_badge" style="display:none;"><strong>全新</strong>version<strong>2.0</strong></span> 
                    </div>
                </div>
            </li>
            <li class="item" style="background-image: url({{{ asset('assets/god/site/images/zbyy.jpg') }}})">
                <div class="container">
                	<div class="carousel-caption">
                   		<h1>微信周边摇一摇</h1>
                        <p class="lead inverse">
                        	周边优惠 | 抽奖游戏 | 摇一摇红包 | 摇一摇导航
                        </p>
                    </div>
                </div>
            </li>
            <li class="item" style="background-image: url({{{ asset('assets/god/site/images/wifi.jpg') }}})">
                <div class="container">
                	<div class="carousel-caption">
                   		<h1>
                    		智能微信WiFi 门店必备利器
                        </h1>
                        
                        <p class="lead inverse">
                        	实时统计到店率，掌握到店访客数据，展示WiFi广告，微信公众号粉丝暴涨
                        </p>
                    </div>
                </div>
            </li>
            
            <li class="item" style="background-image: url({{{ asset('assets/god/site/images/wifi.jpg') }}})">
                <div class="container">
                	<div class="carousel-caption">
                   		<h1>一点云客 移动时代的新玩法</h1>
                        <p class="lead inverse">
                        	玩转粉丝经济，引领智能商家硬件，开启移动营销新时代
                        </p>
                    </div>
                </div>
            </li>
            
        </ul>
    </div>
</section>

<div id="main">
  <div class="container">
    <section class="call_to_action">
      <h3>移动智能时代的营销解决方案</h3>
      <h4>一点云客智能互动体验</h4>
      <a class="btn btn-success btn-large" href="{{ URL::to('angel/register') }}">立即免费试用</a>
    </section>

    <section id="features_teasers_wrapper">
      <div class="row">
        <div class="span4 feature_teaser">
          <img alt="responsive" class="zbyao" src="{{{ asset('assets/god/site/images/zbyao.png') }}}" />
          <h3>
              <a href="{{ URL::to('feature#zbyao') }}">微信周边摇一摇</a>
          </h3>
          <p>部署摇一摇设备，开启周边优惠推送</p>
        </div>

        <div class="span4 feature_teaser">
            <img alt="responsive" class="wifi" src="{{{ asset('assets/god/site/images/wifi.png') }}}" />
          <h3>
              <a href="{{ URL::to('feature#wifi') }}">智能微信Wi-Fi</a>
          </h3>
          <p>微信认证上网，关注公众帐号 获取到店访客数据</p>
        </div>
        
        <div class="span4 feature_teaser">
            <img alt="responsive" class="banner" src="{{{ asset('assets/god/site/images/banner.png') }}}" />
          <h3>
              <a href="{{ URL::to('feature#dg-banner') }}">微信数字标牌</a>
          </h3>
          <p>店内广告屏/入口广告屏 微信互动，到店签到</p>
        </div>
      </div>
    </section>
    
    <section id="features_teasers_wrapper">
      <div class="row">
        <div class="span4 feature_teaser">
          <img alt="responsive" class="kaquan" src="{{{ asset('assets/god/site/images/kaquan.png') }}}" />
          <h3>
              <a href="{{ URL::to('feature#quan') }}">微信营销</a>
          </h3>
          <p>商家门店卡券/商家优惠活动</p>
        </div>

        <div class="span4 feature_teaser">
            <img alt="responsive" class="qiang" src="{{{ asset('assets/god/site/images/qiang.png') }}}" />
          <h3>
              <a href="{{ URL::to('feature#weiqiang') }}">微信墙</a>
          </h3>
          <p>微信上墙,支持会议模式和年会模式</p>
        </div>
        
        <div class="span4 feature_teaser">
            <img alt="responsive" class="map" src="{{{ asset('assets/god/site/images/map.png') }}}" />
          <h3>
              <a href="{{ URL::to('feature#fujin') }}">附近门店</a>
          </h3>
          <p>通过微信快速搜索附近门店</p>
        </div>
      </div>
    </section>

    <section id="portfolio_teasers_wrapper" style="display:none;">
      <h2 class="section_header">
          商家智能营销的首选
      </h2>
      <div class="portfolio_strict row">
        <div class="portfolio_item span3">
          <div class="portfolio_photo" style="background-image:url({{{ asset('assets/god/site/images/p1.jpg') }}})">
            <a href="###">
              <!-- <i class="icon-2x icon-external-link"></i> -->
              <div class="portfolio_photo">
                <img class="js-case-qrcode widget-big-case-qrcode qrcode_img" 
                src="{{{ asset('assets/god/site/images/qr_lumi.jpg') }}}" />
              </div>
            </a>
          </div>

          <div class="portfolio_description">
            <h3>
              <a href="###">Lumi</a>
            </h3>
            <p>营养美容 品牌电商</p>
          </div>
        </div>

        <div class="portfolio_item span3">
          <div class="portfolio_photo" style="background-image:url({{{ asset('assets/god/site/images/p2.jpg') }}})">
            <a href="###">
              <div class="portfolio_photo">
                <img class="js-case-qrcode widget-big-case-qrcode qrcode_img" 
                src="{{{ asset('assets/god/site/images/qr_liufuya.jpg') }}}" />
              </div>
            </a>
          </div>

          <div class="portfolio_description">
            <h3>
              <a href="###">留夫鸭</a>
            </h3>
            <p>快餐熟食 连锁门店</p>
          </div>
        </div>

        <div class="portfolio_item span3">
          <div class="portfolio_photo" style="background-image:url({{{ asset('assets/god/site/images/p3.jpg') }}})">
            <a href="###">
              <div class="portfolio_photo">
                <img class="js-case-qrcode widget-big-case-qrcode qrcode_img" 
                src="{{{ asset('assets/god/site/images/qr_gyzyxm.jpg') }}}" />
              </div>
              <p></p>
            </a>
          </div>

          <div class="portfolio_description">
            <h3>
              <a href="###">中影星美</a>
            </h3>
            <p>影视传媒 商场/电影院</p>
          </div>
        </div>

        <div class="portfolio_item span3">
          <div class="portfolio_photo" style="background-image:url({{{ asset('assets/god/site/images/p4.jpg') }}})">
            <a href="###">
              <div class="portfolio_photo">
                <img class="js-case-qrcode widget-big-case-qrcode qrcode_img" 
                src="{{{ asset('assets/god/site/images/qr_taocai88.jpg') }}}" />
              </div>
            </a>
          </div>

          <div class="portfolio_description">
            <h3>
              <a href="###">淘彩巴巴</a>
            </h3>
            <p>博彩积分 线上/线下彩票销售</p>
          </div>
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
