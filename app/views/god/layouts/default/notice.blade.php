@if ($errors->any())
<div class="alert alert-danger">
    <button class="close" data-dismiss="alert" type="button" style="top:-20px;">×</button>
    <strong><i class="icon-warning-sign"></i></strong>&nbsp;
    请检查下面表单中的错误！
</div>
@endif

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <button class="close" data-dismiss="alert" type="button" style="top:-20px;">×</button>
    <strong><i class="icon-ok"></i></strong>&nbsp;
    {{ $message }}
</div>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-danger">
    <button class="close" data-dismiss="alert" type="button" style="top:-20px;">×</button>
    <strong><i class="icon-remove-sign"></i></strong>&nbsp;
    {{ $message }}
</div>
@endif
