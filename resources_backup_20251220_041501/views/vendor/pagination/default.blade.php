@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        {{-- Previous Page Link --}}
        <div>
            @if ($paginator->onFirstPage())
                <span style="color: #999; font-size: 14px; cursor: not-allowed;">« Previous</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="color: #ac0742; text-decoration: none; font-size: 14px; font-weight: 500;">« Previous</a>
            @endif
        </div>

        {{-- Pagination Elements --}}
        <div style="display: flex; gap: 8px; align-items: center;">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span style="color: #999; padding: 0 5px;">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span style="display: inline-block; min-width: 36px; height: 36px; line-height: 36px; text-align: center; background: #ac0742; color: white; border-radius: 50%; font-size: 14px; font-weight: bold; cursor: default;">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" style="display: inline-block; min-width: 36px; height: 36px; line-height: 36px; text-align: center; color: #ac0742; border: 1px solid #ddd; border-radius: 50%; text-decoration: none; font-size: 14px; transition: all 0.3s ease; background: white;">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        <div>
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="color: white; background: #ac0742; padding: 8px 20px; border-radius: 20px; text-decoration: none; font-size: 14px; font-weight: 500; display: inline-block;">Next »</a>
            @else
                <span style="color: #999; font-size: 14px; cursor: not-allowed;">Next »</span>
            @endif
        </div>

        {{-- Results count --}}
        <div style="width: 100%; text-align: center; margin-top: 10px; color: #666; font-size: 13px;">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
        </div>
    </nav>
@endif
