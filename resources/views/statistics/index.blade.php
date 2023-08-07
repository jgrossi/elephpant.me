@extends('layouts.app')

@section('content')
    <div class="jumbotron jumbotron-fluid text-center">
        <h1>Statistics</h1>
        <p class="lead">
            Here you can find statistics about elePHPants.<br />
            <strong>{{ $nbUsersWithElephpant }}</strong> users out of <strong>{{ $nbUsers }}</strong> registered have at least one elePHPant.
        </p>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="mb-3">
                </div>
                <div class="card">
                    <div class="card-header">
                        <strong>Ownership of elePHPants</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered table-responsive-sm mb-0">
                            <thead>
                            <tr>
                                <th scope="col" width="7%" class="text-center align-middle">#</th>
                                <th scope="col" width="48%" class=" align-middle">Name</th>
                                <th scope="col" width="15%" class="text-center align-middle">Ownership*</th>
                                <th scope="col" width="15%" class="text-center align-middle">Users</th>
                                <th scope="col" width="15%" class="text-center align-middle">Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($elephpants as $key => $elephpant)
                                <tr>
                                    <td class="text-center align-middle">{{ $key + 1 }}</td>
                                    <td>
                                        <span class="font-weight-bold">{{ $elephpant->name }}</span>
                                        <span>- {{ $elephpant->description }}</span>
                                    </td>
                                    <td class="text-center align-middle">{{ round((($elephpant->nbElephpant/$nbUsersWithElephpant) * 100), 2) }}%</td>
                                    <td class="text-center align-middle">{{ $elephpant->nbElephpant }}</td>
                                    <td class="text-center align-middle">{{ (int)$elephpant->totalElephpant }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        *User with at least 1 elePHPant in herd.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
