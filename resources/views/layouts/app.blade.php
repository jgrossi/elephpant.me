<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-149461366-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-149461366-1');
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/site.webmanifest">
    <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ElePHPant.me | The best place for your elePHPant collection</title>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand text-light" href="{{ url('/') }}">
                    <img src="{{ asset('img/elephpant.svg') }}" class="logo" alt="Elephant logo by Freepik"/>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item {{ request()->routeIs('elephpants.index') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('elephpants.index') }}">{{ __('Species') }}</a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('herds.edit') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('herds.edit') }}">{{ __('My Herd') }}</a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('rankings.index') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('rankings.index') }}">{{ __('Ranking') }}</a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('trades.index') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('trades.index') }}">{{ __('Trade Area') }}</a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('statistics.index') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('statistics.index') }}">{{ __('Statistics') }}</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        {{ __('Profile') }}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="pb-3">
            @yield('content')
        </main>

        <footer class="mt-4 text-center py-4 bg-white border-top">
            <p class="mb-2">
                Made with ❤️ by <a href="http://twitter.com/junior_grossi" target="_blank">Junior Grossi</a>, <a href="http://twitter.com/IgorSantoos17" target="_blank">Igor Santos</a> and <a href="https://github.com/jgrossi/elephpant.me/graphs/contributors" target="_blank">contributors</a>.
                <span class="d-block d-md-inline">Contribute to this project on <a href="http://github.com/jgrossi/elephpant.me">GitHub</a>.</span>
            </p>
        </footer>
    </div>
</body>
</html>
