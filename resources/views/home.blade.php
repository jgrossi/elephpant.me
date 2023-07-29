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
    <div class="lead">
        <strong>Total of existent species:</strong> {{ count($elephpants) }}
    </div>
    <p class="mb-4">
        ElePHPants marked as <strong>Prototype Only</strong> are for reference and cannot be added to your herd or traded with other users as they were never mass produced.
    </p>
    <div class="row">
        @foreach($elephpants as $key => $elephpant)
            @include('elephpant._single_box', compact('elephpant'))
        @endforeach
    </div>
</div>
@endsection
