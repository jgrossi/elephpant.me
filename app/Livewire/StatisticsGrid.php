<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $elephpants = DB::table('elephpants')
            ->select(DB::raw('COUNT(elephpant_user.elephpant_id) as nbElephpant, elephpants.id, elephpants.name, elephpants.description, elephpants.image, elephpants.sponsor, elephpants.year, SUM(elephpant_user.quantity) as totalElephpant'))
            ->leftJoin('elephpant_user', 'elephpants.id', '=', 'elephpant_user.elephpant_id')
            ->orderBy('nbElephpant', 'desc')
            ->orderBy('elephpants.id', 'desc')
            ->orderBy('totalElephpant', 'desc')
            ->groupBy('elephpants.id', 'elephpants.name', 'elephpants.description', 'elephpants.image', 'elephpants.sponsor', 'elephpants.year')
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
