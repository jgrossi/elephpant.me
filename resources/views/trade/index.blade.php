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
                                @include('trade._possible_deal')
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
