<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HerdStats extends Component
{
    public int $unique = 0;

    public int $total = 0;

    public int $double = 0;

    protected $listeners = ['refreshStats' => 'refresh'];

    public function mount(): void
    {
        $this->refresh();
    }

    public function refresh($stats = null): void
    {
        if (is_array($stats) && isset($stats['unique'], $stats['total'], $stats['double'])) {
            $this->unique = (int) $stats['unique'];
            $this->total = (int) $stats['total'];
            $this->double = (int) $stats['double'];

            return;
        }

        $userElephpants = Auth::user()->elephpantsWithQuantity()->toArray();
        $this->unique = count($userElephpants);
        $this->total = array_sum($userElephpants);
        $this->double = $this->total - $this->unique;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.herd-stats');
    }
}
