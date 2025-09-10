<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "name" => $this->resource->name,
            "email" => $this->resource->email,
            "phone" => $this->resource->phone,
            "address" => $this->resource->address,
            "status" => $this->resource->status,
            "membership_date" => $this->resource->membership_date,
            "Total_borrowings" => $this->whenLoaded("borrowings", function ($borrowings) {
                return $borrowings->count();
            }),

            "active_borrow_books_count" => $this->when($this->relationLoaded("activeBorrowings"), function () {
                return $this->activeBorrowings->count();
            }),

            "active_borrowed_books" => $this->when($this->relationLoaded("activeBorrowings"), function () {

                return BookResource::collection($this->activeBorrowings->pluck("book"));
            }),

        ];
    }
}
