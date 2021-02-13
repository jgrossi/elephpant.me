@extends('layouts.app')

@section('content')
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Welcome, collector! 🐘</h1>
                    <p>
                        Here is the right place for your elePHPants.<br>
                        You can <a href="{{ route('herds.edit') }}">update your herd</a>, see
                        <a href="{{ route('rankings.index') }}">ranking</a> (global / per country), and
                        <a href="{{ route('trades.index') }}">find people to trade</a>.
                    </p>

                    @guest
                        <hr class="separator">
                        <div class="actions">
                            <a class="btn btn-primary" href="{{ route('register') }}" role="button">Register</a>
                            <a class="btn btn-link" href="{{ route('login') }}">Login</a>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="content-title">
                        <strong>Total of existent species:</strong> {{ count($elephpants) }}
                    </div>
                </div>
                @foreach($elephpants as $key => $elephpant)
                    @include('elephpant._single_box', compact('elephpant'))
                @endforeach
            </div>
        </div>
    </div>
@endsection
