@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                @foreach($elephpants as $year => $group)
                    <div class="card mb-4">
                        <div class="card-header">{{ $year }}</div>
                        <ul class="list-group list-group-flush">
                            @foreach($group as $elephpant)
                                <li class="list-group-item">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col-lg-1 pr-3">
                                            <img src="http://placehold.it/100" alt="{{ $elephpant->name }}" class="img-thumbnail img-fluid float-left">
                                        </div>
                                        <div class="col-xl-9 col-lg-8">
                                            <p class="mb-1"><strong>{{ $elephpant->name }}</strong> <em>({{ $elephpant->popular_name }})</em></p>
                                            <p class="mb-0 text-black-50">
                                                Sponsor: {{ $elephpant->sponsor }} <em>(by {{ $elephpant->manufacturer }})</em>
                                            </p>
                                        </div>
                                        <div class="col-xl-2 col-lg-3">
                                            <div class="float-right">
                                                <div class="input-group input-group-lg mb-0">
                                                    <div class="input-group-prepend">
                                                        <button class="btn btn-outline-secondary" type="button">-</button>
                                                    </div>
                                                    <input type="text" class="form-control text-center" placeholder="0">
                                                    <div class="input-group-append" id="button-addon4">
                                                        <button class="btn btn-outline-secondary" type="button">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
