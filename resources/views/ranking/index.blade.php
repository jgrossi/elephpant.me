@extends('layouts.app')

@section('content')
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Ranking</h1>
                    <p>Here you can find the top 50 collectors in the PHP community.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="content ranking">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <form action="">
                        <div class="form-group">
                            <select name="country" id="country" class="custom-select mr-2">
                                <option value="">-- Global Ranking --</option>
                                @foreach($countries as $code => $current)
                                    <option value="{{ $code }}" {{ $country === $code ? 'selected' : '' }}>{{
                                        $current->get('name') }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">Change</button>
                        </div>
                        @if($country && !$countries->has($country))
                            <div class="alert alert-danger">Ops! This country is not in our records yet.</div>
                        @endif
                    </form>
                </div>

                <div class="col-md-12">
                    <div class="table-title">
                        @if($country && $countries->get($country))
                            <strong>{{ $countries->get($country)->get('name') }}</strong>
                            (<a href="{{ route('rankings.index') }}">Back to Global Ranking</a>)
                        @else
                            <strong>Global</strong>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    @if(count($users))
                        <table class="table table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col" width="7%" class="text-center">#</th>
                                <th scope="col" width="30%">Name</th>
                                <th scope="col">Country</th>
                                <th scope="col" width="10%" class="text-center">Unique</th>
                                <th scope="col" width="10%" class="text-center">Total</th>
                                <th scope="col" width="12%" class="text-center">Updated</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $key => $user)
                                <tr>
                                    <td scope="row" class="text-center">{{ $key + 1 }}</td>
                                    <td>
                                        <a href="{{ route('herds.show', $user->username) }}">{{ $user->name }}</a>
                                    </td>
                                    <td>
                                        <a href="?{{ http_build_query(['country' => $user->country_code]) }}">
                                            @if($flag = $countries->get($user->country_code)->get('flag'))
                                                <span class="mr-1">{!! $flag !!}</span>
                                            @endif
                                            {{ $countries->get($user->country_code)->get('name') }}
                                        </a>
                                    </td>
                                    <td class="text-center">{{ $user->elephpants_unique }}</td>
                                    <td class="text-center">{{ $user->elephpants_total }}</td>
                                    <td class="text-center">{{ $user->last_update->diffForHumans(null, \Carbon\CarbonInterface::DIFF_ABSOLUTE) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info mb-0">
                            No users registered yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
