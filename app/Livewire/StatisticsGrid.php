<?php

namespace App\Livewire;

use App\Elephpant;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Defer;
use Livewire\Component;

#[Defer]
class StatisticsGrid extends Component
{
    public int $nbUsersWithElephpant = 0;

    public function mount(int $nbUsersWithElephpant): void
    {
        $this->nbUsersWithElephpant = $nbUsersWithElephpant;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $elephpants = Elephpant::query()
            ->withCount('users')
            ->withSum('users as copies', 'elephpant_user.quantity')
            ->orderBy('users_count', 'desc')
            ->orderBy('id', 'desc')
            ->orderBy('copies', 'desc')
            ->get();

        $currentUserElephpants = Auth::check()
            ? Auth::user()->elephpants->pluck('id')
            : collect();

        return view('livewire.statistics-grid', [
            'elephpants'            => $elephpants,
            'currentUserElephpants' => $currentUserElephpants,
            'nbUsersWithElephpant'  => $this->nbUsersWithElephpant,
        ]);
    }

    public function placeholder(array $params = []): \Illuminate\Contracts\View\View
    {
        return view('livewire.placeholders.statistics-grid-skeleton', $params);
    }
}
