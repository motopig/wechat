<script id="editor_prize" type="text/x-handlebars-template">
<section class="panel panel-default portlet-item prize-float">
  <header class="panel-heading">
    <ul class="nav nav-pills pull-right">
      <li>
        <a href="javascript:void(0);" class="{{class}}" data-number="{{number}}">
          <span class="label label-{{style}}">{{title}}</span>
        </a>
      </li>

      <li>
        <a href="#" class="panel-toggle text-muted">
          <i class="fa fa-caret-down text"></i>
          <i class="fa fa-caret-up text-active"></i>
        </a>
      </li>
    </ul>
    奖品设置
  </header>

  <section class="prize-panel panel-body collapse" data-pid="{{number}}">
    <div class="form-group">
      <label class="col-sm-2 control-label">奖品类型</label>
      <div class="col-sm-7">
        <label class="radio-inline i-checks">
          {{#each type}}
            <input class="ld-prize-radio" type="radio" name="type[{{../number}}]" value="{{key}}" checked><i></i> {{value}}
          {{/each}}
        </label>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">选择奖品</label>
      <div class="col-sm-7">
        <select name="content[{{number}}]" class="form-control m-b ld-prize-select" style="width:150px;">
          <option value="">请选择</option>
          {{#each coupons}}
            <option value="{{id}}">{{title}}</option>
          {{/each}}
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">中奖概率</label>
      <div class="col-sm-7">
        <input type="text" class="form-control ld-chance" name="chance[{{../number}}]" placeholder="不填此项，则概率默认为0">
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">奖品数量</label>
      <div class="col-sm-7">
        <input type="text" class="form-control ld-quantity" name="quantity[{{number}}]" placeholder="不填此项，则奖品默认为1件">
      </div>
    </div>
  </section>
</section>
</script>
