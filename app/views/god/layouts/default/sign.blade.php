<!doctype html>
<html lang="zh" class="app">
    <head>
        <meta charset="utf-8">
        <title>一点云客-微信智能营销平台</title>
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{{ asset('/apple-touch-icon-114x114-precomposed.png') }}}" />
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{{ asset('/apple-touch-icon-72x72-precomposed.png') }}}" />
        <link rel="apple-touch-icon-precomposed" href="{{{ asset('/apple-touch-icon-57x57-precomposed.png') }}}" />
        <link rel="shortcut icon" href="{{{ asset('/favicon.png') }}}" />
        <link rel="stylesheet" href="{{{ asset('assets/god/dist/jPlayer/jplayer.flat.css') }}}" type="text/css" />
        <link rel="stylesheet" href="{{{ asset('assets/universe/css/bootstrap.css') }}}" type="text/css" />
        <link rel="stylesheet" href="{{{ asset('assets/universe/css/font-awesome.min.css') }}}" type="text/css" />
        <link rel="stylesheet" href="{{{ asset('assets/god/css/animate.css') }}}" type="text/css" />
        <link rel="stylesheet" href="{{{ asset('assets/god/css/simple-line-icons.css') }}}" type="text/css" />
        <link rel="stylesheet" href="{{{ asset('assets/god/css/app.css') }}}" type="text/css" />
        @yield('styles')
    </head>
    <body class="bg-info dker">
  <section id="content" class="m-t-lg wrapper-md animated fadeInUp">
    @yield('main')
  </section>
  <footer id="footer">
    <div class="text-center padder">
      <p>
        © 云客
      </p>
    </div>
  </footer>
    <script src="{{{ asset('assets/universe/js/jquery.min.js') }}}"></script>
    <script src="{{{ asset('assets/universe/js/bootstrap.min.js') }}}"></script>
    <script src="{{{ asset('assets/universe/js/hell.js') }}}"></script>
    <script src="{{{ asset('assets/god/js/app.js') }}}"></script>
    <script src="{{{ asset('assets/god/dist/slimscroll/jquery.slimscroll.min.js') }}}"></script>
    <script src="{{{ asset('assets/god/js/app.plugin.js') }}}"></script>
    <script src="{{{ asset('assets/god/dist/jPlayer/jquery.jplayer.min.js') }}}"></script>
    <script src="{{{ asset('assets/god/dist/jPlayer/add-on/jplayer.playlist.min.js') }}}"></script>
    <script src="{{{ asset('assets/god/dist/jPlayer/demo.js') }}}"></script>
    @yield('scripts')
	</body>
</html>