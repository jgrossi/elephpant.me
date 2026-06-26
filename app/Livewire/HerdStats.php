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

    public function mount(?int $unique = null, ?int $total = null, ?int $double = null): void
    {
        if ($unique !== null && $total !== null && $double !== null) {
            $this->unique = $unique;
            $this->total = $total;
            $this->double = $double;

            return;
        }

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
