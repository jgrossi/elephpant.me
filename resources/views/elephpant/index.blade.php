@extends('layouts.app')

@section('content')
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Species</h1>
                    <p>Here you can find all existent species. There are a total of <strong>{{ count($elephpants) }} species</strong> collected.</p>
                    <div class="actions">
                        <a href="{{ route('herds.edit') }}" class="btn btn-primary">Go to "My Herd" page</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                @foreach($elephpants as $key => $elephpant)
                    @include('elephpant._single_box', compact('elephpant'))
                @endforeach
            </div>
        </div>
    </div>
@endsection
