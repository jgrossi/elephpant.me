@props([
    'user',
    'size' => 'lg',
    'avatarClass' => '',
])
@if($user->hasAvatarImage())
    <flux:avatar size="{{ $size }}" circle class="{{ $avatarClass }}" src="{{ $user->avatar() }}" alt="{{ $user->name }}" />
@else
    <flux:avatar size="{{ $size }}" circle class="{{ $avatarClass }}" name="{{ $user->name }}" color="auto" :color:seed="$user->id" />
@endif
