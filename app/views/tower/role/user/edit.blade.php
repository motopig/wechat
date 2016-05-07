@extends('EcdoSpiderMan::layouts.modal.default')
@section('title')
用户角色编辑
@stop
@section('main')
<section class="panel panel-default">
  <div class="bolt-response-error"></div>
  <div class="bolt-response-success"></div>
  <div class="panel-body">
    <form method="post" action="{{ url('angel/role/users/doEdit') }}" class="form-horizontal frmRoleUser">
        <input type="hidden" name="uid" value="{{$user['id']}}" />
        <div class="form-group">
          <div class="col-lg-2">
            <label class="control-label">账号邮箱</label>
          </div>
          <div class="col-lg-4">
           {{$user['email'] or ''}}
            <span class="help-block"></span>
          </div>
        </div>
        <div class="line b-b line-lg pull-in"></div>
        <div class="form-group row">
            <div class="col-lg-2">
                <label class="control-label">
                     角色列表
                </label>
            </div>
            <div class="col-lg-10">
                <div data-toggle="buttons">
                @foreach ($roles as $role)
                    <label class="btn btn-sm btn-info @if (! empty($role['checked'])) active @endif" data-toggle="tooltip" data-original-title="{{$role['desc']}}">
                        <input type="checkbox" name="roles[]" value="{{$role['id']}}" @if (! empty($role['checked'])) checked="checked" @endif><i class="fa fa-square text"></i><i class="fa fa-check-square text-active"></i> {{$role['title']}}
                    </label>
                @endforeach
                </div>
            </div>
        </div>
        <div class="line b-b line-lg pull-in"></div>
        <div class="form-group">
          <div class="col-lg-4 col-lg-offset-2">
            <button type="button" class="btn btn-success boltClick" bolt-form="frmRoleUser" bolt-func-success="editUserRole" >确认</button>&nbsp;
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
	    var editUserRole = function(rs) {
	        if (rs === "success") {
		        $(".boltClick").addClass("disabled");
		        var alert = {body: {html: "编辑成功"}, bg: {"color": "success"}};
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

	    $.hell.fn.regFunc("editUserRole", editUserRole);
	});
})();
</script>
@stop