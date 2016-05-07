<link href="{{asset('atlas/hell/spider-man/css/modal-dialog.css')}}" rel="stylesheet" />

<div class="bolt-modal-url" data-url="{{ URL::to('angel/modal') }}"></div>
<div class="bolt-modal-preview-url" data-url="{{ URL::to('angel/modalPreview') }}"></div>
<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" 
aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="hellCsrfToken" csrfToken="{{Session::getToken()}}"></div>
  <div class="hellCsrfGuid" CsrfGuid="{{Session::get('guid')}}" /></div>
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body" id="myModalBody"></div>
    </div>
  </div>
</div>

<input type="hidden" value="{{URL::to('/')}}" class="root_url">


<script src="{{asset('atlas/hell/spider-man/js/modal-dialog.js')}}"></script>
<script src="{{asset('atlas/hell/spider-man/js/wechat_emoji.js')}}"></script>