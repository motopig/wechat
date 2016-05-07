@extends('site.layouts.default')

@section('main')

<section id="slider_wrapper" class="gray">
	<div class="container" style="text-align:center">
	    <h3>产品报价</h3>
        <p>一点云客智能营销平台致力解决客户的移动营销难题</p>
	</div>
</section>

<div id="main">
  <div class="container">
      <section id="price_wrapper">
          <div class="span3 free">
              <h3>免费版本</h3>
              <ul>
                  <li>微信公众号托管</li>
                  <li>微信基本功能</li>
                  <li>存储空间2G</li>
                  <li>免费赠送100条短信</li>
              </ul>
              <div class="button">
                  <a class="btn btn-default btn-large" href="{{ URL::to('angel/order/free') }}">立即购买</a>
              </div>
          </div>
          <div class="span3 price_block ent">
              <h3>企业版本</h3>
              <p class="price">
                  ￥299/月，￥2999/年<br>
                  <span>年付套餐节省￥598元</span>
              </p>
              <ul>
                  <li>微信公众号托管</li>
                  <li>微信高级功能</li>
                  <li>存储空间5G</li>
                  <li>免费赠送2000条短信</li>
                  <li>赠送摇一摇设备</li>
                  <li>赠送小商户微信Wi-Fi设备</li>
              </ul>
              <div class="button">
                  <a class="btn btn-success btn-large" href="{{ URL::to('angel/order/ent') }}">立即购买</a>
              </div>
          </div>
          <div class="span3 price_block pro">
              <h3>旗舰版本</h3>
              <p class="price">
                  ￥999/月，￥9999/年<br>
                  <span>支持定制开发/年付套餐节省￥1998元</span>
              </p>
              <ul>
                  <li>微信公众号托管</li>
                  <li>微信高级功能</li>
                  <li>定制微商城</li>
                  <li>存储空间10G</li>
                  <li>免费赠送5000条短信</li>
                  <li>赠送3个摇一摇设备</li>
                  <li>赠送中大型微信Wi-Fi设备</li>
              </ul>
              <div class="button">
                  <a class="btn btn-default btn-large" href="{{ URL::to('angel/order/pro') }}">立即购买</a>
              </div>
          </div>
          <div class="span3 price_block exp">
              <h3>独立版本</h3>
              <p class="price">
                  独立部署版本<br>
                  <span>支持定制开发/自有服务器</span>
              </p>
              <ul>
                  <li>微信公众号托管</li>
                  <li>微信高级功能</li>
                  <li>存储空间无限</li>
                  <li>免费赠送10000条短信</li>
                  <li>云客营销应用源码</li>
                  <li>赠送智能硬件套装(含摇一摇/微信Wi-Fi)</li>
              </ul>
              <div class="button">
                  <a class="btn btn-default btn-large" href="{{ URL::to('angel/order/exp') }}">立即购买</a>
              </div>
          </div>
      </section>
  </div>

</div>
@stop
