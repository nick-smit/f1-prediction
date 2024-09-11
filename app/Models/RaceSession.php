<?php

declare(strict_types=1);

namespace App\Models;

use App\GrandPrixGuessr\Session\SessionType;
use Database\Factories\RaceSessionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Override;

/**
 *
 *
 * @property int $id
 * @property int $race_weekend_id
 * @property int $guessable
 * @property Carbon $session_start
 * @property Carbon $session_end
 * @property SessionType $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Guess> $guesses
 * @property-read int|null $guesses_count
 * @property-read RaceWeekend $raceWeekend
 * @property-read SessionResult|null $sessionResult
 * @method static RaceSessionFactory factory($count = null, $state = [])
 * @method static Builder|RaceSession newModelQuery()
 * @method static Builder|RaceSession newQuery()
 * @method static Builder|RaceSession query()
 * @method static Builder|RaceSession whereCreatedAt($value)
 * @method static Builder|RaceSession whereGuessable($value)
 * @method static Builder|RaceSession whereId($value)
 * @method static Builder|RaceSession whereRaceWeekendId($value)
 * @method static Builder|RaceSession whereSessionEnd($value)
 * @method static Builder|RaceSession whereSessionStart($value)
 * @method static Builder|RaceSession whereType($value)
 * @method static Builder|RaceSession whereUpdatedAt($value)
 * @mixin Model
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
