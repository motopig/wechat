@extends('EcdoSpiderMan::layouts.site.default')

@section('main')
<section id="content" class="padder-lg">
  <div class="row dashboard_row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row row-sm m-t-lg">
            <div class="col-xs-1 col-sm-3 col-md-4 col-lg-4">
                &nbsp;
            </div>
            <div class="col-xs-10 col-sm-6 col-md-4 col-lg-4 m-t-lg">
               <div style="margin:0 auto;width:80%;max-width:320px;">
                   <div class="tower_list block row r-2x">
                       <ul>
                         <li>
                             <a href="{{URL::to(Session::get('guid') . '/card/carduse')}}">
                               <div class="pull-left">
                                  <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x icon-muted"></i>
                                    <i class="fa fa-mobile fa-stack-1x text-white"></i>
                                  </span>
                               </div>
                               <div class="name">
                                   卡券核销
                               </div>
                             </a>
                         </li>
                         <li>
                             <a href="{{URL::to(Session::get('guid') . '/card/carduseLog')}}">
                               <div class="pull-left">
                                  <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x icon-muted"></i>
                                    <i class="fa fa-book fa-stack-1x text-white"></i>
                                  </span>
                               </div>
                               <div class="name">
                                   核销记录
                               </div>
                             </a>
                         </li>
                       </ul>
                   </div>
               </div>
            </div>
        </div>
      </div>
  </div>
</section>
@stop
