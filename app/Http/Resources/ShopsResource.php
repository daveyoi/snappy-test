<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'shop_name' => $this->name,
            'shop_status' => $this->status,
            'shop_type' => $this->type,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];
    }
}
