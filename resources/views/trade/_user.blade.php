<div class="media">
    <img src="{{ $user->avatar() }}" width="50" alt="" class="img-thumbnail rounded-circle img-fluid mr-3">
    <div class="media-body">
        <p class="mb-0">
            <strong>
                <a href="{{ route('herds.show', $user->username) }}">{{ $user->name }}</a>
            </strong>
            @if($user->x_handle)
                <div>
                    <span class="text-muted"><a href="https://twitter.com/{{ $user->x_handle }}" target="_blank">{{ '@' . $user->x_handle }}</a> on X/Twitter</span>
                </div>
            @endif
            @if($user->mastodon)
            <div>
                <span class="text-muted"><a href="{{ $user->mastodonUrl() }}" target="_blank">{{ $user->mastodon }}</a> on Mastodon</span>
            </div>
            @endif
            @if($user->bluesky)
            <div>
                <span class="text-muted"><a href="{{ $user->blueskyUrl() }}" target="_blank">{{ $user->bluesky }}</a> on Bluesky</span>
            </div>
            @endif
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
