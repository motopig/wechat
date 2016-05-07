@extends('EcdoSpiderMan::layouts.site.default')

@section('main')
<link href="{{asset('atlas/hell/thor/css/wheel.css')}}" rel="stylesheet" />
<link href="{{{ asset('atlas/hell/spider-man/css/alertify/alertify.bootstrap.css') }}}" rel="stylesheet" />
<link href="{{{ asset('atlas/hell/spider-man/css/alertify/alertify.core.css') }}}" rel="stylesheet" />

<input type="hidden" class="openid" value="{{$data['openid']}}">
<input type="hidden" class="guid" value="{{$data['guid']}}">
<input type="hidden" class="sid" value="{{$data['id']}}">
<input type="hidden" class="not" value="0">

<input type="hidden" class="prize_action" value="{{URL::to($data['guid'].'/wheelResult')}}">

<div class="wheel">
    <div class="logo"></div>

    <div class="playground">

        <div class="playwarp">

            <div class="sclose warp">

            </div>

            <div class="win warp">

            </div>

            <div class="loser warp">

            </div>

            <div class="info warp">
                <ul class="infoul">
                    <li><span>1.活动名称:</span>
                        <div class="name">    {{$data['info']['name']}}</div>
                    </li>
                    <li><span>2.活动有效时间:</span>
                        <div class="time">{{$data['info']['begin_at']}} 至 {{$data['info']['end_at']}} </div>
                    </li>
                    <li><span>3.玩法说明:</span>
                        <div class="des">@if ($data['info']['description']) {{$data['info']['description']}} @else 点击宝箱领取优惠 @endif </div>
                    </li>
                    <li></li>
                </ul>
            </div>

        </div>

    </div>

</div>

<script src="{{{ asset('atlas/hell/spider-man/js/alertify/alertify.js') }}}"></script>
<script src="{{asset('atlas/hell/thor/js/wheel.js')}}"></script>

@stop
