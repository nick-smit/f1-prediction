<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
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
 * @property-read \App\Models\Driver $driver
 * @property-read \App\Models\Team $team
 * @method static Builder|DriverContract active()
 * @method static \Database\Factories\DriverContractFactory factory($count = null, $state = [])
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
 * @mixin \Illuminate\Database\Eloquent\Model
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

    public function scopeActive(Builder $query): void
    {
        $query->whereDate('start_date', '<=', Carbon::now())
            ->where(function (Builder $query): void {
                $query->whereDate('end_date', '>', Carbon::now())
                    ->orWhereNull('end_date');
            });
    }
}
