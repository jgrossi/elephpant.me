@extends('layouts.app')

@section('content')
    <div class="jumbotron jumbotron-fluid text-center">
        <h1>Statistics</h1>
        <p class="lead">Here you can find statistics about elephpants.</p>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="mb-3">
                </div>
                <div class="card">
                    <div class="card-header">
                        <strong>Ownership of elephpants</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered mb-0">
                            <thead>
                            <tr>
                                <th scope="col" width="7%" class="text-center">#</th>
                                <th scope="col" width="35%">Name</th>
                                <th scope="col" width="12%" class="text-center">Ownership percent</th>
                                <th scope="col" width="12%" class="text-center">Number</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($elephpants as $key => $elephpant)
                                <tr>
                                    <td class="text-center align-middle">{{ $key + 1 }}</td>
                                    <td>
                                        <span class="font-weight-bold">#{{ $elephpant->id }} - {{ $elephpant->name }}</span><br/>
                                        {{ $elephpant->description }}
                                    </td>
                                    <td class="text-center align-middle">{{ round((($elephpant->nbElephpant/$nbUsers) * 100), 2) }}%</td>
                                    <td class="text-center align-middle">{{ $elephpant->nbElephpant }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
