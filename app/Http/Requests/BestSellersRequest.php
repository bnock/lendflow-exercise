<?php

namespace App\Http\Requests;

use App\Rules\ISBN;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * TODO: Use the `intervention/validation` package for ISBN validation.  It has advanced features like checksum.
 */
class BestSellersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'author' => 'string|sometimes|nullable',
            'isbn' => 'array|sometimes|nullable',
            'isbn.*' => new ISBN(),
            'title' => 'string|sometimes|nullable',
            'offset' => 'integer|sometimes|nullable|multiple_of:20',
        ];
    }
}
