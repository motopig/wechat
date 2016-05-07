@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
  <section class="panel panel-default">
    @include('EcdoSuperMan::layouts.tabs.file')
    
    <div class="row wrapper">
      <div class="col-sm-5 m-b-xs">
        <a href="javascript:void(0);" bolt-url="angel/store/voice/crVoice" bolt-modal="上传语音" bolt-modal-icon="icon-plus" class="boltClick btn btn-success btn-xs">
          上传语音
        </a>

        <!-- <a data-toggle="tooltip" data-placement="bottom" data-original-title="批量删除" href="javascript:void(0);" 
        class="btn btn-dark btn-xs bolt-drop" bolt-drop-url="{{ URL::to('angel/store/voice/drVoice') }}">
          <i class="icon-trash"></i>
        </a> -->
      </div>

      <div class="col-sm-4 m-b-xs"></div>
      <div class="col-sm-3">
        <div class="input-group">
          <input type="text" class="input-sm form-control bolt-search-input" placeholder="请输入语音别名">
          <span class="input-group-btn">
            <button type="button" class="btn btn-sm btn-default bolt-search" bolt-search-url="{{ URL::to('angel/store/voice/seVoice') }}" 
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
              <th>
                <a class="all-checkbox" href='javascript:void(0);'>
                  <i class="fa fa-square-o"></i>
                </a>
              </th>
            <th>
                语音
            </th>
            <th>别名</th>
            <th>更新时间 <i class="fa fa-sort fa-sort-p"></i></th>
            <th>操作</th>
          </tr>
        </thead>

        <tbody>
          @if (count($voice) > 0)
            @foreach ($voice as $v)
              <tr>
                  <td><input class="drop-checkbox" type="checkbox" name="image_id" value="{{$v->id}}"></td>
                <td class="b-l">

                  <audio controls="controls" style="margin-left:18px;">
                    <source src="{{asset($v->url)}}" type="audio/mpeg" />
                  </audio>
                </td>

                <td>
                  {{$v->name}}
                </td>

                <td>
                  {{$v->updated_at}}
                </td>

                <td>
                  <a href="javascript:void(0);" bolt-url="angel/store/voice/upVoice" bolt-data="id={{$v->id}}" bolt-modal="编辑语音" bolt-modal-icon="icon-note" 
                  class="boltClick btn btn-success btn-xs">
                    编辑
                  </a>

                  <a href="javascript:void(0);" bolt-delete-url="{{ URL::to('angel/store/voice/deVoice?id='.$v->id) }}" class="btn btn-dark btn-xs bolt-delete">
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
          ( 第 {{ $voice->getCurrentPage() }} 页 / 共 {{ $voice->getTotal() }}  条 @if (isset($search)) | <a href="{{ URL::to('angel/store/voice') }}">离开搜索列表</a> @endif)
        </div>

        <div class="col-sm-4 text-center"></div>
        <div class="col-sm-4 text-right text-center-xs">
          @if (isset($search))
            {{ $voice->appends(array('search'=>$search))->links() }}
          @else
             {{ $voice->links() }}
          @endif
        </div>
      </div>
    </footer>
  </section>
@stop