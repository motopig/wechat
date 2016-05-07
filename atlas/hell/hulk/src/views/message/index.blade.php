@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
    <link href="{{asset('atlas/hell/hulk/css/message.css')}}" rel="stylesheet" />
    <link href="{{asset('atlas/hell/hulk/css/wechatface.css')}}" rel="stylesheet" />

    <section class="panel panel-default">


    <header class="panel-heading text-right bg-light">
        <ul class="nav nav-tabs pull-left">
            <input type="hidden" value="{{ URL::to('/') }}" class="root_url">
            <li @if ( (isset($cat) && $cat == 'all') || !isset($cat) ) class="active"  @endif><a  href="{{ URL::to('angel/wechat/message') }}" data-type="all">所有消息</a></li>
            <li @if (isset($cat) && $cat == 'ignore') class="active" @endif> <a   href="{{ URL::to('angel/wechat/message/cat?cat=ignore') }}" data-type="ignore">未接待</a>
                <span class="tip" data-num="1" @if (!isset($ignoreNum))  style="display:none;" @endif >{{$ignoreNum or 0}}</span>
            </li>
            <li @if (isset($cat) && $cat == 'danger') class="active" @endif> <a   href="{{ URL::to('angel/wechat/message/cat?cat=danger') }}" data-type="danger">风险客户</a></li>
            <li @if (isset($cat) && $cat == 'automatic') class="active" @endif> <a   href="{{ URL::to('angel/wechat/message/cat?cat=automatic') }}" data-type="automatic">自动触发回复</a></li>
        </ul>
        <span class="hidden-sm">&nbsp;</span>
    </header>
    <div class="panel-body">

    <div class="row m-t-sm">
        <div class="input-group">
            <input type="text" class="input-sm form-control bolt-search-input" placeholder="请输入用户昵称">
              <span class="input-group-btn">
                <button type="button" class="btn btn-sm btn-default bolt-search" bolt-search-url="{{ URL::to('angel/wechat/message/seMessage') }}"
                        data-toggle="tooltip" data-placement="bottom" data-original-title="搜索">
                    <i class="icon-magnifier"></i>
                </button>
              </span>
        </div>
    </div>

    <div class="section-content">

        <input type="hidden" @if ( isset($subInfo) ) value="{{$subInfo['curTime']}}" @endif class="curTime">
        <input type="hidden" value="{{ URL::to('angel/wechat/message/getMemberMessage') }}" id="getMemberMessageAction">
        <input type="hidden" value="{{ URL::to('angel/wechat/message') }}" id="NewMessage">
        <input type="hidden" value="{{ URL::to('angel/wechat/message/more') }}" id="moreMessage">
        <input type="hidden" value="{{ URL::to('angel/wechat/message/cat') }}" id="catMessage">
        <input type="hidden" value="{{ URL::to('angel/wechat/message/checkNewMessage') }}" id="checkNewMessage">
        <p id="allMsgTip">
        </p>
        <div class="replies" id="replyListUI">
            <div id="hasNewMsg"><a href="">你有新消息，点击查看</a></div>
            {{--<p class="dataLoading">数据正在加载中，请稍后...</p>--}}
            @if (count($messages) > 0)
                <ul class="list-ul">
                    <div class="modal fade bs-example-modal-lg in" id="MsgModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="false" style="display: none; padding-left: 10px;">
                    <div class="modal-backdrop fade in" style="height: 1240px;"></div>
                    </div>
                @foreach ($messages as $k => $item)

                    <li class="mitem user-item"  data-member_id="{{$item->member_id}}">
                        <div class="photo fm-left">
                            @if ($item->concern == 'unfollow')
                                <img src=""alt="已跑路">
                            @else
                                <img src="{{$item->head}}"alt="头像">
                            @endif
                        </div>
                        <div class="msgBox fm-right">
                            <div class="message fm-left">
                                <div class="left fm-left">
                                    <p class="name">
                                        <a href="#">
                                            {{$item->name}}
                                        </a>
                                    </p>
                                    <p class="msg">
                                        留言:
                                        @if ($item->mold == 0)
                                            @if ($item->type == 0) {{$item->content}} @elseif ($item->type == 1) 「图片」 @elseif ($item->type == 2) 「音频」 @elseif ($item->type == 3) 「视频」 @elseif ($item->type == 4) 「小视频」 @elseif ($item->type == 5) 「地理位置」@elseif ($item->type == 6) 「链接」@elseif ($item->type == 7) 「图文」 @endif
                                        @elseif($item->mold == 1)
                                            @if ($item->type == 0) 「关注公众账号」 @elseif ($item->type == 1) 「取消关注公众账号」 @elseif ($item->type == 2) 「扫描二维码」 @elseif ($item->type == 3) 「地理位置」 @elseif ($item->type == 4 || $item->type == 5) 「点击菜单」 @endif
                                        @endif
                                        <br>
                                        @if ($item->replay && $item->replay->member_id == $item->member_id)
                                            回复: @if ($item->replay->type == 0) {{$item->replay->content}} @elseif ($item->replay->type == 1) 「图片」 @elseif ($item->replay->type == 2) 「音频」 @elseif ($item->replay->type == 3) 「视频」 @elseif ($item->replay->type == 4) 「小视频」 @elseif ($item->replay->type == 5) 「地理位置」@elseif ($item->replay->type == 6) 「链接」@elseif ($item->replay->type == 7) 「图文」 @endif
                                        @endif
                                    </p>
                                </div>
                                <div class="right fm-left">
                                    {{--{{#hasMoreMsg count}}{{/hasMoreMsg}}--}}
                                    @if (isset($subInfo[$item->member_id]))
                                        @if ($subInfo[$item->member_id] > 0  )
                                            <span class='tip' data-num='{{$subInfo[$item->member_id]}}'>{{$subInfo[$item->member_id]}}</span>
                                        @endif
                                    @endif
                                    <div class="time fm-right">
                                        {{$item->updated_at}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                @endforeach
                </ul>
            @else

                <div class="noMessage">没有用户消息</>

            @endif
        </div>

        <div id="msgBox">
            <div class="sendMsgBox fn-hide fn-clear">
                <ul class="recent-message-list" data-userId=""></ul>
                {{--<input type="hidden" id="active-messageid"/>--}}
                <input type="hidden"  class="content-data" data-messagetype="MESSAGE" value=''/>

                {{--<div id="edit-message-box" data-role="form">--}}
                    {{--<div class="message-edit-cont" data-userid="" data-replyid="">--}}
                    <form class="form-horizontal form-message-reply" method="post" action="{{URL::to('angel/wechat/message/replay')}}">
                        <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
                        <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
                        <input type="hidden" name="message-send-url" class="message-send-url" value="{{ URL::to('angel/wechat/message/replay') }}" />
                        <div class="form-group" style="margin-bottom:5px;">
                            <label class="col-sm-2 control-label">回复内容</label>
                            <div class="col-sm-7" style="z-index: 999;">
                                <div class="btn-toolbar m-b-sm btn-editor" data-role="editor-toolbar" data-target="#editor">
                                    <div class="emoji_list" style="display: none;">

                                    </div>
                                    <div class="btn-group">
                                        <a class="btn btn-default btn-sm wechat_emoji noclick" data-edit="bold" href="javascript:void(0);">
                                            <i class="fa fa-smile-o"></i>&nbsp; 表情
                                        </a>

                                    </div>

                                    <div class="btn-group">
                                        <a href="javascript:void(0);" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-file-text"></i>&nbsp; 图文 &nbsp;<b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="javascript:void(0);" class="bolt-modal-click" data-type="graphics">
                                                    微信图文
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="bolt-modal-click" data-type="material">
                                                    高级图文
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="btn-group">
                                        <a href="javascript:void(0);" class="btn btn-default btn-sm bolt-modal-click" data-type="image">
                                            <i class="fa fa-picture-o"></i>&nbsp; 图片
                                        </a>

                                        <a href="javascript:void(0);" class="btn btn-default btn-sm bolt-modal-click" data-type="voice">
                                            <i class="fa fa-microphone"></i>&nbsp; 语音
                                        </a>

                                        <a href="javascript:void(0);" class="btn btn-default btn-sm bolt-modal-click" data-type="video">
                                            <i class="fa fa-video-camera"></i>&nbsp; 视频
                                        </a>
                                    </div>
                                </div>
                                <div id="editor" style="overflow:scroll;overflow-x:hidden;height:222px;max-height:600px" class="form-control" contenteditable="true"></div>

                                <div id="modal-editor" style="display:none;overflow:scroll;overflow-x:hidden;height:222px;max-height:600px" class="form-control"></div>

                                <span id="modal-editor-data" data-type="" data-id="" style="display:none;"></span>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:5px;margin-top:0px;">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button type="button"  class="btn btn-success message-reply-click" data-id="">发送</button>
                            </div>
                        </div>
                    </form>
                    {{--</div>--}}
                {{--</div>--}}
            </div>
        </div>

        <footer class="panel-footer">
            <div class="row">
                @if (count($messages) > 0)
                    <div class="col-sm-4 hidden-xs">
                        ( 第 {{ $messages->getCurrentPage() }} 页 / 共 {{ $messages->getTotal() }}  条 @if (isset($search)) | <a href="{{ URL::to('angel/wechat/message') }}">离开搜索列表</a>  @endif)
                    </div>
                @endif
                <div class="col-sm-4 text-center"></div>
                <div class="col-sm-4 text-right text-center-xs">
                    @if (isset($search) && count($messages) > 0)
                        {{ $messages->appends(array('search'=>$search))->links() }}
                    @elseif (isset($filter) && count($messages) > 0)
                        {{ $messages->appends(array('filter'=>$filter))->links() }}
                    @elseif (count($messages) > 0)
                        {{ $messages->links() }}
                    @endif
                </div>
            </div>
        </footer>
        </div>
    </section>

    @include('EcdoSpiderMan::layouts.modal.dialog')
    @include('EcdoHulk::message/template')
    <script src="{{asset('atlas/hell/hulk/js/wechatface.js')}}"></script>
    <script src="{{asset('assets/universe/js/handlebars.js')}}"></script>
    <script src="{{asset('atlas/hell/hulk/js/handlebars_function.js')}}"></script> {{-- handlebars 新增方法--}}
    <script src="{{asset('atlas/hell/hulk/js/message.js')}}"></script>
    <script src="{{{ asset('atlas/hell/hulk/js/wechat.desktop.js') }}}"></script>
@stop