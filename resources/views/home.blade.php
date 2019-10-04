@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="text-center mb-4">
                <div>
                    <div class="jumbotron pb-4">
                        <h1 class="display-4">Welcome, collector!</h1>
                        <p class="lead">
                            Here is the right place for your elePHPants.<br>
                            You can add your herd, see ranking (global / per country), and find people to trade.
                        </p>
                        @guest
                            <hr class="my-4">
                            <p>It's free. We need just basic information about your to create your account.</p>
                            <a class="btn btn-primary btn-lg" href="{{ route('register') }}" role="button">Register</a>
                            <a class="btn btn-outline-secondary btn-lg" href="{{ route('login') }}" role="button">Login</a>
                        @endguest
                        <p class="mt-4">
                            <img src="{{ asset('img/collection.jpg') }}" alt="ElePHPants Collection" class="img-fluid">
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
