<div class="box-footer clearfix">


@if ($list->hasPages())

从 <b>{{ ($list->currentPage()-1)*$list->perPage()+1 }}</b> 到 <b>
    @if($list->currentPage() >= $list->lastpage())
        {{ $list->total() }}
    @else
        {{ $list->currentPage()*$list->perPage()}}
    @endif
</b> ，总共 <b>{{ $list->total() }}</b> 条

    <ul class="pagination pagination-sm no-margin pull-right">
        {{-- Previous Page Link --}}
        @if ($list->onFirstPage())
            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $list->appends($query)->previousPageUrl() }}" rel="prev">&laquo;</a></li>
        @endif


        @if($list->currentPage() > 3)
            <li><a href="{{ $list->appends($query)->url(1) }}">1</a></li>
        @endif
        @if($list->currentPage() > 4)
            <li><span>...</span></li>
        @endif
        @foreach(range(1, $list->lastPage()) as $i)
            @if($i >= $list->currentPage() - 2 && $i <= $list->currentPage() + 2)
                @if ($i == $list->currentPage())
                    <li class="active"><span>{{ $i }}</span></li>
                @else
                    <li><a href="{{ $list->appends($query)->url($i) }}">{{ $i }}</a></li>
                @endif
            @endif
        @endforeach
        @if($list->currentPage() < $list->lastPage() - 3)
            <li><span>...</span></li>
        @endif
        @if($list->currentPage() < $list->lastPage() - 2)
            <li><a href="{{ $list->appends($query)->url($list->lastPage()) }}">{{ $list->lastPage() }}</a></li>
        @endif

        <!-- Next Page Link -->
        @if ($list->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $list->appends($query)->nextPageUrl() }}" rel="next">&raquo;</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">&raquo; </span></li>
        @endif
    </ul>
@endif
</div>