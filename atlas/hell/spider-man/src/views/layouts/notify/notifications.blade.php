@if ($message = Session::get('success'))
<div class="yunke-alert animated fadeInDown ">
<div class="alert alert-success">
    <button class="close" data-dismiss="alert" type="button">×</button>
    <strong><i class="icon-check"></i></strong>&nbsp;
    {{ $message }}
</div>
</div>
@endif

@if ($message = Session::get('error'))
<div class="yunke-alert animated fadeInDown">
<div class="alert alert-warning">
    <button class="close" data-dismiss="alert" type="button">×</button>
    <strong><i class="fa fa-info-circle"></i></strong>&nbsp;
    {{ $message }}
</div>
</div>
@endif
<script>
    $(document).ready(function(){
        if($('.yunke-alert').children('.alert').hasClass('alert-success')){
            $('.yunke-alert').delay(2000).animate({'top':'-50px',opacity:0.2}, 200).fadeOut('fast');
        }
    });
</script>
