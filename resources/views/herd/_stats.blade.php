@php
    $isShow = request()->routeIs('herds.show');
    $boxes = [
        ['label' => 'Unique', 'value' => $stats['unique'], 'filter' => 'unique'],
        ['label' => 'Double', 'value' => $stats['double'], 'filter' => 'double'],
        ['label' => 'Total', 'value' => $stats['total'], 'filter' => 'total'],
    ];
@endphp
<div id="stats-content" class="row mt-4">
    @foreach($boxes as $box)
        <div class="col-12 col-md-4 mb-2 mb-md-0">
            @if($isShow)
                <a href="{{ url()->current() . ($box['filter'] === 'total' ? '' : '?filter=' . $box['filter']) }}" class="text-decoration-none text-reset">
            @endif
            <div class="jumbotron py-2 mb-0">
                <div class="container">
                    <p class="lead mb-1">{{ $box['label'] }}</p>
                    <h1 class="display-5 mb-0">{{ $box['value'] }}</h1>
                </div>
            </div>
            @if($isShow)
                </a>
            @endif
        </div>
    @endforeach
</div>
