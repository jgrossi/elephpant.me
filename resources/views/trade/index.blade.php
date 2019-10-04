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
                        Found <strong>{{ $count }} users</strong> that can trade with you.
                    </div>
                    @foreach($users as $user)
                        <div class="card mb-4">
                            <div class="card-header">
                                <div class="float-left mr-3">
                                    <img src="{{ $user->avatar() }}" width="50" alt="" class="img-thumbnail rounded-circle img-fluid">
                                </div>
                                <p class="mb-0">
                                    <strong>{{ $user->name }}</strong>
                                    <span class="ml-2 text-muted"><a href="#">{{ '@' . $user->twitter }}</a> on Twitter</span>
                                </p>
                                <p class="mb-0">Country: {{ $user->country_code }}</p>
                            </div>
                            <div class="card-body">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        User's double elePHPants you don't have yet:
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        @foreach($user->elephpantsToTrade as $elephpant)
                                            <li class="list-group-item align-items-center">
                                                <div class="float-right">
                                                    <span class="badge badge-pill badge-secondary">{{ $elephpant->pivot->quantity - 1 }}</span>
                                                </div>
                                                <div>
                                                    <div class="float-left mr-3">
                                                        <img src="{{ asset('elephpants/' . $elephpant->image) }}" width="50" alt="" class="img-thumbnail rounded img-fluid">
                                                    </div>
                                                    <p class="mb-0"><strong>{{ $elephpant->name }}</strong> <em>({{ $elephpant->description }})</em></p>
                                                    <p class="mb-0">By {{ $elephpant->sponsor }}</p>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        User's looking for these ones you have double:
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        @foreach($user->elephpantsInterested as $elephpant)
                                            <li class="list-group-item">
                                                <div class="float-right">
                                                    <span class="badge badge-pill badge-secondary">{{ $elephpant->pivot->quantity - 1 }}</span>
                                                </div>
                                                <div>
                                                    <div class="float-left mr-3">
                                                        <img src="{{ asset('elephpants/' . $elephpant->image) }}" width="50" alt="" class="img-thumbnail rounded img-fluid">
                                                    </div>
                                                    <p class="mb-0"><strong>{{ $elephpant->name }}</strong> <em>({{ $elephpant->description }})</em></p>
                                                    <p class="mb-0">By {{ $elephpant->sponsor }}</p>
                                                </div>
                                            </li>
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
