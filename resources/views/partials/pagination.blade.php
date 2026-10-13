<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-4">
    <p class="small text-body-secondary mb-0">
        {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} / {{ $paginator->total() }} találat
    </p>
    @if ($paginator->hasPages())
        <nav aria-label="Lapozás">
            <ul class="pagination pagination-sm flex-wrap mb-0">
                <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    @if ($paginator->onFirstPage())
                        <span class="page-link" aria-disabled="true">Előző</span>
                    @else
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Előző</a>
                    @endif
                </li>
                <li class="page-item active" aria-current="page">
                    <span class="page-link">{{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>
                </li>
                <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                    @if ($paginator->hasMorePages())
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Következő</a>
                    @else
                        <span class="page-link" aria-disabled="true">Következő</span>
                    @endif
                </li>
            </ul>
        </nav>
    @endif
</div>
