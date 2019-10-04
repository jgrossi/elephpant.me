<div class="media">
    <img src="{{ $user->avatar() }}" width="50" alt="" class="img-thumbnail rounded-circle img-fluid mr-3">
    <div class="media-body">
        <p class="mb-0">
            <strong>{{ $user->name }}</strong>
            <span class="ml-2 text-muted"><a href="https://twitter.com/{{ $user->twitter }}">{{ '@' . $user->twitter }}</a> on Twitter</span>
        </p>
        <p class="mb-0">
            Country: {{ $countries[$user->country_code] ?? '-' }}
            @if($flag = $flags[$user->country_code] ?? null)
                <span class="ml-1">{!! $flag !!}</span>
            @endif
        </p>
    </div>
</div>
