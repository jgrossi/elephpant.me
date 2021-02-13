@extends('layouts.app')

@section('content')
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Statistics</h1>
                    <p>
                        Here you can find statistics about elePHPants.<br/>
                        <strong>{{ $nbUsersWithElephpant }}</strong> users out of <strong>{{ $nbUsers }}</strong> registered have at least one elePHPant.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-title">
                        <strong>Ownership of elePHPants</strong>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-responsive-sm">
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
                                <td class="text-center align-middle">{{ round((($elephpant->nbElephpant/$nbUsersWithElephpant) * 100), 2) }}
                                    %
                                </td>
                                <td class="text-center align-middle">{{ $elephpant->nbElephpant }}</td>
                                <td class="text-center align-middle">{{ (int)$elephpant->totalElephpant }}</td>
                            </tr>
                        @endforeach
                        <tr class="table-footer">
                            <td colspan="5">*User with at least 1 elePHPant in herd.</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
