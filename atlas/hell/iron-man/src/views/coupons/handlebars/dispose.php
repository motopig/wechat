
<script id="editor_section_dispose" type="text/x-handlebars-template">
<div class="edit-right fn-left" id="J_editRight_dispose" style="margin-top: 143px;">
    <div class="arrow-icon" title="箭头" id="arrow-dispose"></div>
	  <p class="title">领券设置</p>

    <div class="form-group">
      <label class="col-lg-2 control-label">库存</label>
      <div class="col-lg-8">
        <input type="text" class="form-control coupons-quantity" placeholder="库存只能是大于0的整数">
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 control-label">领券限制 <br><span class="frm_tips">(选填)</span></label>
      <div class="col-lg-8">
        <input type="text" class="form-control coupons-use_limit" placeholder="领券限制只能是大于0的整数；如不填，则默认为1">
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">分享转赠</label>
      <div class="col-sm-10">
        <div class="checkbox i-checks">
          <label>
            <input type="checkbox" class="coupons-can_share" value="true" checked>
            <i></i>
            用户可以分享领券链接
          </label>
        </div>

        <div class="checkbox i-checks">
          <label>
            <input type="checkbox" class="coupons-can_give_friend" value="true" checked>
            <i></i>
            用户领券后可转赠其他好友
          </label>
        </div>
      </div>
    </div>

    <p class="titles">销券设置</p>
    <div class="form-group">
      <label class="col-sm-2 control-label">销券方式</label>
      <div class="col-sm-10">
        <div class="radio i-checks">
          <label>
            <input type="radio" class="coupons-code_type" name="code_type" value="CODE_TYPE_TEXT">
            <i></i>
            仅卡券号 <span class="frm_tips">(只显示卡券号，验证后可进行销券)</span>
          </label>
        </div>

        <div class="radio i-checks">
          <label>
            <input type="radio" class="coupons-code_type" name="code_type" value="CODE_TYPE_QRCODE">
            <i></i>
            二维码 <span class="frm_tips">(包含卡券信息的二维码，扫描后可进行销券)</span>
          </label>
        </div>

        <div class="radio i-checks">
          <label>
            <input type="radio" class="coupons-code_type" name="code_type" value="CODE_TYPE_BARCODE">
            <i></i>
            条形码 <span class="frm_tips">(包含卡券信息的条形码，扫描后可进行销券)</span>
          </label>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 control-label">操作提示</label>
      <div class="col-lg-8">
        <input type="text" class="form-control coupons-notice" maxlength="16" placeholder="操作提示不能为空且长度不超过16个字符">
        <span class="help-block frm_tips">线下卡券：建议引导用户到店出示卡券，由店员完成核销操作</span>
      </div>
    </div>
</div>
</script>
