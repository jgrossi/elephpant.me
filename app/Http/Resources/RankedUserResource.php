<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class RankedUserResource extends JsonResource
{
    #[\Override]
    public function toArray(Request $request): array
    {
        $total = (int) $this->elephpants_total;
        $unique = (int) $this->elephpants_unique;

        return [
            'rank'     => $this->rank,
            'username' => $this->username,
            'name'     => $this->name,
            'country'  => $this->country_code,
            'stats'    => [
                'total'  => $total,
                'unique' => $unique,
                'spare'  => $total - $unique,
            ],
            'updated_at' => $this->last_update?->toIso8601String(),
            'herd_url'   => route('herds.show', $this->username),
        ];
    }
}
