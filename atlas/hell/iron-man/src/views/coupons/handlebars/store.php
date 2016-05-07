
<script id="editor_section_store" type="text/x-handlebars-template">
<div class="edit-right fn-left" id="J_editRight_store" style="margin-top: 254px;">
    <div class="arrow-icon" title="箭头" id="arrow-store"></div>
	  <p class="title">服务信息</p>

    <div class="form-group">
      <label class="col-sm-2 control-label">适用门店</label>
      <div class="col-sm-10">
        <div class="radio i-checks">
          <label>
            <input type="radio" class="coupons-location_id_list" name="location_id_list" value="store" checked>
            <i></i>
            指定门店适用 
            <span class="frm_tips">
              (<a href="javascript:void(0);" class="bolt-modal-click" data-type="store">添加适用门店</a>)
            </span>
          </label>
        </div>

        <div class="store-look">
          <section class="panel panel-default portlet-item" style="margin-bottom: 10px;">
              <ul class="list-group alt" id="coupons-group-item"></ul>
            </section>
        </div>

        <div class="radio i-checks">
          <label>
            <input type="radio" class="coupons-location_id_list" name="location_id_list" value="all">
            <i></i>
            全部门店适用
          </label>
        </div>

        <div class="radio i-checks">
          <label>
            <input type="radio" class="coupons-location_id_list" name="location_id_list" value="null">
            <i></i>
            无指定门店
          </label>
        </div>
      </div>
    </div>
</div>
</script>
