<script id="editor_section_item" type="text/x-handlebars-template">
  {{#each store}}
  <li class="list-group-item">
    <div class="media">
      <div class="pull-right" style="font-size:18px;">
        <a class="store-list" href="javascript:void(0);" data-id="{{id}}" title="删除">
          <i class="fa fa-trash-o"></i>
        </a>
      </div>
      <div class="media-body">
        <div>{{business_name}}</div>
      </div>
    </div>
  </li>
  {{/each}}
</script>
