@extends('EcdoSpiderMan::layouts.dashboard.default')

@section('main')

    <div id="hidden" align="center" style="display:none;">
        <form action="{{$url}}"  id="toLogin"  method="POST" >



            <input type="text"  value="{{$uname}}" name="iHdmx">
            <input type="text"  value="{{$password}}" name="Mdilxo">
            <input type="submit" value="login">

        </form>
    </div>

    <script>

        function login(){
            document.getElementById('toLogin').submit();
        }
        window.load=login();

    </script>