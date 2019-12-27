@extends('layouts.app')

@section('content')
    <div class="jumbotron jumbotron-fluid text-center">
        <h1>Trade Area</h1>
        <p class="lead">Looking for new elePHPants? Take a look on these possibilities.</p>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                @if(!$users)
                    <div class="alert alert-info">
                        You don't have any double elePHPant to trade yet.
                    </div>
                @elseif(count($users))
                    <div class="alert alert-info mb-3">
                        Found <strong>{{ $users->total() }} {{ \Illuminate\Support\Str::plural('user', $users->total()) }}</strong> that can trade with you.
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
                                <div data-controller="ping" data-ping-id="{{ $user->id }}">
                                    <div class="message-box alert alert-success mb-0" style="display: none;">🎉 The message was sent to the user! 👏👏</div>
                                    <div class="form-box">
                                        <div class="form-group mb-2">
                                            <textarea name="message" id="" class="form-control" rows="2" placeholder="Hey, just saw you're looking for an elePHPant I have double. Let's trade?" data-target="ping.message"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary" data-action="ping#send">Send message to user</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="d-flex custom-pagination">
                        <div class="mx-auto">{{ $users->links() }}</div>
                    </div>
                @else
                    <div class="alert alert-info">
                        No users found that can trade with you.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
