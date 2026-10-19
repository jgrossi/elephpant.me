<?php

namespace App\Livewire;

use Flux\Flux;
use Livewire\Component;

class ApiTokens extends Component
{
    public string $name = '';

    public ?string $plainTextToken = null;

    public function create(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $this->plainTextToken = auth()->user()->createToken($this->name)->plainTextToken;

        $this->reset('name');

        Flux::modal('create-token')->close();
    }

    public function revoke(int $tokenId): void
    {
        auth()->user()->tokens()->whereKey($tokenId)->delete();
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.api-tokens', [
            'tokens' => auth()->user()->tokens()->latest()->get(),
        ]);
    }
}
