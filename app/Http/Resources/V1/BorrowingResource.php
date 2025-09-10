<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            "book" => $this->when($this->relationLoaded('book'), function () {

                return new BookResource($this->resource->book);
            }),
            "member" => $this->when($this->relationLoaded('member'), function () {

                return new MemberResource($this->resource->member);
            }),
            "status" => $this->resource->status,
            "borrow_data" => $this->resource->borrow_date,
            "due_data" => $this->resource->due_date,
            "returned_data" => $this->resource->returned_date,
        ];
    }
}
