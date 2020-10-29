@extends('layouts.app')

@section('content')
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Upload your elePHPoto!</h1>
                    <p>Show the world your elePHPants.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <form action="{{ route('photos.store') }}" method="POST">
            {{ csrf_field() }}

            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="form-group">
                            <label for="url">Image URL</label>
                            <input type="text" class="form-control" id="url" name="url" placeholder="https://i.imgur.com/h0bMonH.jpg">
                            <small class="form-text text-muted">Upload your photo to somewhere first.</small>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
