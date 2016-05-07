	<div class="navbar-right ">
		<ul class="nav navbar-nav m-n hidden-xs nav-user user">
    		
            @if(Auth::angel()->get())
			@if (! Session::get(Auth::angel()->get()->encrypt_id . '_territory_info') || 
			Session::get(Auth::angel()->get()->encrypt_id . '_territory_info')->validator == 'false')
				<li class="hidden-xs" style="display:none;">
		        	<a href="###" class="dropdown-toggle lt" data-toggle="dropdown">
		            	<i class="icon-bell"></i>
		            	<span class="badge badge-sm up bg-danger">1</span>
		        	</a>
		        	
		        	<section class="dropdown-menu aside-xl animated fadeInUp">
		            	<section class="panel bg-white">
		            		<div class="panel-heading b-light bg-light">
		                		<span class="fa fa-volume-up"></span> &nbsp;
		                		<strong>您有 <span>1</span> 个通知</strong>
		                	</div>

		                	<div class="list-group-alt">
		                  		<a href="###" class="media list-group-item">
			                    	<span class="media-body block m-b-none">
			                      		系统通知 <br>
			                      		<small class="text-muted">还未进行企业账户认证</small>
			                    	</span>
		                  		</a>
		                	</div>

			                <div class="panel-footer text-sm">
			                 	<a href="###" class="pull-right"><i class="icon-arrow-right"></i></a>
			                	<a href="###" data-toggle="class:show animated fadeInRight">查看所有通知</a>
			                </div>
		              	</section>
		            </section>
		    	</li>
	    	@endif
            @endif

			<li class="dropdown" style="margin-right:20px;">
				<a href="###" class="dropdown-toggle bg clear" data-toggle="dropdown">
					<span @if (Auth::angel()->check()) title="{{Auth::angel()->get()->email}}" @endif 
					class="thumb-sm avatar pull-right m-t-n-sm m-b-n-sm m-l-sm" style="width:26px;top:7px;">
						<img @if (Auth::angel()->check()) @if (Session::get(Auth::angel()->get()->encrypt_id . '_angel_info_head')) src="{{asset(Session::get(Auth::angel()->get()->encrypt_id . '_angel_info_head'))}}"
						@else src="{{{ asset('/admin.png') }}}" @endif @endif alt="..." />
					</span>
					<!-- @if (Auth::angel()->check()) {{Auth::angel()->get()->email}} @endif &nbsp; -->
					<b class="caret"></b>
				</a>
				<ul class="dropdown-menu animated fadeInRight">
					<li>
						<span class="arrow top"></span>
						<a bolt-url="angel/upAccount" href="{{URL::to('angel/order/index')}}" class="boltClick">
							<i class="fa fa-user"></i>&nbsp;&nbsp;我的账单
						</a>
					</li>
					<li>
						<span class="arrow top"></span>
						<a bolt-url="angel/upAccount" href="{{URL::to('angel/account/edit')}}" class="boltClick">
							<i class="fa fa-user"></i>&nbsp;&nbsp;编辑资料
						</a>
					</li>
					
					<li class="divider"></li>
					<li>
						<a href="{{ URL::to('angel/logout') }}">
							<i class="fa fa-power-off"></i>&nbsp;退出
						</a>
					</li>
				</ul>
			</li>
		</ul>
	</div>