<footer class="site-footer relative left-1/2 -translate-x-1/2 w-screen max-w-[100vw] overflow-hidden border-t border-white/10 bg-gradient-to-br from-[#2e2682] via-violet-500 to-[#5848dc] text-violet-100 dark:from-[#120f24] dark:via-[#1e1848] dark:to-[#352a8a]">
    <div class="relative mx-auto max-w-6xl px-6 py-12 md:py-14">
        <div class="grid gap-10 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,1.9fr)] lg:gap-16">
            <div>
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('img/elephpant.svg') }}" alt="" width="32" height="25" class="opacity-90" aria-hidden="true">
                    <span class="text-lg font-semibold text-white">ElePHPant.me</span>
                </div>
                <p class="mt-4 max-w-md text-sm leading-relaxed text-violet-200/90">
                    An open-source home for PHP&rsquo;s beloved mascot collectors. Catalog your herd, compare rankings,
                    and find people to trade with at conferences and beyond.
                </p>
                <div class="mt-6">
                    <a
                        href="https://github.com/jgrossi/elephpant.me"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-[#2e2682] shadow-sm transition hover:bg-violet-50"
                    >
                        <flux:icon icon="code-bracket-square" variant="mini" class="size-4" />
                        View on GitHub
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8 sm:grid-cols-4">
                <div>
                    <h2 class="text-sm font-semibold text-white">Explore</h2>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" wire:navigate class="text-violet-200/85 transition hover:text-white">Home</a></li>
                        <li><a href="{{ route('elephpants.index') }}" wire:navigate class="text-violet-200/85 transition hover:text-white">Species</a></li>
                        <li><a href="{{ route('rankings.index') }}" wire:navigate class="text-violet-200/85 transition hover:text-white">Ranking</a></li>
                        <li><a href="{{ route('statistics.index') }}" wire:navigate class="text-violet-200/85 transition hover:text-white">Statistics</a></li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-sm font-semibold text-white">Collect</h2>
                    <ul class="mt-3 space-y-2 text-sm">
                        @auth
                            <li><a href="{{ route('herds.edit') }}" wire:navigate class="text-violet-200/85 transition hover:text-white">My herd</a></li>
                            <li><a href="{{ route('trades.index') }}" wire:navigate class="text-violet-200/85 transition hover:text-white">Trade area</a></li>
                            <li><a href="{{ route('profile.edit') }}" wire:navigate class="text-violet-200/85 transition hover:text-white">Profile</a></li>
                        @else
                            <li><a href="{{ route('login') }}" wire:navigate class="text-violet-200/85 transition hover:text-white">Log in</a></li>
                            @if (Route::has('register'))
                                <li><a href="{{ route('register') }}" wire:navigate class="text-violet-200/85 transition hover:text-white">Register</a></li>
                            @endif
                        @endauth
                    </ul>
                </div>

                <div>
                    <h2 class="text-sm font-semibold text-white">Project</h2>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li><a href="https://github.com/jgrossi/elephpant.me" target="_blank" rel="noopener noreferrer" class="text-violet-200/85 transition hover:text-white">Source code</a></li>
                        <li><a href="https://github.com/jgrossi/elephpant.me/graphs/contributors" target="_blank" rel="noopener noreferrer" class="text-violet-200/85 transition hover:text-white">Contributors</a></li>
                        <li><a href="https://github.com/jgrossi/elephpant.me/issues" target="_blank" rel="noopener noreferrer" class="text-violet-200/85 transition hover:text-white">Report an issue</a></li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-sm font-semibold text-white">Community</h2>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li><a href="https://www.php.net" target="_blank" rel="noopener noreferrer" class="text-violet-200/85 transition hover:text-white">PHP.net</a></li>
                        <li><a href="https://github.com/jgrossi/elephpant.me/blob/master/README.md" target="_blank" rel="noopener noreferrer" class="text-violet-200/85 transition hover:text-white">About this site</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-12 border-t border-white/10 pt-8 text-sm text-violet-200/75">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p>Copyright &copy; {{ date('Y') }} ElePHPant.me contributors.</p>
                <p>
                    <a href="https://www.creoline.com/en" target="_blank" class="inline-flex items-center opacity-60 transition hover:opacity-100">
                        Hosting proudly sponsored by&nbsp;
                        <img src="{{ asset('img/creoline-logo-white.svg') }}" alt="Creoline" class="h-5 h-auto">
                    </a>
                </p>
                <p>
                    Made with <span aria-hidden="true">&hearts;</span><span class="sr-only">love</span>
                    by
                    <a href="https://twitter.com/junior_grossi" target="_blank" rel="noopener noreferrer" class="text-violet-100 underline-offset-2 hover:text-white hover:underline">Junior Grossi</a>,
                    <a href="https://www.linkedin.com/in/igorduarte17/" target="_blank" rel="noopener noreferrer" class="text-violet-100 underline-offset-2 hover:text-white hover:underline">Igor Duarte</a>,
                    and the community.
                </p>
            </div>
            <div class="mt-4 flex items-center justify-center gap-2 border-t border-white/10 pt-4">

            </div>
        </div>
    </div>
</footer>
