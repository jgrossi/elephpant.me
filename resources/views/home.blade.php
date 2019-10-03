@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center mb-4">
                <h1>Welcome!</h1>
                <p class="lead">Here is the right place for your elePHPants collection!</p>
                <div class="mt-4 mb-5">
                    @foreach(range(1, 50) as $row)
                        <a href="#">
                            <img src="http://placehold.it/100" width="100" alt="" class="img-thumbnail mb-1">
                        </a>
                    @endforeach
                </div>
                <p><a href="#" class="btn btn-secondary">Send us your elePHPoto!</a></p>
                <p class="text-muted"><small>All photos are randomly displayed on each request.</small></p>
            </div>
        </div>
    </div>
</div>
@endsection
