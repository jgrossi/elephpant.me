@extends('layouts.app')

@section('content')
    <div class="jumbotron jumbotron-fluid text-center">
        <h1>Message History</h1>
        <p class="lead">Your message history</p>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                @if(!$interlocutorMessages)
                    <div class="alert alert-info">
                        You don't have any messages yet.
                    </div>
                    @else
                        @foreach ($interlocutorMessages as $messages)
                            @php ($showInterlocutor = true)
                            @include('messages/_messagesList')
                        @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
