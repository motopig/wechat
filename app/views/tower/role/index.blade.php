@extends('EcdoSpiderMan::layouts.desktop.default')
@section('title')
用户角色
@stop
@section('styles')
<style>
<!--
    .table {
        table-layout: fixed;
    }
    
    .table td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
-->
</style>
@stop
@section('main')
  <section class="panel panel-default">
      <header class="panel-heading"><i class="icon-user"></i>&nbsp;客服管理</header>
  <div class="row wrapper">
      <div class="col-sm-5">
      @if ($chkPerm['add'])
        <!-- <button type="button" bolt-url="angel/role/add" bolt-modal="创建角色" bolt-modal-icon="icon-plus" class="boltClick btn btn-success btn-xs">
          创建角色
        </button> -->
        <button type="button" class="btn btn-success btn-xs">
          创建角色
        </button>
      @endif
      @if ($chkPerm['del'])
        <button type="button" bolt-url="angel/role/del" class="boltDelP btn btn-dark btn-xs">
          批量删除
        </button>
      @endif
      </div>
  </div>
    <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th style="width: 42px;">
              <a class="all-checkbox" href="###">
                <i class="fa fa-square-o"></i>
              </a>
            </th>
            <th class="col-sm-3 col-md-3">角色名称<i class="fa fa-sort fa-sort-p"></i></th>
            <th class="col-sm-4 col-md-4">说明<i class="fa"></i></th>
            <th class="col-sm-2 col-md-2">更新时间<i class="fa fa-sort fa-sort-p"></i></th>
            <th class="col-sm-2 col-md-2">操作</th>
          </tr>
        </thead>
        <tbody>
          @if (! empty($roles))
            @foreach ($roles as $role)
              <tr>
                <td>
                  <input class="drop-checkbox roleIds" type="checkbox" name="role_id" value="{{$role['id']}}">
                </td>
                <td>
                    {{$role['title']}}
                </td>
                <td>
                    {{$role['desc']}}
                </td>
                <td>
                    {{$role['updated_at']}}
                </td>
                <td>
                  <a data-toggle="tooltip" data-placement="bottom" data-original-title="查看" href="###" bolt-url="angel/role/detail" bolt-data="rid={{$role['id']}}" bolt-modal="角色明细" bolt-modal-icon="icon-eyeglasses" class="boltClick btn btn-info btn-xs">
                    <i class="icon-eyeglasses"></i>
                  </a>
                @if ($chkPerm['edit'])
                  <a data-toggle="tooltip" data-placement="bottom" data-original-title="编辑" href="###" bolt-url="angel/role/edit" bolt-data="rid={{$role['id']}}" bolt-modal="编辑角色" bolt-modal-icon="icon-note" class="boltClick btn btn-success btn-xs">
                    <i class="icon-note"></i>
                  </a>
                @endif
                @if ($chkPerm['del'])
                  <a data-toggle="tooltip" data-placement="bottom" data-original-title="删除" href="###" bolt-url="angel/role/del" bolt-data="rid={{$role['id']}}" class="btn btn-dark btn-xs boltDel">
                    <i class="icon-trash"></i>
                  </a>
                @endif
                </td>
              </tr>
             @endforeach
          @else
            <tr>
              <td colspan="5">暂无角色</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>
   <footer class="panel-footer">
      <div class="row">
        <div class="col-sm-4 hidden-xs">
          ( 第 {{$page['curPage']}} 页 / 共 {{$page['ttlPage']}} 页 )
        </div>
        <div class="col-sm-4 text-center"></div>
        <div class="col-sm-4 text-right text-center-xs">
        {{$page['links']}}
        </div>
      </div>
    </footer>
  </section>
@stop
@if ($chkPerm['del'])
@section('scripts')
<script>
(function() {
	$(document).ready(function() {
	    var boltDel = function(el) {
	        var _el = $(el);
	        var url = _el.attr("bolt-url");
	        var data = _el.attr("bolt-data");
	        var funcs = {
		        beforeSend: function() {
		            _el.addClass("disabled");
		        },
		        success: function(rs) {
		            if (rs === "success") {
				        _el.parents("tr").remove();
		            } else {
		                _el.removeClass("disabled");
		            }
		        },
		        error: function() {
		            _el.removeClass("disabled");
		        }
	        };
	        $.hell.fn.bolt(url, data, "GET", funcs);
	    }

	    var boltDelP = function(el) {
		    var _el = $(el);
		    var url = _el.attr("bolt-url");
		    var chkBx = $(".roleIds:checked");

		    if (chkBx.length < 1) {
			    return false;
		    }

		    var data = [];
		    chkBx.each(function(ind, row) {
			    data[ind] = "rid[]=" + $(row).val();
		    });
		    var strData = data.join("&");

		    var funcs = {
				beforeSend: function() {
					_el.addClass("disabled");
				},
				success: function(rs) {
					if (rs === "success") {
						window.location.reload();
					} else {
						_el.removeClass("disabled");
					}
				},
				error: function() {
					_el.removeClass("disabled");
				}
		    };
		    $.hell.fn.bolt(url, strData, "GET", funcs);
	    }

	    var init = function() {
	    	alertify.set({
	            labels : {
	                ok : "确认",
	                cancel : "取消"
	            },
	            delay : 5000,
	            buttonReverse : false,
	            buttonFocus : "ok"
	        });

	    	$(".boltDel").click(function() {
			    var el = this;
	    		alertify.confirm("确认删除？", function(rs) {
			        if (rs) {
				        boltDel(el);
			        }
		        });
		    });

		    $(".boltDelP").click(function() {
			    var el = this;
			    alertify.confirm("确认批量删除？", function(rs) {
				    if (rs) {
					    boltDelP(el);
				    }
			    });
		    });
	    }

	    init();
	});
})();
</script>
@stop
@endif