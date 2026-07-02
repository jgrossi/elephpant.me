@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row items-stretch justify-center gap-4 py-6 md:py-8">
            <div class="flex-1 min-w-0 flex justify-center">
                <x-user-profile :user="$user0" :countries="$countries" />
            </div>

            <div class="flex items-center justify-center px-2">
                <span class="text-sm font-semibold">vs</span>
            </div>

            <div class="flex-1 min-w-0 flex justify-center">
                <x-user-profile :user="$user1" :countries="$countries" />
            </div>
        </div>

        <flux:table>
            <flux:table.columns sticky class="sticky top-0 bg-white dark:bg-zinc-900 z-10">
                <flux:table.column sticky>Image</flux:table.column>
                <flux:table.column sortable>Sponsor</flux:table.column>
                <flux:table.column sortable>Name</flux:table.column>
                <flux:table.column sortable>Description</flux:table.column>
                <flux:table.column sortable>Year</flux:table.column>
                <flux:table.column sortable>{{ $user0->name }}</flux:table.column>
                <flux:table.column sortable>{{ $user1->name }}</flux:table.column>
            </flux:table.columns>

            <tbody id="comparison-table">
            @foreach ($elephpants as $elephpant)
                @php
                    $user0Quantity = (int) ($user0->elephpants->firstWhere('id', $elephpant->id)?->pivot->quantity ?? 0);
                    $user1Quantity = (int) ($user1->elephpants->firstWhere('id', $elephpant->id)?->pivot->quantity ?? 0);
                @endphp
                <flux:table.row :key="$elephpant->id">
                    <flux:table.cell sticky class="bg-white dark:bg-zinc-900">
                        <button
                            type="button"
                            class="block overflow-hidden rounded border border-zinc-200 dark:border-zinc-700 cursor-zoom-in transition hover:opacity-80 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
                            data-elephpant-lightbox
                            data-image="{{ asset('storage/elephpants/'.$elephpant->image) }}"
                            data-alt="{{ $elephpant->name.' – '.$elephpant->description }}"
                            aria-label="{{ __('View larger image of :name', ['name' => $elephpant->name]) }}"
                        >
                            <img
                                src="{{ asset('storage/elephpants/'.$elephpant->image) }}"
                                alt="{{ $elephpant->description }}"
                                width="40"
                                height="40"
                                class="block size-10 object-cover"
                                loading="lazy"
                                decoding="async"
                            >
                        </button>
                    </flux:table.cell>
                    <flux:table.cell>{{ $elephpant->sponsor }}</flux:table.cell>
                    <flux:table.cell>{{ $elephpant->formattedName() }}</flux:table.cell>
                    <flux:table.cell>{{ $elephpant->description }}</flux:table.cell>
                    <flux:table.cell>{{ $elephpant->year }}</flux:table.cell>
                    <flux:table.cell class="whitespace-normal wrap-break-word {{ $user0Quantity === 0 ? 'text-zinc-300! dark:text-zinc-600!' : '' }}">{{ $user0Quantity }}</flux:table.cell>
                    <flux:table.cell class="whitespace-normal wrap-break-word {{ $user1Quantity === 0 ? 'text-zinc-300! dark:text-zinc-600!' : '' }}">{{ $user1Quantity }}</flux:table.cell>
                </flux:table.row>
            @endforeach
            </tbody>
        </flux:table>

        <flux:modal name="elephpant-image" variant="bare" :closable="true" class="w-full max-w-lg">
            <img
                id="elephpant-lightbox-image"
                src=""
                alt=""
                class="mx-auto h-auto max-h-[75vh] w-auto max-w-full rounded-lg object-contain shadow-xl"
            >
            <p id="elephpant-lightbox-caption" class="mt-3 text-center text-sm text-white/90"></p>
        </flux:modal>
    </div>
@endsection
