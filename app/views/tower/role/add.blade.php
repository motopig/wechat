@extends('EcdoSpiderMan::layouts.modal.default')
@section('title')
用户角色
@stop
@section('main')
<section class="panel panel-default">
  <div class="bolt-response-error"></div>
  <div class="bolt-response-success"></div>
  <div class="panel-body">
    <form method="post" action="{{ url('angel/role/doAdd') }}" class="form-horizontal frmRole">
        <div class="form-group">
          <div class="col-lg-2">
            <label class="control-label">角色名称</label>
          </div>
          <div class="col-lg-4">
            <input type="text" name="title" class="form-control" data-required="true" placeholder="输入名称">
            <span class="help-block"></span>
          </div>
        </div>
        <div class="line b-b line-lg pull-in"></div>
@foreach ($perms as $group)
    @if (++ $i > 1)
        <div class="line line-dashed b-b line-lg pull-in"></div>
    @endif
        <div class="form-group row">
            <div class="col-lg-2">
                <div data-toggle="button">
                    <label class="btn btn-sm btn-success clkAll"  data-toggle="tooltip" data-original-title="{{$group['desc']}}">
                         {{$group['title']}}
                    </label>
                </div>
            </div>
            <div class="col-lg-10 clkOpts">
                <div data-toggle="buttons">
                @foreach ($group['perms'] as $row)
                    <label class="btn btn-sm btn-info" data-toggle="tooltip" data-original-title="{{$row['desc']}}">
                        <input type="checkbox" name="perms[]" value="{{$row['id']}}"><i class="fa fa-square text"></i><i class="fa fa-check-square text-active"></i> {{$row['title']}}
                    </label>
                @endforeach
                </div>
            </div>
        </div>
@endforeach
        <div class="line b-b line-lg pull-in"></div>
          <div class="form-group row">
          <div class="col-lg-2">
            <label class="control-label">说明</label>
          </div>
          <div class="col-lg-8">
            <textarea name="desc" class="form-control" rows="3" placeholder="输入说明"></textarea>
          </div>
          </div>
        <div class="line b-b line-lg pull-in"></div>
        <div class="form-group">
          <div class="col-lg-4 col-lg-offset-2">
            <button type="button" class="btn btn-success boltClick" bolt-form="frmRole" bolt-func-success="addRole" >确认</button>&nbsp;
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
          </div>
        </div>
    </form>
  </div>
</section>
@stop
@section('styles')
<script>
(function() {
	$(document).ready(function() {
	    $(".clkAll").click(function() {
		    var _this = $(this);
		    var opts = _this.parents(".row").find(".clkOpts .btn");
		    if (_this.hasClass("active")) {
		        opts.removeClass("active");
		        opts.children("input:checkbox").prop("checked", false);
		    } else {
		        opts.addClass("active");
		        opts.children("input:checkbox").prop("checked", true);
		    }
	    });

	    var addRole = function(rs) {
	        if (rs === "success") {
		        $(".boltClick").addClass("disabled");
		        var alert = {body: {html: "创建成功"}, bg: {"color": "success"}};
		        var objAlert = $.hell.fn.genAlert(alert);
		        $(".bolt-response-error").html("");
		        $(".bolt-response-success").html(objAlert);
		        setTimeout(function() {
			        window.location.reload();
			    }, 3000);
	        } else {
		        var alert = {body: {html: rs}, bg: {"color": "danger"}};
		        var objAlert = $.hell.fn.genAlert(alert);
		        $(".bolt-response-error").html("");
		        $(".bolt-response-error").append(objAlert);
	        }
	    }

	    $.hell.fn.regFunc("addRole", addRole);
	});
})();
</script>
@stop