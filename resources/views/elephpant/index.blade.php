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
    @include('partials._search')
    <p class="mb-4">
        ElePHPants marked as <strong>Prototype Only</strong> are for reference and cannot be added to your herd or traded with other users as they were never mass produced.
    </p>
    <div class="row">
        @if($elephpants->count())
            @foreach($elephpants as $key => $elephpant)
                @include('elephpant._single_box', compact('elephpant'))
            @endforeach
        @else
            @include('partials._no_elephpants_found')
        @endif
    </div>
</div>
@endsection
