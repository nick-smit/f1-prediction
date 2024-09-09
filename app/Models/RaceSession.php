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

/**
 *
 *
 * @property int $id
 * @property int $race_weekend_id
 * @property int $guessable
 * @property \Illuminate\Support\Carbon $session_start
 * @property \Illuminate\Support\Carbon $session_end
 * @property SessionType $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Guess> $guesses
 * @property-read int|null $guesses_count
 * @property-read \App\Models\RaceWeekend $raceWeekend
 * @property-read \App\Models\SessionResult|null $sessionResult
 * @method static \Database\Factories\RaceSessionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|RaceSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RaceSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RaceSession query()
 * @method static \Illuminate\Database\Eloquent\Builder|RaceSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RaceSession whereGuessable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RaceSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RaceSession whereRaceWeekendId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RaceSession whereSessionEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RaceSession whereSessionStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RaceSession whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RaceSession whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Model
 */
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
