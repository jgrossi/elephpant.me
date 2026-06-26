<?php

namespace App\Livewire;

use App\User;
use Livewire\Attributes\Defer;
use Livewire\Component;

#[Defer]
class PublicHerdCollection extends Component
{
    public string $username = '';

    public function mount(string $username): void
    {
        $this->username = $username;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $user = User::whereUsername($this->username)->firstOrFail();
        $elephpants = $user->elephpants()
            ->orderBy('year', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->filter(fn ($e): bool => (int) ($e->pivot->quantity ?? 0) > 0);

        return view('livewire.public-herd-collection', [
            'elephpants' => $elephpants,
        ]);
    }

    public function placeholder(array $params = []): \Illuminate\Contracts\View\View
    {
        return view('livewire.placeholders.species-search-skeleton', $params);
    }
}
