@if ($message = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <div class="alert-body">
        <button type="button"
            class="close"
            data-dismiss="alert">
            <span aria-hidden="true">×</span>
        </button>
        <p>{{ $message }}</p>
    </div>
</div>
@endif
