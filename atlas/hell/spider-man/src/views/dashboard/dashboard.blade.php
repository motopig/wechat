@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')
<div class="col-sm-9">
    
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 m-b-xm">
            <section class="panel panel-default">
                <div class="panel-body">
                    <div class="col-sm-4 text-center">
                        <a href="http://mp.weixin.qq.com" target="_blank">
                            <img alt="responsive" src="{{asset('atlas/hell/spider-man/images/guid/step1.png')}}" style="width:80%;">
                        </a>
                    </div>
                    <div class="col-sm-4 text-center">
                        <a href="{{URL::to('angel/wechat/config')}}">
                            <img alt="responsive" src="{{asset('atlas/hell/spider-man/images/guid/step2.png')}}" style="width:80%;">
                        </a>
                    </div>
                    <div class="col-sm-4 text-center">
                        <a href="{{URL::to('angel/wechat')}}">
                            <img alt="responsive" src="{{asset('atlas/hell/spider-man/images/guid/step3.png')}}" style="width:80%;">
                        </a>
                    </div>
                </div>
            </section>
            
        </div>
    </div>
    
    <div class="row">
      <div class="col-sm-6">
        <section class="panel panel-default">
          <header class="panel-heading bg-white">基本概况</header>
          <table class="table table-striped m-b-none">
                <tbody class="text-left">
                  <tr>                    
                    <td class="b-r">云号名称</td>
                    <td>{{{$tower->name}}}</td>
                  </tr>
                  <tr>                    
                    <td class="b-r">主营类目</td>
                    <td>{{{$tower->business}}}@if($tower->business_other)-{{{$tower->business_other}}}@endif</td>
                  </tr>
                  <tr>                    
                    <td class="b-r">微信绑定</td>
                    <td>
                        @if($noconfig)
                        <strong><a href="{{URL::to('angel/wechat/config')}}" class="btn btn-xs btn-danger">立即绑定</a></strong>
                        @else
                        <strong><a href="{{URL::to('angel/wechat/config')}}" class="btn btn-xs btn-success">已经绑定</a></strong>
                        @endif
                    </td>
                  </tr>
              
                  <tr>                    
                    <td class="b-r">当前套餐</td>
                    <td>免费版本&nbsp; <a href="{{URL::to('angel/order/'.$tower->encrypt_id)}}" class="btn btn-xs btn-success">升级</a></td>
                  </tr>
                  <tr>                    
                    <td class="b-r">创建时间</td>
                    <td>{{{$tower->created_at}}}</td>
                  </tr>
              
              </tbody>
          </table>
        </section>
      </div>

      <div class="col-sm-6">
        <section class="panel panel-default">
          <header class="panel-heading">摇一摇</header>
          <table class="table table-striped m-b-none">
                <tbody class="text-left">
                  <tr>                    
                    <td class="b-r">设备总数</td>
                    <td>{{$shakearoundCount['count']}}</td>
                  </tr>
                  <tr>                    
                    <td class="b-r">已激活设备</td>
                    <td>{{$shakearoundCount['1_count']}}</td>
                  </tr>
                  <tr>                    
                    <td class="b-r">未激活设备</td>
                    <td>{{$shakearoundCount['0_count']}}</td>
                  </tr>
              
                  <tr>                    
                    <td class="b-r">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>                    
                    <td class="b-r">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
              </tbody>
          </table>
        </section>
      </div>
    </div>

</div>
<div class="col-sm-3">
      <div class="tags m-b-lg l-h-2x">
        <a href="{{URL::to('angel/shop')}}" class="block" style="border:1px solid #eee;paddding:15px;">
            <img alt="responsive" src="{{asset('atlas/hell/spider-man/images/guid/shop.png')}}" style="width:100%;">
        </a>
      </div>
      <h5 class="font-bold">微信新闻</h5>
      <div>
        <article class="media">
          稍后开启
        </article>
      </div>
    </div>

@stop
