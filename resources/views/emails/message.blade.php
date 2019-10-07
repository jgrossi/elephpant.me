@component('mail::message')
# Trade request

Someone wants to trade elePHPants with you.

Message: {{ $message }}

Sent by: {{ $sender->name }}

@if($sender->twitter)
Twitter: <a href="https://twitter.com/{{ $sender->twitter }}">Link to Twitter account</a>
@endif

@component('mail::button', ['url' => route('herds.show', $sender->username)])
Sender's collection
@endcomponent

Thanks,<br>
ElePHPant.me
@endcomponent
