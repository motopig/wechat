@extends('site.layouts.default')

@section('main')


<div id="main">
  <div class="container">
    <style>
    section#features_teasers_wrapper .feature_teaser h3{
        color:#2d9f58;
        font-size:32px;
        font-weight:normal;
    }
    ul.feature_list{
        list-style:none;width:100%;display:block;
        margin:0;
        padding:0;
        padding:0px 0px;
    }
    ul.feature_list li{
        width:12.5%;
        display:inline-block;
        margin:0;
        text-align:center;
        float:left;
        padding:20px;
        border-right:1px solid #ededed;
        border-top:1px solid #ededed;
        border-bottom:1px solid #ededed;
        border-left:1px solid #fff;
        cursor: pointer;
    }
    ul.feature_list li:first-child{
        border-left:1px solid #ededed;
    }
    section#features_teasers_wrapper .feature_teaser .feature_list img{
        width:100%;
        height:auto;
        padding:0px;
    }
    </style>
    <section id="features_teasers_wrapper">
        <h2 style="text-align:center;font-weight:normal;color:#555;">丰富的移动智能营销应用</h2>
      
      <div class="row" style="border-bottom:1px solid #eee;padding:25px 0px;">
          <div class="span12 feature_teaser">
              <ul class="feature_list">
                  <li id="f_zbyao" class="active f_zbyao_bai">
                      
                  </li>
                  <li id="f_wifi">
                      
                  </li>
                  <li id="f_haibao">
                      
                  </li>
                  <li id="f_huodong">
                      
                  </li>
                  <li id="f_diaocha">
                      
                  </li>
                  <li id="f_choujiang">
                      
                  </li>
                  <li id="f_qiang">
                      
                  </li>
                  <li id="f_lbs">
                      
                  </li>
              </ul>
          </div>
          <div class="span12 feature_teaser feature_list_content" style="padding:50px 15px;">
              <div id="f_zbyao" style="margin:0 auto;width:90%;min-height:200px;padding:30px 0px;">
                  <div style="width:55%;float:left;display:inline-block;">
                      <h3>周边摇一摇</h3>
                      <p>周边摇一摇为线下商户提供近距离连接用户的能力，支持向周边用户提供个性化营销，互动以及信息服务</p>
                  </div>
                  <div style="width:45%;float:left;display:inline-block">
                      <img style="padding:0px;border-radius:0px;width:100%;height:100%;" alt="responsive" src="{{{ asset('assets/god/site/images/feature/phone_12.png') }}}" />
                  </div>
              </div>
              
              <div id="f_wifi" style="margin:0 auto;width:80%;min-height:200px;padding:30px;display:none;">
                  <div style="width:55%;float:left;display:inline-block;">
                      <h3>微信wifi</h3>
                      <p>
                          微信wifi是集商用路由和营销功能为一体的门店wifi最佳解决方案。面向零售行业以及垂直行业客户提供全方位的WiFi上网、广告营销、室内定位等商业应用和移动营销解决方案。
                          微信wifi更是一款绝好的吸粉利器
                      </p>
                  </div>
                  <div style="width:45%;float:left;display:inline-block">
                      <img style="padding:0px;border-radius:0px;width:100%;height:100%;" alt="responsive" src="{{{ asset('assets/god/site/images/feature/phone_11.png') }}}" />
                  </div>
              </div>
              
              <div id="f_haibao" style="margin:0 auto;width:80%;min-height:200px;padding:30px;display:none;">
                  <div style="width:55%;float:left;display:inline-block;">
                      <h3>微信海报</h3>
                      <p>
                         微信海报是企业用于品牌展示、活动推广、客户互动的最佳工具。根据不同需求可以制作出手机触屏海报，邀请函，官网、手机商城、个人名片、问卷调查甚至是订餐菜单等。
                      </p>
                  </div>
                  <div style="width:45%;float:left;display:inline-block">
                      <img style="padding:0px;border-radius:0px;width:100%;height:100%;" alt="responsive" src="{{{ asset('assets/god/site/images/feature/phone_10.png') }}}" />
                  </div>
              </div>
              
              <div id="f_huodong" style="margin:0 auto;width:80%;min-height:200px;padding:30px;display:none;">
                  <div style="width:55%;float:left;display:inline-block;">
                      <h3>微活动</h3>
                      <p>
                         微活动好玩、有趣！更能带来极速传播轻松提高粉丝互动率，为您的品牌带来更多曝光量。利用微信的强交互性，让您通过对互动流程、环节和方式的设计，运用各种设计活动从而实现与用户的互动交流，微整合系统互动符合微信娱乐性强的产品本质，通过不断更新补充主题，用户可以反复参与，并可带动周边朋友一起分享，从而形成极强的口碑营销效果
                      </p>
                  </div>
                  <div style="width:45%;float:left;display:inline-block">
                      <img style="padding:0px;border-radius:0px;width:100%;" alt="responsive" src="{{{ asset('assets/god/site/images/feature/phone_9.png') }}}" />
                  </div>
              </div>
              
              <div id="f_diaocha" style="margin:0 auto;width:80%;min-height:200px;padding:30px;display:none;">
                  <div style="width:55%;float:left;display:inline-block;">
                      <h3>微调查</h3>
                      <p>
                         快速制作在线调查问卷表格，利用微信进行分享和传播，完成数据收集和定向调查。更适合于快速型调查。
                      </p>
                  </div>
                  <div style="width:45%;float:left;display:inline-block">
                      <img style="padding:0px;border-radius:0px;width:100%;height:100%;" alt="responsive" src="{{{ asset('assets/god/site/images/feature/phone_8.png') }}}" />
                  </div>
              </div>
              
              <div id="f_choujiang" style="margin:0 auto;width:80%;min-height:200px;padding:30px;display:none;">
                  <div style="width:55%;float:left;display:inline-block;">
                      <h3>微抽奖</h3>
                      <p>
                         微抽奖活动,主要作用让粉丝紧密互动,增加客户二次回头率,另外让粉丝介绍粉丝给商家,最后由粉丝转化为客户。如您的客户对你的产品或服务认可,当你举办抽奖活动时,粉丝如果抽奖获得奖励,他会推荐给身边朋友参加享受这实惠,如抽奖不中,他会利用朋友的手机进行再次参加,这样达到快速增加有效粉丝的作用,前提当然是奖品够吸引
                      </p>
                  </div>
                  <div style="width:45%;float:left;display:inline-block">
                      <img style="padding:0px;border-radius:0px;width:100%;height:100%" alt="responsive" src="{{{ asset('assets/god/site/images/feature/phone_5.png') }}}" />
                  </div>
              </div>
              
              
              <div id="f_qiang" style="margin:0 auto;width:80%;min-height:200px;padding:30px;display:none;">
                  <div style="width:55%;float:left;display:inline-block;">
                      <h3>微信墙</h3>
                      <p>
                         微信墙，活跃现场气氛。现场用户关注活动主办方微信公众账号，发送文字、表情、图片消息就可上墙展示，迅速提升现场热度，让大家互动起来
                      </p>
                  </div>
                  <div style="width:45%;float:left;display:inline-block">
                      <img style="padding:0px;border-radius:0px;width:100%;height:100%;" alt="responsive" src="{{{ asset('assets/god/site/images/feature/phone_7.png') }}}" />
                  </div>
              </div>
              
              <div id="f_lbs" style="margin:0 auto;width:80%;min-height:200px;padding:30px;display:none;">
                  <div style="width:55%;float:left;display:inline-block;">
                      <h3>附近门店</h3>
                      <p>
                         通过微信快速查询附近门店，方便客户进行门店搜索和定位，提高到店客流，提升门店影响力和销售数据。
                      </p>
                  </div>
                  <div style="width:45%;float:left;display:inline-block">
                      <img style="padding:0px;border-radius:0px;width:100%;height:100%;" alt="responsive" src="{{{ asset('assets/god/site/images/feature/phone_6.png') }}}" />
                  </div>
              </div>
              
          </div>
      </div>
      
      <div class="row" style="border-bottom:1px solid #eee;padding:25px 0px;">
          <h2 style="text-align:center;font-weight:normal;color:#555;margin-bottom:50px;">微信公众号托管和助力</h2>
        <div class="span6 feature_teaser">
          <h3>微信即时消息</h3>
          <p>
              云客后台可托管微信即时消息，更优秀的即时消息交互体验
          </p>
          <h3 style="margin-top:20px;">微信图文消息</h3>
          <p>
              云客后台支持编辑微信图文消息
          </p>
          <p>
              实时预览/微信群发/社交分享
          </p>
        </div>
        <div class="span6">
          <img alt="responsive" src="{{{ asset('assets/god/site/images/feature/phone_1.png') }}}" />
        </div>
      </div>
      
      <div class="row" style="border-bottom:1px solid #eee;padding:25px 0px;">
        <div class="span6">
          <img alt="responsive" src="{{{ asset('assets/god/site/images/feature/phone_2.png') }}}" />
        </div>
        <div class="span6 feature_teaser">
          <h3>自动回复</h3>
          <p>
              设置关注自动回复和关键字自动回复
          </p>
          <p>
              轻松省时，提高客服工作效率
          </p>
          <h3 style="margin-top:20px;">会员中心</h3>
          <p>
              云客具有完善的会员中心功能
          </p>
          <p>
              无需额外付费，绑定公众号即可享有
          </p>
        </div>
      </div>
      
      <div class="row" style="border-bottom:1px solid #eee;padding:25px 0px;">
        
        <div class="span6 feature_teaser">
          <h3>微官网</h3>
          <p>
              使用云客快速建立微官网，展示企业/门店信息
          </p>
          <h3 style="margin-top:20px;">微购物</h3>
          <p>
              云客集成微商城，支持在线商品展示和购买
          </p>
        </div>
        <div class="span6">
          <img alt="responsive" src="{{{ asset('assets/god/site/images/feature/phone_3.png') }}}" />
        </div>
      </div>
      
    </section>
    
    <h2 style="text-align:center;font-weight:normal;color:#555;margin-bottom:50px;">智能营销&开放平台</h2>
    
    <div class="row" style="margin-bottom:50px;">
        
        <div class="span4" style="text-align:center;">
            <img src="{{{ asset('assets/god/site/images/feature/phone_icon_1.png') }}}" style="display:block;margin:0 auto">
            <div>
                <h4>移动商城</h4>
                <span>
                    在线交易/在线支付/会员中心
                </span>
            </div>
        </div>
        <div class="span4" style="text-align:center;">
            <img src="{{{ asset('assets/god/site/images/feature/phone_icon_2.png') }}}" style="display:block;margin:0 auto;">
            <div>
                <h4>微信公众号</h4>
                <span>
                    对接微信公众号,实现更丰富的移动营销
                </span>
            </div>
        </div>
        <div class="span4" style="text-align:center;">
            <img src="{{{ asset('assets/god/site/images/feature/phone_icon_3.png') }}}" style="display:block;margin:0 auto;">
            <div>
                <h4>门店/商场</h4>
                <span>
                    引流到门店/门店场景研究/微信WiFi
                </span>
            </div>
        </div>
        
        
    </div>
    
    <div class="row" style="margin-bottom:50px;">
        
        <div class="span4" style="text-align:center;">
            <img src="{{{ asset('assets/god/site/images/feature/phone_icon_4.png') }}}" style="display:block;margin:0 auto">
            <div>
                <h4>社区/社交平台</h4>
                <span>
                    社区应用研究与实现/支持在线社区
                </span>
            </div>
        </div>
        <div class="span4" style="text-align:center;">
            <img src="{{{ asset('assets/god/site/images/feature/phone_icon_5.png') }}}" style="display:block;margin:0 auto;">
            <div>
                <h4>分享</h4>
                <span>
                    一键分享 朋友圈/QQ/微博
                </span>
            </div>
        </div>
        <div class="span4" style="text-align:center;">
            <img src="{{{ asset('assets/god/site/images/feature/phone_icon_6.png') }}}" style="display:block;margin:0 auto;">
            <div>
                <h4>LBS附近</h4>
                <span>
                    基于地理位置和近场应用的营销方案
                </span>
            </div>
        </div>
        
        
    </div>
    
  </div>


<script>
    $(document).ready(function(){
        $('.feature_list').children('li').on('click',function(e){
            var el = this;
            if($('.feature_list').find('li.active')){
                $('div#'+$('.feature_list').find('li.active').attr('id')).css('display','none');
                $('.feature_list').find('li.active').removeClass();
            }
            $(el).addClass('active '+$(el).attr('id')+'_bai');
            $('div#'+$(el).attr('id')).css('display','block');
        });
    });
</script>

</div>
@stop