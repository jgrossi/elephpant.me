@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="text-center mb-4">
                    <h1>My Herd</h1>
                    <p class="lead">Which (and how many) elePHPants do you have?</p>
                    <div class="alert alert-info">
                        You have <strong>{{ count($userElephpants) }} unique</strong> elePHPants, but a <strong>total of {{ $count = array_sum($userElephpants) }}</strong> ones.<br>
                        @if($count > 0)
                            You are ranking <strong>#3 in Brazil</strong> and <strong>#24 worldwide</strong>.
                        @endif
                    </div>
                </div>
                @foreach($elephpants as $year => $group)
                    <div class="card mb-4 herd-card">
                        <div class="card-header"><strong>{{ $year }}</strong></div>
                        <ul class="list-group list-group-flush">
                            @foreach($group as $key => $elephpant)
                                <li class="list-group-item
                                    {{ $key % 2 === 0 ? 'bg-white' : 'bg-light' }}
                                    {{ isset($userElephpants[$elephpant->id]) ? 'has-elephpants' : '' }}
                                    ">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col-lg-1 pr-3">
                                            <img src="{{ asset('elephpants/' . $elephpant->image) }}" alt="{{ $elephpant->name }}" class="img-thumbnail img-fluid float-left">
                                        </div>
                                        <div class="col-xl-9 col-lg-8">
                                            <p class="mb-1"><strong>{{ $elephpant->name }}</strong> - {{ $elephpant->description }}</p>
                                            <p class="mb-0 text-black-50">
                                                Sponsor: {{ $elephpant->sponsor }}
                                            </p>
                                        </div>
                                        <div class="col-xl-2 col-lg-3">
                                            <div class="float-right">
                                                <div class="input-group input-group-lg mb-0">
                                                    <div class="input-group-prepend">
                                                        <button class="btn btn-outline-secondary" type="button">-</button>
                                                    </div>
                                                    <input type="text" class="form-control text-center" placeholder="0" value="{{ $userElephpants[$elephpant->id] ?? 0 }}">
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
