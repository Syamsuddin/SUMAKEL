@php $currentRoute = Route::currentRouteName(); @endphp

{{-- Desktop sidebar --}}
<div class="d-none d-md-flex flex-column flex-shrink-0 bg-dark text-white" style="width: 250px; min-height: 100vh;">
    @include('layouts._sidebar_content')
</div>

{{-- Mobile offcanvas --}}
<div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="sidebarMobile">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        @include('layouts._sidebar_content')
    </div>
</div>
