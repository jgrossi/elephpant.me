@extends('layouts.app')

@section('content')
    <div class="jumbotron jumbotron-fluid text-center">
        <h1>Trade Area</h1>
        <p class="lead">Looking for new elePHPants? Take a look on these possibilities.</p>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="mb-3">
                    <div class="mb-3 text-center">
                        <form action="">
                            <select name="country" id="country" class="custom-select w-50 mr-1">
                                <option value="">-- All Traders --</option>
                                @foreach($countries as $code => $current)
                                    <option value="{{ $code }}" {{ $country === $code ? 'selected' : '' }}>{{ $current->get('name') }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">Change</button>
                            <a href="?country={{ auth()->user()->country_code }}" class="btn btn-success">My Country</a>
                        </form>
                    </div>
                    @if($country && !$countries->has($country))
                        <div class="alert alert-danger">Ops! This country is not in our records yet.</div>
                    @endif
                </div>
                @if(!$users)
                    <div class="alert alert-info">
                        You don't have any double elePHPant to trade yet.
                    </div>
                @elseif(count($users))
                    <div class="alert alert-info mb-3">
                        Found <strong>{{ $users->total() }} {{ \Illuminate\Support\Str::plural('user', $users->total()) }}</strong> that can trade with you.
                    </div>
                    @foreach($users as $user)
                        <div class="card mb-4">
                            <div class="card-header">
                                @include('trade._user')
                            </div>
                            <div class="card-body">
                                @include('trade._possible_deal')
                            </div>
                        </div>
                    @endforeach
                    <div class="d-flex custom-pagination">
                        <div class="mx-auto">{{ $users->appends(request()->query())->links() }}</div>
                    </div>
                @else
                    <div class="alert alert-info">
                        No users found that can trade with you.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
