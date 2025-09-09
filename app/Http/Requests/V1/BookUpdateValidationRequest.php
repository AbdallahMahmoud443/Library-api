<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class BookUpdateValidationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "title" => "string|max:255",
            "isbn" => "string|max:20|unique:books,isbn," . $this->route('book'), // get parameter from route
            "genre" => "string|max:100",
            "cover_image" => "nullable|image|mimes:jpeg,png,jpg,gif|max:1024",
            "description" => "nullable|string",
            "total_copies" => "integer|min:0",
            "available_copies" => "integer|min:0|lte:total_copies",
            "price" => "numeric|min:0",
            "status" => "string|in:active,inactive,",
            "author_id" => "exists:authors,id",
            "published_at" => "date",
        ];
    }
}
