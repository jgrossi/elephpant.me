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

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    @livewireStyles
    @fluxAppearance
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-50 dark:bg-zinc-900 antialiased min-h-screen flex flex-col">
    <div id="app" class="flex flex-col flex-1 min-h-0">
        <flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700 h-14">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:brand href="{{ url('/') }}" logo="{{ asset('img/elephpant.svg') }}" name="ElePHPant.me" class="max-lg:hidden" wire:navigate />
            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item href="{{ route('elephpants.index') }}" wire:navigate>{{ __('Species') }}</flux:navbar.item>
                <flux:navbar.item href="{{ route('herds.edit') }}" wire:navigate>{{ __('My Herd') }}</flux:navbar.item>
                <flux:navbar.item href="{{ route('rankings.index') }}" wire:navigate>{{ __('Ranking') }}</flux:navbar.item>
                <flux:navbar.item href="{{ route('trades.index') }}" wire:navigate>{{ __('Trade Area') }}</flux:navbar.item>
                <flux:navbar.item href="{{ route('statistics.index') }}" wire:navigate>{{ __('Statistics') }}</flux:navbar.item>
            </flux:navbar>
            <flux:spacer />
            <flux:navbar class="me-4">
                @guest
                    <flux:navbar.item href="{{ route('login') }}" wire:navigate>{{ __('Login') }}</flux:navbar.item>
                    @if (Route::has('register'))
                        <flux:navbar.item href="{{ route('register') }}" wire:navigate>{{ __('Register') }}</flux:navbar.item>
                    @endif
                @else
                    <flux:dropdown position="top" align="end">
                        @php $user = Auth::user(); @endphp
                        <flux:profile
                            name="{{ $user->name }}"
                            :avatar="$user->hasAvatarImage() ? $user->avatar() : null"
                            avatar:color="auto"
                            avatar:color:seed="{{ $user->id }}"
                        />
                        <flux:navmenu>
                            <flux:navmenu.item href="{{ route('profile.edit') }}" icon="user" wire:navigate>{{ __('Profile') }}</flux:navmenu.item>
                            <flux:navmenu.separator />
                            <flux:navmenu.item href="#" icon="arrow-right-start-on-rectangle"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </flux:navmenu.item>
                        </flux:navmenu>
                    </flux:dropdown>
                @endguest
            </flux:navbar>
        </flux:header>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>

        <div class="app-body flex flex-1 w-full min-h-0">
            <flux:sidebar sticky collapsible="mobile" class="lg:hidden bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
                <flux:sidebar.header>
                    <flux:sidebar.brand href="{{ url('/') }}" logo="{{ asset('img/elephpant.svg') }}" name="ElePHPant.me" wire:navigate />
                    <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
                </flux:sidebar.header>
                <flux:sidebar.nav>
                    <flux:sidebar.item href="{{ route('elephpants.index') }}" icon="squares-2x2" wire:navigate>{{ __('Species') }}</flux:sidebar.item>
                    <flux:sidebar.item href="{{ route('herds.edit') }}" icon="heart" wire:navigate>{{ __('My Herd') }}</flux:sidebar.item>
                    <flux:sidebar.item href="{{ route('rankings.index') }}" icon="trophy" wire:navigate>{{ __('Ranking') }}</flux:sidebar.item>
                    <flux:sidebar.item href="{{ route('trades.index') }}" icon="arrow-path" wire:navigate>{{ __('Trade Area') }}</flux:sidebar.item>
                    <flux:sidebar.item href="{{ route('statistics.index') }}" icon="chart-bar" wire:navigate>{{ __('Statistics') }}</flux:sidebar.item>
                </flux:sidebar.nav>
                <flux:sidebar.spacer />
                <flux:sidebar.nav>
                    @guest
                        <flux:sidebar.item href="{{ route('login') }}" wire:navigate>{{ __('Login') }}</flux:sidebar.item>
                        @if (Route::has('register'))
                            <flux:sidebar.item href="{{ route('register') }}" wire:navigate>{{ __('Register') }}</flux:sidebar.item>
                        @endif
                    @else
                        <flux:sidebar.item href="{{ route('profile.edit') }}" icon="user" wire:navigate>{{ __('Profile') }}</flux:sidebar.item>
                        <flux:sidebar.item href="{{ route('logout') }}" icon="arrow-right-start-on-rectangle"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </flux:sidebar.item>
                    @endguest
                </flux:sidebar.nav>
            </flux:sidebar>

            <div class="app-content flex-1 min-w-0">
                <main class="app-main">
                    <flux:main container class="pb-8 flex-1">
                        @yield('content')
                    </flux:main>
                </main>
            </div>
        </div>

        <footer class="w-full mt-12 text-center py-6 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400">
            <p class="mb-2">
                Made with ❤️ by <flux:link href="http://twitter.com/junior_grossi" external>Junior Grossi</flux:link>, <flux:link href="https://www.linkedin.com/in/igorduarte17/" external>Igor Duarte</flux:link> and <flux:link href="https://github.com/jgrossi/elephpant.me/graphs/contributors" external>contributors</flux:link>.
                <span class="block md:inline mt-1 md:mt-0">Contribute to this project on <flux:link href="http://github.com/jgrossi/elephpant.me" external>GitHub</flux:link>.</span>
            </p>
        </footer>
    </div>
    @livewireScripts
    @fluxScripts
</body>
</html>
