@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="text-center mb-4">
                    <h1>Upload your elePHPoto!</h1>
                    <p class="lead">Show the world your elePHPants.</p>
                </div>
                <form action="{{ route('photos.store') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="url">Image URL</label>
                        <input type="text" class="form-control" id="url" name="url" placeholder="https://i.imgur.com/h0bMonH.jpg">
                        <small class="form-text text-muted">Upload your photo to somewhere first.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Save photo</button>
                </form>
            </div>
        </div>
    </div>
@endsection
