@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="text-center mb-4">
                    <h1>Trade Area</h1>
                    <p class="lead">Looking for new elePHPants? Take a look on these possibilities.</p>
                </div>
                @if($count = count($users))
                    <div class="alert alert-info mb-3">
                        Found <strong>{{ $count }} {{ \Illuminate\Support\Str::plural('user', $count) }}</strong> that can trade with you.
                    </div>
                    @foreach($users as $user)
                        <div class="card mb-4">
                            <div class="card-header">
                                @include('trade._user')
                            </div>
                            <div class="card-body">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        User's double elePHPants you don't have yet:
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        @foreach($user->elephpantsToTrade as $elephpant)
                                            @include('trade._elephpant')
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        User's looking for these ones you have double:
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        @foreach($user->elephpantsInterested as $elephpant)
                                            @include('trade._elephpant')
                                        @endforeach
                                    </ul>
                                </div>
                                <div>
                                    <div class="form-group mb-2">
                                        <textarea name="message" id="" class="form-control" rows="2" placeholder="Hey, just saw you're looking for an elePHPant I have double. Let's trade?"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Send message to user</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-info">
                        No users found that can trade with you.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
