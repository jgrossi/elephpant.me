@component('mail::message')
# Trade request

Someone wants to trade elePHPants with you.

Message: {{ $message }}

Sent by: {{ $sender->name }}

@if($sender->x_handle)
X/Twitter: <a href="https://twitter.com/{{ $sender->x_handle }}">Link to X/Twitter account</a>
@endif

@if($sender->mastodon)
Mastodon: <a href="{{ $sender->mastodonUrl() }}">{{ $sender->mastodon }}</a>
@endif

@if($sender->bluesky)
Bluesky: <a href="{{ $sender->blueskyUrl() }}">{{ $sender->bluesky }}</a>
@endif

@component('mail::button', ['url' => route('herds.show', $sender->username)])
Sender's collection
@endcomponent

Thanks,<br>
ElePHPant.me
@endcomponent
