<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\DriverContractFactory;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

/**
 *
 *
 * @property int $id
 * @property int $driver_id
 * @property int $team_id
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Driver $driver
 * @property-read Team $team
 * @method static Builder|DriverContract active(?DateTimeInterface $onDate = null)
 * @method static DriverContractFactory factory($count = null, $state = [])
 * @method static Builder|DriverContract newModelQuery()
 * @method static Builder|DriverContract newQuery()
 * @method static Builder|DriverContract query()
 * @method static Builder|DriverContract whereCreatedAt($value)
 * @method static Builder|DriverContract whereDriverId($value)
 * @method static Builder|DriverContract whereEndDate($value)
 * @method static Builder|DriverContract whereId($value)
 * @method static Builder|DriverContract whereStartDate($value)
 * @method static Builder|DriverContract whereTeamId($value)
 * @method static Builder|DriverContract whereUpdatedAt($value)
 * @mixin Model
 */
class DriverContract extends Model
{
    use HasFactory;

    #[Override]
    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function scopeActive(Builder $query, DateTimeInterface $onDate = null): void
    {
        $query->whereDate('start_date', '<=', $onDate ?? Carbon::now()->toDateTime())
            ->where(function (Builder $query) use ($onDate): void {
                $query->whereDate('end_date', '>', $onDate ?? Carbon::now()->toDateTime())
                    ->orWhereNull('end_date');
            });
    }
}
