<style>
.qrcode{
    padding:5px;
}
</style>
<div class="form-group padder-v text-center">

    <div class="col-xs-12 m-b-sm text-success">请使用{{{$order['pay_method_name']}}}</div>
    <div class="col-xs-12 m-b-sm">请在2小时内完成支付</div>
    @if($pay_result && array_key_exists('code_url',$pay_result))
    <a href="javascript:void(0);" class="btn btn-default btn-xs boltClick" 
        bolt-url="{{URL::to('/angel/order/payqrcodehtml/'.$pay_result['code_url_encode'])}}" bolt-modal="&nbsp;微信扫码支付" bolt-modal-icon="fa fa-qrcode">
        <img class="qrcode" src="{{URL::to('/qrcode/'.$pay_result['code_url_encode'])}}">
    </a>
    @else
        {{{$errmsg}}}
    @endif
</div>