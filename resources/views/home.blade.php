@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="text-center mb-4">
                <h1>Welcome!</h1>
                <p class="lead">Here is the right place for your elePHPants collection!</p>
                @include('_status')
                @guest
                    <p class="mt-4 mb-4">
                        <a href="#" class="btn btn-primary">Register and add your herd</a>
                        <a href="#" class="btn btn-outline-secondary">Login to your account</a>
                    </p>
                @endguest
                <div>

                </div>
                <div class="mb-4">
                    <img src="{{ asset('img/collection.jpg') }}" alt="ElePHPants Collection" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
