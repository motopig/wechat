@extends('EcdoSpiderMan::layouts.dashboard.default')

@section('main')
 
  <div class="row dashboard_row">
      
  	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row row-sm m-t-lg">
            <div class="row text-center">
                <h4>选择云号</h4>
            </div>
            <div class="col-xs-1 col-sm-3 col-md-4 col-lg-4">
                &nbsp;
            </div>
            <div class="col-xs-10 col-sm-6 col-md-4 col-lg-4 m-t-lg">
               <div style="margin:0 auto;width:80%;max-width:320px;">
                   @if($tower)
                   <div class="tower_list block row r-2x">
                       <ul>
                       @foreach ($tower as $t)
                             <li id="{{$t->encrypt_id}}">
                                 <a href="{{ URL::to('angel/chTower/'.$t->encrypt_id) }}">
                                   <div class="logo">
                                       <img alt="responsive" src="{{{ asset('atlas/hell/spider-man/images/tower_icon.png') }}}" class="r r-2x">
                                   </div>
                                   <div class="name">
                                       {{$t->name}}
                                   </div>
                                 </a>
                             </li>
                       @endforeach
                       </ul>
                   </div>
                   @endif
                
                   @if (Session::get(Auth::angel()->get()->encrypt_id . '_grade') > 0)
                   <div class="block row tower_list tower_create m-t-md">
                       <ul>
                           <li class="tower_create">
                               <a href="{{ URL::to('angel/crTower') }}">
                                   <div class="name"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp;创建云号</div>
                               </a>
                           </li>
                       </ul>
                   </div>
                   @elseif (Session::get(Auth::angel()->get()->encrypt_id . '_grade') == 0 && count($tower) == 0)
                   <!--客服才能看到这里-->
                   @endif
               </div>
            </div>
            
            <div class="col-xs-1 col-sm-3 col-md-4 col-lg-4">
                &nbsp;
            </div>
        </div>
      </div>
      
  	<div class="col-xs-12 col-sm-3 col-md-3" style="display:none;"> 
      <section class="panel panel-default">
            <div class="panel-body" style="overflow:hidden;">
                <div class="clear">
                    <ul class="list-group no-radius m-b-none m-t-n-xxs no-border" style="font-size:13px;">
                        <li class="list-group-item">
                            <i class="fa fa-cloud"></i>&nbsp;当前版本: yunke v2.0
                        </li>
                        <li class="list-group-item">
                            <div class="text-center">扫码获取支持<br>
                            <img alt="responsive" style="width:100%;max-width:120px;" src="{{asset('atlas/hell/spider-man/images/qrcode.jpg')}}">
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
      </div>
      
 
  </div>
  
  
  

@stop
