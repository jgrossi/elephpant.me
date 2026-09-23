@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-start gap-4 py-6 md:py-8">
            <x-user-profile :user="$otherUser" :countries="$countries" name-as-link />
        </div>

        <div class="px-4 sm:px-6 lg:px-8">
            <livewire:conversation-thread :other-user="$otherUser" :date-format="$dateFormat" />
        </div>
    </div>
@endsection
