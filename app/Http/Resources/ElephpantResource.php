<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Elephpant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Elephpant
 */
class ElephpantResource extends JsonResource
{
    #[\Override]
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'sponsor'     => $this->sponsor,
            'year'        => $this->year,
            'image_url'   => $this->image ? asset('storage/elephpants/'.$this->image) : null,
            'owners'      => $this->whenCounted('users'),
            'url'         => route('api.elephpants.show', $this->id),
        ];
    }
}
