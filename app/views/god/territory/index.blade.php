@extends('god.layouts.default.frame')
@section('main')
<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title="">
			<h2>
				<i class="fa fa-facebook-sign"></i>
				<span class="break"></span>
         		公司列表
			</h2>
			<div class="box-icon">
				<a href="###" title="筛选"><i class="fa fa-search"></i></a>
				<a href="###" title="添加"><i class="fa fa-pencil"></i></a>
				<a href="###" title="删除"><i class="fa fa-trash "></i></a>
			</div>
		</div>
		<div class="box-content">
			<table class="table table-striped table-bordered bootstrap-datatable datatable">
			  <thead>
				  <tr>
				  	  <th>
            		  	<a href='javascript:void(0);' class="checkAll">
            		  		<i class="fa fa-square-o"></i>
            		  	</a>
          			  </th>
					  <th>公司名称</th>
					  <th>启用状态</th>
					  <th>更新时间</th>
					  <th>操作</th>
				  </tr>
			  </thead>
			  <tbody>
			  	@foreach ($territory as $t)
				<tr>
					<td>
			            <span class="todo-actions checkMany">
							<a href="javascript:void(0);">
								<i class="fa fa-square-o" id="{{$t['encrypt_id']}}"></i>
							</a>
						</span>
		          	</td>
					<td>
						{{$t['company']}}
						<span class="company_info">
							<span class="box-icon">
								<a href="###" class="btn-minimize" title="店铺简要">
									<i class="fa fa-chevron-down"></i>
								</a>
							</span>
						</span>
						<div class="box-content" style="display:none;">
							<ul class="dashboard-list">
								@foreach ($t['tower'] as $tt)
									<li>
										<span class="title" title="店铺名称">{{$tt['name']}}</span>
										<strong> | </strong>
										<span class="title" title="启用状态">
											@if ($tt['disabled'] == 'false')
												已启用
											@else
												已禁用
											@endif
										</span>
									</li>
								@endforeach
							</ul>
						</div>
					</td>
					<td class="center">
						@if ($t['disabled'] == 'false')
							<span class="label label-success">已启用</span>
						@else
							<span class="label label-inverse">已禁用</span>
						@endif
					</td>
					<td>{{$t['updated_at']}}</td>
					<td class="center">
						<a class="btn btn-success" href="###" title="查看">
							<i class="fa fa-zoom-in "></i>  
						</a>
						<a class="btn btn-info" href="###" title="编辑">
							<i class="fa fa-edit "></i>  
						</a>
						<a class="btn btn-danger" href="###" title="删除">
							<i class="fa fa-trash "></i> 
						</a>
					</td>
				</tr>
				@endforeach
			  </tbody>
		  </table>            
		</div>
	</div>
</div>
@stop
