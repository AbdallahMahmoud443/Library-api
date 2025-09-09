<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "title" => $this->resource->title,
            "isbn" => $this->resource->isbn,
            "genre" => $this->resource->genre,
            "cover_image" => $this->resource->cover_image,
            "description" => $this->resource->description,
            "total_copies" => $this->resource->total_copies,
            "available_copies" => $this->resource->available_copies,
            "price" => $this->resource->price,
            "status" => $this->resource->status,
            "published_at" => $this->resource->published_at,
            "author" => $this->whenLoaded('author', function () {
                return new AuthorResource($this->resource->author);
            }),
        ];
    }
}
