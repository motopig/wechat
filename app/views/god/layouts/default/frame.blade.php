<!doctype html>
<html lang="en" class="app">
<head>
<meta charset="utf-8">
<title>一点云客 | 移动智能营销平台</title>
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{{ asset('/apple-touch-icon-114x114-precomposed.png') }}}" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{{ asset('/apple-touch-icon-72x72-precomposed.png') }}}" />
<link rel="apple-touch-icon-precomposed" href="{{{ asset('/apple-touch-icon-57x57-precomposed.png') }}}" />
<link rel="shortcut icon" href="{{{ asset('/favicon.png') }}}" />
<link rel="stylesheet" href="{{{ asset('assets/god/dist/jPlayer/jplayer.flat.css') }}}" type="text/css" />
<link rel="stylesheet" href="{{{ asset('assets/universe/css/bootstrap.min.css') }}}" type="text/css" />
<link rel="stylesheet" href="{{{ asset('assets/universe/css/font-awesome.min.css') }}}" type="text/css" />
<link rel="stylesheet" href="{{{ asset('assets/god/css/animate.css') }}}" type="text/css" />
<link rel="stylesheet" href="{{{ asset('assets/god/css/simple-line-icons.css') }}}" type="text/css" />
<link rel="stylesheet" href="{{{ asset('assets/god/css/app.css') }}}" type="text/css" />
@yield('styles')
</head>
<body>
  <section class="vbox">
    <header class="bg-black header header-md navbar navbar-fixed-top-xs">@include('god.layouts.default.navbar')</header>
    <section>
      <section class="hbox stretch">
        <aside class="bg-white dk aside-sm hidden-print" id="nav">
          <section class="vbox">
            <section class="w-f-md scrollable">
              <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="10px" data-railOpacity="0.2">@include('god.layouts.default.sidebar')</div>
            </section>
          </section>
        </aside>
        <section id="content">
          <section class="vbox">
            <section class="scrollable">
              <section class="hbox stretch">@yield('main')</section>
              <section class="foot text-center">
                <div class="hellCsrfToken" csrfToken="{{{Session::getToken()}}}"></div>
                  <p>
                    <div>&copy; <a href="http://www.yunke.im" target="_blank">一点云客</a></div>
                  </p>
              </section>
            </section>
          </section>
          <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen,open" data-target="#nav,html"></a>
        </section>
      </section>
    </section>
  </section>
  <script src="{{{ asset('assets/universe/js/jquery.min.js') }}}"></script>
  <script src="{{{ asset('assets/universe/js/bootstrap.min.js') }}}"></script>
  <script src="{{{ asset('assets/universe/js/hell.js') }}}"></script>
  <script src="{{{ asset('assets/god/js/app.js') }}}"></script>
  <script src="{{{ asset('assets/god/dist/slimscroll/jquery.slimscroll.min.js') }}}"></script>
  <script src="{{{ asset('assets/god/dist/charts/easypiechart/jquery.easy-pie-chart.js') }}}"></script>
  <script src="{{{ asset('assets/god/js/app.plugin.js') }}}"></script>
  <script src="{{{ asset('assets/god/dist/jPlayer/jquery.jplayer.min.js') }}}"></script>
  <script src="{{{ asset('assets/god/dist/jPlayer/add-on/jplayer.playlist.min.js') }}}"></script>
  <script src="{{{ asset('assets/god/dist/jPlayer/demo.js') }}}"></script>
  @yield('scripts')
</body>
</html>