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
                                <th scope="col" width="7%" class="text-center align-middle">Rank</th>
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
                                    <td class="align-middle">
                                        <span href="javascript:void(0)" data-toggle="popover" data-content="<img src='{{ asset('storage/elephpants/' . $elephpant->image) }}' width='150'/>" data-placement="left" data-trigger="hover">
                                            <img src="{{ asset('storage/elephpants/' . $elephpant->image) }}" width="50" alt="" class="img-thumbnail rounded img-fluid mr-3">
                                        </span>
                                        @if (auth()->check())
                                            @if ($currentUserElephpants->contains($elephpant->id))
                                            <span class="mr-2 text-success lead">✓</span>
                                            @else
                                            <span class="mr-2 text-danger lead">x</span>
                                            @endif
                                        @endif
                                        <span class="font-weight-bold">{{ $elephpant->name }}</span>
                                        <span>- {{ $elephpant->description }}</span>
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ round((($elephpant->nbElephpant/$nbUsersWithElephpant) * 100), 2) }}%
                                    </td>
                                    <td class="text-center align-middle">{{ $elephpant->nbElephpant }}</td>
                                    <td class="text-center align-middle">{{ (int)$elephpant->totalElephpant }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <p class="mb-0">
                            *User with at least 1 elePHPant in herd.
                        </p>
                        @if (auth()->check())
                        <p class="mb-0">
                            <span class="mr-2 text-success">✓</span> = ElePHPant exists in your collection
                        </p>
                        <p class="mb-0">
                            <span class="mr-2 text-danger">x</span> = ElePHPant does not exist in your collection
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
