<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MovePieceRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'from' => ['required', 'array', 'min:1'],
            'from.column' => ['required', 'integer', 'between:0,9'],
            'from.row' => ['required', 'integer', 'between:0,9'],

            'path' => ['required', 'array', 'min:1'],
            'path.*.column' => ['required', 'integer', 'between:0,9'],
            'path.*.row' => ['required', 'integer', 'between:0,9'],
        ];
    }
}
