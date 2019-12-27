@extends('layouts.app')

@section('content')
<div class="jumbotron jumbotron-fluid text-center">
    <h1>Welcome, collector! 🐘</h1>
    <p class="lead">
        Here is the right place for your elePHPants.<br>
        You can <a href="{{ route('herds.edit') }}">update your herd</a>, see <a href="{{ route('rankings.index') }}">ranking</a> (global / per country), and
        <a href="{{ route('trades.index') }}">find people to trade</a>.
    </p>
    @guest
        <hr class="mt-4">
        <a class="btn btn-primary" href="{{ route('register') }}" role="button">Register</a>
        <a class="btn btn-outline-secondary" href="{{ route('login') }}" role="button">Login</a>
    @endguest
</div>
<div class="container">
    <div class="lead text-center mb-4">
        <strong>Total of existent species:</strong> {{ count($elephpants) }}
    </div>
    <div class="row">
        @foreach($elephpants as $elephpant)
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <div class="card d-flex">
                    <img src="{{ asset('storage/elephpants/' . $elephpant->image) }}" class="card-img-top" alt="{{ $elephpant->name }}">
                    <div class="card-body">
                        <h5 class="card-title">#{{ $elephpant->id }} - {{ $elephpant->name }}</h5>
                        <p class="card-text">
                            {{ $elephpant->description }}<br>
                            <strong>{{ $elephpant->sponsor }}</strong><br>
                            {{ $elephpant->year }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
