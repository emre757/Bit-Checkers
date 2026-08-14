<?php

namespace App\Models;

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

    public function casts(): array
    {
        return [
            'board' => 'array',
            'forced_capture_from' => 'array',
        ];
    }
}
