@if ($paginator->hasPages())
<nav style="display:flex;align-items:center;justify-content:space-between;padding-top:1.5rem;border-top:1px solid rgba(201,168,76,.12)">

    <span style="font-family:'Cormorant Garamond',serif;font-size:0.8rem;letter-spacing:.08em;color:#a8916e">
        {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} sur {{ $paginator->total() }}
    </span>

    <div style="display:flex;gap:0.2rem;align-items:center">

        @if ($paginator->onFirstPage())
            <span style="padding:0.3rem 0.75rem;border-radius:2px;color:#4a3a28;font-family:'Cormorant Garamond',serif;font-size:0.8rem;letter-spacing:.1em;cursor:default">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               style="padding:0.3rem 0.75rem;border-radius:2px;color:#a8916e;border:1px solid rgba(201,168,76,.18);font-family:'Cormorant Garamond',serif;font-size:0.8rem;letter-spacing:.1em;text-decoration:none;transition:all .2s"
               onmouseover="this.style.borderColor='#c9a84c';this.style.color='#f0d080'"
               onmouseout="this.style.borderColor='rgba(201,168,76,.18)';this.style.color='#a8916e'">‹</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span style="padding:0.3rem 0.4rem;color:#4a3a28;font-family:'Cormorant Garamond',serif;font-size:0.8rem">…</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="padding:0.3rem 0.75rem;border-radius:2px;background:#c9a84c;color:#1a1209;font-family:'Cormorant Garamond',serif;font-size:0.8rem;font-weight:700;letter-spacing:.05em">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                           style="padding:0.3rem 0.75rem;border-radius:2px;color:#a8916e;border:1px solid rgba(201,168,76,.18);font-family:'Cormorant Garamond',serif;font-size:0.8rem;letter-spacing:.05em;text-decoration:none"
                           onmouseover="this.style.borderColor='#c9a84c';this.style.color='#f0d080'"
                           onmouseout="this.style.borderColor='rgba(201,168,76,.18)';this.style.color='#a8916e'">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               style="padding:0.3rem 0.75rem;border-radius:2px;color:#a8916e;border:1px solid rgba(201,168,76,.18);font-family:'Cormorant Garamond',serif;font-size:0.8rem;letter-spacing:.1em;text-decoration:none"
               onmouseover="this.style.borderColor='#c9a84c';this.style.color='#f0d080'"
               onmouseout="this.style.borderColor='rgba(201,168,76,.18)';this.style.color='#a8916e'">›</a>
        @else
            <span style="padding:0.3rem 0.75rem;border-radius:2px;color:#4a3a28;font-family:'Cormorant Garamond',serif;font-size:0.8rem;letter-spacing:.1em;cursor:default">›</span>
        @endif

    </div>
</nav>
@endif
