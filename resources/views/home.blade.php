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
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="text-center mb-4">
                <div>
                    <div class="mt-4">
                        @foreach($elephpants as $elephpant)
                            <img src="{{ asset('storage/elephpants/' . $elephpant->image) }}" alt="{{ $elephpant->name }}" width="100" class="img-thumbnail mb-1">
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
