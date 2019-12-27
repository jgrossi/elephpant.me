@extends('layouts.app')

@section('content')
    <div class="jumbotron jumbotron-fluid text-center">
        <h1>My Herd</h1>
        <p class="lead">Which (and how many) elePHPants do you have?</p>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center mb-4">
                    <div class="alert alert-secondary bg-transparent py-2">
                        Your public herd URL:
                        <code>
                            <a href="{{ route('herds.show', auth()->user()->username) }}" target="_blank">
                                {{ route('herds.show', auth()->user()->username) }}
                            </a>
                        </code>
                    </div>
                    <div id="stats">
                        @include('herd._stats')
                    </div>
                </div>
                @foreach($elephpants as $year => $group)
                    <div class="card mb-3 herd-card">
                        <div class="card-header"><strong>{{ $year }}</strong></div>
                        <ul class="list-group list-group-flush">
                            @foreach($group as $key => $elephpant)
                                <li data-controller="counter"
                                    data-counter-id="{{ $elephpant->id }}"
                                    class="list-group-item {{ $key % 2 === 0 ? 'bg-white' : 'bg-light' }} {{ isset($userElephpants[$elephpant->id]) ? 'has-elephpants' : '' }}">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col-xl-9 col-lg-8">
                                            <div class="float-left mr-3">
                                                <div class="media">
                                                    <a href="javascript:void(0)" data-toggle="popover" data-content="<img src='{{ asset('storage/elephpants/' . $elephpant->image) }}' width='150'/>" data-placement="left" data-trigger="hover">
                                                        <img src="{{ asset('storage/elephpants/' . $elephpant->image) }}" width="50" alt="" class="img-thumbnail rounded img-fluid mr-3">
                                                    </a>
                                                    <div class="media-body">
                                                        <p class="mb-0"><strong>{{ $elephpant->name }}</strong> - {{ $elephpant->description }}</p>
                                                        <p class="mb-0 text-black-50">
                                                            Sponsor: {{ $elephpant->sponsor }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3">
                                            <div class="float-right">
                                                <div class="input-group input-group-lg mb-0 mt-2 mt-md-0">
                                                    <div class="input-group-prepend">
                                                        <button class="btn btn-outline-secondary" type="button" data-action="counter#decrement">-</button>
                                                    </div>
                                                    <input type="text" class="form-control text-center" placeholder="0" value="{{ $userElephpants[$elephpant->id] ?? 0 }}" data-target="counter.quantity" data-action="keyup->counter#save">
                                                    <div class="input-group-append" id="button-addon4">
                                                        <button class="btn btn-outline-secondary" type="button" data-action="counter#increment">+</button>
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
