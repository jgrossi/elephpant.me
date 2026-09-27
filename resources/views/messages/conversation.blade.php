@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-start justify-between gap-4 py-6 md:py-8">
            <x-user-profile :user="$otherUser" :countries="$countries" name-as-link />

            <form method="POST" action="{{ route('messages.conversations.destroy', $otherUser->username) }}" onsubmit="return confirm('Delete this conversation?')">
                @csrf
                @method('DELETE')
                <flux:button type="submit" variant="danger" icon="trash">
                    Delete conversation
                </flux:button>
            </form>
        </div>

        <div class="px-4 sm:px-6 lg:px-8">
            <livewire:conversation-thread :other-user="$otherUser" :date-format="$dateFormat" />
        </div>
    </div>
@endsection
