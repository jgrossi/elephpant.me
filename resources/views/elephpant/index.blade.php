@extends('layouts.app')

@section('content')
<div class="jumbotron jumbotron-fluid text-center">
    <h1>Species</h1>
    <p class="lead mb-0">
        Here you can find all existent species. There are a total of <strong>{{ count($elephpants) }} species</strong> collected.<br>
        <span class="d-block mt-3">
            <a href="{{ route('herds.edit') }}" class="btn btn-outline-secondary">Go to "My Herd" page</a>
        </span>
    </p>
</div>
<div class="container">
    <div class="row">
        @foreach($elephpants as $key => $elephpant)
            @include('elephpant._single_box', compact('elephpant'))
        @endforeach
    </div>
</div>
@endsection
