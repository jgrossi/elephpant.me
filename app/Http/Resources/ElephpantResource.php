<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ElephpantResource extends JsonResource
{
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
