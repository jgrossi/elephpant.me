@extends('layouts.app')

@section('meta_description', 'Collect, compare, and trade PHP\'s beloved mascot. Catalog your herd, climb the rankings, and find collectors to swap with on ElePHPant.me.')

@section('content')
    <section class="home-hero relative left-1/2 -translate-x-1/2 w-screen max-w-[100vw] overflow-hidden border-b border-white/10 bg-gradient-to-br from-[#2e2682] via-violet-500 to-[#5848dc] dark:from-[#120f24] dark:via-[#1e1848] dark:to-[#352a8a]">
        <div class="relative mx-auto max-w-6xl px-6 py-16 md:py-24 lg:py-28">
            <div class="grid items-center gap-12 lg:grid-cols-[1.1fr_0.9fr] lg:gap-16">
                <div class="text-center lg:text-left">
                    <p class="inline-block rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-[0.8125rem] font-medium tracking-wide text-violet-100">
                        Join our Community
                    </p>

                    <flux:heading size="xl" level="1" class="mt-4 text-4xl md:text-5xl lg:text-6xl font-semibold tracking-tight text-white text-balance">
                        Collect, compare, and trade PHP&rsquo;s beloved mascot.
                    </flux:heading>

                    <flux:text class="mt-6 text-lg md:text-xl text-violet-100/90 max-w-2xl mx-auto lg:mx-0 text-pretty">
                        ElePHPants are the plush mascots shared at PHP conferences and by community sponsors.
                        ElePHPant.me helps you catalog your herd, climb the rankings, and find collectors to swap with.
                    </flux:text>

                    <div class="mt-8 flex flex-wrap items-center justify-center gap-3 lg:justify-start">
                        @auth
                            <a href="{{ route('herds.edit') }}" wire:navigate class="inline-flex items-center justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-[#2e2682] shadow-sm transition hover:bg-violet-50">
                                My herd
                            </a>
                        @else
                            <a href="{{ route('register') }}" wire:navigate class="inline-flex items-center justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-[#2e2682] shadow-sm transition hover:bg-violet-50">
                                Start collecting
                            </a>
                            <a href="{{ route('login') }}" wire:navigate class="inline-flex items-center justify-center rounded-lg border border-white/55 bg-transparent px-4 py-2 text-sm font-semibold text-white transition hover:border-white/75 hover:bg-white/10">
                                Log in
                            </a>
                        @endauth
                        <a href="{{ route('elephpants.index') }}" wire:navigate class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-semibold text-violet-100 transition hover:bg-white/10">
                            Browse species
                        </a>
                    </div>

                    <dl class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 max-w-xl mx-auto lg:mx-0">
                        <div class="rounded-xl border border-white/10 bg-white/5 p-3.5">
                            <dt class="text-sm text-violet-200/80 whitespace-nowrap">Total Species</dt>
                            <dd class="mt-1 text-2xl font-semibold tabular-nums text-white">{{ number_format($speciesCount) }}</dd>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-white/5 p-3.5">
                            <dt class="text-sm text-violet-200/80 whitespace-nowrap">Total Herds</dt>
                            <dd class="mt-1 text-2xl font-semibold tabular-nums text-white">{{ number_format($collectorCount) }}</dd>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-white/5 p-3.5 col-span-2 sm:col-span-1">
                            <dt class="text-sm text-violet-200/80 whitespace-nowrap">Total Collected</dt>
                            <dd class="mt-1 text-2xl font-semibold tabular-nums text-white">{{ number_format($collectedTotal) }}</dd>
                        </div>
                    </dl>
                </div>

                <x-featured-elephpants :pool="$featuredElephpantPool" />
            </div>
        </div>
    </section>

    <section class="mt-16 md:mt-20">
        <div class="max-w-3xl">
            <p class="text-sm font-medium uppercase tracking-wider text-violet-700 dark:text-violet-300">What are ElePHPants?</p>
            <flux:heading size="lg" level="2" class="mt-3 text-zinc-900 dark:text-zinc-100">
                More than swag. A shared piece of PHP culture.
            </flux:heading>
            <flux:text class="mt-4 text-base md:text-lg text-zinc-600 dark:text-zinc-300 leading-relaxed">
                The tradition started with Vincent Pontier&rsquo;s blue ElePHPant logo and grew into collectible plush
                mascots handed out at PHP conferences, user groups, and by sponsors around the world. Each design marks
                an event, a company, or a moment in the community. Collectors call their sets <em>herds</em>, and no two
                are exactly alike.
            </flux:text>
        </div>
    </section>

    <section class="mt-16 md:mt-20">
        <div class="max-w-3xl">
            <p class="text-sm font-medium uppercase tracking-wider text-violet-700 dark:text-violet-300">Why use ElePHPant.me?</p>
            <flux:heading size="lg" level="2" class="mt-3 text-zinc-900 dark:text-zinc-100">
                Everything you need to manage a growing collection.
            </flux:heading>
            <flux:text class="mt-4 text-zinc-600 dark:text-zinc-300">
                Whether you picked up your first ElePHPant at a meetup or you&rsquo;re hunting the rarest conference editions,
                the site keeps your collection organised and connected to other collectors.
            </flux:text>
        </div>

        <div class="mt-10 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <flux:card class="flex flex-col p-6 border-violet-500/10 transition duration-200 hover:-translate-y-0.5 hover:border-violet-500/30 hover:shadow-lg hover:shadow-violet-500/10">
                <div class="inline-flex size-10 items-center justify-center rounded-xl bg-violet-500/10 text-violet-700 dark:bg-violet-500/20 dark:text-violet-300">
                    <flux:icon icon="heart" variant="outline" class="size-5" />
                </div>
                <flux:heading size="md" class="mt-4">Track your herd</flux:heading>
                <flux:text class="mt-2 flex-1 text-sm text-zinc-600 dark:text-zinc-400">
                    Log every ElePHPant you own, including duplicates you are ready to trade away.
                </flux:text>
                <flux:link href="{{ route('herds.edit') }}" wire:navigate class="mt-4 inline-block text-sm font-medium text-violet-700 hover:text-violet-800 dark:text-violet-300 dark:hover:text-violet-200">
                    Update my herd &rarr;
                </flux:link>
            </flux:card>

            <flux:card class="flex flex-col p-6 border-violet-500/10 transition duration-200 hover:-translate-y-0.5 hover:border-violet-500/30 hover:shadow-lg hover:shadow-violet-500/10">
                <div class="inline-flex size-10 items-center justify-center rounded-xl bg-violet-500/10 text-violet-700 dark:bg-violet-500/20 dark:text-violet-300">
                    <flux:icon icon="trophy" variant="outline" class="size-5" />
                </div>
                <flux:heading size="md" class="mt-4">Climb the rankings</flux:heading>
                <flux:text class="mt-2 flex-1 text-sm text-zinc-600 dark:text-zinc-400">
                    Compare your collection globally and see how you rank against collectors in your country.
                </flux:text>
                <flux:link href="{{ route('rankings.index') }}" wire:navigate class="mt-4 inline-block text-sm font-medium text-violet-700 hover:text-violet-800 dark:text-violet-300 dark:hover:text-violet-200">
                    View rankings &rarr;
                </flux:link>
            </flux:card>

            <flux:card class="flex flex-col p-6 border-violet-500/10 transition duration-200 hover:-translate-y-0.5 hover:border-violet-500/30 hover:shadow-lg hover:shadow-violet-500/10">
                <div class="inline-flex size-10 items-center justify-center rounded-xl bg-violet-500/10 text-violet-700 dark:bg-violet-500/20 dark:text-violet-300">
                    <flux:icon icon="arrow-path" variant="outline" class="size-5" />
                </div>
                <flux:heading size="md" class="mt-4">Find trade partners</flux:heading>
                <flux:text class="mt-2 flex-1 text-sm text-zinc-600 dark:text-zinc-400">
                    Find collectors with extras of the species you still need to complete your herd.
                </flux:text>
                <flux:link href="{{ route('trades.index') }}" wire:navigate class="mt-4 inline-block text-sm font-medium text-violet-700 hover:text-violet-800 dark:text-violet-300 dark:hover:text-violet-200">
                    Open trade area &rarr;
                </flux:link>
            </flux:card>

            <flux:card class="flex flex-col p-6 border-violet-500/10 transition duration-200 hover:-translate-y-0.5 hover:border-violet-500/30 hover:shadow-lg hover:shadow-violet-500/10">
                <div class="inline-flex size-10 items-center justify-center rounded-xl bg-violet-500/10 text-violet-700 dark:bg-violet-500/20 dark:text-violet-300">
                    <flux:icon icon="chart-bar" variant="outline" class="size-5" />
                </div>
                <flux:heading size="md" class="mt-4">Explore the data</flux:heading>
                <flux:text class="mt-2 flex-1 text-sm text-zinc-600 dark:text-zinc-400">
                    See which species are most popular and which conference editions are hardest to find.
                </flux:text>
                <flux:link href="{{ route('statistics.index') }}" wire:navigate class="mt-4 inline-block text-sm font-medium text-violet-700 hover:text-violet-800 dark:text-violet-300 dark:hover:text-violet-200">
                    View statistics &rarr;
                </flux:link>
            </flux:card>
        </div>
    </section>

    <section id="catalog" class="mt-16 md:mt-24 scroll-mt-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between mb-8">
            <div>
                <p class="text-sm font-medium uppercase tracking-wider text-violet-700 dark:text-violet-300">Species catalog</p>
                <flux:heading size="lg" level="2" class="mt-2 text-zinc-900 dark:text-zinc-100">
                    Latest ElePHPants
                </flux:heading>
                <flux:text class="mt-2 text-zinc-600 dark:text-zinc-400">
                    The {{ number_format(min($catalogPreviewLimit, $speciesCount)) }} newest additions to the catalog.
                    Browse all {{ number_format($speciesCount) }} on the full species page.
                </flux:text>
            </div>
            <flux:button href="{{ route('elephpants.index') }}" variant="primary" wire:navigate class="shrink-0">
                View all species
            </flux:button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($latestElephpants as $elephpant)
                @include('elephpant._single_box', compact('elephpant'))
            @endforeach
        </div>
    </section>
@endsection
