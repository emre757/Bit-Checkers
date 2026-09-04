<?php

namespace App\Http\Requests;

use App\Domain\Checkers\Board;
use App\Domain\Checkers\ValueObjects\Position;
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
        $bounds = 'between:0,'.(Board::SIZE - 1);

        return [
            'from' => ['required', 'array', 'array:row,column'],
            'from.column' => ['required', 'integer', $bounds],
            'from.row' => ['required', 'integer', $bounds],

            'destination' => ['required', 'array', 'array:row,column'],
            'destination.column' => ['required', 'integer', $bounds],
            'destination.row' => ['required', 'integer', $bounds],
        ];
    }

    public function fromPosition(): Position
    {
        return new Position(
            row: $this->integer('from.row'),
            column: $this->integer('from.column'),
        );
    }

    public function destinationPosition(): Position
    {
        return new Position(
            row: $this->integer('destination.row'),
            column: $this->integer('destination.column'),
        );
    }
}
