@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<link href="{{{ asset('atlas/hell/hulk/css/graphics.css') }}}" rel="stylesheet" />
<script src="{{{ asset('atlas/hell/hulk/js/wechat.desktop.js') }}}"></script>

    <!-- <div class="msgContainer">
      <div class="newmessage">
        <div class="m_add" style="display:block;">+</div>
        
        <div class="m_select" style="display:none;">
          <a class="multi" href="{{ URL::to('angel/wechat/graphics/crSingel') }}">
            <i class="icon-doc"></i>
            创建单图文
          </a>
          
          <a class="multis" href="{{ URL::to('angel/wechat/graphics/crMany') }}">
            <i class="icon-docs"></i>
            创建多图文
          </a>
        </div>
      </div>
    </div> -->

  	<section class="panel panel-default">
    @include('EcdoHulk::layouts.tabs.graphics')

    <div class="row wrapper">
      <div class="col-sm-5 m-b-xs">
        <div class="btn-group">
          <button class="btn btn-success btn-xs dropdown-toggle" title="添加" data-toggle="dropdown">
            新建图文
            &nbsp;<span class="caret"></span>
          </button>

          <ul class="dropdown-menu">
            <li>
              <a href="{{ URL::to('angel/wechat/graphics/crSingel') }}">
                <i class="icon-doc"></i>&nbsp; 单图文
              </a>
            </li>

            <li>
              <a href="{{ URL::to('angel/wechat/graphics/crMany') }}">
                <i class="icon-docs"></i>&nbsp; 多图文
              </a>
            </li>
          </ul>
        </div>

        <a href="javascript:void(0);" class="btn btn-default btn-xs boltClick" bolt-url="angel/wechat/graphics/fiGraphics" bolt-modal="筛选图文" bolt-modal-icon="icon-target">
          筛选
        </a>
      </div>

      <div class="col-sm-4 m-b-xs"></div>
      <div class="col-sm-3">
        <div class="input-group">
          <input type="text" class="input-sm form-control bolt-search-input" placeholder="请输入图文标题">
          <span class="input-group-btn">
            <button type="button" class="btn btn-sm btn-default bolt-search" bolt-search-url="{{ URL::to('angel/wechat/graphics/seGraphics') }}" 
            data-toggle="tooltip" data-placement="bottom" data-original-title="搜索">
            <i class="icon-magnifier"></i>
            </button>
          </span>
        </div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>图文标题 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>更新时间 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>操作</th>
          </tr>
        </thead>

        <tbody>
        	@if (count($graphics) > 0)
        		@foreach ($graphics as $g)
        			<tr>
        				<td>
			        		<div class="ng">
						        <div class="ng-item">
						            <div class="td-cont with-label">
						                <span class="label label-success">
						                	@if (count($g->item) > 0) 多图文 @else 单图文 @endif
						                </span>
						                <span class="ng-title">
						                	{{$g->title}}
						                </span>
						                
						                @if (count($g->item) > 0)
							                <span class="pull-right graphics-title">
							                	<a href="###">
							                		<i class="fa fa-sort-down" graphics-title-id="{{$g->id}}"></i>
							                	</a>
							                </span>
						                @endif
						            </div>
						        </div>

						        @if (count($g->item) > 0)
							        <div class="ng-item graphics-title-fid_{{$g->id}}" style="display:none;">
							        	@foreach ($g->item as $i)
								            <div class="td-cont with-label" style="padding:2px;">
								                <span class="ng-title">{{$i->title}}</span>
								            </div>
							            @endforeach
							        </div>
						        @endif
					    	  </div>
			        	</td>

	        	    <td>
              			{{$g->updated_at}}
            		</td>

                <td>
                  <a data-toggle="tooltip" data-placement="bottom" data-original-title="查看" href="javascript:void(0);" 
                  bolt-url="angel/wechat/graphics/shGraphics" bolt-data="graphics_id={{$g->id}}" bolt-modal="查看图文" bolt-modal-icon="icon-eyeglasses" 
                  class="boltClick btn btn-info btn-xs">
                    查看
                  </a>

                  <a data-toggle="tooltip" data-placement="bottom" data-original-title="编辑" href="{{ URL::to('angel/wechat/graphics/upGraphics?graphics_id='.$g->id) }}" class="btn btn-success btn-xs">
                    编辑
                  </a>

                  <a data-toggle="tooltip" data-placement="bottom" data-original-title="删除" href="javascript:void(0);" 
                  bolt-delete-url="{{ URL::to('angel/wechat/graphics/deGraphics?graphics_id='.$g->id) }}" class="btn btn-dark btn-xs bolt-delete">
                    删除
                  </a>

                  <!-- <a data-toggle="tooltip" data-placement="bottom" data-original-title="同步微信" href="javascript:void(0);" 
                  class="btn btn-warning btn-xs">
                    <i class="icon-mouse"></i>
                  </a> -->
		            </td>
        			</tr>
        		@endforeach
        	@endif
        </tbody>
      </table>
    </div>

   	<footer class="panel-footer">
      <div class="row">
        <div class="col-sm-4 hidden-xs">
          ( 第 {{ $graphics->getCurrentPage() }} 页 / 共 {{ $graphics->getTotal() }} 条 @if (isset($search)) | <a href="{{ URL::to('angel/wechat/graphics') }}">离开搜索列表</a> @elseif (isset($filter)) | <a href="{{ URL::to('angel/wechat/graphics') }}">离开筛选列表</a> @endif)
        </div>

        <div class="col-sm-4 text-center"></div>
        <div class="col-sm-4 text-right text-center-xs">
          @if (isset($search))
            {{ $graphics->appends(array('search'=>$search))->links() }}
          @elseif (isset($filter))
             {{ $graphics->appends(array('filter'=>$filter))->links() }}
          @else
             {{ $graphics->links() }}
          @endif
        </div>
      </div>
    </footer>
  </section>
@stop