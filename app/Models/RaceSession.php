<?php

declare(strict_types=1);

namespace App\Models;

use App\GrandPrixGuessr\Session\SessionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Override;

class RaceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_start',
        'session_end',
        'type',
    ];

    #[Override]
    protected function casts(): array
    {
        return [
            'session_start' => 'datetime',
            'session_end' => 'datetime',
            'type' => SessionType::class,
        ];
    }

    public function raceWeekend(): BelongsTo
    {
        return $this->belongsTo(RaceWeekend::class);
    }

    public function sessionResult(): HasOne
    {
        return $this->hasOne(SessionResult::class);
    }

    public function guesses(): HasMany
    {
        return $this->hasMany(Guess::class);
    }
}
