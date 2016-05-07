@extends($noconfig ? 'EcdoSpiderMan::layouts.clear.noside' : 'EcdoSpiderMan::layouts.desktop.default')


@section('main')
<div class="row">
	<div class="col-sm-9">
        	<section class="panel panel-default">
            	
                <header class="panel-heading text-right bg-light">
                  <ul class="nav nav-tabs pull-left">
                    <li class="active">
                      <a href="#tab-auto" data-toggle="tab">
                        <i class="fa fa-wechat"></i>&nbsp;微信授权
                      </a>
                    </li>

                    <!-- <li class="">
                      <a href="#tab-config" data-toggle="tab">
                        <i class="fa fa-cubes"></i>&nbsp;手动配置
                      </a>
                    </li> -->
                  </ul>
                  <span class="hidden-sm">&nbsp;</span>
                </header>
                
                <div class="panel-body">
    				<div class="tab-content">
    				    <div class='tab-pane fade active in' id="tab-auto">
    				        <h5 class="text-success m-b-md">绑定微信公众号，联通微信和云号</h5>
                            <div class="m-b-md">
                                
                            </div>
                            <!-- <a href="###" class="btn btn-lg btn-success no-hand-a" onclick="alert('敬请期待!');"> -->
                            <a href="javascript:void(0);" class="btn btn-lg btn-success no-hand-a">
                                <i class="fa fa-wechat" style="color:#fff;font-size:20px;"></i>&nbsp;已有微信公众号，立即设置
                            </a>
                            
                            <div class="col-xs-12 b-t m-t-md">
                                <span class="block text-muted padder-v">
                                    微信公众号的类型和区别，订阅号，服务号
                                </span>
                            </div>
                            
                            <table class="table table-bordered table-hover">
                                  <thead>
                                    <tr class="active">
                                      <th class="text-center">区别</th>
                                      <th class="text-center">未认证订阅号</th>
                                      <th class="text-center">认证订阅号</th>
                                      <th class="text-center">未认证服务号</th>
                                      <th class="text-center text-success">认证服务号</th>
                                    </tr>
                                  </thead>
                                  <tbody class="text-center b-a">
                                    <tr>                    
                                      <td>即时消息</td>
                                      <td><i class="fa fa-check"></i></td>
                                      <td><i class="fa fa-check"></i></td>
                                      <td><i class="fa fa-check"></i></td>
                                      <td><i class="fa fa-check"></i></td>
                                    </tr>
                                    
                                    <tr>                    
                                      <td>客户端显示</td>
                                      <td>订阅号 文件夹</td>
                                      <td>订阅号 文件夹</td>
                                      <td>好友对话列表</td>
                                      <td>好友对话列表</td>
                                    </tr>
                                    <tr>                    
                                      <td>群发消息</td>
                                      <td>1条/天</td>
                                      <td>1条/天</td>
                                      <td>4条/月</td>
                                      <td>4条/月</td>
                                    </tr>
                                    
                                    <tr>                    
                                      <td>自定义菜单</td>
                                      <td><i class="fa fa-check"></i></td>
                                      <td><i class="fa fa-check"></i></td>
                                      <td><i class="fa fa-check"></i></td>
                                      <td><i class="fa fa-check"></i></td>
                                    </tr>
                                    
                                    <tr>                    
                                      <td>高级会员功能</td>
                                      <td></td>
                                      <td>部分功能</td>
                                      <td></td>
                                      <td><i class="fa fa-check"></i> 九大高级接口</td>
                                    </tr>
                                    
                                    <tr>                    
                                      <td>周边摇一摇</td>
                                      <td></td>
                                      <td><i class="fa fa-check"></i></td>
                                      <td></td>
                                      <td><i class="fa fa-check"></i></td>                                                      
                                    </tr>
                                    
                                    <tr>                    
                                      <td>微信支付</td>
                                      <td></td>
                                      <td>部分订阅号</td>
                                      <td></td>
                                      <td><i class="fa fa-check"></i></td>                                                      
                                    </tr>
                                    
                                  </tbody>
                                </table>
                            
                            <!-- <a class="block text-muted padder-v" target="_blank" href="{{URL::to('http://kf.qq.com/faq/120911VrYVrA130805byM32u.html')}}"> -->
                            <a class="block text-muted padder-v wiki_wechat" href="javascript:void(0);">
                                <i class="fa fa-external-link"></i>&nbsp;点击查看官方介绍
                            </a>
    				    </div>
                        
                        <!-- <div class='tab-pane fade' id="tab-config">
                            <h5 class="text-success m-b-lg text-center">绑定微信公众号，联通微信和云号</h5>
                            <div class="col-xs-12 b-b m-b-md padder-v text-center">
                                进入微信公众号后台，点击左侧菜单“开发者中心”，即可看到相应AppID和AppSecret
                            </div>
                            
                            <div class="hand-setting">
                                <form class="form-horizontal" method="post" action="{{ URL::to('angel/wechat/setting') }}">
                                    <input type="hidden" name="csrf_token" id="csrf_token" value="{{{ Session::getToken() }}}" />
                                    <input type="hidden" name="csrf_guid" id="csrf_guid" value="{{Session::get('guid')}}" />
                                <div class="form-group">
                                	<label class="col-xs-4 control-label">AppID(应用ID)</label>
                                	<div class="col-xs-6">
                                		<input type="text" name="appid" class="form-control text-black">
                                        <span class="help-block">{{{ $errors->first('appid') }}}</span>
                                  	</div>
                                </div>

                                <div class="form-group">
                                    <label class="col-xs-4 control-label">AppSecret(应用密钥)</label>
                                    <div class="col-xs-6">
                                        <input type="text" name="appsecret" class="form-control text-black">
                                        <span class="help-block">{{{ $errors->first('appsecret') }}}</span>
                                    </div>
                                </div>
                        
                                <div class="line line-dashed b-b line-lg pull-in"></div>
                        
                                <div class="form-group">
                                	<label class="col-xs-4 control-label">URL(服务器地址)</label>
                                	<div class="col-xs-6">
                                       <input type="text" name="url" class="form-control text-black" readonly 
                                       value="{{url()}}/{{$guid}}/wormhole/wechat">
                                    </div>

                                    <b style="display:none;" class="badge bg-success radio-checks hand-setting-url-help" 
                                    data-toggle="tooltip" data-placement="right" data-original-title="复制到公众号后台配置url输入框中">
                                        <span class="icon-question"></span>
                                    </b>
                                </div>

                                <div class="form-group">
                                    <label class="col-xs-4 control-label">Token(令牌)</label>
                                    <div class="col-xs-6">
                                        <input type="text" readonly name="token" class="form-control text-black" value="{{$guid}}">
                                        <span class="help-block">{{{ $errors->first('token') }}}</span>
                                    </div>
                                </div>
                        
                                <div class="form-group">
                                    <label class="col-xs-4 control-label">EncodingAESKey(消息加解密密钥)</label>
                                    <div class="col-xs-6">
                                        <input type="text" name="encodingAesKey" class="form-control text-black">
                                        <span class="help-block">{{{ $errors->first('encodingAesKey') }}}</span>
                                    </div>
                                </div>
                        
                            	<div class="form-group text-center">
                                	<button type="submit" class="btn btn-success btn-s-xs hand-submit">确认</button>&nbsp;
                                    <a href="{{ URL::to('angel/dashboard') }}">
                                        <button type="button" class="btn btn-default btn-s-xs">取消</button>
                                    </a>
                            	</div>
                                </form>
                            </div>
                        </div> -->
    				</div><!--tab-content-->
            	</div><!--panel-body-->
                
        	</section>
    	
    </div>
    <div class="col-sm-3">
        <section class="panel panel-default">
          <header class="panel-heading font-bold">绑定提示</header>
          <div class="panel-body">              
            <div class="col-xs-12">
                <div class="text-left m-b-n m-t-sm">
                    <p>1. 一个微信号只能绑定一个云号</p>
                    <p>2. 建议使用微信认证服务号</p>
                </div>
            </div>
          </div>
        </section>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  $('.no-hand-a').click(function() {
    componentloginpage();
  });

  var componentloginpage = function() {
    reset = function () {
       alertify.set({
           labels : {
               ok     : "已成功设置",
               cancel : "授权失败, 重试"
           },
           delay : 5000,
           buttonReverse : false,
           buttonFocus   : "ok"
       });
    };

    reset();
    alertify.confirm("请在新窗口中完成微信公众号授权", function (e) {
       if (e) {
          window.location.href = "{{ URL::to('angel/dashboard') }}";
       } else {
          componentloginpage();
       }
    });

    window.open('{{$url}}');
  }

  $('.wiki_wechat').click(function() {
    window.open('http://kf.qq.com/faq/120911VrYVrA130805byM32u.html');
  });
});
</script>
@stop
