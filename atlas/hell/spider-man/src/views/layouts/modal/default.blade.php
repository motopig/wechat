<!doctype html>
<html class="app" lang="en">
    <head>
        <meta charset="utf-8">
        <title>云客 :: mobile e-Commerce system</title>
        @include('EcdoSpiderMan::layouts.modal.header')
        @yield('styles')
    </head>
    <body class="bg-info dker">
    @yield('main')
    @include('EcdoSpiderMan::layouts.modal.footer')
    @yield('scripts')
    </body>
</html>