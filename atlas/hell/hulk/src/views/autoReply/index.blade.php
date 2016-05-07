@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')

<link href="{{asset('atlas/hell/hulk/css/auto_reply.css')}}" rel="stylesheet" />
<link href="{{{ asset('atlas/hell/hulk/css/graphics.css') }}}" rel="stylesheet" />

  <section class="panel panel-default">
      <header class="panel-heading">
    	<i class="icon-bubbles"></i>&nbsp;微信自动回复
    </header>

    <div class="row wrapper">
        <div class="col-sm-9 m-b-xs">
        <a href="{{ URL::to('angel/wechat/autoReply/crAutoReply') }}" class="btn btn-sm btn-success">
        	创建新规则
        </a>
        </div>
        
        <div class="col-sm-3 row">
          <div class="input-group">
              <input type="text" class="input-sm form-control bolt-search-input" placeholder="请输入规则名">
              <span class="input-group-btn">
                <button type="button" class="btn btn-sm btn-default bolt-search" bolt-search-url="{{ URL::to('angel/wechat/autoReply/seAutoReply') }}" 
                data-toggle="tooltip" data-placement="bottom" data-original-title="搜索">
                <i class="icon-magnifier"></i>
                </button>
              </span>
            </div>
          </div>
    </div>
    <div class="panel-body">
        @if (count($autoreply) > 0)
          <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
          <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
          
          @foreach ($autoreply as $a)
            <div class="rule-list-item">
              <input type="hidden" class="ruleId" value="{{$a->id}}">

              <table>
                <tbody>
                  <tr>
                    <td>规则名：</td>
                    <td class="rule-name">
                      {{$a->name}}
                    </td>
                  </tr>

                  <tr>
                      <td>关键词：</td>
                      <td>
                          <ul class="keyword-list">
                            @if (count($a->item) > 0)
                              @foreach ($a->item as $i)
                                @if ($i->matching == '0')
                                  <li data-id="{{$i->id}}" data-type="1" data-url="{{URL::to('angel/wechat/autoReply/matchingAutoReply')}}" 
                                  class="matching-click" data-toggle="tooltip" data-placement="top" data-original-title="设为全字匹配">
                                    {{$i->keyword}}
                                  </li>
                                @else
                                  <li data-id="{{$i->id}}" data-type="0" data-url="{{URL::to('angel/wechat/autoReply/matchingAutoReply')}}" 
                                  class="matching-click active" data-toggle="tooltip" data-placement="top" data-original-title="设为模糊匹配">
                                    {{$i->keyword}}
                                  </li>
                                @endif
                              @endforeach
                            @endif
                          </ul>
                      </td>
                  </tr>

                  <tr>
                      <td style="vertical-align:baseline;">回复内容：</td>
                      <td class="rule-content">
                        @if ($a->type == '0' || ($a->type != '0' && count($a->preview) == 0))
                          {{$a->content}}
                        @elseif ($a->type == '1')
                          <div class="ng" style="width:250px;">
                            <div class="ng-item">
                                <div class="td-cont with-label">
                                    <span class="label label-success">
                                      @if (count($a->preview['item']) > 0) 多图文 @else 单图文 @endif
                                    </span>
                                    <span class="ng-title">
                                      {{$a->preview['title']}}
                                    </span>
                            
                                    @if (count($a->preview['item']) > 0)
                                      <span class="pull-right graphics-title">
                                        <a href="###">
                                          <i class="fa fa-sort-down" graphics-title-id="{{$a->preview['id']}}"></i>
                                        </a>
                                      </span>
                                    @endif
                                </div>
                            </div>

                            @if (count($a->preview['item']) > 0)
                              <div class="ng-item graphics-title-fid_{{$a->preview['id']}}" style="display:none;">
                                @foreach ($a->preview['item'] as $i)
                                    <div class="td-cont with-label" style="padding:2px;">
                                        <span class="ng-title">{{$i->title}}</span>
                                    </div>
                                  @endforeach
                              </div>
                            @endif
                          </div>
                        @elseif ($a->type == '2')
                          {{$a->content}}
                        @elseif ($a->type == '3')
                          <a href="{{asset($a->preview['url'])}}" class="thumb-xs m-t-xs m-l-xs m-r-sm" 
                          data-toggle="tooltip" data-placement="bottom" data-original-title="点击放大图片" data-lighter>
                            <img src="{{asset($a->preview['url'])}}">
                          </a>
                          @if ($a->preview['name']) {{$a->preview['name']}} @endif
                        @elseif ($a->type == '4')
                          <audio controls="controls" title="点击播放语音">
                            <source src="{{asset($a->preview['url'])}}" type="audio/mpeg" />
                          </audio>
                          <span style="margin-left:10px;">
                              @if ($a->preview['name'])
                                {{$a->preview['name']}}
                              @else
                                语音
                              @endif
                          </span>
                        @elseif ($a->type == '5')
                          <a href="{{asset($a->preview['url'])}}" target="_blank" title="点击播放视频">
                            <i class="fa fa-play-circle i-2x"></i>
                          </a>
                          <span style="margin-left:10px;">
                              @if ($a->preview['name'])
                                {{$a->preview['name']}}
                              @else
                                视频
                              @endif
                          </span>
                        @endif
                      </td>
                  </tr>
                </tbody>
              </table>

              <p class="rule-option">
                  <a href="{{ URL::to('angel/wechat/autoReply/upAutoReply?id='.$a->id) }}" style="display:none;" class="btn btn-success btn-xs rule-option-button">
                    编辑
                  </a>

                  <a href="javascript:void(0);" style="display:none;" class="btn btn-dark btn-xs bolt-delete rule-option-button" 
                  bolt-delete-url="{{URL::to('angel/wechat/autoReply/deAutoReply?id='.$a->id)}}">
                    删除
                  </a>

                  &nbsp;
                  @if ($a->concern == '1')
                    <a href="javascript:void(0);" data-id="{{$a->id}}" data-url="{{URL::to('angel/wechat/autoReply/concernAutoReply')}}" data-type="0" 
                    class="btn btn-success btn-xs concern-click no-radius">
                      已设为关注回复
                    </a>
                  @else
                    <a href="javascript:void(0);" style="display:none;" data-id="{{$a->id}}" data-url="{{URL::to('angel/wechat/autoReply/concernAutoReply')}}" data-type="1" 
                    class="btn btn-default btn-xs rule-option-button concern-click">
                      设置成关注回复
                    </a>
                  @endif
              </p>
            </div>
          @endforeach
        @endif

        <div class="row">
            <div class="col-sm-4 hidden-xs">
              @if ($autoreply->getTotal() == 0)
                <h4 class="font-thin m-b"></h4>
              @endif
      
              ( 第 {{ $autoreply->getCurrentPage() }} 页 / 共 {{ $autoreply->getTotal() }}  条 @if (isset($search)) | <a href="{{URL::to('angel/wechat/autoReply')}}">离开搜索列表</a> @endif)
            </div>
    
            <div class="col-sm-4 text-center"></div>
            <div class="col-sm-4 text-right text-center-xs">
              @if (isset($search))
                {{ $autoreply->appends(array('search'=>$search))->links() }}
              @else
                 {{ $autoreply->links() }}
              @endif
            </div>
        </div>
    </div>

</section>
<script src="{{asset('atlas/hell/hulk/js/auto_reply.js')}}"></script>
<script src="{{{ asset('atlas/hell/hulk/js/wechat.desktop.js') }}}"></script>
@stop