@extends('EcdoSpiderMan::layouts.desktop.default')

@section('main')

    
    <div class="row">
    	<div class="col-sm-9">
            <section class="panel panel-default">
                <header class="panel-heading">
            		<i class="icon-grid"></i>&nbsp;微信Wi-Fi管理入口
                </header>
                <div class="panel-body">                    
                    <a href="{{URL::to('http://wifi.yunke.im')}}" target="_blank" class="btn btn-success">进入wifi管理后台</a>
                </div>
            </section>
        </div>
    	<div class="col-sm-3">
            <section class="panel panel-default">
                <header class="panel-heading">
                	wifi帐号和密码
                </header>
                <div class="panel-body">
                    <p>当wifi安装完成后，帐号和密码将会显示在这里</p>
                    <p>默认帐号和密码是 yunke wei5wifi</p>
                </div>
            </section>
        </div>
    </div>
    
@stop
