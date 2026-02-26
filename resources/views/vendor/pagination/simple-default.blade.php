@if ($paginator->hasPages())
    <nav>
        <ul style="list-style:none; display:flex; gap:0.5rem; flex-wrap:wrap;">
            @if ($paginator->onFirstPage())
                <li><span>« Previous</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}">« Previous</a></li>
            @endif
            @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                <li>
                    @if ($page == $paginator->currentPage())
                        <span style="font-weight:bold;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                </li>
            @endforeach
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}">Next »</a></li>
            @else
                <li><span>Next »</span></li>
            @endif
        </ul>
    </nav>
@endif
