@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<link href="{{asset('atlas/hell/bat-man/css/entityshop.css')}}" rel="stylesheet" />

<section class="panel panel-default">
    <div class="panel-body">
      <form class="form-horizontal form-es" method="post" action="{{ URL::to('angel/entityshop/crEntityShopDis') }}">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
        <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
        <div class="graphics-img-url" data-url="{{ URL::to('angel/wechat/graphics/graphicsImageUrl') }}"></div>

        <h3 class="frm_title">基本信息 
          <span class="frm_title_dec" style="color:#f84040;">基本信息同步微信后不可修改</span>
        </h3>

        <div class="form-group">
          <label class="col-sm-2 control-label">门店名</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-business_name" placeholder="门店名不得含有区域地址信息（如，上海市XXX公司）">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">分店名 (选填)</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-branch_name" placeholder="分店名不得含有区域地址信息（如，“上海国际饭店”中的“上海”）">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">门店类目</label>

          <div class="col-sm-10">
            <div class="row">
              <div class="prov-city-dist" style="margin-left:15px;">
                <select name="categories" class="categories-select es-categories form-control m-b" style="width:100px;">
                  @foreach ($category['main'] as $k => $v)
                    <option value="{{$k}}">{{$v}}</option>
                  @endforeach
                </select>
              </div>

              <div class="prov-city-dist sub-select">
                <select name="sub" class="es-sub form-control m-b" style="width:100px;">
                  @foreach ($category['sub'][0] as $k => $v)
                    <option value="{{$k}}">{{$v}}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">门店地址</label>

          <div class="col-sm-10">
            <div class="row" id="cityselsct" data-url="{{URL::to('/')}}">
              <div class="prov-city-dist" style="margin-left:15px;">
                <select name="province" class="form-control m-b prov" style="width:100px;"></select>
              </div>

              <div class="prov-city-dist">
                <select name="city" class="form-control m-b city" style="width:100px;"></select>
              </div>

              <div class="prov-city-dist">
                <select name="district" class="form-control m-b dist" style="width:100px;"></select>
              </div>
            </div>

            <input type="text" class="form-control es-address" placeholder="输入详细地址，请勿重复填写省市区信息" style="width:69%;">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">门店纬度</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-latitude" placeholder="火星坐标；如，25.097486">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">门店经度</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-longitude" placeholder="火星坐标；如，115.32375">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="line-top"></div>
        <h3 class="frm_title">服务信息 
          <span class="frm_title_dec">该部分为公共编辑信息，每个添加了该门店的商户均可提交修改意见</span>
        </h3>

        <div class="form-group">
            <label class="col-sm-2 control-label">门店图片 (选填)</label>
            <div class="col-sm-10">
              <p class="frm_tips">像素必须为640*340像素，支持.jpg .jpeg .bmp .png格式，大小不超过2M；第一张默认为门店logo</p>
              <div id="js_upload_wrp">
                <div class="img_upload_wrp group">
                  <div class="img_upload_box">
                    <a class="img_upload_box_oper bolt-modal-click" data-type="image" href="javascript:">
                      <i class="icon20_common add_gray">
                        上传
                      </i>
                    </a>
                  </div>
                </div>
              </div>
           </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">门店电话</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-telephone" placeholder="固定电话需加区号；区号、分机号均用“-”连接">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">营业时间 (选填)</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-open_time" placeholder="如，10:00-21:00">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">人均价格 (选填)</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-avg_price" placeholder="大于零的整数，须如实填写，默认单位为人民币">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">推荐 (选填)</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-recommend" placeholder="如，推荐菜，推荐景点，推荐房间">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">特色服务 (选填)</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-special" placeholder="如，免费停车，WiFi">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">简介 (选填)</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-desc" maxlength="6" 
            placeholder="在“附近的人”展示，不超过6个字，如上方截图示例中的“满99送咖啡”字样。">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">门店签名 (选填)</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-signature" placeholder="对品牌或门店的简要介绍">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
            <button type="button" class="btn btn-success es-click" data-id="">保存</button>&nbsp;
            <a href="{{ URL::to('angel/entityshop') }}">
              <button type="button" class="btn btn-default">返回</button>
            </a>
          </div>
        </div>
      </form>
    </div>
</section>

@include('EcdoSpiderMan::layouts.modal.dialog')
<script src="{{asset('assets/universe/js/jquery.cityselect.js')}}"></script>
<script src="{{asset('atlas/hell/bat-man/js/entityshop.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
  $("#cityselsct").citySelect({
    prov:"上海",
    city:"黄浦区",
    nodata:"none"
  });

  $('.categories-select').change(function () {
    var id = $(this).val();

    if (id != '') {
      var sub = JSON.parse('{{$categoryjson}}').sub[id];
      var html = '<select name="sub" class="es-sub form-control m-b" style="width:100px;">';

      for(var val in sub) {
        html += '<option value="' + val + '">' + sub[val] + '</option>';
      }

      html += '</select>';
      $('.sub-select').empty().append(html);
    }
  });
});
</script>
@stop
