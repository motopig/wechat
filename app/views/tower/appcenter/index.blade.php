@extends('EcdoSpiderMan::layouts.desktop.default')
@section('title')
应用中心
@stop
@section('main')
<div class="row wrapper">
    <div class="col-lg-12">

    <!-- <a href="javascript:void(0);" class="btn btn-md btn-success replace" 
    data-url="{{URL::to('angel/appCenter/replace')}}">
        <i class="icon-wrench"></i>&nbsp;
        检查更新
    </a>
    <h4 class="font-thin m-b"></h4> -->

    <section class="panel panel-default">
        <header class="panel-heading bg-light">
            <ul class="nav nav-tabs nav-justified">
                <li class="active">
                    <a href="#allStar" data-toggle="tab">全部</a>
                </li>
                <li>
                    <a href="#baseStar" data-toggle="tab">基础</a>
                </li>
                <li>
                    <a href="#optionStar" data-toggle="tab">可选</a>
                </li>
                <li>
                    <a href="#instStar" data-toggle="tab">已安装</a>
                </li>
            </ul>
        </header>
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane active" id="allStar">
                @if (empty($allStars))
                    暂无应用
                @endif
                @foreach ($allStars as $star)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        <div class="row">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" 
                            @if (isset($star['id'])) href="#all-{{$star['id']}}" @else href="javascript:void(0);" @endif>
                                <div class="col-sm-9">{{$star['title']}}</div>
                            </a>
                            <div class="col-sm-3">
                                @if (empty($star['inst_at']))
                                @if ($chkPerm['inst'])
                                <button class="btn btn-success btn-xs btnInst pull-right" app="{{$star['star']}}" app-name="{{$star['title']}}">
                                    <i class="fa fa-cog"></i>&nbsp; 安装
                                </button>
                                @endif
                                @else
                                <!-- 安装时间：{{$star['inst_at']}} -->
                                <button class="btn btn-success btn-xs replace pull-right" 
                                data-url="{{URL::to('angel/appCenter/replace')}}" data-app="{{$star['star']}}">
                                    <i class="icon-wrench"></i>&nbsp; 更新
                                </button>
                                @endif
                            </div>
                        </div>
                        </div>
                        <div @if (isset($star['id'])) id="all-{{$star['id']}}" @endif class="panel-collapse collapse">
                            <div class="panel-body text-sm">
                            @if (empty($star['icon']))
                                <i class="fa fa-star fa-3x"></i>
                            @else
                                <img src="{{$star['icon']}}">
                            @endif
                                <div class="row">
                                    <div class="col-xs-6">
                                      <h4>{{$star['title']}}</h4>
                                      <p></p>
                                      <p>
                                        {{$star['desc']}}
                                      </p>
                                      <p>
                                        @if (! empty($star['depend']))
                                        需要应用：{{$star['dependTitles']}}
                                        @endif
                                      </p>
                                      <p>
                                        @if (! empty($star['conflict']))
                                        冲突应用：{{$star['conflictTitles']}}
                                        @endif
                                      </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
                <div class="tab-pane" id="baseStar">
                @if (empty($baseStars))
                    暂无基础应用
                @endif
                @foreach ($baseStars as $star)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        <div class="row">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" 
                            @if (isset($star['id'])) href="#base-{{$star['id']}}" @else href="javascript:voide(0);" @endif>
                                <div class="col-sm-9">{{$star['title']}}</div>
                            </a>
                            <div class="col-sm-3">
                                @if (empty($star['inst_at']))
                                @if ($chkPerm['inst'])
                                <button class="btn btn-success btn-xs btnInst pull-right" app="{{$star['star']}}" app-name="{{$star['title']}}">
                                    <i data-toggle="tooltip" data-placement="top" title="安装"  class="fa fa-cog"></i>
                                </button>
                                @endif
                                @else
                                <!-- 安装时间：{{$star['inst_at']}} -->
                                <button class="btn btn-success btn-xs replace pull-right" 
                                data-url="{{URL::to('angel/appCenter/replace')}}" data-app="{{$star['star']}}">
                                    <i class="icon-wrench"></i>&nbsp; 更新
                                </button>
                                @endif
                            </div>
                        </div>
                        </div>
                        <div @if (isset($star['id'])) id="base-{{$star['id']}}" @endif class="panel-collapse collapse">
                            <div class="panel-body text-sm">
                            @if (empty($star['icon']))
                                <i class="fa fa-star fa-3x"></i>
                            @else
                                <img src="{{$star['icon']}}">
                            @endif
                                <div class="row">
                                    <div class="col-xs-6">
                                      <h4>{{$star['title']}}</h4>
                                      <p></p>
                                      <p>
                                        {{$star['desc']}}
                                      </p>
                                      <p>
                                        @if (! empty($star['depend']))
                                        需要应用：{{$star['dependTitles']}}
                                        @endif
                                      </p>
                                      <p>
                                        @if (! empty($star['conflict']))
                                        冲突应用：{{$star['conflictTitles']}}
                                        @endif
                                      </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
                <div class="tab-pane" id="optionStar">
                @if (empty($optStars))
                    暂无可选应用
                @endif
                @foreach ($optStars as $star)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        <div class="row">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" 
                            @if (isset($star['id'])) href="#opt-{{$star['id']}}" @else href="javascript:voide(0);" @endif>
                                <div class="col-sm-9">{{$star['title']}}</div>
                            </a>
                            <div class="col-sm-3">
                                @if (empty($star['inst_at']))
                                <button class="btn btn-success btn-xs btnInst pull-right" app="{{$star['star']}}" app-name="{{$star['title']}}">
                                    <i data-toggle="tooltip" data-placement="top" title="安装"  class="fa fa-cog"></i>
                                </button>
                                @else
                                <!-- 安装时间：{{$star['inst_at']}} -->
                                <button class="btn btn-success btn-xs replace pull-right" 
                                data-url="{{URL::to('angel/appCenter/replace')}}" data-app="{{$star['star']}}">
                                    <i class="icon-wrench"></i>&nbsp; 更新
                                </button>
                                @endif
                            </div>
                        </div>
                        </div>
                        <div @if (isset($star['id'])) id="opt-{{$star['id']}}" @endif class="panel-collapse collapse">
                            <div class="panel-body text-sm">
                            @if (empty($star['icon']))
                                <i class="fa fa-star fa-3x"></i>
                            @else
                                <img src="{{$star['icon']}}">
                            @endif
                                <div class="row">
                                    <div class="col-xs-6">
                                      <h4>{{$star['title']}}</h4>
                                      <p></p>
                                      <p>
                                        {{$star['desc']}}
                                      </p>
                                      <p>
                                        @if (! empty($star['depend']))
                                        需要应用：{{$star['dependTitles']}}
                                        @endif
                                      </p>
                                      <p>
                                        @if (! empty($star['conflict']))
                                        冲突应用：{{$star['conflictTitles']}}
                                        @endif
                                      </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
                <div class="tab-pane" id="instStar">
                @if (empty($instStars))
                    暂无已安装应用
                @endif
                @foreach ($instStars as $star)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        <div class="row">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" 
                            @if (isset($star['id'])) href="#inst-{{$star['id']}}" @else href="javascript:void(0);" @endif>
                                <div class="col-sm-9">{{$star['title']}}</div>
                            </a>
                            <div class="col-sm-3">
                                @if (empty($star['inst_at']))
                                @if ($chkPerm['inst'])
                                <button class="btn btn-success btn-xs btnInst pull-right" app="{{$star['star']}}" app-name="{{$star['title']}}">
                                    <i data-toggle="tooltip" data-placement="top" title="安装"  class="fa fa-cog"></i>
                                </button>
                                @endif
                                @else
                                <!-- 安装时间：{{$star['inst_at']}} -->
                                <button class="btn btn-success btn-xs replace pull-right" 
                                data-url="{{URL::to('angel/appCenter/replace')}}" data-app="{{$star['star']}}">
                                    <i class="icon-wrench"></i>&nbsp; 更新
                                </button>
                                @endif
                            </div>
                        </div>
                        </div>
                        <div @if (isset($star['id'])) id="inst-{{$star['id']}}" @endif class="panel-collapse collapse">
                            <div class="panel-body text-sm">
                            @if (empty($star['icon']))
                                <i class="fa fa-star fa-3x"></i>
                            @else
                                <img src="{{$star['icon']}}">
                            @endif
                                <div class="row">
                                    <div class="col-xs-6">
                                      <h4>{{$star['title']}}</h4>
                                      <p></p>
                                      <p>
                                        {{$star['desc']}}
                                      </p>
                                      <p>
                                        @if (! empty($star['depend']))
                                        需要应用：{{$star['dependTitles']}}
                                        @endif
                                      </p>
                                      <p>
                                        @if (! empty($star['conflict']))
                                        冲突应用：{{$star['conflictTitles']}}
                                        @endif
                                      </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </section>
    </div>
