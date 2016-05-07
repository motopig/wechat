@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')

    <link href="{{asset('atlas/hell/hulk/css/menu.css')}}" rel="stylesheet" />
    
        <input type="hidden" value="{{\URL::to('/')}}" class="root_url">
        <div class="section-content">
            <span class="fn-information">最多可添加3个主菜单，每个主菜单最多可添加5个子菜单。</span>
            <h1 class="fn-title">菜单管理</h1>

            {{--<form class="form-horizontal form-graphics" role="form" method="POST"  enctype="multipart/form-data">--}}
                <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
                <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
                <input type="hidden" class="thisid" id="id" value="{{$id}}">

                <div id="menuContainer" class="fn-left" data-menu="">
                    <div class="menu-title">
                        自定义菜单
                        <a href="" id="menuSortBtn" class="menu-fn-right" seed="menuTitle-menuSortBtn">排序</a>
                        <div class="sortControl menu-fn-right fn-hide">
                            <a href="javascript:;" id="saveSort" class="btn-small  btn-orange" seed="sortControl-saveSort" smartracker="on">完成排序</a>
                            <a href="javascript:;" id="cancelSort" class="btn-small btn-gray" seed="sortControl-cancelSort" smartracker="on">取 消</a>
                        </div>
                    </div>

                    <div id="menuList">
                        <div id="sortable_record"></div>
                        <div class="custom">
                            @if (count($menus) > 0)
                                @foreach ($menus as $m)
                                    <div class="menu-list">
                                        <div class="list-item list-parent">
                                            <span class="name @if (!isset($m->subMenuItems) && !$m->messageId) noAction @endif" data-name="{{$m->name}}">{{$m->name}}
                                                @if (!$m->subMenuItems && !$m->messageId)
                                                    <b class="notActive"></b>
                                                @endif
                                            </span>

                                            <div class="actionContainer fn-hide @if (!isset($m->subMenuItems) && !$m->messageId) notSelectType @endif" data-actiontype="" >
                                                <h3 class="title">菜单类型</h3>
                                                <div class="select-box">
                                                    <div class="selectInput"> @if ($m->messageId) @if (in_array($m->actionType,array('emoji','text','image','graphics','voice','video','material'))) 推送消息 @elseif(in_array($m->actionType,array('ecshop'))) 微商城  @elseif(in_array($m->actionType,array('link'))) 自定义链接 @elseif(in_array($m->actionType,array('member'))) 会员中心 @endif  @else 请选择类型  @endif</div>
                                                    <ul class="actionSelect fn-hide" style="display: none;">
                                                        <li val="info" class="deep-back @if ($m->messageId && (in_array($m->actionType, array('emoji','text','image','graphics','voice','video','material')))) active @endif "> <span class="typeName info"  > 推送消息 </span> <span class="explain"> 图文,音频,视频等 </span></li>

                                                        <li val="ecshop" > <span class="typeName ecshop"  > 微商城 </span> <span class="explain"> 微商城 </span></li>
                                                        <li val="link" class="deep-back @if ($m->messageId && (in_array($m->actionType, array('link')))) active @endif"> <span class="typeName link"  > 自定义链接 </span> <span class="explain"> 跳转链接 </span></li>
                                                        <li val="member" > <span class="typeName member"  > 会员中心 </span> <span class="explain"> 会员中心 </span></li>
                                                    </ul></div>
                                                <div class="container-title"><b>菜单效果</b></div>
                                                <div class="tplContainer"><div class="container-tip">请先选择菜单类型</div>
                                                @if ($m->messageId)
                                                    @if (in_array($m->actionType, array('emoji','text','image','graphics','voice','video','material')))
                                                    <div class="infoType">
                                                        <ul class="infoUl">
                                                            <li class="infoLi semoji @if ($m->actionType == 'text') active @endif" data-type="text">表情</li>
                                                            <li class="infoLi image bolt-modal-click @if ($m->actionType == 'image') active @endif" data-type="image">图片</li>
                                                            <li class="infoLi graphics bolt-modal-click @if ($m->actionType == 'graphics') active @endif" data-type="graphics">微信图文</li>
                                                            <li class="infoLi material bolt-modal-click @if ($m->actionType == 'material') active @endif"  data-type="material">高级图文</li>
                                                            <li class="infoLi voice bolt-modal-click @if ($m->actionType == 'voice') active @endif"  data-type="voice">语音</li>
                                                            <li class="infoLi video bolt-modal-click @if ($m->actionType == 'video') active @endif"  data-type="video">视频</li>
                                                        </ul>
                                                        <div class="infoTextArea   @if ($m->actionType == 'text') onCur @endif " contenteditable="true" @if ($m->actionType == 'text') updata='{{json_encode($m->pinfo['actionContent'])}}' style="display:block;" @endif >{{$m->actionContent}}</div>
                                                        <div class="emoji_preview spreview" @if ($m->actionType == 'text') actiontype="text" messageId="{{$m->messageId}}" updata='{{json_encode($m->pinfo)}}' @endif>

                                                        </div>
                                                        <div  @if ($m->actionType == 'image')class="image_preview spreview onCur" @else class="image_preview spreview" @endif actiontype="image" messageId="{{$m->messageId}}" updata='{{json_encode($m->pinfo)}}'>

                                                        </div>
                                                        <div class="graphics_preview spreview @if ($m->actionType == 'graphics') onCur @endif " @if ($m->actionType == 'graphics') actiontype="graphics" messageId="{{$m->messageId}}" updata='{{json_encode($m->pinfo)}}'  @endif>

                                                        </div>
                                                        <div class="material_preview spreview @if ($m->actionType == 'material') onCur @endif " @if ($m->actionType == 'material') actiontype="material" messageId="{{$m->messageId}}" updata='{{json_encode($m->pinfo)}}'  @endif>

                                                        </div>
                                                        <div class="voice_preview spreview @if ($m->actionType == 'voice') onCur @endif " @if ($m->actionType == 'voice') actiontype="voice" messageId="{{$m->messageId}}" updata='{{json_encode($m->pinfo)}}'  @endif>

                                                        </div>
                                                        <div class="video_preview spreview @if ($m->actionType == 'video') onCur @endif " @if ($m->actionType == 'video') actiontype="video" messageId="{{$m->messageId}}" updata='{{json_encode($m->pinfo)}}'  @endif>

                                                        </div>
                                                    </div>
                                                    @elseif (in_array($m->actionType, array('link')))

                                                        <div class="container-tip">用户点击该菜单后，将跳转到以下网页地址。</div>
                                                        <div class="inputWrap">
                                                            <input  placeholder="请填写以http://或https://开头的网页地址" class="typeContentInput onCur" value="{{$m->actionContent}}" type="text">
                                                            <div class="link_preview"></div>
                                                        </div>
                                                        <div class="errorMsg"><i class="iconfont" title="出错"></i>请填写正确的网页地址。</div>
                                                    @elseif (in_array($m->actionType, array('ecshop')))
                                                        <div class="container-tip">用户点击该菜单后，将跳转到微商城。</div>
                                                        <div class="select-box">
                                                            <div class="selectInput"> @if($m->actionContent == 'lp')  首页   @elseif($m->actionContent == 'member') 会员中心 @elseif($m->actionContent == 'order') 订单 @else 请选择页面  @endif </div>
                                                            <ul class="actionSelect ecshopConfig" style="display: none;">
                                                                <li val="lp" class="deep-back "> <span class="typeName lp"  > 首页 </span> <span class="explain"> 首页 </span></li>
                                                                <li val="member" class="deep-back "> <span class="typeName member"  > 会员中心 </span> <span class="explain"> 会员中心 </span></li>
                                                                <li val="order" class="deep-back "> <span class="typeName order"  > 我的订单 </span> <span class="explain"> 我的订单 </span></li>
                                                            </ul>
                                                        </div>
                                                    @elseif (in_array($m->actionType, array('member')))
                                                        <div class="container-tip">用户点击该菜单后，将跳转到会员中心。</div>
                                                        <div class="select-box">
                                                            <div class="selectInput"> @if ($m->actionContent == 'member') 首页 @else 请选择页面 @endif </div>
                                                            <ul class="actionSelect memberConfig" style="display: none;">
                                                                <li val="member" class="deep-back "> <span class="typeName member"> 首页 </span> <span class="explain"> 首页 </span></li>
                                                            </ul>
                                                        </div>
                                                    @endif
                                                @endif
                                                </div>
                                                <div class="btn-wrap"><input class="btn-small btn-orange saveBtn" type="button" value="保存"></div>
                                                <span class="left-point"></span><a class="cancelBtn">x</a>
                                            </div>

                                            <div class="noActionContainer fn-hide">
                                                <div class="msg">
                                                    <i class="iconfont" title="提示"></i>
                                                    <span>已有子菜单，无法设置菜单效果</span>
                                                </div>
                                                <div class="btn-wrap">
                                                    <input class="btn-small btn-orange saveBtn" type="button" value="确定">
                                                </div>

                                                <span class="left-point"></span>
                                                <a class="cancelBtn">×</a>
                                            </div>
                                            <div class="edit">
                                                <a class="rename">编辑</a>
                                                <a class="del">删除</a>
                                            </div>

                                            <span class="sort-icon"></span>
                                        </div>
                                        <div class="subMenu-list">
                                            @if (count($m->subMenuItems) > 0)
                                                @foreach ($m->subMenuItems as $sub)

                                                <div class="list-item list-sub">
                                                    <span class="name @if (!$sub->messageId) noAction @endif" data-name="{{$sub->name}}">{{$sub->name}}
                                                        @if (!$sub->messageId) <b class="notActive"></b> @endif
                                                    </span>
                                                    <div class="actionContainer fn-hide @if (!$sub->messageId) notSelectType  @endif" data-actiontype="" >
                                                        <h3 class="title">菜单类型</h3>
                                                        <div class="select-box">
                                                            <div class="selectInput"> @if ($sub->messageId) @if (in_array($sub->actionType,array('emoji','text','image','graphics','voice','video','material'))) 推送消息 @elseif(in_array($sub->actionType,array('ecshop'))) 微商城  @elseif(in_array($sub->actionType,array('link'))) 自定义链接 @elseif(in_array($sub->actionType,array('member'))) 会员中心 @endif  @else 请选择类型  @endif</div>
                                                            <ul class="actionSelect fn-hide" style="display: none;">
                                                                <li val="info" class="deep-back @if ($sub->messageId && (in_array($sub->actionType, array('emoji','text','image','graphics','voice','video','material')))) active @endif "> <span class="typeName info"  > 推送消息 </span> <span class="explain"> 图文,音频,视频等 </span></li>

                                                                <li val="ecshop" > <span class="typeName ecshop"  > 微商城 </span> <span class="explain"> 微商城 </span></li>
                                                                <li val="link" class="deep-back "> <span class="typeName link"  > 自定义链接 </span> <span class="explain"> 跳转链接 </span></li>
                                                                <li val="member" > <span class="typeName member"  > 会员中心 </span> <span class="explain"> 会员中心 </span></li>
                                                            </ul></div>
                                                        <div class="container-title"><b>菜单效果</b></div>
                                                        <div class="tplContainer"><div class="container-tip">请先选择菜单类型</div>
                                                        @if ($sub->messageId)
                                                            @if (in_array($sub->actionType, array('emoji','text','image','graphics','voice','video','material')))
                                                            <div class="infoType">
                                                                <ul class="infoUl">
                                                                    <li class="infoLi semoji btn btn-default btn-sm @if ($sub->actionType == 'text') active @endif" data-type="text"><i class="fa fa-smile-o"></i>表情</li>
                                                                    <li class="infoLi image btn btn-default btn-sm bolt-modal-click @if ($sub->actionType == 'image') active @endif" data-type="image"><i class="fa fa-picture-o"></i>图片</li>
                                                                    <li class="infoLi graphics btn btn-default btn-sm bolt-modal-click @if ($sub->actionType == 'graphics') active @endif" data-type="graphics"><i class="fa  fa-file-o"></i>微信图文</li>
                                                                    <li class="infoLi material btn btn-default btn-sm bolt-modal-click @if ($sub->actionType == 'material') active @endif"  data-type="material"><i class="fa  fa-files-o"></i>高级图文</li>
                                                                    <li class="infoLi voice btn btn-default btn-sm bolt-modal-click @if ($sub->actionType == 'voice') active @endif"  data-type="voice"><i class="fa fa-microphone"></i>语音</li>
                                                                    <li class="infoLi video btn btn-default btn-sm bolt-modal-click @if ($sub->actionType == 'video') active @endif"  data-type="video"><i class="fa fa-video-camera"></i>视频</li>
                                                                </ul>
                                                                <div class="infoTextArea   @if ($sub->actionType == 'text') onCur @endif " contenteditable="true" @if ($sub->actionType == 'text') updata='{{$sub->actionContent}}' @endif>{{$sub->actionContent}}</div>
                                                                <div class="emoji_preview spreview" @if ($sub->actionType == 'text') actiontype="text" messageId="{{$sub->messageId}}" updata='{{json_encode($sub)}}' @endif></div>
                                                                <div class="image_preview spreview @if ($sub->actionType == 'image') onCur @endif " @if ($sub->actionType == 'image') actiontype="image" messageId="{{$sub->messageId}}" updata='{{json_encode($sub)}}'  @endif></div>
                                                                <div class="graphics_preview spreview @if ($sub->actionType == 'graphics') onCur @endif " @if ($sub->actionType == 'graphics') actiontype="graphics" messageId="{{$sub->messageId}}" updata='{{json_encode($sub)}}'  @endif></div>
                                                                <div class="material_preview spreview @if ($sub->actionType == 'material') onCur @endif " @if ($sub->actionType == 'material') actiontype="material" messageId="{{$sub->messageId}}" updata='{{json_encode($sub)}}'  @endif></div>
                                                                <div class="voice_preview spreview @if ($sub->actionType == 'voice') onCur @endif " @if ($sub->actionType == 'voice') actiontype="voice" messageId="{{$sub->messageId}}" updata='{{json_encode($sub)}}'  @endif></div>
                                                                <div class="video_preview spreview @if ($sub->actionType == 'video') onCur @endif " @if ($sub->actionType == 'video') actiontype="video" messageId="{{$sub->messageId}}" updata='{{json_encode($sub)}}'  @endif></div>
                                                            </div>
                                                            @elseif (in_array($sub->actionType, array('link')))

                                                                <div class="container-tip">用户点击该菜单后，将跳转到以下网页地址。</div>
                                                                <div class="inputWrap">
                                                                <input  placeholder="请填写以http://或https://开头的网页地址" class="typeContentInput onCur" value="{{$sub->actionContent}}" type="text">
                                                                <div class="link_preview"></div>
                                                                </div>
                                                                <div class="errorMsg"><i class="iconfont" title="出错"></i>请填写正确的网页地址。</div>
                                                            @elseif (in_array($sub->actionType, array('ecshop')))
                                                                    <div class="container-tip">用户点击该菜单后，将跳转到微商城。</div>
                                                                    <div class="select-box">
                                                                        <div class="selectInput">  @if($sub->actionContent == 'lp')  首页   @elseif($sub->actionContent == 'member') 会员中心 @elseif($sub->actionContent == 'order') 订单 @else 请选择页面  @endif </div>
                                                                        <ul class="actionSelect ecshopConfig" style="display: none;">
                                                                            <li val="lp" class="deep-back "> <span class="typeName lp"  > 首页 </span> <span class="explain"> 首页 </span></li>
                                                                            <li val="member" class="deep-back "> <span class="typeName member"  > 会员中心 </span> <span class="explain"> 会员中心 </span></li>
                                                                            <li val="order" class="deep-back "> <span class="typeName order"  > 我的订单 </span> <span class="explain"> 我的订单 </span></li>
                                                                        </ul>
                                                                    </div>
                                                            @elseif (in_array($sub->actionType, array('member')))
                                                                    <div class="container-tip">用户点击该菜单后，将跳转到会员中心。</div>
                                                                    <div class="select-box">
                                                                        <div class="selectInput">  @if ($sub->actionContent == 'member') 首页 @else 请选择页面  @endif </div>
                                                                        <ul class="actionSelect ecshopConfig" style="display: none;">
                                                                            <li val="member" class="deep-back "> <span class="typeName member"  > 首页 </span> <span class="explain"> 首页 </span></li>
                                                                        </ul>
                                                                    </div>
                                                            @endif
                                                        @endif
                                                        </div>
                                                        <div class="btn-wrap"><input class="btn-small btn-orange saveBtn" type="button" value="保存"></div>
                                                        <span class="left-point"></span><a class="cancelBtn">x</a>
                                                    </div>
                                                    <div class="edit">
                                                        <a class="rename">编辑</a>
                                                        <a class="del">删除</a>
                                                    </div>

                                                    <span class="sort-icon"></span>
                                                </div>

                                                @endforeach
                                            @endif
                                            <div class="new-subMenu-wrap">
                                                @if (count($m->subMenuItems) < 5)
                                                <a href="javascript:;" class="new-menu new-menu-sub">+ 添加子菜单</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <div class="new-menu-wrap" @if (count($menus) == 3) style="display:none;"  @endif >
                                <a href="javascript:;" class="new-menu new-menu-main">+ 添加主菜单</a>
                            </div>
                        </div>
                    </div>

                    <div class="saveContainer">
                        <div class="saveTip warn" id="J_saveTip"><span>当前菜单尚未生效，点击发布后才能在微信中看到</span></div>
                        <div class="btn btn-orange" id="menuSaveBtn">发<i class="btn-spacing"></i>布</div>
                    </div>
                </div>
                <div id="previewContainer">
                    <div id="previewShowBox"></div>
                    <div id="previewBtnLists">
                        <div class="preview-items dialog" style="width:37px;">
                            <a href="#" data-menudata="dialog" class="menu menu-action dialogPreview"></a>
                        </div>
                        @if (count($menus) > 0)

                            @foreach ($menus as $pm)
                                <div class="preview-items">
                                    <a href="#" data-menudata='{{json_encode($pm)}}' class="menu menu-action @if ($pm->actionType == 'link' || $pm->actionType == 'text' || $pm->actionType == 'ecshop' || $pm->actionType == 'member') {{$pm->actionType}} @endif  @if (count($pm->subMenuItems) > 0) hasSubMenu @endif  @if (!$pm->subMenuItems && !$pm->messageId) noAction @endif">{{$pm->name}}</a>
                                    @if (count($pm->subMenuItems) > 0)
                                        <ul class="preview-sub-items">
                                            @foreach ($pm->subMenuItems as $psub)
                                            <li><a href="#" class="sub-menu menu-action @if($psub->actionType == 'ecshop')  ecshop @endif  @if($psub->actionType == 'member') member @endif @if($psub->actionType == 'text')  text @endif @if($psub->actionType  == 'link') link @endif  @if (!$psub->messageId) noAction @endif" data-menudata='{{json_encode($psub)}}'>{{$psub->name}}</a></li>
                                            @endforeach
                                            <li class="btm-point"></li>
                                        </ul>
                                    @endif
                                </div>
                            @endforeach

                        @endif
                    </div>
                    <div class="previewBottom"> 效果预览 </div>
                    <div class="previewTitle">ECDO</div>
                </div>
            {{--</form>--}}
        </div>


    @include('EcdoSpiderMan::layouts.modal.dialog')
    <script src="{{asset('assets/universe/dist/jquery-ui/jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/universe/js/handlebars.js')}}"></script>
    <script src="{{asset('atlas/hell/hulk/js/menu.js')}}"></script>
    <script src="{{asset('atlas/hell/hulk/js/emoji.js')}}"></script>

@stop