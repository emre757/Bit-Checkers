<?php

namespace App\Models;

use App\Domain\Checkers\Enums\BoardStatusType;
use App\Domain\Checkers\Enums\ColorType;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'current_player',
        'status',
        'winner',
        'board',
        'forced_capture_from',
    ];

    protected function casts(): array
    {
        return [
            'current_player' => ColorType::class,
            'status' => BoardStatusType::class,
            'winner' => ColorType::class,
            'board' => 'array',
            'forced_capture_from' => 'array',
        ];
    }
}
