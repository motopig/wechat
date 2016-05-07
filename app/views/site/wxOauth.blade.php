<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{{ asset('/apple-touch-icon-114x114-precomposed.png') }}}" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{{ asset('/apple-touch-icon-72x72-precomposed.png') }}}" />
<link rel="apple-touch-icon-precomposed" href="{{{ asset('/apple-touch-icon-57x57-precomposed.png') }}}" />
<link rel="shortcut icon" href="{{{ asset('/favicon.png') }}}" />
<link href="{{{ asset('assets/universe/css/bootstrap.min.css') }}}" rel="stylesheet" />
<link href="{{{ asset('assets/universe/css/font-awesome.min.css') }}}" rel="stylesheet" />
<link href="{{{ asset('atlas/hell/spider-man/css/simple-line-icons.css') }}}" rel="stylesheet" />
<link href="{{{ asset('atlas/hell/spider-man/css/font.css') }}}" rel="stylesheet" />
<link href="{{{ asset('atlas/hell/spider-man/css/app.css') }}}" rel="stylesheet" />
<link href="{{{ asset('atlas/hell/spider-man/css/alertify/alertify.bootstrap.css') }}}" rel="stylesheet" />
<link href="{{{ asset('atlas/hell/spider-man/css/alertify/alertify.core.css') }}}" rel="stylesheet" />
<script src="{{{ asset('assets/universe/js/jquery.min.js') }}}"></script>
<script src="{{{ asset('atlas/hell/spider-man/js/alertify/alertify.js') }}}"></script>
<title>一点云客 | 第三方公众平台托管授权</title>
</head>
<body>
    <div class="row">
	<div class="col-sm-9">
        	<section class="panel panel-default">
            	 <span class="wx_oauth_res" data-val="@if (isset($res)) {{$res['errmsg']}} @endif"></span>
               <span class="wx_oauth_url" data-url="@if (isset($url)) {{$url}} @endif"></span>

                <header class="panel-heading text-right bg-light">
                  <ul class="nav nav-tabs pull-left">
                    <li class="active">
                      <a href="#tab-auto" data-toggle="tab">
                        <i class="fa fa-wechat"></i>&nbsp;微信授权
                      </a>
                    </li>
                  </ul>
                  <span class="hidden-sm">&nbsp;</span>
                </header>
                
                <div class="panel-body">
    				<div class="tab-content">
    				    <div class='tab-pane fade active in' id="tab-auto">
    				        <h5 class="text-success m-b-md">绑定微信公众号，联通微信和云号</h5>
                            <div class="m-b-md">
                                
                            </div>
                            @if (isset($res))
                            <a href="javascript:void(0);" class="btn btn-lg btn-info">
                                <i class="fa fa-wechat" style="color:#fff;font-size:20px;"></i>&nbsp;{{$res['errmsg']}}
                            @else
                            <a href="javascript:void(0);" class="btn btn-lg btn-success no-hand-a">
                                <i class="fa fa-wechat" style="color:#fff;font-size:20px;"></i>&nbsp;已有微信公众号，立即设置
                            @endif
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
                            
                            <a class="block text-muted padder-v wiki_wechat" href="javascript:void(0);">
                                <i class="fa fa-external-link"></i>&nbsp;点击查看官方介绍
                            </a>
    				    </div>
                        
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

<footer style="left:50%;font-size:12px;position:absolute;bottom:20px;">
  © <a href="{{\URL::to('/')}}" target="_blank">一点云客</a>
</footer>

</body>

<script type="text/javascript">
  $(document).ready(function() {
    if ($('.wx_oauth_res').attr('data-val') != '') {
      setTimeout(function() {
        alertify.success($('.wx_oauth_res').attr('data-val'));
        var userAgent = navigator.userAgent;

        if (userAgent.indexOf("Firefox") != -1 || userAgent.indexOf("Presto") != -1) {
            window.location.replace("about:blank");
        } else {
            window.opener = null;
            window.open("", "_self");
            window.close();
        }
      }, 2000);
    }

    $('.no-hand-a').click(function() {
      window.open($('.wx_oauth_url').attr('data-url'));
    });

    $('.wiki_wechat').click(function() {
      window.open('http://kf.qq.com/faq/120911VrYVrA130805byM32u.html');
    });
  });
</script>
</html>
