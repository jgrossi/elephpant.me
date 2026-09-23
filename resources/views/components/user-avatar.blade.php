@props([
    'user',
    'size' => 'lg',
    'avatarClass' => '',
])
<flux:avatar
    size="{{ $size }}"
    circle
    class="{{ $avatarClass }}"
    name="{{ $user->name }}"
    color="auto"
    :color:seed="$user->id"
    :src="$user->hasAvatarImage() ? $user->avatar() : null"
    alt="{{ $user->name }}"
/>
