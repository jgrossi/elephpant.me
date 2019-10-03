@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="text-center mb-4">
                    <h1>Matches</h1>
                    <p class="lead">Looking for new elePHPants? Here's yours you can trade.</p>
                </div>
{{--                @foreach($trades as $trade)--}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="float-left mr-3">
                                <img src="http://placehold.it/100" width="50" alt="" class="img-thumbnail rounded-circle img-fluid">
                            </div>
                            <p class="mb-0"><strong>Junior Grossi</strong> <a href="#">@username</a></p>
                            <p class="mb-0">Country: CountryName</p>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-start">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-header">
                                            What user has:
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <div class="float-left mr-3">
                                                    <img src="http://placehold.it/100" width="50" alt="" class="img-thumbnail rounded img-fluid">
                                                </div>
                                                <p class="mb-0"><strong>Sonny</strong> <em>(Sunshine PHP Zend)</em></p>
                                                <p class="mb-0">By SunshinePHP on 2015</p>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="float-left mr-3">
                                                    <img src="http://placehold.it/100" width="50" alt="" class="img-thumbnail rounded img-fluid">
                                                </div>
                                                <p class="mb-0"><strong>Sonny</strong> <em>(Sunshine PHP Zend)</em></p>
                                                <p class="mb-0">By SunshinePHP on 2015</p>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="float-left mr-3">
                                                    <img src="http://placehold.it/100" width="50" alt="" class="img-thumbnail rounded img-fluid">
                                                </div>
                                                <p class="mb-0"><strong>Sonny</strong> <em>(Sunshine PHP Zend)</em></p>
                                                <p class="mb-0">By SunshinePHP on 2015</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card">
                                        <div class="card-header">
                                            What you have:
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <div class="float-left mr-3">
                                                    <img src="http://placehold.it/100" width="50" alt="" class="img-thumbnail rounded img-fluid">
                                                </div>
                                                <p class="mb-0"><strong>Sonny</strong> <em>(Sunshine PHP Zend)</em></p>
                                                <p class="mb-0">By SunshinePHP on 2015</p>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="float-left mr-3">
                                                    <img src="http://placehold.it/100" width="50" alt="" class="img-thumbnail rounded img-fluid">
                                                </div>
                                                <p class="mb-0"><strong>Sonny</strong> <em>(Sunshine PHP Zend)</em></p>
                                                <p class="mb-0">By SunshinePHP on 2015</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
{{--                @endforeach--}}
            </div>
        </div>
    </div>
@endsection
