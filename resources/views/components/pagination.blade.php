@props(['paginator'])
@if($paginator->total() > 0)
<div class="sk-pagination">
    <div>Menampilkan {{ $paginator->firstItem() }}&ndash;{{ $paginator->lastItem() }} dari {{ $paginator->total() }}</div>
    @if($paginator->hasPages())
        {{ $paginator->withQueryString()->links() }}
    @endif
</div>
@endif
