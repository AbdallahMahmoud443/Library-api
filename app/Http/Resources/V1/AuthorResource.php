<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->resource->name,
            'bio' => $this->resource->bio,
            'nationality' => $this->resource->nationality,
            "books" => $this->whenLoaded('books', function () {
                return $this->resource->books->count();
            }),
        ];
    }
}
