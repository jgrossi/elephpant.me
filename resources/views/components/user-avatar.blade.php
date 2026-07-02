@props([
    'user',
    'avatarClass' => '',
])
@if($user->hasAvatarImage())
    <flux:avatar size="lg" circle class="{{ $avatarClass }}" src="{{ $user->avatar() }}" alt="{{ $user->name }}" />
@else
    <flux:avatar size="lg" circle class="{{ $avatarClass }}" name="{{ $user->name }}" color="auto" :color:seed="$user->id" />
@endif
