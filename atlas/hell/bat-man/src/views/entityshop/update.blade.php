@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<link href="{{asset('atlas/hell/bat-man/css/entityshop.css')}}" rel="stylesheet" />

<section class="panel panel-default">
    <div class="panel-body">
      <form class="form-horizontal form-es" method="post" action="{{ URL::to('angel/entityshop/upEntityShopDis') }}">
        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
        <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
        <div class="graphics-img-url" data-url="{{ URL::to('angel/wechat/graphics/graphicsImageUrl') }}"></div>

        <h3 class="frm_title">基本信息 
          <span class="frm_title_dec" style="color:#f84040;">基本信息同步微信后不可修改</span>
        </h3>

        <div class="form-group">
          <label class="col-sm-2 control-label">门店名</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-business_name" value="{{$shop->business_name}}" @if ($shop->status > 0) disabled @endif 
            placeholder="门店名不得含有区域地址信息（如，上海市XXX公司）">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">分店名 (选填)</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-branch_name" value="{{$shop->branch_name}}" @if ($shop->status > 0) disabled @endif 
            placeholder="分店名不得含有区域地址信息（如，“上海国际饭店”中的“上海”）">
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
                    <option value="{{$k}}" @if ($k == $shop->categories[0]) selected @endif @if ($shop->status > 0) disabled @endif>{{$v}}</option>
                  @endforeach
                </select>
              </div>

              <div class="prov-city-dist sub-select">
                <select name="sub" class="es-sub form-control m-b" style="width:100px;">
                  @foreach ($category['sub'][$shop->categories[0]] as $k => $v)
                    <option value="{{$k}}" @if ($k == $shop->categories[1]) selected @endif @if ($shop->status > 0) disabled @endif>{{$v}}</option>
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
                <select name="province" class="form-control m-b prov" style="width:100px;">
                  @if ($shop->status > 0) <option value="{{$shop->province}}">{{$shop->province}}</option> @endif
                </select>
              </div>

              <div class="prov-city-dist">
                <select name="city" class="form-control m-b city" style="width:100px;">
                  @if ($shop->status > 0) <option value="{{$shop->city}}">{{$shop->city}}</option> @endif
                </select>
              </div>

              <div class="prov-city-dist">
                <select name="district" class="form-control m-b dist" style="width:100px;">
                  @if ($shop->status > 0) <option value="{{$shop->district}}">{{$shop->district}}</option> @endif
                </select>
              </div>
            </div>

            <input type="text" class="form-control es-address" value="{{$shop->address}}" @if ($shop->status > 0) disabled @endif 
            placeholder="输入详细地址，请勿重复填写省市区信息" style="width:69%;">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">门店纬度</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-latitude" value="{{$shop->latitude}}" @if ($shop->status > 0) disabled @endif 
            placeholder="火星坐标；如，25.097486">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">门店经度</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-longitude" value="{{$shop->longitude}}" @if ($shop->status > 0) disabled @endif 
            placeholder="火星坐标；如，115.32375">
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

                  @if ($shop->item->store_image_id)
                    @foreach ($shop->store_image as $k => $v)
                      <div data-sid="{{$v['store_image_id']}}" class="img_upload_box img_upload_preview_box js_edit_pic_wrp">
                        <img src="{{$v['store_image_url']}}" alt="">
                        <p class="img_upload_edit_area js_edit_area" style="display: none;">
                          <a href="javascript:;" class="icon18_common del_gray js_delete"></a>
                        </p>
                      </div>
                    @endforeach
                  @endif
                </div>
              </div>
           </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">门店电话</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-telephone" value="{{$shop->item->telephone}}" 
            placeholder="固定电话需加区号；区号、分机号均用“-”连接">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">营业时间 (选填)</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-open_time" value="{{$shop->item->open_time}}" 
            placeholder="如，10:00-21:00">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">人均价格 (选填)</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-avg_price" value="{{$shop->item->avg_price}}" 
            placeholder="大于零的整数，须如实填写，默认单位为人民币">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">推荐 (选填)</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-recommend" value="{{$shop->item->recommend}}" 
            placeholder="如，推荐菜，推荐景点，推荐房间">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">特色服务 (选填)</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-special" value="{{$shop->item->special}}" 
            placeholder="如，免费停车，WiFi">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">简介 (选填)</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-desc" maxlength="6" value="{{$shop->item->desc}}" 
            placeholder="在“附近的人”展示，不超过6个字，如上方截图示例中的“满99送咖啡”字样。">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <label class="col-sm-2 control-label">门店签名 (选填)</label>
          <div class="col-sm-7">
            <input type="text" class="form-control es-signature" value="{{$shop->item->signature}}" 
            placeholder="对品牌或门店的简要介绍">
          </div>
        </div>
        <div class="line line-dashed b-b line-lg pull-in"></div>

        <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
            <button type="button" class="btn btn-success es-click" data-id="{{$shop->id}}">保存</button>&nbsp;
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
  if ('{{$shop->status}}' == 0) {
    if ('{{$shop->district}}' == '') {
        $("#cityselsct").citySelect({
          prov:"{{$shop->province}}",
          city:"{{$shop->city}}",
          nodata:"none"
        });
    } else {
        $("#cityselsct").citySelect({
          prov:"{{$shop->province}}",
          city:"{{$shop->city}}",
          dist:"{{$shop->district}}"
        });
    }

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
  }
});
</script>
@stop
