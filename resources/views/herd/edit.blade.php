@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center mb-4">
                    <h1>My Herd</h1>
                    <p class="lead">Which (and how many) elePHPants do you have?</p>
                    <div id="stats">
                        @include('herd._stats')
                    </div>
                </div>
                @foreach($elephpants as $year => $group)
                    <div class="card mb-3 herd-card">
                        <div class="card-header"><strong>{{ $year }}</strong></div>
                        <ul class="list-group list-group-flush">
                            @foreach($group as $key => $elephpant)
                                @include('herd._elephpant')
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
