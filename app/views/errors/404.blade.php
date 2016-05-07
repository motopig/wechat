@extends('errors.default')

@section('main')
<section class="general_content_holder">
	<div class="container" style="text-align:center;">
    	<h1>找不到您 <span>访问的页面!</span> </h1>
        <img src="{{{ asset('/404_image.gif') }}}">
    </div>
</section>
@stop