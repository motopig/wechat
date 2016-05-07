@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
  <section class="panel panel-default">
    @include('EcdoSuperMan::layouts.tabs.file')

    <div class="row wrapper">
      <div class="col-sm-5 m-b-xs">
        <a href="javascript:void(0);" bolt-url="angel/store/image/crImage" bolt-modal="上传图片" bolt-modal-icon="icon-plus" class="boltClick btn btn-success btn-xs">
          上传图片
        </a>

        <!-- <a data-toggle="tooltip" data-placement="bottom" data-original-title="批量删除" href="javascript:void(0);" 
        class="btn btn-dark btn-xs bolt-drop" bolt-drop-url="{{ URL::to('angel/store/image/drImage') }}">
          <i class="icon-trash"></i>
        </a> -->
      </div>

      <div class="col-sm-4 m-b-xs"></div>
      <div class="col-sm-3">
        <div class="input-group">
          <input type="text" class="input-sm form-control bolt-search-input" placeholder="请输入图片别名">
          <span class="input-group-btn">
            <button type="button" class="btn btn-sm btn-default bolt-search" bolt-search-url="{{ URL::to('angel/store/image/seImage') }}" 
            data-toggle="tooltip" data-placement="bottom" data-original-title="搜索">
            <i class="icon-magnifier"></i>
            </button>
          </span>
        </div>
      </div>
    </div>

    <!-- <hr class="hr-middle" style="margin-top:0px;" />
    <div class="table-responsive">
      @if (count($image) > 0)
        @foreach ($image as $i)
          <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
            <div class="item">
              <div class="pos-rlt">
                <div class="item-overlay opacity r bg-black">
                  <div class="text-info padder m-t-sm text-sm" style="white-space:nowrap;text-overflow:ellipsis;overflow:hidden;">
                    <a href="###" title="{{$i->name}}">{{$i->name}}</a>
                  </div>

                  <div class="center text-center m-t-n" @if ($i->name) style="top:53%;" @endif>
                    <a href="###"><i class="fa fa-play-circle i-2x"></i></a>
                  </div>
                </div>
                <a href="track-detail.html">
                  <img src="{{asset($i->url)}}" class="r img-full" height="123px">
                </a>
              </div>
              <div class="padder-v"></div>
            </div>
          </div>
        @endforeach
      @endif
    </div> -->

    <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>
              <a class="all-checkbox" href='javascript:void(0);'>
                <i class="fa fa-square-o"></i>
              </a>
            </th>
            <th>图片别名</th>
            <th>更新时间 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>操作</th>
          </tr>
        </thead>

        <tbody>
          @if (count($image) > 0)
            @foreach ($image as $i)
              <tr>
                <td>
                  <input class="drop-checkbox" type="checkbox" name="image_id" value="{{$i->id}}">

                  <a href="{{asset($i->url)}}" class="thumb-xs m-t-xs m-l-xs m-r-sm" 
                  data-toggle="tooltip" data-placement="bottom" data-original-title="点击放大" data-lighter>
                    <img src="{{asset($i->url)}}">
                  </a>
                </td>

                <td>
                  {{$i->name}}
                </td>

                <td>
                  {{$i->updated_at}}
                </td>

                <td>
                  <a href="javascript:void(0);" bolt-url="angel/store/image/upImage" bolt-data="id={{$i->id}}" 
                  bolt-modal="编辑图片" bolt-modal-icon="icon-note" class="boltClick btn btn-success btn-xs">
                    编辑
                  </a>

                  <a href="javascript:void(0);" bolt-delete-url="{{ URL::to('angel/store/image/deImage?id='.$i->id) }}" class="btn btn-dark btn-xs bolt-delete">
                    删除
                  </a>
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
          ( 第 {{ $image->getCurrentPage() }} 页 / 共 {{ $image->getTotal() }}  条 @if (isset($search)) | <a href="{{ URL::to('angel/store/image') }}">离开搜索列表</a> @endif)
        </div>

        <div class="col-sm-4 text-center"></div>
        <div class="col-sm-4 text-right text-center-xs">
          @if (isset($search))
            {{ $image->appends(array('search'=>$search))->links() }}
          @else
             {{ $image->links() }}
          @endif
        </div>
      </div>
    </footer>
  </section>
@stop