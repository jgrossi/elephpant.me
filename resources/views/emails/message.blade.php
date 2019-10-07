@component('mail::message')
# Trade request

Someone wants to trade elePHPants with you.

Message: {{ $message }}

Sent by: {{ $sender->name }}

Twitter: <a href="https://twitter.com/{{ $sender->twitter }}">Link to Twitter account</a>

@component('mail::button', ['url' => route('herds.show', $sender->twitter)])
Sender's collection
@endcomponent

Thanks,<br>
ElePHPant.me
@endcomponent
