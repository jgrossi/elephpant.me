<?php

namespace App\Livewire;

use App\Elephpant;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HerdCounter extends Component
{
    public int $elephpantId = 0;

    public int $quantity = 0;

    public function mount(int $elephpantId, int $quantity = 0): void
    {
        $this->elephpantId = $elephpantId;
        $this->quantity = max(0, $quantity);
    }

    public function increment(): void
    {
        $this->quantity++;
        $this->save();
    }

    public function decrement(): void
    {
        if ($this->quantity > 0) {
            $this->quantity--;
            $this->save();
        }
    }

    public function updatedQuantity(): void
    {
        $this->save();
    }

    private function save(): void
    {
        $elephpant = Elephpant::findOrFail($this->elephpantId);
        Auth::user()->adopt($elephpant, $this->quantity);

        $userElephpants = Auth::user()->elephpantsWithQuantity()->toArray();
        $unique = count($userElephpants);
        $total = array_sum($userElephpants);
        $this->dispatch('refreshStats', stats: [
            'unique'         => $unique,
            'total'          => $total,
            'double'         => $total - $unique,
            'userElephpants' => $userElephpants,
        ]);
    }

    public function placeholder(): \Illuminate\Contracts\View\View
    {
        return view('livewire.placeholders.herd-counter-skeleton');
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.herd-counter');
    }
}