</div>
<div class="hellCsrfToken" csrfToken="{{Session::token()}}"></div>
<div class="hellCsrfGuid" CsrfGuid="{{Session::get('guid')}}" /></div>
@stop
@if ($chkPerm['inst'])
@section('scripts')
<script>
(function() {
    $(document).ready(function() {
        var btnInst = {
            selector : ".btnInst",
            getBtn : function() {
                return $(this.selector);
            },
            toReady : function() {
                var btn = this.getBtn();
                btn.prop("disabled", false);
                btn.children("i").removeClass("fa-spin fa-spinner").addClass("fa-cog");
            },
            toBusy : function() {
                var btn = this.getBtn();
                btn.prop("disabled", true);
                btn.children("i").removeClass("fa-cog").addClass("fa-spin fa-spinner");
            }
        };

        var btnApply = {
            toReady : function(btn) {
                var _this = $(btn);
                _this.prop("disabled", false);
                _this.html("确定");
            	$("[data-dismiss='alert']").prop("disabled", false);
            },
            toBusy : function(btn) {
                var _this = $(btn);
            	_this.prop("disabled", true);
            	_this.html("");
            	var i = $("<i>", {"class" : "fa fa-spin fa-spinner"});
            	_this.append(i);
            	$("[data-dismiss='alert']").prop("disabled", true);
            }
        };
        
        var appCenter = {
            app : "",
            appName : "",
            delay : 2000,
        	inst : function() {
            	var _this = $(this);
        	    btnInst.toBusy();
        	    appCenter.app = _this.attr("app");
        	    appCenter.appName = _this.attr("app-name");
        	    appCenter.showNotice(_this.parents(".panel-default")[0]);
        	},
        	getBoltNotice : function() {
        		var p = $(".alert .boltNotice");
                if (p.length > 0) {
                    p.remove();
                }
                
                p = $("<p>", {"class" : "boltNotice"});
                p.appendTo($(".alert"));

                return p;
        	},
        	toInst : function() {
            	var btn = this;
            	btnApply.toBusy(btn);
            	var url = "angel/appCenter/install";
            	var param = "star=" + appCenter.app;
            	var method = "get";
            	var funcs = {
                    success : function(resp) {
                        var p = appCenter.getBoltNotice();
                        
                        if (resp === "success") {
                            p.addClass("bg-success");
                            p.html("安装成功，正在刷新页面");
                            setTimeout(function() {window.location.reload()}, appCenter.delay);
                        } else {
                        	setTimeout(function() {btnApply.toReady(btn)}, appCenter.delay);
                        	p.addClass("bg-warning");
                        	p.html("安装失败，请稍后重试");
                        }
                    },
                    error : function(resp) {
                    	setTimeout(function() {btnApply.toReady(btn)}, appCenter.delay);
                        var p = appCenter.getBoltNotice();
                        p.addClass("bg-danger");
                        p.html("由于网络问题，安装出错");
                    }
            	};
            	$.hell.fn.bolt(url, param, method, funcs);
        	},
        	showNotice : function(target) {
            	var df = {
                    bg : {
                        color : "success"
                    },
                	title : {
                    	html : "安装信息提示"
                    },
                    body : {
                        html : "确认安装应用" + appCenter.appName + "？"
                    },
                    apply : {
                        color : "success",
                        click : appCenter.toInst,
                        html : "安装"
                    },
                    cancel : {
                        html : "取消"
                    }
            	};
        	    var alert = $.hell.fn.genAlert(df);
        	    alert.on("closed.bs.alert", function() {
            	    btnInst.toReady();
        	    });
        	    $(target).after(alert);
        	}
        };

        $(".btnInst").click(appCenter.inst);
    });

    $(document).ready(function() {
        $('.replace').click(function() {
            reset = function () {
                alertify.set({
                     labels : {
                         ok     : "确认",
                         cancel : "取消"
                     },
                     delay : 5000,
                     buttonReverse : false,
                     buttonFocus   : "ok"
                });
            };
            
            var data = new FormData();
            data.append('star', $(this).attr('data-app'));
            data.append('csrf_token', $('.hellCsrfToken').attr('csrfToken'));
            data.append('csrf_guid', $('.hellCsrfGuid').attr('CsrfGuid'));
            
            $.ajax({
               url: $(this).attr('data-url'),
               type: 'POST',
               data: data,
               contentType: false,
               processData: false,
               
               success:function(result) {
                 var data = jQuery.parseJSON(result);

                 if (data.errcode == 'success') {
                   alertify.success(data.errmsg);
                   setTimeout(function() {
                      window.location.href = data.url;
                   }, 2000);
                 }
               }
            });
        });
    });
})();
</script>
@stop
@endif