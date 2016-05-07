@extends('EcdoSpiderMan::layouts.menu.default')

@section('main')
<div class="row">
	<div class="col-sm-10">
    	<form class="form-horizontal" method="post">
        	<section class="panel panel-default">
            	<header class="panel-heading">
            		<i class="icon-game-controller"></i> &nbsp;
                	<strong>新建云号</strong>
                </header>

                <div class="panel-body">
    				<input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />

                    <div class="form-group">
                    	<label class="col-sm-3 control-label">云号名称</label>
                    	<div class="col-sm-6">
                           <input type="text" name="name" class="form-control" placeholder="请输入云号名称">
                    	   <span class="help-block">{{{ $errors->first('name') }}}</span>
                        </div>
                    </div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>

                    <div class="form-group">
                    	<label class="col-sm-3 control-label">主营类目</label>
                    	<div class="col-sm-6">
                        	<div class="btn-group m-r">
                    			<button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                        			<span class="dropdown-label">请选择</span>
                        			&nbsp;<span class="caret"></span>
                      			</button>
                      			<ul class="dropdown-menu dropdown-select business-select">
                      				@foreach ($business as $k => $v)
                          			<li>
                          				<input type="radio" name="business" value="{{$k}}">
                          				<a href="###">{{$v}}</a>
                          			</li>
                          			@endforeach
                        			<li>
                        				<input type="radio" name="business" value="other">
                        				<a href="###">其他</a>
                        			</li>
                      			</ul>
                    		</div>

	                        <div class="btn-group m-r business-input" style="display:none;">
	                        	<input type="text" name="business_other" class="form-control business-text" placeholder="请输入自定义类目">
	                        </div>

                            <span class="help-block">{{{ $errors->first('business') }}}</span>
                            <span class="help-block">{{{ $errors->first('business_other') }}}</span>
                        </div>
                    </div>
                    <!-- <div class="line line-dashed b-b line-lg pull-in"></div>

                    <div class="form-group">
                    	<label class="col-sm-3 control-label">店铺别名</label>
                    	<div class="col-sm-6">
                    		<input type="text" name="byname" class="form-control" placeholder="是客户直接看到并访问的短域名">
                            <span class="help-block">{{{ $errors->first('byname') }}}</span>
                      	</div>
                    </div> -->
            	</div>

            	<footer class="panel-footer text-center bg-light lter">
                	<button type="submit" class="btn btn-success btn-s-xs">确认创建</button>

                    <a href="{{ URL::to('angel') }}">
                        <button type="button" class="btn btn-default btn-s-xs">取消</button>
                    </a>
            	</footer>
        	</section>
    	</form>
    </div>
</div>
<script src="{{{ asset('atlas/hell/spider-man/js/angel.desktop.js') }}}"></script>
@stop
