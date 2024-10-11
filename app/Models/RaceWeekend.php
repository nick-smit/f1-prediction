<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\RaceWeekendFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Stringable;
use Override;

/**
 *
 *
 * @property int $id
 * @property Carbon $start_date
 * @property string $name
 * @property string $stats_f1_name
 * @property string $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, RaceSession> $raceSessions
 * @property-read int|null $race_sessions_count
 * @method static RaceWeekendFactory factory($count = null, $state = [])
 * @method static Builder|RaceWeekend newModelQuery()
 * @method static Builder|RaceWeekend newQuery()
 * @method static Builder|RaceWeekend query()
 * @method static Builder|RaceWeekend whereCreatedAt($value)
 * @method static Builder|RaceWeekend whereId($value)
 * @method static Builder|RaceWeekend whereName($value)
 * @method static Builder|RaceWeekend whereSlug($value)
 * @method static Builder|RaceWeekend whereStartDate($value)
 * @method static Builder|RaceWeekend whereStatsF1Name($value)
 * @method static Builder|RaceWeekend whereUpdatedAt($value)
 * @mixin Model
 */
class RaceWeekend extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'stats_f1_name',
    ];

    #[Override]
    protected static function boot()
    {
        parent::boot();

        static::saving(static function (RaceWeekend $raceWeekend): void {
            $raceWeekend->slug = (new Stringable($raceWeekend->start_date->year . '-' . $raceWeekend->name))
                ->slug();
        });
    }

    public function raceSessions(): HasMany
    {
        return $this->hasMany(RaceSession::class);
    }

    public function getPrevious(): ?RaceWeekend
    {
        return RaceWeekend::query()
            ->whereDate('start_date', '<', $this->start_date)
            ->orderByDesc('start_date')
            ->first();
    }

    public function getNext(): ?RaceWeekend
    {
        return RaceWeekend::query()
            ->whereDate('start_date', '>', $this->start_date)
            ->orderBy('start_date')
            ->first();
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
        ];
    }
}
