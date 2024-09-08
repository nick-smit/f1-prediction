<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RaceWeekend extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
    ];

    public function raceSessions(): HasMany
    {
        return $this->hasMany(RaceSession::class);
    }

    #[\Override]
    protected function casts() : array
    {
        return [
            'start_date' => 'datetime',
        ];
    }
}
