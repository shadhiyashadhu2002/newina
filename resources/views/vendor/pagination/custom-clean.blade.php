@if ($paginator->hasPages())
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 0; flex-wrap: wrap; gap: 15px;">
        {{-- Previous Link --}}
        @if ($paginator->onFirstPage())
            <span style="color: #ccc; font-size: 14px;">« Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" style="color: #ac0742; text-decoration: none; font-size: 14px;">« Previous</a>
        @endif

        {{-- Page Numbers --}}
        <div style="display: flex; gap: 8px;">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span style="color: #999;">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: #ac0742; color: white; border-radius: 50%; font-size: 14px; font-weight: bold;">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border: 1px solid #ddd; border-radius: 50%; color: #ac0742; text-decoration: none; font-size: 14px; background: white;">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" style="background: #ac0742; color: white; padding: 8px 20px; border-radius: 20px; text-decoration: none; font-size: 14px;">Next »</a>
        @else
            <span style="color: #ccc; font-size: 14px;">Next »</span>
        @endif
    </div>
    
    {{-- Results Count --}}
    <div style="text-align: center; color: #666; font-size: 13px; margin-top: 10px;">
        Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
    </div>
@endif
