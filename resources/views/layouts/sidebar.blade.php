{{-- Desktop sidebar --}}
<aside class="sk-sidebar d-none d-lg-block" aria-label="Navigasi utama">
    <div class="sk-sidebar-inner">
        @include('layouts._sidebar_content')
    </div>
</aside>

{{-- Mobile offcanvas --}}
<div class="offcanvas offcanvas-start sk-offcanvas d-lg-none" tabindex="-1" id="sidebarMobile" aria-labelledby="sidebarMobileLabel">
    <div class="offcanvas-header">
        <h2 class="offcanvas-title h6 mb-0 text-white" id="sidebarMobileLabel">Menu</h2>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Tutup menu"></button>
    </div>
    <div class="offcanvas-body p-0 d-flex flex-column">
        @include('layouts._sidebar_content')
    </div>
</div>
