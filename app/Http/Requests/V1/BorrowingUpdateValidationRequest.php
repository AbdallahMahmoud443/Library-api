<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class BorrowingUpdateValidationRequest extends FormRequest
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
            "status" => "required|in:returned,overdue",
            "member_id" => "required|integer|exists:members,id",
            "book_id" => "required|integer|exists:books,id",
            "borrow_date" => "required|date",
            "due_date" => "nullable|date",
            "returned_date" => "nullable|date"
        ];
    }
}
