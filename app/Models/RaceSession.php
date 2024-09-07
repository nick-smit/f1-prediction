<?php

declare(strict_types=1);

namespace App\Models;

use App\GrandPrixGuessr\Session\SessionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $id
 * @property int $race_weekend_id
 * @property \Illuminate\Support\Carbon $session_start
 * @property SessionType $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RaceWeekend $raceWeekend
 * @method static \Database\Factories\RaceSessionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|RaceSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RaceSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RaceSession query()
 * @method static \Illuminate\Database\Eloquent\Builder|RaceSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RaceSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RaceSession whereRaceWeekendId($value)
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

    public function raceWeekend(): BelongsTo
    {
        return $this->belongsTo(RaceWeekend::class);
    }

    #[\Override]
    protected function casts() : array
    {
        return [
            'session_start' => 'datetime',
            'session_end' => 'datetime',
            'type' => SessionType::class,
        ];
    }
}
