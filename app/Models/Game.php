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
    ];

    public function casts(): array
    {
        return [
            'board' => 'array',
        ];
    }
}
