@extends('god.layouts.default.frame')
@section('main')
<div class="row-fluid sortable">
	平台后台首页
</div>   
<div class="hellCsrfToken" csrfToken="{{Session::getToken()}}"></div>
<div bolt-url="god/test" bolt-modal="FormTest" bolt-modal-form="frmTest" class="btn boltClick">一点云客</div>
@stop
