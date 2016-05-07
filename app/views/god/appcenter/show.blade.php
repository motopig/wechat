<div class="panel">
<h4 class="font-thin padder"></h4>
<form action="god/appCenter/edit" method="post" class="infoFrm">
@if (! empty($star['id'])) <input type="hidden" name="star[id]" value="{{$star['id']}}">@endif
<ul class="list-group">
  <li class="list-group-item">
      <strong>名称</strong>
      <span>
        <input type="text" name="star[title]" value="{{{$star['title']}}}" placeholder="应用名称" class="input-xs no-border">
      </span>
  </li>
  <li class="list-group-item">
      <strong>应用</strong>
      <span>
        {{{$star['star']}}}
        <input type="hidden" name="star[star]" value="{{{$star['star']}}}">
        <input type="hidden" name="star[author]" value="{{{$star['author']}}}">
        <input type="hidden" name="star[company]" value="{{{$star['company'] ? $star['company'] : 'hell'}}}">
        <input type="hidden" name="star[conflict]" value="{{{$star['conflict'] ? $star['conflict'] : ''}}}">
        <input type="hidden" name="star[depend]" value="{{{$star['depend'] ? $star['depend'] : ''}}}">
      </span>
  </li>
  <li class="list-group-item">
      <strong>类型</strong>
      <span data-toggle="buttons" class="btn-group">
        <label class="btn btn-dark rounded @if ($star['type'] == 'base')) active @endif">
            <input type="radio" name="star[type]" value="base" placeholder="基本" class="input-xs no-border" @if ($star['type'] == 'base')) checked="checked" @endif>
            基本
            <i class="fa fa-check text-active"></i>
        </label>
        <label class="btn btn-dark rounded @if ($star['type'] != 'base')) active @endif"">
            <input type="radio" name="star[type]" value="option" placeholder="可选" class="input-xs no-border" @if ($star['type'] != 'base')) checked="checked" @endif>
            <i class="fa fa-check text-active"></i>
            可选
        </label>
      </span>
  </li>
  <li class="list-group-item">
      <strong>价格</strong>
      <span>
        <input type="number" name="star[price]" step="0.01" min="0.00" max="1000000" maxlength="6" value="{{{$star['price'] or 0.00}}}" placeholder="价格" class="input-xs no-border">
      </span>
  </li>
  <li class="list-group-item">
      <strong>上架状态</strong>
      <span data-toggle="buttons" class="btn-group">
        <label class="btn btn-dark rounded @if (! empty($star['on_sale']) && $star['on_sale'] == 'Y')) active @endif">
            <input type="radio" name="star[on_sale]" value="Y" placeholder="上架" class="input-xs no-border" @if (! empty($star['on_sale']) && $star['on_sale'] == 'Y')) checked="checked" @endif>
            上架
            <i class="fa fa-check text-active"></i>
        </label>
        <label class="btn btn-dark rounded @if (empty($star['on_sale']) || $star['on_sale'] != 'Y')) active @endif">
            <input type="radio" name="star[on_sale]" value="N" placeholder="下架" class="input-xs no-border" @if (empty($star['on_sale']) || $star['on_sale'] != 'Y')) checked="checked" @endif>
            <i class="fa fa-check text-active"></i>
            下架
        </label>
      </span>
  </li>
  <li class="list-group-item">
      <strong>描述</strong>
      <span>
        <textarea name="star[desc]" placeholder="应用描述" class="form-control input-xs no-border" cols="30" rows="5" style="resize: none;">{{{$star['desc']}}}</textarea>
      </span>
  </li>
  <li class="list-group-item">
        <button type="button" bolt-form="infoFrm" bolt-func-success="editRs" class="btn btn-s-md btn-info boltClick">保存</button>
  </li>
</ul>
</div>
</form>
<script>
(function() {
	var editRs = function(rs) {
	    if (rs === "success") {
	    	$(".showDetail").addClass("hide");
	        $(".showDetail .scrollable").html("");
	    }
	}
	
	$.hell.fn.regFunc("editRs", editRs);
})();
</script>