@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="text-center mb-4">
                <div>
                    <div class="jumbotron pb-4">
                        <h1 class="display-4">Welcome, collector! 🐘</h1>
                        <p class="lead">
                            Here is the right place for your elePHPants.<br>
                            You can add your herd, see ranking (global / per country), and find people to trade.
                        </p>
                        @guest
                            <hr class="my-4">
                            <a class="btn btn-primary btn-lg" href="{{ route('register') }}" role="button">Register</a>
                            <a class="btn btn-outline-secondary btn-lg" href="{{ route('login') }}" role="button">Login</a>
                        @endguest
                    </div>
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
