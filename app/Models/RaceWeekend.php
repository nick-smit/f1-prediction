<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $start_date
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RaceSession> $raceSessions
 * @property-read int|null $race_sessions_count
 * @method static \Database\Factories\RaceWeekendFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|RaceWeekend newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RaceWeekend newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RaceWeekend query()
 * @method static \Illuminate\Database\Eloquent\Builder|RaceWeekend whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RaceWeekend whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RaceWeekend whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RaceWeekend whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RaceWeekend whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Model
 */
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
