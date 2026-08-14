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
            'from' => ['required', 'array', 'array:row,column'],
            'from.column' => ['required', 'integer', 'between:0,9'],
            'from.row' => ['required', 'integer', 'between:0,9'],

            'destination' => ['required', 'array', 'array:row,column'],
            'destination.column' => ['required', 'integer', 'between:0,9'],
            'destination.row' => ['required', 'integer', 'between:0,9'],
        ];
    }
}
