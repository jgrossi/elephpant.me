@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center mb-4">
                <h1>Welcome!</h1>
                <p class="lead">Here is the right place for your elePHPants collection!</p>
                @include('_status')
                @guest
                    <p class="mt-4">
                        <a href="#" class="btn btn-primary">Register and add your herd</a>
                        <a href="#" class="btn btn-outline-secondary">Login to your account</a>
                    </p>
                @endguest
                <div class="mt-4 mb-4">
                    @foreach($photos as $photo)
                        <a href="{{ $photo->url }}" target="_blank">
                            <img src="{{ $photo->url }}" width="100" alt="Image" class="img-thumbnail mb-1">
                        </a>
                    @endforeach
                </div>
                <p><a href="{{ route('photos.create') }}" class="btn btn-secondary">Send us your elePHPoto!</a></p>
                <p class="text-muted"><small>All photos are randomly displayed on each request.</small></p>
            </div>
        </div>
    </div>
</div>
@endsection
