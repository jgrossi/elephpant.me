<div class="media">
    <img src="{{ $user->avatar() }}" width="50" alt="" class="img-thumbnail rounded-circle img-fluid mr-3">
    <div class="media-body">
        <p class="mb-0">
            <strong>{{ $user->name }}</strong>
            <span class="ml-2 text-muted"><a href="https://twitter.com/{{ $user->twitter }}">{{ '@' . $user->twitter }}</a> on Twitter</span>
        </p>
        <p class="mb-0">
            Country:
            @if($country = $countries->get($user->country_code))
                {{ $country->get('name') }}
            @else
                N/A
            @endif
            @if($flag = $country->get('flag'))
                <span class="ml-1">{!! $flag !!}</span>
            @endif
        </p>
    </div>
</div>
