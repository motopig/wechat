
<script id="editor_section_shop" type="text/x-handlebars-template">
<div class="edit-right fn-left" id="J_editRight_shop" style="margin-top: 0px;">
    <div class="arrow-icon" title="箭头" id="arrow-shop"></div>
	  <p class="title">券面信息</p>

    <div class="form-group">
      <label class="col-lg-2 control-label">商家名称</label>
      <div class="col-lg-8">
        <p class="form-control-static">{{brand_name}}</p>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 control-label">商家Logo</label>
      <div class="col-lg-8">
      	<span class="appmsg_preview_msg">
        	<img src="{{logo_url}}">
      	</span>
      	<span class="help-block frm_tips">如商户信息需变更，请在卡券 <a href="{{setting_url}}">基础设置</a> 页更新。</span>
      </div>
    </div>

	  <div class="form-group">
      <label class="col-lg-2 control-label">卡券颜色</label>
      <div class="col-lg-8">
      	<div class="btn-group">
          <button class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
            <span class="card-bgcolor-hide">请选择</span>
            <span class="card-bgcolor-show" style="display:none;" data-val=""></span>
            &nbsp;<span class="caret"></span>
          </button>

          <ul class="dropdown-menu card-bgcolor clearfix color-select" style="padding:1px;">
          	{{#each color}}
          		<li class="js-card-bgcolor card-bgcolor-box" style="background-color: {{value}}" 
          		data-val="{{value}}" data-name="{{name}}"></li>
			       {{/each}}
          </ul>
        </div>
      </div>
    </div>

    {{#if type}}
      {{#if content}}
      <div class="form-group">
        <label class="col-lg-2 control-label">{{content.title}}</label>
        <div class="col-lg-8">
          {{#compare_shop content.type 'DISCOUNT'}}
          <input type="text" class="form-control coupons-coupons_setting" data-type="{{content.type}}"  
          maxlength="3" placeholder="请填写1-9.9之间的数字，精确到小数点后1位">
          {{else}}
          <input type="text" class="form-control coupons-coupons_setting" data-type="{{content.type}}"  
          placeholder="减免金额只能是大于0.01的数字">
          {{/compare_shop}}
        </div>
      </div>
      {{/if}}
    {{/if}}

    <div class="form-group">
      <label class="col-lg-2 control-label">卡券标题</label>
      <div class="col-lg-8">
        <input type="text" class="form-control coupons-title" maxlength="9" placeholder="卡券标题长度不超过9个字符">
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 control-label">副标题 <br><span class="frm_tips">(选填)</span></label>
      <div class="col-lg-8">
        <input type="text" class="form-control coupons-sub_title" maxlength="18" placeholder="卡券副标题长度不超过18个字符">
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">开始时间</label>
      <div class="col-sm-5">
        <div class="input-group">
          <input type="text" class="form-control date coupons-begin_at" onfocus=this.blur()>
          <span class="input-group-btn">
            <button class="btn btn-default" type="button" onfocus=this.blur()>
              <i class="fa fa-calendar"></i>
            </button>
          </span>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">结束时间</label>
      <div class="col-sm-5">
        <div class="input-group">
          <input type="text" class="form-control date coupons-end_at" onfocus=this.blur()>
          <span class="input-group-btn">
            <button class="btn btn-default" type="button" onfocus=this.blur()>
              <i class="fa fa-calendar"></i>
            </button>
          </span>
        </div>
      </div>
    </div>
</div>
</script>
