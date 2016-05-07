@if($paginator->getLastPage() > 1)
    <div class="pagination pagination-sm custom">
        {{ with(new Illuminate\Pagination\BootstrapPresenter($paginator))->render() }}
    </div>
@endif