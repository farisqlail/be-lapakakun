<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'title' => $this->title,
            'duration' => $this->duration,
            'max_user' => $this->max_user,
            'price' => number_format($this->price, 2, '.', ''),
            'logo' => $this->logo ? asset('storage/' . $this->logo) : null,
            'banner' => $this->banner ? asset('storage/' . $this->banner) : null,
            'scheme' => $this->scheme,
            'information' => $this->information,
            'benefit' => $this->benefit,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
        ];
    }
}
